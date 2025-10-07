<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) header("Location: index.php");
$u = $_SESSION['user'];

// update profile JSON
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_profile'])) {
  $profile = [
    'name'=>$_POST['name'],
    'city'=>$_POST['city'],
    'medical_conditions'=>$_POST['medical'],
    'preferences'=>$_POST['preferences']
  ];
  $pjson = $conn->real_escape_string(json_encode($profile));
  $conn->query("UPDATE users SET name='". $conn->real_escape_string($_POST['name'])."', profile='$pjson' WHERE id=".$u['id']);
  // refresh session data
  $res = $conn->query("SELECT * FROM users WHERE id=".$u['id']);
  $_SESSION['user'] = $res->fetch_assoc();
  $u = $_SESSION['user'];
  $msg = "Profile saved.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Welcome, <?php echo htmlspecialchars($u['name']); ?> <span class="role-tag"><?php echo $u['role']; ?></span></div></div>

  <p><a class="link" href="search.php">Search agencies & caretakers</a> | <a class="link" href="chat.php">Messages</a> | <a class="link" href="booking.php">My Bookings</a> | 
  <?php if($u['role']=='agency') echo "<a class='link' href='agency_dashboard.php'>Agency Dashboard</a>"; ?>
  <?php if($u['role']=='admin') echo "<a class='link' href='admin_dashboard.php'>Admin Dashboard</a>"; ?>
  | <a class="link" href="logout.php">Logout</a></p>

  <h3>Profile</h3>
  <?php $profile = json_decode($u['profile'] ?? '{}', true); ?>
  <form method="POST">
    <label>Full name</label><input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>" required>
    <label>City</label><input type="text" name="city" value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>">
    <label>Medical conditions</label><textarea name="medical"><?php echo htmlspecialchars($profile['medical_conditions'] ?? ''); ?></textarea>
    <label>Preferences (comma separated)</label><input type="text" name="preferences" value="<?php echo htmlspecialchars($profile['preferences'] ?? ''); ?>">
    <div class="row"><button class="primary" name="save_profile">Save profile</button></div>
  </form>

  <?php if(isset($msg)) echo "<p style='color:green'>$msg</p>"; ?>
</div></body></html>
