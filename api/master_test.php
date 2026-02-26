<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$q = mysqli_query($conn, "SELECT kode, assemen, tarif
   FROM laboratorium_detail
   WHERE status = 1
   ORDER BY assemen ASC
");

$data = [];

while ($row = mysqli_fetch_assoc($q)) {
   $data[] = $row;
}

echo json_encode($data);
exit;
