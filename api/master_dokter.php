<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$q = mysqli_query($conn, "SELECT sip, nama
   FROM dokter
   WHERE status_dokter = 1
   ORDER BY nama ASC
");

$data = [];

while ($row = mysqli_fetch_assoc($q)) {
   $data[] = $row;
}

echo json_encode($data);
exit;
