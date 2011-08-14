<?php
class Trove {
	
	private static $oauth;
	
	function __construct() {
		$config = array('public'=>'LY24jV9ttnx7cLdd',
					'private'=>'rddmhEuFYzrWDaym',
					'urls'=>array('request_token'=>'https://api.yourtrove.com/v2/oauth/request_token/',
									'authorize_token'=>'https://api.yourtrove.com/v2/oauth/authorize/',
									'access_token'=>'https://api.yourtrove.com/v2/oauth/access_token/',
									'callback'=>'127.0.1.1'),
					'sig_method'=>'HMAC-SHA1');

		self::$oauth = new OAuthUtil($config, 'trove');
		
		self::$oauth->token("d7yCY9Ddy5XfakUj");
		self::$oauth->tokenSecret("B7teRsxAXWJQrSsV");
	}
	
	function post($url, $params = array()) {
		$params = self::$oauth->buildRequest("POST", $url, $params);
		return CurlUtil::post($url, $params, array(CURLOPT_SSL_VERIFYPEER=>false));
	}
}