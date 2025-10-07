<?php
// db.php - DB connection (configured with your Hostinger credentials)
$servername = "localhost";
$username = "u203447863_testapp";
$password = "3M^?H@!Pl@";
$dbname = "u203447863_test_app";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("DB Connection failed: " . $conn->connect_error);
}

// Ensure schema exists (run schema.sql contents automatically)
$schema = file_get_contents(__DIR__ . '/schema.sql');
foreach (explode(";", $schema) as $stmt) {
  $s = trim($stmt);
  if ($s) { $conn->query($s); }
}
?>
