<?php 
	require_once("./yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/yakwala_api_get.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$userid = $response->user->id;
		
		/*USER FAV PLACE*/
		$response = $yakwala->GetPrivate("api/favplace/".$userid);
		$favplace = json_decode($response);
		echo "<br><br> <b>USER FAVPLACE:</b><br>";
		foreach($favplace->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		/*USER SUBSCRIBTION TO USER FEED*/
		$response = $yakwala->GetPrivate("api/subscribe/user/".$userid);
		$usersubs = json_decode($response);
		echo "<br><br> <b>USER SUBSCRIBTIONS:</b><br>";
		foreach($usersubs->data->usersubs as $usersubsItem){
			echo $usersubsItem->userdetails.' (id= '.$usersubsItem->_id.' )<br>';
		}
		
		
		
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

