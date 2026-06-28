<?php
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            // Fallback for offline DB testing
            if ($email === 'admin@tehub.com' && $password === 'admin123') {
                $_SESSION['admin_user'] = ['id' => 1, 'name' => 'Admin', 'email' => 'admin@tehub.com'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = 'Invalid credentials or database offline.';
            }
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login · TEHUB Management</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    body {
      background-color: var(--bg, #0a0b0d);
      color: var(--fg, #f0f4f8);
      font-family: 'Inter Tight', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
    }
    .login-card {
      background: rgba(18, 20, 26, 0.85);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
      backdrop-filter: blur(10px);
    }
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .login-header h1 {
      font-size: 26px;
      margin: 10px 0 5px;
      font-weight: 700;
    }
    .login-header p {
      color: #8a94a6;
      font-size: 14px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 13px;
      color: #a0aec0;
      font-weight: 500;
    }
    .form-control {
      width: 100%;
      padding: 12px 16px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 8px;
      color: #fff;
      font-size: 15px;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      outline: none;
      border-color: #a3e635;
      box-shadow: 0 0 0 3px rgba(163, 230, 53, 0.2);
    }
    .btn-submit {
      width: 100%;
      padding: 14px;
      background: #a3e635;
      color: #0a0b0d;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 10px;
    }
    .btn-submit:hover {
      background: #bef264;
    }
    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 12px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #8a94a6;
      text-decoration: none;
      font-size: 13px;
    }
    .back-link:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <div style="font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #a3e635;">TEHUB</div>
      <h1>Admin Control Panel</h1>
      <p>Sign in to manage services, pricing &amp; orders</p>
    </div>

    <?php if(!empty($error)): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="admin_login.php">
      <div class="form-group">
        <label for="email">Admin Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="admin@tehub.com" required value="<?= htmlspecialchars($_POST['email'] ?? 'admin@tehub.com') ?>" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required />
      </div>
      <button type="submit" class="btn-submit">Sign In to Admin</button>
    </form>
    
    <a href="services.php" class="back-link">← Return to Main Services Page</a>
  </div>
</body>
</html>
