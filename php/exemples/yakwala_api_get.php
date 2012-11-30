<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/php/exemples/yakwala_api_get.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
		
		$userid = "50af3cc0540c32480c000002";
		
		
		// PLACES FORM IDS
		$params = array('place'=>urlencode(json_encode(array(array('_id'=>'50b7440c16a3222005000005'),array('_id'=>'50b7435c003482100a000005')))));
		$response = $yakwala->GetPublic("api/place/",$params);
		$places = json_decode($response);
		echo "<br><br> <b>PLACES FROM IDS:</b><br>";
		foreach($places->data as $place){
				echo $place->title."<br>";
		}
		
		/*
		// USERS FEEDS
		$params = array('count'=>3);
		$response = $yakwala->GetPublic("api/user/feed/".$userid,$params);
		$infos = json_decode($response);
		echo "<br><br> <b>USER'S FEED:</b><br>";
		foreach($infos->data as $info){
				echo $info->title."<br>";
		}
			
		
		//USER FAV PLACE
		$response = $yakwala->GetPrivate("api/favplace/".$userid);
		$favplace = json_decode($response);
		echo "<br><br> <b>USER FAVPLACE:</b><br>";
		foreach($favplace->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		//USER SUBSCRIBTION TO USER FEED
		$response = $yakwala->GetPrivate("api/subscribe/user/".$userid);
		$usersubs = json_decode($response);
		echo "<br><br> <b>USER SUBSCRIBTIONS:</b><br>";
		foreach($usersubs->data->usersubs as $usersubsItem){
			echo $usersubsItem->userdetails.' (id= '.$usersubsItem->_id.' )<br>';
		}
		
		//USER SUBSCRIBTION TO TAG
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$userid);
		$tagsubs = json_decode($response);
		echo "<br><br> <b>TAG SUBSCRIBTIONS:</b><br>";
		foreach($tagsubs->data->tagsubs as $tagsubsItem){
			echo $tagsubsItem.'<br>';
		}*/
		
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

