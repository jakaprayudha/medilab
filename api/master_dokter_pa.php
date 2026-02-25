<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ================= LIST ================= */
if ($method === "GET" && !isset($_GET["id"])) {

   $sql = "SELECT * FROM identitas_ttd WHERE kategori='Laboratorium' ORDER BY nama_ttd DESC";

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
   $q  = mysqli_query($conn, "SELECT * FROM identitas_ttd WHERE id=$id LIMIT 1");

   echo json_encode(mysqli_fetch_assoc($q));
   exit;
}

/* ================= CREATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "create") {

   $kategori = "Laboratorium";
   $nama_ttd = mysqli_real_escape_string($conn, $_POST["nama_ttd"] ?? "");
   $no_sip = mysqli_real_escape_string($conn, $_POST["no_sip"] ?? "");

   $sql = "INSERT INTO identitas_ttd (kategori, nama_ttd, no_sip)
           VALUES ('$kategori', '$nama_ttd', '$no_sip')";

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

/* ================= UPDATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "update") {

   $id = (int)($_POST["id"] ?? 0);

   $nama_ttd = mysqli_real_escape_string($conn, $_POST["nama_ttd"] ?? "");
   $no_sip = mysqli_real_escape_string($conn, $_POST["no_sip"] ?? "");

   $sql = "UPDATE identitas_ttd SET
           nama_ttd='$nama_ttd',
           no_sip='$no_sip'
           WHERE id=$id";

   if (!mysqli_query($conn, $sql)) {
      echo json_encode([
         "message" => "Gagal update",
         "error" => mysqli_error($conn)
      ]);
      exit;
   }

   echo json_encode(["message" => "Berhasil diperbarui"]);
   exit;
}

/* ================= DELETE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "delete") {

   $id = (int)$_POST["id"];
   mysqli_query($conn, "DELETE FROM identitas_ttd WHERE id=$id");

   echo json_encode(["message" => "Berhasil dihapus"]);
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

   $sql = "UPDATE identitas_ttd SET status=$status WHERE id=$id";

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
