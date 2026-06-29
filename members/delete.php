<?php
require_once '../config/database.php';

$data = getJsonInput();

if (isset($data['id'])) {
    $id = $data['id'];
    
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Member deleted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Deletion failed."]);
    }
    $stmt->close();
}
?>