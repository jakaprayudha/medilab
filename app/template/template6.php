<?php
require_once "../database/db.php";
$visit = $_GET["visit"];
$no = $_GET["no"];
$rm = $_GET["rm"];
$lab = $_GET["lab"];
$q = mysqli_query($conn, "SELECT * FROM tfaskes LIMIT 1");
$data = mysqli_fetch_assoc($q);
$datahasil = mysqli_query($conn, "SELECT * FROM hasil_lab INNER JOIN permintaan_lab ON permintaan_lab.nopermintaan = hasil_lab.permintaan WHERE hasil_lab.permintaan = '$no' AND hasil_lab.lab = '$lab'  LIMIT 1");
$datalab = mysqli_fetch_assoc($datahasil);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <title>Thermal Print CRE</title>
  <link rel="stylesheet" href="../app/template/faal.css" />
</head>

<body>

  <div class="struk">

    <div class="big">
      <?php echo (strtoupper($data["nama_faskes"])) ?>
    </div>

    <pre>
<?= str_pad("Sample ID", 12) ?>: <?= $rm ?>

<?= str_pad("Test Name", 12) ?>: <?= $lab ?>

<?= str_pad("Abs.", 12) ?>: 0.0002
<?= str_pad("Result", 12) ?>: <?= $datalab["hasil"] ?>

<?= str_pad("Unit", 12) ?>: mg/dl
<?= str_pad("Ref. Low", 12) ?>: 0.60
<?= str_pad("Ref. High", 12) ?>: 1.40
<?= str_pad("Test/Date", 12) ?>: <?= $datalab["tanggal"] . " " . $datalab["waktu"] ?>

</pre>

    <div class="mt big">
      <?php echo (strtoupper($data["kabupaten"])) ?>
    </div>

  </div>

  <script>
    window.onload = () => {
      setTimeout(() => {
        window.print();
        window.close();
      }, 500);
    };
  </script>

</body>

</html>