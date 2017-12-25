<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="Description" content="GIC is a community of gamers who like to play and share fun together. We also run TF2 servers across asia.">
		<meta name="Keywords" content="GIC,gaming,gaminginstitute,tf2,csgo,dota2,steam,indian gamers, indian tf2 server, indian gaming community, pc gaming, paladins, overwatch">

		<link href="css/style.css" rel="stylesheet">
		<link rel="icon" href="images/favicon.ico">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript" async></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript" async></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript" async></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" async></script>
		<link rel="stylesheet" href="css/css.php" media="screen">
		<style>
			.banner-head a:hover {
				text-decoration: none;
				color: lightblue !important;
			}

			.flashit {
				-webkit-animation: flash ease-out 7s infinite;
				-moz-animation: flash ease-out 7s infinite;
				animation: flash ease-out 7s infinite;
				animation-delay: 2s;
			}

			@-webkit-keyframes flash {
				from {
					opacity: 0;
				}
				92% {
					opacity: 0;
				}
				93% {
					opacity: 0.6;
				}
				94% {
					opacity: 0.2;
				}
				96% {
					opacity: 0.9;
				}
				to {
					opacity: 0;
				}
			}

			@keyframes flash {
				from {
					opacity: 0;
				}
				92% {
					opacity: 0;
				}
				93% {
					opacity: 0.6;
				}
				94% {
					opacity: 0.2;
				}
				96% {
					opacity: 1;
				}
				to {
					opacity: 0;
				}
			}

		</style>

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
						<li><a href="index">Home</a></li>
						<li><a href="servers">Servers</a></li>
						<li><a href="download">Downloads</a></li>
						<li><a href="http://gaminginstitute.in/bans/">Sourcebans</a></li>
						<li><a href="about">About us</a></li><br>





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
		<!- -Endheader- ->
		<section class="banner">
			<div class="banner-inner lightning flashit">
				<img src="" id="banner-img">
				<div id="hlstat">
					<h3>GIC hlstatsx</h3>
					Tracking
					<?php ?>
				</div>
				<h2 class="banner-head"><br><br>
					<br><br>
				</h2>

			</div>
		</section>

		<!--end banner-->
		<section class="one-fourth" id="matches">
			<a href="matches">
				<td><i class="fa fa-clock-o"></i></td>
				<h3>Matches</h3>
			</a>
		</section>

		<section class="one-fourth" id="results">
			<a href="logs">
				<td><i class="fa fa-trophy"></i></td>
				<h3>Match Logs</h3>
			</a>
		</section>
		<section class="one-fourth" id="reg">
			<a href="ultiduo">
				<td><i class="fa fa-pencil"></i></td>
				<h3>Team Registration</h3>
			</a>
		</section>
		<section class="one-fourth" id="upload">
			<a href="https://gaminginstitute.in/uploader">
				<td><i class="fa fa-upload"></i></td>
				<h3>Upload Map</h3>
			</a>
		</section>
		<div id="up_matches">
			<div id="match_head">
				<center>
					<h3 class="match_header">Upcoming matches</h3>
				</center>
			</div>
			<div id="list_matches">
				<table class="table table-responsive table_match_table">
					<?php
			$q="select team1,team2, date from matches WHERE complete = 0 ORDER BY DATE LIMIT 5";
			$r=mysqli_query($db,$q);
			while($row=mysqli_fetch_array($r)){
				echo "<tr><td>$row[0]</td>";
				echo "<td>$row[1]</td>";
				echo "<td>$row[2]</td></tr>";
			}
				
			?>
				</table>
			</div>
			<hr style="color:white; width:100%">
			<div id="icons">
				<center>
					<h3 class="icon_header">Join Us</h3>
				</center>
				<div id="icon_id">
					<div class="icon_links">
						<a href="http://steamcommunity.com/groups/gamiin" target="_blank"><img src="images/steam.png" id="steam"></a>
					</div>
					<div class="icon_links">
						<a href="http://discord.me/gic" target="_blank"><img src="images/discord.png" id="discord"></a>
					</div>
				</div>
			</div>
			<hr style="color:white; width:100%">
			<!--End of four-column section-->
			<div id="footer"><br><br><br><br>
				<div class="footer-block">
					<div class="footer-column">
						<h4>GIC</h4>
						<a href="https://gaminginstitute.in/stat">Hlstatsx</a><br>
						<a href="servers">Servers</a><br>
						<a href="credits.htm">Credits</a><br>
						<a href="donate">Donation</a><br>
					</div>
					<div class="footer-column">
						<h4>Matches</h4>
						<a href="matches">Upcoming Matches</a><br>
						<a href="logs">Logs</a><br>
						<a href="sch_match">Schedule Matches</a><br>
						<a href="clans">Clans</a><br>
						<a href="tour_list">Tournaments</a><br>
						<a href="faqs">FAQs/Rules</a><br>
					</div>
					<div class="footer-column">
						<h4>Report</h4>
						<a href="http://steamcommunity.com/groups/gamiin/discussions/1/1471966894881988542/">Report Bug</a><br>
						<a href="http://steamcommunity.com/groups/gamiin/discussions/1/1471966894881988542/">Suggestions</a><br>
						<a href="http://gaminginstitute.in/bans/">Server Bans</a><br>
						<a href="#">Report Abuse</a><br>
					</div>

				</div>
			</div>
			<div id="footer2">
				<center><br><br> &copy; 2017 Gaming Institute <br><img src="images/logo2.png"><br></center>
			</div>
		</div>

		<?php
	/**
	 * include database settings
	 */
	require_once( 'admin/visitorTracking/src/_installation/db.php' );

	/**
	 * include the class
	 */
	require_once( 'admin/visitorTracking/src/class.visitorTracking.php' );

	/**
	 * instance the class
	 */
	$visitors = new visitorTracking();
?>


	</body>

	</html>
