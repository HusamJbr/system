<?php
  include "./config.php";
  session_start();
  if(empty($_SESSION['username'])) header("location:./index.php");
  $sql = "SELECT * FROM users where username='".$_SESSION['username']."'";
  $result = mysqli_query($con,$sql);
  $row=mysqli_fetch_array($result);
  $drid = $row['username'];
  $name = $row['name'];
  if($row['major'] == 0) header("location:./choose.php");
  $st = $row['major'];
  $flag = false;
  if (isset($_POST['receive'])) {
    $flag = true;
  }
  $sql = "SELECT * FROM queue where status='".$st."'";
  $result = mysqli_query($con,$sql);
  $before = mysqli_num_rows($result);
  if(isset($_POST['delete']) && isset($_POST['id'])){
    $id=mysqli_real_escape_string($con,$_POST['id']);
    $sql = "SELECT * FROM queue where ID='".$id."'";
    $result = mysqli_query($con,$sql);
    $data = mysqli_fetch_array($result);
    $user = $data['username'];
    if($st != 3){
      date_default_timezone_set("Asia/Amman");
      $date = date("Y-m-d h:i:sa");
      $sql = "INSERT INTO prescription(ID, username, dr_name,ph_name,info,date) VALUES (NULL, '".$user."', '".$drid."', '', '', '".$date."')";
      $result = mysqli_query($con,$sql);
    }
    $sql = "DELETE FROM queue where ID='".$id."'";
    $result = mysqli_query($con,$sql);
    if($st == 3){
      $sql = "UPDATE prescription SET ph_name='".$drid."' where username='".$user."' ORDER BY ID DESC LIMIT 1";
      $result = mysqli_query($con,$sql);
    }
    header("location:./receive.php");
    die;
  }
  if(isset($_POST['prescription']) && isset($_POST['send'])){
    $id=mysqli_real_escape_string($con,$_POST['id']);
    $presc=mysqli_real_escape_string($con,$_POST['prescription']);
    $sql = "SELECT * FROM queue where ID='".$id."'";
    $result = mysqli_query($con,$sql);
    $data = mysqli_fetch_array($result);
    $user = $data['username'];
    date_default_timezone_set("Asia/Amman");
    $date = date("Y-m-d h:i:sa");
    $sql = "INSERT INTO prescription(ID, username, dr_name,ph_name,info,date) VALUES (NULL, '".$user."', '".$drid."', '', '".$presc."', '".$date."')";
    $result = mysqli_query($con,$sql);
    $sql = "DELETE FROM queue where ID='".$id."'";
    $result = mysqli_query($con,$sql);
    $sql = "INSERT INTO queue (ID, username, status,date) VALUES (NULL,'".$user."','3','".$date."')";
    $result = mysqli_query($con,$sql);
    header("location:./receive.php");
    die;
  }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
       <script type="text/javascript" src="jquery-1.4.4.js"></script>

<style type="text/css">
.clear { height: 100% }
.clear:after { content: ''; display: block; clear: both }

#menu, #menu ul { list-style: none; margin: 0; padding: 0 }

#menu li { background: #5161ce; border-right: 1px solid #fff; position: relative; float: left; white-space: nowrap }
#menu li a { display: inline-block; padding: 5px 20px; text-decoration: none; color: #13a;  }

#menu ul { background: #fff; display: none; position: absolute }
#menu ul li { background: #aabde6; border-top: 1px solid #bdd2ff; border-right: 0px solid transparent; float: none }

#menu ul ul { top: -1px; left: 100% }

#menu li.hover  { background-color: #5f61ff }

#menu ul { -webkit-box-shadow: 3px 3px 4px #999; -moz-box-shadow: 3px 3px 4px #999; box-shadow: 3px 3px 4px #999 }
</style>

<script type="text/javascript">
$(document).ready(function() {
	$('#menu').menu();
});

var ie = $.browser.msie && $.browser.version < 8.0;

$.fn.menu = function() {
	$(this).find('li').each(function() {
		if ($(this).find('> ul').size() > 0) {
			$(this).addClass('has_child');
		}
	});

	var closeTimer = null;
	var menuItem = null;

	function cancelTimer() {
		if (closeTimer) {
			window.clearTimeout(closeTimer);
			closeTimer = null;
		}
	}

	function close() {
		$(menuItem).find('> ul ul').hide();
		ie ? $(menuItem).find('> ul').fadeOut() : $(menuItem).find('> ul').slideUp(250);
		menuItem = null;
	}

	$(this).find('li').hover(function() {
		cancelTimer();

		var parent = false;
		$(this).parents('li').each(function() {
			if (this == menuItem) parent = true;
		});
		if (menuItem != this && !parent) close();

		$(this).addClass('hover');
		ie ? $(this).find('> ul').fadeIn() : $(this).find('> ul').slideDown(250);
	}, function() {
		$(this).removeClass('hover');
		menuItem = this;
		cancelTimer();
		closeTimer = window.setTimeout(close, 500);
	});

	if (ie) {
		$(this).find('ul a').css('display', 'inline-block');
		$(this).find('ul ul').css('top', '0');
	}
}
</script>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Receive</title>
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
      <link rel="stylesheet" type="text/css" href="./css/Nav.css">
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-mainbg">
         <img src="Images/hospital-logo-clinic-health-care-physician-business.png" height="70px" width="80px">
         <h1 class="navbar-brand navbar-logo"><?php echo $name; ?></h1>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                  <a class="nav-link" href=""></a>
               </li>

            </ul>
         </div>
          <ul id="menu" class="clear">
	<li><a href="#"><img src="Images/hamburger-menu-icon-png-white-10.jpg" height="60px" width="60px"></a>
		<ul>


      <li><a href="./receive.php">Home</a></li>
			<li><a href="./logout.php">Logout</a></li>
		</ul>
	</li>
</ul>
      </nav>
      <div class="limiter">
        <?php
        if($flag){
          $sql = "SELECT * FROM queue where status='".$st."'";
          $result = mysqli_query($con,$sql);
          $row=mysqli_fetch_array($result);
          ?>
          <div class="container-login100" style="background: white" >
            <?php
              if($st != 3){
            ?>
            <div  style="margin: -50px 50px 25px 100px;">
              <form class="login100-form validate-form" action="./receive.php" method="post">
                <div class="form-group">
                  <label for="prescription">Make a prescription</label>
                  <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                  <textarea class="form-control" name="prescription" id="prescription" rows="5"></textarea>
                </div>
                <button type="submit" name="send" class="btn btn-primary">send to pharmacist</button>
            </div>
          <?php } ?>
          </form>
             <div  style="margin: -50px 50px 25px 100px;">
               <form class="login100-form validate-form" action="./prescription.php" method="post">
                 <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                 <button type="submit" name="show" class="btn btn-primary">show all prescriptions</button>
               </form><br>
                <form class="login100-form validate-form" action="./receive.php" method="post">
                  <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                   <button type="submit" name="delete" class="btn btn-primary">Delete from queue</button>
                 </form>
          </div>
          <?php
        }else{
          if($before == 0){
          echo '<h1 style="text-align: center; position:absolute; display:inline; top: 15%; left:43%">The queue is empty</h1>';
        }  else{
        ?>
                 <h1 style="text-align: center; position:absolute; display:inline; top: 15%; left:43%">Queue</h1>

         <div class="container-login100" style="background: white" >
            <div  style="margin: -50px 50px 25px 100px;">
              <form class="login100-form validate-form" action="./receive.php" method="post">
               <div class="wrap-login100-form-btn">
                  <div class="login100-form-bgbtn"></div>
                  <button class="login100-form-btn" name="receive" style="padding: 180px 75px;">
                     <h1 style="font-size: 75px;">receive a patient</h1>
                  </button>
               </div>
            </div>
          </form>
         </div>
       <?php } } ?>
      </div>
   </body>
</html>
