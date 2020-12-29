<?php
  include "./config.php";
  session_start();
  if(empty($_SESSION['username'])) header("location:./index.php");
  $name = $_SESSION['username'];
  $flag = false;
  if(isset($_POST['id'])) $flag = true;
  $name = $_POST['username'];
  $name=mysqli_real_escape_string($con,$name);
  $sql = "SELECT * FROM users where username='".$name."'";
  $result = mysqli_query($con,$sql);
  $row=mysqli_fetch_array($result);
  $num_row = mysqli_num_rows($result);
  if($num_row <= 0){
    header("location:./index.php");
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
function DoPost(){
var form = document.createElement('form');
form.style.visibility = 'hidden'; // no user interaction is necessary
form.method = 'POST'; // forms by default use GET query strings
form.action = 'prescription.php';
var input = document.createElement('input');
input.name = 'username';
input.value = '<?php echo $name; ?>';
form.appendChild(input); // add key/value pair to form
document.body.appendChild(form); // forms cannot be submitted outside of body
form.submit(); // send the payload and navigate
}
</script>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Prescription</title>
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
         <h1 class="navbar-brand navbar-logo"><?php echo $row['name']; ?></h1>
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


      <li><a href="./choose.php">Home</a></li>
      <li><a href="javascript:DoPost()">Prescription</a></li>
			<li><a href="./logout.php">Logout</a></li>
		</ul>
	</li>
</ul>
      </nav>
      <div class="limiter">
         <div>
           <?php
            if($flag){
              $sql = "SELECT * FROM prescription where ID='".$_POST['id']."'";
              $result = mysqli_query($con,$sql);
              $idinfo=mysqli_fetch_array($result);
              echo "      <div class='limiter'>
                               <h1 style='text-align: center; position:absolute; display:inline; top: 15%; left:43%'>".$idinfo['info']."</h1></div>";
            ?>

            <?php
          }else{
           ?>
           <form class="login100-form validate-form" action="./prescription.php" method="post">
           <?php
           $sql = "SELECT * FROM prescription where username='".$name."'";
           $result = mysqli_query($con,$sql);
           $num_row = mysqli_num_rows($result);
           if($num_row <= 0){
             echo "<div  style='margin: 50px 50px 100px 100px;width:50%'>
                  <div class='wrap-login100-form-btn'>
                     <div class='login100-form-bgbtn'></div>
                     <button class='login100-form-btn' style='padding: 75px 200px;''>
                        <h1 style='font-size: 75px;'></h1>
                            <h1>You have no prescriptions</h1>
                     </button>
                  </div>
               </div>";
           }else{
           while($row=mysqli_fetch_array($result)){
             echo "<div  style='margin: 50px 50px 100px 100px;width:50%'>
                  <div class='wrap-login100-form-btn'>
                     <div class='login100-form-bgbtn'></div>
                     <input type='hidden' name='id' value=".$row['ID'].">
                     <input type='hidden' name='username' value=".$name.">
                     <button class='login100-form-btn' name='date' style='padding: 75px 200px;''>
                        <h1 style='font-size: 75px;'></h1>
   					                <h1>".$row['date']."</h1>
                     </button>
                  </div>
               </div>";
           }}
?>
</form>
<?php
}
?>
      </div>
   </body>
</html>
