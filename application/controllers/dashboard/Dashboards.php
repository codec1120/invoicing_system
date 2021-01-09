<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('validate');
		$this->load->helper('serializerequestdata');
		$this->load->library('form_validation');
		$this->load->model('dashboard/dashboards_model', 'model');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	function index () {
		if ($this->session->userdata['logged'] == TRUE) {
        	$this->load->view('header');
        	$this->load->view('pages/dashboard/dashboards', base_url());
        }
        else {
            redirect('login'); //if session is not there, redirect to login page
        }   
    }
    
    function getSales () {

        // Serialize Request Data
        $data = getSerializeData();

        $year = isset( $data['year'] ) ? $data['year']: null;
        $month = isset( $data['month'] ) ? $data['month']: null;
        $day = isset( $data['day'] ) ? $data['day']: null;
        
        $record = $this->model->getTotalSales( $year, $month, $day );

        
		die(
			json_encode(
				array(
					'status' => true,
					'data'	=> $record ? $record: []
				)
			)
		);
    }
}
