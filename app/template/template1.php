<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Thermal Print</title>
  <link rel="stylesheet" href="../app/template/hemoglobin.css" />
</head>

<body>

  <?php
  $date = date("Y/m/d", strtotime($pasien['tanggal']));
  $time = date("H:i", strtotime($pasien['waktu']));

  $nama  = $pasien['nama_pasien'] ?? '-';
  $rm    = $pasien['nomor_rm'] ?? '-';

  $hba1c = $hasil['hba1c'] ?? '-';
  $eag   = $hasil['eag'] ?? '-';

  function getHasil($key, $default = '-')
  {
    global $hasil;
    return $hasil[strtolower($key)] ?? $default;
  }
  function statusDiabetes($hba1c)
  {
    if (!is_numeric($hba1c)) return '-';

    if ($hba1c < 5.6) return "Normal"; //Normal
    if ($hba1c < 6.4) return "Prediabetes"; //Prediabetes
    if ($hba1c > 6.5) return "Diabetes"; //Diabetes Terkontrol
  }
  $status = statusDiabetes($hba1c);
  ?>

  <div class="receipt">

    <!-- Logo -->
    <div class="center logo">
      <img src="../app/template/logo.png" width="150" />
    </div>

    <!-- Date Time -->
    <div class="row">
      <div><?= $date ?></div>
      <div><?= $time ?></div>
    </div>

    <div class="dash"></div>

    <!-- Device Info -->
    <div>SN : FA20FBITH2499</div>
    <div>Version : V001.063</div>

    <br />

    <div>Lot No. : 60771D3</div>

    <div class="dash"></div>

    <!-- User Info -->
    <div>Operator ID : LAB</div>
    <div>Patient : <?= strtoupper($nama) ?></div>
    <div>No RM : <?= $rm ?></div>

    <!-- Big Result -->
    <div class="big">
      <img src="../app/template/c.png" width="80" alt="" />
    </div>

    <div class="dash"></div>

    <!-- Result -->
    <div>HbA1c = <?= $hba1c ?> %[NGSP]</div>
    <div class="indent"><?= $eag ?> mg/dL[eAG]</div>
    <div>Procedural Control = Valid</div>

    <div class="dash"></div>

    <!-- ADA Target -->
    <div class="small center">[ ADA : <b><?= $status ?></b> ]</div>

    <div class="grid">
      <div>IFCC : 53 mmol/mol</div>
      <div>NGSP : 7.00%</div>
      <div>JDS/JSCC : 6.60%</div>
      <div>Mono-S : 6.10%</div>
    </div>

    <div class="dash"></div>

    <div>Note:</div>

  </div>

</body>

</html>