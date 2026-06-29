<?php
require_once '../../config/database.php';

$query = "SELECT id, name, email, phone, age, payment_method, transaction_id, created_at FROM pending_requests ORDER BY created_at ASC";
$result = $conn->query($query);

$pending = [];
while ($row = $result->fetch_assoc()) {
    $pending[] = $row;
}

echo json_encode(["success" => true, "pending" => $pending]);
?>