<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/php/exemples/yakwala_api_post_info.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
	
		// IDENTIFY USER
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$userid = $response->user->id;
		
		// POST a place
		$params = array(
						"place"=>json_encode(
											array(
													"title"     => 'place title'
												  , "content"	=> 'place content'		
												  , "yakcat"	=> array(
																		'50923b9afa9a95d409000',
																		'50923b9afa9a95d409000001'
																	)						  
												  , "freetag"	=> array('tag11','tag22')
												  , "outgoinglink"	=> 'http://www.theplacewebsite.com'
												  ,	"location" =>	array('lat'=>48.2,'lng'=>2.3)
												  ,	"formatted_address" => " 3 rue du Ruisseau, Paris , France"
												  ,	"address" => array( 
																		"street_number"=>"3"
																		,"street"=>"rue du Ruisseau"
																		,"arr"=> ""
																		,"city"=> "Paris"
																		,"state"=> "Paris"
																		,"area"=> "Ile de France"
																		,"country"=> "France"
																		,"zip"=> "75018"
																		)
													, "contact" => array(
																"tel"=> "0123456789",
																"mobile"=>"0612345678",
																"mail"=>"lemail@yakwala.fr",
																"transportation"=>"metro 3 station Ruisseau",
																"web"=>"http://www.theplace.com",
																"opening"=>"Tlj de 8h à 20h",
																"closing"=>"dimancehs et jours fériés",
																"specialopening"=>"Nocture le jeudi de 19h à minuit"
															)
												)
											)
					, "picture" =>"@C:\miro.jpg;type=image/jpeg"
				);

						
					
		$response = $yakwala->GetPrivate("api/place/".$userid,$params,'POST');
		var_dump($response);
		$theplace = JSON_decode($response)->place;
		
		
		// POST an info ( in the user's feed )
		$params = array(
						"info"=>json_encode(array(
							"title"     => 'info title'
						  , "content"	=> 'info content'		
						  , "yakcat"	=> array(
												'50923b9afa9a95d409000',
												'50923b9afa9a95d409000001'
											)
						  , "yaktype"	=> 3
						  , "freetag"	=> array('tag11','tag22')
						  , "pubdate"	=> 1354363261		  
						  ,	"placeid"	=> array('_id'=>$theplace->_id)
						))
					, "picture" =>"@C:\miro.jpg;type=image/jpeg"
				);

						
					
		$response = $yakwala->GetPrivate("api/user/feed/".$userid,$params,'POST');
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

