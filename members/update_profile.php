<?php
require_once '../config/database.php';
$data = getJsonInput();

if (isset($data['id'])) {
    $id = $data['id'];
    $phone = isset($data['phone']) ? $data['phone'] : '';
    
    if (!empty($data['password'])) {
        $stmt = $conn->prepare("UPDATE members SET phone = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sss", $phone, $data['password'], $id);
    } else {
        $stmt = $conn->prepare("UPDATE members SET phone = ? WHERE id = ?");
        $stmt->bind_param("ss", $phone, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update profile."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "User ID missing."]);
}
?>