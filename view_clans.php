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
</head>

<body>
	<center>
		<table class="table table-responsive">
			<tr>
				<th>Clan Id</th>
				<th>Clan Name</th>
				<th>Clan Image</th>
				<th>Clan Owner</th>
			</tr>
			<?php
	require 'db_connect.php';
	$qu="select * from clans";
	$res=mysqli_query($db,$qu);
		while($row=mysqli_fetch_array($res)){?>

				<tr>
					<td>
						<?php echo $row[0]; ?>
					</td>
					<td>
						<?php echo $row[1]; ?>
					</td>
					<td>
						<?php echo $row[2]; ?>
					</td>
					<td>
						<a href="http://steamcommunity.com/profiles/<?php echo $row['clan_owner']; ?>" target="_blank">
							<?php echo $row[3]; ?>
						</a>
					</td>
				</tr>


				<?php	}?>
		</table>
	</center>


</body>

</html>
