<?php

class Gnews_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://news.google.com/news?hl=sv_se&ned=sv_se&ie=UTF-8&output=rss";
		
	function getNews($string = null, $count = 7){
		$data=array();
		
		$url = Gnews_api::$baseURI;
		if ($string != null){
			$url = $url.'&q='.urlencode(utf8_encode(urldecode($string)));
		}
		$this->load->library('RSSParser', array('url' => $url, 'life' => 300));
		$content = $this->rssparser->getFeed($count);
		foreach ($content as $i => $item){
			$data[$i]['title'] = explode(' - ', $item['title'][0].'');
			preg_match('/url=(.*)/',$item['link'],$match);
			$data[$i]['url'] = $match[1];
			$data[$i]['teaser'] = strip_tags($item['description'], '<p><br><br/>');
			
		}
		return $data;

	}
	function getTopics(){
		$url = "http://news.google.com/news?hl=sv&ned=sv_se";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $url);
		$content = curl_exec($curl);
		curl_close ($curl);
		$this->load->library('html_dom');
		$this->html_dom->loadHTML($content);
		$element = $this->html_dom->find("#anchorman-two-browse-nav", 0);
		$wrapper = $element->first_child()->last_child()->children();
		$topics = array();
		foreach($wrapper as $topic){
			$a = $topic->first_child();
			$topics[] = strtolower($a->getInnerText());
		}
		return $topics;

		
	}
	


}

?>