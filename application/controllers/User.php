<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
        $this->load->library('form_validation');
        $this->load->helper('form');
		$this->load->model('user_model');
		$this->load->model('admin_model');
        $this->load->library('table');
		$this->load->model('Common_model');
        date_default_timezone_set('Asia/Kolkata'); 
		$data['header']=$this->admin_model->get_setting();
		//echo "<pre>";print_r($data);die;
		$is_comingsoon=$data['header'][18]['status'];
		$method=$this->router->fetch_method();
		if($is_comingsoon==1 && $method!='comingsoon'){
			redirect('user/comingsoon');
		}
	}
    public function check_email()
    {
       $email=$this->input->post("username");
        if(!empty($email))
        {
            $id=$this->user_model->get_user_id_from_email($email);                        
		    if(!empty($id))
            {
                $token=md5(uniqid(rand(), true));
                if($this->user_model->set_token($token,$id))
                {                    
					$link="For Reset Your Password.<a href='".base_url()."user/reset_pass/".$token."/".$id."'>Click Here</a>";
					$this->email->from('info@themes.potenzaglobalsolutions.com', 'Cupid Love');
					$this->email->to($email);
					$this->email->subject('Reset Password');
					$this->email->message($link);
					$this->email->set_mailtype('html');
					if($this->email->send()){
                        $this->session->set_flashdata('success',lang('lbl_forgot_password_mail_success'));                                                                      
                    }
                    else
                        $this->session->set_flashdata('fail',lang('lbl_forgot_password_error_mail'));
					    $this->common_msg();				   
                }
            }
            else
            {
               $this->session->set_flashdata('fail',lang('lbl_forgot_password_error_mail'));
               redirect("/user/forgot_pass");
            } 
        }
		else
		{
		   $this->session->set_flashdata('fail',lang('lbl_forgot_password_error_mail'));
		   redirect("/user/forgot_pass");
		} 
    }
	//Reset Password Function
    public function reset_pass($token,$id)
    {
    	
	   $user=$this->user_model->get_user_where_row(array("pass_token"=>$token,"id"=>$id));

        if(!empty($user))
        {
            if($user->id==$id&&$user->pass_token==$token)
            {
				//$value['header']=$this->admin_model->get_setting();
				//$this->load->view('header',$value);

				$this->load->view("forgot_password/reset_pass",array("id"=>$id,"token"=>$token,'email'=>$user->email));   
				//$this->load->view('footer');  				
            }
            else
            {
                $this->session->set_flashdata('fail',lang('lbl_common_unauthorized'));
                $this->common_msg();
            }            
        }
        else
        {
            $this->session->set_flashdata('fail',lang('lbl_common_link_expired'));						      
			$this->common_msg();
        }           
    }
	//Change Password Function
    public function change_pass()
    {
    	$this->load->library('form_validation'); 
		$this->form_validation->set_rules('reset_password', lang('lbl_register_password'), 'trim|required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', lang('lbl_register_confirm_password'), 'trim|required|matches[reset_password]');
		if ($this->form_validation->run() === false) 
		{
	        $id=$this->input->post("id");
	        $token=$this->input->post("token");
			$this->reset_pass($token,$id);
	    }
	    else{
	    	$id=$this->input->post("id");
	        $token=$this->input->post("token");
	        $pass=$this->input->post("reset_password");
	        $cpass=$this->input->post("confirm_password");
	        if($pass==$cpass)
	        {
	            if($this->user_model->update_password(array("id"=>$id),$pass))
	            {
	                $this->session->set_flashdata('success',lang('lbl_log_in_success') ); 
	                $this->user_model->unset_token($id);    
	                redirect('user/reset_pass/'.$token.'/'.$id);            
	            }
	            else {
	                $this->session->set_flashdata('fail',lang('lbl_log_in_error'));
	                redirect('login'); 
	            }
	        }else{
	            $this->session->set_flashdata('fail','Please enter same password');
	        }
	   	}
    }	
	//After the completing every reset password function this function message is displayed
    public function common_msg()
    {
        //$value['header']=$this->admin_model->get_setting();
		//$this->load->view('header',$value);
		$this->load->view("forgot_password/common_msg");
		//$this->load->view('footer');  
    }
	
 }