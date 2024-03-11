<?php
function isauth() {
  session_start();

  if (!isset($_SESSION['user'])) {
    header('Location: signin.php');
  }
  
  if ($_SESSION['user']['user_role'] !== 'CHEF') {
    header('Location: index.php');
  }
}
