<?php
include 'db.php'; $msg='';
$token = $_GET['token'] ?? '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $token = $conn->real_escape_string($_POST['token']);
  $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $res = $conn->query("SELECT * FROM users WHERE reset_token='$token' LIMIT 1");
  if ($u = $res->fetch_assoc()) {
    $conn->query("UPDATE users SET password='$pw', reset_token=NULL WHERE id=".$u['id']);
    $msg = "Password reset. You can login.";
  } else $msg = "Invalid token.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Reset</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Reset Password</div></div>
  <form method="POST">
    <label>Token</label><input name="token" value="<?php echo htmlspecialchars($token); ?>" required>
    <label>New Password</label><input name="password" type="password" required>
    <div class="row"><button class="primary">Set Password</button><a class="link" href="index.php">Back</a></div>
  </form>
  <?php if($msg) echo "<p>$msg</p>"; ?>
</div></body></html>
