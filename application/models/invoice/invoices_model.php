<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoices_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
				$this->load->database(); 
    }

    function createInvoice ( $data ) { 

        // create Invoice
        $this->db->insert( 'invoices', [ 'amount_paid' => $data['amount_paid'] ]);
        $invoice_id = $this->db->insert_id();

        // Create Transaction
        foreach ( $data['invoices'] as $key => $value) {
            $value['invoice_id'] = $invoice_id;
            $this->db->insert( 'invoice_transactions', $value );
        }
       
    }

    function updateInvoice ( $data ) {
        // Update invoice amount_paid
        $this->db->where( 'invoice_id', $data['invoice_id'] );
        $this->db->update( 'invoices',  [ 'amount_paid' => $data['amount_paid'] ] );

        // Delete Invoice Transaction
        $this->db->where( 'invoice_id', $data['invoice_id'] );
        $this->db->delete( 'invoice_transactions' );

        // Create new Invoice Transaction
        foreach ( $data['invoices'] as $key => $value) {
            $value['invoice_id'] = $data['invoice_id'];
            $this->db->insert( 'invoice_transactions', $value );
        }
    }

    function removeInvoice ( $id ) {
        $this->db->where( 'invoice_id', $id );
        $this->db->delete( 'invoices' );

        $this->db->where( 'invoice_id', $id );
        $this->db->delete( 'invoice_transactions' );
    }

    function getInvoices ( $id = null, $start, $limit = 20 ) {
        $whereClause = '';

        if (  $id ) {
            $whereClause = 'invTbl.invoice_id like "%'.$id.'%" ';
        }
        
       return $this->db->query('
            SELECT 
                invTbl.invoice_id,
                amount_paid,
                CONCAT( userTbl.first_name, " ",  userTbl.last_name) AS customer,
                sum( item_price) as total,
                userTbl.user_id,
                group_concat( transactTbl.item_id)as selectedItems,
                group_concat( itemTbl.item_name)as selectedItemName,
                group_concat( itemTbl.item_price)as selectedItemsPrice,
                transactTbl.transaction_id,
                DATE_FORMAT( invTbl.created_at, "%Y-%m-%d" ) transaction_date
            FROM
                invoices AS invTbl
                    LEFT JOIN
                invoice_transactions AS transactTbl ON transactTbl.invoice_id = invTbl.invoice_id
                    LEFT JOIN
                users AS userTbl ON userTbl.user_id = transactTbl.user_id
                    LEFT JOIN
                items AS itemTbl ON itemTbl.item_id = transactTbl.item_id 
            '.$whereClause.'
            group by invTbl.invoice_id, userTbl.user_id
        ')->result_array();
    }
}