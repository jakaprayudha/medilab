<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ================= LIST ================= */
if ($method === "GET" && !isset($_GET["id"])) {

   $kode = $_GET['kode'] ?? '';

   if ($kode) {
      $kode = mysqli_real_escape_string($conn, $kode);
      $where = "WHERE kode='$kode'";
   } else {
      $where = "";
   }

   $q = mysqli_query($conn, "
      SELECT * FROM laboratorium_item
      $where
      ORDER BY urutan ASC
   ");

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
   $q  = mysqli_query($conn, "SELECT * FROM laboratorium_item WHERE id=$id LIMIT 1");

   echo json_encode(mysqli_fetch_assoc($q));
   exit;
}

/* ================= CREATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "create") {

   $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
   $urutan = mysqli_real_escape_string($conn, $_POST["urutan"] ?? "");
   $assemen = mysqli_real_escape_string($conn, $_POST["assemen"] ?? "");
   $ass_alat = mysqli_real_escape_string($conn, $_POST["ass_alat"] ?? "");
   $minimum = mysqli_real_escape_string($conn, $_POST["minimum"] ?? "");
   $maksimum = mysqli_real_escape_string($conn, $_POST["maksimum"] ?? "");
   $catatan = mysqli_real_escape_string($conn, $_POST["catatan"] ?? "");
   $catatan = mysqli_real_escape_string($conn, $_POST["catatan"] ?? "");

   $sql = "INSERT INTO laboratorium_item (kode, urutan, assemen, ass_alat, minimum, maksimum, catatan)
           VALUES ('$kode', '$urutan', '$assemen', '$ass_alat', '$minimum', '$maksimum', '$catatan')";

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

   $kode = mysqli_real_escape_string($conn, $_POST["kode"] ?? "");
   $urutan = mysqli_real_escape_string($conn, $_POST["urutan"] ?? "");
   $assemen = mysqli_real_escape_string($conn, $_POST["assemen"] ?? "");
   $ass_alat = mysqli_real_escape_string($conn, $_POST["ass_alat"] ?? "");
   $minimum = mysqli_real_escape_string($conn, $_POST["minimum"] ?? "");
   $maksimum = mysqli_real_escape_string($conn, $_POST["maksimum"] ?? "");
   $catatan = mysqli_real_escape_string($conn, $_POST["catatan"] ?? "");
   $satuan = mysqli_real_escape_string($conn, $_POST["satuan"] ?? "");

   $sql = "UPDATE laboratorium_item SET
           kode='$kode',
           urutan='$urutan',
           assemen='$assemen',
           ass_alat='$ass_alat',
           minimum='$minimum',
           maksimum='$maksimum',
           catatan='$catatan',
           satuan='$satuan'
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
   mysqli_query($conn, "DELETE FROM laboratorium_item WHERE id=$id");

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

   $sql = "UPDATE laboratorium_item SET status=$status WHERE id=$id";

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
