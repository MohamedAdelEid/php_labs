<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $mysqli->prepare("SELECT profile_picture FROM lab4_registrations WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($row && $row['profile_picture'] && file_exists(__DIR__ . '/' . $row['profile_picture'])) {
        @unlink(__DIR__ . '/' . $row['profile_picture']);
    }
    $stmt = $mysqli->prepare("DELETE FROM lab4_registrations WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: list.php');
exit;
