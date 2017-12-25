<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/style_donate.css" rel="stylesheet">
	<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/pace.css" rel="stylesheet">
	<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
	<script src="scripts/donate.js"></script>
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

			$("#triggerall").click(function() {
				$.ajax({
					url: "donators.php",
					success: function(result) {
						$("#content").html(result);
					}
				});
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
					<li><a href="about.php">About us</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<!- -Endheader- ->
	<div id="content">
		<section class="banner">
			<div class="banner-inner">

				<h2 class="banner-head">
					- Support GIC -<br>Any steam community marketables items can be donated.<br> Server slot reservation is instant.<br><br>
					<a href="https://steamcommunity.com/tradeoffer/new/?partner=294994601&token=wQU7CFHE" target="_blank"><button class="btn btn-success">Donate</button></a>
				</h2>

			</div>
		</section>

		<!--end banner-->
		<center>
			<div id="team">
				<h1>Top Donators</h1>
			</div>
		</center>


		<?php	
            function convert($data){
			$tmp=explode(':',$data);
				
			
				
	$steamid64=bcadd((($tmp[2]*2)+$tmp[1]),'76561197960265728');
				
	$url="https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=  &steamids=$steamid64";
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
						<th>Value($)</th>
					</tr>
					<?php 
				include_once 'db_connect2.php';
				$q="select steamID,sum(tradeValue) from tradeoffers group by steamID limit 10";
				$r=mysqli_query($db,$q);
				while($row=mysqli_fetch_array($r)){?>
					<tr>
						<td>
							<?php 
					
					convert($row[0]); ?>
						</td>
						<td>
							<?php echo $row[1]; }?>
						</td>
					</tr>

				</table>
				<center><a href="#" id="triggerall">Full list</a></center>
			</div>
	</div>

	<hr>
	<footer>
		<center>
			All donations are used towards maintainenace of server. If you are facing any problem in slot reservation , contact any <a href="about">admin</a>
		</center>
	</footer>



</body>

</html>
