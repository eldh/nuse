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
		echo json_encode($this->cache->model('Boilerpipe_api', 'getArticle', array($this->input->post('url')), 12000));
	}
	public function mixedtweets($string, $page = 1) {
		echo json_encode($this->cache->model('Twitter_api', 'getMixedTweets', array($string), 1200));
	}
	public function populartweets($string, $page = 1) {
		echo json_encode($this->cache->model('Twitter_api', 'getPopularTweets', array($string), 1200));
	}
	public function recenttweets($string, $page = 1) {
		echo json_encode($this->cache->model('Twitter_api', 'getRecentTweets', array($string), 1200));
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
			'nja',
			'trevligt',
			'tackar',
			'tack',
			'visst',
			'precis',
			'japp',
			'såg',
			'älskar',
			'Älskar',
			'årets',
			'Årets'
		);
		$bt = $this->cache->model('Bloggar_api', 'getTopics', array(), 1200);
		$gt = $this->cache->model('Twitter_api', 'getTrends', array(), 1200);
		$topics = array();
		$j = 0;
		while(sizeof($topics) < $max){
			if(isset($gt[0]->trends[$j]) && 
				!in_array($gt[0]->trends[$j]->name, $topics) && 
				!in_array($gt[0]->trends[$j]->name, $blacklist)) {
					$topics[] = $gt[0]->trends[$j]->name;
			}
			if(isset($bt[$j]) && !in_array($bt[$j], $topics) && !in_array($bt[$j], $blacklist)){
				$topics[] = $bt[$j];
			}
			$j++;
		}
		echo json_encode($topics);
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */