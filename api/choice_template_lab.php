<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$id = $_POST['id'] ?? 0;
$template = $_POST['template'] ?? 0;

$response = ["success" => false];

if ($id && $template) {

   $stmt = $conn->prepare("UPDATE laboratorium_detail
        SET template = ?
        WHERE id = ?
    ");

   $stmt->bind_param("ii", $template, $id);

   if ($stmt->execute()) {
      $response["success"] = true;
   }
}

header('Content-Type: application/json');
echo json_encode($response);
