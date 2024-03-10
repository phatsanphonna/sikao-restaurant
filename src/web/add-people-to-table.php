<?php
include '../lib/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $table_id = $_POST['table_id'];
  $amount = $_POST['amount'];
  
  // ตรวจสอบว่าโต๊ะที่ระบุมีอยู่ในฐานข้อมูลหรือไม่
  $sql_check_table = "SELECT * FROM res_table WHERE table_id = $table_id";
  $result_check_table = $conn->query($sql_check_table);

  if ($result_check_table->num_rows != 0) {
    // คำสั่ง SQL สำหรับเพิ่มข้อมูลใหม่
    $sql_create_reservation = "INSERT INTO res_order (table_id, customer_amount) VALUES ($table_id, $amount)";

    # If create success
    if ($conn->query($sql_create_reservation) === TRUE) {
      $sql = "SELECT * FROM res_order WHERE table_id = $table_id AND checkout_at IS NULL";
      $result = $conn->query($sql)->fetch_assoc();

      $sql = "INSERT INTO cart (order_id) VALUES (". $result['order_id']. ");";
      $conn->query($sql);
    }
  }

  header("Location: ./tables.php");
  exit;
}
?>
