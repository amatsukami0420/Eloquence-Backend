<?php
require_once '../../config/database.php';
$data = getJsonInput();

if (isset($data['id'])) {
    $stmt = $conn->prepare("DELETE FROM pending_requests WHERE id = ?");
    $stmt->bind_param("s", $data['id']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Request rejected and deleted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete request."]);
    }
    $stmt->close();
}
?>