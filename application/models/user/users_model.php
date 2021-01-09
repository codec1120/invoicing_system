<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
				$this->load->database(); 
    }

    function createUser ( $data ) {
        $data['password'] = md5( $data );
        return $this->db->insert( 'users', $data );
    }

    function updateUser ( $id, $data ) {
        $this->db->where( 'user_id', $id );
        $this->db->update( 'users', $data );
    }

    function deleteUser ( $id ) {
        $this->db->where( 'user_id', $id );
        $this->db->delete( 'users' );
    }

    function archiveUser ( $id ) {
        $this->db->where( 'user_id', $id );
        $this->db->update( 'users', [ 'status' => 2 ] );
    }

    function getUsers ( $id = null, $start, $limit = 20 ) {
        $this->db->select( '*' );
        if ( $id ) {
            $this->db->where( 'user_id', $id );   
        }

        if ( $start ) {
            $this->db->limit( $limit, ( intval( $start ) + intval( $limit ) ) );
        } else {
            $this->db->limit( $limit );
        }
        
        $this->db->where_in( 'status', [0, 1]);   
        return $this->db->get('users')->result_array();
    }

    function emailCheckExist ( $email, $user_id = null) {
        if ( $user_id ) {
            $this->db->where( 'user_id !=', $user_id);
        }

        $this->db->where( 'email', $email);
        return $this->db->get( 'users' )->num_rows();
    }
}