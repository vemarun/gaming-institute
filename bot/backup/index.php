<?php

include_once '../db_connect.php';

$access_token="EAAZA9AFPdFSYBALT6INu23sywkQZBPSF6oqln1uzj0ogmUEzeUjUkC9LGXXWnjgLX6qzehZBXCVFHslFE9ZCENPxUZBO6llbZAfstvCbtrBA5FvcVPt8lR2s0xrRreUSUkNXtulHFA1RgWYQfxU8OgDBS19BPW3KmMXZCbRUOuV62JYOG4ujOY2";

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
	$words = explode(" ", $message);
	//$q="select * from mean where word='".$words[0]."'";
	//$res=mysqli_query($db,$q);
	//if($res){
	//$row=mysqli_fetch_assoc($res);
	
	if($words[0]="hi"||$words[0]="hey"||$words[0]="how"){
		$message_to_reply="Hi, I am fine and you ?
		Use !help to get more info";
	}
	
	else if($words[0]="!help"|| $words[0]="help"){
		$message_to_reply="Test Bot made by Arun Verma (lava).
		Reply with any word to search for meaning in dictionray.
		Other Commands :
		!owner - Get owner information
		!time - get time";
	}
	
	/**else if($row){
	$message_to_reply=$row['meaning'];
	}
	
	
	$words = explode(" ", $message);
	if($words[0]=="Hi"||$words[0]=="Hello"||$words[0]=="hi"||$words[0]=="hello")
	{
		$message_to_reply="hi there :)"."How can I help You??";
	}
	else if($words[0]=="What"||$words[0]=="Why"||$words[0]=="Who"||$words[0]=="who"||$words[0]=="what"||$words[0]=="why")
	{
		$message_to_reply="I am just a normal bot to answer the questions on behalf of my creator lava:)";
	} 
	***/
	
	
	else
	{
		$message_to_reply="Unable to find the meaning of ".$words[0];
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
