<?php
require_once '../config/database.php';
$data = getJsonInput();

if (isset($data['id']) && isset($data['name']) && isset($data['email']) && isset($data['role'])) {
    $stmt = $conn->prepare("UPDATE members SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssss", $data['name'], $data['email'], $data['role'], $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Member updated."]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed."]);
    }
    $stmt->close();
}
?>