<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'/PHPThumb/ThumbLib.inc.php';
/**
 * Setting class.
 * 
 * @extends CI_Controller
 */
class Setting extends CI_Controller {
	/**
	 * __construct function.
	 * 
	 */
	public function __construct()
	{	
		parent::__construct();
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->load->model('SiteErrorLog');
		$this->load->model('Common_model');
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
	public function index()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}
		$setting=$this->admin_model->get_configuration();
		if( !empty($setting)){
			$GOOGLE_PLACE_API_KEY = $setting[$this->findKey($setting,'key','GOOGLE_PLACE_API_KEY')]['value'];
			$FACEBOOK_KEY = $setting[$this->findKey($setting,'key','FACEBOOK_KEY')]['value'];
			$XMPP_ENABLE = $setting[$this->findKey($setting,'key','XMPP_ENABLE')]['value'];
			$XMPP_HOST = $setting[$this->findKey($setting,'key','XMPP_HOST')]['value'];
			$APP_XMPP_HOST = $setting[$this->findKey($setting,'key','APP_XMPP_HOST')]['value'];
			$XMPP_DEFAULT_PASSWORD = $setting[$this->findKey($setting,'key','XMPP_DEFAULT_PASSWORD')]['value'];
			$XMPP_SERVER = $setting[$this->findKey($setting,'key','XMPP_SERVER')]['value'];
			$APP_XMPP_SERVER = $setting[$this->findKey($setting,'key','APP_XMPP_SERVER')]['value'];
			//$PEM_FILE = $setting[$this->findKey($setting,'key','PEM_FILE')]['value'];
			$PUSH_ENABLE_SANDBOX = $setting[$this->findKey($setting,'key','PUSH_ENABLE_SANDBOX')]['value'];
			$PUSH_SANDBOX_GATEWAY_URL = $setting[$this->findKey($setting,'key','PUSH_SANDBOX_GATEWAY_URL')]['value'];
			$PUSH_GATEWAY_URL = $setting[$this->findKey($setting,'key','PUSH_GATEWAY_URL')]['value'];
			$ANDROID_FCM_KEY = $setting[$this->findKey($setting,'key','ANDROID_FCM_KEY')]['value'];
			$INSTAGRAM_CALLBACK_BASE = $setting[$this->findKey($setting,'key','INSTAGRAM_CALLBACK_BASE')]['value'];
			$INSTAGRAM_CLIENT_SECRET = $setting[$this->findKey($setting,'key','INSTAGRAM_CLIENT_SECRET')]['value'];
			$INSTAGRAM_CLIENT_ID = $setting[$this->findKey($setting,'key','INSTAGRAM_CLIENT_ID')]['value'];
			$adMobKey = $setting[$this->findKey($setting,'key','adMobKey')]['value'];
			$adMobVideoKey = $setting[$this->findKey($setting,'key','adMobVideoKey')]['value'];
			$RemoveAddInAppPurchase = $setting[$this->findKey($setting,'key','RemoveAddInAppPurchase')]['value'];
			$RemoveAddInAppBilling = $setting[$this->findKey($setting,'key','RemoveAddInAppBilling')]['value'];
			$PaidChatInAppBilling = $setting[$this->findKey($setting,'key','PaidChatInAppBilling')]['value'];
			$LocationInAppBilling = $setting[$this->findKey($setting,'key','LocationInAppBilling')]['value'];
			$SuperLikeInAppBilling = $setting[$this->findKey($setting,'key','SuperLikeInAppBilling')]['value'];
			$PaidChatInAppPurchase = $setting[$this->findKey($setting,'key','PaidChatInAppPurchase')]['value'];
			$LocationInAppPurchase = $setting[$this->findKey($setting,'key','LocationInAppPurchase')]['value'];
			$SuperLikeInAppPurchase = $setting[$this->findKey($setting,'key','SuperLikeInAppPurchase')]['value'];
			$TermsAndConditionsUrl = $setting[$this->findKey($setting,'key','TermsAndConditionsUrl')]['value'];
			
		}
		$data=array(
			'GOOGLE_PLACE_API_KEY'=>$GOOGLE_PLACE_API_KEY,
			'FACEBOOK_KEY'=>$FACEBOOK_KEY,
			'XMPP_ENABLE'=>$XMPP_ENABLE,
			'XMPP_HOST'=>$XMPP_HOST,
			'APP_XMPP_HOST'=>$APP_XMPP_HOST,
			'XMPP_DEFAULT_PASSWORD'=>$XMPP_DEFAULT_PASSWORD,
			'APP_XMPP_SERVER'=>$APP_XMPP_SERVER,
			'XMPP_SERVER'=>$XMPP_SERVER,
			//'PEM_FILE'=>$PEM_FILE,
			'PUSH_ENABLE_SANDBOX'=>$PUSH_ENABLE_SANDBOX,
			'PUSH_SANDBOX_GATEWAY_URL'=>$PUSH_SANDBOX_GATEWAY_URL,
			'PUSH_GATEWAY_URL'=>$PUSH_GATEWAY_URL,
			'ANDROID_FCM_KEY'=>$ANDROID_FCM_KEY,
			'INSTAGRAM_CALLBACK_BASE'=>$INSTAGRAM_CALLBACK_BASE,
			'INSTAGRAM_CLIENT_SECRET'=>$INSTAGRAM_CLIENT_SECRET,
			'INSTAGRAM_CLIENT_ID'=>$INSTAGRAM_CLIENT_ID,
			'adMobKey'=>$adMobKey,
			'adMobVideoKey'=>$adMobVideoKey,
			'RemoveAddInAppPurchase'=>$RemoveAddInAppPurchase,
			'TermsAndConditionsUrl'=>$TermsAndConditionsUrl,
			'RemoveAddInAppBilling'=>$RemoveAddInAppBilling,
			'PaidChatInAppBilling'=>$PaidChatInAppBilling,
			'LocationInAppBilling'=>$LocationInAppBilling,
			'SuperLikeInAppBilling'=>$SuperLikeInAppBilling,
			'PaidChatInAppPurchase'=>$PaidChatInAppPurchase,
			'LocationInAppPurchase'=>$LocationInAppPurchase,
			'SuperLikeInAppPurchase'=>$SuperLikeInAppPurchase,
        );
		//echo "<pre>";print_r($data);die;
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/setting/setting',$data);
		$this->load->view('admin/footer');
	}
	/*
	 * Update site setting 
	 */
	public function update_setting()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}
		$setting=array();
		foreach($this->input->post() as $key=>$value)
		{
			$setting[$key]=$value;
		}
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('PUSH_SANDBOX_GATEWAY_URL', 'PUSH SANDBOX GATEWAY URL', 'trim|prep_url|valid_url|xss_clean');
		$this->form_validation->set_rules('PUSH_GATEWAY_URL', 'PUSH GATEWAY URL', 'trim|prep_url|valid_url|xss_clean');
		$this->form_validation->set_rules('INSTAGRAM_CALLBACK_BASE', 'INSTAGRAM CALLBACK BASE', 'trim|prep_url|valid_url|xss_clean');
		$this->form_validation->set_rules('TermsAndConditionsUrl', 'Terms And Conditions', 'trim|prep_url|valid_url|xss_clean');
		if ($this->form_validation->run() === true)
        {
			/*$data = new stdClass();
			if(!empty($_FILES["PEM_FILE"]['name']))
			{
				$name = $_FILES["PEM_FILE"]["name"];
				$ext = end((explode(".", $name)));
				$config['upload_path']= './Newassets/file/';        
				$config['allowed_types'] = $ext;
				$config['overwrite'] = TRUE;
				$config['file_name'] = $_FILES["PEM_FILE"]["name"];
				$this->load->library('upload');
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload('PEM_FILE'))
				{
					// On File Upload Fail
					$this->session->set_flashdata('fail',$this->upload->display_errors());
					redirect('admin/setting/index');
				}else{
					// On File Upload success
					$file_data = $this->upload->data();
					$setting=$this->admin_model->get_configuration();
					if( !empty($setting)){
						$PEM_FILE = $setting[$this->findKey($setting,'key','PEM_FILE')]['value'];
					}
					$file=$PEM_FILE;
					$res=$this->admin_model->configuration(array('PEM_FILE'=>$name));
					if(file_exists(base_url("/Newassets/file/".$file)))
							unlink(base_url("/Newassets/file/".$file));
					//echo "<pre>";print_r($file_data);die;
					$source_path = DIRECTORY_PATH.'Newassets/file/'.$file_data["file_name"];
					$target_path = DIRECTORY_PATH.'Newassets/file/'.$file_data["file_name"];
					$thumb = PhpThumbFactory::create($source_path);
					$thumb->adaptiveResize(96, 40);
					$thumb->save($target_path, $ext);
				}
			}*/
			$res=$this->admin_model->configuration($setting);
			$this->session->set_flashdata('success','success');
		}else{
			$this->session->set_flashdata('fail','fail');
		}
		redirect('admin/setting/index');
	}
	function valid_url($url){

           $pattern = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
            if (!preg_match($pattern, $url))
            {
                return FALSE;
            }
            return TRUE;
    }
	private function findKey($array, $field, $value){
		foreach($array as $key => $item)
		{
			if ( $item[$field] === $value )
				return $key;
		}
		return false;
	}
	public function features()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}
		$setting=$this->admin_model->get_configuration();
		if( !empty($setting)){
			$PAID_CHAT = $setting[$this->findKey($setting,'key','PAID_CHAT')]['value'];
			$PAID_LOCATION = $setting[$this->findKey($setting,'key','PAID_LOCATION')]['value'];
			$PAID_SUPERLIKE = $setting[$this->findKey($setting,'key','PAID_SUPERLIKE')]['value'];
			$PAID_AD = $setting[$this->findKey($setting,'key','PAID_AD')]['value'];
			$PER_DAY_SUPERLIKE = $setting[$this->findKey($setting,'key','PER_DAY_SUPERLIKE')]['value'];
		}
		$data=array(
			'PAID_CHAT'=>$PAID_CHAT,
			'PAID_LOCATION'=>$PAID_LOCATION,
			'PAID_SUPERLIKE'=>$PAID_SUPERLIKE,
			'PER_DAY_SUPERLIKE'=>$PER_DAY_SUPERLIKE,
			'PAID_AD'=>$PAID_AD,
        );
		//echo "<pre>";print_r($data);die;
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/features/features',$data);
		$this->load->view('admin/footer');
	}
	public function update_features()
	{
		$logged_in_user = $this->session->userdata('logged_in');   
		if (!$logged_in_user)
		{
			redirect('admin/admin/login');			
		}
		$setting=array();
		foreach($this->input->post() as $key=>$value)
		{
			$setting[$key]=$value;
		}
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('PAID_CHAT', 'Chat Option', 'required');
		$this->form_validation->set_rules('PAID_LOCATION', 'Location Option', 'required');
		$this->form_validation->set_rules('PAID_SUPERLIKE', 'Superlike Option', 'required');
		$this->form_validation->set_rules('PAID_AD', 'Add Option', 'required');
		if ($this->form_validation->run() === true)
        {
			$res=$this->admin_model->configuration($setting);
			$this->session->set_flashdata('success','success');
		}else{
			$this->session->set_flashdata('fail','fail');
		}
		redirect('admin/setting/features');
	}
}