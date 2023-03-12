<?php include('server.php'); ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Register</title>
     <!-- <link rel="stylesheet" href="login_register_bootstrap.css"> -->
     <link rel="stylesheet" href="assets\stylesheets\style.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
     <!-- CSS Code -->
     <!-- <style>
         .container{
             justify-content: center;
             text-align: center;
             align-items: center;
         }
         input{
             padding: 5px;
         }
         .error{
             background-color: pink;
             color: red;
             width: 300px;
             margin: 0 auto;
         }
     </style> -->
 </head>
 
 <body class = "register">
 
 <div class="container">
     <h2 class = "heading">Register</h2>
     <hr>
     <!-- <h4><a href="welcome.php">Home Page</a></h4> -->
     
      
     <div class="Form" id="signUp">
     <form method="POST" >
        <div class="error"> <?php echo $error ?> </div>
 
            <!--------- To check user regidtration status ------->
        <p>
         <?php
            if (!isset($_COOKIE["fld_ai_id"]) OR !isset($_SESSION["fld_ai_id"]) ) {
             echo "<p class = 'fil_top_msg'>Please first register to proceed.</p>";
            }
         ?>
        </p>
       <label for="username" class = "l_uname">User Name</label>
       <input type="text" name="username" placeholder="User Name" id = "username" required>  <div class="nerror2"> <?php echo $nerror2 ?> </div> <br>
       <label for="name" class = "l_name">Name</label>
       <input type="text" name="name" placeholder="Name" id = "name" required> <div class="nerror1"> <?php echo $nerror1?> </div> <br>
       <label for="email" class = "l_email">Email</label>
       <input type= "email" name="email" placeholder="Email" id ="email" required> <div class = "emerror"><?php echo $emerror ?> </div> <br>
       <label for="phone_number" class = "l_phone_number">Phone Number</label>
       <input type= "text" name="phone_number" placeholder="Phone Number" id = "phone_number" required> <div class = "pherror"><?php echo $pherror ?> </div> <br>
       <label for="address" class = "l_address">Address</label>
       <input type = "text" name="address" placeholder="Address" id = "address" required> <div class = "addresserror"><?php echo $addresserror ?> </div> <br>
       <!-- <input type = "text" name = "image" placeholder = "Image"><div class = "image"><?php echo $imgerror?></div><br> -->
       <label for="password" class = "l_password">Password</label>
       <input type="password" name="password" placeholder="Password" id = "password" required> <div class = "perror"><?php echo $perror ?> </div> <br>
       <label for="repeatpassword" class = "l_repeatpassword">Repeat Password</label>
       <input type="password" name="repeatPassword" placeholder="Repeat Password" id = "repeatpassword" required> <div class = "repasserror"><?php echo $repasserror?> </div> <br>
       <label for="checkbox" class = "stayloggedin">Remember Me!</label>
       <input type="checkbox" name="stayLoggedIn" id="chechbox" value="1"> 
       <!-- <a href="" class = "forgot">Forgot Password?</a> -->
       <input type="submit"  name="signUp" value="Sign Up">
       <p class = "redirecttext">Have an account already? <a  href="index.php">Log In</a></p>
      </form>
     </div>
 </body>
 </html>