<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

if (!isset($_GET['table_id'])) {
  header('Location: /tables.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>โต๊ะ - Si Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>
</body>

</html>