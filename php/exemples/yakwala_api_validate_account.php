<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	if(!empty($_GET['key']) && !empty($_GET['token'])){
		$params = array(
			"token"=>$_GET['token'] ,
			"key"=>$_GET['key'],
			"client_key" => "50a0e2c4fa9a95240b000001",
			);
		
		$response = $yakwala->GetPrivate("api/user/validate/",$params,'POST');
		echo '<br>RESULT:<br>';
		var_dump($response);
	}else
		echo "need param : ?key=xxxxx&token=yyy";
			
	
?>

