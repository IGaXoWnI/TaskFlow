<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

require_once 'con/con.Class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'] ?? null;
    $newStatus = $_POST['newStatus'] ?? null;

    if ($taskId && $newStatus) {
        $db = new database();
        $pdo = $db->connect();

        $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE task_id = :task_id");
        $success = $stmt->execute([
            ':status' => $newStatus,
            ':task_id' => $taskId
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}

header('HTTP/1.1 400 Bad Request');
echo json_encode(['success' => false]); 