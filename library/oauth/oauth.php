<?php

require('curl.php');

/**
 *  Step 1: getLoginUrl(). 
 *  This will give you a url which you should redirect your user to. It will allow them to log in to the service provider.
 *  Otherwise, it will explode in a terrible ball of fire.
 *  Once the user logs in to the service provider, it should return the user to a page that initiates step 2.
 *  This can be handled through the oauth_callback parameter added to the list of $requestParams or through your
 *  service provider 
 *  
 *  Step 2: acceptToken(). 
 *  If everything goes ok, this will set the logged in user's <subtype>_oauth_token and <subtype>_oauth_token_secret
 *  It will also fire an authorized event and provide array('token'=>$token, 'token_secret'=>$tokenSecret);
 *  These two pieces of data need to be stored for future calls. 
 *  If they are set in the session as described above, they do not need to be injected into this utility. 
 *  Otherwise you need to manually set the data with the token and token secret function. 
 *  
 * 
 * 
 * Configuration:
 * - <b>public:</b> Public key (Consumer Key)
 * - <b>private:</b> Private key (Consumer Secret)
 * - <b>sig_method:</b> Signature hash method
 *
 * @author aaron
 * @version
 */
class OAuthUtil
{
	const version = '1.0';
	private $debug = true;
	private $subtype;
	
	private $token;
	private $tokenSecret;
	private $consumerKey;
	private $consumerSecret;
	
	private $requestTokenUrl;
	private $authorizeTokenUrl;
	private $accessTokenUrl;
	private $callback;
	
	
	public function __construct($config, $subtype='default')
	{
		if(!isset($config)) throw new Exception('The OAuth utility requires configuration entries. Please check the documentation for more information');
		if(!isset($subtype)) throw new Exception('The OAuth utility requires a subtype. please construct it using gfw::util(\'oauth:<subtype>\')');
		
		$this->subtype = $subtype;
		
		//public/private key config
		if(!isset($config['public'])) throw new Exception('The OAuth utility requires a config value of public. This will contain your consumer key');
		$this->consumerKey = $config['public'];
		
		if(!isset($config['private'])) throw new Exception('The OAuth utility requires a config value of private. This will contain your consumer secret');
		$this->consumerSecret = $config['private'];
		
		//url config
		$this->requestTokenUrl = $config['urls']['request_token'];
		$this->authorizeTokenUrl = $config['urls']['authorize_token'];
		$this->accessTokenUrl = $config['urls']['access_token'];
		
		$this->callback = $config['urls']['callback'];
		
		//signature hash method config
		if(!isset($config['public'])) throw new Exception('The OAuth utility requires a signature method. Currently supported is HMAC-SHA1');
		if($config['sig_method'] !== 'HMAC-SHA1') throw new Exception('Currently, only HMAC-SHA1 is supported for the oauth signature method');
		else $this->sigMethod = $config['sig_method'];
		
		//try to find existing tokens, set them on success, null otherwise
		if(isset($_SESSION['user']["{$subtype}_oauth_token"])) {
			$this->token = $_SESSION['user']["{$subtype}_oauth_token"];
		} else {
			$this->token = null;
		}
		
		if(isset($_SESSION['user']["{$subtype}_oauth_token_secret"])) {
			$this->tokenSecret = $_SESSION['user']["{$subtype}_oauth_token_secret"];
		} else {
			$this->tokenSecret =  null;
		}
	}
	
	/**
	 * Get/Set token secret currently used in this utility
	 */	
	public function tokenSecret($newSecret = null)
	{
		if(isset($newSecret)) $this->tokenSecret = $newSecret;
		return $this->tokenSecret;
	}

	/**
	 * Get/Set token currently used in this utility
	 */
	public function token($newToken = null)
	{
		if(isset($newToken)) $this->token = $newToken;
		return $this->token;
	}
	
	/**
	 * First step of oauth, contact the service provider and send the user to their page.
	 * 
	 * No redirections are handled here, the result is the final url string.
	 * 
	 * @todo change the string to an array and parse correctly
	 * @param string $requestParams all additional parameters that should be set when retrieving a "request token"
	 * 
	 * @todo change the string to an array and parse correctly
	 * @param string $authorizeParams all additional parameters that should be set when directing the user to the service provider
	 * 
	 * @return string Url that the user should be redirected to.
	 */
	public function buildLoginURL($requestParams=array(), $authorizeParams=array())
	{
		//Build post request with any additional parameters
		if(!isset($requestParams['oauth_callback'])) $requestParams['oauth_callback'] = $this->callback;
		
		$params = $this->buildRequest('POST', $this->requestTokenUrl, $requestParams); 

		//Request an unauthorized token
		$data = CurlUtil::post($this->requestTokenUrl, $params, array(CURLOPT_SSL_VERIFYPEER=>false));
		
		//The standard response is similar to a get string.
		//This array is first parsed into an array
		$data = CurlUtil::parseQuery($data);
		
		$requestToken = $data['oauth_token'];
		
		//From the array we pull the token secret. This is used in authorizing a token (step 2)
		$_SESSION['user']["{$this->subtype}_oauth_request_token_secret"] =  $data['oauth_token_secret'];
		
		//The final url is returned here, containing the configured authroizeTokenUrl and the newly
		//generated authorization token.
		if(!empty($authorizeParams))
		{
			$authorizeParams = http_build_query($authorizeParams);
			return $this->authorizeTokenUrl . "?oauth_token={$requestToken}&{$authorizeParams}";
		}
		
		return $this->authorizeTokenUrl . "?oauth_token={$requestToken}";
		
	}
	
	/**
	 * When delivered an authorized token from the service provider, this exchanges it for an access token
	 *
	 */
	public function acceptToken($params = array())
	{
		//First we retrieve all the data we can to build the request
		//From the GET variable we pull the verifier string (for oauth 1.0a) and the token
		//From the session we pull the token secret, retrieved in step one.
		
		//verifier was added in oauth 1.0a
		if(isset($_GET['oauth_verifier']) && !isset($params['oauth_verifier']))
		{
			$params['oauth_verifier'] = $_GET['oauth_verifier'];
		}
		
		$this->token($_GET['oauth_token']);
		//if(isset($_GET['oauth_token_secret'])) $this->tokenSecret($_GET['oauth_token_secret']);
		//else
		//{
			$secret = $_SESSION['user']["{$this->subtype}_oauth_request_token_secret"];
			if($secret) $this->tokenSecret($secret);
		//}	
		 
		//build the request from the provided parameters
		$params = $this->buildRequest('POST', $this->accessTokenUrl, $params);
		
		//request an access token using the built request
		$data = CurlUtil::post($this->accessTokenUrl, $params, array(CURLOPT_SSL_VERIFYPEER=>false));
		
		//the response is similar to the query section of a url, this parses it into an array
		$data = CurlUtil::parseQuery($data);
		
		$token = $data['oauth_token'];
		$tokenSecret = $data['oauth_token_secret'];
		
		//assign the token information to the currently logged in user
		$this->token = $token;
		$_SESSION["{$this->subtype}_oauth_token"] = $token;
			
		$this->token_secret = $tokenSecret;
		$_SESSION["{$this->subtype}_oauth_token_secret"] = $tokenSecret;
	}
	
	/**
	 * Builds the request parameters and adds the signature.
	 *
	 * @param string $method HTTP method, GET, POST, DELETE etc.
	 * @param string $url destination url
	 * @param array $params any additional parameters that are needed
	 * @return array $parmas with the oauth data injected
	 */
	public function buildRequest($method, $url, $params = array())
	{
		//$params['oauth_realm'] = 'http://photos.example.net/photos';
		$params['oauth_consumer_key'] = $this->consumerKey;
		if(isset($this->token)) $params['oauth_token'] = $this->token;
		
		$params['oauth_nonce'] = $this->nonce();
		$params['oauth_timestamp'] = time();
		$params['oauth_signature_method'] = $this->sigMethod;
		$params['oauth_version'] = self::version;
		$params['realm'] = 'photos';
		$params['oauth_signature'] = $this->buildSignature($method, $url, $params);
		return $params;
	}
	
	/**
	 * Builds the signature hash
	 *
	 * @param string $type
	 * @param string $url
	 * @param array $params
	 * @return
	 */
	public function buildSignature($type, $url, $params)
	{
		ksort($params);
		$query = http_build_query($params);

		//build un-hashed signature
		$signature = "{$this->clean($type)}&{$this->clean($url)}&{$this->clean($query)}"; //eg. GET&http%3A%2F%2Fphotos.example.net%2F

		if($params['oauth_signature_method'] == 'HMAC-SHA1')
		{
			$hmac_key = "{$this->clean($this->consumerSecret)}&";
			if(isset($this->tokenSecret)) $hmac_key .= "{$this->clean($this->tokenSecret)}";
			
			return base64_encode(hash_hmac('sha1', $signature, $hmac_key, true));			
		}
		else throw new Exception('Currently unsupported signature method');
	}
	
	/**
	 * OAuth uses a different url encode scheme than php, so this function ensures compliance
	 *
	 * @param string $data
	 * @return 
	 */
	private function clean($data)
	{
		$data = utf8_encode($data);
		$data = rawurlencode($data);
		//cheating and un-doing the non-rfc compliant encoding
		//TODO: Do this the right way
		return str_replace('+',' ', str_replace('%7E', '~', $data));
	}
	
	/**
	 * Generates unique data between requests
	 *
	 * @return
	 */
	private function nonce()
	{
		return sha1(mt_rand() . microtime());
	}
}