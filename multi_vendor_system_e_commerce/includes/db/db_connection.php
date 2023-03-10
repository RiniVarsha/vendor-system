<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "users_db";

// Create connection
$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}
#echo "Connected successfully"."<br>";
#$sql = "INSERT INTO tbl_users(fld_user_name, fld_name, fld_email, fld_phone_number) VALUES('sui765','suha shaha','suha765@yahoo.com','9876015634'),('laxha987', 'laxha ray', 'laxha@gmail.com', '7485512354');";
?> 
</body>
</html>
