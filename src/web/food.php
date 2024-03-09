<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}

$order_id = $_GET['order_id'];
$food_id = $_GET['food_id'];

$sql = "SELECT * FROM food WHERE food_id = $food_id";
$result = $conn->query($sql);
$food = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Food - Gin Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6">
    <h2 class="text-secondary text-left text-4xl font-bold">
      <?php echo $food['food_name'] ?>
    </h2>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($food['food_image']) ?>" alt="<?php echo $food['food_name'] ?>" class="rounded-lg h-96 object-cover">
    <div>
      <p class="text-left text-lg text-gray-500">
        <?php echo $food['food_description'] ?>
      </p>
      <p class="text-left text-primary font-bold text-2xl">
        ฿<?php echo number_format($food['food_price']) ?>
      </p>
    </div>

    <form action="add-to-cart.php" method="POST" class="flex flex-col gap-4 items-center">
      <input type="hidden" name="order_id" value="<?php echo $order_id ?>">
      <input type="hidden" name="food_id" value="<?php echo $food_id ?>">

      <div class="flex justify-center items-center gap-2 w-3/5 mx-auto">
        <button type="button" class="bg-surface text-black px-5 py-2 rounded-full text-2xl" onclick="document.getElementById('amount').stepDown()">-</button>
        <input type="number" name="amount" id="amount" class="text-center text-2xl w-full border-surface rounded-full" readonly value="1" min="1">
        <button type="button" class="bg-surface text-black px-5 py-2 rounded-full text-2xl" onclick="document.getElementById('amount').stepUp()">+</button>
      </div>

      <div class="w-3/5">
        <button type="submit" class="bg-primary text-white px-4 py-2 w-full rounded-full">เพิ่มลงในตะกร้า</button>
      </div>
    </form>
  </main>

  <?php include '../lib/components/Cart.php'; ?>
</body>

</html>