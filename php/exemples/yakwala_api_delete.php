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
		
		$user = $response->user; 
		
		
		//PLACES
		// SEARCH PLACES
		$params = array('count'=>3);
		$response = $yakwala->GetPublic("api/place/search/tit",$params);
		$places = json_decode($response)->places;
		//var_dump($response);
		echo "<br><br> <b>PLACES:</b><br>";
		foreach($places as $place){
			echo $place->title."<br>";
		}
		
		// delete a place with a restfull method 
		$params = array('place'=>$places[0]->_id);
		$response = $yakwala->GetPrivate("api/place/".$user->id,$params,'DELETE');
		$delete = json_decode($response);
		var_dump($delete);
		
		// delete a place with a non restfull method sending an array
		$params = array('place'=>$places[1]->_id);
		$response = $yakwala->GetPrivate("api/delplace/".$user->id,$params,'POST');
		$delete = json_decode($response);
		var_dump($delete);
		
		
		
		// SEARCH PLACES
		$params = array('count'=>3);
		$response = $yakwala->GetPublic("api/place/search/tit",$params);
		$places = json_decode($response)->places;
		//var_dump($response);
		echo "<br><br> <b>PLACES:</b><br>";
		foreach($places as $place){
			echo $place->title."<br>";
		}
		
		/*
		//INFOS
		// get infos
		$response = $yakwala->GetPrivate("api/user/feed/".$user->id);
		$infos = json_decode($response);
		echo '<b>Last infos posted : <br></b>';
		foreach($infos->data as $info){
			echo $info->title.' (id= '.$info->_id.' )<br>';
		}
		
		// delete the first one with a post method
		$params = array('info'=>json_encode(array('_id'=>$infos->data[0]->_id)));
		$response = $yakwala->GetPrivate("api/user/delfeed/".$user->id,$params,'POST');
		$delete = json_decode($response);
		var_dump($delete);
		
		// delete the first one with a post method
		$params = array('info'=>json_encode(array('_id'=>$infos->data[1]->_id)));
		$response = $yakwala->GetPrivate("api/user/feed/".$user->id,$params,'DELETE');
		$delete = json_decode($response);
		var_dump($delete);
		
		// get infos
		$response = $yakwala->GetPrivate("api/user/feed/".$user->id);
		$infos = json_decode($response);
		echo '<b>Last infos posted : <br></b>';
		foreach($infos->data as $info){
			echo $info->title.' (id= '.$info->_id.' )<br>';
		}
		*/
		
		
		
		/*
		//FAVPLACE
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
		
		
		//SUBSCRIBE TO USER FEED
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
		
		//SUBSCRIBE TO TAGS
		//list subscribtions : 
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$user->id);
		$tagsubs = json_decode($response);
		var_dump($response);
		echo "<br><br> <b>TAG SUBSCRIBTIONS:</b><br>";
		foreach($tagsubs->data->tagsubs as $tagsubsItem){
			echo $tagsubsItem.'<br>';
		}
		
		//delete the first one with a post method
		$params = array('tagsubs'=>$tagsubs->data->tagsubs[0]);
		$response = $yakwala->GetPrivate("api/unsubscribe/tag/".$user->id,$params,'POST');
		$delete = json_decode($response);
		var_dump($delete);
		
		// delete the second one with a DELETE method ( RESTfull )
		$params = array('tagsubs'=>$tagsubs->data->tagsubs[1]);
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$user->id,$params,'DELETE');
		$delete = json_decode($response);
		var_dump($delete);

		// list subscribtions ( again )
		$response = $yakwala->GetPrivate("api/subscribe/tag/".$user->id);
		$tagsubs = json_decode($response);
		echo "<br><br> <b>TAG SUBSCRIBTIONS:</b><br>";
		foreach($tagsubs->data->tagsubs as $tagsubsItem){
			echo $tagsubsItem.'<br>';
		}
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

