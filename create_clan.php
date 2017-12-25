<center>
	<?php
	require_once 'db_connect.php';
	require_once 'steamauth/steamauth.php';
	$qu="select clanid from users where steamid=$_SESSION[steamid]";
	$res=mysqli_query($db,$qu);
	$row=mysqli_fetch_array($res);
		if($row[0]!=NULL){
			echo "<h3>You already own a team. To create a new team leave/delete your previous team.</h3>";
		}
		else{ ?>
		<form method="post">
		<input type="text" name="clanid" placeholder="Team Tag">
			<input type="text" placeholder="Clan Name" name="clan_name"><br><br>
			<!--Clan Image : <input type="file" placeholder="Clan image" name="clan_img"><br><br> -->
			<input type="submit" value="Create" name="sub">
		</form>
</center>
<?php }
				?>
<?php
	require 'db_connect.php';
	extract($_POST);
	$stat="";
	if(isset($_POST['sub'])){ 
	
		
	$q="INSERT INTO `clans`(`clanid`,`clan_name`,`clan_owner`) VALUES ('$clanid','$clan_name','$_SESSION[steamid]')";
		
		
	if(mysqli_query($db, $q)){
		$qu="update users set clanid='$clanid' where steamid=$_SESSION[steamid]";
		$result=mysqli_query($db,$qu) or die(mysqli_error($db));
		$stat="CLAN CREATED SUCCESSFULLY<a href='member.php'> -> CLICK TO REFRESH <-";
	}
		else
		{
			$stat="Something went wrong";
		}
	}
	?>
