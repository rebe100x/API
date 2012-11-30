<?php 
	require_once("../yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/php/exemples/yakwala_api_put_place.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
	
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$userid = $response->user->id;
		
		// PUT a place 
		$params = array(
						"place"=>json_encode(
											array(
													"_id" => "50b8b4b80d104ab418000006"
												  ,	"title"     => 'place title updated'
												  , "content"	=> 'place content updated'		
												  , "yakcat"	=> array(
																		'50923b9afa9a95d409000000',
																		'50923b9afa9a95d409000001'
																	)						  
												  , "freetag"	=> array('tag11 updated','tag22 updated')
												  , "outgoinglink"	=> 'http://www.theplacewebsiteupdated.com'
												  ,	"location" =>	array('lat'=>48.5,'lng'=>2.5)
												  ,	"formatted_address" => " 3 rue du Ruisseau updated, Paris , France"
												  ,	"address" => array( 
																		"street_number"=>"3updated"
																		,"street"=>"rue du Ruisseauupdated"
																		,"arr"=> "updated"
																		,"city"=> "Parisupdated"
																		,"state"=> "Parisupdated"
																		,"area"=> "Ile de Franceupdated"
																		,"country"=> "Franceupdated"
																		,"zip"=> "75018updated"
																		)
													, "contact" => array(
																//"tel"=> "0123456789updated",
																"mobile"=>"0612345678updated2",
																"mail"=>"lemail@yakwala.frupdated",
																"transportation"=>"metro 3 station Ruisseauupdated",
																"web"=>"http://www.theplace.comupdated",
																"opening"=>"Tlj de 8h à 20hupdated",
																"closing"=>"dimancehs et jours fériésupdated",
																"specialopening"=>"Nocture le jeudi de 19h à minuitupdated"
															)
												)
											)
					, "picture" =>"@C:\miro.jpg;type=image/jpeg"
				);

						
					
		$response = $yakwala->GetPrivate("api/place/".$userid,$params,'PUT');
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

