<?php
include '../lib/conn.php';

$order_id = $_GET['order_id'];

if (isset($order_id)) {
  $sql = "SELECT * FROM res_order ro JOIN res_table rt on (rt.table_id = ro.table_id) WHERE ro.order_id = '$order_id' LIMIT 1;";
  $result = $conn->query($sql);
  $order = mysqli_fetch_assoc($result);
}
?>

<nav class="h-24 flex items-center justify-between px-4">
  <?php include '../lib/components/LogoSmall.php'; ?>

  <?php if (isset($order_id)) { ?>
    <a class="text-2xl font-medium text-teritary pr-2" href="order.php?order_id=<?php echo $order_id ?>">
      <?php echo $order['table_name'] ?>
    </a>
  <?php } else if (isset($_SESSION['user'])) { ?>
    <div class="flex gap-4 pr-2">
      <a class="text-xl font-medium text-teritary" href="index.php">
        <?php echo $_SESSION['user']['username']; ?>
      </a>
  
      <a href="./signout.php" class="text-xl text-secondary underline" href="index.php">
        Sign Out
      </a>
    </div>
  <?php } ?>
</nav>