<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboards_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
				$this->load->database(); 
    }

    function getTotalSales ( $year = null, $month = null, $day = null) {
        $whereGroupClause = 'group by  itemTbl.item_id';

        if ( $year && $month && $day ) {
            $whereGroupClause = "
                    where DATE_FORMAT( transactTbl.created_at, '%Y-%m-%d'  )  = ".$year."-".$month."-".$day." 
                    group by DATE_FORMAT( transactTbl.created_at, '%Y-%m-%d'  ), itemTbl.item_id "
            ;
        } else if ( $year ) {
            $whereGroupClause = "where DATE_FORMAT( transactTbl.created_at, '%Y'  )  = ".$year." group by DATE_FORMAT( transactTbl.created_at, '%Y'  ), itemTbl.item_id ";
        } else if ( $month ) {
            $whereGroupClause = "where DATE_FORMAT( transactTbl.created_at, '%m'  )  = ".$month." group by DATE_FORMAT( transactTbl.created_at, '%m'  ), itemTbl.item_id ";
        } else if ( $day ) {
            $whereGroupClause = "where DATE_FORMAT( transactTbl.created_at, '%d'  )  = ".$day." group by DATE_FORMAT( transactTbl.created_at, '%d'  ), itemTbl.item_id ";
        }

        return $this->db->query('
            SELECT 
                invTbl.invoice_id,
                itemTbl.item_name,
                count(itemTbl.item_id) itemCnt,
                itemTbl.item_price,
                ( count(itemTbl.item_id) *itemTbl.item_price  )as sales ,
                DATE_FORMAT( transactTbl.created_at, "%Y-%m-%d"  )  as transaction_date
            FROM
                invoices AS invTbl
                    LEFT JOIN
                invoice_transactions AS transactTbl ON transactTbl.invoice_id = invTbl.invoice_id
                    LEFT JOIN
                users AS userTbl ON userTbl.user_id = transactTbl.user_id
                    LEFT JOIN
                items AS itemTbl ON itemTbl.item_id = transactTbl.item_id 
            '.$whereGroupClause.'
           
        ')->result_array();
    }
}