<?php  use PHPMailer\PHPMailer\PHPMailer;
                            use PHPMailer\PHPMailer\SMTP;
                            use PHPMailer\PHPMailer\Exception; $emerror = "";?>

 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Forgot Password</title>
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
     <h2 class = "heading">Forgot Password</h2>
     <hr>
     <!-- <h4><a href="welcome.php">Home Page</a></h4> -->
                      <!--------log in form------>
 
     <div class="Form" id="forgot">
     <form method="POST">
     <!-- <div class="error"> <?php echo $error2 ?> </div> -->
     <p>Send e-mail Verification</p> 
     <!-- To show errors is user put wrong data -->
        <!-- <div class="error">  </div> -->
 
        <!-- To check the user loged In status -->
        <!-- <p>
         <?php
            if (!isset($_COOKIE["fld_ai_id"]) OR !isset($_SESSION["fld_ai_id"]) ) {
             echo "<p class = 'fil_top_msg'>Please first log in to proceed.</p>";
            }
         ?>
       </p> -->
       <div>
       <label for="email" class = "l_email">Email</label>
       <input type="email" name="email" placeholder="Email" required><br><div class = "emerror"><?php echo $emerror ?> </div> <br>      
        </div>
       <input type="submit" name="forgot" value="Send">
 
       <!-- User registration form link -->
       
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
    <?php
        $emerror = "";
        include('includes\db\db_connection.php');  
        include('electricity_bill_function.php');
        if (array_key_exists("forgot", $_POST)){
            $email = electricity_bill_get_post_escape(__LINE__,__FILE__,'email');
            if (empty($email)) {
                $emerror = "*Email is required.";
            }
            
            elseif (!electricity_bill_filter_var(__LINE__,__FILE__,$email)) {  
                $emerror = "*Email is not valid.";  
            }

            else{
                $query = "SELECT fld_ai_id FROM tbl_users WHERE fld_email = '$email'";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) == "") {
                    $emerror .="User Not Found";
                }
            }
            if ($emerror != "") {
                echo $emerror;
            } else{
                $output = '';

                            $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
                            $expDate = date("Y-m-d H:i:s", $expFormat);
                            $key = md5(time());
                            $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
                            $key = $key . $addKey;
                            // Insert Temp Table
                            mysqli_query($con, "UPDATE tbl_users
                            SET ContactName = 'Alfred Schmidt', City = 'Frankfurt'
                            WHERE CustomerID = 1;");

mysqli_query($con, "INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`) VALUES ('" . $email . "', '" . $key . "', '" . $expDate . "');");

                            $output.='<p>Please click on the following link to reset your password.</p>';
                            //replace the site url
                            $output.='<p><a href="http://localhost/multi_vendor_system_e_commerce/forgot.php?key=' . $key . '&email=' . $email . '&action=reset" target="_blank">http://localhost/tutorial/reset-password.php?key=' . $key . '&email=' . $email . '&action=reset</a></p>';
                            $body = $output;
                        


                            //autoload the PHPMailer

                           
                            //Import PHPMailer classes into the global namespace
                            //These must be at the top of your script, not inside a function
                            
                            
                            //Load Composer's autoloader
                            // require 'vendor\phpmailer\phpmailer\src';
                            require 'vendor\autoload.php';
                            
                            //Create an instance; passing `true` enables exceptions
                            $mail = new PHPMailer(true);
                            
                            try {
                                //Server settings
                                $mail->SMTPDebug = 3;                      //Enable verbose debug output
                                $mail->isSMTP();                                            //Send using SMTP
                                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                $mail->Username   = 'nameu6025@gmail.com';                     //SMTP username
                                $mail->Password   = 'qtucxtnbhwohbtzv';                               //SMTP password
                                $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                                $mail->Port       = 587;     
                                $mail->CharSet = 'UTF-8';                               //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            
                                //Recipients
                                $mail->setFrom('nameu6025@gmail.com');
                                $mail->addAddress($email);     //Add a recipient
                                // $mail->addAddress('ellen@example.com');               //Name is optional
                                // $mail->addReplyTo('noreply@example.com', 'Information');
                                // $mail->addCC('cc@example.com');
                                // $mail->addBCC('bcc@example.com');
                            
                                //Attachments
                                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                            
                                //Content
                                $mail->isHTML(true);                                  //Set email format to HTML
                                $mail->Subject = 'Here is the subject';
                                $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                            
                                $mail->send();
                                echo 'Message has been sent';
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                           

                            
            }
        }
    ?>
 
  
 </body>
 </html>