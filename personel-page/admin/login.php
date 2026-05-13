<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

start_secure_session();

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = clean_string($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    try {
        $statement = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username AND is_active = 1 LIMIT 1');
        $statement->execute([':username' => $username]);
        $admin = $statement->fetch();

        if ($admin && password_verify($password, (string) $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_username'] = (string) $admin['username'];
            setcookie('portfolio_admin_name', (string) $admin['username'], [
                'expires' => time() + 86400 * 30,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
    } catch (Throwable $exception) {
        $error = 'Database connection failed. Import database/portfolio.sql and update config/database.php credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Emirhan Demircan</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="./admin.css">
</head>
<body>
  <main class="admin-shell compact">
    <section class="admin-card" aria-labelledby="login-title">
      <p class="section-subtitle">Secure Area</p>
      <h1 class="h2" id="login-title">Portfolio Admin Login</h1>
      <p class="admin-muted">Use the default SQL seed account only for local review, then change the password before hosting.</p>

      <?php if ($error !== ''): ?>
        <p class="admin-alert"><?= e($error) ?></p>
      <?php endif; ?>

      <form method="post" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>
          <span>Username</span>
          <input type="text" name="username" value="admin" required autocomplete="username">
        </label>
        <label>
          <span>Password</span>
          <input type="password" name="password" required autocomplete="current-password" placeholder="admin123">
        </label>
        <button type="submit" class="btn-submit">Login</button>
      </form>
      <a class="admin-link" href="../index.html">Back to portfolio</a>
    </section>
  </main>
</body>
</html>
