<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/style_about.css" rel="stylesheet">
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
					<li><a href="http://104.223.87.143/bans/">Sourcebans</a></li>
					<li><a href="about.php">About us</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<!- -Endheader- ->
	<section class="banner">
		<div class="banner-inner">

			<h2 class="banner-head">
				GIC brings back the good old days of TF2 experience.<br> More details about our servers <a href="servers.php">here</a><br><br>
			</h2>

		</div>
	</section>

	<!--end banner-->
	<center>
		<div id="team">
			<h1>Administrators / Moderators</h1>
		</div>
	</center>

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
        
        echo "<a href=$profile_url target='_blank'><img src=$avatar>  $name";
    }
        ?>

		<div class="table-responsive">
			<table class="table">
				<tr>
					<th>Name</th>
					<th>Position</th>
				</tr>
				<tr>
					<td>
						<?php getdetail("76561198196107719"); ?>
					</td>
					<td>Administrator</td>
				</tr>
				<?php 
				include_once 'db_connect.php';
				$q="select * from mods order by position";
				$r=mysqli_query($db,$q);
				while($row=mysqli_fetch_array($r)){?>
				<tr>
					<td>
						<?php getdetail($row['steamid']); ?>
					</td>
					<td>
						<?php echo $row['position']; }?>
					</td>
				</tr>

			</table>
		</div>
		<hr>
		<footer>
			<center>
				Website designed and developed by <a href="https://steamcommunity.com/profiles/76561198196107719" target='_blank'>lava</a>, logo by
				<a href="https://steamcommunity.com/profiles/76561198045448849" target='_blank'>topgunLT1</a>, sfm artworks by <a href="http://steamcommunity.com/profiles/76561198137561268" target='_blank'>Suicide</a>
				<br>&copy; GIC
			</center>
		</footer>

</body>

</html>
