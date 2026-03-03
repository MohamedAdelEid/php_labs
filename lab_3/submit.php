<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$address    = isset($_POST['address']) ? trim($_POST['address']) : '';
$country    = isset($_POST['country']) ? trim($_POST['country']) : '';
$gender     = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$skillsArr  = isset($_POST['skills']) && is_array($_POST['skills']) ? $_POST['skills'] : [];
$skills     = implode(', ', array_map('trim', $skillsArr));
$username   = isset($_POST['username']) ? trim($_POST['username']) : '';
$department = isset($_POST['department']) ? trim($_POST['department']) : '';

$sql = "INSERT INTO registrations
        (first_name, last_name, address, country, gender, skills, username, department)
        VALUES (?,?,?,?,?,?,?,?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param(
    'ssssssss',
    $first_name,
    $last_name,
    $address,
    $country,
    $gender,
    $skills,
    $username,
    $department
);

$stmt->execute();
$stmt->close();

header('Location: list.php');
exit;