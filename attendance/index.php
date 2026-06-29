<?php
require_once '../config/database.php';

// Fetch all sessions
$sessionQuery = "SELECT id, title, session_date as date FROM attendance_sessions ORDER BY session_date DESC";
$sessionsResult = $conn->query($sessionQuery);
$sessions = [];

while ($session = $sessionsResult->fetch_assoc()) {
    $session['records'] = new stdClass(); // Initialize as empty object
    $sessions[$session['id']] = $session;
}

// Fetch all records and map them to their respective sessions
$recordsQuery = "SELECT session_id, member_id, is_present FROM attendance_records";
$recordsResult = $conn->query($recordsQuery);

while ($record = $recordsResult->fetch_assoc()) {
    $s_id = $record['session_id'];
    $m_id = $record['member_id'];
    $present = (bool)$record['is_present'];

    if (isset($sessions[$s_id])) {
        // Build the key-value pair map React expects
        if (is_object($sessions[$s_id]['records'])) {
            $sessions[$s_id]['records'] = []; // Convert to array first time we add a record
        }
        $sessions[$s_id]['records'][$m_id] = $present;
    }
}

// Re-index array to remove IDs as keys
$finalSessions = array_values($sessions);

echo json_encode(["success" => true, "sessions" => $finalSessions]);
?>