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

  $insert_sql = "INSERT INTO cart_food (cart_id, food_id, amount) VALUES ($cart_id, $food_id, $amount)";
  mysqli_query($conn, $insert_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Order Sent - Si Kao Restaurant</title>
  <style>
    .fill-screen {
      height: calc(100vh - 6rem);
    }
  </style>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>
  <main class="fill-screen p-4 flex flex-col gap-2 justify-center items-center">
    <h1 class="text-sikao-yellow font-bold text-3xl">ส่งอาหารไปที่ครัวเรียบร้อย</h1>
    <p class="text-center text-xl">รายการของท่านถูกส่งไปยังครัวเรียบร้อยแล้ว โปรดรออาหารสักครู่</p>

    <a href="menu.php?order_id=<?php echo $order_id ?>" class="bg-primary text-white px-4 py-2 rounded-lg">สั่งอาหารเพิ่มเติม</a>
  </main>
</body>

</html>