<?php
session_start();
include '../lib/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM user WHERE username = '$username'";
  $result = mysqli_query($conn, $sql);
  $user = mysqli_fetch_assoc($result);

  if (!$user) {
    echo "<script>alert('Username not found')</script>";
  } else if (!password_verify($password, $user['password'])) {
    echo "<script>alert('Password is incorrect')</script>";
  } else {
    $_SESSION['user'] = $user;
    if ($user['user_role'] == 'CHEF') {
      header('Location: /kitchen.php');
    } else {
      header('Location: /index.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../lib/components/Header.php'; ?>
  <title>Sign In - Gin Kao Restaurant</title>
</head>

<body>
  <div class="container mx-auto grid place-items-center h-screen">
    <form class="shadow-lg w-80 rounded-lg p-2" method="POST">
      <h1 class="use-serif font-bold text-center text-2xl text-primary">Gin Kao Restaurant</h1>

      <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
      <div>
        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary sm:max-w-md">
          <input type="text" name="username" id="username" autocomplete="username" class="px-2 block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="chef">
        </div>
      </div>

      <label for="password" class="block text-sm font-medium leading-6 text-gray-900 mt-2">Password</label>
      <div>
        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary sm:max-w-md">
          <input type="password" name="password" id="password" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
        </div>
      </div>

      <button type="submit" class="mt-2 w-full rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
        Sign In
      </button>
    </form>

  </div>
</body>

</html>