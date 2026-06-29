<?php
include_once '../config/database.php';

try {
    $query = "
        SELECT 
            a.id, 
            a.title, 
            a.message, 
            a.type, 
            DATE_FORMAT(a.created_at, '%Y-%m-%d') as date, 
            m.name as author 
        FROM announcements a
        JOIN members m ON a.author_id = m.id
        ORDER BY a.created_at DESC
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $announcements = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        "success" => true, 
        "announcements" => $announcements
    ]);

} catch(Exception $e) {
    echo json_encode([
        "success" => false, 
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>