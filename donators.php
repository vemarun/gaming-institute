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

	<h2>- Donators - </h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>Value($)</th>
		</tr>
		<?php 
				include_once 'db_connect2.php';
				$q="select steamID,sum(tradeValue) from tradeoffers group by steamID";
				$r=mysqli_query($db,$q);
				while($row=mysqli_fetch_array($r)){?>
		<tr>
			<td>
				<?php convert($row[0]); ?>
			</td>
			<td>
				<?php echo $row[1]; }?>
			</td>
		</tr>

	</table>
	<h2><a href="https://steamcommunity.com/tradeoffer/new/?partner=294994601&token=wQU7CFHE" target="_blank"><button class="btn btn-success">Donate</button></a></h2>
