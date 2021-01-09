<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('validate');
		$this->load->helper('serializerequestdata');
		$this->load->library('form_validation');
		$this->load->model('user/users_model', 'model');
		$this->load->helper('url');
	
	} 

	public function index() {
		if (isset( $this->session->userdata['logged'] ) && $this->session->userdata['logged'] == TRUE) {
        	$this->load->view('header');
        	$this->load->view('pages/dashboard/dashboards', base_url());
        }
        else {
			$this->load->view('pages/login/logins');
        }   
	}
}
