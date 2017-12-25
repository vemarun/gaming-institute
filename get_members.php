<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';

?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Custom script as written on bootstrap page -->
		<link href="css/style_tour.css" rel="stylesheet">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
		<title>Home - GIC </title>

		<script>
			var data = <?php echo $row[0];?>;
			var link = "get_members.php?clanid=" + data;
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
				$("#get_members").click(function() {
					$.ajax({
						url: link,
						success: function(result) {
							$("#show_members").html(result);
						}
					});
				})
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
		<br><br>
		
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
        
        echo "<a href=$profile_url target='_blank'><img src=$avatar>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $name";
    }
        ?>


			<table class="table table-responsive get_mem" style="width:60%;margin-left:20%;margin-right:20%">

				<th>Members</th>
				<?php
include_once 'db_connect.php';
    if(isset($_GET["clanid"]))
    {
        $data = $_GET["clanid"];
		$tour_name=$_GET["tour_name"];
		$qu="select player_num from gic_tour where tour_name='".$tour_name."'";
		$result=mysqli_query($db,$qu);
		$number=mysqli_fetch_array($result);
        $q="select clan_owner,member1,member2,member3,member4,member5,member6,member7,member8,member9,member10,member11 from $tour_name where clanid='$data'";
		$r=mysqli_query($db,$q);
		if($number[0]==2){
		while($row=mysqli_fetch_array($r)){
		
			echo "<tr><td>";
			echo getdetail($row['clan_owner']);
			echo "<br><br><br></td></tr>";
				echo "<tr><td>";
			echo getdetail($row['member1']);
			echo "<br><br><br></td></tr>";
			
		}
		}
		else if($number[0]==12){
			while($row=mysqli_fetch_array($r)){
		
			echo "<tr><td>";
			echo getdetail($row['clan_owner']);
			echo "<br><br><br></td></tr>";
				echo "<tr><td>";
			echo getdetail($row['member1']);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[1]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[2]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[3]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[4]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[5]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[6]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[7]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[8]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[9]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[10]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[11]);
			echo "<br><br><br></td></tr>";
			}
		}
		else if($number[0]==8){
			while($row=mysqli_fetch_array($r)){
		
			echo "<tr><td>";
			echo getdetail($row['clan_owner']);
			echo "<br><br><br></td></tr>";
				echo "<tr><td>";
			echo getdetail($row['member1']);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[1]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[2]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[3]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[4]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[5]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[6]);
			echo "<br><br><br></td></tr>";
			echo "<tr><td>";
			echo getdetail($row[7]);
			echo "<br><br><br></td></tr>";
		
			}
		}
    }
?>

			</table>

	</body>

	</html>
