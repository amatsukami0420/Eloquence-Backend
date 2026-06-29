<?php
require_once '../config/database.php';
$data = getJsonInput();

if (isset($data['id'])) {
    // Because of the ON DELETE CASCADE we put in the database schema, 
    // deleting the session automatically deletes all linked attendance_records.
    $stmt = $conn->prepare("DELETE FROM attendance_sessions WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Session deleted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete session."]);
    }
    $stmt->close();
}
?>