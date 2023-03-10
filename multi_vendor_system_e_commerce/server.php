<?php 
session_start();
//------ PHP code for User registration form---
$nerror1 = "";
$nerror2 = "";
$error = "";
$error2 = "";
$emerror = "";
$perror = "";
$password = "";
$repasserror = "";
$pherror = "";
$addresserror = "";
ini_set('display_errors', 1);
if (isset($_GET['logout'])) {
    unset($_SESSION['fld_ai_id']);
    setcookie('fld_ai_id',"", time() - 60*60);
    $_COOKIE['fld_ai_id'] = "";
}
if (array_key_exists("signUp", $_POST)) {
 
     // Database Link
    include('includes\db\db_connection.php');  
    include('electricity_bill_function.php');
    //Taking HTML Form Data from User
    $uname = electricity_bill_get_post_escape(__LINE__,__FILE__,'username');
    $name = electricity_bill_get_post_escape(__LINE__,__FILE__,'name');
    $email = electricity_bill_get_post_escape(__LINE__,__FILE__,'email');
    // $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $phone_number = electricity_bill_get_post_escape(__LINE__,__FILE__,'phone_number');
    $address = electricity_bill_get_post_escape(__LINE__,__FILE__,'address');
    $password = electricity_bill_get_post_escape(__LINE__,__FILE__,'password');
    $repeatPassword = electricity_bill_get_post_escape(__LINE__,__FILE__,'repeatPassword'); 
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})*$/";
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    // var_dump($phone_number);
    // var_dump(electricity_bill_validate_mobile_number(__LINE__,__FILE__,$phone_number));
    // echo"<pre>";print_r($_POST);echo "</pre>";exit();
    #echo "$email<br>$uname<br>$name";
     
    // PHP form validation PHP code
    if (empty($name)) {
        $nerror1 = "*Name is required. ";
    }
    if (!preg_match ("/^[a-zA-z ]*$/", $name) ) {  
        $nerror1 .= "*Only alphabets and whitespace are allowed.";  
    }
    if (empty($uname)) {
        $nerror2 = "*User Name is required.";
    }
    if (!preg_match ("/^[a-zA-z0-9 ]*$/", $uname) ) {  
        $nerror2 .= "*Only alphabets, numbers and whitespace are allowed.";  
    }
    if (empty($email)) {
        $emerror = "*Email is required.";
     }
       
    elseif (!electricity_bill_filter_var(__LINE__,__FILE__,$email)) {  
        $emerror = "*Email is not valid.";  
    }    
    if (empty($password)) {
        $perror = "*Password is required.";
     } 
     
    elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {
         $perror = '*Password should be at least 8 characters in length and should include at least one upper case letter, one number and one special character.';
     }
    if (empty($phone_number)) {
        $pherror = "*Phone Number is required.";
    }
   
    elseif (!electricity_bill_validate_mobile_number(__LINE__,__FILE__,$phone_number)) {
        $pherror = "*Phone number is not valid";
    }
    if (empty($address)) {
        $addresserror = "*Address is required.";
    }

    if (empty($repeatPassword)) {
        $repasserror = "*Password is required.";
    }
    elseif ($password !== $repeatPassword) {
        $repasserror .= "*Password does not match.";
     }
    if ( $nerror1 || $nerror2 || $emerror || $perror || $repasserror || $pherror) {
        // $error = '<b>There are errors in the form.</b><br>';
    }
//      if (!$phone_number) {
//         $error .= "Phone Number is required <br>";
//      }
      else {
       
        //Check if email is already exist in the Database
        
        $query = "SELECT fld_ai_id FROM tbl_users WHERE fld_email = '$email'";
        $result = mysqli_query($con, $query);
        echo mysqli_num_rows($result);
        if (mysqli_num_rows($result) > 0) {
            $emerror .="<p> Your email is taken already!</p>";
        } else {
 
            //Password encryption or Password Hashing
            $hashedPassword = md5($password); 
            $query = "INSERT INTO tbl_users (fld_user_name, fld_name, fld_email, fld_phone_number, fld_address, fld_password) VALUES ('$uname', '$name', '$email', '$phone_number', '$address','$hashedPassword')";
             
            if (!mysqli_query($con, $query)){
                $error ="<p> Could not sign you up - please try again.</p>";
                } else {
 
                    //session variables to keep user logged in
                $_SESSION['fld_ai_id'] = mysqli_insert_id($con);  
                $_SESSION['fld_name'] = $name;

                #echo "You are signed up.";
                
                if ($_POST['stayLoggedIn'] == '1') {
                                    setcookie('fld_ai_id', mysqli_insert_id($con), time() + 60*60*365);
                                    //echo "<p>The cookie id is :". $_COOKIE['id']."</P>";
                }
                header("Location: welcome.php");
                }
            } 
        }  
    }
//                 //Setcookie function to keep user logged in for long time
//                 if ($_POST['stayLoggedIn'] == '1') {
//                 setcookie('id', mysqli_insert_id($con), time() + 60*60*365);
//                 //echo "<p>The cookie id is :". $_COOKIE['id']."</P>";
//                 }
                  
//                 //Redirecting user to home page after successfully logged in 
//                 header("Location: welcome.php");  
             
//                 }
             
//             }
 
//         }  
//     }
 
//       //-------User Login PHP Code ------------
 
if (array_key_exists("logIn", $_POST)) {
     
    // Database Link
    include('includes\db\db_connection.php'); 
 
      //Taking form Data From User
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con,  $_POST['password']); 
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})*$/";
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
      
       
      //Check if input Field are empty
    if (empty($email)) {
        $emerror = "*Email is required.";
    }
       
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {  
        $emerror = "*Email is not valid.";  
    }    
    if (empty($password)) {
        $perror = "*Password is required.";
    } 
     
    // elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    //      $perror = '<p>*Password should be at least 8 characters in length and should include at least one upper case letter, one number and one special character.</p>';
    // }

    
    if ( $nerror1 || $nerror2 || $emerror || $perror || $repasserror) {
        // $error = '<b>There are errors in the form.</b><br>';
    }
        
      else {        
          //matching email and 
          
         
          

          $email = mysqli_real_escape_string($con, $_POST['email']);
          
          $password = mysqli_real_escape_string($con,  $_POST['password']);
          $hashedPassword = md5($password);
        //   echo '<pre>';print_r($_POST);echo '</pre>';
          $query = "SELECT fld_ai_id, fld_name, fld_email,fld_password, fld_is_active FROM tbl_users WHERE fld_email = '$email' and fld_password = '$hashedPassword'";
        //   echo $query."<br>";
        // exit();
        //   echo var_dump(strval(md5($result)));
        //     echo var_dump($password);
           
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            // echo $row;
            $count = mysqli_num_rows($result);

            // if ($count == 1) {
            //     echo "Login succesful"."<br>";

            // }
            // else {
            //     echo "Login failed"."<br>";
            // }
            
            
            if (isset($row)) {
                
                $password = mysqli_real_escape_string($con,  $_POST['password']);
                $hashedPassword = md5($password);
                // echo $row['fld_is_active'];exit();
                
                
                if (($hashedPassword === $row['fld_password']) && $row['fld_is_active'] == 1) {
                    // echo "hello";
                    //session variables to keep user logged in
                    $_SESSION['fld_ai_id'] = $row['fld_ai_id'];  
                    $_SESSION['fld_name'] = $row['fld_name'];
                      //Logged in for long time untill user didn't log out
                    
                    // print_r($_POST['stayLoggedIn']);exit();
                    
                    if ($_POST['stayLoggedIn'] == '1') {
                    setcookie('fld_ai_id', $row['fld_ai_id'], time() + 60*60*24); //Logged in permanently
                    }
 
                    header("Location: welcome.php");
 
                } else {
                    $perror = "User does not exist!";
                    }
   
            }  else {
                $perror = "Combination of email/password does not match!";
                 }
        }
}

// if (array_key_exists("forgot", $_POST)) {
//     include('includes\db\db_connection.php');  
//     include('electricity_bill_function.php');
//     $email = electricity_bill_get_post_escape(__LINE__,__FILE__,'email');
// }
?>