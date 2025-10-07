<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) header("Location: index.php");
$me = $_SESSION['user'];
$to = intval($_GET['to'] ?? 0);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Chat</title><link rel="stylesheet" href="style.css">
<script>
function loadMessages(){ fetch('api/messages.php?with=<?php echo $to;?>').then(r=>r.text()).then(html=>{ document.getElementById('msgs').innerHTML = html; }); }
function sendMsg(){
  const t = document.getElementById('msg').value;
  if(!t) return;
  fetch('api/messages.php', {
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({to:<?php echo $to;?>, message:t})
  }).then(()=>{ document.getElementById('msg').value=''; loadMessages(); });
}
setInterval(loadMessages,3000);
window.onload = loadMessages;
</script>
</head>
<body>
<div class="container">
  <div class="header"><div class="logo">NAMMCARE</div><div class="h-title">Chat</div></div>
  <div id="msgs" style="height:300px; overflow:auto; border:1px solid #eee; padding:8px; background:#fff;"></div>
  <div class="row" style="margin-top:10px;">
    <input id="msg" type="text" placeholder="Type message" />
    <button class="primary" onclick="sendMsg()">Send</button>
  </div>
  <p><a class="link" href="profile.php">Back</a></p>
</div>
</body></html>
