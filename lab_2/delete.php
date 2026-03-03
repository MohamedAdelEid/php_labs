<?php
define('DATA_FILE', __DIR__ . '/data.json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
if ($id < 0) {
    header('Location: list.php');
    exit;
}
$data = [];
if (file_exists(DATA_FILE)) {
    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true) ?: [];
}
if (isset($data[$id])) {
    array_splice($data, $id, 1);
    file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
header('Location: list.php');
exit;
