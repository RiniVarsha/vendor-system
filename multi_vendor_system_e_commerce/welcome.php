
<?php
    // echo"<pre>";print_r($_);echo "</pre>";exit();
    session_start();
    if (array_key_exists('fld_ai_id', $_COOKIE)) {
        $_SESSION['fld_ai_id'] = $_COOKIE['fld_ai_id'];
        echo "<h1>Welcome ".$_SESSION['fld_name']."</h1>";
    }
    if (array_key_exists('fld_ai_id', $_COOKIE) || array_key_exists('fld_ai_id', $_SESSION)) {
        echo "<h2>You are a registered user <a href = login.php?logout=1>Log out</a></h2>";
    }
    else {
        header("Location: index.php");
    }
    // echo"<pre>";print_r($_SESSION);echo "</pre>";
    // echo"<pre>";print_r($_COOKIE);echo "</pre>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets\stylesheets\style.css">
    <title>Home</title>
</head>
<body>
<header>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Shop</a></li>
      <li><a href="#">Accessories</a></li>
      <li><h1>Ecommerce</h1></li>
      <li><a href="#">Collections</a></li>
      <li><a href="#">Brands</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </header>
    <a href="index.php">Back</a>
    <!-- <a href="login.php?logout = 1">Log out</a> -->
</body>
</html>