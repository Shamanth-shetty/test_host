<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
$agencyId = intval($_GET['agency'] ?? 0);

if ($id) {
  $res = $conn->query("SELECT c.*, a.agency_name FROM caretakers c JOIN agencies a ON c.agency_id=a.id WHERE c.id=$id LIMIT 1");
  $ct = $res->fetch_assoc();
} elseif ($agencyId) {
  // list caretakers under agency
  $res = $conn->query("SELECT * FROM caretakers WHERE agency_id=$agencyId");
  $list = $res;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Caretaker</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Caretaker</div></div>

  <?php if(isset($ct)): ?>
    <h3><?php echo htmlspecialchars($ct['name']); ?></h3>
    <p><strong>Agency:</strong> <?php echo htmlspecialchars($ct['agency_name']); ?></p>
    <p><strong>Experience:</strong> <?php echo $ct['experience_years']; ?> years</p>
    <p><strong>Specializations:</strong> <?php echo htmlspecialchars($ct['specializations']); ?></p>
    <p><strong>Languages:</strong> <?php echo htmlspecialchars($ct['languages']); ?></p>
    <p><strong>Hourly rate:</strong> ₹<?php echo htmlspecialchars($ct['hourly_rate']); ?></p>

    <div class="row">
      <a class="primary" href="booking.php?caretaker=<?php echo $ct['id']; ?>">Book</a>
      <a class="link" href="chat.php?to=<?php echo $ct['id']; ?>">Chat</a>
    </div>

    <h4>Reviews</h4>
    <?php
      $rres = $conn->query("SELECT r.*, u.name FROM reviews r LEFT JOIN users u ON r.user_id=u.id WHERE r.caretaker_id=".$ct['id']);
      while($r = $rres->fetch_assoc()) {
        echo "<div style='border-bottom:1px solid #eee;padding:8px 0;'><b>".htmlspecialchars($r['name'])."</b> - ".$r['rating']."/5<br>".htmlspecialchars($r['comment'])."</div>";
      }
    ?>

  <?php elseif(isset($list)): ?>
    <h3>Caretakers for agency</h3>
    <table class="table"><tr><th>Name</th><th>Exp</th><th>Rate</th><th></th></tr>
    <?php while($c = $list->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($c['name']); ?></td>
        <td><?php echo $c['experience_years']; ?></td>
        <td>₹<?php echo $c['hourly_rate'];?></td>
        <td><a class="link" href="caretaker.php?id=<?php echo $c['id']; ?>">View</a></td>
      </tr>
    <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No caretaker specified.</p>
  <?php endif; ?>

</div></body></html>
