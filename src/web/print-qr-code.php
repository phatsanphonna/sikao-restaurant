<?php
include '../lib/conn.php';

if (!isset($_GET['order_id'])) {
  header('Location: /index.php');
}

$order_id = $_GET['order_id'];




include "../lib/phpqrcode/qrlib.php"; // ไฟล์ของ PHP QR Code library

// URL ของ Google
$url = "localhost:8080/menu.php?order_id=". $order_id;

// กำหนดพาธของโฟลเดอร์ที่ต้องการเก็บไฟล์ภาพ
$imagePath = "../lib/assets/qrcode.png";

// สร้าง QR code และเก็บไฟล์ภาพในโฟลเดอร์เป้าหมาย
QRcode::png($url, $imagePath);
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <img src="../lib/assets/qrcode.png" alt="">
</body>

</html>