<!-- PHP command to link server.php file with registration form  -->
<?php include('server.php'); ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login</title>
     <link rel="stylesheet" href="assets\stylesheets\style.css">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
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
 <body class = "login">
 <div class="container">
     <h2 class = "heading">Login</h2>
     <hr>
     <!-- <h4><a href="welcome.php">Home Page</a></h4> -->
                      <!--------log in form------>
 
     <div class="Form" id="logIn">
     <form method="POST">
     <div class="error"> <?php echo $error2 ?> </div>
 
     <!-- To show errors is user put wrong data -->
        <!-- <div class="error">  </div> -->
 
        <!-- To check the user loged In status -->
        <p>
         <?php
            if (!isset($_COOKIE["fld_ai_id"]) OR !isset($_SESSION["fld_ai_id"]) ) {
             echo "<p class = 'fil_top_msg'>Please first log in to proceed.</p>";
            }
         ?>
       </p>
       <label for="email" class = "l_email">Email</label>
       <input type="email" name="email" placeholder="Email" ><br><div class = "emerror"><?php echo $emerror ?> </div> <br>
       <label for="password" class = "l_password">Password</label>
       <input type="password" name="password" placeholder="Password" ><br><div class = "perror"><?php echo $perror ?> </div> <br>
       <label for="checkbox" class = "stayloggedin">Remember Me!</label>
       <input type="checkbox" name="stayLoggedIn" id="chechbox" value="1">
       <a href="forgot.php" class = "forgot">Forgot Password?</a>
       <input type="submit" name="logIn" value="Log In">
 
       <!-- User registration form link -->
       <p class = "redirecttext">Not a register user? <a href="register.php"> Create Account</a></p>
     </form>
     </div>
     
 </div>
 
 <!-- <script>
    $(document).ready(function()) {
        $("#forgot").click(function(){
            if($(""))
        })
    }
 </script> -->
 
  
 </body>
 </html>