<?php
header("Content-Type: application/json");
session_start();

require_once "../database/db.php";

$userId = $_SESSION['user_id'] ?? null;

/* ================= HAPUS TOKEN DB ================= */
if ($userId) {
   mysqli_query($conn, "
        UPDATE user 
        SET session_token = NULL
        WHERE id = $userId
    ");
}

/* ================= DESTROY SESSION ================= */
session_unset();
session_destroy();

echo json_encode([
   "success" => true,
   "message" => "Logout berhasil"
]);
exit;
