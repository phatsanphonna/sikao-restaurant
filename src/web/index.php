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
  
  <?php echo $_GET['q']; ?>

  <h1 class="button">
    Hello world!
  </h1>
</body>

</html>