<?php
include 'db.php';
$q = $conn->real_escape_string($_GET['q'] ?? '');
$city = $conn->real_escape_string($_GET['city'] ?? '');
$min_rating = floatval($_GET['min_rating'] ?? 0);

/* basic agencies query */
$where = "1=1";
if ($q) $where .= " AND agency_name LIKE '%$q%'";
if ($city) $where .= " AND city LIKE '%$city%'";
if ($min_rating) $where .= " AND rating >= $min_rating";
$agencies = $conn->query("SELECT * FROM agencies WHERE $where ORDER BY rating DESC LIMIT 50");

$caretakers = [];
// optionally show caretakers too (simple)
$ctq = $conn->real_escape_string($_GET['ct'] ?? '');
if ($ctq) {
  $resct = $conn->query("SELECT c.*, a.agency_name FROM caretakers c JOIN agencies a ON c.agency_id=a.id WHERE c.name LIKE '%$ctq%' LIMIT 50");
} else {
  $resct = $conn->query("SELECT c.*, a.agency_name FROM caretakers c JOIN agencies a ON c.agency_id=a.id LIMIT 50");
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Search</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Search Agencies & Caretakers</div></div>

  <form method="GET">
    <div class="row">
      <div class="col"><input name="q" placeholder="Agency name" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"></div>
      <div class="col"><input name="city" placeholder="City" value="<?php echo htmlspecialchars($_GET['city'] ?? ''); ?>"></div>
      <div style="width:140px;"><input name="min_rating" placeholder="Min rating" value="<?php echo htmlspecialchars($_GET['min_rating'] ?? ''); ?>"></div>
      <div><button class="primary">Search</button></div>
    </div>
  </form>

  <h4>Agencies</h4>
  <table class="table"><tr><th>Agency</th><th>City</th><th>Rating</th><th>Services</th><th></th></tr>
  <?php while($a = $agencies->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($a['agency_name']); ?></td>
      <td><?php echo htmlspecialchars($a['city']); ?></td>
      <td><?php echo htmlspecialchars($a['rating']); ?></td>
      <td><?php echo htmlspecialchars($a['services']); ?></td>
      <td><a class="link" href="caretaker.php?agency=<?php echo $a['id']; ?>">View caretakers</a></td>
    </tr>
  <?php endwhile; ?>
  </table>

  <h4 style="margin-top:20px;">Caretakers</h4>
  <table class="table"><tr><th>Name</th><th>Agency</th><th>Exp</th><th>Rate</th><th></th></tr>
  <?php while($c = $resct->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($c['name']); ?></td>
      <td><?php echo htmlspecialchars($c['agency_name']); ?></td>
      <td><?php echo htmlspecialchars($c['experience_years']); ?> yrs</td>
      <td><?php echo htmlspecialchars($c['hourly_rate']); ?></td>
      <td><a class="link" href="caretaker.php?id=<?php echo $c['id']; ?>">View</a></td>
    </tr>
  <?php endwhile; ?>
  </table>

</div></body></html>
