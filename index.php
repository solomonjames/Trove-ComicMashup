<?php
use oauth\OAuthUtil;
require_once dirname(__FILE__) . "/config/boot.php";

echo "trove";


$config = array('public'=>'LY24jV9ttnx7cLdd',
				'private'=>'LY24jV9ttnx7cLdd',
				'urls'=>array('request_token'=>'/v2/oauth/authorize/',
								'authorize_token'=>'/v2/oauth/authorize/',
								'access_token'=>'/v2/oauth/access_token/',
								'callback'=>'127.0.1.1'),
				'sig_method'=>'HMAC-SHA1');

$oauth = new OAuthUtil($config, 'trove');

var_dump($oauth);