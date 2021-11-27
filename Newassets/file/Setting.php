<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'/PHPThumb/ThumbLib.inc.php';
/**
 * Admin class.
 * 
 * @extends CI_Controller
 */
class Admin extends CI_Controller {
	/**
	 * __construct function.
	 * 
	 */
	public function __construct()
	{	
		parent::__construct();
		$this->load->library('session');
		$this->load->helper(array('url','form'));
		$this->load->model('SiteErrorLog');
		$this->load->model('admin_model');
		$this->load->model('testimonial_model');
		$this->load->model('blog_model');
		$this->load->model('story_model');
		$this->load->model('Slider_model');
		$this->load->model('error404_model');
		$this->load->model('comingsoon_model');
		$this->load->model('members_model');
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
	/*
	 * Load the Admin site setting page
	 */
	public function site_setting()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}
		$data['languages']=$this->fetch_language();
		$data['setting']=$this->admin_model->get_setting();
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/site_setting/site_setting',$data);
		$this->load->view('admin/footer');
	}
	/*
	 * Update site setting 
	 */
	public function update_site_setting()
	{
		$logged_in_user = $this->session->userdata('logged_in');   

		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}

		$setting=array();
		foreach($this->input->post() as $key=>$value)

			$setting[$key]=$value;

		$this->load->helper('form');
		$this->load->library('form_validation');
		//echo "<pre>";print_r($setting);die;
		if ($this->form_validation->run() === false)
        {
			$data = new stdClass();
			if(!empty($_FILES["loaderimage"]['name']))
			{
				$name = $_FILES["loaderimage"]["name"];
				$ext = end((explode(".", $name))); 
				$config['upload_path']= './Newassets/images/';        
				$config['allowed_types']= 'gif';
				$config['overwrite'] = TRUE;
				$fname="loader";
				$config['file_name'] = $fname;
				$this->load->library('upload');
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload('loaderimage'))
				{
					// On File Upload Fail
					$this->session->set_flashdata('fail',$this->upload->display_errors());
					redirect('admin/admin/site_setting');
				}else{
					// On File Upload success
					$file_data = $this->upload->data();
					$result= $this->admin_model->get_loader_images();
					$logo=$result['status'];
					$res=$this->admin_model->site_setting(array('loaderimage'=>$_FILES["loaderimage"]["name"]));
					if(file_exists(base_url("/Newassets/images/".$logo)))
							unlink(base_url("/Newassets/images/".$logo));

					$source_path = DIRECTORY_PATH.'Newassets/images/'.$file_data["file_name"];

					$target_path = DIRECTORY_PATH.'Newassets/images/'.$file_data["file_name"];

					$thumb = PhpThumbFactory::create($source_path);

					$thumb->adaptiveResize(96, 40);

					$thumb->save($target_path, $ext);

				}

			}

			if(!empty($_FILES["site_logo"]['name']))
			{

				$name = $_FILES["site_logo"]["name"];

				$ext = end((explode(".", $name))); 

				// Code for File Upload

				$config['upload_path']= './Newassets/images/';        

				$config['allowed_types']= 'png';

				$config['overwrite'] = TRUE;

				$fname="logo";

				$config['file_name'] = $fname;

				$this->load->library('upload');

				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload('site_logo'))
				{
					// On File Upload Fail
					$this->session->set_flashdata('fail',$this->upload->display_errors());
					redirect('admin/admin/site_setting');
				}else{
					// On File Upload success
					$file_data = $this->upload->data();
					$result= $this->admin_model->get_logo_images();
					$lang= $this->admin_model->get_default_language();
					$logo=$result['status'];
					if(file_exists(base_url("/Newassets/images/".$logo)))

							unlink(base_url("/Newassets/images/".$logo));

					$source_path = DIRECTORY_PATH.'Newassets/images/'.$file_data["file_name"];

					$target_path = DIRECTORY_PATH.'Newassets/images/'.$file_data["file_name"];

					$thumb = PhpThumbFactory::create($source_path);

					$thumb->adaptiveResize(96, 40);

					$thumb->save($target_path, $ext);

					$res=$this->admin_model->site_setting($setting);

					$lange= $this->admin_model->get_default_language();

					$language=$lange['status'];

					$this->session->set_userdata('site_lang',$language);

					if($result && $res)
					{
						$this->session->set_flashdata('success',lang('lbl_admin_site_setting_update_logo'));
						redirect('admin/admin/site_setting');
					}
				}
			}else{
				$language=$this->input->post('default_language');
				$lang= $this->admin_model->get_default_language();
				$res=$this->admin_model->site_setting($setting);
				$lange= $this->admin_model->get_default_language();
				$language=$lange['status'];
				$this->session->set_userdata('site_lang',$language);
				redirect('admin/admin/site_setting');
			}
		}
		$data['lang']= $this->admin_model->get_languages();
		$data['languages']=$this->fetch_language();
		$data['setting']=$this->admin_model->get_setting();
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/site_setting/site_setting',$data);
		$this->load->view('admin/footer');
	}
	public function fetch_language()
	{
		return $this->admin_model->fetch_language();
	}
	/**
	 * Search Users function .
	 */
	public function search(){
        $logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
            redirect('login');
        }
		$data['users']=$this->admin_model->get_all();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/search/search',$data);
		$this->load->view('admin/footer');
    }
	/**
	 * Fetch Users List according to search Input.
	 * @return Users List
	 */
	public function fetchusers()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$userdata="";
		$users=$this->input->post('users');
		$str=array('fname like'=>"%".$users."%",'lname like'=>"%".$users."%");
		$user=$this->admin_model->get_all($str,array('is_admin!='=>'1'));		
		$cnt=1;
		foreach($user as $row)
		{	
			$today=date('Y-m-d');
			$dob=$row['dob'];
			$diff = date_diff(date_create($dob),date_create($today));
			$age = $diff->format('%Y');
			if($cnt%4==0)
				$userdata.="</div><div class='row mt-50'>";
				$userdata.="<div class='col-sm-3 admin-search'>
				<div class='frnd-box'>";
				if(!empty($row['profile_image']))
				{
					if(file_exists(DIRECTORY_PATH."uploads/thumbnail/".$row['profile_image']))
					{ 
						$img=$row['profile_image'];
						$userdata.="<img src=".base_url("uploads/thumbnail/$img")." class='img-center full-width'/>";     
					}
					else
					{
					   $userdata.="<img src=".base_url("/assets/images/default.png")." class='img-center full-width'  />";                              
					}
				}
				else
				{
					$userdata.="<img src=".base_url("/assets/images/default.png")." class='img-center full-width'   />";
				}
				$userdata.="<div class='frnd-cntn'>"; 
				$userdata.="<h5><a href=".base_url('admin/admin/user_detail/'.$row['id']).">".$row["fname"]." ".$row["lname"]."</a></h5>";
				$userdata.="<small>".$age."  ".lang('lbl_dashboard_years_old')."</small>";
				$userdata.="</div></div></div>";
		}
		$userdata.="</div>";
		echo $userdata;
	}
	public function language()
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
						'created_date'=>date('Y-m-d H:i:s'),
						'modified_date'=>date('Y-m-d H:i:s'),
					);
					$user= $this->admin_model->insertrow('language',$data);
					if($user)
					{
						$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');
						$this->session->set_flashdata('success',lang('lbl_admin_language_success_insert'));
						redirect('/admin/admin/language');
					}
					else
					{
						$this->session->set_flashdata('fail',lang('lbl_admin_language_error_insert'));
						redirect('/admin/admin/language');
					}
				}
				else
				{
					$this->session->set_flashdata('fail',lang('lbl_admin_language_error_file'));
					redirect('/admin/admin/language');
				}
			}
			else
					$this->session->set_flashdata('fail',lang('lbl_admin_language_error_folder'));
			redirect('/admin/admin/language');
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
					$name=$this->admin_model->get_remove_language($this->input->post('id'));
					$column=$this->admin_model->remove_old_field($name,'ethnicity');
					$column=$this->admin_model->remove_old_field($name,'questions');
					$column=$this->admin_model->remove_old_field($name,'religions');
					$user= $this->admin_model->updaterow('language',array('id'=>$this->input->post('id')),array('name'=>$this->input->post('name')));			
					if($user)
					{
						$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
						$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');
						$this->session->set_flashdata('success',lang('lbl_admin_language_success_update'));
						redirect('/admin/admin/language');
					}
					else
					{
						$this->session->set_flashdata('fail',lang('lbl_admin_language_error_update'));
						redirect('/admin/admin/language');
					}
				}
				else
				{
					$this->session->set_flashdata('fail',lang('lbl_admin_language_error_file'));
					redirect('/admin/admin/language');
				}
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_language_error_folder'));
				redirect('/admin/admin/language');
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
			redirect('/admin/admin/language');
		}
		else
		{
			$this->session->set_flashdata('fail',lang('lbl_admin_language_error_delete'));
			redirect('/admin/admin/language');
		}
	}
	/*
		Display Site Error to the admin
	*/
	public function siteError()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$data['siteerrors']=$this->SiteErrorLog->getdata();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/siteerrorList', $data);
		$this->load->view('admin/footer');
	}
	/**
	 * Blocked Users List function.
	 * @access public
	 * @return block user detail
	 * Load the block_user_detail Page with  Blocked Users List
	 */
	public function block_detail()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$data['block']=$this->admin_model->block_detail();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/block/blockuser_detail', $data);
		$this->load->view('admin/footer');
	}
	/**
	 * Report Users List function.
	 * @access public
	 * @return block user detail
	 * Load the block_user_detail Page with  Blocked Users List
	 */
	public function report_detail()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$data['report']=$this->admin_model->report_detail();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/report/reportuser_detail', $data);
		$this->load->view('admin/footer');
	}
	/**
	 * Function for block a user and
	 * Redirect tblockuser_detail page
	 */
	public function block_user()
	{	
		$user_id=$this->uri->segment(4);
		$page=$this->uri->segment(5);
		if ($this->admin_model->block_user($user_id)) 
		{
			$this->session->set_flashdata('success',lang('lbl_admin_block_request_success_msg'));
			if($page=="block")
				redirect('admin/admin/block_detail');
			else if($page=="report")
				redirect('admin/admin/report_detail');
		}
	}
	/**
	 * Block Users function .
	 * 
	 * @access public
	 * @return Its Display Block User List
	 */
	public function block_users()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$data['block']=$this->admin_model->block_users();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/block/block_user', $data);
		$this->load->view('admin/footer');
	}
	/**
	 * Un Block Users function .
	 * 
	 * @access public
	 * @return void
	 */
	public function unblock_user()
	{		
		$user_id=$this->uri->segment(4);
		if ($this->admin_model->unblock_user($user_id)) 
		{
			$this->session->set_flashdata('success',lang('lbl_admin_block_users_success_msg'));
			redirect('admin/admin/block_users');	
		}
	}
	/**
	 * User Detail function .
	 * 
	 * @access public
	 * @return Perticular User Details
	 */
	public function user_detail()
	{
		$user_id=$this->uri->segment(4);
		$data['content'] = $this->admin_model->get_user($user_id);
        $data['religion'] = $this->admin_model->get_all_religion();
        $data['ethnicity'] = $this->admin_model->get_all_ethnicity();
        $data['datepreference'] = $this->admin_model->get_new_datepreference($data['content']->date_pref);
        $data['questions'] = $this->admin_model->get_all_questions();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/user_detail', $data);
		$this->load->view('admin/footer');
	}
	public function ethnicity()
	{
		$data['ethnicities']=$this->admin_model->fetch_data('ethnicity');
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/ethnicity/ethnicity_list', $data);
		$this->load->view('admin/footer');
	}
	public function new_ethnicity()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Please enter Ethnicity', 'required');
		if ($this->form_validation->run() === true)
        {
			$values=array();
			foreach($lan as $row)
			{
				$values[$row]=$this->input->post($row.'_name');
			}
			$ethnicity=array(
				'name'=>$this->input->post('name'),
				'status'=>'1',
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user= $this->admin_model->insertrow('ethnicity',$ethnicity);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_ethnicity_success_insert'));
				redirect('/admin/admin/ethnicity');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_ethnicity_error_insert'));
				redirect('/admin/admin/ethnicity');
			}
		}
		$data['users']=$this->admin_model->get_all_users();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/ethnicity/ethnicity_add',$data);
		$this->load->view('admin/footer'); 
	}
	public function ethnicity_edit($id)
	{
		$data['ethnicity']=$this->admin_model->getrow('ethnicity',array('id'=>$id));
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/ethnicity/ethnicity_edit', $data);
		$this->load->view('admin/footer');
	}
	public function ethnicity_update()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Please enter Ethnicity', 'required');
		if ($this->form_validation->run() === true)
        {		
			$ethnicity=array(
				'name'=>$this->input->post('name'),
				'status'=>'1',
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user= $this->admin_model->updaterow('ethnicity',array('id'=>$this->input->post('id')),$ethnicity);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_ethnicity_success_update'));
				redirect('/admin/admin/ethnicity');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_ethnicity_error_update'));
				redirect('/admin/admin/ethnicity');
			}
		}
		$data['ethnicity']=$this->admin_model->getrow('ethnicity',array('id'=>$this->input->post('id')));
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/ethnicity/ethnicity_edit', $data);
		$this->load->view('admin/footer');
	}
	public function ethnicity_delete($id)
	{		
		if($this->admin_model->deleterow('ethnicity',array('id'=>$id)))
		{
			$this->session->set_flashdata('success',lang('lbl_admin_ethnicity_success_delete'));
			redirect('/admin/admin/ethnicity');
		}
		else
		{
			$this->session->set_flashdata('fail',lang('lbl_admin_ethnicity_error_delete'));
			redirect('/admin/admin/ethnicity');
		}
	}
	public function religion()
	{
		$data['religions']=$this->admin_model->fetch_data('religions');
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/religion/religion_list', $data);
		$this->load->view('admin/footer');
	}
	public function new_religion()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Please Enter Religion', 'required');
		if ($this->form_validation->run() === true)
        {
			$val=array(
				'name'=>$this->input->post('name'),
				'status'=>'1',
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user= $this->admin_model->insertrow('religions',$val);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_religion_success_insert'));
				redirect('/admin/admin/religion');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_religion_error_insert'));
				redirect('/admin/admin/religion');
			}
		}
		$data['users']=$this->admin_model->get_all_users();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/religion/religion_add',$data);
		$this->load->view('admin/footer'); 
	}
	public function religion_edit($id)
	{
		$data['religion']=$this->admin_model->getrow('religions',array('id'=>$id));		
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/religion/religion_edit', $data);
		$this->load->view('admin/footer');
	}
	public function religion_update()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Please Enter Religion', 'required');
		if ($this->form_validation->run() === true)
        {
			$val=array(
				'name'=>$this->input->post('name'),
				'status'=>'1',
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user=$this->admin_model->updaterow('religions',array('id'=>$this->input->post('id')),$val);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_religion_success_update'));
				redirect('/admin/admin/religion');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_religion_error_update'));
				redirect('/admin/admin/religion');
			}
		}
		$data['religion']=$this->admin_model->getrow('religions',array('id'=>$this->input->post('id')));		
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/religion/religion_edit', $data);
		$this->load->view('admin/footer');
	}
	public function religion_delete($id)
	{
		if($this->admin_model->deleterow('religions',array('id'=>$id)))
		{
			$this->session->set_flashdata('success',lang('lbl_admin_religion_success_delete'));
			redirect('/admin/admin/religion');
		}
		else
		{
			$this->session->set_flashdata('fail',lang('lbl_admin_religion_error_delete'));
			redirect('/admin/admin/religion');
		}
	}
	public function question()
	{
		$data['religions']=$this->admin_model->fetch_data('questions');
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/question/question_list', $data);
		$this->load->view('admin/footer');
	}
	public function new_question()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Please enter question', 'required');
		if ($this->form_validation->run() === true)
        {
			$question=array(
				'name'=>$this->input->post('name'),
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user= $this->admin_model->insertrow('questions',$question);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_question_success_insert'));
				redirect('/admin/admin/question');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_question_error_insert'));
				redirect('/admin/admin/question');
			}
		}
		$data['users']=$this->admin_model->get_all_users();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/question/question_add',$data);
		$this->load->view('admin/footer'); 
	}
	public function question_edit($id)
	{
		$data['question']=$this->admin_model->getrow('questions',array('id'=>$id));
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/question/question_edit', $data);
		$this->load->view('admin/footer');
	}
	public function question_update()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('name','Please enter question', 'required');
		if ($this->form_validation->run() === true)
        {		
			$question=array(
				'name'=>$this->input->post('name'),
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
			);
			$user= $this->admin_model->updaterow('questions',array('id'=>$this->input->post('id')),$question);
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_question_success_update'));
				redirect('/admin/admin/question');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_question_error_update'));
				redirect('/admin/admin/question');
			}
		}
		$data['question']=$this->admin_model->getrow('questions',array('id'=>$this->input->post('id')));
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/question/question_edit', $data);
		$this->load->view('admin/footer');
	}
	public function question_delete($id)
	{	
		if($this->admin_model->deleterow('questions',array('id'=>$id)))
		{
			$this->session->set_flashdata('success',lang('lbl_admin_question_success_delete'));
			redirect('/admin/admin/question');
		}
		else
		{
			$this->session->set_flashdata('fail',lang('lbl_admin_question_error_delete'));
			redirect('/admin/admin/question');
		}
	}
	/**
	 * Captcha function .
	 * 
	 * @access public
	 * @return Validation status
	 */
	public function captcha_validation()
	{
		if(!empty($_POST))
		{
			try
			{	
				 $data = array(
							'secret' => GOOGLE_SECRAT_KEY,
							'response' => $this->input->post("g-recaptcha-response")
						);
				$verify = curl_init();
				curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($verify, CURLOPT_POST, true);
				curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($verify);
				 $check = json_decode($response);
				$this->form_validation->set_message('captcha_validation', lang('lbl_admin_log_in_error_ceptcha'));
				if(!$check->success)
				{
					$error_array=(array)$check;
				 	if(in_array("invalid-input-secret",$error_array["error-codes"]) or in_array("missing-input-secret",$error_array["error-codes"]))
					{
						$error=implode(",",$error_array["error-codes"]);
						throw new Exception("Admin Google Captcha Error :- ".$error);
					}
				}
				return $check->success;
			}			
			catch(Exception $e)
			{
				$this->SiteErrorLog->addData($e->getMessage());
				return false;
			}
		}
	}
	/**
	 * logout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logout() 
	{
        // create the data object
		$data = new stdClass();
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			// remove session datas
			foreach ($_SESSION as $key => $value) 
			{
				unset($_SESSION[$key]);
			}
			// user logout ok
			redirect('/admin');
		} 
		else 
		{
			// redirect him to site root
			redirect('/admin/admin');
		}
	}
	/**
	 * admod function.
	 * 
	 * @access Private
	 * @return void
	 */
	public function admod()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('chk_all', lang('lbl_admin_admod_error_all'), 'required');
		$this->form_validation->set_rules('chk_new', lang('lbl_admin_admod_error_new'), 'required');
		if ($this->form_validation->run() === true)
        {
			$user= $this->admin_model->insert_setting($this->input->post());
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_admod_update_success'));
				redirect('/admin/admin/admod');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_admod_update_error'));
				redirect('/admin/admin/admod');
			}
		}
		$data['result']=$this->admin_model->get_admod();
		$lang['languages']=$this->fetch_language();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/admod/admod',$data);
		$this->load->view('admin/footer');
	}
	 /**
	 * Notifications function.
	 * 
	 * @access Private
	 * @return Nitification List
	 */
	public function notifications()
	{
		 $logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		$data['notification']=$this->admin_model->get_notifications();
		$lang['languages']=$this->fetch_language();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/notifications/notifications',$data);
		$this->load->view('admin/footer');
	}
	 /**
	 * New Notifications function.
	 * 
	 * @access Private
	 * @return void
	 */
	public function new_notifications()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('login');
		}
		 // validators
        // load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('title', lang('lbl_admin_new_notification_title'), 'required');
		$this->form_validation->set_rules('message', lang('lbl_admin_new_notification_message'), 'required');
        $this->form_validation->set_rules('chk_status[]', lang('lbl_admin_new_notification_users'), 'required');
		if ($this->form_validation->run() === true)
        {
			$user = $this->admin_model->insert_notification($this->input->post());
			if($user)
			{
				$this->session->set_flashdata('success',lang('lbl_admin_new_notification_success'));
				redirect('/admin/admin/notifications');
			}
			else
			{
				$this->session->set_flashdata('fail',lang('lbl_admin_new_notification_error'));
				redirect('/admin/admin/notifications');
			}
		}
		$data['users']=$this->admin_model->get_all_users();
		$lang['languages']=$this->fetch_language();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/notifications/new_notifications',$data);
		$this->load->view('admin/footer'); 
	}
	/**
	 * login function.
	 * 
	 */
	public function login() 
	{
		if ($this->session->userdata('logged_in'))
        {
            redirect('admin/admin/dashboard');
        }
        // create the data object
		$data = new stdClass();
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('txtusername', lang('lbl_admin_sign_in_username'), 'trim|required|valid_email');
		$this->form_validation->set_rules('txtpassword', lang('lbl_admin_sign_in_password'), 'required');
		if(CAPTCHA_ENABLE){ 
			$this->form_validation->set_rules('login', lang('lbl_admin_log_in_error_ceptcha'), 'callback_captcha_validation');
		}
		if ($this->form_validation->run() == false) {
			// validation not ok, send validation errors to the view
			//$lang['languages']=$this->fetch_language();
			$this->load->view('admin/header');
			$this->load->view('admin/login/login');
			$this->load->view('admin/footer');
		} 
		else {
			// set variables from the form
			$email = $this->input->post('txtusername');
			$password = $this->input->post('txtpassword');
			//echo "<pre>";print_r($_POST);
			if ($this->admin_model->resolve_user_login($email, $password)) {
				$user_id = $this->admin_model->get_user_id_from_email($email);
				$user    = $this->admin_model->get_user($user_id);
				//echo "<pre>";print_r($user);
				// set session user data
				/*$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['email']     = (string)$user->email;
				$_SESSION['fname']     = (string)$user->fname;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;*/
				$this->session->set_userdata('user_id',$user->id);
				$this->session->set_userdata('email',$user->email);
				$this->session->set_userdata('fname',$user->fname);
				$this->session->set_userdata('logged_in',true);  
				$this->session->set_userdata('is_confirmed',$user->is_confirmed);
				$this->session->set_userdata('is_admin',$user->is_admin);  
				$data  = $user;
				if($this->session->userdata('logged_in'))
				{
					// echo "<pre>";
					// print_r($this->session->all_userdata());
					// die;
					redirect('admin/admin/dashboard');
				}else{
					redirect('login');
				}
			} 
			else {
				// login failed
				$data->error = lang('lbl_admin_sign_in_wrong');
				// send error to the view
				$this->load->view('admin/header');
				$this->load->view('admin/login/login', $data);
				$this->load->view('admin/footer');
			}
		}
	}
    public function dashboard()
	{
		// echo '<pre>';
		// debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		// echo '</pre>';
		// echo "<pre>";
		// print_r($this->session->userdata());
		// echo '</pre>';
		// die;
        $logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
            redirect('login');
        }
        /* create the data object
		*  fetch current DAY registration
		*  fetch current WEEK registration
		*  fetch current MONTH registration
		*/
        $user_id = $this->session->userdata['user_id'];
        $data['content'] = $this->admin_model->get_user($user_id);
		$today=$this->admin_model->get_datewise(array('created_date like '=> date('Y-m-d')."%"));
		$today_count=$today->num_rows();
		$week=$this->admin_model->get_datewise("WEEKOFYEAR(created_date)=WEEKOFYEAR(CURDATE())");
		$week_count=$week->num_rows();
		$month=$this->admin_model->get_datewise("MONTH(created_date) = MONTH(CURDATE())");
		$month_count=$month->num_rows();
		$total=$this->admin_model->get_all();
		$total_count=count($total);
		$online=$this->admin_model->get_all_users();
		$online_count=count($online);
		$male=$this->admin_model->get_all_users(array('gender'=>'male'));
		$male_count=count($male);
		$female=$this->admin_model->get_all_users(array('gender'=>'female'));
		$female_count=count($female);
		$data['today']= $today_count;
		$data['week']=  $week_count;
		$data['month']=  $month_count;
		$data['total_users']=  $total_count;
		$data['online_users']=  $online_count;
		$data['male_users']=  $male_count;
		$data['female_users']=  $female_count;
		$lang['languages']=$this->fetch_language();
	    // user logout ok
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/dashboard/dashboard', $data);
		$this->load->view('admin/footer');
    }
}