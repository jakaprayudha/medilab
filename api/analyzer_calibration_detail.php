<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ==========================================================
   LIST PARAMETER LAB
========================================================== */
if ($method === "GET" && !isset($_GET["id"])) {

   $lab          = mysqli_real_escape_string($conn, $_GET["lab"] ?? '');

   // ambil kode lab
   $kode = '';

   $checkkode = mysqli_query(
      $conn,
      "SELECT kode FROM laboratorium_detail
       WHERE assemen = '$lab'
       LIMIT 1"
   );

   if ($checkkode && mysqli_num_rows($checkkode) > 0) {
      $datakode = mysqli_fetch_assoc($checkkode);
      $kode = $datakode['kode'];
   }

   $where = "WHERE 1=1";

   if ($kode != '') {
      $where .= " AND li.kode = '$kode'";
   }

   $sql = "SELECT li.*, hl.hasil
      FROM laboratorium_item li
      LEFT JOIN laboratorium_item_calibration hl
         ON hl.parameter = li.assemen
         AND hl.lab = '$lab'
      $where
      ORDER BY li.urutan ASC
   ";

   $q = mysqli_query($conn, $sql);

   if (!$q) {
      echo json_encode([
         "success" => false,
         "message" => mysqli_error($conn)
      ]);
      exit;
   }

   $data = [];
   while ($row = mysqli_fetch_assoc($q)) {
      $data[] = $row;
   }

   echo json_encode($data);
   exit;
}


/* ==========================================================
   SAVE / UPDATE HASIL LAB
========================================================== */
if ($method === "POST" && ($_POST["mode"] ?? '') === "save_hasil") {

   $lab        = mysqli_real_escape_string($conn, $_POST["lab"] ?? '');
   $parameter  = mysqli_real_escape_string($conn, $_POST["parameter"] ?? '');
   $hasil      = mysqli_real_escape_string($conn, $_POST["hasil"] ?? '');
   $satuan     = mysqli_real_escape_string($conn, $_POST["satuan"] ?? '');
   $referensi  = mysqli_real_escape_string($conn, $_POST["referensi"] ?? '');

   if (!$parameter) {
      echo json_encode([
         "success" => false,
         "message" => "Data tidak lengkap"
      ]);
      exit;
   }

   // cek sudah ada
   $cek = mysqli_query($conn, "SELECT id FROM laboratorium_item_calibration
      WHERE lab = '$lab'
      AND parameter = '$parameter'
      LIMIT 1
   ");

   if (!$cek) {
      echo json_encode([
         "success" => false,
         "message" => mysqli_error($conn)
      ]);
      exit;
   }

   /* ================= UPDATE ================= */
   if (mysqli_num_rows($cek) > 0) {

      $row = mysqli_fetch_assoc($cek);
      $id = $row['id'];

      $update = mysqli_query($conn, "
         UPDATE laboratorium_item_calibration
         SET hasil = '$hasil',
             satuan = '$satuan',
             referensi = '$referensi'
         WHERE id = $id
      ");

      if (!$update) {
         echo json_encode([
            "success" => false,
            "message" => mysqli_error($conn)
         ]);
         exit;
      }

      echo json_encode([
         "success" => true,
         "message" => "Berhasil update"
      ]);
   }

   /* ================= INSERT ================= */ else {

      $insert = mysqli_query($conn, "
         INSERT INTO laboratorium_item_calibration
         (lab, parameter, hasil, satuan, referensi)
         VALUES
         ('$lab','$parameter','$hasil','$satuan','$referensi')
      ");

      if (!$insert) {
         echo json_encode([
            "success" => false,
            "message" => mysqli_error($conn)
         ]);
         exit;
      }

      echo json_encode([
         "success" => true,
         "message" => "Berhasil simpan"
      ]);
   }

   exit;
}


/* ==========================================================
   FALLBACK
========================================================== */
echo json_encode([
   "success" => false,
   "message" => "Invalid Request"
]);
exit;
