<?php
require_once '../config/database.php';
$data = getJsonInput();

if (isset($data['title']) && isset($data['date']) && isset($data['records'])) {
    $conn->begin_transaction();

    try {
        // 1. Create the session
        $stmt = $conn->prepare("INSERT INTO attendance_sessions (title, session_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $data['title'], $data['date']);
        $stmt->execute();
        $sessionId = $stmt->insert_id; // Get the newly created session ID
        $stmt->close();

        // 2. Insert all the individual records
        $recordStmt = $conn->prepare("INSERT INTO attendance_records (session_id, member_id, is_present) VALUES (?, ?, ?)");
        
        foreach ($data['records'] as $memberId => $isPresent) {
            $presentInt = $isPresent ? 1 : 0;
            $recordStmt->bind_param("isi", $sessionId, $memberId, $presentInt);
            $recordStmt->execute();
        }
        $recordStmt->close();

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Attendance saved.", "sessionId" => $sessionId]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Failed to save attendance."]);
    }
}
?>