<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $mysqli->prepare("SELECT id, username, password FROM lab4_registrations WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Invalid username or password.';
        } else {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lab4-primary: #2563eb; --lab4-primary-dark: #1d4ed8; --lab4-bg: #f8fafc; --lab4-card: #ffffff; --lab4-border: #e2e8f0; --lab4-error: #dc2626; }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; display: flex; flex-direction: column; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand { font-weight: 700; color: #fff !important; }
        .lab4-nav .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); max-width: 400px; }
        .form-control { border-radius: 10px; border: 1px solid var(--lab4-border); }
        .form-control:focus { border-color: var(--lab4-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .btn-lab4-primary { background: var(--lab4-primary); color: #fff; border: none; border-radius: 10px; font-weight: 600; padding: 0.6rem 1.5rem; }
        .btn-lab4-primary:hover { color: #fff; background: var(--lab4-primary-dark); }
    </style>
</head>
<body>
<?php include __DIR__ . '/_nav_public.php'; ?>

<div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="card login-card w-100">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">Log in</h2>
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">Registration successful. You can log in now.</div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-lab4-primary w-100">Log in</button>
            </form>
            <p class="mt-3 mb-0 text-muted small">Don't have an account? <a href="registration.php">Register</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
