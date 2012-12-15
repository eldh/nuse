<?php

class Bloggar_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://feeds.feedburner.com/bloggar-aktuellt?format=xml";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
	}
	function getBlogs($string){
		$data=array();
		$this->load->library('RSSParser', array('url' => 'http://www.twingly.com/search.rss?approved=True&lang=sv&tspan=w&q='.urlencode(utf8_encode(urldecode($string))), 'life' => 300));
		$content = $this->rssparser->getFeed(7);
		foreach ($content as $i => $item){
			$data[$i]['title'] = (string) $item['title'][0];
			$data[$i]['url'] = (string) $item['link'][0];
			$name = (string) $item['link'][0];
			$match = parse_url($name);
			$data[$i]['name'] = $match['host'];
			$data[$i]['date'] = (string) $item['pubDate'][0];
			
		}
		return $data;

	}

	function getTopics(){
		$url = "http://feeds.feedburner.com/knuff-ord";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $url);
		$content = curl_exec($curl);
		curl_close ($curl);
		$doc = new DOMDocument();
		$doc->loadXML($content);
		$rawtopics = $doc->getElementsByTagName('title');
		for ($i=1; $i < $rawtopics->length; $i++){
			$topics[] = strtolower($rawtopics->item($i)->nodeValue);
		}
		return $topics;		
	}
	


}

?>