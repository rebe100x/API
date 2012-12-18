<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$response = $yakwala->GetPrivate("api/user/".$response->user->id);
		$userdetails = json_decode($response);
		echo "<br><br> <b>USER DETAILS:</b> user/userid<br>";
		print_r($userdetails);
		
		echo "<br><br> <b>USER FAVPLACE:</b>";
		foreach($userdetails->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		$response = $yakwala->GetPrivate("api/user/feed/".$userdetails->data->_id);
		$userfeed = json_decode($response);
		echo "<br><br> <b>LAST 20 INFOS POSTED:</b> user/feed/userid<br>";
		print_r($userfeed);
		
		$response = $yakwala->GetPrivate("api/user/search/rebe");
		$userlist = json_decode($response);
		echo "<br><br> <b>USERS SEARCH RESULTS:</b> user/search/rebe<br>";
		print_r($userlist);
		
	}else{
		$authlink =  $yakwala->AuthenticationLink($redirect_uri);
		//echo $authlink;
		header('Location:'.$authlink);
	}
	function showUserBasics($userBasic){
		echo "
			<br>Name: <b>".$userBasic->full_name."</b><br>
			Login: <b>".$userBasic->username."</b><br>
			Profile Picture: <img src='".$userBasic->profile_picture."' />
		";
	}
?>

