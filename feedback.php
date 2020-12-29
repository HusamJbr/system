<?php
  include "./config.php";
  session_start();
  if(empty($_SESSION['username'])) header("location:./index.php");
  $sql = "SELECT * FROM users where username='".$_SESSION['username']."'";
  $result = mysqli_query($con,$sql);
  $row=mysqli_fetch_array($result);
  if($row['major'] != 0) header("location:./receive.php");

  $sql = "SELECT * FROM prescription where username='".$_SESSION['username']."' ORDER BY ID DESC LIMIT 1";
  $result = mysqli_query($con,$sql);
  $num_row = mysqli_num_rows($result);
  $inf = mysqli_fetch_array($result);
  if($num_row <= 0) {
    header("location:./choose.php");
    die;
  }
  $sql = "SELECT * FROM feedback where ID='".$inf['ID']."'";
  $result = mysqli_query($con,$sql);
  $num_row = mysqli_num_rows($result);
  $nrow=mysqli_fetch_array($result);
  if($num_row > 0){
    if($nrow['value'] != 0){
      header("location:./choose.php");
      die;
    }
  }
  $sql = "INSERT INTO feedback(ID, value, info) VALUES ('".$inf['ID']."', '', '')";
  $result = mysqli_query($con,$sql);
  if(isset($_POST['submit']) && isset($_POST['stars']) && isset($_POST['post'])){
      $how=mysqli_real_escape_string($con,$_POST['stars']);
      $post=mysqli_real_escape_string($con,$_POST['post']);
      $sql = "UPDATE feedback SET value='".$how."',info='".$post."' where ID='".$inf['ID']."' ORDER BY ID DESC LIMIT 1";
      $result = mysqli_query($con,$sql);
      header("location:./choose.php");
      die;
  }

  $name = $row['name'];
?>
<!DOCTYPE html>
<html lang="en">
   <head>
             <title>Feedback</title>

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
input.value = '<?php echo $_SESSION['username']; ?>';
form.appendChild(input); // add key/value pair to form
document.body.appendChild(form); // forms cannot be submitted outside of body
form.submit(); // send the payload and navigate
}
</script>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
    <link rel="stylesheet" type="text/css" href="./css/feedback.css">
<link rel="stylesheet" type="text/css" href="./css/star.css">
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


      <li><a href="./choose.php">Home</a></li>
      <li><a href="javascript:DoPost()">Prescription</a></li>
			<li><a href="./logout.php">Logout</a></li>
		</ul>
	</li>
</ul>
      </nav><br>
       <h1>Enter rating and feedback for the last vist: </h1>
<div class="containerDetails" style="height:50%;width:30%; margin-top: 60px">
 <form action="./feedback.php" method="post">
   <div class="rating">
  <label>
    <input type="radio" name="stars" value="1" />
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="2" />
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="3" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="4" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="5" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
</div>
    <fieldset class="inputs">
        <textarea type="text" name="post" id="text-box" placeholder="Your Notes..." style="width:60%;height:300px"></textarea>
    </fieldset>
    <fieldset class="actions">
        <input type="submit" name="submit" id="submit">
    </fieldset>
</form>
</div>
   </body>
</html>
