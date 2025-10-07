<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Hostinger CRUD Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Hostinger CRUD Test App</h2>

  <form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo isset($_GET['edit']) ? $_GET['edit'] : ''; ?>">
    <input type="text" name="name" placeholder="Enter name" required>
    <input type="email" name="email" placeholder="Enter email" required>
    <button type="submit" name="save">Save</button>
  </form>

  <?php
  // INSERT or UPDATE
  if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $id = $_POST['id'];

    if ($id) {
      $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
    } else {
      $conn->query("INSERT INTO users (name, email) VALUES ('$name', '$email')");
    }
    header("Location: index.php");
  }

  // DELETE
  if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: index.php");
  }

  // EDIT
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    $editData = $result->fetch_assoc();
    echo "<script>
      document.querySelector('[name=name]').value = '{$editData['name']}';
      document.querySelector('[name=email]').value = '{$editData['email']}';
      document.querySelector('[name=id]').value = '{$editData['id']}';
    </script>";
  }

  // READ (Display Table)
  $result = $conn->query("SELECT * FROM users");
  echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>
              <a href='?edit={$row['id']}'>Edit</a> |
              <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this user?')\">Delete</a>
            </td>
          </tr>";
  }
  echo "</table>";
  ?>

</body>
</html>
