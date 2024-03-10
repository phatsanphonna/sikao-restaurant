<?php
include '../lib/conn.php';

session_start();

if (!isset($_GET['bill_id'])) {
  header('Location: index.php');
}

$bill_id = $_GET['bill_id'];

$sql = "SELECT * FROM bill WHERE bill_id = $bill_id";
$bill = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ใบเสร็จรับเงิน - Gin Kao Restaurant</title>
  <?php include '../lib/components/Header.php'; ?>
  <script>
    window.onload = () => {
      print();
    }
  </script>
</head>

<body>
  <div class="w-full flex flex-col justify-center items-center gap-2">
    <?php include '../lib/components/LogoSmall.php'; ?>
    <p class="text-2xl font-bold">ใบเสร็จรับเงิน</p>
    <hr class="w-full">
  </div>

  <div class="grid grid-cols-1 my-4">
    <p>เลขที่ใบเสร็จ: <?php echo $bill['bill_id'] ?></p>
    <p>Cashier: <?php echo $_SESSION['user']['username'] ?></p>
    <p>เวลาที่ชำระ: <?php echo (new DateTime($bill['created_at']))->format('d/m/Y H:i'); ?></p>
    <p>ยอดรวม: <?php echo $bill['total'] ?> บาท</p>
    <p>
      <?php
      if ($bill['payment_method'] === 'CASH') {
        echo 'ชำระโดย: เงินสด';
      } else if ($bill['payment_method'] === 'QR_PAYMENT') {
        echo 'ชำระโดย: PromptPay';
      } else if ($bill['payment_method'] === 'CREDIT_CARD') {
        echo 'ชำระโดย: บัตรเครดิต';
      }
      ?></p>
  </div>

  <table class="table-fixed w-full">
    <thead class="border-b">
      <tr>
        <th class="text-left">รายการ</th>
        <th class="text-center w-14">จำนวน</th>
        <th class="text-right w-14">ราคา</th>
      </tr>
    </thead>
    <tbody class="border-b">
      <?php
      $sql = "SELECT * FROM order_list_food olf JOIN food f on (f.food_id = olf.food_id) JOIN order_list ol ON (ol.order_list_id = olf.order_list_id) WHERE ol.order_id = " . $bill['order_id'];
      $result = $conn->query($sql);
      $foods = $result->fetch_all(MYSQLI_ASSOC);

      foreach ($foods as $food) {
      ?>
        <tr>
          <td><?php echo $food['food_name'] ?></td>
          <td class="text-center"><?php echo $food['amount'] ?></td>
          <td class="text-right"><?php echo number_format($food['food_price'] * $food['amount'], 2) ?></td>
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">ยอดรวม</td>
        <td class="text-right"><?php echo number_format($bill['total'], 2) ?></td>
      </tr>
    </tfoot>
  </table>

  <p class="mt-8 text-center">ขอบคุณที่ใช้บริการ และขอให้โชคดียิ่งยวด</p>
</body>

</html>