<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: ./signin.php');
}

// clear session
session_unset();
session_destroy();

header('Location: ./signin.php');
?>