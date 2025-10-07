<?php
include 'db.php';
$code = $conn->real_escape_string($_GET['code'] ?? '');
$res = $conn->query("SELECT * FROM bookings WHERE booking_code='$code' LIMIT 1");
$b = $res->fetch_assoc();
$msg = '';
if ($b && isset($_POST['pay'])) {
  $conn->query("UPDATE bookings SET payment_status='mock_paid', status='confirmed' WHERE id=".$b['id']);
  $msg = "Mock payment successful. Booking confirmed.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Mock Payment</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Mock Payment</div></div>
  <?php if(!$b) { echo "<p>Booking not found.</p>"; } else { ?>
    <p>Booking: <?php echo $b['booking_code'];?></p>
    <p>Amount: ₹<?php echo $b['amount'];?></p>
    <form method="POST"><button class="primary" name="pay">Simulate Pay Now</button></form>
    <?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
  <?php } ?>
  <p><a class="link" href="profile.php">Back</a></p>
</div></body></html>
