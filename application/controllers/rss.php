<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rss extends CI_Controller {

	function __construct()
	{
		global $data;
		parent::__construct();
		$this->load->model(array('Gnews_api'));
	}

	public function index()
	{	
		$data= array(
			'title' =>"Svenska nyheter",
			'items' => $this->Gnews_api->getNews(null, 30)
		);
		$this->load->view('rss', $data);
	}	

}
