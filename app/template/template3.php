<?php
require_once "../database/db.php";
$visit = $_GET["visit"];
$no = $_GET["no"];
$rm = $_GET["rm"];
$lab = $_GET["lab"];
$q = mysqli_query($conn, "SELECT * FROM tfaskes LIMIT 1");
$data = mysqli_fetch_assoc($q);
$checkidentitas = mysqli_query($conn, "SELECT * FROM permintaan_lab WHERE nopermintaan = '$no' AND nomor_rm = '$rm' LIMIT 1");
$identitas = mysqli_fetch_assoc($checkidentitas);
$datahasil = mysqli_query($conn, "
  SELECT parameter, hasil 
  FROM hasil_lab 
  WHERE permintaan = '$no' 
  AND lab = '$lab'
");

$datalab = [];

while ($row = mysqli_fetch_assoc($datahasil)) {
  $key = strtolower(trim($row['parameter']));
  $datalab[$key] = $row['hasil'];
}

function arrow($value, $min, $max)
{
  if (!is_numeric($value)) return '';

  if ($value < $min) return ' ↓';
  if ($value > $max) return ' ↑';

  return '';
}
// echo "<pre>";
// print_r($datalab);
// echo "</pre>";

$k  = $datalab['kalium'] ?? null;
$na = $datalab['natrium'] ?? null;
$cl = $datalab['clorida'] ?? null;
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Serum Sample Report</title>
  <link rel="stylesheet" href="elektrolit.css" />
</head>

<body>
  <div class="struk">

    <pre>
<?= $identitas['tanggal'] ?> <?= $identitas['waktu'] ?>

<?= rand(000000, 999999) ?>:000<?= rand(0, 9)  ?>


K  = <?= $k ?? '-' ?> mmol/L<?= arrow($k, 3.50, 5.50) ?>

Na = <?= $na ?? '-' ?> mmol/L<?= arrow($na, 135.00, 155.00) ?>

Cl = <?= $cl ?? '-' ?> mmol/L<?= arrow($cl, 90.00, 111.00) ?>
</pre>

    <div class="big">REFERENCE RANGE</div>

    <pre>
K   3.50 — 5.50 mmol/L
Na 135.00 —155.00 mmol/L
Cl  90.00 —111.00 mmol/L

------------------------
</pre>
  </div>
</body>

</html>