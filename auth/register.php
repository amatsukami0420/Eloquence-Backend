<?php
require_once '../config/database.php';

$data = getJsonInput();

if (isset($data['email']) && isset($data['password'])) {
    $id = $data['intake'] . '-' . $data['dept'] . '-' . $data['roll'];
    $name = $data['fullName'];
    $email = $data['email'];
    $phone = $data['cell'];
    $method = $data['paymentMethod'];
    $tid = $data['transactionId'];
    
    $password = $data['password'];

    $stmt = $conn->prepare("INSERT INTO pending_requests (id, name, email, password, phone, payment_method, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $id, $name, $email, $password, $phone, $method, $tid);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registration pending approval."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
}
?>