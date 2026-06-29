<?php
require_once '../../config/database.php';
$data = getJsonInput();

if (isset($data['id'])) {
    $id = $data['id'];
    
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT * FROM pending_requests WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pendingUser = $result->fetch_assoc();
        $stmt->close();

        if (!$pendingUser) {
            throw new Exception("Pending request not found.");
        }

        $insertStmt = $conn->prepare("INSERT INTO members (id, name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, 'General Member')");
        $insertStmt->bind_param("sssss", $pendingUser['id'], $pendingUser['name'], $pendingUser['email'], $pendingUser['password'], $pendingUser['phone']);
        $insertStmt->execute();
        $insertStmt->close();

        $deleteStmt = $conn->prepare("DELETE FROM pending_requests WHERE id = ?");
        $deleteStmt->bind_param("s", $id);
        $deleteStmt->execute();
        $deleteStmt->close();

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Member approved."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Approval failed: " . $e->getMessage()]);
    }
}
?>