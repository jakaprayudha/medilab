<?php
require_once "../database/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$permintaan = $data['permintaan'];
$lab = $data['lab'];
$list = $data['data'];

foreach ($list as $item) {

   $parameter = $item['parameter'];
   $hasil = $item['hasil'];
   $satuan = $item['satuan'];
   $referensi = $item['referensi'];

   $stmt = $conn->prepare("
        INSERT INTO hasil_lab
        (permintaan,lab,parameter,hasil,satuan,referensi,create_at)
        VALUES (?,?,?,?,?,?,NOW())
    ");

   $stmt->bind_param(
      "ssssss",
      $permintaan,
      $lab,
      $parameter,
      $hasil,
      $satuan,
      $referensi
   );

   $stmt->execute();
}

echo json_encode([
   "status" => "success"
]);
