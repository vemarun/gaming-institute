<!DOCTYPE html>

<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/style_servers.css" rel="stylesheet">
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
					<li><a href="about.php">About us</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<div id="addserver">
		<h3>Add Server</h3>
		<form method="post" id="serverform">
			<input type="text" placeholder="ip" name="ip">
			<input type="text" placeholder="port" name="port">
			<input type="text" placeholder="unique name" name="svname">

			<input type="submit" name="sub" value="Add server">
		</form>
	</div>
	<?php
    require 'db_connect.php';
    extract($_POST);
    if(isset($_POST['sub'])){
        $q="insert into servers values('$ip','$port','$svname')";
        $result=mysqli_query($db,$q);
       }
        ?>


		<?php
        
	require __DIR__ . '/PHP-Source-Query-master/SourceQuery/bootstrap.php';

	use xPaw\SourceQuery\SourceQuery;
	
	// Edit this ->
	define( 'SQ_SERVER_ADDR', 'localhost' );
	define( 'SQ_SERVER_PORT', 27015 );
	define( 'SQ_TIMEOUT',     3 );
	define( 'SQ_ENGINE',      SourceQuery::SOURCE );
	// Edit this <-
	
	$Timer = MicroTime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = Array( );
	$Rules   = Array( );
	$Players = Array( );
    
    require 'db_connect.php';
    
    $qu="select * from servers";
    $res=mysqli_query($db,$qu); ?>

			<table class="table table-resposive">
				<tr>
					<th>Server Name</th>
					<th>Ip address</th>
					<th>Port</th>
					<th>Map</th>
					<th>Players</th>
				</tr>


				<?php while($row=mysqli_fetch_array($res)){
    
   
	try
	{
		$Query->Connect($row[0], $row[1]);
		//$Query->SetUseOldGetChallengeMethod( true ); // Use this when players/rules retrieval fails on games like Starbound
		
		$Info    = $Query->GetInfo( );
		$Players = $Query->GetPlayers( );
		$Rules   = $Query->GetRules( );
	}
	catch( Exception $e )
	{
		$Exception = $e;
	}
	finally
	{
		$Query->Disconnect( );
	}
	
	$Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );
?>

				<tr>

					<td>



						<?php echo $Info['HostName']; ?>

					</td>
					<td>
						<?php echo $row[0];?>
					</td>
					<td>
						<?php echo $row[1]; ?>
					</td>
					<td>
						<?php echo $Info['Map'];?>
					</td>
					<td>
						<?php echo $Info['Players']; ?>
					</td>
					<td><button class="btn btn-info"><a href="steam://connect/<?php echo $row[0];?>:<?php echo $row[1];?>"></a>Join</button></td>

				</tr>

				<?php echo "<br>"; } ?>

			</table>



</body>

</html>
