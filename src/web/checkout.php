<?php
include '../lib/conn.php';

if (!isset($_GET['table_id'])) {
  header('Location: /tables.php');
}

$table_id = $_GET['table_id'];

$sql = "SELECT * FROM res_table WHERE table_id = $table_id";
$result = $conn->query($sql);
$table = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เก็บเงิน - Si Kao Restaurant</title>
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
    <header>
      <h4 class="text-8xl font-bold"><?php echo $table['table_name'] ?></h4>
      <hr>
    </header>

    <h2 class="text-secondary text-left text-4xl font-bold">
      จ่ายเงิน
    </h2>

    <ul class="grid grid-cols-3 gap-4">
      <li class="h-60 rounded-lg bg-white bg-stone-100 shadow-xl border flex flex-col gap-4 items-center justify-center">
        <div>
          <?php include '../lib/components/CashIcon.php'; ?>
        </div>
        <p class="text-4xl text-gray-600">เงินสด</p>
      </li>
      <li class="h-60 rounded-lg bg-white bg-stone-100 shadow-xl border flex flex-col gap-4 items-center justify-center">
        <div>
          <?php include '../lib/components/PromptPayIcon.php'; ?>
        </div>
        <p class="text-4xl text-gray-600">พร้อมเพย์</p>
      </li>
      <li class="h-60 rounded-lg bg-white bg-stone-100 shadow-xl border flex flex-col gap-4 items-center justify-center">
        <div>
          <?php include '../lib/components/CreditCardIcon.php'; ?>
        </div>
        <p class="text-4xl text-gray-600">บัตรเครดิต</p>
      </li>
    </ul>
  </main>
</body>

</html>