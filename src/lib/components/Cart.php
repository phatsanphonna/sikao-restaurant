<?php
$order_id = $_GET['order_id'];
?>

<a href="cart.php?order_id=<?php echo $order_id ?>" class="w-20 h-20 rounded-full fixed bottom-5 right-5 shadow-lg z-50 grid place-items-center">
  <img src="../lib/assets/cart-icon.png" alt="Cart">
</a>