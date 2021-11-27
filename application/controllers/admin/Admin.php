<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'/PHPThumb/ThumbLib.inc.php';

/*Crop Avtar Class */
class CropAvatar {
	private $src;
	private $data;
	private $dst;
	private $type;
	private $extension;
	private $msg;
	private $upload_path;
  
	function __construct($src, $data, $file,$upload_path) {
	  $this->setUploadPath($upload_path);
	  $this -> setSrc($src);
	  $this -> setData($data);
	  $this -> setFile($file);
	  $this -> crop($this -> src, $this -> dst, $this -> data);
	}
   private function setUploadPath($upload_path){
		$this -> upload_path = $upload_path;
   }
	private function setSrc($src) {
	  if (!empty($src)) {
		$type = exif_imagetype($src);
  
		if ($type) {
		  $this -> src = $src;
		  $this -> type = $type;
		  $this -> extension = image_type_to_extension($type);
		  $this -> setDst();
		}
	  }
	}
  
	private function setData($data) {
	  if (!empty($data)) {
		$this -> data = json_decode(stripslashes($data));
	  }
	}
  
	private function setFile($file) {
	  $errorCode = $file['error'];
  
	  if ($errorCode === UPLOAD_ERR_OK) {
		$type = exif_imagetype($file['tmp_name']);
  
		if ($type) {
		  $extension = image_type_to_extension($type);
		  //$src = FCPATH .'assets/images/student/avtar/' . date('YmdHis') . '.original' . $extension;
		  $src= $this -> upload_path . date('YmdHis') . '.original' . $extension;
		  if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
  
			if (file_exists($src)) {
			  unlink($src);
			}
  
			$result = move_uploaded_file($file['tmp_name'], $src);
  
			if ($result) {
			  $this -> src = $src;
			  $this -> type = $type;
			  $this -> extension = $extension;
			  $this -> setDst();
			} else {
			   $this -> msg = 'Failed to save file';
			}
		  } else {
			$this -> msg = 'Please upload image with the following types: JPG, PNG, GIF';
		  }
		} else {
		  $this -> msg = 'Please upload image file';
		}
	  } else {
		$this -> msg = $this -> codeToMessage($errorCode);
	  }
	}
  
	private function setDst() {
	  //$this -> dst = FCPATH .'assets/images/student/avtar/' . date('YmdHis') . '.png';
	  $this->dst= $this -> upload_path. date('YmdHis') . '.png';
	}
  
	private function crop($src, $dst, $data) {
	  if (!empty($src) && !empty($dst) && !empty($data)) {
		switch ($this -> type) {
		  case IMAGETYPE_GIF:
			$src_img = imagecreatefromgif($src);
			break;
  
		  case IMAGETYPE_JPEG:
			$src_img = imagecreatefromjpeg($src);
			break;
  
		  case IMAGETYPE_PNG:
			$src_img = imagecreatefrompng($src);
			break;
		}
  
		if (!$src_img) {
		  $this -> msg = "Failed to read the image file";
		  return;
		}
  
		$size = getimagesize($src);
		$size_w = $size[0]; // natural width
		$size_h = $size[1]; // natural height
  
		$src_img_w = $size_w;
		$src_img_h = $size_h;
  
		$degrees = $data -> rotate;
  
		// Rotate the source image
		if (is_numeric($degrees) && $degrees != 0) {
		  // PHP's degrees is opposite to CSS's degrees
		  $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
  
		  imagedestroy($src_img);
		  $src_img = $new_img;
  
		  $deg = abs($degrees) % 180;
		  $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
  
		  $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
		  $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
  
		  // Fix rotated image miss 1px issue when degrees < 0
		  $src_img_w -= 1;
		  $src_img_h -= 1;
		}
  
		$tmp_img_w = $data -> width;
		$tmp_img_h = $data -> height;
		$dst_img_w = 206;
		$dst_img_h = 206;
  
		$src_x = $data -> x;
		$src_y = $data -> y;
  
		if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
		  $src_x = $src_w = $dst_x = $dst_w = 0;
		} else if ($src_x <= 0) {
		  $dst_x = -$src_x;
		  $src_x = 0;
		  $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
		} else if ($src_x <= $src_img_w) {
		  $dst_x = 0;
		  $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
		}
  
		if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
		  $src_y = $src_h = $dst_y = $dst_h = 0;
		} else if ($src_y <= 0) {
		  $dst_y = -$src_y;
		  $src_y = 0;
		  $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
		} else if ($src_y <= $src_img_h) {
		  $dst_y = 0;
		  $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
		}
  
		// Scale to destination position and size
		$ratio = $tmp_img_w / $dst_img_w;
		$dst_x /= $ratio;
		$dst_y /= $ratio;
		$dst_w /= $ratio;
		$dst_h /= $ratio;
  
		$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
  
		// Add transparent background to destination image
		imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
		imagesavealpha($dst_img, true);
  
		$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
  
		if ($result) {
		  if (!imagepng($dst_img, $dst)) {
			$this -> msg = "Failed to save the cropped image file";
		  }
		} else {
		  $this -> msg = "Failed to crop the image file";
		}
  
		imagedestroy($src_img);
		imagedestroy($dst_img);
	  }
	}
  
	private function codeToMessage($code) {
	  $errors = array(
		UPLOAD_ERR_INI_SIZE =>'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		UPLOAD_ERR_FORM_SIZE =>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		UPLOAD_ERR_PARTIAL =>'The uploaded file was only partially uploaded',
		UPLOAD_ERR_NO_FILE =>'No file was uploaded',
		UPLOAD_ERR_NO_TMP_DIR =>'Missing a temporary folder',
		UPLOAD_ERR_CANT_WRITE =>'Failed to write file to disk',
		UPLOAD_ERR_EXTENSION =>'File upload stopped by extension',
	  );
  
	  if (array_key_exists($code, $errors)) {
		return $errors[$code];
	  }
  
	  return 'Unknown upload error';
	}
  
	public function getResult() {
	  return !empty($this -> data) ? $this -> dst : $this -> src;
	}
  
	public function getMsg() {
	  return $this -> msg;
	}
  }
  /* Ends */
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
						'rtl'=>$this->input->post('rtl'),
						'created_date'=>date('Y-m-d H:i:s'),
						'modified_date'=>date('Y-m-d H:i:s'),
					);
					$user= $this->admin_model->insertrow('language',$data);
					if($user)
					{
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');
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
					print_r($_POST);
					die;
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
					//$name=$this->admin_model->get_remove_language($this->input->post('id'));
					//$column=$this->admin_model->remove_old_field($name,'ethnicity');
					//$column=$this->admin_model->remove_old_field($name,'questions');
					//$column=$this->admin_model->remove_old_field($name,'religions');
					$data=array('name'=>$this->input->post('name'),'rtl'=>$this->input->post('rtl'));
					echo("<pre>");
					print_r($data);
					die;
					$user= $this->admin_model->updaterow('language',array('id'=>$this->input->post('id')),$data) ;			
					if($user)
					{
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'ethnicity');
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'questions');
						//$column=$this->admin_model->add_new_field($this->input->post('name'),'religions');
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
		$data['gallery'] = $this->admin_model->get_gallery_images(array("user_id "=>$user_id));
        $data['religion'] = $this->admin_model->get_all_religion();
        $data['ethnicity'] = $this->admin_model->get_all_ethnicity();
        $data['datepreference'] = $this->admin_model->get_new_datepreference($data['content']->date_pref);
        $data['questions'] = $this->admin_model->get_all_questions();
	    $data['googleapiskey'] = $this->admin_model->get_googleapiskey();
        //$data['googleapis']='https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_KEY.'&libraries=places&callback=initialize';
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/user_detail', $data);
		$this->load->view('admin/footer');
	}
	public function update_user(){
		//echo "<pre>";
		//print_r($_POST);
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('gender', lang('lbl_register_gender'), 'required');
		$this->form_validation->set_rules('day', lang('lbl_register_birthdate'), 'required|max_length[2]|callback_dob_validation');
		$this->form_validation->set_rules('month', lang('lbl_register_birthdate'), 'required|max_length[2]');
		$this->form_validation->set_rules('year', lang('lbl_register_birthdate'), 'required|max_length[4]');
		$this->form_validation->set_rules('about', lang('lbl_register_about'), 'trim|required|max_length[500]');
		$this->form_validation->set_rules('ethnicity', lang('lbl_register_ehtnticity'), 'required');
		$this->form_validation->set_rules('religion', lang('lbl_register_religion'), 'required');		
		$this->form_validation->set_rules('height', lang('lbl_register_height'), 'trim|required');				
		$this->form_validation->set_rules('kids', lang('lbl_register_kids'), 'trim|required');
		$this->form_validation->set_rules('address', lang('lbl_register_address'), 'trim|required');
		$this->form_validation->set_message('dob_validation', lang('lbl_profile_error_age'));
		$user_id = $this->input->post('user_id');
		if ($this->form_validation->run() === true) 
		{
			$datepref=$this->input->post('datepref');
			$datepref=str_replace(',datepref', '', $datepref);
			$birthdate=$this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day');
			$address = $_POST['address'];            
            $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&address='.urlencode($address).'&sensor=true');
			// Convert the JSON to an array
			$geo = json_decode($geo, true);
			if ($geo['status'] == 'OK') 
			{
			  // Get Lat & Long
				$latitude = $geo['results'][0]['geometry']['location']['lat'];
				$longitude = $geo['results'][0]['geometry']['location']['lng'];
			} 
			if(!empty($_POST["ethnicity"]))
			{
				$ethnicity=$_POST["ethnicity"];
				$ethnicities=implode(",",$ethnicity);
			}else{
				$ethnicity=0;
				$ethnicities=implode(",",$ethnicity);
			}
			if(!empty($_POST["religion"]))
			{
				$religion=$_POST["religion"];
				$religions=implode(",",$religion);
			}else{
				$religion=0;
				$religions=implode(",",$religion);
			}	
			$data=array(
				"fname"=>$this->input->post('fname'),
				"lname"=>$this->input->post('lname'),
				"dob"=>$birthdate,
				"about"=>$this->input->post('about'),
				"education"=>$this->input->post('education'),
				"profession"=>$this->input->post('profession'),
				"address"=>$this->input->post('address'),
				"min_age_pref"=>$this->input->post('age-min'),
				"max_age_pref"=>$this->input->post('age-max'),
				"max_dist_pref"=>$this->input->post('dist-max'),
				"religion"=>$this->input->post('religion'),
				"min_dist_pref"=>$this->input->post('dist-min'),
				"ethnicity"=>$this->input->post('ethnicity'),
				"que_id"=>$this->input->post('question'),
				"gender"=>$this->input->post('gender'),
				"gender_pref"=>$this->input->post('gender_pref'),
				"date_pref"=>$datepref,
				"que_ans"=>$this->input->post('question_ans'),
				"access_location"=>$this->input->post('access_loc'),
				'location_lat'=>$latitude,
				'location_long'=>$longitude,	
				'religion_pref'=>$religions,
				'ethnicity_pref'=>$ethnicities,
			);
			
			$condition=array("id"=>$user_id);
			if($this->admin_model->updaterow("users",$condition,$data)){
				
				//echo $this->db->last_query();
				redirect("admin/admin/user_detail/".$user_id);
			}
			//print_r($data);
		}
		else
		{
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
		
		/*$logged_in_user = $this->session->userdata('logged_in');   
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
		$this->load->view('admin/footer');*/
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
	//Date Of Birth Validation
	public function dob_validation()
	{
		$day=$this->input->post('day');
		$month=$this->input->post('month');
		$year=$this->input->post('year');
		$dat=date_create($day."-".$month."-".$year);
		$curr=date_create(date('d-m-Y'));
		$diff=date_diff($dat,$curr);
		$yer=$diff->y;
		if($yer < 15 )
		{
			return false;
		}
		return true;
	}
	public function sampledata(){
        $logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
            redirect('login');
        }
        $data=array(
        	"sampledatas"=>$this->admin_model->get_all(array(),array("sampledata"=>"1")),
        	"sampledata"=>$this->admin_model->get_sampledata(),
        );
		$lang['languages']=$this->fetch_language();
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar',$lang);
	    $this->load->view('admin/sampledata', $data);
		$this->load->view('admin/footer');
	}
	public function updateLocation(){
		$logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
			redirect('login');
        }
        // create the data object
		$lat=$this->input->post('lat');
		$lon=$this->input->post('lon');
		$user_id=$this->input->post('user_id');
		$update_array=array(
			'location_lat'=>$lat,
			'location_long'=>$lon,
		);
		$user=$this->user_model->update_profile(array('id'=>$user_id),$update_array);
	}
	public function sampledata_edit(){
		$logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
			redirect('login');
        }
        if(!empty($this->input->post("sampledataenable"))){
        	$this->admin_model->updaterow("site_setting",array("mode"=>"sample_data"),array("status"=>$this->input->post("sampledataenable")));
        }
        if(!empty($this->input->post("sampledata"))){
	       	$data['user_ids']= implode(',', $this->input->post("sampledata"));
	        $data['religion'] = $this->admin_model->get_all_religion();
	        $data['ethnicity'] = $this->admin_model->get_all_ethnicity();
	        $data['googleapiskey'] = $this->admin_model->get_googleapiskey();
			$lang['languages']=$this->fetch_language();
			$this->load->view('admin/header');
	        $this->load->view('admin/sidebar',$lang);
		    $this->load->view('admin/sampledata_set', $data);
			$this->load->view('admin/footer');
	        //echo "<pre>";
	        //print_r($_POST);
	   	}
	   	else{
	   		redirect("admin/admin/sampledata");
	   	}
	}
	public function update_sampledata(){
		$logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
			redirect('login');
        }
        $ids=explode(",",$this->input->post("user_ids"));
        $data=array();
        if(!empty($this->input->post("gender"))){
        	$data['gender']=$this->input->post("gender");
        }
        if(!empty($this->input->post("gender_pref"))){
        	$data['gender_pref']=$this->input->post("gender_pref");
        }
        if(!empty($this->input->post("religion"))){
        	$data['religion']=$this->input->post("religion");
        }
        if(!empty($this->input->post("ethnicity"))){
        	$data['ethnicity']=$this->input->post("ethnicity");
        }
        if(!empty($this->input->post("address"))){
        	$data['address']=$this->input->post("address");
        	$address = $this->input->post("address");            
            $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&address='.urlencode($address).'&sensor=true');
			// Convert the JSON to an array
			$geo = json_decode($geo, true);
			if ($geo['status'] == 'OK') 
			{
			  // Get Lat & Long
				$latitude = $geo['results'][0]['geometry']['location']['lat'];
				$longitude = $geo['results'][0]['geometry']['location']['lng'];
				$data['location_lat']=$latitude ;
				$data['location_long']=$longitude;
			}
        }
        if(!empty($this->input->post("min_age"))){
        	$data['min_age_pref']=$this->input->post("min_age");
        }
        if(!empty($this->input->post("max_age"))){
        	$data['max_age_pref']=$this->input->post("max_age");
        }
        if(!empty($this->input->post("min_distance"))){
        	$data['min_dist_pref']=$this->input->post("min_distance");
        }
        if(!empty($this->input->post("max_distance"))){
        	$data['max_dist_pref']=$this->input->post("max_distance");
        }
        if(!empty($this->input->post("religion_pref"))){
        	$data['religion_pref']=implode(',', $this->input->post("religion_pref"));
        }
        if(!empty($this->input->post("ethnicity_pref"))){
        	$data['ethnicity_pref']=implode(',', $this->input->post("ethnicity_pref"));
        }
        if($this->admin_model->bulkupdate($data,$ids)){
        	redirect("admin/admin/sampledata");
        }
        else{
        	redirect("admin/admin/dashboard");
        }
	}
	public function updatedata(){
		$this->admin_model->updaterow("site_setting",array("mode"=>"sample_data"),array("status"=>$this->input->post("sampledataenable")));
		echo $this->db->last_query();
	}
	public function admin_detail()
	{
		$user_id=1;
		$data['content'] = $this->admin_model->get_user($user_id);
        $data->contentt = $this->fetch_gallery($user_id);
		$data['gallery'] = $this->admin_model->get_gallery_images(array("user_id "=>$user_id));
	    $data['googleapiskey'] = $this->admin_model->get_googleapiskey();
		$data->userdetail=$this->admin_model->get_user_where_row(array("id "=>$user_id));
        //$data['googleapis']='https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_KEY.'&libraries=places&callback=initialize';
		$this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/admin_detail', $data);
		$this->load->view('admin/footer');
	}
	public function update_admin(){
		//echo "<pre>";
		//print_r($_POST);
		$this->load->helper('form');
		$this->load->library('form_validation');
		// set validation rules
		$this->form_validation->set_rules('gender', lang('lbl_register_gender'), 'required');
		$this->form_validation->set_rules('day', lang('lbl_register_birthdate'), 'required|max_length[2]|callback_dob_validation');
		$this->form_validation->set_rules('month', lang('lbl_register_birthdate'), 'required|max_length[2]');
		$this->form_validation->set_rules('year', lang('lbl_register_birthdate'), 'required|max_length[4]');
		$this->form_validation->set_rules('about', lang('lbl_register_about'), 'trim|required|max_length[500]');
		$this->form_validation->set_rules('height', lang('lbl_register_height'), 'trim|required');	
		$this->form_validation->set_rules('address', lang('lbl_register_address'), 'trim|required');
		$this->form_validation->set_message('dob_validation', lang('lbl_profile_error_age'));
		$user_id = $this->input->post('user_id');
		if ($this->form_validation->run() === true) 
		{
			$datepref=$this->input->post('datepref');
			$datepref=str_replace(',datepref', '', $datepref);
			$birthdate=$this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day');
			$address = $_POST['address'];            
            $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&address='.urlencode($address).'&sensor=true');
			// Convert the JSON to an array
			$geo = json_decode($geo, true);
			if ($geo['status'] == 'OK') 
			{
			  // Get Lat & Long
				$latitude = $geo['results'][0]['geometry']['location']['lat'];
				$longitude = $geo['results'][0]['geometry']['location']['lng'];
			} 	
			$data=array(
				"fname"=>$this->input->post('fname'),
				"lname"=>$this->input->post('lname'),
				"dob"=>$birthdate,
				"about"=>$this->input->post('about'),
				"education"=>$this->input->post('education'),
				"profession"=>$this->input->post('profession'),
				"address"=>$this->input->post('address'),
				"gender"=>$this->input->post('gender'),
				'location_lat'=>$latitude,
				'location_long'=>$longitude,	
			);
			
			$condition=array("id"=>$user_id);
			if($this->admin_model->updaterow("users",$condition,$data)){
				
				//echo $this->db->last_query();
				redirect("admin/admin/admin_detail/".$user_id);
			}
			//print_r($data);
		}
		else
		{
			$data['content'] = $this->admin_model->get_user($user_id);
			$this->load->view('admin/header');
	        $this->load->view('admin/sidebar');
		    $this->load->view('admin/admin_detail', $data);
			$this->load->view('admin/footer');
		}
		
	}
	public function fetch_gallery($user_id)
	{
		$logged_in_user = $this->session->userdata('logged_in');
        if (!$logged_in_user)
        {
            redirect('login');
        }		
		return  $this->admin_model->get_gallery_images(array("user_id "=>$user_id));
	}
	public function user_gallery($user_id)
    {
		$data = new stdClass(); 
        $data->userdetail=$this->admin_model->get_user_where_row(array("id "=>$user_id));
        $data->content = $this->fetch_gallery($user_id);
		$this->load->view('admin/header');
	    $this->load->view('admin/sidebar'); 
		$this->load->view('admin/user_gallery',$data);
		$this->load->view('admin/footer');
    }
	public function unlinkfile($file=null)
	{
		if($file)
		{
			if(file_exists($file))
					unlink($file);
		}
	}
	public function galleryprofile()
	{
		$user_id=$this->input->post("avtar_user");
        $ispost=$this->input->method(TRUE);
		$image="img".$this->input->post("avatar_pos");
		if($this->input->post("avatar_pos"))
		{
			$position=$this->input->post("avatar_pos");
		}
		else
		{
			$position="";
		}
		$imgkey=$_FILES['avatar_file']['name'];
		//echo "<pre>";print_r($_POST);print_r($_FILES);die;
		$crop = new CropAvatar(
		  isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
		  isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
		  isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
		  'uploads/'
		);
		
		$response = array(
		  'state'  => 200,
		  'message' => $crop->getMsg(),
		  'result' => $crop->getResult()
		);
		//echo "<pre>";print_r($response);die;
		if($response['state']==200){

			$mainurl=$this->config->item('base_url');
			$img_url= $mainurl."".$response['result'];
			$filename = str_replace("uploads/","",$response['result']); 
			//copy file 
			copy("./uploads/".$filename,"./uploads/thumbnail/".$filename);
			$condition=array("user_id"=>$user_id,"img_key"=>$image);				
			if($image=="img1")
			{
				$condition=array("id"=>$user_id);
				$data=array("profile_image"=>$filename,"modified_date"=>date("Y-m-d H:i:s"));
				
				$user = $this->admin_model->update_users($data,$condition);
				$_SESSION['profile_image']=$filename;
			}
			else
			{
				
				if($this->admin_model->get_gallery_images($condition))
				{
					$data=array("img_url"=>$filename,"modified_date"=>date("Y-m-d H:i:s"),"imgposition"=>$position);
					$user = $this->admin_model->change__gallery_images($data,$condition);
				}
				else
				{
					$data=array(
						"img_url"=>$filename,
						"user_id"=>$user_id,
						"img_key"=>$image,
						"imgposition"=>$position,
						"created_date"=>date("Y-m-d H:i:s"),
						"modified_date"=>date("Y-m-d H:i:s")
					);
					$user = $this->admin_model->add_gallery_images($data);
				}
			}
			
			if($user)
			{
				$response = array(
				  'position'=> $position,
				  'state'  => 200,
				  'message' => $crop->getMsg(),
				  'result' => $img_url,
				);
			}
			else
			{
				$response = array(
				  'position'=> $position,
				  'state'  => 200,
				  'message' => $crop->getMsg(),
				  'result' => $crop->getResult()
				);
			}
		}
		else
		{
			$response = array(
				'position'=> $position,
				'state'  => 200,
				'message' => $crop->getMsg(),
				'result' => $crop->getResult()
			);
		}
		echo json_encode($response);
		die;
	}
	// Remove Gallery Image Of User
	public function ajax_remove_gallery_image()
    {
		$imgposition = $this->input->post('position');
		try
		{
			$logged_in_user = $this->session->userdata('logged_in');
			if (!$logged_in_user)
			{
				redirect('login');
			}
			$user_id = $this->input->post('user_id');
			$condition=array("imgposition"=>$imgposition);
			$image_detail= $this->admin_model->get_gallery_images($condition);
			$unlinkimg="";
			// delete old Image
			if(!empty($image_detail[0]["img_url"]))
			{
				$unlinkimg=$image_detail[0]["img_url"];
				$this->unlinkfile(DIRECTORY_PATH."uploads/thumbnail/".$unlinkimg);
				$this->unlinkfile(DIRECTORY_PATH."uploads/".$unlinkimg);
			}
			$data=array("img_url"=>"","modified_date"=>date("Y-m-d H:i:s"));
			$this->admin_model->change__gallery_images($data,$condition); 
		}
		catch(Exception $e)
		{
			$this->SiteErrorLog->addData("Remove Gallery Image Error :- ".$e->getMessage(),$user_id);
		}
		if($imgposition == 2){
			$url = base_url('images/step/02.png');
		}
		if($imgposition == 3){
			$url = base_url('images/step/03.png');
		}
		if($imgposition == 4){
			$url = base_url('images/step/04.png');
		}
		if($imgposition == 5){
			$url = base_url('images/step/05.png');
		}
		if($imgposition == 6){
			$url = base_url('images/step/06.png');
		}
		$response = array(
			'url'=>$url
		);
		echo json_encode($response);
		die;
		
    }

}