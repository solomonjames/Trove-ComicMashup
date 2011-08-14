<?php

/**
 * 
 *
 * @author aaron
 * @version
 */
class CurlUtil
{
	private $debug = false;
	/**
	 * Posts the parameters to the provided url
	 * 
	 * @param string $url url to post the params to
	 * @param array $params List of key=>value parameters to post to the url
	 */
	public function post($url, $params, $opts=array())
	{
		$params = http_build_query($params);
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		foreach($opts as $opt=>$value)
		{
			curl_setopt($curl, $opt, $value);
		}
		
		
		$data = curl_exec($curl);
		if(curl_errno($curl))
		{
			$err = curl_error($curl);
			curl_close($curl);
			throw new Exception($err);
		}
		$page = curl_getinfo($curl);

		if($page['http_code']!=200)
		{	 
			throw new CurlException($page, $data);
		}
		curl_close($curl);
		
		return $data;
	}
	
	/**
	 * 
	 * Assigned Vars:
	 *
	 * Events:
	 *
	 * @param unknown_type $url
	 * @param unknown_type $params
	 */
	public function get($url, $params = null, $opts=array())
	{
		if(isset($params))
		{
			if(strstr($url, '?')) $url .= '&' . http_build_query($params);
			else $url .= '?' . http_build_query($params);
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		foreach($opts as $opt=>$value)
		{
			curl_setopt($curl, $opt, $value);
		}
		
		$data = curl_exec($curl);

		if(curl_errno($curl))
		{
			$err = curl_error($curl);
			curl_close($curl);
			throw new Exception($err);
		}
		curl_close($curl);
		
		return $data;
	}
	
	
	/**
	 * Builds an array out of the query string of a url
	 * name=john&id=5 becomes array('name'=>'john', 'id'=>'5')
	 * 
	 * @param string $query
	 * @return array array representation of query
	 */
	public function parseQuery($query)
	{
		$query = rawurldecode($query);
		$params = explode('&', $query);

		$paramArray = array();
		
		foreach($params as $param)
		{
			$split = explode('=', $param);
			$paramArray[$split[0]] = $split[1];
		}
		return $paramArray;
	}
}

class CurlException extends RuntimeException
{
	private $curlinfo = null;
	private $data = null;
	
	public function __construct($curlinfo, $data=null)
	{
		$this->curlinfo = $curlinfo;
		$this->data = $data;
		
		$message = "{$curlinfo['url']} [{$curlinfo['http_code']}]  [{$data}]";
		parent::__construct($message);
	}
}