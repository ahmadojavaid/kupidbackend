<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Api_model extends CI_Model {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	/**
	 * logout function.	 
	 * @access public
	 * @return void
	 */
	public function SaveInstaImages($userid,$arrImagesurl)
	{
		$where=$this->db->where(array('userid' => $userid));
		$this->db->delete('instaimages');
		for ($i = 0; $i < count($arrImagesurl); $i++)
        {
			$this->db->insert('instaimages',array('userid' => $userid,'url'=> $arrImagesurl[$i]));                	
        }
		return true;
	}
	/**
	 * login function.
	 * @param email,Password
	 * @access public
	 * @return void
	 */
	public function login($email,$password)
	{
		$this->db->select('id,password');
		$this->db->from('users');
		$this->db->where('email', $email);
		$hash = $this->db->get()->row();
		if(!empty($hash))
		{
			$hashpassword=$hash->password;		
			$isverify=$this->verify_password_hash($password, $hashpassword);
			if($isverify)
				return (!empty($hash->id)) ? $hash->id : 0;
			else
				return false;
		}
		else
			return false;
	}
	/**
	 * userlogin function.
	 * @param data,table
	 * when user login with ios or android then used 
	 * @access public
	 * @return void
	 */
	public function userlogin($data,$table)
	{
		return $this->db->insert($table,$data);
	}
	/**
	 * logout function.	 
	 * @access public
	 * @return void
	 */
	public function logout($condition)
	{
		$where=$this->db->where($condition);
		$this->db->delete('userlogin');
		return $this->db->affected_rows();
	}
	/**
	 * get_userslogin function.	
	 * @param $condition
	 * @access public
	 * @return users list who is online
	 */
	public function get_userslogin($condition)
	{
		$this->db->where($condition);
		$result=$this->db->get("userlogin");
		return $result->result_array();
	}
	/**
	 * fetch_authtoken function.	
	 * @param $device_token
	 * @access public
	 * @return authtoken 
	 */
	public function fetch_authtoken($device_token,$id)
	{
		$this->db->where('user_id',$id);
		$this->db->where('device_token',$device_token);
		$result=$this->db->get("userlogin");
		$result=$result->row_array();
		return $result['AuthToken'];
	}
	/**
	 * hash_password function.	
	 * @param $password
	 * @access public
	 * @return incrypt_password
	 */
	public function hash_password($password) 
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}
	/**
	 * update_token function.	
	 * @param id,data
	 * @access public
	 * @return void
	 */
	public function update_token($id,$data)
	{
		$this->db->where('id',$id);
		if($this->db->update('users',$data))
			return true;
		else
			return false;
	}
	/**
	 * fetch_language function.	
	 * @access public
	 * @return default Language Row
	 */
	public function fetch_language()
	{
		$this->db->select("site_setting.*,language.rtl");
		$this->db->where('mode','default_language');
		$this->db->join("language","language.name=site_setting.status");
		$result=$this->db->get('site_setting')->row_array();
		return $result;
	}
	/**
	 * Getsaved Instagram images function.	
	 * @access public
	 * @return multiple Language provided by admin
	 */
	public function GetUserSaveInstaImages($userid){
		$this->db->select(array('url'));
		$this->db->from('instaimages');
		$this->db->where('userid',$userid);
		$result=$this->db->get()->result_array();
		if (count($result)>0) 
		{
			return $result;			
		}
		else
		{
			$result['code']=101;
			return $result;
		}
	}
	/**
	 * all_language function.	
	 * @access public
	 * @return multiple Language provided by admin
	 */
	public function all_language()
	{
		$this->db->select(array('name','rtl'));
		$this->db->from('language');
		$this->db->where('status',1);
		return $result=$this->db->get()->result_array();	
	}
	/**
	 * verify_password_hash function.	
	 * @param password,hash
	 * @access public
	 * @return match password status
	 */
	private function verify_password_hash($password, $hash) 
	{
		return password_verify($password, $hash);
	}
	/**
	 * api_user_update function.	
	 * @param data,condition
	 * @access public
	 * @return updated status
	 */
	public function api_user_update($data,$condition)
	{
		if(isset($data['AuthToken']))
				unset($data['AuthToken']);
		if(isset($data['device_token']))
				unset($data['device_token']);
		$this->db->where($condition);
		if($this->db->update("users",$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * create_user_fb function.	
	 * @param Post data from view page
	 * @access public
	 * @return user id fetch from facebook
	 */
    public function create_user_fb($data = array())
	{
		if($data)
        {
			$dob=str_replace("/","-",$data['dob']);
			$dob = date("Y-m-d",strtotime($dob));
            $fb_id="";
            if($this->input->post("fb_id")!="")
            {
                $fb_id=$this->input->post("fb_id");
            }
            $sql = array("fname"=>$data['fname'], 
            	"lname"=>$data['lname'], 
            	"email"=>$data['email'], 
            	"dob"=> $dob, 
            	"about"=>$data['about'], 
            	"gender"=>$data['gender'], 
            	"education"=>$data['education'], 
            	"profession"=> $data['profession'], 
                "fb_id"=>$fb_id,
                "location_lat"=>$data['location_lat'],
                "location_long"=>$data['location_long'],
            	"access_location"=> $data['access_location'], 
            	"created_date"=>date('Y-m-d H:i:s'), 
               	"modified_date"=>date('Y-m-d H:i:s' ));
				$this->db->insert("users",$sql);
				$id = $this->db->insert_id();				
				if($id)
				{
					$data=array(
						'user_id'=>$id,
						'device_token'=>$data['device_token'],
						'AuthToken'=>md5(uniqid(rand(), true)),
						'device'=>$data['device'],
						'login_time'=>date('H:i:s')
					);
					$value=$this->api_model->userlogin($data,$table="userlogin");
					if($value)
							return $id;
				}
				else
				{
					return null;
				}
		}
		return FALSE;
	}
    /**
	 * create_user function.	
	 * @param Post data from view page
	 * @access public
	 * @return user id who is register by email
	 */
    public function create_user($data = array()) 
	{
		if ($data)
        {
            $dob=str_replace("/","-",$data['dob']);
			$dob = date("Y-m-d",strtotime($dob));
        	$res=$this->db->query("SELECT status FROM site_setting where mode='chk_new'");
			$res=$res->row_array();
			$status=$res['status'];
            $fb_id="";
            if($this->input->post("fb_id")!="")
            {
                $fb_id=$this->input->post("fb_id");
            }
            $sql = array("fname"=>$data['fname'], 
            	"lname"=>$data['lname'], 
            	"email"=>$data['email'], 
				"password"=>$this->hash_password($data['password']), 
            	"dob"=> $dob, 
            	"about"=>$data['about'], 
            	"gender"=>$data['gender'], 
            	"education"=>$data['education'], 
            	"profession"=> $data['profession'], 
            	"religion"=> $data['religion'], 
            	"height"=> $data['height'], 
            	"kids"=>$data['kids'], 
                "fb_id"=>$fb_id,
                "location_lat"=>$data['location_lat'],
                "location_long"=>$data['location_long'],
            	"date_pref"=> $data['date_pref'], 
            	"gender_pref"=>$data['gender_pref'], 
            	"max_age_pref"=> $data['max_age_pref'] , 
            	"min_age_pref"=>$data['min_age_pref'], 
            	"max_dist_pref"=> $data['max_dist_pref'] , 
            	"min_dist_pref"=> $data['min_dist_pref'] , 
            	"ethnicity"=> $data['ethnicity'], 
            	"que_id"=> $data['que_id'], 
            	"que_ans"=> $data['que_ans'], 
            	"access_location"=> $data['access_location'], 
				"enableAdd"=>$status,
            	"created_date"=>date('Y-m-d H:i:s'), 
               	"modified_date"=>date('Y-m-d H:i:s' ) );
				$this->db->insert("users",$sql);
				$id = $this->db->insert_id();
			if($id)
			{
				$data=array(
					'user_id'=>$id,
					'device_token'=>$data['device_token'],
					'AuthToken'=>md5(uniqid(rand(), true)),
					'device'=>$data['device'],
					'login_time'=>date('H:i:s')
				);
				$value=$this->api_model->userlogin($data,$table="userlogin");
				if($value)
						return $id;
			}
			else
			{
				return null;
			}
		}
		return FALSE;
	}
	/**
	 * chat function.	
	 * @param id
	 * @access public
	 * @return send message to particular user 
	 */
    public function chat($id)
    {
		$sql="select f.*,u.ejuser from friends f,users u where f.send_user_id=$id  and f.approved='1' and f.receive_user_id=u.id and u.status != '1'";
		
		$result=$this->db->query($sql);
		$results=$result->result_array();
		if(empty($results)){
			$sql="select f.*,u.ejuser from friends f,users u where f.receive_user_id=$id  and f.approved='1' and f.send_user_id=u.id and u.status != '1'";
			$result=$this->db->query($sql);
			$results=$result->result_array();
		}
        $chats=array();
        $i=0;
        foreach($results as $result)
        {
            if($result["receive_user_id"]==$id)
            {
                $fid=$result["send_user_id"];
            }
            else
            {
                $fid=$result["receive_user_id"];
			}
            $user=$this->getUserData($fid);
            $chats[$i]["fid"]=$user["id"];
            $chats[$i]["fname"]=$user["fname"];
            $chats[$i]["lname"]=$user["lname"];
            $chats[$i]["profile_image"]=$user["profile_image"];
			$chats[$i]["ejuser"]=$user["ejuser"];
            $i++;
        }
        return $chats;
    }
    //send messege
   public function sendmessage($id,$fid,$msg)
   {
        $data=array();
   }
    /**
	 * dislike function.	
	 * @param condition
	 * @access public
	 * @return void 
	 */
   public function dislike($condition=null)
   {
	   if($condition)
	   {
		  return $this->db->delete("friends",$condition);
	   }
   }
   /**
	 * userUnfriend function.	
	 * @param id,fid
	 * @access public
	 * @return void 
	 */
    public function userUnfriend($id,$fid)
    {
        return $this->db->query("delete from friends where (send_user_id=$id and receive_user_id=$fid) or( send_user_id=$fid and receive_user_id=$id) ");
	}
	/**
	 * add_gallery_images function.	
	 * @param data
	 * @access public
	 * @return void
	 */
    public function add_gallery_images($data)
    {
        $this->db->insert("usergallery",$data);
    }
    public function add_user_story($data)
    {
        $this->db->insert("user_story",$data);
    }
	/**
	 * User Purchase function.	
	 * @param data
	 * @access public
	 * @return void
	 */
    public function user_purchase($data)
    {
        $this->db->insert("user_purchase",$data);
    }
	public function get_user_purchase($condition=null)
    {
        if($condition)
        {
            $this->db->where($condition);
	    }
        $result=$this->db->get("user_purchase");
        return $result->row_array();
    }
	public function get_login_user_purchase($condition=null)
    {
        if($condition)
        {
            $this->db->where($condition);
	    }
        $result=$this->db->get("user_purchase");
        return $result->result_array();
    }
	 public function update_user_purchase($data,$condition)
    {
        $this->db->where($condition);
        $this->db->update("user_purchase",$data);
    }
	/**
	 * change__gallery_images function.	
	 * @param data,condition
	 * @access public
	 * @return void
	 */
    public function change__gallery_images($data,$condition)
    {
        $this->db->where($condition);
        $this->db->update("usergallery",$data);
    }
    public function change_story_images($data,$condition)
    {
        $this->db->where($condition);
        $this->db->update("user_story",$data);
    }
	/**
	 * get_user_gellary function.	
	 * @param condition
	 * @access public
	 * @return gallery images
	 */
    public function get_user_gellary($condition=null)
    {
        if($condition)
        {
            $this->db->where($condition);
	    }
        $result=$this->db->get("usergallery");
        return $result->result_array();
    }
    public function get_user_story($condition=null)
    {
        if($condition)
        {
            $this->db->where($condition);
	    }
        $result=$this->db->get("user_story");
        return $result->result_array();
    }
    public function get_friend_story($friends=null)
    {
        if(!empty($friends))
        {
        	foreach ($friends as $friend) {
            	$this->db->or_like("userid",$friend);
        	}
	    }
        $result=$this->db->get("user_story");
        //print_r($this->db->last_query());
        //die;
        return $result->result_array();
    }

	/**
	 * update_authtoken function.	
	 * @param id
	 * @access public
	 * @return boolean status
	 */
	public function update_authtoken($id)
	{
		$data=array('AuthToken'=>md5(uniqid(rand(), true)));
		$this->db->where('id',$id);
		if($this->db->update('users',$data))
			return true;
		else
			return false;
	}
	/**
	 * checkmail function.	
	 * @param email
	 * @access public
	 * @return message 
	 */
	public function checkmail($email)
	{
		$this->db->where('email',$email);
		$res=$this->db->get("users")->num_rows();
		if($res==1)
		{
			$id=$this->get_user_id_from_email($email);                        
            if(!empty($id))
            {
                $token=md5(uniqid(rand(), true));
                if($this->set_token($token,$id))
                {                    
					$link="For Reset Your Password.<a href='".base_url()."user/reset_pass/".$token."/".$id."'>Click Here</a>";
					$this->email->from('FindFellow@potenzaglobalsolutions.com', 'FindFellow');
					$this->email->to($email);
					$this->email->subject('Reset Password');
					$this->email->message($link);
					$this->email->set_mailtype('html');					
					if($this->email->send())
                    {
                    	$message="We have sent you an email containing password reset information.  Please follow it to reset your password.";
						return $message;;                                                                      
                    }
                    else
					{
						$message="Mail not Send because of some issue...!!!";
						return $message;
					}
                }
            }
            else
            {
               $message="Please enter valid email address and try again.";
			   return $message;
            } 
		}
	}
	/**
	 * get_user_id_from_email function.	
	 * @param email
	 * @access public
	 * @return id
	 */
	public function get_user_id_from_email($email) 
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		return $this->db->get()->row('id');
	}
		/**
	 * set_token function.	
	 * @param token,id
	 * @access public
	 * @return boolean status
	 */
	public function set_token($token,$id)
    {
        $this->db->where("id",$id);
        if($this->db->update("users",array("pass_token"=>$token)))
            return true;
        else
            return false;
    }
	/**
	 * getUserData function.	
	 * @param id
	 * @access public
	 * @return userdata
	 */
	public function getUserData($id)
	{
		$this->db->where('id',$id);
		$result=$this->db->get('users')->row_array();
		$birthday=$result['dob'];
		$from = new DateTime($birthday);
		$to   = new DateTime('today');
		$age= $from->diff($to)->y;
		$result['age']=$age;	
		return $result;
	}
	/**
	 * get_users_row function.	
	 * @param condition
	 * @access public
	 * @return single row of user
	 */
	public function get_users_row($condition)
	{
			$this->db->where($condition);
			$result=$this->db->get("users");
			return $result->row_array();
	}
	/**
	 * get_users_result function.	
	 * @param condition
	 * @access public
	 * @return userdata
	 */
	public function get_users_result($condition=null)
	{
			if($condition)
			{
					$this->db->where($condition);
			}
			$result=$this->db->get("users");
			return $result->result_array();
	}
	/**
	 * get_freienddata function.	
	 * @param senderid,receiverid
	 * @access public
	 * @return friend list
	 */
	public function get_freienddata($senderid,$receiverid)
	{
		$this->db->where(array('send_user_id'=>$senderid,'receive_user_id'=>$receiverid));
		return $this->db->get("friends")->row_array();
	}
	/**
	 * one day superlike function.	
	 * @param senderid,receiverid
	 * @access public
	 * @return friend list
	 */
	public function superlike($senderid)
	{
		$date = new DateTime("now");
		$curr_date = $date->format('Y-m-d');		
		$query = "SELECT count(id) FROM `friends` WHERE `created_date` LIKE '$curr_date%' AND `send_user_id` = '$senderid'";
		return $this->db->query($query)->row_array();
	}
	public function paid_check_superlike($senderid)
	{
		$this->db->where(array('userid'=>$senderid,'purchasekey'=>'PAID_SUPERLIKE'));
		$query=$this->db->get("user_purchase");
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	/**
	 * get_freienddata reverse function.	
	 * @param senderid,receiverid
	 * @access public
	 * @return friend list
	 */
	public function get_freienddata_reverse($receiverid,$senderid)
	{
		$this->db->where(array('send_user_id'=>$receiverid,'receive_user_id'=>$senderid));
		return $this->db->get("friends")->row_array();
	}
	/**
	 * userCheckFilter function.	
	 * @param user_id,latitude,longitude,start,auth_token
	 * @access public
	 * @return list of users match with preferences
	 */
	public function userCheckFilter($user_id,$latitude,$longitude,$start,$auth_token)
	{
		$result=$this->get_data(array('id'=>$user_id),"users");
		$Gender = $result['gender'];
		$MinAgePref = $result['min_age_pref'];
		$MaxAgePref = $result['max_age_pref'];
		$DatePreferceIdOrder = $result['date_pref'];
		$MinDistance = $result['min_dist_pref'];
		$MaxDistance = $result['max_dist_pref'];
		$gender_pref= $result['gender_pref'];
		$access_location=$result['access_location'];

		$reg_pref=$result['religion_pref'];
		$eth_pref=$result['ethnicity_pref'];

		$start=$start-1;
		if($access_location == 1)
		{
			$select="select *,( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) as distance FROM `users` ";
			$where_user=" WHERE id != $user_id  AND `status` != '1' AND id not in (select f.receive_user_id from friends f where f.send_user_id=$user_id) AND id not in (select f.send_user_id from friends f where f.receive_user_id=$user_id) AND id not in (select b.blockid from blockuser b where b.userid=$user_id) AND id not in (select b.blockid from blockuser b where b.userid=$user_id)";
			$where_gender_age=" AND (`Gender` = '{$gender_pref}') AND (TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())>={$MinAgePref} AND TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())<={$MaxAgePref}) ";
			$where_distance=" AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) >= $MinDistance AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) <= $MaxDistance";

			$sample_data=$this->get_sampledata();
			$where_sample;
			if($sample_data['status']==0){
				$where_sample=" AND users.sampledata= 0 ";
			}
			else{
				$where_sample=" ";
			}
			if(!empty($reg_pref)){
				$where_reg=" AND `users`.`religion` in ($reg_pref) ";
			}else{
				$where_reg="";
			}
			if(!empty($eth_pref)){
				$where_eth=" AND `users`.`ethnicity` in ($eth_pref) ";
			}else{
				$where_eth="";
			}

			$where=$where_user." ".$where_gender_age." ".$where_distance." ".$where_sample." ".$where_reg." ".$where_eth;

			$order=" order by distance asc";
			$limit=" limit $start,5";
			$sql=$select." ".$where." ".$order." ".$limit;
		
			$results = $this->db->query($sql)->result_array();
			$fusers = array();
			$results1 = $this->get_data(array('send_user_id'=>$user_id),"friends");
			$users = array();
			$cnt=count($results);
			$image=array();
			if($cnt > 0)
			{
				$i=0;
				foreach($results as $row)
				{
					$imgs=0;
					$query="select count(distinct(f2.receive_user_id)) as mcount from friends f2 where f2.send_user_id=".$user_id." and f2.approved='1' and f2.status='1' and f2.receive_user_id in ( select f1.receive_user_id from friends f1 where f1.send_user_id=".$row['id']." and f1.approved='1' and f1.status='1' )";
					$mutualFriend = $this->db->query($query);
					$mutualFriend=$mutualFriend->result_array();
					$mfriend=$mutualFriend[0]['mcount'];
					if($mfriend> 0)
					{  
					 $results[$i]['mutual_friend']=$mfriend;
					}
					else
					{
						$results[$i]['mutual_friend']=0;
					}
					if(!empty($row['profile_image']))
					{
						$results[$i]['gallary']['img1']=$row['profile_image'];
						$imgs++;								
					}
					$usergallary = $this->db->query("SELECT * from usergallery where user_id=".$row['id']);
					$usergallary=$usergallary->result_array();
					$gcount=count($usergallary);
					if($gcount > 0 )
					{
						$j=2;
						foreach($usergallary as $img)
						{
							if(!empty($img['img_url']))
								$results[$i]['gallary'][$img['img_key']]=$img['img_url'];
							$j++;
							$imgs++;
						}							
					}	
					$results[$i]['no_of_images']=$imgs;
					if($user_id!=$row['id']){
						if(!in_array($row['id'], $fusers))
						{
						  $users[] = $row;
						}
					}
					$birthday=$row['dob'];
					$from = new DateTime($birthday);
					$to   = new DateTime('today');
					$age= $from->diff($to)->y;
					$results[$i]['age']=$age;
					if(empty($results[$i]['gallary']))
						$results[$i]['gallary']["img1"]="default.png";
					$i++;
				}
			}
			else 
			{
				return false;
			}
			$total_record = count($users);
			return $results;
		}
		else
		{
			return null;
		}
	}
	/**
	 * approve notification function.	
	 * @param data,condition,insdata
	 * @access public
	 * @return boolean status
	 */
    public function approvenotification($data,$condition=null,$insdata)
    {
		if($insdata!=null)
		{
			$this->db->insert("friends",$insdata);
		}			
        if($condition!=null)
        {
            $this->db->where($condition);
            return $this->db->update("friends",$data);
        }
        else
        {
            return false;
        }
    }
	/**
	 * get_data function.	
	 * @param condition,table
	 * @access public
	 * @return particular user data
	 */
	public function get_data($condition,$table)
	{
			$this->db->where($condition);
			$result=$this->db->get($table);
			return $result->row_array();
	}  
	/**
	 * send_notification function.	
	 * @param senderid,receiverid,status,auth_token
	 * @access public
	 * @return user data who send notification
	 */
    public function send_notification($senderid,$receiverid,$status,$auth_token)
	{
		$response = array();
		$data=array(
			'send_user_id'=>$senderid,
			'receive_user_id'=>$receiverid,
			'status'=>$status,
			'created_date'=>date('Y-m-d H:i:s'),
			'modified_date'=>date('Y-m-d H:i:s')
		);
		$result=$this->db->insert("friends",$data);
		if($result)
		{
			$res=$this->db->query("SELECT * FROM users WHERE `id` = '$receiverid' ");
			$res=$res->row_array();
			$ucount=count($res);
			if($ucount >0)
			{		
				$bedge=$this->getusernotificationcount($receiverid);
				$results2 = $this->db->query("SELECT fname from users WHERE  id =".$senderid);
				$results2=$results2->result_array();				
			}
			$response[] =  $res;
			return $response;
		}
	}
	/**
	 * getusernotificationcount function.	
	 * @param id
	 * @access public
	 * @return notification counter
	 */	
    public function getusernotificationcount($id) 
	{
        $results = $this->db->query("SELECT notificationcounter from users WHERE  id =".$id);
		$results=$results->row_array();
		$rcount=count($results);
        if($rcount > 0)
        {
			$user = $results;	
        }
        return $user;
    }
	/**
	 * get_device_token function.	
	 * @param user_id
	 * @access public
	 * @return user data
	 */	
    public function get_device_token($user_id)
	{
		$this->db->from('userlogin');
		$this->db->where('user_id', $user_id);
		return $this->db->get()->result_array();
	}
	/**
	 * getNotification function.	
	 * @param userid,auth_token
	 * @access public
	 * @return notification list who send login user
	 */	
   	public function getNotification($userid,$auth_token)
	{		
		$response = array();
		$results = $this->db->query("SELECT * from users WHERE id =".$userid);
		$results=$results->row_array();
		$rcount=count($results);
		if($rcount > 0)
		{
			$latitude=$results['location_lat'];
			$longtitude=$results['location_long'];
		}
		$stmt = $this->db->query("SELECT friends.*,users.ejuser, users.fname, users.lname, users.profile_image,users.location_lat, users.location_long FROM `friends` LEFT JOIN users ON friends.send_user_id = users.id WHERE friends.receive_user_id=".$userid." and friends.status='1' and friends.approved ='2' and users.status !='1'");
		$stmt=$stmt->result_array();
		$scount=count($stmt);
		if($scount > 0)
		{
			foreach($stmt as $row)
			{
			    $latitude1=$row['location_lat']; 
                $longtitude1=$row['location_long']; 
				$theta = $longtitude - $longtitude1;
	     		$dist = sin(deg2rad($latitude)) * sin(deg2rad($latitude1)) +  cos(deg2rad($latitude)) * cos(deg2rad($latitude1)) * cos(deg2rad($theta));
			    $dist = acos($dist);
			    $dist = rad2deg($dist);
			    $miles = $dist * 60 * 1.1515;
				$row['distance']=$miles;
				$response[] =  $row;
		   }
	   }
	   return $response;
	}
	/**
	 * getNotificationCount function.	
	 * @param userid,auth_token
	 * @access public
	 * @return notification counter
	 */
    public 	function getNotificationCount($userid, $auth_token)
	{
		$response = array();
		$stmt = $this->db->query("SELECT * FROM `friends` f ,users u where f.receive_user_id=".$userid." and f.status = '1' and f.approved = '2' and u.status !='1' AND f.send_user_id=u.id");
        $stmt=$stmt->result_array();
	    $scount=count($stmt);
	    if($scount > 0)
	    {
	    	  $response['count'] =  $scount;
	    }
		return $response;
	}
	/**
	 * updatenotification function.	
	 * @param userid,auth_token
	 * @access public
	 * @return boolean status
	 */
	public function updatenotification($userid,$counter)
	{
		$data=array('notificationcounter'=>$counter);
		$this->db->where('id',$userid);
		$result=$this->db->update('users',$data);
        if ($result==false) 
		{
           return 0;
        }
        return 1;
	}
	/**
	 * blockUser function.	
	 * @param userid,blockid,blockstatus,auth_token
	 * @access public
	 * @return message
	 */
    public function blockUser($userid, $blockid, $blockstatus, $auth_token)
	{
		if($blockstatus == 1)
		{
			$deviceQuery = "SELECT * FROM blockuser WHERE `userid` = '$userid' AND `blockid` = '$blockid'";
			$result1=$this->db->query($deviceQuery); 
			$result1=$result1->result_array();
			$bcount=count($result1);
			if($bcount == 0)
			{
				$data=array(
					'userid'=>$userid,
					'blockid'=>$blockid,
					'created_date'=>date('Y-m-d H:i:s'),
				    'modified_date'=>date('Y-m-d H:i:s')
				);
				$results = $this->db->query("INSERT INTO `blockuser`(userid, blockid,blockstatus,created_date,modified_date) values('$userid','$blockid','$blockstatus','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
				$message = "Block user Successfully";
				$this->userUnfriend($userid, $blockid);
			}
			else
			{
				$message = 'Already blocked';
			}
		}
		else
		{
			$results = $this->db->query("DELETE FROM blockuser WHERE userid =".$userid." AND blockid = ".$blockid."");
			$message = "Unblock user Successfully";
		}
		return $message;	
	}
	/**
	 * reporteUser function.	
	 * @param report_from_id,report_to_id,auth_token
	 * @access public
	 * @return message
	 */
	public function reporteUser($report_from_id, $report_to_id, $auth_token)
	{
		$response = array();
		$deviceQuery = "SELECT * FROM reporteuser WHERE `report_from_id` = '$report_from_id' AND `report_to_id` = '$report_to_id'";
		$result1=$this->db->query($deviceQuery); 
		$result1=$result1->row_array();
		$rcount=count($result1);
		if($rcount == 0){
			$results = $this->db->query("INSERT INTO `reporteuser`(report_from_id, report_to_id,created_date) values('".$report_from_id."','".$report_to_id."','".date('Y-m-d H:i:s')."')");
			$message = "Report user Successfully";
		}
		else
		{
			$message = 'Already reported';
		}
		return $message;
	}
	/**
	 * getAllUsers function.	
	 *
	 * @access public
	 * @return all users data
	 */
	public function getAllUsers()
	{
		$users = array();
		$results = $this->db->query("SELECT * from users"); 
		$results=$results->result_array();
		$ucount=count($results);
		if($ucount > 0)
		{  
			foreach($results as $row)
			{
				$results1 = $this->db->query("SELECT * from usergallery where user_id=".$row['id']); 
				$results1=$results1->result_array();
				$gcount=count($results1);
				if($gcount > 0)
				{  $i=1;
					foreach($results1 as $row1) 
					{
						$row[$i][$row1['img_key']]=$row1['img_url'];             
						$i++;
					}
				}
				$users[] = $row;
			}
       }
       return $users;
	}
	/**
	 * userUpdateLatLong function.	
	 * @param user_id, latitude, longitude, auth_token
	 * @access public
	 * @return user data
	 */
	public function userUpdateLatLong($user_id, $latitude, $longitude, $auth_token)
	{
		$data=array(
			'location_lat'=>$latitude,
			'location_long'=>$longitude
		);
		$this->db->where('id',$user_id);
		$stmt=$this->db->update("users",$data);
        $results = $this->db->query("SELECT * from users WHERE  id =".$user_id);
        $row=$results->row_array();
        return $row;		
	}
	/**
	 * mutualFriends function.	
	 * @param user_id, receive_user_id, auth_token
	 * @access public
	 * @return user data of mutual friend
	 */
	public function mutualFriends($user_id, $receive_user_id, $auth_token)
	{
		$results=$this->db->query("Select distinct(f1.receive_user_id) as mutual_friend_id from friends f1 where f1.send_user_id=$user_id and f1.approved='1' and f1.receive_user_id IN (Select distinct(f2.receive_user_id) from friends f2 where f2.send_user_id=$receive_user_id and f2.approved='1')"); 
		$users = array();
		$results=$results->result_array();
		$mcount=count($results);
		if($mcount > 0)
		{
			foreach($results as $row) 
			{
				$results1 = $this->db->query("SELECT * from users where id=".$row['mutual_friend_id']); 
				$results1=$results1->row_array();
				$ucount=count($results1);
				if($ucount > 0)
				{  
					 $users[]=$results1;
				}
			}
		}
		return $users;
	}
	public function matchFriends($user_id)
	{
		$results=$this->db->query("Select receive_user_id,send_user_id  from friends where  (receive_user_id=$user_id or send_user_id=$user_id) and approved='1'"); 
		$users = array();
		$results=$results->result_array();
		$mcount=array();
		foreach ($results as $result) {
			$mcount[]=$result['receive_user_id'];
			$mcount[]=$result['send_user_id'];
		}
		$mcount=array_unique($mcount);
		$pos = array_search($user_id, $mcount);
		// Remove from array
		unset($hackers[$pos]);
		//print_r($mcount);
		//die;
		return $mcount;
	}
	 /**
	 * enableAdd function.	
	 * @param user_id, enableadd, auth_token
	 * @access public
	 * @return message
	 */
	public function enableAdd($userid,$enableadd, $auth_token)
	{
		$results = $this->db->query("SELECT * from users WHERE  id =".$userid." AND enableAdd ='".$enableadd."'");
		$acount=$this->db->affected_rows();;
		if($acount==0)
		{
			$res = $this->db->query("UPDATE users set enableAdd = '".$enableadd."' WHERE id = ".$userid);
			$rcount=$this->db->affected_rows();
			if($rcount > 0 )
			{
				if($enableadd == 0)
				{
					$message = "Disabled Add successfully";
				}
				else
				{
					$message = "Enabled Add successfully";
				}
				return $message;
			}
			else
			{
				return NULL;	
			}	
		}
		else
		{
			$message = "Already updated";
			return $message;
		}
	}
	/**
	 * getblockstatus function.	
	 * @param user_id, auth_token
	 * @access public
	 * @return user block status
	 */
	public function getblockstatus($userid,$auth_token)
	{
		$results = $this->db->query("SELECT status,enableAdd from users WHERE  id =".$userid."");
		$results=$results->row_array();
		$result['status']=$results['status'];
		$result['user_add']=$results['enableAdd'];
		$res=$this->db->query("SELECT status from site_setting WHERE  mode ='chk_all'");
		$res=$res->row_array();
		$result['acc_status']=$res['status'];
		return $result;
	}
	/**
	 * get_all_religion function.
	 * 
	 * @access public
	 * @return list of religion
	 */
	 public function get_all_religion($lang)
	{
		$this->db->select('id,name as '.$lang);
        $this->db->from('religions');
		//$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * get_all_ethnicity function.
	 * 
	 * @access public
	 * @return list of ethnicity
	 */
    public function get_all_ethnicity($lang)
	{
		$this->db->select('id,name as '.$lang);
		$this->db->from('ethnicity');
		//$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * get_all_questions function.
	 * 
	 * @access public
	 * @return list of questions
	 */
    public function get_all_questions($lang)
	{
		$this->db->select('id,name as '.$lang);
		$this->db->from('questions');
		//$this->db->where('status', '1');
		return $this->db->get()->result_array();
	}
	public function updateDeviceToken($userid,$AuthToken,$device_token)
	{
		$this->db->where('AuthToken',$AuthToken);
		$this->db->where('user_id',$userid);
		$this->db->update('userlogin',array('device_token'=>$device_token));
		return true;
	}
	public function user_verification($data){
		return $this->db->insert("user_verification",$data);
	}
	public function delete_user_verification($condition){
		$this->db->where($condition);
		return $this->db->delete("user_verification");
	}
	public function update_user_verification($data,$condition){
		$this->db->where($condition);
		return $this->db->update("user_verification",$data);
	}
	public function get_users_verification($condition){
		$this->db->from('user_verification');
		$this->db->where($condition);
		return $this->db->get()->row_array();
	}
	/*public function checkAuthToken($user_id,$AuthToken){
		$this->db->select("user_id");		
		$this->db->where("AuthToken='".$AuthToken."' AND user_id=".$user_id);
		return $this->db->get("userlogin")->row_array();
	}*/
	public function remove_galleryimage($img_key,$user_id){
		$this->db->where("user_id=".$user_id." AND img_key in ('".str_replace(",","','",$img_key)."')");
		$i= $this->db->delete("usergallery");
		if($i){		
			return true;
		}
		else{
			return false;
		}
	}
	public function update_profile($data,$user_id){
		$this->db->where("id=".$user_id);
		$i=$this->db->update("users",$data);
		if($i){		
			return true;
		}
		else{
			return false;
		}
	}
	public function get_sampledata(){
		$this->db->where(array("mode"=>"sample_data"));
		return $this->db->get("site_setting")->row_array();
	}
	/*for visiter's info*/
	public function add_visiter($data){
		return $this->db->insert("visiter_info",$data);
	}
	public function get_vister($condition){
		$this->db->where($condition);
		return $this->db->get("visiter_info")->row_array();
	}
	public function update_visiter($data,$condition){
		$this->db->where($condition);
		return $this->db->update("visiter_info",$data);
	}
	public function get_visters($condition,$start=null,$limit=null){
		$this->db->limit($start, $limit);
		return $this->db->get("visiter_info")->result_array();
	}
}