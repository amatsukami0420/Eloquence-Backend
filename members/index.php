<?php
require_once '../config/database.php';

$query = "SELECT id, name, email, role, phone FROM members ORDER BY created_at DESC";
$result = $conn->query($query);

$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

echo json_encode(["success" => true, "members" => $members]);
?>