<?php
require_once "../database/db.php";
$lab = $_GET['lab'];

$stmt = $conn->prepare("
    SELECT parameter, hasil, satuan, referensi
    FROM laboratorium_item_calibration
    WHERE lab = ?
");

$stmt->bind_param("s", $lab);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
   $data[] = $row;
}

if (count($data) > 0) {
   echo json_encode([
      "status" => "success",
      "data" => $data
   ]);
} else {
   echo json_encode([
      "status" => "empty"
   ]);
}
