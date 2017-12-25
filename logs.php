<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';

?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="images/favicon.ico">
		<link href="css/style.css" rel="stylesheet">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
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
						<li><a href="index.php">Home</a></li>
						<li><a href="servers.php">Servers</a></li>
						<li><a href="download.php">Downloads</a></li>
						<li><a href="bans/">Sourcebans</a></li>
						<li><a href="about.php">About us</a></li><br>




						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

     loginbutton('rectangle');//login button

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
		<div id="match_table">
			<center>
				<h2><a href="http://logs.tf/profile/76561198078552523/uploads" target="_blank">Match Logs</a></h2>
				<table class="table table-responsive table_match_table">
					<tr>
						<th>Match ID</th>
						<th>Team A</th>
						<th>Team B</th>
						<th>Winner</th>

						<th>Date</th>
						<th>Time</th>
						<th>Log</th>

					</tr>
					<?php
			$q="select matchid, team1,team2, winner, date, time, logs from matches WHERE complete = 1 ORDER BY DATE LIMIT 10";
			$r=mysqli_query($db,$q);
			while($row=mysqli_fetch_array($r)){
				echo "<tr><td>$row[0]</td>";
				echo "<td>$row[1]</td>";
				echo "<td>$row[2]</td>";
				echo "<td>$row[3]</td>";
				echo "<td>$row[4]</td>";
				echo "<td>$row[5]</td>";
				echo "<td><a href=$row[6] target=_blank>$row[6]</a></td></tr>";
			}
				
			?>
				</table>
		</div>
