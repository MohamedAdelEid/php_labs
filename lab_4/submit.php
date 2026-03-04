<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

$errors = [];

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$address    = isset($_POST['address']) ? trim($_POST['address']) : '';
$country    = isset($_POST['country']) ? trim($_POST['country']) : '';
$gender     = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$skillsArr  = isset($_POST['skills']) && is_array($_POST['skills']) ? $_POST['skills'] : [];
$skills     = implode(', ', array_map('trim', $skillsArr));
$username   = isset($_POST['username']) ? trim($_POST['username']) : '';
$password   = isset($_POST['password']) ? $_POST['password'] : '';

if ($e = validateFirstName($first_name)) $errors['first_name'] = $e;
if ($e = validateLastName($last_name)) $errors['last_name'] = $e;
if ($e = validateAddress($address)) $errors['address'] = $e;
if ($e = validateCountry($country)) $errors['country'] = $e;
if ($e = validateGender($gender)) $errors['gender'] = $e;
if ($e = validateSkills($skillsArr)) $errors['skills'] = $e;
if ($e = validateUsername($username)) $errors['username'] = $e;
if ($e = validatePassword($password)) $errors['password'] = $e;

if (!empty($_FILES['profile_picture']['name'])) {
    if ($e = validateProfilePicture($_FILES['profile_picture'])) {
        $errors['profile_picture'] = $e;
    }
}

// Check username unique
if (!isset($errors['username'])) {
    $stmt = $mysqli->prepare("SELECT id FROM lab4_registrations WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        $errors['username'] = 'This username is already taken.';
    }
    $stmt->close();
}

if (!empty($errors)) {
    session_start();
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = [
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'address'    => $address,
        'country'    => $country,
        'gender'     => $gender,
        'skills'     => $skillsArr,
        'username'   => $username,
    ];
    header('Location: registration.php');
    exit;
}

$profile_path = null;
if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
    if ($ext === 'jpeg') $ext = 'jpg';
    $filename = 'profile_' . uniqid() . '_' . preg_replace('/[^a-z0-9_]/', '', $username) . '.' . $ext;
    $target = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
        $profile_path = 'uploads/' . $filename;
    }
}

$password_hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO lab4_registrations
        (first_name, last_name, address, country, gender, skills, username, password, profile_picture)
        VALUES (?,?,?,?,?,?,?,?,?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param(
    'sssssssss',
    $first_name,
    $last_name,
    $address,
    $country,
    $gender,
    $skills,
    $username,
    $password_hashed,
    $profile_path
);

$stmt->execute();
$stmt->close();

if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['form_errors'])) unset($_SESSION['form_errors']);
if (isset($_SESSION['form_old'])) unset($_SESSION['form_old']);

if (!empty($_SESSION['user_id'])) {
    header('Location: list.php');
} else {
    header('Location: login.php?registered=1');
}
exit;
