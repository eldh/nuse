<?php

class Boilerpipe_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://boilerpipe-web.appspot.com/extract?mode=article&output=json&url=";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
	}
	
	function getArticle($string){
		$content = $this->_makeCall(Boilerpipe_api::$baseURI.$string);
		return $content;
	}
	


}

?>