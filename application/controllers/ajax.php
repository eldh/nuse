<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		global $data;
		parent::__construct();
		$this->load->model(array('Twingly_api','Gnews_api','Twitter_api', 'Bloggar_api', 'Boilerpipe_api'));
	}

	public function index()
	{
		$this->load->view('welcome_message', $data);
	}
	
	public function getarticle() {
		echo json_encode($this->cache->model('Boilerpipe_api', 'getArticle', array($this->input->post('url')), 120000));
	}
	public function mixedtweets($string, $page = 1) {
		// echo json_encode($this->Twitter_api->getMixedTweets($string));
		echo json_encode($this->cache->model('Twitter_api', 'getMixedTweets', array($string), 12000));
	}
	public function populartweets($string, $page = 1) {
		echo json_encode($this->cache->model('Twitter_api', 'getPopularTweets', array($string), 12000));
	}
	public function recenttweets($string, $page = 1) {
		echo json_encode($this->cache->model('Twitter_api', 'getRecentTweets', array($string), 12000));
	}
	public function refresh() {
		$url = $this->input->post('url');
		echo json_encode($this->Twitter_api->refresh($url));
	}
	public function nextpage() {
		$url = $this->input->post('url');
		echo json_encode($this->Twitter_api->nextpage($url));
	}
	public function svd($string) {
		echo json_encode($this->Svd_api->getNews($string));
	}
	public function news($string) {
		echo json_encode($this->Gnews_api->getNews($string));
	}
	public function blogs($string) {
		echo json_encode($this->Bloggar_api->getBlogs($string));
	}
	public function topics1() {
		echo json_encode($this->cache->model('Bloggar_api','getTopics', array($string), 1200));
	}
	public function topics($max = 5) {
		$blacklist = array(
			'filip prpic',
			'iphone',
			'björn ranelid',
			'melodifestivalen',
			'fredrik reinfeldt',
			'lchf',
			'danny',
			'stefan löfven',
			'påsk',
			'påskafton',
			'påskdagen',
			'påskhelgen',
			'påskgodis',
			'Glad Påsk',
			'Happy Easter',
			'Göteborg',
			'påskmiddag',
			'påskägg',
			'fra',
			'jul',
			'Såg',
			'går',
			'nyår',
			'julafton',
			'juldagen',
			'facebook',
			'instagram',
			'anders borg',
			'israel',
			'välkommen',
			'Välkommen',
			'nja',
			'trevligt',
			'tackar',
			'tack',
			'visst',
			'precis',
			'såg',
			'älskar',
			'Älskar',
			'årets',
			'Årets',
			'New British Boyband',
			'Soccer Six Bolton',
			'arbetslösheten',
			'japp',
			'Japp',
			'Verkligen',
			'Har',
			'Justin Bieber',
			'Inte',
			'Men',
			'Twitter',
			'twitter',
			'och',
			'Och',
			'Precis',
			'ryssland',
			'tyskland',
			'Hur',
			'INTE',
			'gotland',
			'apple'
		);
		$blogTopics = $this->Bloggar_api->getTopics();
		// $blogTopics = $this->cache->model('Bloggar_api', 'getTopics', array(), 120); // 2h
		// $twitterTopics = $this->cache->model('Twitter_api', 'getTrends', array(), 1800); // 30 mins
		// $twitterTopics = $this->Twitter_api->getTrends();
		$topics = array();
		$j = 0;
		while(sizeof($topics) < $max){
			// if(isset($twitterTopics[0]->trends[$j]) && 
			// 	!in_array($twitterTopics[0]->trends[$j]->name, $topics) && 
			// 	!in_array($twitterTopics[0]->trends[$j]->name, $blacklist)) {
			// 		$topics[] = $twitterTopics[0]->trends[$j]->name;
			// }
			if(isset($blogTopics[$j]) && !in_array($blogTopics[$j], $topics) && !in_array($blogTopics[$j], $blacklist)) {
				$topics[] = $blogTopics[$j];
			}
			$j++;
		}
		// $topics = array('Blaj', 'struktur', 'Feminazi', 'Arbetslösheten');
		echo json_encode($topics);
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */