<?php
include 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $role = in_array($_POST['role'],['user','agency','admin'])?$_POST['role']:'user';
  $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (name,email,phone,password,role) VALUES (?,?,?,?,?)");
  $stmt->bind_param("sssss",$name,$email,$phone,$passwordHash,$role);
  if ($stmt->execute()) {
    // create OTP (mock) and store
    $otp = rand(100000,999999);
    $expires = date('Y-m-d H:i:s', time() + 300);
    $conn->query("UPDATE users SET otp_code='$otp', otp_expires='$expires' WHERE email='$email'");
    $msg = "Registered. Mock OTP: <strong>$otp</strong> (valid 5 minutes) — go to Verify OTP.";
  } else {
    $msg = "Error: email may already exist.";
  }
}
?>
<!doctype html>
<html><head>
<meta charset="utf-8"><title>Register</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Register</div></div>
  <form method="POST">
    <label>Full name</label><input type="text" name="name" required>
    <label>Email</label><input type="email" name="email" required>
    <label>Phone</label><input type="text" name="phone" required>
    <label>Password</label><input type="password" name="password" required>
    <label>Role</label>
    <select name="role"><option value="user">User</option><option value="agency">Agency</option><option value="admin">Admin</option></select>
    <div class="row"><button class="primary" name="register">Register</button><a class="link" href="index.php">Back to login</a></div>
  </form>
  <?php if($msg) echo "<p>$msg</p>"; ?>
  <p>For testing, OTP is shown above when registration succeeds.</p>
  <p><a href="verify_otp.php" class="link">Verify OTP</a></p>
</div>
</body></html>
