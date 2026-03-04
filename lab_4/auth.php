<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (empty($_SESSION['user_id']) || empty($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }
}

function getLoggedInUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}
