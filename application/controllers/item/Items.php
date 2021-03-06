<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('validate');
		$this->load->helper('serializerequestdata');
		$this->load->library('form_validation');
		$this->load->model('item/items_model', 'model');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	function index () {
		if ($this->session->userdata['logged'] == TRUE) {
        	$this->load->view('header');
			$this->load->view('pages/item/items', base_url());
        }
        else {
            redirect('login'); //if session is not there, redirect to login page
        } 
	
    }
    
    function getItems () {
        // Serialize Request Data
		$data = getSerializeData();

		$id 	= isset( $data['id'] ) ? $data['id']: null;
		$start 	= isset( $data['start'] ) ? $data['start']: null;
		$limit 	= isset( $data['limit'] ) ? $data['limit']: null;

		// Get items
		$list = $this->model->getItems( $id, $start, $limit );

		die(
			json_encode(
				array(
					'status' => true,
					'data'	=> $list ? $list: []
				)
			)
		);
    }

    function createItem () {
        // Serialize Request Data
		$data = getSerializeData();

		// Validate Fields
		$this->validateSaveFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Check if Item Name Already Exist
		$itemExist = $this->model->checkItemExist( $data );

		if ( $itemExist ) {
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Item Name already exists."
					)
				)
			);
		}	

		// Save Record
		$this->model->createItem( $data );
	

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
						'message' => 'Successfully Created New Item.'
					)
				)
			);
		}
    }

    function validateSaveFieldData ( $data ) {

		$config = array(
			array(
                'field' => 'item_name',
                'rules' => 'required',
                'errors' => 'item_name is required',
			),
			array(
                'field' => 'item_price',
                'rules' => 'required',
                'errors' => 'item_price is required',
			)
		);

		return validateData( $config , $data );
		
	}

    function updateItem () {
        // Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateIdUpdatedDataFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Check if Item Name Already Exist
		$itemExist = $this->model->checkItemExist( $data['updatedData'], $data['item_id'] );

		if ( $itemExist ) {
			die(
				json_encode(
					array (
						'status' =>  false,
						'message' => "Item Name already exists."
					)
				)
			);
		}	

		// Update Record
		$id = $data['item_id'];

		// Unset item_id
		$this->model->updateItem( $id, $data['updatedData'] );


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
						'message' => 'Successfully Update Item.'
					)
				)
			);
		}
    }

    function removeItem () {
        // Serialize Request Data
		$data = getSerializeData();
		
		// Validate Fields
		$this->validateIdFieldData( $data );

		// Transaction Begin
		$this->db->trans_begin();

		// Update Record
		$id = $data['item_id'];

		// Unset item
		$this->model->removeItem( $id );


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
					'field' => 'item_id',
					'rules' => 'required',
					'errors' => 'item_id is required',
				)
		);

		// Set Fields Rule
		return validateData( $config , $data );
	}

    function validateIdUpdatedDataFieldData (  $data ) {
		$config = array(
				array(
					'field' => 'item_id',
					'rules' => 'required',
					'errors' => 'item_id is required',
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
}

