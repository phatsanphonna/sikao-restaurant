<?php
include '../lib/conn.php';

session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}

if (!isset($_GET['table_id'])) {
  header('Location: /tables.php');
}

$table_id = $_GET['table_id'];
$sql = "SELECT * FROM res_table WHERE table_id = $table_id";
$result = $conn->query($sql);
$table = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>โต๊ะ - Si Kao Restaurant</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6 container mx-auto">

    <header>
      <h4 class="text-8xl font-bold"><?php echo $table['table_name'] ?></h4>
      <hr>
    </header>

  </main>
</body>

</html>