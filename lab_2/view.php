<?php
define('DATA_FILE', __DIR__ . '/data.json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$data = [];
if (file_exists(DATA_FILE)) {
    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true) ?: [];
}
if ($id < 0 || !isset($data[$id])) {
    header('Location: list.php');
    exit;
}
$row = $data[$id];
$skills_text = is_array($row['skills'] ?? null) ? implode(', ', $row['skills']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Record - Lab 2</title>
    <style>
        dl { max-width: 400px; }
        dt { font-weight: bold; margin-top: 10px; }
        dd { margin-left: 0; }
    </style>
</head>
<body>
    <h1>View Registration</h1>
    <p><a href="list.php">Back to list</a> | <a href="edit.php?id=<?php echo $id; ?>">Edit</a></p>
    <dl>
        <dt>First Name</dt>
        <dd><?php echo htmlspecialchars($row['first_name'] ?? ''); ?></dd>
        <dt>Last Name</dt>
        <dd><?php echo htmlspecialchars($row['last_name'] ?? ''); ?></dd>
        <dt>Address</dt>
        <dd><?php echo nl2br(htmlspecialchars($row['address'] ?? '')); ?></dd>
        <dt>Country</dt>
        <dd><?php echo htmlspecialchars($row['country'] ?? ''); ?></dd>
        <dt>Gender</dt>
        <dd><?php echo htmlspecialchars($row['gender'] ?? ''); ?></dd>
        <dt>Skills</dt>
        <dd><?php echo htmlspecialchars($skills_text); ?></dd>
        <dt>Username</dt>
        <dd><?php echo htmlspecialchars($row['username'] ?? ''); ?></dd>
        <dt>Department</dt>
        <dd><?php echo htmlspecialchars($row['department'] ?? ''); ?></dd>
    </dl>
</body>
</html>
