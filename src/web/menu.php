<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Document</title>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <?php
  include '../lib/conn.php';

  $sql = "SELECT * FROM food";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<h1>" . $row["food_name"] . "</h1>";
    }
  } else {
    echo "0 results";
  }
  ?>
</body>

</html>