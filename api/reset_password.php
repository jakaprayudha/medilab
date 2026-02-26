<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$username = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

if (!$username) {
   echo json_encode([
      "success" => false,
      "message" => "Email / Username wajib diisi"
   ]);
   exit;
}

/* ================= CEK USER ================= */
$q = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' LIMIT 1");

if (!$q || mysqli_num_rows($q) == 0) {
   echo json_encode([
      "success" => false,
      "message" => "User tidak ditemukan"
   ]);
   exit;
}

$user = mysqli_fetch_assoc($q);

/* ================= GENERATE PASSWORD RANDOM ================= */
$newPassword = rand(100000, 999999); // angka 6 digit
$hash = md5($newPassword);

/* ================= UPDATE DB ================= */
mysqli_query($conn, "
    UPDATE user 
    SET password = '$hash'
    WHERE id = {$user['id']}
");

/* ================= RESPONSE ================= */
echo json_encode([
   "success" => true,
   "message" => "Password berhasil direset",
   "new_password" => $newPassword
]);
exit;
