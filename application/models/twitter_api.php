<?php

class Twitter_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
		$this->load->library('twitteroauth');
		$this->config->load('twitter');
		$this->twconnection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'), $this->config->item('twitter_access_token'), $this->config->item('twitter_access_secret'));
		// $this->twcontent = $this->twconnection->get('account/verify_credentials');
	}

	static $baseURI = "https://api.twitter.com/1.1/search/tweets.json";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
	}

	function getTweets11($string) {

	}
	
	function getRecentTweets($string){
		$byt = array ( "Å", "Ä", "Ö", "å", "ä", "ö" );
		$med = array ( "A", "A", "O", "a", "a", "o" );
		$string = str_replace($byt,$med,utf8_encode(urldecode($string)));
		$content = 	$this->_makeCall('http://search.twitter.com/search.json?q='.urlencode($string).'&lang=sv&rpp=6&result_type=recent');
		return $this->fix($content);
	}
	function getPopularTweets($string){
		$byt = array ( "Å", "Ä", "Ö", "å", "ä", "ö" );
		$med = array ( "A", "A", "O", "a", "a", "o" );
		$string = str_replace($byt,$med,utf8_encode(urldecode($string)));
		$content = 	$this->_makeCall('http://search.twitter.com/search.json?q='.urlencode($string).'&lang=sv&rpp=6&result_type=popular');
		return $this->fix($content);
	}
	function getMixedTweets($string){
		$byt = array ( "Å", "Ä", "Ö", "å", "ä", "ö" );
		$med = array ( "A", "A", "O", "a", "a", "o" );
		$string = str_replace($byt,$med,utf8_encode(urldecode($string)));
		// $content = 	$this->_makeCall('http://search.twitter.com/search.json?q='.urlencode($string).'&lang=sv&rpp=10&result_type=mixed');
		// $data = array();
		// $reserves = array();
		// $counter = 0;
		// foreach($content['results'] as $tweet){
		// 	if (strpos($tweet['text'], "RT") === 0){
		// 		//Leave it
		// 	}
		// 	else if($tweet['metadata']['result_type'] == "recent"){
		// 		$reserves[] = $tweet;
		// 	}
		// 	else if ($counter > 4){
		// 		break;
		// 	}
		// 	else { //Keep it
		// 		$data[] = $tweet;
		// 		$counter++;
		// 	}
		// }
		// $content['count'] = $counter;
		// $i = 0;
  //   	while ($counter < 4){
  //   		$data[] = $reserves[$i];
  //   		$i++;
  //   		$counter++;
  //   	}
		// $content['results'] = $data;
		// $content['reserves'] = $reserves;
		// return $this->fix($content);
		$data = array(
			'q' => $string,
			'lang' => "sv",
			'result_type' => "mixed", 
			'count' => "4"
		);

		$res = $this->twconnection->get('search/tweets', $data);
		$res = $res->statuses;
		return $res;
	}
		
	function getTrends($woeid = 23424954){
		$data = array(
			'id' => $woeid,
			'exclude' => "hashtags"
		);
		$result = $this->fix($this->twconnection->get('trends/place', $data));
		return $result;
	}
	
	private function fix($in) {
		if(isset($in['results'])) {
			foreach ($in['results'] as &$tweet){
				$tweet['text'] = $this->fixUrls($tweet['text']);
			}
		}
		return $in;
	}
	
	private function StartsWith($haystack, $needle){
	    return strpos($haystack, $needle) === 0;
	}
	
	private function fixUrls($string){
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    preg_match_all($reg_exUrl, $string, $matches);
    $usedPatterns = array();
    foreach($matches[0] as $pattern){
        if(!array_key_exists($pattern, $usedPatterns)){
            $usedPatterns[$pattern]=true;
            $string = str_replace ($pattern, "<a href=".$pattern." rel='nofollow' target='_blank'>{$pattern}</a> ", $string);
        }
    }
    return $string;
	}
}
?>