<?php
include 'db.php'; $msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = $conn->real_escape_string($_POST['email']);
  $res = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
  if ($u = $res->fetch_assoc()) {
    $token = bin2hex(random_bytes(16));
    $conn->query("UPDATE users SET reset_token='$token' WHERE id=".$u['id']);
    // In production, email the token. For prototype we display it.
    $msg = "Reset token (mock): $token — use reset.php?token=$token";
  } else $msg = "Email not found.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Forgot</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Forgot Password</div></div>
  <form method="POST"><label>Email</label><input name="email" type="email" required>
    <div class="row"><button class="primary">Generate Reset Token</button><a class="link" href="index.php">Back</a></div>
  </form>
  <?php if($msg) echo "<p>$msg</p>"; ?>
</div></body></html>
