<?php
session_start();
include 'db.php';
if (isset($_SESSION['user'])) header("Location: profile.php");

// login handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $email = $conn->real_escape_string($_POST['email']);
  $res = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
  $u = $res->fetch_assoc();
  if ($u && password_verify($_POST['password'], $u['password'])) {
    $_SESSION['user'] = $u;
    header("Location: profile.php"); exit;
  } else {
    $error = "Invalid credentials";
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Nammcare Prototype - Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">NAMMCARE</div>
      <div class="h-title">Prototype — Login</div>
    </div>

    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div class="row">
        <button class="primary" name="login">Login</button>
        <a class="link" href="register.php">Register</a>
        <a class="link" href="forgot.php">Forgot?</a>
      </div>
    </form>

    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <hr>
    <p>Quick demo accounts you can create: user / agency / admin via Register page.</p>
  </div>
</body>
</html>
