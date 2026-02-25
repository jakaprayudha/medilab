<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ================= LIST ================= */
if ($method === "GET" && !isset($_GET["id"])) {

   $sql = "SELECT * FROM user WHERE roles='laboratorium' ORDER BY fullname DESC";

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
   $q  = mysqli_query($conn, "SELECT * FROM user WHERE id=$id LIMIT 1");

   echo json_encode(mysqli_fetch_assoc($q));
   exit;
}

/* ================= CREATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "create") {

   $roles = "laboratorium";

   $fullname = mysqli_real_escape_string($conn, $_POST["fullname"] ?? "");
   $username = mysqli_real_escape_string($conn, $_POST["username"] ?? "");
   $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

   $status = 1;
   $uid = uniqid("USR");

   $sql = "INSERT INTO user 
      (uid, roles, path, fullname, username, password, status_user)
      VALUES 
      ('$uid', '$roles', '$roles', '$fullname', '$username', '$password', '$status')
   ";

   if (!mysqli_query($conn, $sql)) {
      echo json_encode([
         "message" => "Gagal insert",
         "error" => mysqli_error($conn),
         "sql" => $sql
      ]);
      exit;
   }

   echo json_encode(["message" => "Berhasil ditambahkan"]);
   exit;
}

/* ================= UPDATE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "update") {

   $id = (int)($_POST["id"] ?? 0);

   $fullname = mysqli_real_escape_string($conn, $_POST["fullname"] ?? "");
   $username = mysqli_real_escape_string($conn, $_POST["username"] ?? "");
   $passwordInput = $_POST["password"] ?? "";

   /* ================= PASSWORD OPTIONAL ================= */
   if (!empty($passwordInput)) {

      $password = md5($passwordInput);

      $sql = "UPDATE user SET
              fullname='$fullname',
              username='$username',
              password='$password'
              WHERE id=$id";
   } else {

      $sql = "UPDATE user SET
              fullname='$fullname',
              username='$username'
              WHERE id=$id";
   }

   if (!mysqli_query($conn, $sql)) {
      echo json_encode([
         "message" => "Gagal update",
         "error" => mysqli_error($conn),
         "sql" => $sql
      ]);
      exit;
   }

   echo json_encode(["message" => "Berhasil diperbarui"]);
   exit;
}

/* ================= DELETE ================= */
if ($method === "POST" && ($_POST["mode"] ?? '') === "delete") {

   $id = (int)$_POST["id"];
   mysqli_query($conn, "DELETE FROM user WHERE id=$id");

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

   $sql = "UPDATE user SET status_user=$status WHERE id=$id";

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
