<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/style.css" rel="stylesheet">
		<link rel="icon" href="images/favicon.ico">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<link rel="stylesheet" href="css/css.php" media="screen">
		<script src="brackets/jquery.challonge.js" type="text/javascript"></script>




		<title>Home - GIC </title>

		<script>
			$(document).ready(function() {


				var num = 80;
				$(window).scroll(function() {
					//if you hard code, then use console
					//.log to determine when you want the 
					//nav bar to stick.  
					console.log($(window).scrollTop())
					if ($(window).scrollTop() > num) {
						$('#header-inner').addClass('navbar-fixed');
					}
					if ($(window).scrollTop() < num) {
						$('#header-inner').removeClass('navbar-fixed');
					}
				});
				$('.bracket').challonge('bcx7ixf1', {
					subdomain: '',
					theme: '1',
					multiplier: '1.0',
					match_width_multiplier: '1.0',
					show_final_results: '0',
					show_standings: '0'
				});
			});

		</script>
	</head>

	<body>
		<header>
			<div id="header-inner">
				<a href="index.php" id="logo"></a>
				<nav>
					<a href="#" id="menu-icon"></a>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="servers.php">Servers</a></li>
						<li><a href="download.php">Downloads</a></li>
						<li><a href="http://gaminginstitute.in/bans/">Sourcebans</a></li>
						<li><a href="about.php">About us</a></li><br>





						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

    loginbutton("rectangle"); //login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
    //Protected content
?>
								<div class="dropdown">
									<?php echo "<img src='".$_SESSION['steam_avatar']."'>"; ?>
									<div class="dropdown-content">
										<a href="profile.php" class="drop">Dashboard</a>
										<a href="clans.php" class="drop">Clans</a>
										<a href="#" class="drop">
											<?php logoutbutton(); } ?>
										</a>


									</div>
								</div>
						</div>





					</ul>
				</nav>
			</div>
		</header>
		<div class="brackets">
			<iframe src="http://challonge.com/bcx7ixf1/module" width="100%" height="500" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>

		</div>
	</body>

	</html>
