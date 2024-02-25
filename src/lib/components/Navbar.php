<?php
include '../lib/conn.php';

$order_id = $_GET['order_id'];

if (isset($order_id)) {
  $sql = "SELECT * FROM res_order ro JOIN res_table rt on (rt.table_id = ro.table_id) WHERE ro.order_id = '$order_id' LIMIT 1;";
  $result = $conn->query($sql);
  $order = mysqli_fetch_assoc($result);
}
?>

<nav class="h-14 flex items-center justify-between px-4">
  <?php include '../lib/components/Logo.php'; ?>

  <h4 class="text-2xl font-bold text-teritary">
    <?php echo $order['table_name']; ?>
  </h4>
</nav>