<?php
include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->title) && !empty($data->message) && !empty($data->author_id)) {
    $type = !empty($data->type) ? $data->type : 'General';
    
    $query = "INSERT INTO announcements (title, message, type, author_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        // "ssss" tells MySQL to expect 4 strings
        $stmt->bind_param("ssss", $data->title, $data->message, $type, $data->author_id);
        
        if($stmt->execute()) {
            $newId = $conn->insert_id;
            
            $fetchQuery = "
                SELECT 
                    a.id, 
                    a.title, 
                    a.message, 
                    a.type, 
                    DATE_FORMAT(a.created_at, '%Y-%m-%d') as date, 
                    m.name as author 
                FROM announcements a 
                JOIN members m ON a.author_id = m.id 
                WHERE a.id = ?
            ";
            $fetchStmt = $conn->prepare($fetchQuery);
            $fetchStmt->bind_param("i", $newId);
            $fetchStmt->execute();
            
            $result = $fetchStmt->get_result();
            $newAnnouncement = $result->fetch_assoc();
            
            echo json_encode([
                "success" => true, 
                "newAnnouncement" => $newAnnouncement
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to execute insertion: " . $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Incomplete data. Title, message, and author_id are required."]);
}
?>