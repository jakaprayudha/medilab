<?php
require_once "../database/db.php";



$no  = $_GET['no'] ?? '';
$lab = $_GET['lab'] ?? '';

$no  = mysqli_real_escape_string($conn, $no);
$lab = mysqli_real_escape_string($conn, $lab);


/* ================= DATA PASIEN ================= */

$sqlPasien = "
SELECT 
    pl.nopermintaan,
    pl.nomor_rm,
    pl.tanggal,
    pl.waktu,
    ps.nama_pasien,
    ps.nomor_visit,
    ps.usia,
    pv.gender
FROM permintaan_lab pl
JOIN pasien_visit ps ON ps.nomor_rm = pl.nomor_rm
JOIN pasien pv ON pv.nomor_rm = pl.nomor_rm
WHERE pl.nopermintaan = '$no'
LIMIT 1
";

$qPasien = mysqli_query($conn, $sqlPasien);
$pasien = mysqli_fetch_assoc($qPasien);

/*
Ambil hasil dan mapping ke ass_alat
*/
$sql = "
SELECT li.ass_alat, hl.hasil
FROM hasil_lab hl
JOIN laboratorium_item li 
  ON li.assemen = hl.parameter
WHERE hl.permintaan = '$no'
AND hl.lab = '$lab'
";

$q = mysqli_query($conn, $sql);

$hasil = [];

while ($row = mysqli_fetch_assoc($q)) {
   $key = strtolower(trim($row['ass_alat']));
   $hasil[$key] = $row['hasil'];
}

/* ===== AMBIL TEMPLATE ===== */

$q = mysqli_query($conn, "
   SELECT template 
   FROM laboratorium_detail 
   WHERE assemen = '$lab'
   LIMIT 1
");

$data = mysqli_fetch_assoc($q);

$template = $data['template'] ?? 0; // default 1 kalau kosong
switch ($template) {

   case 1:
      require "../app/template/template1.php";
      break;

   case 2:
      require "../app/template/template2.php";
      break;

   case 3:
      require "../app/template/template3.php";
      break;

   case 4:
      require "../app/template/template4.php";
      break;

   case 5:
      require "../app/template/template5.php";
      break;

   default:
      require "../app/template/template0.php";
      break;
}
