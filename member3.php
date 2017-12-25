<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		<div class="member_detail">
			<table class="table table-responsive" id="user_clan">
				<div id="level">
					<?php echo "<img src='".$_SESSION['steam_avatarmedium']."' id='profileimage'>"; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					<div id="pro_name">&nbsp;&nbsp;
						<?php echo $steamprofile['personaname']; ?>
					</div>

				</div>
				<div id="clan">
					<tr>
						<?php 
			$qu="select clanid from users where steamid=$_SESSION[steamid]";
			$result=mysqli_query($db,$qu);
			$row=mysqli_fetch_array($result);
			if($row[0]==0){
				echo "<td style='color:brown';>You are not in any team. Join any clan or create your own clan.</td>";
			}
			else {
				
				$q="select clan_name,clan_img,clan_owner from clans where clanid=$row[0]";
				$p=mysqli_query($db,$q);
				$col=mysqli_fetch_assoc($p) or die(mysqli_error($db));
				echo "<td><img src='get_image.php?id=$row[0]' id='clan_image'></td>";
				echo "<td><strong>Clan name</strong> : ".$col['clan_name']."</td>";
				echo "<td><strong>Clanid :&nbsp;&nbsp;</strong>".$row[0]."</td>";?>
						<td><a href="http://steamcommunity.com/profiles/<?php echo $col['clan_owner']; ?>" target="_blank">Clan Owner</a></td>
						<?php } ?>
					</tr>
				</div>
			</table>
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
		</div>
	</body>

	</html>
