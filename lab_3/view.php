<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
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

$skills_text = $row['skills'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 3 - View Record</title>
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
        <h1 class="h3 mb-0">View Registration</h1>
        <div>
            <a href="list.php" class="btn btn-secondary btn-sm">Back to list</a>
            <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">First Name</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['first_name']); ?></dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['last_name']); ?></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($row['address'] ?? '')); ?></dd>

                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['country'] ?? ''); ?></dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['gender'] ?? ''); ?></dd>

                <dt class="col-sm-3">Skills</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($skills_text); ?></dd>

                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['username'] ?? ''); ?></dd>

                <dt class="col-sm-3">Department</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['department'] ?? ''); ?></dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></dd>
            </dl>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>