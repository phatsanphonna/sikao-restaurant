<?php
include '../lib/conn.php';

if (!isset($_GET['table_id'])) {
  header('Location: /index.php');
}

if ($_SESSION['user']['user_role'] === 'CHEF') {
  header('Location: /index.php');
}

$table_id = $_GET['table_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php include '../lib/components/Header.php'; ?>
  <style>
    .screen {
      height: calc(100vh - 8rem);
    }
  </style>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>
  <main class="p-4 flex flex-col gap-6 container mx-auto">
    <?php
    $sql = "SELECT * FROM res_table WHERE table_id = $table_id";
    $result = $conn->query($sql);
    $table = $result->fetch_assoc();
    ?>

    <header>
      <h4 class="text-8xl font-bold"><?php echo $table['table_name'] ?></h4>
      <hr>
    </header>

    <form action="add-people-to-table.php" method="POST" class="flex flex-col gap-4 items-center justify-center h-full">
      <p class="text-4xl">จำนวนคนที่นั่ง</p>

      <input hidden name="table_id" value="<?php echo $table_id ?>">

      <div class="flex justify-center items-center gap-2 w-3/5 mx-auto">
        <button type="button" class="bg-surface text-black px-5 py-2 rounded-full text-2xl" onclick="document.getElementById('amount').stepDown()">-</button>
        <input type="number" name="amount" id="amount" class="text-center text-2xl w-full border-surface rounded-full" readonly value="1" min="1">
        <button type="button" class="bg-surface text-black px-5 py-2 rounded-full text-2xl" onclick="document.getElementById('amount').stepUp()">+</button>
      </div>

      <div class="w-3/5">
        <button type="submit" class="bg-primary text-white px-4 py-2 w-full rounded-full">ยืนยันการเปิดโต๊ะ</button>
      </div>
    </form>
  </main>

</body>

</html>