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

  $find_exist_sql = "SELECT * FROM cart_food WHERE cart_id = $cart_id AND food_id = $food_id";
  $result = $conn->query($find_exist_sql);

  if ($result->num_rows > 0) {
    $cart_food = mysqli_fetch_assoc($result);
    $amount += $cart_food['amount'];
    $update_sql = "UPDATE cart_food SET amount = $amount WHERE cart_food_id = " . $cart_food['cart_food_id'];
    mysqli_query($conn, $update_sql);

  } else {
    $insert_sql = "INSERT INTO cart_food (cart_id, food_id, amount) VALUES ($cart_id, $food_id, $amount)";
    mysqli_query($conn, $insert_sql);
  }

  header("Location: /menu.php?order_id=$order_id");
  exit;
}
?>