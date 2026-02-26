<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$permintaan = mysqli_real_escape_string($conn, $_GET["no"] ?? '');
$lab        = mysqli_real_escape_string($conn, $_GET["lab"] ?? '');

$sql = "
SELECT parameter, hasil
FROM hasil_lab
WHERE permintaan = '$permintaan'
AND lab = '$lab'
";

$q = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($q)) {
   $data[strtolower($row['parameter'])] = $row['hasil'];
}

echo json_encode([
   "success" => true,
   "data" => $data
]);
