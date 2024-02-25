<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Document</title>
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
          <a href="menu.php?order_id=<?php echo $order_id ?>&category_id=<?php echo $category_id ?>" class="rounded-full p-1 shadow-lg text-center">
            <?php echo $row['category_name'] ?>
          </a>
        <?php } ?>
      </div>
    <?php } ?>

    <h2 class="text-secondary text-right text-4xl font-bold">
      <?php
      $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 1;
      $sql = "SELECT category_name FROM category WHERE category_id = $category_id";
      $result = $conn->query($sql);
      $category = mysqli_fetch_assoc($result);

      echo $category['category_name'];
      ?>
    </h2>
  </main>
  <?php include '../lib/components/Cart.php'; ?>
</body>

</html>