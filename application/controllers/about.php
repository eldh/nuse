<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set("allow_url_fopen", 1);


class About extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		$this->load->library('user_agent');
		$isMobile = $this->agent->is_mobile() && !((bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad'));
		$data = array(
    		'title' => 'Nuse',
    		'base' =>  base_url(),
    		'mobile' => $isMobile
    	);
		$this->load->view('about', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */