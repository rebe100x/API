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
		
		$userid = $response->user->id;
		
		// PUT an info ( in the user's feed )
		$params = array(
						"info"=>json_encode(array(
							"_id"	=> "50c99d20fca9604412000004"
					  	  ,"title"     => 'info title TOTO'
						  , "content"	=> 'info content updated'		
						  , "yakcat"	=> array(
												'50923b9afa9a95d409000',
												'508fc6ebfa9a95680b000029',
												'50896395fa9a950007000000'
											)
						  , "yaktype"	=> 3
						  , "freetag"	=> array('tag11','tag22')
						  	  
						  ,	"placeid"	=> array('_id'=>'50c864e81108f3340d00000a')
						))
					, "picture" =>"@C:\miro.jpg;type=image/jpeg"
				);

						
					
		$response = $yakwala->GetPrivate("api/user/feed/".$userid,$params,'PUT');
		$insert = ($response);
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

