<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
ini_set("allow_url_fopen", 1);

/*
* This class is written based entirely on the work found below
* www.techbytes.co.in/blogs/2006/01/15/consuming-rss-with-php-the-simple-way/
* All credit should be given to the original author
*/

class RSSParser
{
  // ===================//
  //Instance vars     //
  // ===================//

  /* Feed URI */
  var $feed_uri;

  /* Associative array containing all the feed items */
  var $data;

  /* Store RSS Channel Data in an array */
  var $channel_data;

  /*  Boolean variable which indicates whether an RSS feed was unavailable */
  var $feed_unavailable;

  /* Cache lifetime */
  var $cache_life;

  /* Flag to write to cache - defaulted to false*/
  var $write_cache_flag = false;

  /* Code Ignitor cache directory */
  var $cache_dir;

  // ================ //
  // Constructor     //
  // ================ //
  function RSSParser($params) {
      $this->CI =& get_instance();
      $this->cache_dir = ($this->CI->config->item('cache_path') == '') ? BASEPATH.'cache/' : $this->CI->config->item('cache_path');

      //$this->cache_dir = '/system/cache';
      $this->cache_life = $params['life'];
  
      $this->feed_uri = $params['url'];
      $this->current_feed['title'] = '';
      $this->current_feed['description'] = '';
      $this->current_feed['link'] = '';
      $this->data = array();
      $this->channel_data = array();
  
      //Attempt to parse the feed
      $this->parse();
  }

  // =============== //
  // Methods       //
  // =============== //
  function parse() {
      //Are we caching?
      if ($this->cache_life != 0)
      {

        $filename = $this->cache_dir.'rss_Parse_'.md5($this->feed_uri);

        //is there a cache file ?
        if (file_exists($filename))
        {
          //Has it expired?
          $timedif = (time() - filemtime($filename));
          if ($timedif < ( $this->cache_life * 60))
          {
              //its ok - so we can skip all the parsing and just return the cached array here
              $this->xml_to_object(implode('', file($filename)));
              return true;
          }
          //So raise the falg
          $this->write_cache_flag = true;

        } else {
          //Raise the flag to write the cache
          $this->write_cache_flag = true;
        }
    }


/*
    //Parse the document
    $rawFeed = file_get_contents($this->feed_uri);
*/
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_URL, $this->feed_uri);
		$rawFeed = curl_exec($curl);
		curl_close ($curl);
    	$this->xml_to_object($rawFeed);

    //Do we need to write the cache file?
    if ($this->write_cache_flag)
    {
        if ( ! $fp = @fopen($filename, 'wb'))
        {
          echo “ERROR”;
          log_message('error', 'Unable to write cache file: '.$cache_path);
          return;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $rawFeed);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    return true;
  }

  /* Return the feeds one at a time: when there are no more feeds return false
    * @param No of items to return from the feed
    * @return Associative array of items
  */
  function getFeed($num) {
      $c = 0;
      $return = array();
      foreach($this->data AS $item)
      {
        $return[] = $item;
        $c++;
        if($c == $num) break;
      }
      return $return;
  }

  /* Return channel data for the feed */
  function & getChannelData() {
      $flag = false;
      if(!empty($this->channel_data)) {
        return $this->channel_data;
      } else {
        return $flag;
      }
  }

  /* Were we unable to retreive the feeds ?  */
  function errorInResponse() {
    return $this->feed_unavailable;
  }
  
  function xml_to_object($feed)
  {
      $xml = new SimpleXmlElement($feed);
      
      //Assign the channel data
      $this->channel_data['title'] = $xml->channel->title;
      $this->channel_data['description'] = $xml->channel->description;
      
      //Build the item array
      foreach ($xml->channel->item as $item)
      {
        $data = array();
        $data['title'] = $item->title;
        $data['description'] = $item->description;
        $data['pubDate'] = $item->pubDate;
        $data['link'] = $item->link;
        $this->data[] = $data;
      }
      
      //return $this->data;
  }

}

?>