<?php
define('DATA_FILE', __DIR__ . '/data.json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
if (isset($_POST['id']) && $_POST['id'] !== '') {
    $id = (int)$_POST['id'];
}
$data = [];
if (file_exists(DATA_FILE)) {
    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true) ?: [];
}
if ($id < 0 || !isset($data[$id])) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data[$id]['first_name'] = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $data[$id]['last_name'] = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $data[$id]['address'] = isset($_POST['address']) ? trim($_POST['address']) : '';
    $data[$id]['country'] = isset($_POST['country']) ? trim($_POST['country']) : '';
    $data[$id]['gender'] = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $data[$id]['skills'] = isset($_POST['skills']) && is_array($_POST['skills'])
        ? array_map('trim', $_POST['skills'])
        : [];
    $data[$id]['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
    $data[$id]['department'] = isset($_POST['department']) ? trim($_POST['department']) : '';
    file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: list.php');
    exit;
}

$row = $data[$id];
$skills = $row['skills'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record - Lab 2</title>
</head>
<body>
    <h1>Edit Registration</h1>
    <p><a href="list.php">Back to list</a> | <a href="view.php?id=<?php echo $id; ?>">View</a></p>
    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($row['first_name'] ?? ''); ?>" required><br><br>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($row['last_name'] ?? ''); ?>" required><br><br>
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($row['address'] ?? ''); ?></textarea><br><br>
        <label for="country">Country</label>
        <select id="country" name="country">
            <option value="">Select Country</option>
            <?php
            $countries = ['Egypt', 'USA', 'UK', 'Germany', 'France'];
            $current = $row['country'] ?? '';
            foreach ($countries as $c) {
                $sel = ($c === $current) ? ' selected' : '';
                echo '<option value="' . htmlspecialchars($c) . '"' . $sel . '>' . htmlspecialchars($c) . '</option>';
            }
            ?>
        </select><br><br>
        <label>Gender</label>
        <input type="radio" id="male" name="gender" value="Male" <?php echo ($row['gender'] ?? '') === 'Male' ? 'checked' : ''; ?>>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="Female" <?php echo ($row['gender'] ?? '') === 'Female' ? 'checked' : ''; ?>>
        <label for="female">Female</label><br><br>
        <label>Skills</label><br>
        <?php
        $all_skills = ['PHP', 'MySQL', 'J2SE', 'PostgreSQL'];
        foreach ($all_skills as $s):
            $checked = in_array($s, $skills) ? ' checked' : '';
        ?>
        <input type="checkbox" id="skill_<?php echo strtolower($s); ?>" name="skills[]" value="<?php echo htmlspecialchars($s); ?>"<?php echo $checked; ?>>
        <label for="skill_<?php echo strtolower($s); ?>"><?php echo htmlspecialchars($s); ?></label>
        <?php endforeach; ?><br><br>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username'] ?? ''); ?>" required><br><br>
        <label for="department">Department</label>
        <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($row['department'] ?? ''); ?>"><br><br>
        <button type="submit">Update</button>
        <a href="list.php">Cancel</a>
    </form>
</body>
</html>
