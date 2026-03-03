<?php
define('DATA_FILE', __DIR__ . '/data.json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$skills = isset($_POST['skills']) && is_array($_POST['skills'])
    ? array_map('trim', $_POST['skills'])
    : [];
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$department = isset($_POST['department']) ? trim($_POST['department']) : '';

$record = [
    'id' => null,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'address' => $address,
    'country' => $country,
    'gender' => $gender,
    'skills' => $skills,
    'username' => $username,
    'department' => $department,
];

$data = [];
if (file_exists(DATA_FILE)) {
    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true) ?: [];
}
$record['id'] = count($data);
$data[] = $record;

file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: list.php');
exit;
