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
<?php

function fmtUrine($val)
{
  if (!$val) return '';

  $v = strtoupper(trim($val));

  // NEGATIVE → NEG
  if (strpos($v, 'NEG') !== false) {
    return 'Neg';
  }

  // POSITIVE 1 / POSITIF 1
  if (preg_match('/POS(ITIVE|ITIF)?\s*([0-9]+)/i', $v, $m)) {
    return $m[2] . '+';
  }

  // format +1 → 1+
  if (preg_match('/^\+([0-9]+)/', $v, $m)) {
    return $m[1] . '+';
  }

  // format 1 → 1+
  if (preg_match('/^[0-9]+$/', $v)) {
    return $v . '+';
  }

  return $val;
}

function urineCA($param, $val)
{
  $v = strtoupper(trim($val));

  $map = [
    'LEU' => [
      '1+' => 70,
      '2+' => 125,
      '3+' => 500,
    ],
    'BLD' => [
      '1+' => 25,
      '2+' => 80,
      '3+' => 200,
    ],
    'KET' => [
      '1+' => 1.5,
      '2+' => 3.9,
      '3+' => 7.8,
      '4+' => 16,
    ],
    'BIL' => [
      '1+' => 17,
      '2+' => 51,
      '3+' => 103,
    ],
    'UBG' => [
      '1+' => 34,
      '2+' => 68,
      '3+' => 135,
    ],
    'PRO' => [
      '1+' => 0.3,
      '2+' => 1.0,
      '3+' => 3.0,
      '4+' => 20.0,
    ],
    'GLU' => [
      '1+' => 5.6,
      '2+' => 14,
      '3+' => 28,
      '4+' => 56,
    ],
  ];

  return $map[$param][$v] ?? null;
}

$bld_strip = fmtUrine($hasil['bld'] ?? '');
$bld_ca    = urineCA('BLD', $bld_strip);

$leu_strip = fmtUrine($hasil['leu'] ?? '');
$leu_ca    = urineCA('LEU', $leu_strip);

$ket_strip = fmtUrine($hasil['ket'] ?? '');
$ket_ca    = urineCA('KET', $ket_strip);

$bil_strip = fmtUrine($hasil['bil'] ?? '');
$ket_bil    = urineCA('BIL', $bil_strip);

$pro_strip = fmtUrine($hasil['pro'] ?? '');
$pro_ca   = urineCA('PRO', $pro_strip);

$glu_raw   = $hasil['glu'] ?? '';
$glu_strip = fmtUrine($glu_raw);
$glu_ca    = urineCA('GLU', $glu_strip);

$ubg_raw   = $hasil['ubg'] ?? '';
$ubg_strip = fmtUrine($ubg_raw);
$ubg_ca    = urineCA('UBG', $ubg_strip);


$val_upper = strtoupper(trim($ubg_raw));

if (is_numeric($ubg_raw) || $val_upper == 'NORMAL') {
  // numeric atau tulisan NORMAL → tampil dengan satuan
  $ubg_text = $ubg_strip . "  " . [3.4, 17][array_rand([3.4, 17])] . " umol/L";
} elseif ($ubg_strip && $ubg_strip != 'Neg') {
  // strip + mapping
  $ubg_text = $ubg_strip . ($ubg_ca ? "  " . $ubg_ca . " umol/L" : "");
} else {
  // NEG atau kosong
  $ubg_text = $ubg_strip;
}

$val_upper2 = strtoupper(trim($glu_raw));

if (is_numeric($glu_raw) || $val_upper2 == 'NORMAL') {
  // numeric atau tulisan NORMAL → tampil dengan satuan
  $glu_text = "Normal";
} elseif ($glu_strip && $glu_strip != 'Neg') {
  // strip + mapping
  $glu_text = $glu_strip . ($glu_ca ? "  " . $glu_ca . " mmol/L" : "");
} else {
  // NEG atau kosong
  $glu_text = $glu_strip;
}
?>

<?= rowUrine("UBG", $ubg_text) ?>
<?= rowUrine(
  "BIL",
  $bil_strip .
    ($ket_bil ? "  " . $ket_bil : "") .
    ($bil_strip && $bil_strip != 'Neg' ? " umol/L" : "")
) ?>
<?= rowUrine(
  "KET",
  $ket_strip .
    ($ket_ca ? "  " . $ket_ca : "") .
    ($ket_strip && $ket_strip != 'Neg' ? " mmol/L" : "")
) ?>
<?= rowUrine(
  "BLD",
  $bld_strip .
    ($bld_ca ? "  " . $bld_ca : "") .
    ($bld_strip && $bld_strip != 'Neg' ? " Ery/uL" : "")
) ?>
<?= rowUrine(
  "PRO",
  $pro_strip .
    ($pro_ca ? "  " . $pro_ca : "") .
    ($pro_strip && $pro_strip != 'Neg' ? " g/L" : "")
) ?>
<?= rowUrine("NIT", fmtUrine($hasil['nit'] ?? '')) ?>
<?= rowUrine(
  "LEU",
  $leu_strip .
    ($leu_ca ? "  " . $leu_ca : "") .
    ($leu_strip && $leu_strip != 'Neg' ? " Leu/uL" : "")
) ?>
<?= rowUrine("GLU", $glu_text) ?>
<?= sprintf("%-5s %15s\n", "SG", fmtUrine($hasil['sg'] ?? '')) ?>
<?= sprintf("%-5s %15s\n", "pH", fmtUrine($hasil['ph'] ?? '')) ?>

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