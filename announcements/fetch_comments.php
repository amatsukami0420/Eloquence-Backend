<?php
include_once '../config/database.php';

$announcement_id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["success" => false, "message" => "No ID provided"]));

$query = "
    SELECT 
        c.id, 
        c.comment_text AS text, 
        m.name AS author, 
        DATE_FORMAT(c.created_at, '%b %d, %Y') AS date 
    FROM announcement_comments c
    JOIN members m ON c.author_id = m.id
    WHERE c.announcement_id = ?
    ORDER BY c.created_at ASC
";

$stmt = $conn->prepare($query);

if ($stmt) {
    // "i" means integer
    $stmt->bind_param("i", $announcement_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        "success" => true, 
        "comments" => $comments
    ]);
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Database error: " . $conn->error
    ]);
}
?>