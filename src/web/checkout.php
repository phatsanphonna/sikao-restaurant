<?php
include '../lib/conn.php';
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
}

if ($_SESSION['user']['user_role'] !== 'CHEF') {
  header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $type = $_POST['payment_method'];
  $order_id = $_POST['order_id'];

  if (!isset($type) || !isset($order_id)) {
    header('Location: tables.php');
  }

  $sql = "UPDATE res_order SET checkout_at = NOW() WHERE order_id = $order_id";
  $conn->query($sql);

  $sql = "DELETE FROM cart WHERE order_id = $order_id";
  $conn->query($sql);

  // set bill
  $sql = "SELECT * FROM order_list_food olf JOIN food f on (f.food_id = olf.food_id) JOIN order_list ol ON (ol.order_list_id = olf.order_list_id) WHERE ol.order_id = $order_id";
  $result = $conn->query($sql);
  $foods = $result->fetch_all(MYSQLI_ASSOC);

  $total = 0;

  foreach ($foods as $food) {
    $total += $food['amount'] * $food['food_price'];
  }

  $sql = "INSERT INTO bill (order_id, total, payment_method) VALUES ($order_id, $total, '$type')";
  $conn->query($sql);

  header("Location: ./bill.php?bill_id=" . $conn->insert_id);
} else if (!isset($_GET['table_id']) && !isset($_GET['order_id'])) {
  header('Location: ./tables.php');
} else {
  $table_id = $_GET['table_id'];

  $sql = "SELECT * FROM res_order WHERE table_id = $table_id AND checkout_at IS NULL";
  $order = $conn->query($sql)->fetch_assoc();

  $sql = "SELECT * FROM res_table WHERE table_id = $table_id";
  $table = $conn->query($sql)->fetch_assoc();

  $total = 0;

  if ($order) {
    $sql = "SELECT * FROM order_list_food olf JOIN food f on (f.food_id = olf.food_id) JOIN order_list ol ON (ol.order_list_id = olf.order_list_id) WHERE ol.order_id = " . $order['order_id'];
    $result = $conn->query($sql);
    $foods = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($foods as $food) {
      $total += $food['amount'] * $food['food_price'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เก็บเงิน - Gin Kao Restaurant</title>
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

    <div class="flex justify-between items-center">
      <h3 class="text-2xl font-bold">ยอดที่ต้องจ่ายทั้งหมด</h3>
      <p class="text-4xl font-bold text-primary">฿<?php echo number_format($total, 2) ?></p>
    </div>

    <ul class="grid grid-cols-3 gap-4">
      <li class="transition-all h-80 rounded-lg bg-white bg-stone-100 shadow-xl border group hover:bg-primary">
        <form method="POST" action="./checkout.php" class="h-full">
          <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?>">
          <input type="hidden" name="payment_method" value="CASH">
          <a onclick="this.parentNode.submit();" class="cursor-pointer flex flex-col gap-4 items-center justify-center h-full">
            <img src="../lib/assets/cashicon.png" alt="">
            <p class="text-4xl text-gray-600 group-hover:text-white">เงินสด</p>
          </a>
        </form>
      </li>
      <li class="transition-all h-80 rounded-lg bg-white bg-stone-100 shadow-xl border group hover:bg-primary">
        <form method="POST" action="./checkout.php" class="h-full">
          <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?>">
          <input type="hidden" name="payment_method" value="QR_PAYMENT">
          <a onclick="this.parentNode.submit();" class="cursor-pointer flex flex-col gap-4 items-center justify-center h-full">
            <img src="../lib/assets/promptpayicon.png" alt="">
            <p class="text-4xl text-gray-600 group-hover:text-white">พร้อมเพย์</p>
          </a>
        </form>
      </li>
      <li class="transition-all h-80 rounded-lg bg-white bg-stone-100 shadow-xl border group hover:bg-primary">
        <form method="POST" action="./checkout.php" class="h-full">
          <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?>">
          <input type="hidden" name="payment_method" value="CREDIT_CARD">
          <a onclick="this.parentNode.submit();" class="cursor-pointer flex flex-col gap-4 items-center justify-center h-full">
            <img src="../lib/assets/creditcardicon.png" alt="">
            <p class="text-4xl text-gray-600 group-hover:text-white">บัตรเครดิต</p>
          </a>
        </form>
      </li>
    </ul>
  </main>
</body>

</html>