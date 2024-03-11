<?php
include "../lib/conn.php";

session_start();

if (!isset($_SESSION['user'])) {
  header('Location: signin.php');
}

if ($_SESSION['user']['user_role'] !== 'CHEF') {
  header('Location: index.php');
}

if (isset($_POST['order_list_food_id'])) {
  $sql = "UPDATE order_list_food SET order_food_status = '" . $_POST['order_food_status'] . "' WHERE order_list_food_id = " . $_POST['order_list_food_id'] . ";";
  $conn->query($sql);

  // Check if all food items in the order list have been marked as 'READY'
  $check_sql = "SELECT COUNT(*) AS total_food FROM order_list_food WHERE order_list_id = " . $_GET['order_list_id'] . " AND order_food_status <> 'READY';";
  $check_row = $conn->query($check_sql)->fetch_assoc();

  if ($check_row['total_food'] == 0) {
    // Update order_success to 1 if all food items are 'READY'
    $update_sql = "UPDATE order_list SET order_success = 1 WHERE order_list_id = " . $_GET['order_list_id'];
    $conn->query($update_sql);
  }

  header('Location: ./kitchen.php?order_list_id=' . $_GET['order_list_id']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Kitchen - Gin Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6 container mx-auto">
    <?php if (!isset($_GET['order_list_id'])) { ?>
      <h2 class="text-secondary text-left text-4xl font-bold">
        รายการอาหารที่ต้องทำ
      </h2>

      <div class="grid grid-cols-1 gap-4">
        <?php
        $sql = "SELECT *, ol.created_at as `ol_created_at` FROM order_list ol JOIN res_order o ON (ol.order_id = o.order_id) JOIN res_table t on (o.table_id = t.table_id) WHERE order_success = 0 AND checkout_at IS NULL ORDER BY ol.created_at ASC;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $count_sql = "SELECT COUNT(*) AS `total_food` FROM order_list_food WHERE order_list_id = " . $row['order_list_id'] . ";";
            $count_result = $conn->query($count_sql);
            $count_row = $count_result->fetch_assoc();

            $status_sql = "SELECT * FROM order_list_food WHERE order_list_id = " . $row['order_list_id'] . ";";
            $status_result = $conn->query($status_sql);

            $status = '';
            $status_list = array();

            while ($status_row = $status_result->fetch_assoc()) {
              array_push($status_list, $status_row['order_food_status']);
            }


            if (in_array('COOKING', $status_list)) {
              $status = 'text-yellow-300';
            } else if (
              count(array_unique($status_list)) === 1 && $status_list[0] === 'READY'
            ) {
              $status = 'text-green-500';
            }
        ?>
            <div class="bg-gray-100 w-full rounded-lg p-4 flex flex-col">
              <div class="flex justify-between">
                <span class="text-lg">Order ID : <?php echo $row['order_list_id']; ?></span>
                <p class="text-4xl">สั่งเมื่อ : <?php echo date('H.i', strtotime($row['ol_created_at'])); ?> น.</p>
              </div>
              <h2 class="text-8xl font-bold <?php echo $status; ?>"><?php echo $row['table_name'] ?></h2>
              <div class="flex justify-between items-end">
                <p class="text-lg text-gray-500">จำนวนอาหารที่สั่งทั้งหมด <?php echo $count_row['total_food'] ?> รายการ</p>
                <a href="kitchen.php?order_list_id=<?php echo $row['order_list_id'] ?>" class="bg-primary text-white text-2xl rounded-lg p-2">รายละเอียด</a>
              </div>
            </div>
        <?php }
        } ?>
      </div>
    <?php } else { ?>
      <div class="flex justify-between items-end">
        <h2 class="text-secondary text-left text-6xl font-bold">
          ออเดอร์ที่ : <?php echo $_GET['order_list_id']; ?>
        </h2>
        <p class="text-4xl">
          <span class="font-medium">สั่งเมื่อ :</span>
          <?php
          $sql = "SELECT created_at FROM order_list WHERE order_list_id = " . $_GET['order_list_id'] . ";";
          $result = $conn->query($sql)->fetch_assoc();
          echo date('H.i', strtotime($result['created_at']));
          ?> น.
        </p>
      </div>

      <hr>

      <table class="table-auto border-collapse border-spacing-2">
        <thead>
          <tr class="text-secondary font-normal text-2xl">
            <th class="text-left">รายการอาหาร</th>
            <th>จำนวน</th>
            <th>สถานะ</th>
            <th>คำสั่ง</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM order_list_food olf JOIN food f ON (olf.food_id = f.food_id) WHERE order_list_id = " . $_GET['order_list_id'] . ";";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
          ?>
              <tr class="border-b">
                <td><?php echo $row['food_name']; ?></td>
                <td class="text-center"><?php echo $row['amount']; ?></td>
                <td class="text-center p-2">
                  <?php if ($row['order_food_status'] === 'SENT_TO_KITCHEN') { ?>
                    <span class="text-red-500">รอทำ</span>
                  <?php } else if ($row['order_food_status'] === 'COOKING') { ?>
                    <span class="text-yellow-300">กำลังทำ</span>
                  <?php } else if ($row['order_food_status'] === 'READY') { ?>
                    <span class="text-green-500">เสร็จแล้ว</span>
                  <?php } ?>
                </td>
                <td>
                  <form method="POST" class="w-full">
                    <input type="text" hidden value="<?php echo $row['order_list_food_id'] ?>" name="order_list_food_id">
                    <?php if ($row['order_food_status'] === 'SENT_TO_KITCHEN') { ?>
                      <input type="text" hidden value="COOKING" name="order_food_status">
                      <button type="submit" class="bg-yellow-500 w-full text-white rounded p-1">กำลังทำ</button>
                    <?php } else if ($row['order_food_status'] === 'COOKING') { ?>
                      <input type="text" hidden value="READY" name="order_food_status">
                      <button type="submit" class="bg-green-500 w-full text-white rounded p-1">เสร็จแล้ว</button>
                    <?php } ?>
                  </form>
                </td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
      <div>
      </div>
    <?php } ?>
  </main>

  <script>
    // refresh page every 10 seconds
    setTimeout(() => {
      location.reload();
    }, 10000);
  </script>
</body>

</html>