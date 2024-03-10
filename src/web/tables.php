<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: ./signin.php');
}

if ($_SESSION['user']['user_role'] === 'CHEF') {
  header('Location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>โต๊ะ - Gin Kao Restaurant</title>
  <style>
    .screen {
      height: calc(100vh - 8rem);
    }
    
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <?php if (!isset($_GET['table_id'])) { ?>
    <main class="flex mb-4 mb">
      <aside class="screen overflow-y-auto w-96 flex flex-col gap-4 bg-gray-100 p-5 drop-shadow-lg rounded-lg">
        <!-- <h4 class="text-4xl font-bold">โต๊ะที่เปิดใช้งาน</h4> -->
        <?php
        $sql = "SELECT * FROM res_order ro JOIN res_table rt ON (ro.table_id = rt.table_id) WHERE ro.checkout_at IS NULL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
        ?>
            <a href="./tables.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-lg h-40 shadow-lg flex flex-col justify-between text-white bg-primary p-4">
              <h4 class="text-8xl font-bold text-left">
                <?php echo $row['table_name'] ?>
              </h4>
              <div class="flex flex-row justify-between">
                <p><i class="fa-regular fa-user"></i> <?php echo $row['customer_amount'] ?></p>
                <p><i class="fa-regular fa-clock"></i> <?php echo date('H.i', strtotime($row['created_at'])) ?></p>
              </div>
            </a>
        <?php }
        } ?>
      </aside>

      <div class="w-full screen overflow-y-auto">
        <div class="grid grid-cols-3 w-full gap-2 place-items-center h-full">
          <?php
          $sql = "SELECT * FROM res_table";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $sql = "SELECT * FROM res_order WHERE table_id = " . $row['table_id'] . " AND checkout_at IS NULL";
              $active_result = $conn->query($sql);
              $is_active = $active_result->num_rows > 0;
          ?>
              <a href="./tables.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-2xl w-40 h-20 text-center flex justify-center items-center text-2xl <?php $is_active ? print('text-white bg-primary') : print('text-black bg-stone-200') ?>">
                <?php echo $row['table_name'] ?>
              </a>
          <?php }
          } ?>
        </div>
      </div>
    </main>
  <?php } else { ?>
    <main class="p-4 flex flex-col gap-6 container mx-auto">
      <?php
      $sql = "SELECT * FROM res_table WHERE table_id = " . $_GET['table_id'];
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();

      $customer_in_sql = "SELECT * FROM res_order WHERE table_id = " . $_GET['table_id'] . " AND checkout_at IS NULL";
      $customer_in_result = $conn->query($customer_in_sql);
      $is_customer_in = $customer_in_result->num_rows > 0;
      $customer_in_row = null;

      if ($is_customer_in) {
        $customer_in_row = $customer_in_result->fetch_assoc();
      }
      ?>

      <header>
        <h4 class="text-8xl font-bold"><?php echo $row['table_name'] ?></h4>
        <hr>
      </header>

      <div class="grid grid-cols-3 gap-4 text-4xl drop-shadow">
        <?php if (!isset($customer_in_row)) { ?>
          <a class="h-40 bg-gray-200 rounded-lg flex flex justify-center items-center text-white">
            รายการอาหารที่สั่งไป
          </a>
          <a href="./open-table.php?table_id=<?php echo $row['table_id'] ?>" class="h-40 bg-gray-300 rounded-lg flex flex justify-center items-center text-white bg-primary">
            เปิดบิล
          </a>
          <a class="h-40 bg-gray-200 rounded-lg flex flex justify-center items-center text-white">
            Print QR Code
          </a>
        <?php } else { ?>
          <a target="_blank" href="print-qr-code.php?print-qr=<?php echo $row['table_id'] ?>&order_id=<?php echo $customer_in_row['order_id'] ?>" class="h-40 bg-primary rounded-lg flex flex justify-center items-center text-white">
            Print QR Code
          </a>
          <a href="./order-list.php?table_id=<?php print($row['table_id']) ?>" class="h-40 bg-primary rounded-lg flex flex justify-center items-center text-white">
            รายการอาหารที่สั่งไป
          </a>
          <a href="./checkout.php?table_id=<?php print($row['table_id']) ?>" class="h-40 bg-primary rounded-lg flex flex justify-center items-center text-white">
            จ่ายเงิน
          </a>
        <?php } ?>

      </div>

    </main>
  <?php } ?>
  
</body>

</html>