<?php
include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->title) && !empty($data->message)) {
    $query = "UPDATE announcements SET title = ?, message = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        // "ssi" tells MySQL to expect string, string, integer
        $stmt->bind_param("ssi", $data->title, $data->message, $data->id);
        
        if($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update: " . $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Incomplete data provided."]);
}
?>