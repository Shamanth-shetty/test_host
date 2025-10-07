<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!='agency') header("Location: index.php");
$u = $_SESSION['user'];

// create simple caretaker form
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_caretaker'])) {
  $name = $conn->real_escape_string($_POST['name']);
  $exp = intval($_POST['exp']);
  $rate = floatval($_POST['rate']);
  // ensure agency record exists for this user, create if not
  $are = $conn->query("SELECT * FROM agencies WHERE user_id=".$u['id']." LIMIT 1");
  if (!$are->num_rows) {
    $conn->query("INSERT INTO agencies (user_id, agency_name, city, about) VALUES ({$u['id']},'My Agency','City','About')");
  }
  $agency = $conn->query("SELECT * FROM agencies WHERE user_id=".$u['id']." LIMIT 1")->fetch_assoc();
  $conn->query("INSERT INTO caretakers (agency_id,name,experience_years,hourly_rate) VALUES ({$agency['id']},'$name',$exp,$rate)");
  $msg = "Caretaker added.";
}
$agency = $conn->query("SELECT * FROM agencies WHERE user_id=".$u['id'])->fetch_assoc();
$cts = $agency ? $conn->query("SELECT * FROM caretakers WHERE agency_id=".$agency['id']) : null;
$bookings = $agency ? $conn->query("SELECT b.*, u.name as user_name, c.name as caretaker_name FROM bookings b LEFT JOIN users u ON u.id=b.user_id LEFT JOIN caretakers c ON c.id=b.caretaker_id WHERE b.agency_id=".$agency['id']." ORDER BY b.created_at DESC") : null;
?>
<!doctype html><html><head><meta charset="utf-8"><title>Agency Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Agency Dashboard</div></div>
  <p><a class="link" href="profile.php">Back</a></p>

  <h4>Agency Info</h4>
  <?php if($agency) { echo "<p><b>".htmlspecialchars($agency['agency_name'])."</b> - ".htmlspecialchars($agency['city'])."</p>"; } else { echo "<p>No agency profile yet. Adding caretaker will create basic agency record.</p>"; } ?>

  <h4>Add Caretaker</h4>
  <form method="POST">
    <label>Name</label><input name="name" required>
    <label>Experience (years)</label><input name="exp" type="number" value="1" required>
    <label>Hourly rate</label><input name="rate" required>
    <div class="row"><button class="primary" name="add_caretaker">Add</button></div>
  </form>
  <?php if(isset($msg)) echo "<p style='color:green'>$msg</p>"; ?>

  <h4>My Caretakers</h4>
  <?php if($cts && $cts->num_rows): ?>
    <table class="table"><tr><th>Name</th><th>Exp</th><th>Rate</th></tr>
      <?php while($r=$cts->fetch_assoc()): ?>
        <tr><td><?php echo htmlspecialchars($r['name']);?></td><td><?php echo $r['experience_years'];?></td><td><?php echo $r['hourly_rate'];?></td></tr>
      <?php endwhile; ?>
    </table>
  <?php else: echo "<p>No caretakers yet.</p>"; endif; ?>

  <h4>Bookings</h4>
  <?php if($bookings && $bookings->num_rows): ?>
    <table class="table"><tr><th>Code</th><th>User</th><th>Caretaker</th><th>Date</th><th>Status</th></tr>
    <?php while($b=$bookings->fetch_assoc()): ?>
      <tr><td><?php echo $b['booking_code'];?></td><td><?php echo htmlspecialchars($b['user_name']);?></td><td><?php echo htmlspecialchars($b['caretaker_name']);?></td><td><?php echo $b['booking_date'];?></td><td><?php echo $b['status'];?></td></tr>
    <?php endwhile; ?></table>
  <?php else: echo "<p>No bookings yet.</p>"; endif; ?>

</div></body></html>
