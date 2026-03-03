<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
}

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $address    = isset($_POST['address']) ? trim($_POST['address']) : '';
    $country    = isset($_POST['country']) ? trim($_POST['country']) : '';
    $gender     = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $skillsArr  = isset($_POST['skills']) && is_array($_POST['skills']) ? $_POST['skills'] : [];
    $skills     = implode(', ', array_map('trim', $skillsArr));
    $username   = isset($_POST['username']) ? trim($_POST['username']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';

    $sql = "UPDATE registrations
            SET first_name = ?, last_name = ?, address = ?, country = ?, gender = ?,
                skills = ?, username = ?, department = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        'ssssssssi',
        $first_name,
        $last_name,
        $address,
        $country,
        $gender,
        $skills,
        $username,
        $department,
        $id
    );
    $stmt->execute();
    $stmt->close();

    header('Location: list.php');
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM registrations WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    header('Location: list.php');
    exit;
}

$currentSkills = array_map('trim', $row['skills'] !== null && $row['skills'] !== '' ? explode(',', $row['skills']) : []);
$countries = ['Egypt', 'USA', 'UK', 'Germany', 'France'];
$all_skills = ['PHP', 'MySQL', 'J2SE', 'PostgreSQL'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 3 - Edit Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="list.php">Lab 3</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Registration</h1>
        <div>
            <a href="list.php" class="btn btn-secondary btn-sm">Back to list</a>
            <a href="view.php?id=<?php echo $id; ?>" class="btn btn-outline-primary btn-sm">View</a>
        </div>
    </div>

    <form method="post" action="edit.php" class="row g-3">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="col-md-6">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name"
                   class="form-control"
                   value="<?php echo htmlspecialchars($row['first_name'] ?? ''); ?>" required>
        </div>
        <div class="col-md-6">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name"
                   class="form-control"
                   value="<?php echo htmlspecialchars($row['last_name'] ?? ''); ?>" required>
        </div>

        <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" rows="3"
                      class="form-control"><?php echo htmlspecialchars($row['address'] ?? ''); ?></textarea>
        </div>

        <div class="col-md-4">
            <label for="country" class="form-label">Country</label>
            <select id="country" name="country" class="form-select">
                <option value="">Select Country</option>
                <?php foreach ($countries as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>"
                        <?php echo ($row['country'] ?? '') === $c ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label d-block">Gender</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="male" name="gender" value="Male"
                    <?php echo ($row['gender'] ?? '') === 'Male' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="female" name="gender" value="Female"
                    <?php echo ($row['gender'] ?? '') === 'Female' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="female">Female</label>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label d-block">Skills</label>
            <?php foreach ($all_skills as $s): ?>
                <?php $checked = in_array($s, $currentSkills, true) ? 'checked' : ''; ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           id="skill_<?php echo strtolower($s); ?>"
                           name="skills[]"
                           value="<?php echo htmlspecialchars($s); ?>" <?php echo $checked; ?>>
                    <label class="form-check-label" for="skill_<?php echo strtolower($s); ?>">
                        <?php echo htmlspecialchars($s); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username"
                   class="form-control"
                   value="<?php echo htmlspecialchars($row['username'] ?? ''); ?>" required>
        </div>

        <div class="col-md-6">
            <label for="department" class="form-label">Department</label>
            <input type="text" id="department" name="department"
                   class="form-control"
                   value="<?php echo htmlspecialchars($row['department'] ?? ''); ?>">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="list.php" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>