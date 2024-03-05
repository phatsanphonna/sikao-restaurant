<?php
include '../lib/conn.php';

session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

if (!isset($_GET['bill_id'])) {
  header('Location: /tables.php');
}

$bill_id = $_GET['bill_id'];
$sql = "SELECT * FROM bill b JOIN res_order ro ON (b.order_id = ro.order_id) JOIN res_table rt ON (rt.table_id = ro.table_id) WHERE b.bill_id = $bill_id";
$bill = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เก็บเงิน - Gin Kao Restaurant</title>
  <?php include '../lib/components/Header.php'; ?>
  <style>
    .screen {
      height: calc(100vh - 8rem);
    }
  </style>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6 container mx-auto">
    <header>
      <h4 class="text-8xl font-bold"><?php echo $bill['table_name'] ?></h4>
      <hr>
    </header>

    <section class="flex flex-col gap-4">
      <h2 class="text-secondary text-left text-4xl font-bold">
        รายการอาหารที่สั่ง
      </h2>

      <ul class="flex flex-col gap-4">
        <?php
        $sql = "SELECT * FROM order_list_food olf JOIN food f on (f.food_id = olf.food_id) JOIN order_list ol ON (ol.order_list_id = olf.order_list_id) WHERE ol.order_id = " . $bill['order_id'];
        $result = $conn->query($sql);
        $foods = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($foods as $food) { ?>
          <li class="flex items-center gap-4 p-2 rounded-lg shadow-lg bg-stone-50">
            <div class="flex gap-4 items-center">
              <img src="data:image/jpeg;base64,<?php echo base64_encode($food['food_image']) ?>" alt="<?php echo $food['food_name'] ?>" class="rounded-lg h-24 w-24 object-cover">
              <div>
                <h4 class="text-2xl font-bold"><?php echo $food['food_name'] ?></h4>
                <p class="text-lg">จำนวน: <?php echo $food['amount'] ?></p>
                <p class="text-lg">ราคา: <?php echo $food['price'] ?> บาท</p>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>

      
  </main>
</body>

</html>