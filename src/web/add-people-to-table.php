<?php
include '../lib/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $table_id = $_POST['table_id'];
  $amount = $_POST['amount'];
  
  // ตรวจสอบว่าโต๊ะที่ระบุมีอยู่ในฐานข้อมูลหรือไม่
  $sql_check_table = "SELECT * FROM res_table WHERE table_id = $table_id";
  $result_check_table = $conn->query($sql_check_table);

  if ($result_check_table->num_rows > 0) {
    // หากโต๊ะมีอยู่ในฐานข้อมูล แต่ต้องการสร้างข้อมูลใหม่

    // คำสั่ง SQL สำหรับเพิ่มข้อมูลใหม่
    $sql_create_reservation = "INSERT INTO res_order (table_id, customer_amount) VALUES ($table_id, $amount)";

    if ($conn->query($sql_create_reservation) === TRUE) {
      // หากสร้างข้อมูลใหม่สำเร็จ
    } else {
      // หากเกิดข้อผิดพลาดในการสร้างข้อมูลใหม่
    }
  } else {
    // หากไม่พบโต๊ะที่ระบุในฐานข้อมูล
  }

   
  header("Location: /tables.php");
  exit;
}
?>
