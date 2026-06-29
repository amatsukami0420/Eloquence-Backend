<?php
require_once '../config/database.php';

$data = getJsonInput();

if (isset($data['email']) && isset($data['name'])) {
    $id = $data['intake'] . '-' . $data['dept'] . '-' . $data['roll'];
    $name = $data['name'];
    $email = $data['email'];
    $role = $data['role'];
    $password = $data['password'];

    $stmt = $conn->prepare("INSERT INTO members (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $id, $name, $email, $password, $role);

    if ($stmt->execute()) {
        $newMember = ["id" => $id, "name" => $name, "email" => $email, "role" => $role, "phone" => ""];
        echo json_encode(["success" => true, "newMember" => $newMember]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }
    $stmt->close();
}
?>