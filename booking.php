<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) header("Location: index.php");
$me = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['book'])) {
  $caretaker = intval($_POST['caretaker']);
  $date = $_POST['date'];
  $start = $_POST['start'];
  $duration = intval($_POST['duration']);
  $amount = floatval($_POST['amount']);
  $booking_code = 'BK'.time().rand(100,999);
  // find agency_id for caretaker
  $cres = $conn->query("SELECT agency_id FROM caretakers WHERE id=$caretaker LIMIT 1");
  $aid = $cres->fetch_assoc()['agency_id'] ?? null;
  $stmt = $conn->prepare("INSERT INTO bookings (booking_code,user_id,agency_id,caretaker_id,booking_date,start_time,duration_hours,amount) VALUES (?,?,?,?,?,?,?,?)");
  $stmt->bind_param("siiiisid",$booking_code,$me['id'],$aid,$caretaker,$date,$start,$duration,$amount);
  if ($stmt->execute()) {
    $msg = "Booking created (mock payment pending). <a href='mock_payment.php?code=$booking_code'>Pay now (mock)</a>";
  } else { $msg = "Failed to create booking"; }
}

// my bookings
$myb = $conn->query("SELECT b.*, c.name as caretaker_name, a.agency_name FROM bookings b LEFT JOIN caretakers c ON c.id=b.caretaker_id LEFT JOIN agencies a ON a.id=b.agency_id WHERE b.user_id=".$me['id']." ORDER BY b.created_at DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Bookings</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Bookings</div></div>

  <?php if(isset($_GET['caretaker'])): $cid=intval($_GET['caretaker']); ?>
    <h4>Book caretaker (id <?php echo $cid;?>)</h4>
    <form method="POST">
      <input type="hidden" name="caretaker" value="<?php echo $cid;?>">
      <label>Date</label><input type="date" name="date" required>
      <label>Start time</label><input type="time" name="start" required>
      <label>Duration (hours)</label><input type="number" name="duration" value="2" required>
      <label>Amount (₹) (mock)</label><input type="text" name="amount" value="500" required>
      <div class="row"><button class="primary" name="book">Book</button></div>
    </form>
  <?php endif; ?>

  <h4>My bookings</h4>
  <table class="table"><tr><th>Code</th><th>Caretaker</th><th>Date</th><th>Start</th><th>Status</th><th>Amount</th></tr>
  <?php while($b = $myb->fetch_assoc()): ?>
    <tr>
      <td><?php echo $b['booking_code'];?></td>
      <td><?php echo htmlspecialchars($b['caretaker_name'].' / '.$b['agency_name']);?></td>
      <td><?php echo $b['booking_date'];?></td>
      <td><?php echo $b['start_time'];?></td>
      <td><?php echo $b['status'];?></td>
      <td><?php echo $b['amount'];?> <?php if($b['payment_status']=='none') echo "<a class='link' href='mock_payment.php?code={$b['booking_code']}'>Pay</a>"; else echo "(paid)";?></td>
    </tr>
  <?php endwhile; ?>
</table>
<p><a class="link" href="profile.php">Back</a></p>
</div></body></html>
