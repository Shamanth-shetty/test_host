<?php
session_start();
include __DIR__ . '/../db.php';
if (!isset($_SESSION['user'])) { http_response_code(401); exit; }
$me = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $with = intval($_GET['with'] ?? 0);
  $res = $conn->query("SELECT m.*, u.name as sender_name FROM messages m LEFT JOIN users u ON u.id=m.sender_id
    WHERE (sender_id={$me['id']} AND receiver_id=$with) OR (sender_id=$with AND receiver_id={$me['id']}) ORDER BY m.created_at ASC");
  while($row = $res->fetch_assoc()) {
    $side = ($row['sender_id']==$me['id']) ? 'You' : htmlspecialchars($row['sender_name']);
    echo "<div><b>$side</b>: ".htmlspecialchars($row['message'])." <small style='color:#999'>".$row['created_at']."</small></div>";
  }
  exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $to = intval($data['to']);
  $msg = $conn->real_escape_string($data['message']);
  $conn->query("INSERT INTO messages (sender_id,receiver_id,message) VALUES ({$me['id']}, $to, '$msg')");
  echo "ok";
  exit;
}
