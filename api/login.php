<?php
header("Content-Type: application/json");
session_start();

require_once "../database/db.php";

$username = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
   echo json_encode([
      "success" => false,
      "message" => "Username dan password wajib diisi"
   ]);
   exit;
}

/* ================= CEK USER ================= */
$sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
$q = mysqli_query($conn, $sql);

if (!$q || mysqli_num_rows($q) == 0) {
   echo json_encode([
      "success" => false,
      "message" => "User tidak ditemukan"
   ]);
   exit;
}

$user = mysqli_fetch_assoc($q);

/* ================= CEK STATUS ================= */
if ($user['status_user'] != 1) {
   echo json_encode([
      "success" => false,
      "message" => "User tidak aktif"
   ]);
   exit;
}

/* ================= CEK PASSWORD MD5 ================= */
if (md5($password) !== $user['password']) {
   echo json_encode([
      "success" => false,
      "message" => "Password salah"
   ]);
   exit;
}

/* ================= CEK ROLE ================= */
if (strtolower($user['roles']) !== 'laboratorium') {
   echo json_encode([
      "success" => false,
      "message" => "Akses ditolak. Hanya user Laboratorium yang dapat login."
   ]);
   exit;
}

/* ================= GENERATE SESSION ================= */
$token = bin2hex(random_bytes(32));

mysqli_query($conn, "
    UPDATE user 
    SET session_token = '$token'
    WHERE id = {$user['id']}
");

/* ================= SET SESSION ================= */
$_SESSION['user_id'] = $user['id'];
$_SESSION['fullname'] = $user['fullname'];
$_SESSION['roles'] = $user['roles'];
$_SESSION['username'] = $user['username'];
$_SESSION['token'] = $token;

/* ================= RESPONSE ================= */
echo json_encode([
   "success" => true,
   "message" => "Login successful",
   "role" => $user['roles'],
   "name" => $user['fullname']
]);
exit;
