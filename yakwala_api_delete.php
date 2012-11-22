<?php 
	require_once("./yakwala_api.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Yakwala";
	// Set your client key and secret
	$client_key = "50a0e2c4fa9a95240b000001";
	$client_secret = "5645a25f963bd0ac846b17eb517cd638754f1a7b";  
	$redirect_uri = "dev.backend.yakwala.com/TEST/API/yakwala_api_delete.php";
	
	
	
	// Load the Yakwala API library
	$yakwala = new YakwalaAPI($client_key,$client_secret);
	
	
	if(array_key_exists("code",$_GET)){
	
		
		$response = $yakwala->GetToken($_GET['code'],$redirect_uri);
		echo 'TOKEN'.$response->access_token;
		$yakwala->SetAccessToken($response->access_token);
		showUserBasics($response->user);
		
		$user = $response->user; 
		
		/**FAVPLACE**/
		// get favplace
		$response = $yakwala->GetPrivate("api/favplace/".$user->id);
		$favplace = json_decode($response);
		foreach($favplace->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		//delete the first one with a post method
		$params = array('place'=>json_encode(array('_id'=>$favplace->data->favplace[0]->_id)));
		$response = $yakwala->GetPrivate("api/delfavplace/".$user->id,$params,'POST');
		$delete = json_decode($response);
		var_dump($delete);
		
		// delete the second one with a DELETE method ( RESTfull )
		$params = array('place'=>json_encode(array('_id'=>$favplace->data->favplace[1]->_id)));
		$response = $yakwala->GetPrivate("api/favplace/".$user->id,$params,'DELETE');
		$delete = json_decode($response);
		var_dump($delete);

		// get favplace ( again )
		$response = $yakwala->GetPrivate("api/favplace/".$user->id);
		$favplace = json_decode($response);
		foreach($favplace->data->favplace as $favplaceItem){
			echo $favplaceItem->name.' (id= '.$favplaceItem->_id.' )<br>';
		}
		
		
		/**SUBSCRIBE TO USER FEED**/
		//list subscribtions : 
		$response = $yakwala->GetPrivate("api/subscribe/user/".$user->id);
		$usersubs = json_decode($response);
		echo "<br><br> <b>USER SUBSCRIBTIONS:</b><br>";
		foreach($usersubs->data->usersubs as $usersubsItem){
			echo $usersubsItem->userdetails.' (id= '.$usersubsItem->_id.' )<br>';
		}
		
		//delete the first one with a post method
		$params = array('usersubs'=>json_encode(array('_id'=>$usersubs->data->usersubs[0]->_id)));
		$response = $yakwala->GetPrivate("api/unsubscribe/user/".$user->id,$params,'POST');
		$delete = json_decode($response);
		var_dump($delete);
		
		// delete the second one with a DELETE method ( RESTfull )
		$params = array('usersubs'=>json_encode(array('_id'=>$usersubs->data->usersubs[1]->_id)));
		$response = $yakwala->GetPrivate("api/subscribe/user/".$user->id,$params,'DELETE');
		$delete = json_decode($response);
		var_dump($delete);

		// list subscribtions ( again )
		$response = $yakwala->GetPrivate("api/subscribe/user/".$user->id);
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
			Profile Picture: <img src='".$userBasic->profile_picture."' width='20' />
		";
	}
?>

