<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}
$first_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
$skills = isset($_POST['skills']) && is_array($_POST['skills']) ? array_map('htmlspecialchars', $_POST['skills']) : [];
$department = isset($_POST['department']) ? htmlspecialchars($_POST['department']) : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$title = $gender === 'Female' ? 'Miss' : 'Mr.';
$skills_text = implode(', ', $skills);
$full_name = $first_name . ' ' . $last_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review</title>
</head>
<body>
    <p>Thanks <?php echo $title; ?> <?php echo $first_name . ' ' . $last_name; ?></p>
    <h2>Please Review Your Information:</h2>
    <p><strong>Name:</strong> <?php echo $full_name; ?></p>
    <p><strong>Address:</strong> <?php echo $address; ?></p>
    <p><strong>Your Skills:</strong> <?php echo $skills_text; ?></p>
    <p><strong>Department:</strong> <?php echo $department; ?></p>
    <button type="button" onclick="window.print()">Print</button>
</body>
</html>
