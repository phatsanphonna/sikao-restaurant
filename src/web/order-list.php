<?php
include '../lib/conn.php';

session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

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
  <?php include '../lib/components/Header.php'; ?>
  <title>โต๊ะ - Gin Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6 container mx-auto">

    <header>
      <h4 class="text-8xl font-bold"><?php echo $table['table_name'] ?></h4>
      <hr>
    </header>

    <h2 class="text-secondary text-left text-4xl font-bold">
      รายการอาหารที่สั่ง
    </h2>

    <ul>
      <?php
      $sql = "SELECT order_id FROM res_order WHERE table_id = $table_id AND checkout_at IS NULL";
      $result = $conn->query($sql);
      $order = $result->fetch_assoc();
      $order_id = $order['order_id'];

      $sql = "SELECT * FROM order_list_food olf JOIN food f ON (olf.food_id = f.food_id) JOIN order_list ol ON (ol.order_list_id = olf.order_list_id) WHERE order_id = $order_id ORDER BY f.food_id ASC ";
      $result = $conn->query($sql);

      while ($order_list = $result->fetch_assoc()) { ?>
        <li class="flex items-center gap-4 p-2 rounded-lg shadow-lg bg-stone-100">
          <div class="flex gap-4 items-center">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($order_list['food_image']) ?>" alt="<?php echo $order_list['food_name'] ?>" class="rounded-lg h-24 w-24 object-cover">
            <div>
              <h4 class="text-2xl font-bold"><?php echo $order_list['food_name'] ?></h4>
              <p class="text-lg">จำนวน: <?php echo $order_list['amount'] ?></p>
              <p class="text-lg">สถานะ: <?php 
                if ($order_list['order_food_status'] == 'SENT_TO_KITCHEN') {
                  echo 'ส่งให้ครัว';
                } else if ($order_list['order_food_status'] == 'COOKING') {
                  echo 'กำลังทำ';
                } else if ($order_list['order_food_status'] == 'READY') {
                  echo 'เสร็จแล้ว';
                }
              ?></p>
            </div>
          </div>
        </li>
      <?php } ?>
    </ul>

  </main>
</body>

</html>