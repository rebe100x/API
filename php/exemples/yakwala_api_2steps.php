<?php 
	require_once("./yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/yakwala_api_2steps.php";
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("access_token",$_GET) && array_key_exists("id",$_GET)){
		
		$yakwala->SetAccessToken($_GET['access_token']);
		
		
		$response = $yakwala->GetPrivate("api/users/".$_GET['id']);
		
		
		$response = $yakwala->GetPrivate("api/users/".$_GET['id']);
		$userdetails = json_decode($response);
		echo "<br><br> <b>USER DETAILS:</b> users/userid<br>";
		print_r($userdetails);
		
		$response = $yakwala->GetPrivate("api/users/feed/".$userdetails->_id."/20");
		$userfeed = json_decode($response);
		echo "<br><br> <b>LAST 20 INFOS POSTED:</b> users/feed/userid/20<br>";
		print_r($userfeed);
		
		$response = $yakwala->GetPrivate("api/users/search/renaud");
		$userlist = json_decode($response);
		echo "<br><br> <b>USERS SEARCH RESULTS:</b> users/search/renaud<br>";
		print_r($userlist);
		
	}else{
		$authlink =  $yakwala->AuthenticationLink($redirect_uri,'token');
		//echo $authlink;
		header('Location:'.$authlink);
	}
	
	function showUserBasics($userBasic){
		echo "
			<br>Name: <b>".$userBasic->full_name."</b><br>
			Login: <b>".$userBasic->username."</b><br>
			Profile Picture: <img src='".$userBasic->profile_picture."'/>
		";
	}
?>

