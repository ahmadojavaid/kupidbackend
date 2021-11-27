<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
	}
	/**
	 * fetch_language function.
	 * 
	 * @access public
	 * @return languages name except english
	 */
	public function fetch_language()
	{
		$this->db->where('status',1);
		$result=$this->db->get('language');
		return $result->result_array();
	}
	/**
	 * site_setting function.
	 * 
	 * @access public
	 * @return status of update or insert query
	 */
	public function site_setting($data=array())
	{
		foreach($data as $key=>$val)
		{
			if($key!="btn_submit")
			{
					if(strcmp($key,"select_language")==0)
					{						
						$val=implode(",",$val);
						echo $val."</br>";												
					}
				$this->db->where('mode',$key); 
				$res=$this->db->get('site_setting');
				$count=$res->num_rows();
				if($count > 0)
				 { 
					$this->db->where('mode',$key); 
					$values=array(
					'status'=>$val,
					'modified_date'=>date('Y-m-d H:i:s')
					);
					$this->db->update('site_setting',$values);
				}
				else
				{
					$values=array(
					'mode'=>$key,
					'status'=>$val,
					'created_date'=>date('Y-m-d H:i:s'),
					'modified_date'=>date('Y-m-d H:i:s')
					);
					$this->db->insert('site_setting',$values);
				}
			}	
		}
		return true;
	}
	/**
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
     public function update_users($data,$condition)
     {
        $this->db->where($condition);
        return $this->db->update("users",$data);
     }
	 /**
	 * get_gallery_images function.
	 * @param condition
	 * @access public
	 * @return user gallery
	 */
     public function get_gallery_images($condition=NULL)
     {
        if($condition)
        {
            $this->db->where($condition);       
        }
        $this->db->order_by("img_key");
        return $this->db->get("usergallery")->result_array();
     }
	 /**
	 * add_gallery_images function.
	 * @param data
	 * @access public
	 * @return void
	 */
     public function add_gallery_images($data)
     {
       return $this->db->insert("usergallery",$data);
     }
	 /**
	 * change__gallery_images function.
	 * @param data
	 * @access public
	 * @return boolean status
	 */
     public function change__gallery_images($data,$condition)
     {
        $this->db->where($condition);
		return $this->db->update("usergallery",$data);
     }
	  /**
	 * create_user function.
	 * @param Post data
	 * @access public
	 * @return id
	 */
	public function create_user($data = array()) {
		if ($data)
        {
            $sql = "INSERT INTO `users` ( 
            	`fname`, 
            	`lname`, 
            	`username`, 
            	`email`, 
                `fb_id`,
            	`password`, 
            	`education`, 
            	`profession`, 
            	`created_date`, 
            	`modified_date`
				) 
				VALUES (	
					" . $this->db->escape($data['fname']) . ",
					" . $this->db->escape($data['lname']) . ",
					" . $this->db->escape($data['username']) . ",
					" . $this->db->escape($data['email']) . ",
                    " . $this->db->escape($data['fb_id']) . ",
					" . $this->db->escape($this->hash_password($data['password'])) . ",	
					" . $this->db->escape($data['education']) . ",  
					" . $this->db->escape($data['profession']) . ", 
					'" . date('Y-m-d H:i:s') . "', 
					'" . date('Y-m-d H:i:s') . "'
				)";
				$this->db->query($sql);
				if ($id = $this->db->insert_id())
				{
					$this->XMPP_ENABLE = $this->Common_model->get_key_configuration(array('key'=>'XMPP_ENABLE'));
					if($this->XMPP_ENABLE == 'true'){
						//register user
						$ejuser=$this->Ejabberd_model->register($id);
					}
					else{
						$ejuser=$data->userdetail->ejuser;
					}
					/*if(XMPP_ENABLE)
					{
						$connection=array(
							'hostname'	=> XMPP_HOST,
							'username'	=> XMPP_ADMIN,
							'password'	=> XMPP_ADMIN_PASSWORD,
						);
						$demo=new Radix_XMPP($connection);
						//create new user take ID and take first name and set default password .
						$register=$demo->register($id.'_'.strtolower(trim($data['fname']).'_cl_new'),XMPP_DEFAULT_PASSWORD);
						if($register)
						{
							//XMPP register success
						}
					}*/
					return $id;
				}
		}
		return FALSE;
	}
	/**
	 * get_default_language function.
	 * 
	 * @access public
	 * @return Logo Object
	 */
	 public function get_default_language()
    {
        $this->db->where('mode','default_language');       
        return $this->db->get("site_setting")->row_array();
     }
	 public function get_religion()
	 {
		return $this->db->query("SELECT religions.id, religions.name, GROUP_CONCAT(religions_lang.religion_id SEPARATOR ',') as religion_id,GROUP_CONCAT(religions_lang.value SEPARATOR ',') as religions_value,GROUP_CONCAT(religions_lang.language SEPARATOR ',') as religions_language FROM religions RIGHT JOIN religions_lang ON (religions.id = religions_lang.religion_id) GROUP BY religions.id")->result_array();
	 }
	 public function get_ethnicity()
	 {
		return $this->db->query("SELECT ethnicity.id, ethnicity.name, GROUP_CONCAT(ethnicity_lang.ethnicity_id SEPARATOR ',') as ethnicity_id,GROUP_CONCAT(ethnicity_lang.value SEPARATOR ',') as ethnicity_value,GROUP_CONCAT(ethnicity_lang.language SEPARATOR ',') as ethnicity_language FROM ethnicity RIGHT JOIN ethnicity_lang ON (ethnicity.id = ethnicity_lang.ethnicity_id) GROUP BY ethnicity.id")->result_array();
	 }
	 public function get_questions()
	 {		
		return $this->db->query("SELECT questions.id, questions.question, GROUP_CONCAT(questions_lang.question_id SEPARATOR ',') as question_id,GROUP_CONCAT(questions_lang.value SEPARATOR ',') as question_value,GROUP_CONCAT(questions_lang.language SEPARATOR ',') as question_language FROM questions RIGHT JOIN questions_lang ON (questions.id = questions_lang.question_id) GROUP BY questions.id")->result_array();
	 }
	/**
	 * blocked_user function.
	 * @param user_id
	 * @access public
	 * @return boolean status
	 */
	public function blocked_user($user_id)
	{
		$id=$_SESSION['user_id'];
		$data=array(
			'userid'=>$id,
			'blockid'=>$user_id,
			'blockstatus'=>'1',
			'created_date'=>date('Y-m-d H:i:s'),
			'modified_date'=>date('Y-m-d H:i:s')
		);
		$this->db->insert('blockuser',$data);		
		$this->db->where(array('send_user_id'=>$id,'receive_user_id'=>$user_id));
		$this->db->or_where(array('send_user_id'=>$user_id));
		$this->db->where('receive_user_id',$id);
		return $this->db->delete('friends');
	}
	public function report_user($user_id)
	{
		$id=$_SESSION['user_id'];
		$data=array(
			'report_from_id'=>$id,
			'report_to_id'=>$user_id,
			'created_date'=>date('Y-m-d H:i:s'),
		);
		return $this->db->insert('reporteuser',$data);
	}
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($email, $password) 
	{
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('email', $email);
		$hash = $this->db->get()->row('password');
		return $this->verify_password_hash($password, $hash);
	}
	/**
	 * get_new_datepreference function.
	 * 
	 * @access public
	 * @param Date Preferance fetch According its order
	 * @param mixed $password
	 * @return array of date preferance
	 */
	public function get_new_datepreference($date_pref)
	{
		$value=explode(",",$date_pref);
		$str=array();
		foreach($value as $row)
		{
			$this->db->select(array('id','name'));
			$this->db->from('datepreference');
			$this->db->where('id',$row);
			$result=$this->db->get()->row_array();
			$str[$result['id']]=$result['name'];
		}
		return $str;
    }
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $email
	 * @return int the user id
	 */
	public function get_user_id_from_email($email) 
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		return $this->db->get()->row('id');
	}
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) 
	{
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
	}
	public function get_user_by_con($condition) 
	{
		$this->db->from('users');
		$this->db->where($condition);
		return $this->db->get()->row();
	}
	/**
	 * get_user_array function.
	 * @param user_id
	 * @access public
	 * @return particular user data
	 */
	public function get_user_array($user_id) 
	{
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row_array();
	}
	/**
	 * get_device_token function.
	 * @param user_id
	 * @access public
	 * @return list of user data when login with multiple device
	 */
	public function get_device_token($user_id)
	{
		$this->db->from('userlogin');
		$this->db->where('user_id', $user_id);
		return $this->db->get()->result_array();
	}
	/**
	 * registerwithfb function.
	 * @param data
	 * @access public
	 * @return boolean status
	 */
    public function registerwithfb($data)
    {
        $this->db->insert("users",$data);
        return  $this->db->insert_id();
    }
	/* 
	 * Returns User's detail 
	 * @param Array $condition.
	 * @return Object of One User's details
	 */
    public function get_user_where_row($condition=null)
    {
        if(!empty($condition))
        {
            $this->db->where($condition);
        }
        $result=$this->db->get("users");
        return $result->row();        
    }
	/* 
	 * Returns Users' detail 
	 * @param Array $condition.
	 * @return Array of Users' details
	 */
    public function get_user_where_result($condition=null)
    {
        if(!empty($condition))
        {
            $this->db->where($condition);
        }
        $result=$this->db->get("users");
        return $result->result_array();        
    }
	/* 
	 * Returns Users' detail 
	 * @param Array $condition.
	 * @return Array of Users' details
	 */
    public function get_friend($user_id,$friend_id)
	{
		$this->db->from('friends');
		$this->db->where(array('send_user_id'=>$friend_id,'receive_user_id'=>$user_id));
        $this->db->or_where('send_user_id',$user_id);
        $this->db->where('receive_user_id',$friend_id);
		return $this->db->get()->row();
	}
	/**
	 * get_friendlist function.
	 * @param user_id
	 * @access public
	 * @return list of users
	 */
	public function get_friendlist($user_id) 
	{
        $this->db->from('friends');
		$this->db->where('send_user_id', $user_id);
		return $this->db->get()->result_array();
	}
	/**
	 * get_all_religion function.
	 * 
	 * @access public
	 * @return list of religion
	 */
    public function get_all_religion()
	{
        $this->db->from('religions');
		$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * get_all_ethnicity function.
	 * 
	 * @access public
	 * @return list of ethnicity
	 */
    public function get_all_ethnicity()
	{
        $this->db->from('ethnicity');
		$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * get_all_datepreference function.
	 * 
	 * @access public
	 * @return list of datepreference
	 */
    public function get_all_datepreference()
	{
        $this->db->from('datepreference');
		$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * get_all_questions function.
	 * 
	 * @access public
	 * @return list of questions
	 */
    public function get_all_questions()
	{
        $this->db->from('questions');
		return $this->db->get()->result_array();
    }
	/**
	 * unlinkfile function.
	 * 
	 * @access public
	 * @return void
	 */
	public function unlinkfile($file=null)
	{
		if($file)
		{
			if(file_exists($file))
					unlink($file);
		}
	}
	/**
	 * update_profile function.
	 * @param Post data
	 * @access public
	 * @return boolean status
	 */
	public function update_profile($condition,$data)
	{
		$this->db->where($condition);
		return $this->db->update('users',$data);
	}
	/**
	 * update_perference function.
	 * @param Post data
	 * @access public
	 * @return boolean status
	 */
     public function update_perference($data = array())
	 {
        $address = $data['address'];            
		$geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
		// Convert the JSON to an array
		$geo = json_decode($geo, true);
		if ($geo['status'] == 'OK') 
		{
		  // Get Lat & Long
			$latitude = $geo['results'][0]['geometry']['location']['lat'];
			$longitude = $geo['results'][0]['geometry']['location']['lng'];
		}   
		$this->db->where('id', $data['user_id']);
		$this->db->update('users', array(
		'address' => $data['address'],
		'location_lat' =>$latitude,
		'location_long'=>$longitude,
		'date_pref' => $data['datepref'],
		'gender_pref' => $data['gender_pref'],
		'min_age_pref' => $data['age-min'],
		'max_age_pref' => $data['age-max'],
		'min_dist_pref'  => $data['dist-min'],
		'max_dist_pref' => $data['dist-max'],
		'ethnicity' => $data['ethnicity'],
		'access_location' => $data['access_loc'],
		'modified_date'=>date('Y-m-d H:i:s')
		));
		return true;
     }
	  /**
	 * get_search_prefences function.
	 * @param user_id
	 * @access public
	 * @return user list according to preferences
	 */
    public function get_search_prefences($user_id)
	{
		$data = $this->get_user($user_id);       
		
		$Gender = $data->gender_pref;
		$MinAgePref = $data->min_age_pref;
		$MaxAgePref = $data->max_age_pref;
		$DatePreferceIdOrder = $data->date_pref;
		$MinDistance = $data->min_dist_pref;
		$MaxDistance = $data->max_dist_pref; 
		$latitude = $data->location_lat;
		$longitude = $data->location_long;

		//religion pref
		//ethnicity pref
		$reg_pref=$data->religion_pref;
		$eth_pref=$data->ethnicity_pref;

		if(empty($latitude))
			$latitude = 0;
		if(empty($longitude))
			$longitude = 0; 	
		$select="select *,( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) as distance FROM `users` ";
		$where_user=" WHERE id != $user_id  AND `status` != '1' AND id not in (select f.receive_user_id from friends f where f.send_user_id=$user_id) and id not in (select f.send_user_id from friends f where f.receive_user_id=$user_id) and id not in (select b.blockid from blockuser b where b.userid=$user_id)and id not in (select b.blockid from blockuser b where b.userid=$user_id)";
		$where_gender_age=" and (`Gender` = '{$Gender}') AND (TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())>={$MinAgePref} AND TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())<={$MaxAgePref}) ";
		$where_distance=" AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) >= $MinDistance AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) <= $MaxDistance ";

		$sample_data=$this->get_sampledata();
		$where_sample;
		if($sample_data['status']==0){
			$where_sample=" and users.sampledata= 0 ";
		}
		else{
			$where_sample=" ";
		}
		if(!empty($reg_pref)){
			$where_reg=" and `users`.`religion` in ($reg_pref) ";
		}else{
			$where_reg="";
		}
		if(!empty($eth_pref)){
			$where_eth=" and `users`.`ethnicity` in ($eth_pref) ";
		}else{
			$where_eth="";
		}

		$where=$where_user." ".$where_gender_age." ".$where_distance." ".$where_sample." ".$where_reg." ".$where_eth;

		$order=" order by distance asc";
		$sql=$select." ".$where." ".$order;
		$result = $this->db->query($sql)->result_array();
		$friend_list = $this->get_friendlist($user_id);
		$friend_array=array();
        foreach($friend_list as $flist)
		{
            array_push($friend_array,$flist['receive_user_id']);
        }
        $data = array();
        foreach($result as $res)
		{
             if(in_array($res['id'],$friend_array))
			 {
				unset($res);
             }
             else
			 {
				array_push($data,$res);
             }
        }
        return $data;         
    }
     /**
	 * get_search_prefences_by_condition function.
	 * @param user_id
	 * @param condition
	 * @param from_age
	 * @param to_age
	 * @access public
	 * @return user list according to preferences
	 */
    public function get_search_prefences_by_condition($user_id,$gender=null,$from_age=null,$to_age=null)
	{
        $data = $this->get_user($user_id);       
		$MinAgePref = $data->min_age_pref;
		$MaxAgePref = $data->max_age_pref;
		$DatePreferceIdOrder = $data->date_pref;
		$MinDistance = $data->min_dist_pref;
		$MaxDistance = $data->max_dist_pref; 
		$latitude = $data->location_lat;
		$longitude = $data->location_long; 		  		  
		/*$this->db->select("*,( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) as distance FROM `users`  WHERE DOB>='".
			date('Y-m-d', strtotime(date('Y-m-d')."-$to_age years"))."' AND  DOB<='". date('Y-m-d', strtotime(date('Y-m-d')."-$from_age years")) ."' AND Gender = '".$gender."' AND id != $user_id  AND `status` != '1' AND id not in (select f.receive_user_id from friends f where f.send_user_id=$user_id) and id not in (select f.send_user_id from friends f where f.receive_user_id=$user_id) and id not in (select b.blockid from blockuser b where b.userid=$user_id)and id not in (select b.blockid from blockuser b where b.userid=$user_id) and (`Gender` = '{$Gender}') AND (TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())>={$MinAgePref} AND TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())<={$MaxAgePref})  AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) >= $MinDistance AND ( 3959 * acos( cos( radians($latitude) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( `location_lat` ) ) ) ) <= $MaxDistance order by distance asc");*/
			$sample_data=$this->get_sampledata();
			$where_sample;
			if($sample_data['status']==0){
				$where_sample=" and users.sampledata= 0 ";
			}
			else{
				$where_sample=" ";
			}
			$this->db->select("* FROM `users`  WHERE DOB>='".
			date('Y-m-d', strtotime(date('Y-m-d')."-$to_age years"))."' AND  DOB<='". date('Y-m-d', strtotime(date('Y-m-d')."-$from_age years")) ."' AND Gender = '".$gender."' AND id != $user_id  AND `status` != '1' AND id not in (select f.receive_user_id from friends f where f.send_user_id=$user_id) and id not in (select f.send_user_id from friends f where f.receive_user_id=$user_id) and id not in (select b.blockid from blockuser b where b.userid=$user_id)  AND (TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())>={$MinAgePref} AND TIMESTAMPDIFF(YEAR, `DOB`, CURDATE())<={$MaxAgePref}) $where_sample  order by DOB asc");
		$result = $this->db->get()->result_array();
		$friend_list = $this->get_friendlist($user_id);
		$friend_array=array();
        foreach($friend_list as $flist)
		{
            array_push($friend_array,$flist['receive_user_id']);
        }
        $data = array();
        foreach($result as $res)
		{
             if(in_array($res['id'],$friend_array))
			 {
				unset($res);
             }
             else
			 {
				array_push($data,$res);
             }
        }
        return $data;         
    }
	/**
	 * friend_like function.
	 * @param user_id,friend_id
	 * @access public
	 * @return id or false
	 */
    public function friend_like($user_id,$friend_id)
	{
        $isuser = $this->get_user($friend_id);
        if($isuser)
		{        
				$data=array("send_user_id"=>$user_id,"receive_user_id"=>$friend_id ,"status"=>'1',
				"created_date"=> date('Y-m-d H:i:s'),
				"modified_date"=> date('Y-m-d H:i:s'));
				$this->db->insert("friends",$data);		
				if ($id = $this->db->insert_id())
				{
					return $id;
				}
                return FALSE;
		}
        return FALSE;
    }
	/**
	 * friend_dislike function.
	 * @param user_id,friend_id
	 * @access public
	 * @return id or false
	 */
    public function friend_dislike($user_id,$friend_id)
	{
		$isuser = $this->get_user($friend_id);
		if($isuser)
		{    
			$sql = "INSERT INTO `friends` ( 
					`send_user_id`, 
					`receive_user_id`, 
					`status`, 
					`created_date`, 
					`modified_date`) 
					VALUES (	
						" . $user_id . ",
						" . $friend_id . ",
						'" . 0 . "',  
						'" . date('Y-m-d H:i:s') . "', 
						'" . date('Y-m-d H:i:s') . "'
					)";
			$this->db->query($sql);
			if ($id = $this->db->insert_id())
			{
				return $id;
			}
			return FALSE;
		}
		return FALSE;
    }
	/**
	 * get_notification function.
	 * @param user_id,lat,lan
	 * @access public
	 * @return no of notification list for login user
	 */
    public function get_notification($user_id,$lat,$lan)
	{
		if(empty($lat))
			$lat=0;
		if(empty($lan))
			$lan=0;
	    $this->db->select("friends.*,users.*,friends.id as fid,( 3959 * acos( cos( radians($lat) ) * cos( radians( `location_lat` ) ) * cos( radians( `location_long` ) - radians($lan) ) + sin( radians($lat) ) * sin( radians( `location_lat` ) ) ) ) as distance");
        $this->db->from('friends');
        $this->db->join('users', 'friends.send_user_id = users.id');
		$this->db->where('friends.receive_user_id', $user_id);
        $this->db->where('friends.approved', '2');
		return $this->db->get()->result_array();
    }
    /**
	 * friend_approved function.
	 * @param user_id,friend_id
	 * @access public
	 * @return id or false
	 */    
    public function friend_approved($user_id,$friend_id)
	{
        $isuser = $this->get_user($friend_id);
        if($isuser)
		{
			$data=array('approved'=>'1','modified_date'=>date('Y-m-d H:i:s'));
			$this->db->where('send_user_id',$friend_id);
			$this->db->where('receive_user_id',$user_id);
			$this->db->update('friends',$data);
			$sql = "INSERT INTO `friends` ( 
				`send_user_id`, 
				`receive_user_id`, 
				`approved`, 
				`status`, 
				`created_date`, 
				`modified_date`) 
				VALUES (	
					" . $user_id . ",
					" . $friend_id . ",
					'" . 1 . "',
					'" . 1 . "',  
					'" . date('Y-m-d H:i:s') . "', 
					'" . date('Y-m-d H:i:s') . "'
				)";
			$this->db->query($sql);
			if ($id = $this->db->insert_id())
			{
				return $id;
			}
            return FALSE;
		}
        return FALSE;
    }
    public function is_already_friend($user_id,$friend_id){
    	$this->db->where('(send_user_id='.$user_id.' AND receive_user_id='.$friend_id.') OR (send_user_id='.$friend_id.' AND receive_user_id='.$user_id.')');
		return $this->db->get('friends')->result_array();

    }
    public function delete_friend($user_id,$friend_id){
    	$this->db->where('(send_user_id='.$user_id.' AND receive_user_id='.$friend_id.') OR (send_user_id='.$friend_id.' AND receive_user_id='.$user_id.')');
		$this->db->from('friends');
		return $this->db->delete();
    }
	/**
	 * friend_decline function.
	 * @param user_id,friend_id
	 * @access public
	 * @return boolean
	 */
    public function friend_decline($user_id,$friend_id)
	{
	    $isuser = $this->get_user($friend_id);
        if($isuser)
		{
			$this->db->insert("friends",array("send_user_id"=>$user_id,"receive_user_id"=>$friend_id,"modified_date"=>date("Y-m-d H:i:s"),"approved"=>"0","created_date"=>date("Y-m-d H:i:s"),"status"=>"1"));
			$this->db->where(array('send_user_id'=> $friend_id,'receive_user_id'=> $user_id));
			$this->db->update("friends",array("approved"=>"0"));
			return TRUE;
		}
        return FALSE;
    }
	/* Returns Friends List of User 
	 * @param Number $user_id User's ID .
	 * @return array of Firends' details
	 */
    public function get_friends_list($user_id)
	{
        $this->db->select('friends.*,users.*');
        $this->db->from('friends');
        $this->db->join('users', 'friends.send_user_id = users.id');
		$this->db->where('friends.receive_user_id', $user_id);
        $this->db->where('friends.approved', '1');
		$this->db->order_by("friends.modified_date","desc");
		return $this->db->get()->result_array();
    }
	 /* Returns Friends List of User 
	 * @param Number $user_id User's ID .
	 * @param Number $user_id Friend's ID .
	 * @return Object of Firends details
	 */
    public function get_friend_profile($user_id,$friend_id)
	{
      $isuser = $this->get_user($friend_id);
      $isfriend = $this->get_friend($user_id,$friend_id);
      if($isuser)
	  {
            if($isfriend)
			{
                return $this->get_user($friend_id);
            }
            return FALSE;
        }
        return FALSE; 
    }
	/**
	 * get_user_profile function.
	 * @param user_id,friend_id
	 * @access public
	 * @return user data or false
	 */
	public function get_user_profile($user_id)
	{
		$isuser = $this->get_user($user_id);
        if($isuser)
		{           
            return $isuser;
        }
        return FALSE;		
	}
    //change password function
	public function set_token($token,$id)
    {
        $this->db->where("id",$id);
        if($this->db->update("users",array("pass_token"=>$token)))
            return true;
        else
            return false;
    }
	/**
	 * check_authorize function.
	 * @param email,token
	 * @access public
	 * @return user data 
	 */
    public function check_authorize($email,$token)
    {
        $this->db->where("pass_token",$token);
        $this->db->where("email",$email);
        $result=$this->db->get("users");
        return $result->row_array();
    }
	/**
	 * get_question function.
	 * @param id
	 * @access public
	 * @return question Data
	 */
    public function get_question($id)
    {
        $this->db->where("id",$id);
        $result=$this->db->get("questions");
        return $result->row();
    }
	/**
	 * unset_token function.
	 * @param id
	 * @access public
	 * @return boolean status
	 */
    public function unset_token($id)
    {
        $this->db->where("id",$id);
        if($this->db->update("users",array("pass_token"=>"")))
            return true;
        else 
            return false;
    }
	/**
	 * update_password function.
	 * @param codition,password
	 * @access public
	 * @return boolean status
	 */
    public function update_password($codition,$password)
    {
       $hashpassword=$this->hash_password($password);
       $this->db->where($codition);
       if($this->db->update("users",array("password"=>$hashpassword)))
            return true;
       else
            return false;
    }
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) 
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) 
	{
		return password_verify($password, $hash);
	}
	public function get_site_setting()
	{
        $result=$this->db->get('site_setting');
		return $result->result_array();
    }
	public function block_site_setting()
	{
        $result=$this->db->get('block');
		return $result->result_array();
    }
	public function insert_contact($data)
    {
        if(empty($data))
            return false;
        else{
            if($this->db->insert('contact',$data))
            {
                //data inserted
                return ($this->db->insert_id());
            }
            else
                return false;
        }
    }
	public function getblog($page,$blog_id){
        $offset = 10*$page;
        $limit = 10;
        $sql = "select * from blog where language_id in (select language_id from blog  where id=$blog_id) limit $offset ,$limit";
        $result = $this->db->query($sql)->result();
        return $result;
    }
    public function get_all_session_data(){
		$query=$this->db->get('ci_sessions');
		return $query->result_array();
	}
	public function get_total_users(){
		$query=$this->db->query('SELECT * FROM users');
		return $query->num_rows();
	}
	public function delete_user_verification($condition){
		$this->db->where($condition);
		return $this->db->delete("user_verification");
	}
	public function user_verification($data){
		return $this->db->insert("user_verification",$data);
	}
	public function get_users_verification($condition){
		$this->db->from('user_verification');
		$this->db->where($condition);
		return $this->db->get()->row_array();
	}
	public function update_user_verification($data,$condition){
		$this->db->where($condition);
		return $this->db->update("user_verification",$data);
	}
	public function get_sampledata(){
		$this->db->where(array("mode"=>"sample_data"));
		return $this->db->get("site_setting")->row_array();
	}
}