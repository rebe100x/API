<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/php/exemples/yakwala_api_search.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
		
		$userid = "50af3cc0540c32480c000002";
		
		// SEARCH USERS
		$params = array('count'=>3);
		$response = $yakwala->GetPrivate("api/user/search/rebe",$params);
		$userlist = json_decode($response);
		echo "<br><br> <b>USERS SEARCH RESULTS:</b> users/search/rebe<br>";
		print_r($userlist);
		
		
		// SEARCH PLACES
		$params = array('count'=>3);
		$response = $yakwala->GetPublic("api/place/search/tit",$params);
		$places = json_decode($response)->places;
		//var_dump($response);
		echo "<br><br> <b>PLACES:</b><br>";
		foreach($places as $place){
			echo $place->title."<br>";
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

