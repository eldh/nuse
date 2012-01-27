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
		$this->load->model(array('Twingly_api','Svd_api','Gnews_api','Twitter_api', 'Bloggar_api'));
	}

	public function index()
	{
		
		$this->load->view('welcome_message', $data);
	}
	
	public function trends() {
		echo json_encode($this->Twitter_api->getTrends());
	}
	public function mixedtweets($string, $page = 1) {
		echo json_encode($this->Twitter_api->getMixedTweets($string));
	}
	public function populartweets($string, $page = 1) {
		echo json_encode($this->Twitter_api->getPopularTweets($string));
	}
	public function recenttweets($string, $page = 1) {
		echo json_encode($this->Twitter_api->getRecentTweets($string));
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
		echo json_encode($this->Bloggar_api->getTopics());
	}
	public function topics() {
		$bt = $this->Bloggar_api->getTopics();
		$gt = $this->Gnews_api->getTopics();
		$tt = $this->Twitter_api->getTrends();
		$topics = array();
		foreach ($bt as &$b){
			if (in_array($b, $tt) === true){
				$topics[] = $b;
			}
			if (in_array($b, $gt) === true){
				$topics[] = $b;
			}
		}
		foreach ($gt as &$g){
			if (in_array($g, $tt) === true){
				$topics[] = $g;
			}
		}
		$limit = 8 - sizeof($topics);
		for($j = 0; $j < $limit; $j++){
			if(!in_array($gt[$j], $topics)){
				$topics[] = $gt[$j];
			}
			if(!in_array($bt[$j], $topics)){
				$topics[] = $bt[$j];
			}
/*
			if(!in_array($tt[$j], $topics)){
				$topics[] = $tt[$j];
			}
*/
		}
		echo json_encode($topics);
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */