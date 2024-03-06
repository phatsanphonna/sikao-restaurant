<?php
include '../lib/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id = $_POST['order_id'];
  $food_id = $_POST['food_id'];
  $amount = $_POST['amount'];
  
  $sql = "SELECT * FROM cart WHERE order_id = $order_id";
  $result = $conn->query($sql);
  $cart = mysqli_fetch_assoc($result);
  $cart_id = $cart['cart_id'];

  $create_order_list_sql = "INSERT INTO order_list (order_id, order_success) VALUES ($order_id, 0)";
  $conn->query($create_order_list_sql);

  $get_last_order_list_sql = "SELECT * FROM order_list WHERE order_id = $order_id ORDER BY created_at DESC LIMIT 1";
  $last_order_list_result = $conn->query($get_last_order_list_sql);
  $last_order_list = mysqli_fetch_assoc($last_order_list_result);
  $order_list_id = $last_order_list['order_list_id'];

  $cart_item_sql = "SELECT * FROM cart_food WHERE cart_id = $cart_id";
  $cart_item_result = $conn->query($cart_item_sql);

  while ($cart_item = mysqli_fetch_assoc($cart_item_result)) {
    $food_id = $cart_item['food_id'];
    $amount = $cart_item['amount'];

    $create_order_item_sql = "INSERT INTO order_list_food (order_list_id, food_id, amount) VALUES ($order_list_id, $food_id, $amount)";
    $conn->query($create_order_item_sql);
  }

  $delete_cart_sql = "DELETE FROM cart_food WHERE cart_id = $cart_id";
  $conn->query($delete_cart_sql);

  header("Location: /order-sent.php?order_id=$order_id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Order Sent - Gin Kao Restaurant</title>
  <style>
    .fill-screen {
      height: calc(100vh - 6rem);
    }
  </style>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>
  <main class="fill-screen p-4 flex flex-col gap-2 justify-center items-center">
  <img src="../lib/assets/order-sent.gif" alt="" class="w-36">
    <h1 class="text-sikao-yellow font-bold text-3xl">ส่งอาหารไปที่ครัวเรียบร้อย</h1>
    <p class="text-center text-xl">รายการของท่านถูกส่งไปยังครัวเรียบร้อยแล้ว โปรดรออาหารสักครู่</p>

    <a href="menu.php?order_id=<?php echo $order_id ?>" class="bg-primary text-white px-4 py-2 rounded-lg">สั่งอาหารเพิ่มเติม</a>
  </main>
</body>

</html>