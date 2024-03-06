<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $cart_food_id = $_POST['cart_food_id'];
  $amount = $_POST['amount'];

  if (isset($_POST['increment'])) {
    $amount++;
  } else if (isset($_POST['decrement'])) {
    $amount--;
  }

  if ($amount == 0) {
    $sql = "DELETE FROM cart_food WHERE cart_food_id = $cart_food_id";
    $conn->query($sql);
  } else {
    $sql = "UPDATE cart_food SET amount = $amount WHERE cart_food_id = $cart_food_id";
    $conn->query($sql);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Cart - Gin Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6">
    <h2 class="text-secondary text-left text-4xl font-bold">
      ตะกร้า
    </h2>

    <ul class="grid grid-cols-1 gap-4">
      <?php
      $order_id = $_GET['order_id'];
      $sql = "SELECT * FROM cart c JOIN cart_food cf on (cf.cart_id = c.cart_id) JOIN food f on (f.food_id = cf.food_id) WHERE c.order_id = $order_id ORDER BY f.food_id ASC";
      $result = $conn->query($sql);

      $total = 0;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $total += $row['amount'] * $row['food_price'];
      ?>
          <li class="flex items-center gap-4 p-2 rounded-lg shadow-lg bg-stone-100">
            <div class="flex gap-4 items-center">
              <img src="data:image/jpeg;base64,<?php echo base64_encode($row['food_image']) ?>" alt="<?php echo $row['food_name'] ?>" class="rounded-lg h-24 w-24 object-cover">
            </div>
            <div class="flex flex-col gap-2">
              <h3 class="text-lg font-medium"><?php echo $row['food_name'] ?></h3>

              <div class="flex justify-between items-center">
                <form class="flex w-1/2 justify-center items-center gap-2" method="POST">
                  <input type="hidden" name="cart_food_id" value="<?php echo $row['cart_food_id'] ?>">
                  <button name='decrement' type="submit" class="bg-surface text-black px-3 py-1 rounded-full">-</button>
                  <input type="number" name="amount" class="text-center w-full border-surface rounded-full" readonly value="<?php echo $row['amount'] ?>" min="1">
                  <button name='increment' type="submit" class="bg-surface text-black px-3 py-1 rounded-full">+</button>
                </form>

                <p class="text-2xl font-bold text-primary pr-2">฿<?php echo number_format($row['amount'] * $row['food_price']) ?></p>
              </div>
            </div>
          </li>
        <?php }
      } else { ?>
        <p class="text-center text-xl">ไม่มีรายการอาหารในตะกร้า</p>
      <?php } ?>
    </ul>

    <?php if ($result->num_rows > 0) { ?>
      <div class="flex justify-between">
        <h3 class="text-xl font-bold">ราคารวม</h3>
        <p class="text-2xl font-bold text-primary">฿<?php echo number_format($total) ?></p>
      </div>

      <form action="order-sent.php" method="POST" class="flex justify-center">
        <input type="hidden" name="order_id" value="<?php echo $_GET['order_id'] ?>">
        <button type="submit" class="bg-primary text-white px-4 py-2 text-center rounded-lg">สั่งอาหาร</a>
      </form>
    <?php } ?>
  </main>
</body>

</html>