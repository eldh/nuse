<?php

class Twingly_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://pipes.yahoo.com/pipes/pipe.run?_id=612eda5cf101d30a42dd4576b958682f&_render=json&textinput1=";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
	}
	
	function getBlogPosts($string){
		$content = $this->_makeCall(Twingly_api::$baseURI.urlencode($string));
		return $content['value']['items'];

	}
	


}

?>