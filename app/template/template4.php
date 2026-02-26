<?php
function flag($value, $min, $max)
{
   if ($value < $min) return "L";
   if ($value > $max) return "H";
   return "";
}

$time = date(
   "d-m-Y H:i",
   strtotime(($pasien['tanggal'] ?? '') . ' ' . ($pasien['waktu'] ?? ''))
);
$hist = [];

$qHist = mysqli_query($conn, "
   SELECT parameter, hasil
   FROM hasil_lab
   WHERE permintaan = '$no'
   AND lab = '$lab'
");

while ($r = mysqli_fetch_assoc($qHist)) {
   $hist[strtolower($r['parameter'])] = $r['hasil'];
}

?>
<!doctype html>
<html>

<head>
   <meta charset="utf-8" />
   <link rel="stylesheet" href="../app/template/hematologi.css" />
</head>

<body>
   <div class="paper">
      <div class="header">Assay Report by the Analyzer</div>
      <div class="info">

         <div class="info-line">

            <span>
               Time : <?= $time ?? '-' ?>
            </span>

            <span>
               ID : <?= $pasien['nomor_rm'] ?? '-' ?>
            </span>

            <span>
               Mode : WB-All
            </span>

            <span>
               Gender : <?= $pasien['gender'] ?? '-' ?>
            </span>

            <span>
               Age : <?= $pasien['usia'] ?? '-' ?> Yrs
            </span>

         </div>

         <div class="info-name">

            Name : <?= $pasien['nama_pasien'] ?? '-' ?>
            <?= $pasien['nomor_rm'] ?? '' ?>

         </div>

      </div>

      <div class="row">
         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
Parameter 

WBC    
Lymph#  
Mid#   
Gran# 
Lymph% 
Mid%    
Gran%  
RBC   
HGB   
HCT   
         </pre>
         </div>

         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
Result 

<?php
$wbc = $hasil['wbc'] ?? 0;
$f = flag($wbc, 5.0, 11.0);
?>
<?= $f ?> <?= $wbc ?> x 10^9/L
<?= $hasil['lymph#'] ?? '-' ?> x 10^9/L
<?= $hasil['mid#'] ?? '-' ?> x 10^9/L
<?= $hasil['gran#'] ?? '-' ?> x 10^9/L
<?= $hasil['lymph%'] ?? '-' ?> %
<?= $hasil['mid%'] ?? '-' ?> %
<?= $hasil['gran%'] ?? '-' ?> %
<?= $hasil['rbc'] ?? '-' ?> x 10^12/L
<?= $hasil['hgb'] ?? '-' ?> g/dL
<?= $hasil['hct'] ?? '-' ?> %
</pre>
         </div>

         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
  Ref. Range 

  5.0 - 11.0
  1.2 - 3.2
  0.3 - 0.8
  1.2 - 6.8
  20.0 - 40.0
  2.0 - 8.0
  43.0 - 76.0
  11.0 - 16.0
  3.80 - 6.50
  36.0 - 54.0
         </pre>
         </div>
         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
  Parameter 

  MCV    
  MCH
  MCHC
  RDW-CV
  RDW-SD
  PLT
  MPV
  PDW
  PCT
         </pre>
         </div>

         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
Result 

<?= $hasil['mcv'] ?? '-' ?> fL
<?= $hasil['mch'] ?? '-' ?> pg
<?= $hasil['mchc'] ?? '-' ?> g/dL
<?= $hasil['rdw-cv'] ?? '-' ?> %
<?= $hasil['rdw-sd'] ?? '-' ?> fL
<?= $hasil['plt'] ?? '-' ?> x 10^9/L
<?= $hasil['mpv'] ?? '-' ?> fL
<?= $hasil['pdw'] ?? '-' ?>
<?= $hasil['pct'] ?? '-' ?> %
</pre>
         </div>

         <!-- ===== 1 ===== -->
         <div class="block">
            <pre>
  Ref. Range 

  76.0 - 96.0
  27.0 - 33.0
  32.0 - 36.0
  11.5 - 14.5
  35.0 - 56.0
  150 - 450
  6.5 - 9.5
  10.0 - 18.0
  0.100 - 0.500
         </pre>
         </div>

         <!-- ===== HISTOGRAM ===== -->
         <div class="block">
            <div class="small-chart">
               <canvas id="wbc"></canvas>
               <canvas id="rbc"></canvas>
               <canvas id="plt"></canvas>
            </div>
         </div>
      </div>
   </div>
</body>
<script>
   /* ================= DATA DARI PHP ================= */

   /* pakai data parameter asli */
   const d = <?= json_encode($hist ?? []); ?>;

   /* ================= GAUSSIAN ================= */

   function gaussian(x, mean, sd, height) {
      return height * Math.exp(-0.5 * Math.pow((x - mean) / sd, 2));
   }

   /* ================= DRAW ================= */

   function drawChart(canvasId, labels, data, title) {

      const c = document.getElementById(canvasId);
      const ctx = c.getContext("2d");

      c.width = 180;
      c.height = 70;

      const w = c.width;
      const h = c.height;

      ctx.clearRect(0, 0, w, h);

      ctx.beginPath();

      for (let i = 0; i < data.length; i++) {

         let x = (i / data.length) * w;
         let y = h - data[i] / Math.max(...data) * (h - 10);

         if (i === 0) ctx.moveTo(x, y);
         else ctx.lineTo(x, y);
      }

      ctx.strokeStyle = "#000";
      ctx.stroke();

      ctx.font = "10px monospace";
      ctx.fillText(title, 5, 10);
   }


   /* ================= WBC ================= */

   const lymph = Number(d["limfosit"] ?? 30);

   const mid =
      Number(d["monosit"] ?? 0) +
      Number(d["eosinofil"] ?? 0) +
      Number(d["basofil"] ?? 0);

   const gran =
      Number(d["neutrofil segmen"] ?? 0) +
      Number(d["neutrofil batang"] ?? 0);

   const wbcTotal = Number(d["leukosit"] ?? 8000);

   const wbcLabels = Array.from({
      length: 40
   }, (_, i) => i * 10 + 50);

   const wbcData = wbcLabels.map(x =>
      gaussian(x, 90, 15, (lymph / 100) * wbcTotal) +
      gaussian(x, 150, 20, (mid / 100) * wbcTotal) +
      gaussian(x, 300, 40, (gran / 100) * wbcTotal)
   );


   /* ================= RBC ================= */

   const mcv = Number(d["mcv"] ?? 90);
   const rdw = Number(d["rdw"] ?? 13);

   const rbcLabels = Array.from({
      length: 40
   }, (_, i) => i * 5 + 60);

   const rbcData = rbcLabels.map(x =>
      gaussian(x, mcv, rdw, 100)
   );


   /* ================= PLT ================= */

   const mpv = Number(d["mpv"] ?? 10);
   const pdw = Number(d["pdw"] ?? 12);

   const pltLabels = Array.from({
      length: 40
   }, (_, i) => i * 2 + 2);

   const pltData = pltLabels.map(x =>
      gaussian(x, mpv, pdw / 2, 120)
   );


   /* ================= RENDER ================= */

   drawChart("wbc", wbcLabels, wbcData, "WBC");
   drawChart("rbc", rbcLabels, rbcData, "RBC");
   drawChart("plt", pltLabels, pltData, "PLT");


   /* ================= PRINT ================= */

   // window.onload = () => {
   //    setTimeout(() => {
   //       window.print();
   //       window.close();
   //    }, 500);
   // };
</script>

</html>