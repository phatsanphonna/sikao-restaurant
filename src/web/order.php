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
  <title>Order - Si Kao Restaurant</title>
</head>

<body>
</body>

</html>