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
		
		
		// get the user id
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		$userid = $response->user->id;
		
		
		// PLACES FORM IDS
		// both parameters are working :
		$params = array('place'=>urlencode(json_encode(array('50cef8f8087542a812000006','50ceeb60cb7b8e2410000010'))));
		$response = $yakwala->GetPublic("api/place/".$userid,$params);
		$places = json_decode($response);
		var_dump($places);
		echo "<br><br> <b>PLACES FROM IDS:</b><br>";
		foreach($places->data as $place){
				echo $place->title."<br>";
		}
		
		
		// USERS PROFILE
		$response = $yakwala->GetPublic("api/user/".$userid,$params);
		$user = json_decode($response);
		echo "<br><br> <b>USER'S PROFILE:</b><br>";
		print_r($user);
		
		// USERS FEEDS
		$params = array('limit'=>0,'skip'=>0);
		$response = $yakwala->GetPublic("api/user/feed/".$userid,$params);
		$infos = json_decode($response);
		print_r($response);
		echo "<br><br> <b>USER'S FEED:</b><br>";
		foreach($infos->data as $info){
				if(!empty($info->thumb))
					echo "<img src='".$info->thumb."' />  ";
				echo $info->title."<br>";
		}
		
		
		//USER FAV PLACE
		$response = $yakwala->GetPublic("api/favplace/".$userid);
		$favplace = json_decode($response);
		var_dump($favplace->data);
		echo "<br><br> <b>USER FAVPLACE:</b><br>";
		foreach($favplace->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		//USER SUBSCRIBTION TO USER FEED
		$response = $yakwala->GetPublic("api/subscribe/user/".$userid);
		$usersubs = json_decode($response);
		echo "<br><br> <b>USER SUBSCRIBTIONS:</b><br>";
		foreach($usersubs->data->usersubs as $usersubsItem){
			echo $usersubsItem->userdetails.' (id= '.$usersubsItem->_id.' )<br>';
		}
		
		//USER SUBSCRIBTION TO TAG
		$response = $yakwala->GetPublic("api/subscribe/tag/".$userid);
		$tagsubs = json_decode($response);
		echo "<br><br> <b>TAG SUBSCRIBTIONS:</b><br>";
		foreach($tagsubs->data->tagsubs as $tagsubsItem){
			echo $tagsubsItem.'<br>';
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

