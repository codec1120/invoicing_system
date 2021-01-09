<?php  
 class Login_model extends CI_Model  
 {  
      function can_login($username, $password)  
      {  
           $this->db->where('email', $username);  
           $this->db->where('password', md5( $password ));  
           $query = $this->db->get('users');  

           if($query->num_rows() > 0) {  
                return true;  
           }  
           else  
           {  
                return false;       
           }  
      }  
 }  