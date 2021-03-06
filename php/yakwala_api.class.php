<?php
/**
 * YakwalaApi
 * A PHP-based Yakwala client library
 * 
 * ENVIRONNEMENT : need curl activated , tested on PHP 5.3
 *
 * @package php-yakwala 
 * @author Stephen Young <stephen@tryllo.com>, @stephenyoungdev
 * @modified by rebe100x rebe100x@yakwala.fr 
 * @version 1.0.0
 * @license GPLv3 <http://www.gnu.org/licenses/gpl.txt>
 */

DEFINE("HTTP_GET","GET");
DEFINE("HTTP_POST","POST");
DEFINE("HTTP_PUT","PUT");
DEFINE("HTTP_DELETE","DELETE");

/**
 * YakwalaApi
 * Provides  methods to access Yakwala api through the oauth2.0 framework.
 */

class YakwalaApiException extends Exception {}

class YakwalaApi {
	
	/** @var String $BaseUrl The base url for the yakwala API */
	//private $BaseUrl = "http://dev.api.yakwala.fr:3002/";
	//private $BaseUrl = "http://preprod.api.yakwala.fr/";
	private $BaseUrl = "http://api.yakwala.fr";
	/** @var String $AuthUrl The url for obtaining the auth access code */
	//private $AuthUrl = "http://dev.api.yakwala.fr/api/oauth/authorize";
	//private $AuthUrl = "http://preprod.api.yakwala.fr/api/oauth/authorize";
	private $AuthUrl = "http://api.yakwala.fr/api/oauth/authorize";
	/** @var String $TokenUrl The url for obtaining an auth token */
	//private $TokenUrl = "http://dev.api.yakwala.fr:3002/api/oauth/access_token";
	//private $TokenUrl = "http://preprod.api.yakwala.fr/api/oauth/access_token";
	private $TokenUrl = "http://api.yakwala.fr/api/oauth/access_token";
	
	private $Version = '0'; 

	/** @var String $ClientID */
	private $ClientID;
	/** @var String $ClientSecret */
	private $ClientSecret;
	/** @var String $RedirectUri */
	protected $RedirectUri;
	/** @var String $AuthToken */
	private $AuthToken;
	/** @var String $ClientLanguage */
	private $ClientLanguage;
	
	/**
	 * Constructor for the API
	 * Prepares the request URL and client api params
	 * @param String $client_id
	 * @param String $client_secret
	 * @param String $version Defaults to v2, appends into the API url
	 */
	public function  __construct($client_id = false,$client_secret = false, $redirect_uri='', $version='', $language='fr'){
		//$this->BaseUrl = "{$this->BaseUrl}$version/";
		$this->BaseUrl = "{$this->BaseUrl}";
		$this->ClientID = $client_id;
		$this->ClientSecret = $client_secret;
		$this->ClientLanguage = $language;
		$this->RedirectUri = $redirect_uri;
	}
    
	public function setRedirectUri( $uri ) {
		$this->RedirectUri = $uri;
	}
	
	// Request functions
	
	/** 
	 * GetPublic
	 * Performs a request for a public resource
	 * @param String $endpoint A particular endpoint of the Yakwala API
	 * @param Array $params A set of parameters to be appended to the request, defaults to false (none)
	 */
	public function GetPublic($endpoint,$params=false){
		// Build the endpoint URL
		$url = $this->BaseUrl . trim($endpoint,"/");
		// Append the client details
		//$params['client_id'] = $this->ClientID;
		//$params['client_secret'] = $this->ClientSecret;
		$params['v'] = $this->Version;
		$params['locale'] = $this->ClientLanguage;
		// Return the result;
		return $this->GET($url,$params);
	}
	
	/** 
	 * GetPrivate
	 * Performs a request for a private resource
	 * @param String $endpoint A particular endpoint of the Yakwala API
	 * @param Array $params A set of parameters to be appended to the request, defaults to false (none)
	 * @param bool $POST whether or not to use a POST request
	 */
	public function GetPrivate($endpoint,$params=false,$POST=false){
		$url = $this->BaseUrl . trim($endpoint,"/");
		$params['access_token'] = $this->AuthToken;
		$params['v'] = $this->Version;
		$params['locale'] = $this->ClientLanguage;
		switch($POST){
				case 'POST':
					return $this->POST($url,$params);
				break;
				case 'GET':
					return $this->GET($url,$params);
				break;
				case 'DELETE':
					return $this->DELETE($url,$params);
				break;
				case 'PUT':
					return $this->PUT($url,$params);
				break;
				default:
					return $this->GET($url,$params);
			}
			
			
	}

	/**
	 * GetMulti
	 * Performs a request for up to 5 private or public resources
	 * @param Array $requests An array of arrays containing the endpoint and a set of parameters
	 * to be appended to the request, defaults to false (none)
	 * @param bool $POST whether or not to use a POST request, e.g.  for large request bodies.
	 * It does not allow you to call endpoints that mutate data.
	 */
	public function GetMulti($requests=false,$POST='GET'){
		$url = $this->BaseUrl . "multi";		
		$params = array();
		$params['access_token'] = $this->AuthToken;
		$params['v'] = $this->Version;		
		if (is_array($requests)){
			$request_queries = array();
			foreach($requests as $request) {
				$endpoint = $request['endpoint'];
				unset($request['endpoint']);
				$query = '/' . $endpoint;
					if (!empty($request)) $query .= '?' . http_build_query($request);
				$request_queries[] = $query;
			}
			$params['requests'] = implode(',', $request_queries);
		}
			switch($POST){
				case 'POST':
					return $this->POST($url,$params);
				break;
				case 'GET':
					return $this->GET($url,$params);
				break;
				case 'DELETE':
					return $this->DELETE($url,$params);
				break;
				case 'PUT':
					return $this->PUT($url,$params);
				break;
				default:
					return $this->GET($url,$params);
			}
	}
    
	public function getResponseFromJsonString($json) {
		$json = json_decode( $json );
		if ( !isset( $json->response ) ) {
			throw new YakwalaApiException( 'Invalid response' );
		}

		
		
		return $json->response;
	}
	
	/**
	 * Request
	 * Performs a cUrl request with a url generated by MakeUrl. The useragent of the request is hardcoded
	 * as the Google Chrome Browser agent
	 * @param String $url The base url to query
	 * @param Array $params The parameters to pass to the request
	 */
	private function Request($url,$params=false,$type=HTTP_GET){
		echo "<br><hr>REQUEST : ".$url;
		echo "<br>PARAMS";
		var_dump($params);
		
		// Populate data for the GET request
		if($type == HTTP_GET) $url = $this->MakeUrl($url,$params);

		// borrowed from Andy Langton: http://andylangton.co.uk/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
		} else {
			// Handle the useragent like we are Google Chrome
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.X.Y.Z Safari/525.13.');
		}
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$acceptLanguage[] = "Accept-Language:" . $this->ClientLanguage;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $acceptLanguage); 
		// Populate the data for POST
		if($type == HTTP_POST) {
			curl_setopt($ch, CURLOPT_POST, 1); 
			if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}elseif($type == HTTP_DELETE) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}elseif($type == HTTP_PUT) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		
		
		$result=curl_exec($ch);
		$info=curl_getinfo($ch);
		curl_close($ch);
		
		// echo 'info<br>';
		// var_dump($info);
		// echo 'result<br>';
		// var_dump($result);
		
		
		if ( 200 !== $info['http_code'] ) {
			throw new YakwalaApiException( 'Invalid response (http code :'.$info['http_code'].')' );
		}
		
		return $result;
	}

	/**
	 * GET
	 * Abstraction of the GET request
	 */
	private function GET($url,$params=false){
		return $this->Request($url,$params,HTTP_GET);
	}

	/**
	 * POST
	 * Abstraction of a POST request
	 */
	private function POST($url,$params=false){
		return $this->Request($url,$params,HTTP_POST);
	}

	/**
	 * PUT
	 * Abstraction of a PUT request
	 */
	private function PUT($url,$params=false){
		return $this->Request($url,$params,HTTP_PUT);
	}
	
	/**
	 * DELETE
	 * Abstraction of a DELETE request
	 */
	private function DELETE($url,$params=false){
		return $this->Request($url,$params,HTTP_DELETE);
	}
	
	// Helper Functions
	
	/**
	 * GeoLocate
	 * Leverages the google maps api to generate a lat/lng pair for a given address
	 * packaged with YakwalaApi to facilitate locality searches.
	 * @param String $addr An address string accepted by the google maps api
	 * @return array(lat, lng) || NULL
	 */
	public function GeoLocate($addr){
		$geoapi = "http://maps.googleapis.com/maps/api/geocode/json";
		$params = array("address"=>$addr,"sensor"=>"false");
		$response = $this->GET($geoapi,$params);
		$json = json_decode($response);
		if ($json->status === "ZERO_RESULTS") {
			return NULL;
		} else {
			return array($json->results[0]->geometry->location->lat,$json->results[0]->geometry->location->lng);
		}
	}
	
	/**
	 * MakeUrl
	 * Takes a base url and an array of parameters and sanitizes the data, then creates a complete
	 * url with each parameter as a GET parameter in the URL
	 * @param String $url The base URL to append the query string to (without any query data)
	 * @param Array $params The parameters to pass to the URL
	 */	
	private function MakeUrl($url,$params){
		if(!empty($params) && $params){
			foreach($params as $k=>$v) $kv[] = "$k=$v";
			$url_params = str_replace(" ","+",implode('&',$kv));
			$url = trim($url) . '?' . $url_params;
		}
		return $url;
	}
	
	// Access token functions
	
	/**
	 * SetAccessToken
	 * Basic setter function, provides an authentication token to GetPrivate requests
	 * @param String $token A Yakwala user auth_token
	 */
	public function SetAccessToken($token){
		$this->AuthToken = $token;
	}
	
	/**
	 * AuthenticationLink
	 * Returns a link to the Yakwala web authentication page.
	 * @param String $redirect The configured redirect_uri for the provided client credentials
	 */
	public function AuthenticationLink($redirect='',$response_type="code"){
		if ( 0 === strlen( $redirect ) ) {
			$redirect = $this->RedirectUri;
		}
		$params = array("client_id"=>$this->ClientID,"response_type"=>$response_type,"redirect_uri"=>$redirect);
		return $this->MakeUrl($this->AuthUrl,$params);
	}
	
	/**
	 * GetToken
	 * Performs a request to Yakwala for a user token, and returns the token, while also storing it
	 * locally for use in private requests
	 * @param $code The 'code' parameter provided by the Yakwala webauth callback redirect
	 * @param $redirect The configured redirect_uri for the provided client credentials
	 */
	public function GetToken($code,$redirect=''){
		if ( 0 === strlen( $redirect ) ) {
			// If we have to use the same URI to request a token as we did for 
			// the authorization link, why are we not storing it internally?
			$redirect = $this->RedirectUri;
		}
		$params = array("client_id"=>$this->ClientID,
						"client_secret"=>$this->ClientSecret,
						"grant_type"=>"authorization_code",
						"redirect_uri"=>$redirect,
						"code"=>$code);
		$result = $this->GET($this->TokenUrl,$params);
		
		$json = json_decode($result);
		// Petr Babicka Check if we get token
		if (property_exists($json, 'access_token')) {
			$this->SetAccessToken($json->access_token);
			return $json;
		}
		else {
			return 0;
		}
	}
}
