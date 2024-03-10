<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
}

if ($_SESSION['user']['user_role'] === 'CHEF') {
  header('Location: ./kitchen.php');
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

  <main class="container mx-auto p-4 flex flex-col gap-6 justify-center items-center">
    <header class="w-full">
      <h1 class="text-6xl font-bold">Gin Kao Restaurant</h1>
    </header>

    <h4 class="w-full text-secondary text-4xl font-bold">
      โปรดเลือกทำรายการที่ต้องการ
    </h4>
  
    <div class="grid grid-cols-3 gap-4 w-full text-white font-medium text-4xl">
      <a href="tables.php" class="bg-primary w-full h-40 rounded flex justify-center items-center">จัดการโต๊ะ</a>
      <a href="report.php" class="bg-primary w-full h-40 rounded flex justify-center items-center">สรุปยอดขาย</a>
    </div>
  </main>
</body>

</html>