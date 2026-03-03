<?php
define('DATA_FILE', __DIR__ . '/data.json');

$data = [];
if (file_exists(DATA_FILE)) {
    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations List - Lab 2</title>
    <style>
        table { border-collapse: collapse; margin: 1em 0; }
        th, td { border: 1px solid #333; padding: 8px 12px; text-align: left; }
        th { background: #eee; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Stored Registrations</h1>
    <p><a href="registration.php">Add new registration</a></p>
    <?php if (empty($data)): ?>
        <p>No data yet. <a href="registration.php">Register</a> to add the first record.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['country'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['department'] ?? ''); ?></td>
                        <td class="actions">
                            <a href="view.php?id=<?php echo $index; ?>">View</a>
                            <a href="edit.php?id=<?php echo $index; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $index; ?>" onclick="return confirm('Delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
