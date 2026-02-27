<?php

$time = date(
  "d-m-Y H:i",
  strtotime(($pasien['tanggal'] ?? '') . ' ' . ($pasien['waktu'] ?? ''))
);

function rowUrine($label, $value)
{
  if ($value === '' || $value === null) return '';

  return sprintf("%-5s %-10s\n", $label, $value);
}

function rowUrineWide($label, $value)
{
  if ($value === '' || $value === null) return '';

  // lebih menjorok ke kanan seperti alat
  return sprintf("%-5s %12s\n", $label, $value);
}

?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Urine Sample Report</title>
  <link rel="stylesheet" href="../app/template/urine.css" />
</head>

<body>

  <pre class="struk">
Date:<?= $time ?>        
No.  <?= $pasien['nopermintaan'] ?? '-' ?> 

<?= rowUrine("UBG", $hasil['ubg'] ?? '') ?>
<?= rowUrine("BIL", $hasil['bil'] ?? '') ?>
<?= rowUrine("KET", $hasil['ket'] ?? '') ?>
<?= rowUrine("BLD", $hasil['bld'] ?? '') ?>
<?= rowUrine("PRO", $hasil['pro'] ?? '') ?>
<?= rowUrine("NIT", $hasil['nit'] ?? '') ?>
<?= rowUrine("LEU", $hasil['leu'] ?? '') ?>
<?= rowUrine("GLU", $hasil['glu'] ?? '') ?>
<?= sprintf("%-5s %15s\n", "SG", $hasil['sg'] ?? '') ?>
<?= sprintf("%-5s %15s\n", "pH", $hasil['ph'] ?? '') ?>

</pre>

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