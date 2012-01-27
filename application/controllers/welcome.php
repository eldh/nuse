<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		$this->load->model(array('Twingly_api','Svd_api','Gnews_api','Twitter_api', "Bloggar_api"));
		$data = array(
    		'title' => 'Nuse',
    		'base' =>  base_url()
    	);

		$this->load->view('welcome_message', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */