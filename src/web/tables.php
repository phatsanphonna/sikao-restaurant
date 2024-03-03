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
    <main class="p-4 flex gap-6 divide-x">
      <aside class="h-full">
        <?php
        $sql = "SELECT * FROM res_order ro JOIN res_table rt ON (ro.table_id = rt.table_id) WHERE ro.checkout_at IS NULL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
        ?>
            <a href="tables.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-lg w-72 h-40 shadow-lg flex flex-col justify-between text-white bg-primary p-4">
              <h4 class="text-8xl font-bold text-left">
                <?php echo $row['table_name'] ?>
              </h4>
              <div class="flex flex-row justify-between">
                <p><?php echo $row['customer_amount'] ?> ท่าน</p>
                <p><?php echo date('H.i', strtotime($row['created_at'])) ?></p>
              </div>
            </a>
        <?php }
        } ?>
      </aside>
      
      <div class="grid grid-cols-3 w-full gap-16 place-items-center">
        <?php
        $sql = "SELECT * FROM res_table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $sql = "SELECT * FROM res_order WHERE table_id = " . $row['table_id'] . " AND checkout_at IS NULL";
            $active_result = $conn->query($sql);
            $is_active = $active_result->num_rows > 0;
        ?>
            <a href="tables.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-2xl w-40 h-20 text-center flex justify-center items-center text-2xl <?php $is_active ? print('text-white bg-primary') : print('text-black bg-stone-200') ?>">
              <?php echo $row['table_name'] ?>
            </a>
        <?php }
        } ?>
      </div>
    </main>
  <?php } else { ?>
    <main class="screen p-4 flex flex-col gap-6 justify-center items-center">
      <?php
      $sql = "SELECT * FROM res_table WHERE table_id = " . $_GET['table_id'];
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
      ?>
          <a href="tables.php?table_id=<?php echo $row['table_id'] ?>" class="rounded-full p-1 shadow-lg text-center text-white bg-primary">
            <?php echo $row['table_name'] ?>
          </a>
      <?php }
      } ?>
    </main>
  <?php } ?>
</body>

</html>