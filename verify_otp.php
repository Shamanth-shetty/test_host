<?php
include 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
  $email = $conn->real_escape_string($_POST['email']);
  $otp = $conn->real_escape_string($_POST['otp']);
  $res = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
  $u = $res->fetch_assoc();
  if ($u) {
    if ($u['otp_code'] == $otp && strtotime($u['otp_expires']) > time()) {
      // clear otp and mark verified field optionally
      $conn->query("UPDATE users SET otp_code=NULL, otp_expires=NULL WHERE id=".$u['id']);
      $msg = "Verified! You can now login.";
    } else $msg = "Invalid or expired OTP.";
  } else $msg = "User not found.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Verify OTP</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Verify OTP</div></div>
  <form method="POST">
    <label>Email</label><input name="email" type="email" required>
    <label>OTP</label><input name="otp" type="text" required>
    <div class="row"><button class="primary">Verify</button><a class="link" href="index.php">Login</a></div>
  </form>
  <?php if($msg) echo "<p>$msg</p>"; ?>
</div></body></html>
