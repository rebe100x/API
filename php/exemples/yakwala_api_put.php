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
		

		
		/*
		// update a favplace
		$params = array('place'=>json_encode(array(array('name'=>'api test1','location'=>array('lat'=>10,'lng'=>20)),array('name'=>'api test2','location'=>array('lat'=>11,'lng'=>22)),)));
		$response = $yakwala->GetPrivate("api/favplace/".$userid,$params,'PUT');
		$insert = json_decode($response);
		var_dump($insert);
		*/
		
		/*
		// update a user subscribtion to a user feed
		$params = array('usersubs'=>json_encode(array('50cee95bcb7b8e2410000004')));
		$response = $yakwala->GetPrivate("api/subscribe/user/".$userid,$params,'PUT');
		$insert = json_decode($response);
		var_dump($insert);
		*/
		
		/*
		// update a subscribtion to tags
		$params = array('tagsubs'=>json_encode(array('tag11','tag22')));
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$userid,$params,'PUT');
		$insert = json_decode($response);
		var_dump($insert);
		*/
		
		/*
		// update users 
		$params = array(
						"usesr"=>json_encode(array(
					  	 	"address" => array( 
												"street_number"=>"3updated!!!"
												,"street"=>"rue du Ruisseauupdated"
												,"arr"=> "updated"
												,"city"=> "Parisupdated"
												,"state"=> "Parisupdated"
												,"area"=> "Ile de Franceupdated"
												,"country"=> "Franceupdated"
												,"zip"=> "75018updated"
												)
						 //,	"formatted_address" => " 3 rue du Ruisseau updated, Paris , France"
						 ,	"location" =>	array('lat'=>48.5,'lng'=>2.5)
						 ,  "mail" => "mon mail"
						 ,  "name" => "nameNONO"
						 ,  "tag" => array("tag1",'tag2')
						 ,  "bio" => "ma bio updated"
						 ,  "web" => "my new website"
						))
					, "picture" =>"@C:\miro.jpg;type=image/jpeg"
				);

						
					
		$response = $yakwala->GetPrivate("api/user/".$userid,$params,'PUT');
		$insert = ($response);
		var_dump($insert);
		*/
		
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

