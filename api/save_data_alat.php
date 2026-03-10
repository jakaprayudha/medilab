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

   // cek apakah data sudah ada
   $cek = $conn->prepare("
        SELECT id 
        FROM hasil_lab 
        WHERE permintaan=? AND lab=? AND parameter=?
    ");

   $cek->bind_param("sss", $permintaan, $lab, $parameter);
   $cek->execute();
   $result = $cek->get_result();

   if ($result->num_rows > 0) {

      // UPDATE
      $stmt = $conn->prepare("
            UPDATE hasil_lab 
            SET hasil=?, satuan=?, referensi=? 
            WHERE permintaan=? AND lab=? AND parameter=?
        ");

      $stmt->bind_param(
         "ssssss",
         $hasil,
         $satuan,
         $referensi,
         $permintaan,
         $lab,
         $parameter
      );
   } else {

      // INSERT
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
   }

   $stmt->execute();
}

echo json_encode([
   "status" => "success"
]);
