<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$q = mysqli_query($conn, "SELECT pv.nomor_rm, pv.id, pv.nama_pasien, ps.tanggal_lahir, ps.gender, pv.nomor_visit
   FROM pasien_visit AS pv INNER JOIN pasien AS ps ON pv.nomor_rm = ps.nomor_rm
   WHERE pv.status_visit IN (0,1,2,3,4,5)
   ORDER BY nama_pasien ASC
");

$data = [];

while ($row = mysqli_fetch_assoc($q)) {
   $data[] = $row;
}

echo json_encode($data);
exit;
