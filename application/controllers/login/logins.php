<?php  
 defined('BASEPATH') OR exit('No direct script access allowed');  
 class Logins extends CI_Controller { 
       
      function login()  { 
           $data['title'] = 'Login';
           $this->load->view('pages/login/logins');
      }  

      function login_validation()  {  

           $this->load->library('form_validation');  
           $this->form_validation->set_rules('email', 'email', 'required');  
           $this->form_validation->set_rules('password', 'Password', 'required');  
 
           if($this->form_validation->run()) {  
                //true  
                $username = $this->input->post('email');  
                $password = $this->input->post('password');  
                //model function  
                $this->load->model('login/login_model');  
                if($this->login_model->can_login($username, $password))  
                {  
                     $session_data = array(  
                          'email'     =>     $username  ,
                          'logged'     =>     true  
                     );  
                     $this->session->set_userdata($session_data);  
                     redirect(base_url() . 'dashboard');  
                }  
                else  
                {  
                     $this->session->set_flashdata('error', 'Invalid Username and Password');  
                     redirect(base_url() . 'home');  
                }  
           }  
           else {   
                $this->login();  
           }  
      }  


      function enter() {  
           if($this->session->userdata('username') != '')  
           {  
                echo '<h2>Welcome - '.$this->session->userdata('username').'</h2>';  
                echo '<label><a href="'.base_url

                ().'main/logout">Logout</a></label>';  
           }  
           else  
           {  
                redirect(base_url() . 'login');  
           }  
      }  
      function logout()  
      {  
           $this->session->unset_userdata('email');
           $this->session->unset_userdata('logged');  
           redirect(base_url() . 'login');  
      }  
 }  