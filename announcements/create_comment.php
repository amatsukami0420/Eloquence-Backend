<?php
include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->announcement_id) && !empty($data->author_id) && !empty($data->comment_text)) {
    $query = "INSERT INTO announcement_comments (announcement_id, author_id, comment_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        // "iss" = integer, string, string
        $stmt->bind_param("iss", $data->announcement_id, $data->author_id, $data->comment_text);
        
        if($stmt->execute()) {
            $newId = $conn->insert_id;
            
            $fetchQuery = "
                SELECT 
                    c.id, 
                    c.comment_text AS text, 
                    m.name AS author, 
                    DATE_FORMAT(c.created_at, '%b %d, %Y') AS date 
                FROM announcement_comments c
                JOIN members m ON c.author_id = m.id
                WHERE c.id = ?
            ";
            
            $fetchStmt = $conn->prepare($fetchQuery);
            $fetchStmt->bind_param("i", $newId);
            $fetchStmt->execute();
            
            $result = $fetchStmt->get_result();
            $newComment = $result->fetch_assoc();
            
            echo json_encode([
                "success" => true, 
                "newComment" => $newComment
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add comment: " . $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Incomplete data provided."]);
}
?>