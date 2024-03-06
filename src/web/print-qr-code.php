<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

if ($_SESSION['user']['user_role'] === 'CHEF') {
  header('Location: /index.php');
}

$order_id = $_GET['order_id'];

include "../lib/phpqrcode/qrlib.php"; // ไฟล์ของ PHP QR Code library

// URL ของ Google
$url = "http://" . $_SERVER['HTTP_HOST'] . "/menu.php?order_id=" . $order_id;

// สร้าง QR code และเก็บไฟล์ภาพลงในตัวแปร
ob_start();
QRCode::png($url, null, QR_ECLEVEL_L, 10, 1);
$imageString = base64_encode(ob_get_contents());
ob_end_clean();

$sql = "SELECT * FROM res_order ro JOIN res_table rt ON (rt.table_id = ro.table_id) WHERE order_id = $order_id LIMIT 1";
$order = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print QR Code - Gin Kao Restaurant</title>
  <?php include '../lib/components/Header.php'; ?>
  <script>
    window.onload = () => {
      print()
    }
  </script>
</head>

<body class="flex flex-col gap-4 items-center">
  <?php include '../lib/components/LogoSmall.php' ?>

  <header>
    <h1 class="text-secondary font-medium text-4xl text-center">
      <?php echo $order['table_name'] ?>
    </h1>
    <p>จำนวน <?php echo $order['customer_amount'] ?> ท่าน</p>
  </header>

  <img src="data:image/png;base64,<?php echo $imageString ?>" alt="<?php echo $order['table_name'] ?> QR Code">
  <hr class="w-full">
  <p class="text-center text-sm">QR Code สำหรับสั่งอาหารของโต๊ะ ท่านสามารถใช้ Smartphone ในการสแกนผ่าน Application อ่าน QR Code</p>
</body>

</html>