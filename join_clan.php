<h3>Read carefully:</h3>
	<ul>
		<li>Don't join any clan without permission of clan owner.</li>
		<li>In case you joined any clan without permission of clan owner, clan owner has full right to expel you. </li>
		<li>Follow the rule of clan.</li>
	</ul>

<hr><br>
<center>
<?php
	require_once 'db_connect.php';
	require_once 'steamauth/steamauth.php';
	$qu="select clanid from users where steamid=$_SESSION[steamid]";
	$res=mysqli_query($db,$qu);
	$row=mysqli_fetch_array($res);
		if($row[0]!=NULL){
			echo "<h3>You have already joined a team. To create/join a new team leave/delete your previous team.</h3>";
		}
	else {?>
	<form method="post">
		<select name="clanid">
		<?php
		include 'db_connect.php';
		$qu="select clanid from clans";
		$result=mysqli_query($db,$qu);
		
		while($row=mysqli_fetch_array($result)){
		?>
			
		<option name="clanid"><?php echo $row[0];?></option>	
		
			<?php }  ?>
		</select>
		<br><br><input type="submit" value="Join" name="sub">
	</form>
	<?php } ?>
</center>
<?php
extract($_POST);
if(isset($_POST['sub'])){
	$q="update users set clanid='$clanid' where steamid=$_SESSION[steamid]";
	$res=mysqli_query($db,$q) or die(mysqli_error($db));
	$stat="You have joined entered clan <a href='member.php'> -> CLICK TO REFRESH <-";
}
