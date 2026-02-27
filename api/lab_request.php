<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ================= LIST ================= */
if ($method === "GET" && !isset($_GET["id"])) {

   $sql = "SELECT 
    d.*, 
    pv.nama_pasien, 
    pv.dokter, 
    pv.sumber,
    COUNT(i.id) AS total_item
      FROM permintaan_lab d

      LEFT JOIN permintaan_lab_detail i 
         ON i.nopermintaan = d.nopermintaan 
         AND i.nomor_rm = d.nomor_rm

      INNER JOIN pasien_visit pv 
         ON pv.nomor_visit = d.catatan

      WHERE d.status = 0

      GROUP BY d.nopermintaan
      ORDER BY d.tanggal DESC";

   $q = mysqli_query($conn, $sql);

   $data = [];
   while ($row = mysqli_fetch_assoc($q)) {
      $data[] = $row;
   }

   echo json_encode($data);
   exit;
}

/* ================= DETAIL ================= */
if ($method === "GET" && isset($_GET["id"])) {
   $id = (int)$_GET["id"];
   $q  = mysqli_query($conn, "SELECT * FROM permintaan_lab WHERE id=$id LIMIT 1");

   echo json_encode(mysqli_fetch_assoc($q));
   exit;
}

/* ================= CREATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "create") {
   // ================= GENERATE NO PERMINTAAN =================
   $tanggalKode = date('Ymd'); // 20251015
   $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 4 digit
   $nopermintaan = "LAB-" . $tanggalKode . $random;

   $nomor_rm = mysqli_real_escape_string($conn, $_POST["nomor_rm"]);
   $tanggal = mysqli_real_escape_string($conn, $_POST["tanggal"] ?? "");
   $waktu = mysqli_real_escape_string($conn, $_POST["waktu"] ?? "");
   $nomor_visit = mysqli_real_escape_string($conn, $_POST["nomor_visit"] ?? "");



   $sql = "INSERT INTO permintaan_lab (nopermintaan, nomor_rm, tanggal, waktu, catatan)
           VALUES ('$nopermintaan', '$nomor_rm', '$tanggal', '$waktu', '$nomor_visit')";

   if (!mysqli_query($conn, $sql)) {
      echo json_encode([
         "message" => "Gagal insert",
         "error" => mysqli_error($conn)
      ]);
      exit;
   }

   echo json_encode(["message" => "Berhasil ditambahkan"]);
   exit;
}

/* ================= DELETE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "delete") {

   $id = (int)$_POST["id"];

   // ===== AMBIL DATA PERMINTAAN =====
   $q = mysqli_query($conn, "
      SELECT nopermintaan, nomor_rm
      FROM permintaan_lab
      WHERE id = $id
      LIMIT 1
   ");

   if (!$q || mysqli_num_rows($q) == 0) {
      echo json_encode([
         "success" => false,
         "message" => "Data tidak ditemukan"
      ]);
      exit;
   }

   $row = mysqli_fetch_assoc($q);
   $nopermintaan = mysqli_real_escape_string($conn, $row["nopermintaan"]);
   $nomor_rm = mysqli_real_escape_string($conn, $row["nomor_rm"]);

   // ===== CEK ADA DETAIL =====
   $cek = mysqli_query($conn, "
      SELECT id
      FROM permintaan_lab_detail
      WHERE nopermintaan = '$nopermintaan'
      AND nomor_rm = '$nomor_rm'
      LIMIT 1
   ");

   if (mysqli_num_rows($cek) > 0) {

      echo json_encode([
         "success" => false,
         "message" => "Tidak bisa dihapus, sudah ada detail pemeriksaan"
      ]);
      exit;
   }

   // ===== HAPUS =====
   if (mysqli_query($conn, "DELETE FROM permintaan_lab WHERE id=$id")) {

      echo json_encode([
         "success" => true,
         "message" => "Berhasil dihapus"
      ]);
   } else {

      echo json_encode([
         "success" => false,
         "message" => "Gagal hapus",
         "error" => mysqli_error($conn)
      ]);
   }

   exit;
}
/* ================= TOGGLE STATUS ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "toggle_status") {

   $id = (int)($_POST["id"] ?? 0);
   $status = (int)($_POST["status"] ?? 0);

   if (!$id) {
      echo json_encode(["message" => "ID tidak valid"]);
      exit;
   }

   $sql = "UPDATE permintaan_lab SET status=$status WHERE id=$id";

   if (!mysqli_query($conn, $sql)) {
      echo json_encode([
         "message" => "Gagal update status",
         "error" => mysqli_error($conn)
      ]);
      exit;
   }

   echo json_encode([
      "message" => "Status berhasil diperbarui"
   ]);
   exit;
}

/* ================= FALLBACK ================= */
echo json_encode(["message" => "Invalid Request"]);
exit;
