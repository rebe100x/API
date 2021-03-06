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
		echo '<br>TOKEN'.$response->access_token.'<br>';
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$userid = $response->user->id;
		
		
		// POST favplace
		$params = array('place'=>json_encode(array(array('name'=>'api test1111','location'=>array('lat'=>10,'lng'=>20)),array('name'=>'api test22222','location'=>array('lat'=>11,'lng'=>22)),)));
		$response = $yakwala->GetPrivate("api/favplace/".$userid,$params,'POST');
		$insert = json_decode($response);
		var_dump($insert);
		
		// POST user subscribtion to user feed
		$params = array('usersubs'=>json_encode(array('50cee95bcb7b8e2410000004')));
		$response = $yakwala->GetPrivate("api/subscribe/user/".$userid,$params,'POST');
		$insert = json_decode($response);
		var_dump($insert);
		
		// POST user subscribtion to tags
		$params = array('tagsubs'=>json_encode(array('tag1','tag2')));
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$userid,$params,'POST');
		$insert = json_decode($response);
		var_dump($insert);
		
		
		
	}else{
		$authlink =  $yakwala->AuthenticationLink($redirect_uri);
		//echo $authlink;
		header('Location:'.$authlink);
	}
	
	
	
	
	function showUserBasics($userBasic){
		echo "
			<br>Name: <b>".$userBasic->full_name."</b><br>
			Login: <b>".$userBasic->username."</b><br>
			Profile Picture: <img src='".$userBasic->profile_picture."' width='20' />
		";
	}
?>

