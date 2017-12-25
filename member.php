<?php
require 'steamauth/steamauth.php';
include_once 'db_connect.php';
$q="select match_played from users where steamid=$_SESSION[steamid]";
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/style_member.php" rel="stylesheet">
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

     header("location:index.php");//login button

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

		<section id="content">

			<h1><?php echo $steamprofile['personaname']; ?></h1>

			<div id="profile_pic"></div>

			<div id="head"></div>

			<h2>Team Name:</h2>
			<?php 
			$qu="select `clanid` from users where steamid=$_SESSION[steamid]";
			$result=mysqli_query($db,$qu);
			$row=mysqli_fetch_array($result);
			echo $row[0];
			if($row['clanid']==NULL){
				echo "You have not joined any team. Use menu below to join/create team.";
			}
			else {
				
				$q="select `clan_name` from `clans` where `clanid`='".$row['clanid']."'";
				$p=mysqli_query($db,$q);
				$col=mysqli_fetch_array($p) or die(mysqli_error($db));
				print "<h2>".$col['clan_name'];
				echo "</h2>";
				
						 } ?>
			
			
			
			<ul>
				<a href="#">
					<li>
						<p><img src="images/steam.png" id="profileimgid"></p>
					</li>
				</a>
				<a href="#">
					<li>
						<p><img src="images/battle_net.png" id="profileimgid"> </p>
					</li>
				</a>
				<a href="#">
					<li>
						<p><img src="images/origin.png" id="profileimgid"></p>
					</li>
				</a>
				<a href="#">
					<li>
						<p><img src="images/uplay2.png" id="profileimgid"></p>
					</li>
				</a>
				
			</ul>

		</section>
		<hr style="border-top: 2px solid #ccc">
		
		<div id="member_menu">

				<div class="block" id="create_team">
					<a href="#mymodal" data-toggle="modal" data-target="#mymodal">
						<td><i class="fa fa-plus"></i></td>
						<h3>Create Team</h3>
					</a>
				</div>


				<!-- Modal -->
				<div id="mymodal" class="modal fade mymodal" role="dialog" tabindex="-1" aria-labelledby="mymodal">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Create clan</h4>
							</div>
							<div class="modal-body">
								<p>
									<?php include 'create_clan.php';?>
								</p>
							</div>
						</div>

					</div>
				</div>

				<!--Modal end -->


				<div class="block" id="join_clans">
					<a href="#j_clans" data-toggle="modal" data-target="#j_clans">
						<td><i class="fa fa-user-plus"></i></td>
						<h3>Join Clan</h3>
					</a>
				</div>

				<div id="j_clans" class="modal fade j_clans" role="dialog" tabindex="-1" aria-labelledby="j_clans">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">View Clans</h4>
							</div>
							<div class="modal-body">
								<p>
									<?php include 'join_clan.php';?>
								</p>
							</div>
						</div>

					</div>
				</div>


				<div class="block" id="view_clans">
					<a href="#v_clans" data-toggle="modal" data-target="#v_clans">
						<td><i class="fa fa-eye"></i></td>
						<h3>View Clans</h3>
					</a>
				</div>


				<!-- Modal -->
				<div id="v_clans" class="modal fade v_clans" role="dialog" tabindex="-1" aria-labelledby="v_clans">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">View Clans</h4>
							</div>
							<div class="modal-body">
								<p>
									<?php include 'view_clans.php';?>
								</p>
							</div>
						</div>

					</div>
				</div>


				<div class="block" id="sch_match">
					<a href="sch_match.php">
						<td><i class="fa fa-gamepad"></i></td>

						<h3>Schedule a Match</h3>
					</a>
				</div>
			</div>
			
<div id="stat">
				<?php echo $stat;?>
			</div>
	</body>

	</html>
