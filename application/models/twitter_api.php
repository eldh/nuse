<?php

class Twitter_api extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	static $baseURI = "http://search.twitter.com/";
	
	private function _makeCall($string) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $string);
		$content = json_decode(curl_exec($curl), true);
		curl_close ($curl);
		return $content;
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
		$content = 	$this->_makeCall('http://search.twitter.com/search.json?q='.urlencode($string).'&lang=sv&rpp=10&result_type=mixed');
		$data = array();
		$reserves = array();
		$counter = 0;
		foreach($content['results'] as $tweet){
			if (strpos($tweet['text'], "RT") === 0){
				//Leave it
			}
			else if($tweet['metadata']['result_type'] == "recent"){
				$reserves[] = $tweet;
			}
			else if ($counter > 4){
				break;
			}
			else { //Keep it
				$data[] = $tweet;
				$counter++;
			}
		}
		$content['count'] = $counter;
		$i = 0;
    	while ($counter < 4){
    		$data[] = $reserves[$i];
    		$i++;
    		$counter++;
    	}
		$content['results'] = $data;
		$content['reserves'] = $reserves;
		return $this->fix($content);
	}
	
	function refresh($url){
		$content = 	$this->_makeCall('http://search.twitter.com/search.json'.$url);
		return $this->fix($content);
	}
	function nextpage($url){
		$content = 	$this->_makeCall('http://search.twitter.com/search.json'.$url);
		return $this->fix($content);
	}
	
	function getTrends($woeid = 23424954){
		$content = 	$this->_makeCall('http://api.twitter.com/1/trends/'.$woeid.'.json?exclude=hashtags');
		$data = array();
		foreach($content[0]['trends'] as $trend){
			$data[] = strtolower($trend['name']);
		}
		return $data;
	}
	
	private function fix($in) {
		foreach ($in['results'] as &$tweet){
			$tweet['text'] = $this->fixUrls($tweet['text']);
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