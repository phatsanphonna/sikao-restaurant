<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}

$sql = "SELECT * FROM res_order WHERE order_id = " . $_GET['order_id'] . " AND checkout_at IS NULL";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  header('Location: /index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Menu - Si Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6">
    <?php
    $order_id = $_GET['order_id'];
    $sql = "SELECT * FROM category ORDER BY category_id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { ?>
      <div class="grid grid-cols-3 gap-2">
        <?php while ($row = $result->fetch_assoc()) {
          $category_id = $row['category_id']
        ?>
          <a href="menu.php?order_id=<?php echo $order_id ?>&category_id=<?php echo $category_id ?>" class="rounded-full p-1 shadow-lg text-center <?php $category_id == $_GET['category_id'] ? print('text-white bg-primary') : print('') ?>">
            <?php echo $row['category_name'] ?>
          </a>
        <?php } ?>
      </div>
    <?php } ?>

    <h2 class="text-secondary text-center text-4xl font-bold">
      <?php
      $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 1;
      $sql = "SELECT category_name FROM category WHERE category_id = $category_id";
      $result = $conn->query($sql);
      $category = mysqli_fetch_assoc($result);

      echo $category['category_name'];
      ?>
    </h2>

    <div class="grid grid-cols-2 gap-2">
      <?php
      $sql = "SELECT * FROM food WHERE category_id = $category_id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
      ?>
          <a class="transition-all group rounded-lg p-4 shadow-lg flex flex-col gap-2 hover:bg-primary" href="food.php?order_id=<?php echo $order_id ?>&food_id=<?php echo $row['food_id'] ?>" class="rounded-full bg-primary text-white px-4 py-2">
            <img src="<?php echo $row['food_image'] ?>" alt="<?php echo $row['food_name'] ?>" class="rounded-lg h-48 object-cover">
            <h3 class="text-xl"><?php echo $row['food_name'] ?></h3>
            <p class="group-hover:text-white text-2xl text-right font-medium text-primary">฿<?php echo number_format($row['food_price']) ?></p>
          </a>
      <?php }
      } ?>
    </div>
  </main>

  <?php include '../lib/components/Cart.php'; ?>
</body>

</html>