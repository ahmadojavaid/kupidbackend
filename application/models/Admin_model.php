<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin_model class.
 * 
 * @extends CI_Model
 */
class Admin_model extends CI_Model {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model("Pushnotification_model");
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
		$this->db->where(array('email'=>$email,'is_admin'=>'1'));
		$hash = $this->db->get()->row('password');
		return $this->verify_password_hash($password, $hash);
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
	/**
	 * get_all function.
	 * 
	 * @access public
	 * @param $condition,$con
	 * @return object the user object
	 */
	public function get_all($condition=null,$con=null) 
	{
		if(!empty($condition))
		{
			$this->db->or_where($condition);
		}
		if(!empty($con))
		{
			$this->db->where($con);
		}
		$this->db->order_by("created_date", "desc");
		$this->db->from('users');
		return $this->db->get()->result_array();
	}
	/**
	 * get_all_users function.
	 * 
	 * @access public
	 * @param $condition
	 * @return Online Users Object
	 */
	public function get_all_users($condition=null) 
	{
		$this->db->select('distinct(users.email),users.fname,users.id,users.lname');
		$this->db->from('userlogin');
		$this->db->join("users","users.id=userlogin.user_id");
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->get()->result_array();
	}
	public function fetch_data($table,$condition=null)
	{
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->get($table)->result_array();
	}
	public function get_remove_language($id)
	{
		$this->db->where('id',$id);
		$result=$this->db->get('language');
		$result=$result->row_array();
		return $result['name'];
	}
	public function set_languages($data)
	{
		$this->db->update('language',array('status'=>0));
		foreach($data as $row)
		{
			$this->db->where('name',$row);
			$this->db->update('language',array('status'=>1));
		}
		return true;
	}
	public function site_setting($data,$condition=null)
	{
		//echo "<pre>";print_r($data);
		foreach($data as $key=>$val)
		{
			//echo $key;
			if($key!="btn_submit")
			{
				$this->db->where('mode',$key);
				$result=$this->db->get('site_setting');
				//echo $this->db->last_query()."</br>";
				$cnt=$result->num_rows();
				if($cnt==1)
				{
					$this->db->where('mode',$key);
					$this->db->update('site_setting',array('status'=>$val));
					//echo $this->db->last_query()."</br>";
				}
				else
				{
					$data=array(
					'mode'=>$key,
					'status'=>$val);
					$this->db->insert('site_setting',$data);
					//echo $this->db->last_query()."</br>";
				}
			}
		}
	}
	public function get_setting()
	{
		return $this->db->get('site_setting')->result_array();
	}
	public function configuration($data,$condition=null)
	{
		//echo "<pre>";print_r($data);die;
		foreach($data as $key=>$val)
		{
			if($key!="btn_submit")
			{
				$this->db->where('key',$key);
				$result=$this->db->get('configuration');
				echo $this->db->last_query()."</br>";
				$cnt=$result->num_rows();
				if($cnt==1)
				{
					$this->db->where('key',$key);
					$this->db->update('configuration',array('value'=>$val));
					echo $this->db->last_query()."</br>";
				}
				else
				{
					$data=array(
						'key'=>$key
					);
					$this->db->insert('configuration',$data);
					echo $this->db->last_query()."</br>";
				}
			}
		}
	}
	public function get_configuration()
	{
		return $this->db->get('configuration')->result_array();
	}
	/**
	 * fetch_language function.
	 * 
	 * @access public
	 * @return Logo Object
	 */
	public function fetch_language()
	{
		$result=$this->db->get('language');
		return $result->result_array();
	}
	/**
	 * get_logo_images function.
	 * 
	 * @access public
	 * @return Logo Object
	 */
	public function get_logo_images()
    {
        $this->db->where('mode','site_logo');       
        return $this->db->get("site_setting")->row_array();
    }
	public function get_loader_images()
    {
        $this->db->where('mode','loaderimage');       
        return $this->db->get("site_setting")->row_array();
    }
	 /**
	 * add_new_field function.
	 * 
	 * @access public
	 * @return status of creation column
	 */
	 public function add_new_field($field,$table)
	 {
		return true;
	 }
	 /**
	 * remove_old_field function.
	 * 
	 * @access public
	 * @return status of Removed column
	 */
	 public function remove_old_field($field,$table)
	 {
             return true;
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
	public function get_language_id($key)
	{
        $this->db->where('name',$key);
        $query=$this->db->get('language');
        $result=$query->row_array();
		if(empty($result))
        {
            return false;
        }
        else{
            return ($result);
        }
	}
	 public function get_languages()
	 {
		$this->db->where('mode','default_language');
		$this->db->or_where('mode','select_language');		 
        return $this->db->get("site_setting")->result_array();
	 }
	 /**
	 * block_detail function.
	 * 
	 * @access public
	 * @return Block Detail
	 */
	public function block_detail()
	{
		$result=$this->db->query("select CONCAT_WS(' ',fuser.fname,fuser.lname) AS Block_From, CONCAT_WS(' ', buser.fname, buser.lname) AS Block_To,buser.id,r.created_date from users buser,users fuser,blockuser r where r.userid=fuser.id and r.blockid=buser.id AND buser.id in(SELECT id FROM users where status =1)");
		return $result->result_array();
	}
	/**
	 * report_detail function.
	 * 
	 * @access public
	 * @return Block Detail
	 */
	public function report_detail()
	{
		$result=$this->db->query("select CONCAT_WS(' ',fuser.fname,fuser.lname) AS Report_From, CONCAT_WS(' ', buser.fname, buser.lname) AS Report_To,buser.id,r.created_date from users buser,users fuser,reporteuser r where r.report_from_id=fuser.id and r.report_to_id=buser.id AND buser.id in(SELECT id FROM users where status =1)");
		return $result->result_array();
	}
	/**
	 * block_users function.
	 * 
	 * @access public
	 * @return Block Users 
	 */
	public function block_users()
	{
		$result=$this->db->query("SELECT distinct b.id,fname,lname from users b,reporteuser r,blockuser bu where (r.report_to_id=b.id or bu.blockid=b.id) AND b.status='1'");		
		return $result->result_array();
	}
	/**
	 * get_new_datepreference function.
	 * 
	 * @access public
	 * @param  date_pref
	 * @return Date Preferance 
	 */
	public function get_new_datepreference($date_pref)
	{
		$value=explode(",",$date_pref);
		$str=array();
		foreach($value as $row)
		{
			$this->db->select('name');
			$this->db->from('datepreference');
			$this->db->where('id',$row);
			$result=$this->db->get()->row_array();
			$str[$row]=$result['name'];
		}
		return $str;
    }
	/**
	 * insert_setting function.
	 * 
	 * @access public
	 * @param  Post data
	 * @return id 
	 */
	public function insert_setting($data = array())
	{
		foreach($data as $key=>$value)
		{
			if($key!='btn_submit')
			{
				if($value=="on")
					$value=1;
				else
					$value=0;
				$this->db->where('mode',$key);
				$count=$this->db->get('site_setting')->num_rows();
				if($count)
				{
					$this->db->where('mode',$key);
					$this->db->update('site_setting',array('status'=>$value,'modified_date'=>date('Y-m-d H:i:s')));
					$id=$this->db->affected_rows();
				}
				else
				{
					$sql = "INSERT INTO `site_setting` ( 
					`mode`, 
					`status`,        
					`created_date`, 
					`modified_date`) 
					VALUES (	
						'" . $key . "',
						" . $value . ",
						'" . date('Y-m-d H:i:s') . "', 
						'" . date('Y-m-d H:i:s') . "'
					)";
					$this->db->query($sql);
					$id=$this->db->insert_id();
				}
			}
		}	
		if($id)
		{
			return $id;
		}
	}
	/**
	 * get_admod function.
	 * 
	 * @access public
	 * @return Site Setting Data
	 */
	public function get_admod()
	{
		$this->db->from('site_setting');
		$data=$this->db->get();
		return $data->result_array();
	}
	/**
	 * get_notifications function.
	 * 
	 * @access public
	 * @return Notification Detail
	 */
	public function get_notifications()
	{
		$this->db->from('notification');
		$data=$this->db->get();
		return $data->result_array();
	}
	/**
	 * insert_notification function.
	 * 
	 * @access public
	 * @param Post data
	 * @return id
	 */
	public function insert_notification($data = array())
	{
		$users=implode(",",$data['chk_status']);
		$ids=array();
		$ids=$data['chk_status'];
		$title=$data['title'];
		$message=$data['message'];
		$sql = "INSERT INTO `notification` ( 
            	`title`, 
            	`message`, 
				`users`,
            	`created_date`, 
            	`modified_date`) 
				VALUES (	
					" . $this->db->escape($data['title']) . ",
					" . $this->db->escape($data['message']) . ",
					'" . $users . "',
					'" . date('Y-m-d H:i:s') . "', 
					'" . date('Y-m-d H:i:s') . "'
				)";
				$this->db->query($sql);
				$id = $this->db->insert_id();
				for($i=0;$i<count($ids);$i++)
				{
					$res=$this->db->query("SELECT * FROM users u,userlogin ul where u.id=".$ids[$i]." and u.id=ul.user_id and ul.device_token!=''");
					$ress=$res->result_array();
					foreach ($ress as $res) {
						$device_token=$res['device_token'];
						$fdetail['message']=$message;
						if($res['device']=="ios")
							$this->send_push($device_token, "Cupid Love",($res["notificationcounter"]+1) ,$message,$type=4,$fdetail);
						if($res['device']=="android")
								$this->Pushnotification_model->send_push_android($device_token, "Cupid Love",($res["notificationcounter"]+1) ,$message,4,$fdetail);
					}
				}
				if($id)
				{
					return $id;
				}
	}
	public function send_push($token, $msg, $badge, $custom_msg,$type=1,$friend=null)
    {    	
        // Using Autoload all classes are loaded on-demand
		$deviceToken = $token;         
		$ctx = stream_context_create();
		// ck.pem is your certificate file
		stream_context_set_option($ctx, 'ssl', 'local_cert',$_SERVER['DOCUMENT_ROOT'].'/key/DevCupidLove.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', '');
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
			// Create the payload body
		$body['aps'] = array(
			'alert' => array(
			    'title' =>$msg,
                'body' => $custom_msg,
				'badge'=>$badge
			 ),
			'sound' => 'default'
		);
		/* data type */
		if($type==1)
		{
			$body['data'] = array(
				'type' => '1'
			);
		}
		else if($type==4)
		{
			$body['data'] = array(
					'message' => $friend['message'],
					'type' => '4',
				);
		}
		else
		{
			//type 2 and user firend details
			$body['data'] = array(
					'type' => $type,
					'friendid'=>$friend['friendid'],
					'friend_Fname'=>$friend['friend_Fname'],
					'friend_Lname'=>$friend['friend_Lname'],
					'friend_profileImg_url'=>$friend['friend_profileImg_url'],
				);
		}
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		 $result = fwrite($fp, $msg, strlen($msg));
		// Close the connection to the server
		fclose($fp);
		if (!$result)
			return 'Message not delivered' . PHP_EOL;
		else
			return 'Message successfully delivered' . PHP_EOL;
		//return true;		
    }
	/**
	 * get_datewise function.
	 * 
	 * @access public
	 * @param Condition
	 * @return Latest Added Friend
	 */
	public function get_datewise($where)
	{
		$this->db->where($where);
		return $this->db->get('users');
	}
	/**
	 * get_friend function.
	 * 
	 * @access public
	 * @param Current User id,Friend Id
	 * @return perticular frined detail
	 */
    public function get_friend($user_id,$friend_id){	
		$this->db->from('friends');
		$this->db->where('send_user_id',$friend_id);
        $this->db->where('receive_user_id',$user_id);
		return $this->db->get()->row();
	}
    /**
	 * get_friendlist function.
	 * 
	 * @access public
	 * @param Current User id,Friend Id
	 * @return friends detail
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
	 * @return religion detail
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
	 * @return ethnicity detail
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
	 * @return Default datepreference detail
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
	 * @return questions detail
	 */
    public function get_all_questions()
	{
        $this->db->from('questions');
		//$this->db->where('status', '1');
		return $this->db->get()->result_array();
    }
	/**
	 * block_user function.
	 * 
	 * @access public
	 * @return Block User Detail
	 */
	public function block_user($user_id)
	{
		$this->db->where('id',$user_id);
		return $this->db->update('users',array('status'=>'1'));
	}
	/**
	 * unblock_user function.
	 * 
	 * @access public
	 * @return void
	 */
	public function unblock_user($user_id)
	{		
		$this->db->where('id',$user_id);
		return $this->db->update('users',array('status'=>'0'));
	}
    /**
	 * get_notification function.
	 * 
	 * @access public
	 * @return notification send by admin
	 */
    public function get_notification($user_id)
	{
        $this->db->select('friends.*,users.*');
        $this->db->from('friends');
        $this->db->join('users', 'friends.send_user_id = users.id');
		$this->db->where('friends.receive_user_id', $user_id);
        $this->db->where('friends.approved', '0');
		return $this->db->get()->result_array();
    }
    public function get_friends_list($user_id)
	{
        $this->db->select('friends.*,users.*');
        $this->db->from('friends');
        $this->db->join('users', 'friends.send_user_id = users.id');
		$this->db->where('friends.receive_user_id', $user_id);
        $this->db->where('friends.approved', '1');
		return $this->db->get()->result_array();
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
	public function getrow($table,$condition=null)
	{
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->get($table)->row_array();
	}
	public function updaterow($table,$condition=null,$data)
	{
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->update($table,$data);
	}
	public function deleterow($table,$condition=null)
	{
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->delete($table);
	}
	public function insertrow($table,$data)
	{
		return $this->db->insert($table,$data);
	}
    public function geterror404($condition=null)
	{
		$this->db->select('error_404.*');
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->get('error_404')->result_array();
	}
     public function get_gallery_images($condition=NULL)
     {
        if($condition)
        {
            $this->db->where($condition);       
        }
        $this->db->order_by("img_key");
        return $this->db->get("usergallery")->result_array();
     }
     public function bulkupdate($data=null,$ids=null){
     	$flag=true;
     	if(!empty($ids)){		
     		/*for ($i=0; $i < sizeof($ids); $i++) { 
				$this->db->where(array("id"=>$ids[$i]));
				if(!$this->db->update("users",$data))
					$flag=false;
				// echo($this->db->last_query());
				// echo "<br/>";
     		}*/
     	}
     	return $flag;
     }	
     public function get_googleapiskey(){
     	$this->db->where("key='GOOGLE_PLACE_API_KEY'");
     	return $this->db->get("configuration")->row_array();
     }
     public function get_sampledata(){
     	$this->db->where("mode='sample_data'");
     	return $this->db->get("site_setting")->row_array();
     }
	 public function get_user_where_row($condition=null)
	 {
		 if(!empty($condition))
		 {
			 $this->db->where($condition);
		 }
		 $result=$this->db->get("users");
		 return $result->row();        
	 }
	 public function update_users($data,$condition)
     {
        $this->db->where($condition);
        return $this->db->update("users",$data);
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
}