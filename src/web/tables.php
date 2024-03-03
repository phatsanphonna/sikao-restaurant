<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: /signin.php');
}
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

  <?php if (!isset($_GET['table_id'])) { ?>
    <main class="screen p-4 flex flex-col gap-6 justify-center items-center">
      <?php
      $sql = "SELECT * FROM res_table";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
      ?>
          <a href="menu.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-full p-1 shadow-lg text-center text-white bg-primary">
            <?php echo $row['table_name'] ?>
          </a>
      <?php }
      } ?>
    </main>
  <?php } else { ?>
    <main class="screen p-4 flex flex-col gap-6 justify-center items-center">
      <?php
      $sql = "SELECT * FROM res_table WHERE table_id = " . $_GET['table_id'];
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
      ?>
          <a href="menu.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-full p-1 shadow-lg text-center text-white bg-primary">
            <?php echo $row['table_name'] ?>
          </a>
      <?php }
      } ?>
    </main>
  <?php } ?>
</body>

</html>