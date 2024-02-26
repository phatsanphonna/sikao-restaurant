<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Document</title>
  <style>
    .screen {
      height: calc(100vh - 6rem);
    }
  </style>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="screen p-4 flex flex-col gap-6 justify-center items-center">
    <?php include '../lib/components/LogoLarge.php'; ?>
  </main>
</body>

</html>