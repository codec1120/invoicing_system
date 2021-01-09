<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('validate');
		$this->load->helper('serializerequestdata');
		$this->load->library('form_validation');
		$this->load->model('user/users_model', 'model');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	function index () {
		if ($this->session->userdata['logged'] == TRUE) {
        	$this->load->view('header');
			$this->load->view('pages/user/users', base_url());
        }
        else {
            redirect('login'); //if session is not there, redirect to login page
        } 
		
	}

	public function createUser () {
				
		// Serialize Request Data
		$data = getSerializeData();
	
		// Validate Fields
		$this->validateSaveFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Save Record
		$emailExist = $this->model->emailCheckExist( $data['email'] );
		
		if ( $emailExist ) {
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Email already exist."
					)
				)
			);
		}

		unset( $data['isCustomer'] );

		$this->model->createUser( $data );
	

		// Verifying if transaction was completed.
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Something wen't wrong while saving data. Please contact your System Administrator."
					)
				)
			);
		} 
		else {
			$this->db->trans_commit();
			die(
				json_encode(
					array (
						'status' =>  true,
						'message' => 'Successfully Created New User.'
					)
				)
			);
		}
		

	}

	function validateSaveFieldData ( $data ) {
		
		$config = $data['isCustomer'] ? array(
			array(
                'field' => 'first_name',
                'rules' => 'required',
                'errors' => 'first_name is required',
			),
			array(
                'field' => 'last_name',
                'rules' => 'required',
                'errors' => 'last_name is required.',
			),
		)
		: array(
			array(
                'field' => 'first_name',
                'rules' => 'required',
                'errors' => 'first_name is required',
			),
			array(
                'field' => 'last_name',
                'rules' => 'required',
                'errors' => '',
			), 
			array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|email|unique',
				'errors'=> 'first_name is required.|Invalid email.',
				'table' => 'users'
			),
			array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => 'password is required.',
        	)
		);

		return validateData( $config , $data );
		
	}

	function updateUser () {
	
		// Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateIdUpdatedDataFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Update Record
		$id = $data['user_id'];

		// Unset user_id
		$this->model->updateUser( $id, $data['updatedData'] );


		// Verifying if transaction was completed.
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Something wen't wrong while saving data. Please contact your System Administrator."
					)
				)
			);
		} 
		else {

			$this->db->trans_commit();
			die(
				json_encode(
					array (
						'status' =>  true,
						'message' => 'Successfully Update User Details.'
					)
				)
			);
		}
	}

	function validateIdUpdatedDataFieldData (  $data ) {
		$config = array(
				array(
					'field' => 'user_id',
					'rules' => 'required',
					'errors' => 'user_id is required',
				),
				array(
					'field' => 'updatedData',
					'rules' => 'required',
					'errors' => 'updatedData is required',
				)
		);

		// Set Fields Rule
		return validateData( $config , $data );
	}


	function archiveUser () {
		
		// Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateIdFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Archive User
		$this->model->archiveUser( $data['user_id'] );
		

		// Verifying if transaction was completed.
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Something wen't wrong while saving data. Please contact your System Administrator."
					)
				)
			);
		} 
		else {

			$this->db->trans_commit();
			die(
				json_encode(
					array (
						'status' =>  true,
						'message' => 'Successfully Deleted User Details.'
					)
				)
			);
		}

	}

	function validateIdFieldData (  $data ) {
		$config = array(
				array(
					'field' => 'user_id',
					'rules' => 'required',
					'errors' => 'user_id is required',
				)
		);

		// Set Fields Rule
		return validateData( $config , $data );
	}

	function getUsers () {
		// Serialize Request Data
		$data = getSerializeData();

		$id 	= isset( $data['id'] ) ? $data['id']: null;
		$start 	= isset( $data['start'] ) ? $data['start']: null;
		$limit 	= isset( $data['limit'] ) ? $data['limit']: null;

		// Get Users
		$list = $this->model->getUsers( $id, $start, $limit );

		die(
			json_encode(
				array(
					'status' => true,
					'data'	=> $list ? $list: []
				)
			)
		);
	}
}
