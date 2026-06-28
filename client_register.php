<?php
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (!empty($name) && !empty($email) && !empty($password)) {
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            if ($pdo) {
                // Check existing email
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'An account with this email already exists.';
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmtIns = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'client')");
                    if ($stmtIns->execute([$name, $email, $hash])) {
                        $client_id = $pdo->lastInsertId();
                        $_SESSION['client_user'] = [
                            'id' => $client_id,
                            'name' => $name,
                            'email' => $email
                        ];
                        header('Location: client_dashboard.php');
                        exit;
                    } else {
                        $error = 'Registration failed. Please try again.';
                    }
                }
            } else {
                $error = 'Database offline. Cannot register.';
            }
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client Registration · TEHUB Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    body {
      background-color: #0a0b0d;
      color: #f0f4f8;
      font-family: 'Inter Tight', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px 0;
    }
    .register-card {
      background: rgba(18, 20, 26, 0.85);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 40px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
      backdrop-filter: blur(10px);
    }
    .header-text {
      text-align: center;
      margin-bottom: 25px;
    }
    .header-text h1 {
      font-size: 24px;
      margin: 10px 0 5px;
    }
    .header-text p {
      color: #8a94a6;
      font-size: 14px;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
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
      margin-top: 10px;
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
    .auth-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #8a94a6;
    }
    .auth-footer a {
      color: #a3e635;
      text-decoration: none;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="register-card">
    <div class="header-text">
      <div style="font-size: 22px; font-weight: bold; letter-spacing: 2px; color: #a3e635;">TEHUB</div>
      <h1>Create Client Account</h1>
      <p>Sign up to order services &amp; track projects</p>
    </div>

    <?php if(!empty($error)): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="client_register.php">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="john@company.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="At least 6 characters" required />
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat password" required />
      </div>
      <button type="submit" class="btn-submit">Create Account &amp; Login</button>
    </form>
    
    <div class="auth-footer">
      Already have an account? <a href="client_login.php">Sign In</a>
    </div>
  </div>
</body>
</html>
