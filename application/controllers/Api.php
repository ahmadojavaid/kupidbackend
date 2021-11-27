<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'/PHPThumb/ThumbLib.inc.php';
//en
/**
 * User class.
 * 
 * @extends CI_Controller
 */
class Api extends CI_Controller {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('api_model');
		$this->load->model('SiteErrorLog');
		$this->load->model('Pushnotification_model');
		//$this->load->model('Ejabberd_model');
		date_default_timezone_set('Asia/Kolkata');
	}
	/**
	 * edit_gallery_images function for webservices.
	 * 
	 * @access public
	 * @return List Of Users Gallery
	 */
    public function edit_gallery_images()
    {
        $required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
        $response=array();
        if($status)
        {
            $userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
                $files=array_keys($_FILES);
			    $user=$this->api_model->getUserData($this->input->post("id"));
                $imgkey=key($_FILES);
				$user_id=$user["id"];
			    foreach($files as $imgkey)
                {
					if($_FILES[$imgkey]["name"]!="")
					{	
						$unlinkimg="";
						if($imgkey=="img1")
						{
							$unlinkimg=$user["profile_image"];
						}
						else
						{
							$abc=$this->api_model->get_user_gellary(array("user_id"=>$user_id,"img_key"=>$imgkey));
							if(!empty($abc["img_url"]))
							{	 
								$unlinkimg=$abc["img_url"];
							}
						}
						if(file_exists(base_url($unlinkimg)))
							unlink(base_url($unlinkimg));
					}
					$config['upload_path']= './uploads/';        
					$config['allowed_types']= 'gif|jpg|png|jpeg';
					$config['overwrite'] = TRUE;					
					$this->load->library('upload');
					$this->upload->initialize($config);
					if (!($this->upload->do_upload($imgkey)))
                    {
                        $data = $this->upload->display_errors();
                        $response['error'] = true;
                        $response['message'] = 'File could not be uploaded!. Please try again. '.$data;
                    }
                    else
                    {
					    $file_data = $this->upload->data();
						$source_path = './uploads/'.$file_data["file_name"];
						$target_path = './uploads/thumbnail/'.$file_data["file_name"];
						$thumb = PhpThumbFactory::create($source_path);
						$thumb->adaptiveResize(280, 250);
						$thumb->save($target_path, 'jpg');
						if($imgkey=="img1")
                        {
					        $condition=array("id"=>$user_id);
                            $data=array("profile_image"=>$file_data["file_name"],"modified_date"=>date("Y-m-d H:i:s"));
                            $this->api_model->api_user_update($data,$condition);
                        }
                        else
                        {
					        $condition=array("user_id"=>$user_id,"img_key"=>$imgkey);
                            $abc=$this->api_model->get_user_gellary(array("user_id"=>$user_id,"img_key"=>$imgkey));
                            if($abc)
                            {
                                $data=array("img_url"=>$file_data["file_name"],"modified_date"=>date("Y-m-d H:i:s"));
                        		$this->api_model->change__gallery_images($data,$condition);   
                            }
                            else
                            {
								$data=array(
									"img_url"=>$file_data["file_name"],
									"user_id"=>$user_id,
									"img_key"=>$imgkey,
									"created_date"=>date("Y-m-d H:i:s"),
									"modified_date"=>date("Y-m-d H:i:s"));
                                $this->api_model->add_gallery_images($data);
                            }
                        }
                        $user=$this->api_model->getUserData($this->input->post("id"));
                        $imgs["img1"]=$user["profile_image"]; 
                        $user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
						foreach($user_gellary as $gimg)
						{
							if($gimg["img_url"]!="")
								$imgs[$gimg["img_key"]]=$gimg["img_url"];
						}
					    $response["gallary"]=$imgs;
                        //response for success
                        $response["error"] = false;
        				$response['message'] = "Your profile has been updated!";
                    }
                }
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Session is expired';
            }    
        }
        else
        {
       	    $response['error'] = true;
			$response['message'] = 'Could not update Gallery. Invalid Username/Password';
        }
        $this->SendResponse($response);        
    }
	public function testsendpush()
	{
		$user=$this->Pushnotification_model->send_push("D0E53F0E2669265D8B6B4381DD245BF3449AAEB0D69C1AA69A959F414299FA52", "FindFellow",5 , "Testing Message ",$type=2,array('friendid'=>1,'friend_Fname'=>"test",'friend_Lname'=>"test",'friend_profileImg_url'=>"abc.png"));
	}
	/**
	 * default_language function for webservices.
	 * 
	 * @access public
	 * @return default Language 
	 */
	public function default_language()
	{
		$result=$this->api_model->fetch_language();
		if($result)
		{
			$response["error"] = false;
			$response['language'] = $result['status'];
			$response['rtl'] = $result['rtl'];
		}
		else
		{
			$response["error"] = true;
			$response['message'] = "Default language is not set.";
		}
		$this->SendResponse($response);  
	}
	/**
	 * all_languages function for webservices.
	 * 
	 * @access public
	 * @return All Language 
	 */
	public function all_languages()
	{
		$result=$this->api_model->all_language();
		if($result)
		{
			$response["error"] = false;
			$response['language'] = $result;
		}
		else
		{
			$response["error"] = true;
			$response['language'] = "english";
			$response['message'] = "Languages are not set.";
		}		
		$this->SendResponse($response);
	}
	/**
	 * forgot_password function for webservices.
	 * 
	 * @access public
	 * @return user data who is forgot his or her password
	 */
	public function forgot_password()
	{
		 $required_fields=array("email");
		 $status=$this->verifyRequiredParams($required_fields);
         $response=array();
         if($status)
         {
			$email=$this->input->post("email");             
		    $result=$this->api_model->checkmail($email);
			if($result)
			{
				$response["error"] = false;
				$response['message'] = $result;
			}
			else
			{
				$response["error"] = true;
				$response['message'] = "This Email is not registered with this application.";
			}
		 }
		 $this->SendResponse($response);  
	}
	/**
	 * login function for webservices.
	 * 
	 * @access public
	 * @return login user information when login is success
	 */
	public function login()
	{
		 // reading post params
		$required_fields=array('email','password','device_token','device');
		$response = array();
		 // check for required params
		$status=$this->verifyRequiredParams($required_fields);
		if($status)
		{
			$email=$this->input->post("email");
			$password=$this->input->post("password");
			$device_token=$this->input->post("device_token");
			$device=$this->input->post("device");
			 // check for correct email and password
			$isuserfound=$this->api_model->login($email,$password);				
			if($isuserfound)
			{
				$data=array(
				'user_id'=>$isuserfound,
				'device_token'=>$device_token,
				'AuthToken'=>md5(uniqid(rand(), true)),
				'device'=>$device,
				'login_time'=>date('H:i:s')
				);
				//insert user login data in userlogin table
				$value=$this->api_model->userlogin($data,$table="userlogin");
				$authtoken=$this->api_model->fetch_authtoken($device_token,$isuserfound);
				$user=$this->api_model->getUserData($isuserfound);
				$user['AuthToken']=$authtoken;
				$imgs=array();
                $imgs["img1"]=$user["profile_image"]; 
               	$user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
                foreach($user_gellary as $gimg)
                {
                    if($gimg["img_url"]!="")
                        $imgs[$gimg["img_key"]]=$gimg["img_url"];
                }
				$response["error"] = false;
				$response['message'] = "User Login Successfully.";
				$response['loginUserDetails'] = $user;
                $response["user_gallary"]=$imgs;
				$check_purchase=$this->api_model->get_login_user_purchase(array('userid'=>$isuserfound));
				//echo "<pre>";print_r($check_purchase);die;
				$response["user_purchase"]=$check_purchase;
			}
			else
			{
				$response['error'] = true;
                $response['message'] = "Invalid username or password.";
			}
		}
		else
		{
			 $response['error'] = true;
             $response['message'] = 'Invalid username or password.';
		}
		$this->SendResponse($response);	 
	}
	//Api_Logout Function
	 public function api_logout()
	 {
		$required_fields=array('id','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
			if($userstatus)
			{
				$id=$this->input->post("id");
				$auth_Token=$this->input->post("AuthToken");
				$result=$this->api_model->logout(array('user_id'=>$id,'AuthToken'=>$auth_Token));
				if($result > 0)
				{
					$response["error"] = false;
					$response['message'] = "You are successfully Logout.";
				}
				else
				{
					$response['error'] = true;
					$response['message'] = "Something went wrong. Please try again.";
				}
			}
			else
			{
				$response['error'] = true;
				$response['error_code'] = "101";
				$response['message'] = 'Seesion Expired. Please try again';
			}
		 }
		 else
		 {
			$response['error'] = true;
            $response['message'] = 'Seesion Expired. Please try again';
		 }
         $this->SendResponse($response);
	 }
	  /**
	 * checkauthtoken function for webservices.
	 * 
	 * @access public
	 * @return TRUE/FALSE to check current user is authenticate or not
	 */
	public function checkauthtoken($id,$auth)
    {
        $result=$this->api_model->get_userslogin(array("user_id"=>$id,"AuthToken"=>$auth));
        if($result)
            return true;
        else
            return false;
    }
	/**
	 * userPrefencesUpdate function for webservices.
	 * 
	 * @access public
	 * @return void
	 */
     public function userPrefencesUpdate()
     {
        $response = array();
		$required_fields=array('id','gender_pref','max_age_pref','min_age_pref','date_pref','max_dist_pref','min_dist_pref','que_id','que_ans','AuthToken');
        //$required_fields=array('id','about', 'gender_pref','max_age_pref','min_age_pref','date_pref','max_dist_pref','min_dist_pref','height','religion','ethnicity','kids','que_id','que_ans','AuthToken');
		$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
        if($userstatus)
        {
            $status=$this->verifyRequiredParams($required_fields);
            if($status)
            {
				$result=$this->api_model->api_user_update($this->input->post(),array("id"=>$this->input->post("id")));
				if($result)
				{
					$response["error"] = false;
					$response['message'] = "Your preference has been updated!";
				//		$response['loginUserDetails'] = $this->api_model->getUserData($this->input->post("id"));
				}
				else
				{
					$response['error'] = true;
					$response['message'] = "Something went wrong. Please try again.";
				}
            }
    		else 
			{
    			// user credentials are wrong
    			$response['error'] = true;
    			$response['message'] = 'Could not register the user. Invalid credentials';
    		}
		}
		else
		{
            $response['error'] = true;
    	    $response['error_code'] = "101";
            $response['message'] = 'Seesion Expired. Please try again';
		}
		$this->SendResponse($response);
    }
      /**
	 * loginwithfb function for webservices.
	 * 
	 * @access public
	 * @return void
	 */
    public function loginwithfb()
    {
        $required_fields=array("fb_id","device");      
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $fbstatus=$this->api_model->get_users_row(array("fb_id"=>$this->input->post("fb_id")));
            if($fbstatus)
            {
				$isalreadyExists=$this->api_model->get_users_row(array("email"=>$this->input->post("email")));
				if(!$isalreadyExists || $fbstatus['email']==$isalreadyExists['email']){
					$data=array("fname"=>$this->input->post("fname"),
								"lname"=>$this->input->post("lname"),
								"email"=>$this->input->post("email"),
								"device_token"=>$this->input->post("device_token"));
					$dob=$this->input->post("dob");          
					if(!empty($dob))
					{
						$dob=str_replace("/","-",$this->input->post("dob"));
						$dob = date("Y-m-d",strtotime($dob));
						$data["dob"]=$dob;
					}
					if($this->input->post("about")!="")
					{
						$data["about"]=$this->input->post("about");
					}
					if($this->input->post("gender")!="")
					{
						$data["gender"]=$this->input->post("gender");
					}
					if($this->input->post("gender_pref")!="")
					{
						$data["gender_pref"]=$this->input->post("gender_pref");
					}
					if($this->input->post("location_lat")!="")
					{
						$data["location_lat"]=$this->input->post("location_lat");
					}
					if($this->input->post("location_long")!="")
					{
						$data["location_long"]=$this->input->post("location_long");
					}
					if($this->input->post("access_location")!="")
					{
						$data["access_location"]=$this->input->post("access_location");
					}
					if($this->input->post("education")!="")
					{
						$data["education"]=$this->input->post("education");
					}
					if($this->input->post("profession")!="")
					{
						$data["profession"]=$this->input->post("profession");
					}
					$result=$this->api_model->api_user_update($data,array("fb_id"=>$this->input->post("fb_id")));
					if($result)
					{
						$device_token=$this->input->post('device_token');
						$user=$this->api_model->get_users_row(array("fb_id"=>$this->input->post("fb_id")));
						$ejuser=substr($user['fname'],0,1).substr($user['lname'],0,1).$user['id'];
						$this->api_model->api_user_update(array("ejuser"=>$ejuser),array("id"=>$user['id']));
						$user=$this->api_model->get_users_row(array("fb_id"=>$this->input->post("fb_id")));
						//After login with fb insert user data into the userlogin table
						$data=array(
							'user_id'=>$user['id'],
							'device_token'=>$device_token,
							'AuthToken'=>md5(uniqid(rand(), true)),
							'device'=>$this->input->post('device'),
							'login_time'=>date('H:i:s'));
						//insert user login data in userlogin table
						$value=$this->api_model->userlogin($data,$table="userlogin");
						$user['AuthToken']=$this->api_model->fetch_authtoken($device_token,$user['id']);
						if($user["profile_image"]=="")
						{
							$imgs["img1"]="uploads/default.png"; 
						}
						else
						{
							$imgs["img1"]=$user["profile_image"]; 
						}
						$user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
						foreach($user_gellary as $gimg)
						{
							if($gimg["img_url"]!="")
								$imgs[$gimg["img_key"]]=$gimg["img_url"];
						}
						//update authtoken
						$response["error"] = false;
						$response['message'] = "You are logged in successfully.";
						$response['body'] = $user;
						$response['body']["new_user"]="0";
						$response['body']["user_gallary"]=$imgs;
					}
					else
					{
						$response['error'] = true;
						$response['message'] = 'Invalid username or password.';
					}
				}
				else{
					$response['error'] = true;
					$response['message'] = 'This email is already in use. Please try with different email address';
				}
            }
            else
            {
				$device_token=$this->input->post('device_token');
                $result=$this->api_model->create_user_fb($this->input->post());
				$user=$this->api_model->get_users_row(array("fb_id"=>$this->input->post("fb_id")));
				$ejuser=substr($user['fname'],0,1).substr($user['lname'],0,1).$user['id'];
				$this->api_model->api_user_update(array("ejuser"=>$ejuser),array("id"=>$user['id']));
				$user=$this->api_model->get_users_row(array("fb_id"=>$this->input->post("fb_id")));
				$data=array(
					'user_id'=>$user['id'],
					'device_token'=>$device_token,
					'AuthToken'=>md5(uniqid(rand(), true)),
					'device'=>$this->input->post('device'),
					'login_time'=>date('H:i:s')
					);
				if(!$result){
					$value=$this->api_model->userlogin($data,$table="userlogin");
				}
				$user['AuthToken']=$this->api_model->fetch_authtoken($device_token,$user['id']);
				if($user["profile_image"]=="")
					{
						$imgs["img1"]="uploads/default.png"; 
					}
					else
					{
						$imgs["img1"]=$user["profile_image"]; 
					}
                   	$user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
                    foreach($user_gellary as $gimg)
                    {
                        if($gimg["img_url"]!="")
                            $imgs[$gimg["img_key"]]=$gimg["img_url"];
					}
				$response['error'] = false;
				$response['body'] = $user;
				$response['body']["new_user"]="1";
				$response['body']["user_gallary"]=$imgs;
				$response['message'] = 'Successfully register with facebook';
            }
        }
        else
        {
			$response['error'] = true;
			$response['message'] = 'Invalid username or password.';
        }
        $this->SendResponse($response);
    }
	 //Session Expired
	public function session_expired()
	{
		$required_fields=array("device_token");
		$status=$this->verifyRequiredParams($required_fields);
		if($status)
		{
			$device_token=$this->input->post("device_token");
			$result=$this->api_model->logout(array('device_token'=>$device_token));
			if($result)
			{
				$response['message'] = "You are logged out successfully";
				$response['error'] = false;
			}
			else
			{
				$response['message'] = "Something went wrong. Please log in again.";
				$response['error'] = true; 
			}
		}
		$this->SendResponse($response);
	}
	 /**
	 * getuserdetails function for webservices.
	 * 
	 * @access public
	 * @return user information which is needed
	 */
    public function getuserdetails()
    {
    	$response=array();
		$required_fields=array("id","AuthToken","userid");      
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
                $uid=$this->input->post("id");
                $result=$this->api_model->sendmessage($this->input->post("id"),$this->input->post("friendid"),$this->input->post("message"));
            	$user=$this->api_model->getUserData($this->input->post("userid"));
                $imgs["img1"]=$user["profile_image"]; 
                $user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
				foreach($user_gellary as $gimg)
				{
					if($gimg["img_url"]!="")
						$imgs[$gimg["img_key"]]=$gimg["img_url"];
				}
                $response['message'] = "User Details";
				$response['error'] = false;
				$response["body"]=$this->api_model->getUserData($this->input->post("userid"));
                $response['body']["gallary"]=$imgs;
                $this->add_visiter($this->input->post("userid"),$this->input->post("id"));
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
        }
        else
        {
       	    $response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
        }
        $this->SendResponse($response);
    }
    /**
	 * sendmessage function for webservices.
	 * 
	 * @access public
	 * @return send messages to user
	 */
    public function sendmessage()
    {
		$required_fields=array("id","AuthToken","friendid","message");      
		$status=$this->verifyRequiredParams($required_fields);
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
			    $uid=$this->input->post("id");
				$result=$this->api_model->sendmessage($this->input->post("id"),$this->input->post("friendid"),$this->input->post("message"));
				$ludetail=$this->api_model->getUserData($this->input->post("id"));
				$udetail=$this->api_model->getUserData($this->input->post("friendid"));
				$fdetail["friend_profileImg_url"]=$ludetail["profile_image"];
				$fdetail["friend_Fname"]=$ludetail["fname"];
				$fdetail["friend_Lname"]=$ludetail["lname"];
				$fdetail["friendid"]=$ludetail["id"];
				$fdetail["friend_ejuser"]=$ludetail["ejuser"];
				$users=$this->api_model->get_userslogin(array('user_id'=>$this->input->post("friendid")));
				$msg=$this->input->post("message");
				foreach($users as $user)
				{
					if(!empty($user["device_token"]))
					{
						if($user['device']=="ios")				
							$this->Pushnotification_model->send_push($user["device_token"], "FindFellow",($udetail["notificationcounter"]+1) , $ludetail["fname"]." Send Message You : ".$msg,$type=3,$fdetail);
						else if($user['device']=='android')
							$this->Pushnotification_model->send_push_android($user["device_token"], "FindFellow",($udetail["notificationcounter"]+1) , $msg,$type=3,$fdetail);
					}
				}
				$response['error'] = false;
            	$response['message'] = "Message has been sent.";
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
        }
        else
        {
       	    $response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
        }
        $this->SendResponse($response);
    }
    /**
	 * userUnfriend function for webservices.
	 * 
	 * @access public
	 * @return void
	 */
    public function userUnfriend()
    {
        $required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $required_fields=array("id","AuthToken","friendid");      
			$status=$this->verifyRequiredParams($required_fields);
			if($status)
			{
				$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
				if($userstatus)
				{
					$uid=$this->input->post("id");
					$result=$this->api_model->userUnfriend($this->input->post("id"),$this->input->post("friendid"));
					$response['message'] = "Unfriend.";
					$response['error'] = false;
				}
				else
				{
					$response['error'] = true;
					$response['error_code'] = "101";
					$response['message'] = 'Something went wrong. Please log in again.';
				}
			}
			else
			{
				$response['error'] = true;
				$response['message'] = 'Something went wrong. Please log in again.';
			}
			$this->SendResponse($response);
    	}
    }
    /**
	 * chat function for webservices.
	 *  Function is used to send message to friend
	 * @access public
	 * @return void
	 */ 
    public function chat()
    {
        $required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
                $uid=$this->input->post("id");
                $result=$this->api_model->chat($this->input->post("id"));
            	$response['message'] = "Chat.";
                $response["body"]=$result;
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
        }
        else
        {
       	    $response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
        }
        $this->SendResponse($response);
    }
    /**
	 * approvenotification function for webservices.
	 *  
	 * @access public
	 * @return void
	 */
    public function approvenotification()
    {
        $required_fields=array("id","AuthToken","send_user_id","approved");      
        $status=$this->verifyRequiredParams($required_fields);
	    if($status)
        {
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
			if($userstatus)
			{
				$chk=$this->api_model->get_freienddata($this->input->post("id"),$this->input->post("send_user_id"));
				if(!empty($chk))
				{
					$response["error"] = false;
					$response['message'] = "You have already approved this user.";
				}
				else
				{
					$update=$this->api_model->approvenotification(
							array("approved"=>$this->input->post("approved"),
								  "modified_date"=>date("Y-m-d H:i:s")),
							array("send_user_id"=>$this->input->post("send_user_id"),
								  "receive_user_id"=>$this->input->post("id")),
							array("receive_user_id"=>$this->input->post("send_user_id"),
								  "send_user_id"=>$this->input->post("id"),
								  "modified_date"=>date("Y-m-d H:i:s"),
								  "created_date"=>date("Y-m-d H:i:s"),
								  "status"=>"1",
								  "approved"=>$this->input->post("approved"))
					);
					if($update)
					{
						$response["error"] = false;
						$response['message'] = "Approved.";
						if($this->input->post("approved")==1)
						{
							$ludetail=$this->api_model->getUserData($this->input->post("id"));
							$udetail=$this->api_model->getUserData($this->input->post("send_user_id"));
							
							$fdetail["friend_profileImg_url"]=$ludetail["profile_image"];
							$fdetail["friend_Fname"]=$ludetail["fname"];
							$fdetail["friend_Lname"]=$ludetail["lname"];
							$fdetail["friendid"]=$ludetail["id"];
							$fdetail["friend_ejuser"]=$ludetail["ejuser"];
						
							$users=$this->api_model->get_userslogin(array('user_id'=>$this->input->post("send_user_id")));
							foreach($users as $user)
							{
								if(!empty($user['device_token']))
								{
									$fdetail["friend_profileImg_url"]=$ludetail["profile_image"];
									$fdetail["friend_Fname"]=$ludetail["fname"];
									$fdetail["friend_Lname"]=$ludetail["lname"];
									$fdetail["friendid"]=$ludetail["id"];
									if($user['device']=="ios")
									{
										$this->Pushnotification_model->send_push($user["device_token"], "FindFellow",($udetail["notificationcounter"]+1) , "You are matched With ".$ludetail["fname"],$type=2,$fdetail);
									}
									elseif($user['device']=="android")
									{
										$this->Pushnotification_model->send_push_android($user["device_token"], "FindFellow",($udetail["notificationcounter"]+1) , "You are matched With ".$ludetail["fname"],$type=2,$fdetail);
									}
								}
								else
								{
									$response['error'] = true;
									$response['message'] = 'Device Token is Not Available';										
								}
							}
						}
						else if($this->input->post("approved")==0)
						{
							$response['error'] = false;
							$response['message'] = 'Dis-approved';
						}
						else
						{   
							$response['error'] = true;
							$response['message'] = 'Something went wrong. Please log in again.';
						}
					}
				}
			}
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
        }
        else
        {
            // user credentials are wrong
			$response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
        }
        $this->SendResponse($response);
    }
	 /**
	 * usergallary function for webservices.
	 *  Function is used fetch perticular gallery
	 * @access public
	 * @return void
	 */
    public function usergallary()
    {
        $required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
                $user=$this->api_model->getUserData($this->input->post("id"));
                $imgs["img1"]=$user["profile_image"]; 
               	$user_gellary=$this->api_model->get_user_gellary(array("user_id"=>$user["id"]));
                foreach($user_gellary as $gimg)
                {
                    if($gimg["img_url"]!="")
                        $imgs[$gimg["img_key"]]=$gimg["img_url"];
                }
                $response["user_gallary"]=$imgs;
				$response["error"] = false;
				$response['message'] = "User Gallery.";
				$response['loginUserDetails'] = $user;
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
        }
        else
        {
            $response['error'] = true;
            $response['message'] = 'Faild to load gallery';
        }
        $this->SendResponse($response);
    }
	/**
	 * register function for webservices.
	 *  Function is used register user in system
	 * @access public
	 * @return void
	 */
	public function register()
    {
		$response = array();
        $required_fields=array( 
			'fname', 
			'lname', 
			'email', 
			'password',
			'device_token',
			'dob', 
			'about', 
			'gender_pref',
			'max_age_pref',
			'min_age_pref',
			'date_pref', 
			'gender',
			'location_lat', 
			'location_long', 
			'education', 
			'profession',
			'max_dist_pref',
			'min_dist_pref',
			'status',
			'height',
			'religion',
			'ethnicity',
			'kids',
			'que_id',
			'que_ans',
			'device');
        $status=$this->verifyRequiredParams($required_fields);
        if($status)
        {
            $emailun=$this->api_model->get_users_row(array("email"=>trim($this->input->post("email"))));
            if($emailun)
            {
                $response['error'] = true;
                $response['message'] = "This email is already in use. Please try with different email address";
            }
            else
            {
				$emailverified=$this->api_model->get_users_verification(array("email"=>$this->input->post("email"),"status"=>1));
				if(!empty($emailverified)){
					$result=$this->api_model->create_user($this->input->post());				
					if($result)
					{
						$device_token=$this->input->post('device_token');
						$user=$this->api_model->getUserData($result);
						$ejuser=substr($user['fname'],0,1).substr($user['lname'],0,1).$user['id'];
						$this->api_model->api_user_update(array("ejuser"=>$ejuser),array("id"=>$user['id']));
						$user=$this->api_model->getUserData($result);
						$user['AuthToken']=$this->api_model->fetch_authtoken($device_token,$user['id']);
						$response["error"] = false;
						$response['message'] = "You has been registered successfully.";
						$response['body'] = $user;	
					}
					else
					{
						$response['error'] = true;
						$response['message'] = "Something went wrong. Please log in again.";
					}
				}
				else{
					$response['error'] = true;
					$response['message'] = "Please verified your email address.";
				}
            }
        }
		else 
		{
			// user credentials are wrong
			$response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
    }
	public function SendResponse($response)
	{
	  header('Content-Type: application/json');
	  echo json_encode($response);
	  die; 
	}
	 // verified Required Parameters
	public function verifyRequiredParams($fields)
    {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        foreach ($fields as $field) 
		{
			if (!isset($request_params[$field])) 
			{
				$error = true;
				$error_fields .= $field . ', ';
			}
		}
		if ($error) 
		{
			// Required field(s) are missing or empty
			// echo error json and stop the app
			$response = array();
			$response["error"] = true;
			$response["message"] = 'One or more fileds are required. ' . substr($error_fields, 0, -2);
			return false;
		}
		else
		{
			return true;
		}		 
    }
	/**
	 * userfilter function for webservices.
	 *  
	 * @access public
	 * @return void
	 */
	public function userfilter()
	{
		  // reading post params
		$required_fields=array('id','location_lat','location_long','start','AuthToken');
		$response = array();
		 // check for required params
		$status=$this->verifyRequiredParams($required_fields);
		//echo "<pre>";
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
			//echo $this->db->last_query();
			//print_r($userstatus);die;
            if($userstatus)
            {
    			$user_id = $this->input->post('id');
    			$latitude = $this->input->post('location_lat');
    			$longitude = $this->input->post('location_long');
    			$start = $this->input->post('start');
    			$auth_token = $this->input->post('AuthToken');
    			$res=$this->api_model->userCheckFilter($user_id,$latitude,$longitude,$start,$auth_token);
				
    			if($res)
    			{
    				$response["error"] = false;
                    $response["message"] = "Users profile";
                    foreach($res as $row)
					{
						$response["body"][]=$row;
					}
				}
    			else
    			{
					$response['error'] = true;
					$response['message'] = "No user found.";
    			}
			}
			else
			{
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		else
		{
			$response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);	
	}
    /**
	 * sendnotification function for webservices.
	 *  Function is used send friend request to particular user
	 * @access public
	 * @return void
	 */
    public function sendnotification()
	{
		$required_fields=array('id', 'receive_user_id', 'status', 'AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
        	$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$senderid = $this->input->post('id');
				$receiverid = $this->input->post('receive_user_id');
				/* limit only one day*/
				$superlike=$this->api_model->superlike($senderid);
				$count_superlike = $superlike['count(id)'];
				$setting=$this->admin_model->get_configuration();
				$per_day_superlike = $setting[$this->findKey($setting,'key','PER_DAY_SUPERLIKE')]['value'];
				$total_day_superlike = $per_day_superlike + 1;
				$paid_superlike=$this->api_model->paid_check_superlike($senderid);
				if($count_superlike=='0' || (!empty($paid_superlike) && $per_day_superlike=='-1') || (!empty($paid_superlike) && $total_day_superlike > $count_superlike) ){
					$check=$this->api_model->get_freienddata($senderid,$receiverid);
					if(empty($check))
					{
						$status = $this->input->post('status');
						$auth_token = $this->input->post('AuthToken');
						$result=$this->api_model->send_notification($senderid,$receiverid,$status,$auth_token);
						if($result)
						{
							$response["error"] = false;
							$response["message"] = "Notification has been sent successfully.";
							//$response["body"] = $result;
							$ludetail=$this->api_model->getUserData($this->input->post("id"));
							$udetail=$this->api_model->getUserData($this->input->post("receive_user_id"));
							$fdetail["friend_profileImg_url"]=$ludetail["profile_image"];
							$fdetail["friend_Fname"]=$ludetail["fname"];
							$fdetail["friend_Lname"]=$ludetail["lname"];
							$fdetail["friendid"]=$ludetail["id"];
							if($status==1)
							{
								$users=$this->api_model->get_userslogin(array('user_id'=>$this->input->post("receive_user_id")));
								$notificationcounter=$udetail["notificationcounter"]+1;
								try
								{
									foreach($users as $user)
									{
										if($user['device']=="ios")
											$this->Pushnotification_model->send_push($user['device_token'], "FindFellow",$notificationcounter, $ludetail["fname"]." has Liked you",$type=1,$fdetail);
										elseif($user['device']=="android")
											$this->Pushnotification_model->send_push_android($user['device_token'], "FindFellow",$notificationcounter, $ludetail["fname"]." has Liked you",$type=1,$fdetail);
									}
								}
								catch(Exception $e)
								{
									$this->SiteErrorLog->addData("Notification Error :- ".$e->getMessage(),$ludetail["id"]);
								}
							}
							else
							{
								$response["error"] = false;
								$response["message"] = "Dislike.";
							}
						}
						else
						{
						   $response["error"] = true;
						   $response["message"] = "Filed to send notification";
						}
					}
					else
					{
						$response["error"] = true;
						$response['error_code'] = "503";
						$response["message"] = "You have already sent friend request to this user";
					}
				}else if( empty($paid_superlike) ){
					$response["error"] = true;
					$response['error_code'] = "502";
					$response["message"] = "You have not Purchase the Subscription";
				}else{
					$response["error"] = true;
					$response['error_code'] = "501";
					$response["message"] = "You have day limit is over";
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		else
		{
			$response['error'] = true;
			$response['message'] = 'Filed to send notification';
		}
		$this->SendResponse($response);
	}
	/**
	 * user like function for webservices.
	 *  Function is used send friend request to particular user
	 * @access public
	 * @return void
	 */
	public function user_like()
	{	
		$required_fields=array('id', 'receive_user_id', 'status', 'AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
        	$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$senderid = $this->input->post('id');
				$receiverid = $this->input->post('receive_user_id');
				$check=$this->api_model->get_freienddata($senderid,$receiverid);
				if(empty($check))
				{
					$status = $this->input->post('status');
					$auth_token = $this->input->post('AuthToken');
					$reverse_check=$this->api_model->get_freienddata_reverse($receiverid,$senderid);
					if(!empty($reverse_check)){
						$result=$this->api_model->approvenotification(
							array("approved"=>"1",
								  "status"=>"1",
								  "modified_date"=>date("Y-m-d H:i:s")),
							array("send_user_id"=>$receiverid,
								  "receive_user_id"=>$senderid),
							array("receive_user_id"=>$receiverid,
								  "send_user_id"=>$senderid,
								  "modified_date"=>date("Y-m-d H:i:s"),
								  "created_date"=>date("Y-m-d H:i:s"),
								  "status"=>"1",
								  "approved"=>"1")
						);
						if($result)
						{
							$ludetail=$this->api_model->getUserData($senderid);
							$udetail=$this->api_model->getUserData($receiverid);
							$fdetail["friend_profileImg_url"]=$ludetail["profile_image"];
							$fdetail["friend_Fname"]=$ludetail["fname"];
							$fdetail["friend_Lname"]=$ludetail["lname"];
							$fdetail["friendid"]=$ludetail["id"];
							$users=$this->api_model->get_userslogin(array('user_id'=>$receiverid));
							$users1=$this->api_model->get_userslogin(array('user_id'=>$senderid));
							$fdetail1["friend_profileImg_url"]=$udetail["profile_image"];
							$fdetail1["friend_Fname"]=$udetail["fname"];
							$fdetail1["friend_Lname"]=$udetail["lname"];
							$fdetail1["friendid"]=$udetail["id"];
							$notificationcounter=$udetail["notificationcounter"]+1;
							$notificationcounter1=$ludetail["notificationcounter"]+1;
							try
							{
								foreach($users as $user)
								{
									if($user['device']=="ios")
										$this->Pushnotification_model->send_push($user['device_token'], "FindFellow",$notificationcounter, $ludetail["fname"]." has Liked you",$type=1,$fdetail);
									elseif($user['device']=="android")
										$this->Pushnotification_model->send_push_android($user['device_token'], "FindFellow",$notificationcounter, $ludetail["fname"]." has Liked you",$type=1,$fdetail);
								}
								
								foreach($users1 as $user1)
								{
									if($user1['device']=="ios")
										$this->Pushnotification_model->send_push($user1['device_token'], "FindFellow",$notificationcounter1, $udetail["fname"]." has Liked you",$type=1,$fdetail1);
									elseif($user1['device']=="android")
										$this->Pushnotification_model->send_push_android($user1['device_token'], "FindFellow",$notificationcounter1, $udetail["fname"]." has Liked you",$type=1,$fdetail1);
								}
							}
							catch(Exception $e)
							{
								$this->SiteErrorLog->addData("Notification Error :- ".$e->getMessage(),$ludetail["id"]);
							}
							$response["error"] = false;
							$response["message"] = "Notification has been sent successfully";
						}
						else
						{
						   $response["error"] = true;
						   $response["message"] = "Filed to send notification";
						}
					}else{				
						$result=$this->api_model->send_notification($senderid,$receiverid,$status,$auth_token);
						if($result)
						{
							$response["error"] = false;
							$response["message"] = "You have like to this user";
						}
						else
						{
						   $response["error"] = true;
						   $response["message"] = "Filed to send notification";
						}
					}
				}
				else
				{
					 $response["error"] = true;
					 $response["message"] = "You have already like to this user";
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		else
		{
			$response['error'] = true;
			$response['message'] = 'Filed to send notification';
		}
		$this->SendResponse($response);
	}
	/**
	 * user purchase function for webservices.
	 * Function is used for particluar key purchase user
	 * @access public
	 * @return void
	 */
	public function user_purchase()
	{	
		$required_fields=array('id', 'purchasekey', 'AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
        	$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
			if($userstatus)
            {
				$check_purchase=$this->api_model->get_user_purchase(array('userid'=>$this->input->post("id"),'purchasekey'=>$this->input->post("purchasekey")));
				if($this->input->post("purchasekey")=="PAID_AD"){
					$result=$this->api_model->enableAdd($this->input->post("id"),1,$this->input->post("AuthToken"));
				}
				if(empty($check_purchase)){
					$data=array(
						'userid'=>$this->input->post("id"),
						'purchasekey'=>$this->input->post("purchasekey"),
						'createdate'=>date('Y-m-d H:i:s'),
						'expirydate'=>date('Y-m-d H:i:s')
					);
					$this->api_model->user_purchase($data);
					$response["error"] = false;
					$response["message"] = "You have purchase the subscription is successfully";
				}else{
					//$data=array('expirydate'=>date('Y-m-d H:i:s'));
					//$this->api_model->update_user_purchase($data,array('userid'=>$this->input->post("id"),'purchasekey'=>$this->input->post("purchasekey")));
					$response["error"] = true;
					$response["message"] = "You have alredy subscription purchased";
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		else
		{
			$response['error'] = true;
			$response['error_code'] = "101";
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
	}
	/**
	 * get notification function for webservices.
	 *  
	 * @access public
	 * @return notification list
	 */
    public function getnotification()
	{
		$required_fields=array('id','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
		  	$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$userid = $this->input->post('id');
				$auth_token = $this->input->post('AuthToken');
				$result = $this->api_model->getNotification($userid,$auth_token);
				if ($result) 
				{
					$response["error"] = false;
					$response["message"] = "All Friend List";
					$response["body"] = $result;
				}
				else
				{
					$response["message"] = "Nohing found";
				}
            }
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		else
		{
			$response['error'] = true;
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);	
	}
	/**
	 * getNotificationCount function for webservices.
	 *  
	 * @access public
	 * @return notification Counter
	 */
	public function getNotificationCount()
	{
		$required_fields=array('id','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$userid = $this->input->post('id');
				$auth_token = $this->input->post('AuthToken');
				$result =$this->api_model->getNotificationCount($userid, $auth_token);
				if ($result) 
				{
					$response["error"] = false;
					$response["count"] = $result['count'];
				}
				else
				{
					$response["count"] = "0";
				}
            }
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);
	}
	/**
	 * update notification function for webservices.
	 *  Function is used update user notification
	 * @access public
	 * @return void
	 */
    public function updatenotification()
	{
		$required_fields=array('id','counter','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
			if($userstatus)
			{
				$userid = $this->input->post('id');
				$counter = $this->input->post('counter');
				$result= $this->api_model->updatenotification($userid,$counter);
				if ($result) 
				{
					$response["error"] = false;
					$response["message"] = "Notification counter has been updated!";
					$response["body"] = $result;
				} 
				else 
				{
                // user credentials are wrong
					$response['error'] = true;
					$response['message'] = 'Something went wrong. Please log in again.';
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }	
		}
		$this->SendResponse($response);
	}
	/**
	 * blockuser function for webservices.
	 *  Function is used block one user to its friend list
	 * @access public
	 * @return void
	 */
    public function blockuser()
	{
		$required_fields=array('id', 'blockid', 'blockstatus','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status){
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
			{
				$userid = $this->input->post('id');
				$blockid = $this->input->post('blockid');
				$blockstatus = $this->input->post('blockstatus');
				$auth_token = $this->input->post('AuthToken');
				$result = $this->api_model->blockUser($userid, $blockid, $blockstatus, $auth_token);
				if($result)
				{
					$response["error"] = false;
					$response["message"] = $result;
				}
				else
				{
					$response["message"] = "Nohing found";
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);
	}
	/**
	 * reporteuser function for webservices.
	 *  Function is used report user to admin for permanantly block
	 * @access public
	 * @return void
	 */
    public function reporteuser()
	{
		$required_fields=array('id', 'report_to_id','AuthToken');
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
			{
				$report_from_id = $this->input->post('id');
				$report_to_id = $this->input->post('report_to_id');
				$auth_token = $this->input->post('AuthToken');
				$result = $this->api_model->reporteUser($report_from_id, $report_to_id, $auth_token);
				if($result)
				{
					$response["error"] = false;
					$response["message"] = $result;
				}
				else
				{
					$response["message"] = "Nohing found";
				}
			}
			else
             {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);
	}
	/**
	 * users function for webservices.
	 *  Function is used fetch all users data
	 * @access public
	 * @return all users dsta
	 */
	public function users()
	{
		global $user_id;
		$response = array();
        // fetching all users
		$result = $this->api_model->getAllUsers();
		$response["error"] = false;
		$response['message'] = 'Users list.';
		$response["users"] =$result;
		$this->SendResponse($response);
	}
	/**
	 * userUpdateLatLong function for webservices.
	 *  Function is used update user latitude and longtitiude;
	 * @access public
	 * @return all users dsta
	 */
    public function userUpdateLatLong()
	{
	    $required_fields=array('id','location_lat', 'location_long','AuthToken');		
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status){
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
             if($userstatus)
             {
				$user_id = $this->input->post('id'); 
				$latitude = $this->input->post('location_lat');
				$longitude = $this->input->post('location_long');
				$auth_token = $this->input->post('AuthToken'); 
				$result = $this->api_model->userUpdateLatLong($user_id, $latitude, $longitude, $auth_token);
				if ($result != NULL) {
					$response["error"] = false;
					$response["message"] = "Location has been updated.";
					$response["user_id"] = $user_id;
					$response["latitude"] = $latitude;
					$response["longitude"] = $longitude;
				} 
				else 
				{
					$response["error"] = true;
					$response["message"] = "Something went wrong. Please log in again.";                
				} 
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);		
	}
	/**
	 * mutualFriends function for webservices.
	 *  Function is used to find mutual friend 
	 * @access public
	 * @return all users dsta
	 */
	public function mutualFriends()
	{
		$required_fields=array('id','receive_user_id','AuthToken');		
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$user_id = $this->input->post('id'); 
				$receive_user_id = $this->input->post('receive_user_id');
				$auth_token = $this->input->post('AuthToken');
				$result = $this->api_model->mutualFriends($user_id, $receive_user_id, $auth_token);
				if ($result != NULL) 
				{
					$response["error"] = false;
					$response["message"] = "Mutual Friend List";
					$response["mutualFriendList"] = $result;
				}
				else 
				{
					$response["error"] = true;
					$response["message"] = "0 Mutual friend";     
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);	
	}
	/**
	 * enableadd function for webservices.
	 *  Function is used to Enable/Disable advertise
	 * @access public
	 * @return all users dsta
	 */		
	public function enableadd()
	{
		try
		{
			$required_fields=array('id','enableadd','AuthToken');		
			$status=$this->verifyRequiredParams($required_fields);
			$response = array();
			if($status)
			{
				$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
				if($userstatus)
				{
					$userid = $this->input->post('id');
					$enableadd = $this->input->post('enableadd');
					$auth_token = $this->input->post('AuthToken');
					$result=$this->api_model->enableAdd($userid,$enableadd,$auth_token);
					if ($result) 
					{
						$response["error"] = false;
						$response["message"] = $result ;			
					}
					else
					{
						$response["message"] = "Nohing found";
					}
				}
				else
				{
					$response['error'] = true;
					$response['error_code'] = "101";
					$response['message'] = 'Something went wrong. Please log in again.';
				}
			}
			$this->SendResponse($response);	
		}
		catch(Exception $e)
		{
			$this->SiteErrorLog->addData("Api Error :- ".$e->getMessage(),$user_id);
			$this->SendResponse($response);
		}
	}
	/**
	 * getblockstatus function for webservices.
	 *  Function is used to fetch user block or not
	 * @access public
	 * @return all users dsta
	 */	
	public function getblockstatus()
	{
		$required_fields=array('id','AuthToken');		
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		if($status)
		{
			$userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
				$userid = $this->input->post('id');
				$auth_token = $this->input->post('AuthToken');
				$result=$this->api_model->getblockstatus($userid,$auth_token);
				if ($result) 
				{
				    $response["error"] = false;
				    $response["status"] = $result['status'];
					$response["all_account_ad"] = $result['acc_status'];
					$response["user_add"] = $result['user_add'];
		    	}
				else
				{
					$response["message"] = "No user found";
				}
			}
			else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Something went wrong. Please log in again.';
            }
		}
		$this->SendResponse($response);
	}
	 /**
	 * get_all_static function for webservices.
	 *  Function is used to fetch religion,ethnicity and question
	 * @access public
	 * @return all religion,ethnicity and question data
	 */	
	 public function get_all_static()
	 {
		$required_fields=array('language');		
		$status=$this->verifyRequiredParams($required_fields);
		if($status)
		{
			$lang = $this->input->post('language');
			$response = array();
			$religion=$this->api_model->get_all_religion($lang);
			$ethnicity=$this->api_model->get_all_ethnicity($lang);
			$question=$this->api_model->get_all_questions($lang);
			
			if ($religion && $ethnicity && $question) 
			{
				$response["error"] = false;
				$response["religion"] = $religion;
				$response["ethnicity"] = $ethnicity;
				$response["question"] = $question;
			}
			else
			{
				$response["error"] = true;
				$response["message"] = "No Static Data.";
			}
		}
		$this->SendResponse($response);
	 }
	 public function get_all_configuration()
	 {
		$setting=$this->admin_model->get_configuration();
		$response = array();
		if( !empty($setting)){
			$response["GOOGLE_PLACE_API_KEY"] = $setting[$this->findKey($setting,'key','GOOGLE_PLACE_API_KEY')]['value'];
			$response["FACEBOOK_KEY"] = $setting[$this->findKey($setting,'key','FACEBOOK_KEY')]['value'];
			$response["XMPP_ENABLE"] = $setting[$this->findKey($setting,'key','XMPP_ENABLE')]['value'];
			$response["APP_XMPP_SERVER"] = $setting[$this->findKey($setting,'key','APP_XMPP_SERVER')]['value'];
			$response["APP_XMPP_HOST"] = $setting[$this->findKey($setting,'key','APP_XMPP_HOST')]['value'];
			//$response["APP_XMPP_HOST"] = $setting[$this->findKey($setting,'key','APP_XMPP_HOST')]['value'];
			//$response["APP_XMPP_SERVER"] = $setting[$this->findKey($setting,'key','APP_XMPP_SERVER')]['value'];
			$response["XMPP_DEFAULT_PASSWORD"] = $setting[$this->findKey($setting,'key','XMPP_DEFAULT_PASSWORD')]['value'];
			
			//$response["PEM_FILE"] = $setting[$this->findKey($setting,'key','PEM_FILE')]['value'];
			$response["PUSH_ENABLE_SANDBOX"] = $setting[$this->findKey($setting,'key','PUSH_ENABLE_SANDBOX')]['value'];
			$response["PUSH_SANDBOX_GATEWAY_URL"] = $setting[$this->findKey($setting,'key','PUSH_SANDBOX_GATEWAY_URL')]['value'];
			$response["PUSH_GATEWAY_URL"] = $setting[$this->findKey($setting,'key','PUSH_GATEWAY_URL')]['value'];
			$response["ANDROID_FCM_KEY"] = $setting[$this->findKey($setting,'key','ANDROID_FCM_KEY')]['value'];
			$response["INSTAGRAM_CALLBACK_BASE"] = $setting[$this->findKey($setting,'key','INSTAGRAM_CALLBACK_BASE')]['value'];
			$response["INSTAGRAM_CLIENT_SECRET"] = $setting[$this->findKey($setting,'key','INSTAGRAM_CLIENT_SECRET')]['value'];
			$response["INSTAGRAM_CLIENT_ID"] = $setting[$this->findKey($setting,'key','INSTAGRAM_CLIENT_ID')]['value'];
			$response["adMobKey"] = $setting[$this->findKey($setting,'key','adMobKey')]['value'];
			$response["adMobVideoKey"] = $setting[$this->findKey($setting,'key','adMobVideoKey')]['value'];
			$response["RemoveAddInAppPurchase"] = $setting[$this->findKey($setting,'key','RemoveAddInAppPurchase')]['value'];
			$response["RemoveAddInAppBilling"] = $setting[$this->findKey($setting,'key','RemoveAddInAppBilling')]['value'];
			$response["PaidChatInAppBilling"] = $setting[$this->findKey($setting,'key','PaidChatInAppBilling')]['value'];
			$response["LocationInAppBilling"] = $setting[$this->findKey($setting,'key','LocationInAppBilling')]['value'];
			$response["SuperLikeInAppBilling"] = $setting[$this->findKey($setting,'key','SuperLikeInAppBilling')]['value'];
			$response["PaidChatInAppPurchase"] = $setting[$this->findKey($setting,'key','PaidChatInAppPurchase')]['value'];
			$response["LocationInAppPurchase"] = $setting[$this->findKey($setting,'key','LocationInAppPurchase')]['value'];
			$response["SuperLikeInAppPurchase"] = $setting[$this->findKey($setting,'key','SuperLikeInAppPurchase')]['value'];
			$response["TermsAndConditionsUrl"] = $setting[$this->findKey($setting,'key','TermsAndConditionsUrl')]['value'];
			$response["PAID_CHAT"] = $setting[$this->findKey($setting,'key','PAID_CHAT')]['value'];
			$response["PAID_LOCATION"] = $setting[$this->findKey($setting,'key','PAID_LOCATION')]['value'];
			$response["PAID_SUPERLIKE"] = $setting[$this->findKey($setting,'key','PAID_SUPERLIKE')]['value'];
			$response["PER_DAY_SUPERLIKE"] = $setting[$this->findKey($setting,'key','PER_DAY_SUPERLIKE')]['value'];
			$response["PAID_AD"] = $setting[$this->findKey($setting,'key','PAID_AD')]['value'];
			$response["PAID_VISITOR"] = $setting[$this->findKey($setting,'key','PAID_VISITOR')]['value'];
			$response["PaidVisitorInAppBilling"] = $setting[$this->findKey($setting,'key','PaidVisitorInAppBilling')]['value'];
			$response["PaidVisitorInAppPurchase"] = $setting[$this->findKey($setting,'key','PaidVisitorInAppPurchase')]['value'];
			
		}
		$this->SendResponse($response);
	 }
	 private function findKey($array, $field, $value){
		foreach($array as $key => $item)
		{
			if ( $item[$field] === $value )
				return $key;
		}
		return false;
	 }
	 	/**
	 * userPrefencesUpdate function for webservices.
	 * 
	 * @access public
	 * @return void
	 */
     public function saveInstagramImages()
     {
        $response = array();
        $required_fields=array('userid','url','AuthToken');
		$userid = $this->input->post('userid');
		$AuthToken = $this->input->post('AuthToken');
		$url = $this->input->post('url');
        $userstatus=$this->checkauthtoken($userid,$AuthToken);
        if($userstatus)
        {
				$result=$this->api_model->SaveInstaImages($userid,$url);
				if($result)
				{
					$response["error"] = false;
					$response['message'] = "Images have been stored successfully.";
				}
				else
				{
					$response['error'] = true;
					$response['message'] = "Something went wrong. Please log in again.";
				}
		}
		else
		{
            $response['error'] = true;
    	    $response['error_code'] = "101";
            $response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
    }
	/**
	 * userPrefencesUpdate function for webservices.
	 * 
	 * @access public
	 * @return void
	 */
     public function GetInstagramImages()
     {
        $response = array();
        $required_fields=array('userid','friendid','AuthToken');
		$userid = $this->input->post('userid');
		$friendid = $this->input->post('friendid');
		$AuthToken = $this->input->post('AuthToken');
        $userstatus=$this->checkauthtoken($userid,$AuthToken);
        if($userstatus)
        {
				$result=$this->api_model->GetUserSaveInstaImages($friendid);
				if($result)
				{
					if (isset($result['code'])) 
					{
							$response["error"] = false;
							$response['message'] = "No image found";
							$response['ErrorCode'] = 0;
					}
					else
					{
							$response["error"] = false;
							$response['message'] = "Instagram Images list";
							$response['InstaImages'] = $result;
					}
				}
				else
				{
					$response['error'] = true;
					$response['message'] = "Something went wrong. Please log in again.";
				}
		}
		else
		{
            $response['error'] = true;
    	    $response['error_code'] = "101";
            $response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
    }
    /*
    confirm_email function is to confirm user email*/
	public function send_email(){
		$email=$this->input->post("email");
    	$user=$this->api_model->get_users_row(array("email"=>$email));
    	if(empty($user)){
			$verification_code=rand(1000,9999);
			$this->api_model->delete_user_verification(array("email"=>$email));
			$i=$this->api_model->user_verification(array("email"=>$email,"verification_code"=>$verification_code));
			//echo "<pre>";
			//echo $this->db->last_query();die;
			if($i){
				$this->load->library('email');
				$this->email->from('nirav.tandel@potenzaglobalsolutions.com', 'Cupidlove');
				$this->email->to($email);
				$this->email->subject('Verify Account');
				$this->email->message("Your verification code is : ".$verification_code);
				$this->email->send();
				$response['error'] = false;
		        $response['message'] = 'Verification code has been sent to your registered Email address..';
				$response['verification_code'] = $verification_code;
			}
			else{
				$response['error'] = true;
				$response['message'] = 'Verification could not be sent.';
			}
    	}
    	else{
    		$response['error'] = true;
			$response['message'] = 'User has already registered with this email address.';
    	}
		$this->SendResponse($response);
	}
    public function email_verification(){
		$email=$this->input->post("email");
		$verification_code=$this->input->post("verification_code");
    	$user=$this->api_model->get_users_verification(array("email"=>$email,"verification_code"=>$verification_code));
		$response = array();
		if($user){
			$response['error'] = false;
            $response['message'] = 'User has been verified.';
			$this->api_model->update_user_verification(array("status"=>1),array("email"=>$email));
			//$this->api_model->delete_user_verification(array("email"=>$email));
		}
		else{
			$response['error'] = true;
    	    $response['error_code'] = "0";
            $response['message'] = 'Invalid verification code.';
		}
		$this->SendResponse($response);
    }
	/*
		Remove Gallery Images
	*/
	public function delete_galleryimage(){
		$img_key=$this->input->post("key");
		$user_id=$this->input->post("user_id");
		$AuthToken=$this->input->post("AuthToken");
		$required_fields=array("user_id","AuthToken","key");   
		$status=$this->verifyRequiredParams($required_fields);
		$response = array();
		$validAuthToken=$this->checkauthtoken($user_id,$AuthToken);
		if(!empty($validAuthToken)){
			if($status){
				$i=$this->api_model->remove_galleryimage($img_key,$user_id);
				if($i){
					$response['error'] = false;
					$response['message'] = 'Image has been removed.';
				}
				else{
					$response['error'] = true;
					$response['message'] = 'Something went wrong. Please log in again.';
				}
			}
			else{
				$response["error"] = true;
				$response["message"] = "Gallery could not be updated.";
			}
		}
		else{
			$response['error'] = true;
			$response['error_code'] = "101";
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
	}
	/*
		Update Profile Information
	*/
	public function update_profile(){
		$response = array();
		$required_fields=array("id","AuthToken","email","lname","fname","education","profession","dob","gender","about","height","religion");   
		$status=$this->verifyRequiredParams($required_fields);
		$validAuthToken=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
		if(!empty($validAuthToken)){
			if($status){
				$i=$result=$this->api_model->api_user_update($this->input->post(),array("id"=>$this->input->post("id")));
				if($i){
					$response['error'] = false;
					$response['message'] = 'Your profile has been updated!';
				}
				else{
					$response['error'] = true;
					$response['message'] = 'Something went wrong. Please log in again.';
				}
			}
			else{
				$response["error"] = true;
				$response["message"] = "Profile could not be updated.";
			}
		}
		else{
			$response['error'] = true;
			$response['error_code'] = "101";
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
	}
	/*Visiter's info*/
	public function add_visiter($user_id=null, $viewer_id=null){
		if($user_id!=$viewer_id){
			$data['user_id']=$user_id;
			$data['viewer_id']=$viewer_id;
			$data['time']=date("Y-m-d H:i:s");
			$condition=array("user_id"=>$user_id,"viewer_id"=>$viewer_id);
			$viewer=$this->api_model->get_users_row(array("id"=>$user_id));
			$data['viewer_username']=$viewer['fname'].$viewer['lname'];
			$data['viewer_profile']=$viewer['profile_image'];
			if($this->api_model->get_vister($condition)) {
				$i=$this->api_model->update_visiter(array("time"=>date("Y-m-d H:i:s")),$condition);
			}
			else{
				$i=$this->api_model->add_visiter($data,array("user_id"=>$user_id));
			}
		}
	}
	public function get_visters($start=null,$limit=null){
		$response = array();
		$required_fields=array("AuthToken","user_id");   
		$validAuthToken=$this->checkauthtoken($this->input->post("user_id"),$this->input->post("AuthToken"));
		$status=$this->verifyRequiredParams($required_fields);
		if(!empty($validAuthToken)){
			if($status){
				$data=$this->api_model->get_visters(array("user_id"=>$this->input->post("user_id")),$start,$limit);
				if(!empty($data)){
					$response['error'] = false;
					$response['message'] = '';
					$response['data'] = $data;
				}
				else{
					$response['error'] = true;
					$response['message'] = 'No Data Found';
					$response['data'] = $data;
				}
			}
			else{
				$response['error'] = true;
				$response['error_code'] = "101";
				$response['message'] = 'Something went wrong. Please log in again.';
			}
		}
		else{
			$response['error'] = true;
			$response['error_code'] = "101";
			$response['message'] = 'Something went wrong. Please log in again.';
		}
		$this->SendResponse($response);
	}
	/*For Status Story*/
	public function addstory()
    {
        $required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
        $response=array();
        if($status)
        {
            $userstatus=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
            if($userstatus)
            {
               $files=array_keys($_FILES);
			    $user=$this->api_model->getUserData($this->input->post("id"));
                $imgkey=key($_FILES);
				$user_id=$user["id"];
			    foreach($files as $imgkey)
                {

					if($_FILES[$imgkey]["name"]!="")
					{	
						$unlinkimg="";
						if($imgkey=="img1")
						{
							$unlinkimg=$user["profile_image"];
						}
						else
						{
							$abc=$this->api_model->get_user_story(array("userid"=>$user_id,"img_key"=>$imgkey));
							if(!empty($abc["img_url"]))
							{	 
								$unlinkimg=$abc["img_url"];
							}
						}
						if(file_exists(base_url($unlinkimg)))
							unlink(base_url($unlinkimg));
					}
					$config['upload_path']= './uploads/story/';        
					$config['allowed_types']= 'gif|jpg|png|jpeg';
					$config['overwrite'] = TRUE;					
					$this->load->library('upload');
					$this->upload->initialize($config);
					if (!($this->upload->do_upload($imgkey)))
                    {
                        $data = $this->upload->display_errors();
                        $response['error'] = true;
                        $response['message'] = 'File could not be uploaded!. Please try again. '.$data;
                    }
                    else
                    {
					    $file_data = $this->upload->data();
						$source_path = './uploads/story/'.$file_data["file_name"];
						$target_path = './uploads/thumbnail/'.$file_data["file_name"];
						$thumb = PhpThumbFactory::create($source_path);
						$thumb->adaptiveResize(280, 250);
						$thumb->save($target_path, 'jpg');
						
					        $condition=array("userid"=>$user_id,"img_key"=>$imgkey);
                            $abc=$this->api_model->get_user_story(array("userid"=>$user_id,"img_key"=>$imgkey));
                            if($abc)
                            {
                                $data=array("img_url"=>$file_data["file_name"]);
                        		$this->api_model->change_story_images($data,$condition);   
                            }
                            else
                            {
								$data=array(
									"userid"=>$user_id,
									"img_key"=>$imgkey,
									"img_url"=>$file_data["file_name"],
									"created"=>date("Y-m-d H:i:s"),
									"text"=>$this->input->post("text")
								);	
                                $this->api_model->add_user_story($data);
                            }
                        $user=$this->api_model->getUserData($this->input->post("id"));
                        $imgs["img1"]=$user["profile_image"]; 
                        $user_gellary=$this->api_model->get_user_story(array("userid"=>$user["id"]));
						foreach($user_gellary as $gimg)
						{
							if($gimg["img_url"]!="")
								$imgs[$gimg["img_key"]]=$gimg["img_url"];
						}
                    }
                }
                $user=$this->api_model->getUserData($this->input->post("id"));
                $user_story=$this->api_model->get_user_story(array("userid"=>$user["id"]));
			    $response["story"]=$user_story;
			    $response["user"]=$user;
                //response for success
                $response["error"] = false;
				$response['message'] = "";	
            }
            else
            {
                $response['error'] = true;
                $response['error_code'] = "101";
                $response['message'] = 'Session is expired';
            }    
        }
        else
        {
       	    $response['error'] = true;
            $response['error_code'] = "102";
			$response['message'] = 'Missing fields';
        }
        //echo "hello";
        //print_r($response);
        $this->SendResponse($response);        
    }
    public function getuserStory(){
    	$required_fields=array("id","AuthToken");      
        $status=$this->verifyRequiredParams($required_fields);
		$validAuthToken=$this->checkauthtoken($this->input->post("id"),$this->input->post("AuthToken"));
        $response=array();
        if($status)
        {
	        if($validAuthToken)
	        {
	        	$user_id=$this->input->post("id");
	        	$user=$this->api_model->getUserData($user_id);
	        	$condition=array("user_id"=>$user_id);
	        	$friends=$this->api_model->matchFriends($user_id);
	        	$story=$this->api_model->get_friend_story($friends);
	        	foreach ($friends as $s) {
	        		$story=$this->api_model->get_user_story(array("userid"=>$s));
	        		if($s!=$user_id && !empty($story)){
		        		$u=array("friend_detail"=>$this->api_model->getUserData($s));
		        		$u["friend_detail"]["story"]=$story;
		        		//$user_detail=array_merge($u,$u1);
		        		$response['friend'][]=$u;
		        		//$response['user'][]["user_detail"]["story"]=$this->api_model->get_user_story(array("userid"=>$s));	
	        		}
	        	}
	        	$user_story=$this->api_model->get_user_story(array("userid"=>$user_id));
	        	if(!empty($user_story)){
		        	$response['user']=$user;
		        	$response['user']['story']=$user_story;
	        	}
	            $response["error"] = false;
				$response['message'] = "";
				//$response['story'] = $story;
				//$response['data'] = $story;
				//$response['friends'] = $friends;
				//$response['user'] = $user;
	        }
	        else
	        {
	            $response['error'] = true;
	            $response['error_code'] = "101";
	            $response['message'] = 'Session is expired';
	        }  
        } 
        else
        {
            $response['error'] = true;
            $response['error_code'] = "101";
			$response['message'] = 'Could not get Story. Invalid Username/Password';
        }  
    	$this->SendResponse($response); 
    } 
}