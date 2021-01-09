<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('validate');
		$this->load->helper('serializerequestdata');
		$this->load->library('form_validation');
		$this->load->model('invoice/invoices_model', 'model');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	function index () {
		if ($this->session->userdata['logged'] == TRUE) {
        	$this->load->view('header');
			$this->load->view('pages/invoice/invoices', base_url());
        }
        else {
            redirect('login'); //if session is not there, redirect to login page
        } 
		
	}

	function getInvoices () {
        // Serialize Request Data
		$data = getSerializeData();

		$id 	= isset( $data['id'] ) ? $data['id']: null;
		$start 	= isset( $data['start'] ) ? $data['start']: null;
		$limit 	= isset( $data['limit'] ) ? $data['limit']: null;

		// Get items
		$list = $this->model->getInvoices( $id, $start, $limit );

		die(
			json_encode(
				array(
					'status' => true,
					'data'	=> $list ? $list: []
				)
			)
		);
    }

    function createInvoice () {
        // Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateSaveFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Save Record
		$this->model->createInvoice( $data );
	

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
						'message' => 'Successfully Created New Invoice.'
					)
				)
			);
		}
    }

    function validateSaveFieldData ( $data ) {

		$config = array(
			array(
                'field' => 'invoices',
                'rules' => 'required',
                'errors' => 'invoices is required',
			),
			array(
                'field' => 'amount_paid',
                'rules' => 'required',
                'errors' => 'amount_paid is required',
			),
			array(
                'field' => 'user_id',
                'rules' => 'required',
                'errors' => 'user_id is required',
			)
		);

		return validateData( $config , $data );
		
	}

	function validateUpdateFieldData ( $data ) {

		$config = array(
			array(
                'field' => 'invoices',
                'rules' => 'required',
                'errors' => 'invoices is required',
			),
			array(
                'field' => 'amount_paid',
                'rules' => 'required',
                'errors' => 'amount_paid is required',
			),
			array(
                'field' => 'invoice_id',
                'rules' => 'required',
                'errors' => 'invoice_id is required',
			)
		);

		return validateData( $config , $data );
		
	}

    function updateInvoice () {
        // Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateUpdateFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Update Record

		// Unset item_id
		$this->model->updateInvoice( $data );


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
						'message' => 'Successfully Update Invoice.'
					)
				)
			);
		}
    }

    function removeInvoice () {
        // Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateIdFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Update Record
		$id = $data['invoice_id'];

		// Unset item
		$this->model->removeInvoice( $id );


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
						'message' => 'Successfully removed Item.'
					)
				)
			);
		}
    }

    function validateIdFieldData (  $data ) {
		$config = array(
				array(
					'field' => 'invoice_id',
					'rules' => 'required',
					'errors' => 'invoice_id is required',
				)
		);

		// Set Fields Rule
		return validateData( $config , $data );
	}
}
