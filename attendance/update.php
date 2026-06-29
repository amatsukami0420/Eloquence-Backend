<?php
require_once '../config/database.php';
$data = getJsonInput();

if (isset($data['sessionId']) && isset($data['records'])) {
    $sessionId = $data['sessionId'];
    $conn->begin_transaction();

    try {
        // Wipe existing records for this session
        $delStmt = $conn->prepare("DELETE FROM attendance_records WHERE session_id = ?");
        $delStmt->bind_param("i", $sessionId);
        $delStmt->execute();
        $delStmt->close();

        // Re-insert the updated records
        $recordStmt = $conn->prepare("INSERT INTO attendance_records (session_id, member_id, is_present) VALUES (?, ?, ?)");
        foreach ($data['records'] as $memberId => $isPresent) {
            $presentInt = $isPresent ? 1 : 0;
            $recordStmt->bind_param("isi", $sessionId, $memberId, $presentInt);
            $recordStmt->execute();
        }
        $recordStmt->close();

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Attendance updated."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Failed to update attendance."]);
    }
}
?>