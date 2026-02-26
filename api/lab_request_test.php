<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ================= HEADER PERMINTAAN ================= */
if ($method === "GET" && ($_GET["mode"] ?? '') === "header") {

   $nopermintaan = mysqli_real_escape_string($conn, $_GET["no"] ?? '');
   $nomor_rm     = mysqli_real_escape_string($conn, $_GET["rm"] ?? '');
   $nomor_visit  = mysqli_real_escape_string($conn, $_GET["visit"] ?? '');

   $sql = "SELECT lab.*, ps.nama_pasien, ps.dokter, ps.nomor_visit,
            ps.sumber, pv.gender, ps.usia, pv.tanggal_lahir
           FROM permintaan_lab AS lab INNER JOIN pasien_visit AS ps ON ps.nomor_rm = lab.nomor_rm INNER JOIN pasien AS pv ON pv.nomor_rm = ps.nomor_rm
           WHERE lab.nopermintaan = '$nopermintaan'
           AND lab.nomor_rm = '$nomor_rm'
           AND lab.catatan = '$nomor_visit'
           LIMIT 1";

   $q = mysqli_query($conn, $sql);

   if (!$q || mysqli_num_rows($q) == 0) {
      echo json_encode([
         "success" => false,
         "message" => "Data tidak ditemukan"
      ]);
      exit;
   }

   $row = mysqli_fetch_assoc($q);

   // ===== HITUNG TOTAL ITEM =====
   $q2 = mysqli_query($conn, "
      SELECT COUNT(*) as total
      FROM permintaan_lab_detail
      WHERE nopermintaan = '$nopermintaan'
   ");

   $total = mysqli_fetch_assoc($q2)["total"] ?? 0;

   echo json_encode([
      "success" => true,
      "data" => $row,
      "total_item" => $total
   ]);

   exit;
}
/* ================= LIST ================= */
if ($method === "GET" && !isset($_GET["id"])) {

   $nopermintaan = mysqli_real_escape_string($conn, $_GET["no"] ?? '');
   $nomor_rm     = mysqli_real_escape_string($conn, $_GET["rm"] ?? '');
   $nomor_visit  = mysqli_real_escape_string($conn, $_GET["visit"] ?? '');

   $where = "WHERE 1=1";

   if ($nopermintaan != '') {
      $where .= " AND nopermintaan = '$nopermintaan'";
   }

   if ($nomor_rm != '') {
      $where .= " AND nomor_rm = '$nomor_rm'";
   }

   $sql = "SELECT *
      FROM permintaan_lab_detail  $where
      ORDER BY id DESC
   ";

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
   $q  = mysqli_query($conn, "SELECT * FROM permintaan_lab_detail WHERE id=$id LIMIT 1");

   echo json_encode(mysqli_fetch_assoc($q));
   exit;
}

/* ================= CREATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "create") {
   // ================= GENERATE NO PERMINTAAN =================

   $nomor_rm = mysqli_real_escape_string($conn, $_POST["nomor_rm"]);
   $nomor_visit = mysqli_real_escape_string($conn, $_POST["nomor_visit"] ?? "");
   $lab = mysqli_real_escape_string($conn, $_POST["pemeriksaan_id"] ?? "");
   $nomor_lab = mysqli_real_escape_string($conn, $_POST["nomor_lab"] ?? "");
   $catatan = mysqli_real_escape_string($conn, $_POST["catatan"] ?? "");



   $sql = "INSERT INTO permintaan_lab_detail (nomor_rm, nopermintaan, lab, catatan)
           VALUES ('$nomor_rm', '$nomor_lab', '$lab', '$catatan')";

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
   $q = mysqli_query($conn, "SELECT nopermintaan, lab 
      FROM permintaan_lab_detail
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
   $lab =  mysqli_real_escape_string($conn, $row["lab"]);

   // ===== CEK ADA DETAIL =====
   $cek = mysqli_query($conn, "SELECT id
      FROM hasil_lab
      WHERE permintaan = '$nopermintaan'
      AND lab = '$lab'
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
   if (mysqli_query($conn, "DELETE FROM permintaan_lab_detail WHERE id=$id")) {

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

   $sql = "UPDATE permintaan_lab_detail SET status=$status WHERE id=$id";

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
