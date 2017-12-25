<?php

$access_token="EAAZA9AFPdFSYBAEPYZA5lZBz00HdxGMPUBMYCrSWglQQ5pSbZCi0gsPxl7GUjEsvSFZCDd2qBX7zf6miVZAzFIbhbpIxsJZB7PvD9poEad2v9kMBhFtZBc3cWm9gNtnzsFSGOUOtjgYSFpP0FVlrncw1uLybCDSZB0z2nYNZC0tgwemcUrISxIQiws";

$verify_token="gic_testing_bot";
$hub_verify_token=null;

if(isset($_REQUEST['hub_mode'])&&$_REQUEST['hub_mode']=='subscribe')
{
	$challenge=$_REQUEST['hub_challenge'];
	$hub_verify_token=$_REQUEST['hub_verify_token'];
	if($hub_verify_token==$verify_token)
		header('HTTP/1.1 200 OK');
		echo $challenge;
		die;
}
$db=mysqli_connect("localhost","root","","dictionary");
file_put_contents("fb.txt",file_get_contents("http://input"));
$input=json_decode(file_get_contents('php://input'),true);
$sender=$input['entry'][0]['messaging'][0]['sender']['id'];
$message=isset($input['entry'][0]['messaging'][0]['message']['text'])?$input['entry'][0]['messaging'][0]['message']['text']:'';

if($message)
{
	
	
	$q="select * from query where ques RLIKE '[[:<:]]".$message."[[:>:]]'";
	$res=mysqli_query($db,$q);
	if($res){
		while($row=mysqli_fetch_assoc($res)){
			$message_to_reply=$row['response'];
		}
	}
	else
	{
		$message_to_reply="can't understand what your word ".$message." want to say";
	}
	
	
	$url="https://graph.facebook.com/v2.10/me/messages?access_token=".$access_token;
	$jsonData='{
					"recipient":{
						"id":"'.$sender.'"
					},
					"message":{
						"text":"'.$message_to_reply.'"
					}
				 
				}';

	
	$ch=curl_init($url);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonData);
	curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_HTTP_VERSION,CURLOPT_HTTP_VERSION_NONE);
	$result=curl_exec($ch);
	curl_close($ch);
	
}	
?>
