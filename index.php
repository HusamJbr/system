<?php
  include "./config.php";
  session_start();
  if(isset($_SESSION['username'])) header("location:./choose.php");

  if(isset($_POST['username']) && isset($_POST['password'])){
    	//database security **********************************
    	$username=mysqli_real_escape_string($con,$_POST['username']);
    	$password=mysqli_real_escape_string($con,$_POST['password']);
    	//end database security **********************************
    	$sql = "SELECT * FROM users where username='".$username."'";
    	$result = mysqli_query($con,$sql);
    	$num_row = mysqli_num_rows($result);
    	$row=mysqli_fetch_array($result);
    	if($num_row == 1){
    		  if($password == $row["password"]){
    			     //every thing is okay and send user to choose page
    			   $_SESSION['username'] = $row['username'];
             if($row['major'] == 0) header("Location: ./choose.php");
             else header("Location: ./receive.php");
            die;
          }else{
    			     header("Location: ./index.php?Error=Error username or password is incorrect");
    			     die;
          }
      }else{
            header("Location: ./index.php?Error=Error username or password is incorrect");
            die;
    	}
    }

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Login</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="./css/material-design-iconic-font.min.css">
      <link rel="stylesheet" type="text/css" href="./css/animate.css">
      <link rel="stylesheet" type="text/css" href="./css/hamburgers.min.css">
      <link rel="stylesheet" type="text/css" href="./css/animsition.min.css">
      <link rel="stylesheet" type="text/css" href="./css/select2.min.css">
      <link rel="stylesheet" type="text/css" href="./css/daterangepicker.css">
      <link rel="stylesheet" type="text/css" href="./css/util.css">
      <link rel="stylesheet" type="text/css" href="./css/main.css">
   </head>
   <body>
      <div class="limiter">
         <div class="container-login100">
            <div class="wrap-login100">
               <form class="login100-form validate-form" action="./index.php" method="post">
                  <span class="login100-form-title p-b-26">
                  WELCOME
                  </span>
                    <?php
                      if(isset($_GET['Error'])){
                        echo "<h3 class='error'>*".$_GET['Error']."</h3>";
                      }
                    ?>
                  <div class="wrap-input100 validate-input">
                     <input class="input100" type="text" name="username">
                     <span class="focus-input100" data-placeholder="Username"></span>
                  </div>
                  <div class="wrap-input100 validate-input">
                     <span class="btn-show-pass">
                     </span>
                     <input class="input100" type="password" name="password">
                     <span class="focus-input100" data-placeholder="Password"></span>
                  </div>
                  <div class="container-login100-form-btn">
                     <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn">
                        Login
                        </button>
                     </div>
                  </div>

               </form>
            </div>
         </div>
      </div>
   </body>
</html>
