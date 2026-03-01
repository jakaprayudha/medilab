<?php

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


/* ================= FLAG ================= */

function flagHL($value, $min, $max)
{
   if (!is_numeric($value)) return ["", $value];

   if ($value < $min) return ["L", $value];
   if ($value > $max) return ["H", $value];

   return ["", $value];
}


/* ================= ROW FORMAT ================= */

function rowHL($name, $flag, $value, $unit = "")
{
   return sprintf(
      "%-8s %-2s %-5s %s\n",
      $name,
      $flag,
      $value,
      $unit
   );
}

/* ================= DATA FLAG ================= */

list($f_wbc, $wbc) = flagHL($hist['leukosit'] ?? 0, 5.0, 11.0);
list($f_lymph, $lymph) = flagHL($hist['#lymphocytes'] ?? 0, 1.2, 3.2);
list($f_mid, $mid) = flagHL($hist['#monocytes'] ?? 0, 0.3, 0.8);
list($f_gran, $gran) = flagHL($hist['#granulocytes'] ?? 0, 1.2, 6.8);

list($f_lymphp, $lymphp) = flagHL($hist['%lymphocytes'] ?? 0, 20.0, 40.0);
list($f_midp, $midp) = flagHL($hist['%monocytes'] ?? 0, 2.0, 8.0);
list($f_granp, $granp) = flagHL($hist['%granulocyte'] ?? 0, 43.0, 76.0);

list($f_rbc, $rbc) = flagHL($hist['eritrosit'] ?? 0, 3.80, 6.50);
list($f_hgb, $hgb) = flagHL($hist['hemoglobin'] ?? 0, 11.0, 16.0);
list($f_hct, $hct) = flagHL($hist['hct'] ?? 0, 36.0, 54.0);


/* ===== BLOCK 2 ===== */

list($f_mcv, $mcv) = flagHL($hist['mcv'] ?? 0, 76.0, 96.0);
list($f_mch, $mch) = flagHL($hist['mch'] ?? 0, 27.0, 33.0);
list($f_mchc, $mchc) = flagHL($hist['mchc'] ?? 0, 32.0, 36.0);

list($f_rdwcv, $rdwcv) = flagHL($hist['rdw'] ?? 0, 11.5, 14.5);
list($f_rdwsd, $rdwsd) = flagHL($hist['rdw-sd'] ?? 0, 35.0, 56.0);

list($f_plt, $plt) = flagHL($hist['trombosit'] ?? 0, 150, 450);
list($f_mpv, $mpv) = flagHL($hist['mpv'] ?? 0, 6.5, 9.5);
list($f_pdw, $pdw) = flagHL($hist['pdw'] ?? 0, 10.0, 18.0);


/* ===== NORMALISASI PCT ===== */

$pctRaw = $hist['pct'] ?? 0;
$pct = $pctRaw > 5 ? $pctRaw / 1000 : $pctRaw;

list($f_pct, $pct) = flagHL($pct, 0.100, 0.500);

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
         <div class="block">
            <pre>
Parameter    F  Result

<?php
echo rowHL("WBC", $f_wbc, $wbc, "x10^9/L");
echo rowHL("Lymph#", $f_lymph, $lymph, "x10^9/L");
echo rowHL("Mid#", $f_mid, $mid, "x10^9/L");
echo rowHL("Gran#", $f_gran, $gran, "x10^9/L");

echo rowHL("Lymph%", $f_lymphp, $lymphp, "%");
echo rowHL("Mid%", $f_midp, $midp, "%");
echo rowHL("Gran%", $f_granp, $granp, "%");

echo rowHL("RBC", $f_rbc, $rbc, "x10^12/L");
echo rowHL("HGB", $f_hgb, $hgb, "g/dL");
echo rowHL("HCT", $f_hct, $hct, "%");
?>
</pre>
         </div>

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
3.80 - 6.50
11.0 - 16.0
36.0 - 54.0
</pre>
         </div>


         <div class="block">
            <pre>
Parameter    F  Result   

<?php
echo rowHL("MCV", $f_mcv, $mcv, "fL");
echo rowHL("MCH", $f_mch, $mch, "pg");
echo rowHL("MCHC", $f_mchc, $mchc, "g/dL");

echo rowHL("RDW-CV", $f_rdwcv, $rdwcv, "%");
echo rowHL("RDW-SD", $f_rdwsd, $rdwsd, "fL");

echo rowHL("PLT", $f_plt, $plt, "x10^9/L");
echo rowHL("MPV", $f_mpv, $mpv, "fL");
echo rowHL("PDW", $f_pdw, $pdw, "");

echo rowHL("PCT", $f_pct, $pctRaw, "%");
?>
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

   const d = <?= json_encode($hist ?? []); ?>;

   /* ================= GAUSSIAN ================= */

   function gaussian(x, mean, sd, height) {
      return height * Math.exp(-0.5 * Math.pow((x - mean) / sd, 2));
   }

   /* ================= DRAW ================= */

   function drawChart(canvasId, labels, data, title, maxX) {

      const c = document.getElementById(canvasId);
      const ctx = c.getContext("2d");

      c.width = 200;
      c.height = 80;

      const w = c.width;
      const h = c.height;

      const leftPad = 25;
      const bottomPad = 18;

      const baseY = h - bottomPad;

      ctx.clearRect(0, 0, w, h);

      /* ===== AXIS ===== */

      ctx.strokeStyle = "#000";
      ctx.lineWidth = 1;

      ctx.beginPath();
      ctx.moveTo(leftPad, baseY);
      ctx.lineTo(w - 5, baseY);
      ctx.stroke();

      /* ===== SCALE ===== */

      ctx.font = "9px monospace";
      ctx.textAlign = "center";

      const steps = 3;

      for (let i = 0; i <= steps; i++) {

         const value = (maxX / steps) * i;

         const x = leftPad + ((w - leftPad - 5) / maxX) * value;

         ctx.beginPath();
         ctx.moveTo(x, baseY);
         ctx.lineTo(x, baseY + 4);
         ctx.stroke();

         ctx.fillText(Math.round(value), x, baseY + 12);
      }

      /* ===== CURVE ===== */

      ctx.beginPath();

      const maxVal = Math.max(...data);

      for (let i = 0; i < data.length; i++) {

         let x = leftPad + (i / data.length) * (w - leftPad - 5);
         let y = baseY - (data[i] / maxVal) * (h - bottomPad - 10);

         if (i === 0) ctx.moveTo(x, y);
         else ctx.lineTo(x, y);
      }

      ctx.stroke();

      /* ===== LABEL ===== */

      ctx.save();

      ctx.translate(15, h / 10);
      ctx.rotate(-Math.PI / 2);

      ctx.font = "bold 10px monospace";
      ctx.textAlign = "center";

      ctx.fillText(title, 0, 0);

      ctx.restore();
   }


   /* ================= WBC ================= */

   const lymph = Number(
      d["lymph%"] ?? d["limfosit"] ?? d["%lymphocytes"] ?? 30
   );

   const mid = Number(
      d["mid%"] ?? d["%monocytes"] ?? d["monosit"] ?? 10
   );

   const gran = Number(
      d["gran%"] ?? d["%granulocyte"] ?? 60
   );

   const wbcTotal = Number(d["leukosit"] ?? 8000);

   /* label mulai dari 0 */
   const wbcLabels = Array.from({
      length: 40
   }, (_, i) => i * 10);

   /* ===== RUMUS WBC FINAL ===== */

   const wbcData = wbcLabels.map((x) => {

      // LYMPH
      let lymphPeak =
         gaussian(x, 60, 7, (lymph / 100) * wbcTotal * 1.5);

      // 🔥 FORCE PEAK AWAL
      if (x < 80) {
         lymphPeak *= 3;
      }

      // MID
      const midPeak =
         gaussian(x, 120, 30, (mid / 100) * wbcTotal * 0.9);

      // GRAN
      const granPeak =
         gaussian(x, 220, 50, (gran / 100) * wbcTotal * 1.8);

      // TAIL
      const tail =
         gaussian(x, 270, 90, (gran / 100) * wbcTotal * 0.45);

      // VALLEY
      const valley =
         gaussian(x, 95, 18, wbcTotal * 0.25);

      return Math.max(
         lymphPeak + midPeak + granPeak + tail - valley,
         0
      );

   });


   /* ================= RBC ================= */

   const mcv = Number(d["mcv"] ?? 90);
   const rdw = Number(d["rdw"] ?? 13);

   const rbcLabels = Array.from({
      length: 40
   }, (_, i) => i * 5 + 60);

   const rbcData = rbcLabels.map(x =>
      gaussian(x, mcv, rdw / 2.5, 120)
   );


   /* ================= PLT ================= */

   const mpv = Number(d["mpv"] ?? 10);
   const pdw = Number(d["pdw"] ?? 12);

   const pltLabels = Array.from({
      length: 40
   }, (_, i) => i * 2 + 2);

   const pltData = pltLabels.map(x =>
      gaussian(x, mpv, pdw / 3, 120)
   );


   /* ================= RENDER ================= */

   drawChart("wbc", wbcLabels, wbcData, "WBC", 350);
   drawChart("rbc", rbcLabels, rbcData, "RBC", 200);
   drawChart("plt", pltLabels, pltData, "PLT", 30);


   /* ================= PRINT ================= */

   // window.onload = () => {
   //    setTimeout(() => {
   //       window.print();
   //       window.close();
   //    }, 500);
   // };
</script>

</html>