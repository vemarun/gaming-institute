<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
?>
	<?php
		$stat="";
		if(isset($_POST['sub'])){
			$q="insert into giveaway(giveaway1) values($_SESSION[steamid])";
			$res=mysqli_query($db,$q);
			$stat="You have successfully entered the giveaway";
		}
		?>
		<!DOCTYPE html>
		<html>

		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link href="css/style.css" rel="stylesheet">
			<link rel="icon" href="images/favicon.ico">
			<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
			<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
			<link href="spop/spop.min.css" rel="stylesheet" type="text/css">
			<script src="spop/spop.min.js" type="text/javascript"></script>
			<link rel="stylesheet" href="css/css.php" media="screen">



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
			<style>
				.giv_img {
					height: 150px;
					width: 220px;
					padding-bottom: 50px;
				}
				
				#stat1 {
					background-color: beige;
					color: dimgray;
					text-align: center;
					font-size: 200%;
				}
				
				a:hover {
					text-decoration: none;
				}

			</style>
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
			</header><br>
			<div id="stat1">
				<?php echo $stat; ?>
			</div>

			<h2 style="color:#6991AC; margin: 0 auto"><b>Game Giveaway</b></h2>
			<center>
				<h4>
					<ol>
						<li><a href="http://steamcommunity.com/groups/gamiin" target="_blank">Click here </a>to invite your friends to GIC group if you have not invited your friends yet. Your entry to giveaway will be cancelled if you will enter without inviting.</li>
						<li>All games are indian region locked.</li>
						<li>In case if any non-indian player wins any game , he/she will be given one TF2 key in place of that game.</li>
						<li>All games will be delivered through steam gifts.</li>
					</ol>
				</h4>
			</center><br><br>
			<img src="images/giveaway/terraria.jpg" class="giv_img">
			<img src="images/giveaway/priml%20carnage.jpg" class="giv_img">
			<img src="images/giveaway/rust.jpg" class="giv_img">
			<img src="images/giveaway/kill%20bad%20guy.jpg" class="giv_img">
			<img src="images/giveaway/rocket%20league.jpg" class="giv_img">
			<img src="images/giveaway/verdun.jpg" class="giv_img">
			<img src="images/giveaway/blockstorm.jpg" class="giv_img">
			<img src="images/giveaway/castleminer.jpg" class="giv_img">
			<img src="images/giveaway/detour.jpg" class="giv_img">
			<img src="images/giveaway/guncraft.jpg" class="giv_img">
			<img src="images/giveaway/iron%20brigade.jpg" class="giv_img">
			<img src="images/giveaway/primal%20fear.jpg" class="giv_img">
			<img src="images/giveaway/wanderlust.jpg" class="giv_img">
			<img src="images/giveaway/windward.jpg" class="giv_img">
			<img src="images/giveaway/worms%20revolution.jpg" class="giv_img">
			<img src="images/giveaway/strike%20vector.jpg" class="giv_img">
			<img src="images/giveaway/sanctum.jpg" class="giv_img">
			<img src="images/giveaway/insurgency.jpg" class="giv_img">
			<br><br><br>
			<?php
	if(!isset($_SESSION['steamid'])) {
		echo "<center><h3>Login to Enter</h3>";

    loginbutton("rectangle"); //login button
		echo "<center>";

}  else { ?>

				<center>

					<form method="post">
						<input type="submit" class="btn btn-info" name="sub" value="Enter Giveaway">
					</form>
				</center>
				<br><br>

				<?php } ?>
				<br><br><br>
				<hr style="display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 1px;">
				<center>
					<h3>Entries</h3>
				</center>
				<br>
				<?php
    function getdetail($id){
        $url="http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=  &steamids=$id";
        $get_json=file_get_contents($url);
        $decode=json_decode($get_json,true);
            
        if(!empty($decode)){
            foreach($decode['response']['players'] as $detail ){
            
                $avatar=$detail['avatarmedium'];
                    $name=$detail['personaname'];
                $profile_url=$detail['profileurl'];
            }
        }
        
        echo "<img src=$avatar class='img-circle'>";
    }
        ?>
					<?php 
			$qu="select giveaway1 from giveaway";
			$r=mysqli_query($db,$qu);
			while($row=mysqli_fetch_array($r)){
				getdetail($row[0]);
			}
			?>
					<br><br><br><br><br>
					<hr style="display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 1px;">

					<center>
						<h3>Winners</h3>

						<?php
$input = array("76561198031916532", "76561198140566270", "76561198141296166", "76561198153940908", "76561198163595630","76561198167597272","76561198170021443","76561198175935671","76561198188840902","76561198193029346","76561198198905201","76561198259637419","76561198260420177","76561198280936171","76561198283607211","76561198294860908","76561198319395652","76561198342083431","76561198361392476","NULL");
shuffle($input);
$rand_keys = array_rand($input, 20);
?>
							<table class="table" style="margin-left:10%">
								<tr>
									<td>Game</td>
									<td>
										Winner
									</td>
								</tr>
								<tr>
									<td>Terraria</td>
									<td>
										<?php echo $input[$rand_keys[0]]; ?>
									</td>
								</tr>
								<tr>
									<td>Primal Carnage: Extinction</td>
									<td>
										<?php echo $input[$rand_keys[1]]; ?>
									</td>
								</tr>
								<tr>
									<td>Rust</td>
									<td>
										<?php echo $input[$rand_keys[2]]; ?>
									</td>
								</tr>
								<tr>
									<td>Kill The Bad Guy</td>
									<td>
										<?php echo $input[$rand_keys[3]]; ?>
									</td>
								</tr>
								<tr>
									<td>Kill The Bad Guy</td>
									<td>
										<?php echo $input[$rand_keys[4]]; ?>
									</td>
								</tr>
								<tr>
									<td>Rocket League</td>
									<td>
										<?php echo $input[$rand_keys[5]]; ?>
									</td>
								</tr>
								<tr>
									<td>Verdun</td>
									<td>
										<?php echo $input[$rand_keys[6]]; ?>
									</td>
								</tr>
								<tr>
									<td>Blockstorm</td>
									<td>
										<?php echo $input[$rand_keys[7]]; ?>
									</td>
								</tr>
								<tr>
									<td>CastleMiner Z</td>
									<td>
										<?php echo $input[$rand_keys[8]]; ?>
									</td>
								</tr>
								<tr>
									<td>Detour</td>
									<td>
										<?php echo $input[$rand_keys[9]]; ?>
									</td>
								</tr>
								<tr>
									<td>Guncraft</td>
									<td>
										<?php echo $input[$rand_keys[10]]; ?>
									</td>
								</tr>
								<tr>
									<td>Iron Brigade</td>
									<td>
										<?php echo $input[$rand_keys[11]]; ?>
									</td>
								</tr>
								<tr>
									<td>Blockstorm</td>
									<td>
										<?php echo $input[$rand_keys[12]]; ?>
									</td>
								</tr>
								<tr>
									<td>Primal Fear</td>
									<td>
										<?php echo $input[$rand_keys[13]]; ?>
									</td>
								</tr>
								<tr>
									<td>Wanderlust: Rebirth</td>
									<td>
										<?php echo $input[$rand_keys[14]]; ?>
									</td>
								</tr>
								<tr>
									<td>Windward</td>
									<td>
										<?php echo $input[$rand_keys[15]]; ?>
									</td>
								</tr>
								<tr>
									<td>Worms Revolution</td>
									<td>
										<?php echo $input[$rand_keys[16]]; ?>
									</td>
								</tr>
								<tr>
									<td>Strike Vector</td>
									<td>
										<?php echo $input[$rand_keys[17]]; ?>
									</td>
								</tr>
								<tr>
									<td>Sanctum</td>
									<td>
										<?php echo $input[$rand_keys[18]]; ?>
									</td>
								</tr>
								<tr>
									<td>Insurgency</td>
									<td>
										<?php echo $input[$rand_keys[19]]; ?>
									</td>
								</tr>
							</table>
					</center>
					<br>
		</body>

		</html>
