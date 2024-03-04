<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Order - Si Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6">
    <h2 class="text-secondary text-left text-4xl font-bold">
      รายการอาหารที่สั่งไป
    </h2>

    <ul class="grid grid-cols-1 gap-4">
      <?php
      $order_id = $_GET['order_id'];
      $sql = "SELECT * FROM order_list WHERE order_id = $order_id";
      $result = $conn->query($sql);

      $total = 0;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $sql = "SELECT * FROM order_list_food olf JOIN food f on (f.food_id = olf.food_id) WHERE olf.order_list_id = " . $row['order_list_id'];
          $food_result = $conn->query($sql);
      ?>
          <li>
            <h3 class="text-lg font-bold">ออเดอร์เลขที่: <?php echo $row['order_list_id'] ?></h3>
            <ul class="flex flex-col gap-2">
              <?php if ($food_result->num_rows > 0) {
                while ($food_row = $food_result->fetch_assoc()) {
                  $total += $food_row['amount'] * $food_row['food_price'];
              ?>
                  <li class="flex items-center gap-4 p-2 rounded-lg shadow-lg bg-stone-100">
                    <div class="flex gap-4 items-center">
                      <img src="data:image/jpeg;base64,<?php echo base64_encode($food_row['food_image']) ?>" alt="<?php echo $food_row['food_name'] ?>" class="rounded-lg h-24 w-24 object-cover">
                    </div>
                    <div class="flex flex-col gap-2 w-full">
                      <h3 class="text-lg font-medium"><?php echo $food_row['food_name'] ?></h3>

                      <div class="flex justify-between items-center gap-2 w-full">
                        <input type="number" name="amount" class="text-center w-1/2 md:w-1/3 border-surface rounded-full" readonly value="<?php echo $food_row['amount'] ?>" min="1">
                        <p class="text-2xl font-bold text-primary pr-2">฿<?php echo number_format($food_row['amount'] * $food_row['food_price']) ?></p>
                      </div>
                    </div>
                  </li>
              <?php }
              } ?>
            </ul>
          </li>
      <?php }
      } ?>
    </ul>

    <div class="flex justify-between mb-24">
      <h3 class="text-xl font-bold">ราคาทั้งหมด</h3>
      <p class="text-2xl font-bold text-primary">฿<?php echo number_format($total) ?></p>
    </div>

  </main>

  <?php include '../lib/components/Cart.php'; ?>
</body>

</html>