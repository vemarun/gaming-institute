<?php
require 'steamauth/steamauth.php';
include_once 'db_connect.php';
$tour_name='ultiduo';
?>


		<!DOCTYPE html>
		<html>

		<head>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<link href='css/style_tour.css' rel='stylesheet'>
			<link href='css/font-awesome-4.7.0/css/font-awesome.min.css' rel='stylesheet'>
			<link href='bootstrap/css/bootstrap.min.css' rel='stylesheet'>
			<link href='css/pace.css' rel='stylesheet'>
			<script src='bootstrap/js/pace.min.js' type='text/javascript'></script>
			<script src='bootstrap/js/bootstrap.min.js' type='text/javascript'></script>
			<script src='bootstrap/js/jquery.min.js' type='text/javascript'></script>
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
				<div id='header-inner'>
					<a href='index.php' id='logo'></a>
					<nav>
						<a href='#' id='menu-icon'></a>
						<ul>
							<li><a href='index.php'>Home</a></li>
							<li><a href='servers.php'>Servers</a></li>
							<li><a href='download.php'>Downloads</a></li>
							<li><a href='bans/'>Sourcebans</a></li>
							<li><a href='about.php'>About us</a></li><br>




							<div id='login'>
								<?php
if(!isset($_SESSION['steamid'])) {

     loginbutton('rectangle');//login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
    //Protected content
?>
									<div class='dropdown'>
										<?php echo '<img src='.$_SESSION['steam_avatar'].'>'; ?>
										<div class='dropdown-content'>
											<a href='profile.php' class='drop'>Dashboard</a>
											<a href='clans.php' class='drop'>Clans</a>
											<a href='#' class='drop'>
												<?php logoutbutton(); } ?>
											</a>


										</div>
									</div>
							</div>



						</ul>
					</nav>
				</div>
			</header>
			<?php
		extract ($_POST);
		$today = date('Y-m-d H:i:s');
		if(isset($_POST['sub'])){
			$q="insert into $tour_name(clan_name,clanid,clan_owner,member1,time_created) values('$clan_name','$clanid','$clan_owner','$member1','$today')";
			if(mysqli_query($db,$q)){
				echo '<h3 style=background-color:cyan>Verified</h3>';
			}
			$qu="insert into clans(clanid,clan_name,clan_owner,time_created) values('$clanid','$clan_name','$clan_owner','$today')";
			if(mysqli_query($db,$qu)){
			echo '<h3 style=background-color:cyan>Successfully Registered</h3>';}
			
		}
		
		?>



				<center>
					<?php
if(!isset($_SESSION['steamid'])) {

     echo '<h1>Login to Register Team</h1>';
	loginbutton('rectangle');//login button

}
		else { ?>
						<form method='post'>
							<input type='text' placeholder='Team Name' size='20' name='clan_name'><br>
							Team Tag (max 4 characters) : <input type='text' placeholder='Team Tag (max 4 char)' size='20' name='clanid'>
							<hr style='border-top: 2px solid #ccc'><br> Team Captain : &nbsp;<input type='text' placeholder='Member 1' size='50' Value='<?php echo $_SESSION['steamid']; ?>' name='clan_owner'><br><br>
							<div class='alert alert-info'>
								<strong>Team Members -</strong> Fill the steamid(64) of team members</div>
							<input type='text' placeholder='Member 2' size='50' name='member1'>
							<br>
							<input type='submit' value='submit' name='sub'><br><br>
						</form>
						<?php } ?>
				</center>

		</body>

		</html>



		