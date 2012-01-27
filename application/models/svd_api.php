<?php

class Svd_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://www.svd.se/search.do?sort=date&output=json&q=";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
	}
	
	function getNews($string){
		$content = $this->_makeCall(Svd_api::$baseURI.$string);
		return $content['SvDSearch']['results']['articles'];
	}
	


}

?>