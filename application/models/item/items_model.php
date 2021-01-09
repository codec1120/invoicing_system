<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Items_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
				$this->load->database(); 
    }

    function createItem ( $data ) {
        return $this->db->insert( 'items', $data );
    }

    function updateItem ( $id, $data ) {
        $this->db->where( 'item_id', $id );
        $this->db->update( 'items', $data );
    }

    function removeItem ( $id ) {
        $this->db->where( 'item_id', $id );
        $this->db->delete( 'items' );
    }

    function getItems ( $id = null, $start, $limit = 20 ) {
        $this->db->select( '*' );
        if ( $id ) {
            $this->db->where( 'item_id', $id );   
        }

        if ( $start ) {
            $this->db->limit( $limit, ( intval( $start ) + intval( $limit ) ) );
        } else {
            $this->db->limit( $limit );
        }
        
        return $this->db->get('items')->result_array();
    }

    function checkItemExist ( $data, $item_id ) {
        $this->db->where( 'item_name', $data['item_name']);

        if ( $item_id ) {
            $this->db->where( 'item_id !=', $item_id );
        }
        return $this->db->get( 'items' )->num_rows();
    }
}