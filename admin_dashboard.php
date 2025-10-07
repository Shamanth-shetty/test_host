<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin') header("Location: index.php");

// simple actions: verify agency
if (isset($_GET['verify'])) {
  $id = intval($_GET['verify']);
  $conn->query("UPDATE agencies SET is_verified=1 WHERE id=$id");
}
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 50");
$agencies = $conn->query("SELECT a.*, u.name as owner FROM agencies a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.created_at DESC LIMIT 50");
$bookings = $conn->query("SELECT COUNT(*) as cnt FROM bookings");
$stats = $bookings->fetch_assoc();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Admin Dashboard</div></div>
  <p><a class="link" href="profile.php">Back</a></p>

  <h4>Stats</h4>
  <p>Total bookings (count): <?php echo $stats['cnt'];?></p>

  <h4>Recent Agencies</h4>
  <table class="table"><tr><th>Agency</th><th>Owner</th><th>Verified</th><th>Action</th></tr>
    <?php while($a = $agencies->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($a['agency_name']);?></td>
        <td><?php echo htmlspecialchars($a['owner']);?></td>
        <td><?php echo $a['is_verified']? 'Yes':'No';?></td>
        <td><?php if(!$a['is_verified']) echo "<a class='link' href='?verify={$a['id']}'>Verify</a>"; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h4>Recent Users</h4>
  <table class="table"><tr><th>Name</th><th>Email</th><th>Role</th></tr>
  <?php while($u = $users->fetch_assoc()): ?>
    <tr><td><?php echo htmlspecialchars($u['name']);?></td><td><?php echo htmlspecialchars($u['email']);?></td><td><?php echo $u['role'];?></td></tr>
  <?php endwhile; ?>
  </table>
</div></body></html>
