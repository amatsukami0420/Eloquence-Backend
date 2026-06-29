<?php
require_once '../config/database.php';

$data = getJsonInput();

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password == $row['password']) {
            unset($row['password']);
            echo json_encode(["success" => true, "user" => $row]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid credentials."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing credentials."]);
}
?>