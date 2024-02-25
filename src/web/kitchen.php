<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

if ($_SESSION['user']['user_role'] !== 'CHEF') {
  header('Location: /signout.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Kitchen - Si Kao Restaurant</title>
</head>
<body>
  <?php include '../lib/components/Navbar.php'; ?>
</body>
</html>