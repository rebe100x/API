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
		
		$userid = "50af3cc0540c32480c000002";
		
		// SEARCH TAGS
		$params = array('limit'=>0,'skip'=>0,'sort'=>'lastUsed');
		$response = $yakwala->GetPublic("api/tag/search/no",$params);
		$taglist = json_decode($response);
		echo "<br><br> <b>TAGS SEARCH RESULTS:</b> tag/search/no<br>";
		print_r($taglist);
		
		// SEARCH YAKCAT
		$params = array('limit'=>0,'skip'=>0);
		$response = $yakwala->GetPublic("api/cat/search/spor",$params);
		$catlist = json_decode($response);
		echo "<br><br> <b>YAKCAT SEARCH RESULTS:</b> cat/search/spor<br>";
		print_r($catlist);
		
		
		// SEARCH USERS
		$params = array('limit'=>0,'skip'=>0);
		$response = $yakwala->GetPublic("api/user/search/Rebe",$params);
		$userlist = json_decode($response);
		echo "<br><br> <b>USERS SEARCH RESULTS:</b> users/search/rebe<br>";
		print_r($userlist);
		
		
		// SEARCH PLACES
		$params = array('limit'=>2,'skip'=>0,'sensitive'=>0,'lat'=>48,'lng'=>2,'maxd'=>10.95);
		
		$response = $yakwala->GetPublic("api/place/search/place",$params);
		$places = json_decode($response)->places;
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

