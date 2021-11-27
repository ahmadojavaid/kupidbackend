<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'/PHPThumb/ThumbLib.inc.php';
/**
 * User class.
 * 
 * @extends CI_Controller
 */
class Language extends CI_Controller {
    public function __construct()
	{
        parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->library('form_validation');
        $this->load->helper('form');
		$this->load->model('SiteErrorLog');
		$this->load->model('admin_model');
		date_default_timezone_set('Asia/Kolkata');
		$logged_in_user = $this->session->userdata('logged_in');   
		if ($logged_in_user)
		{
			$is_admin = $this->session->userdata('is_admin');
			if(!$is_admin)
			{
				redirect('login');
			}
		}
	}
	public function index()
	{
		$data['language']=$this->admin_model->fetch_data('language');
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/language/language_list', $data);
		$this->load->view('admin/footer');
	}
	public function new_language()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Title', 'required');
		if ($this->form_validation->run() === true)
                {
		// set validation rules
		$path=APPPATH."language/".$this->input->post('name');
		if(is_dir($path))
		{
			$file=$path."/all_lang.php";
			if(file_exists($file))
			{
				$data=array(
				'name'=>$this->input->post('name'),
						'rtl'=>$this->input->post('rtl'),
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				);
				$user= $this->admin_model->insertrow('language',$data);
				if($user)
				{/*
					$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
					$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
					$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');*/
					$this->session->set_flashdata('success',lang('lbl_admin_language_success_insert'));
					redirect('/admin/language/');
				}
				else
				{
					$this->session->set_flashdata('fail',lang('lbl_admin_language_error_insert'));
					redirect('/admin/language/');
				}
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_language_error_file'));
				redirect('/admin/language/');
			}
		}
		else
				$this->session->set_flashdata('fail',lang('lbl_admin_language_error_folder'));
				redirect('/admin/language/');
		}
		$data['users']=$this->admin_model->get_all_users();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/language/language_add',$data);
		$this->load->view('admin/footer'); 
	}
	public function language_edit($id)
	{
		$data['language']=$this->admin_model->getrow('language',array('id'=>$id));
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/language/language_edit', $data);
		$this->load->view('admin/footer');
	}
	public function language_update()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('name', 'Title', 'required');
		if ($this->form_validation->run() === true)
        {
			$path=APPPATH."language\\".$this->input->post('name');
			if(is_dir($path))
			{
				$file=$path."\all_lang.php";
				if(file_exists($file))
				{
					/*$name=$this->admin_model->get_remove_language($this->input->post('id'));
					$column=$this->admin_model->remove_old_field($name,'ethnicity');
					$column=$this->admin_model->remove_old_field($name,'questions');
					$column=$this->admin_model->remove_old_field($name,'religions');*/
					$user= $this->admin_model->updaterow('language',array('id'=>$this->input->post('id')),array('name'=>$this->input->post('name'),'rtl'=>$this->input->post('rtl')));			
					if($user)
					{
						/*$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');*/
						$this->session->set_flashdata('success',lang('lbl_admin_language_success_update'));
						redirect('/admin/language/');
					}
					else
					{
						$this->session->set_flashdata('fail',lang('lbl_admin_language_error_update'));
						redirect('/admin/language/');
					}
				}
				else
				{
					$this->session->set_flashdata('fail',lang('lbl_admin_language_error_file'));
					redirect('/admin/language/');
				}
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_language_error_folder'));
				redirect('/admin/language/');
			}			
		}
	}
	public function language_delete($id)
	{	
		$name=$this->admin_model->get_remove_language($id);
		if($this->admin_model->deleterow('language',array('id'=>$id)))
		{
			$column=$this->admin_model->remove_old_field($name,'ethnicity');
			$column=$this->admin_model->remove_old_field($name,'questions');
			$column=$this->admin_model->remove_old_field($name,'religions');
			$this->session->set_flashdata('success',lang('lbl_admin_language_success_delete'));
			redirect('/admin/language/');
		}
		else
		{
			$this->session->set_flashdata('fail',lang('lbl_admin_language_error_delete'));
			redirect('/admin/language/');
		}
	}
}