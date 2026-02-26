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
   const histogramData = <?= json_encode($hasil); ?>;

   /* ================= UTIL ================= */

   function scaleX(value, maxX, width) {
      return (value / maxX) * width;
   }

   function drawAxis(ctx, width, height, maxX) {

      ctx.strokeStyle = "#000";
      ctx.lineWidth = 1;

      ctx.beginPath();
      ctx.moveTo(0, height - 10);
      ctx.lineTo(width, height - 10);
      ctx.stroke();

      ctx.font = "9px monospace";

      for (let i = 0; i <= maxX; i += maxX / 3) {

         let x = scaleX(i, maxX, width);

         ctx.beginPath();
         ctx.moveTo(x, height - 10);
         ctx.lineTo(x, height - 5);
         ctx.stroke();

         ctx.fillText(Math.round(i), x - 6, height);
      }
   }

   function drawGate(ctx, value, maxX, width, height) {

      let x = scaleX(value, maxX, width);

      ctx.save();
      ctx.setLineDash([4, 4]);

      ctx.beginPath();
      ctx.moveTo(x, 0);
      ctx.lineTo(x, height - 10);
      ctx.stroke();

      ctx.restore();

      return x;
   }

   function drawCurve(ctx, points) {

      ctx.beginPath();
      ctx.moveTo(points[0][0], points[0][1]);

      for (let p of points) {
         ctx.lineTo(p[0], p[1]);
      }

      ctx.stroke();
   }

   function drawVerticalLabel(ctx, text, x, height) {

      ctx.save();

      ctx.translate(x, height / 2);
      ctx.rotate(-Math.PI / 2);

      ctx.font = "bold 9px monospace";
      ctx.textAlign = "center";

      ctx.fillText(text, 0, 0);

      ctx.restore();
   }


   /* ================= CREATE CHART ================= */

   function createChart(id, type) {

      const c = document.getElementById(id);
      const ctx = c.getContext("2d");

      c.width = 180;
      c.height = 70;

      const w = c.width;
      const h = c.height;

      ctx.lineWidth = 1.2;


      /* ================= WBC ================= */

      if (type === "wbc") {

         const maxX = 300;

         const wbcValue = Number(histogramData["wbc"] ?? 50);

         drawAxis(ctx, w, h, maxX);

         let g1 = drawGate(ctx, wbcValue, maxX, w, h);
         let g2 = drawGate(ctx, 100, maxX, w, h);
         let g3 = drawGate(ctx, 150, maxX, w, h);

         drawVerticalLabel(ctx, "WBC", g1, h);

         drawCurve(ctx, [
            [0, 50],
            [20, 40],
            [40, 30],
            [60, 35],
            [80, 45],
            [100, 50],
            [130, 45],
            [160, 30],
            [200, 25],
            [240, 20],
            [280, 35],
            [300, 50],
         ]);
      }


      /* ================= RBC ================= */

      if (type === "rbc") {

         const maxX = 300;

         const rbcValue = Number(histogramData["mcv"] ?? 90);

         drawAxis(ctx, w, h, maxX);

         let g = drawGate(ctx, rbcValue, maxX, w, h);

         drawVerticalLabel(ctx, "RBC", g, h);

         drawCurve(ctx, [
            [0, 60],
            [40, 50],
            [70, 20],
            [100, 5],
            [130, 20],
            [160, 50],
            [200, 65],
         ]);
      }


      /* ================= PLT ================= */

      if (type === "plt") {

         const maxX = 25;

         const pltValue = Number(histogramData["plt"] ?? 15);

         drawAxis(ctx, w, h, maxX);

         let g = drawGate(ctx, pltValue, maxX, w, h);

         drawVerticalLabel(ctx, "PLT", g, h);

         drawCurve(ctx, [
            [0, 60],
            [20, 40],
            [40, 20],
            [80, 10],
            [120, 20],
            [150, 40],
            [180, 60],
         ]);
      }
   }


   /* ================= INIT ================= */

   createChart("wbc", "wbc");
   createChart("rbc", "rbc");
   createChart("plt", "plt");


   /* ================= AUTO PRINT ================= */

   window.onload = function() {

      setTimeout(() => {
         window.print();
         window.close();
      }, 500);

   };
</script>
<script>
   window.onload = function() {
      window.print();

      setTimeout(() => {
         window.close();
      }, 500);
   };
</script>

</html>