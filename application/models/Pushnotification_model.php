<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// en
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class Pushnotification_model extends CI_Model {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Common_model');
		$this->PUSH_ENABLE_SANDBOX = $this->Common_model->get_key_configuration(array('key'=>'PUSH_ENABLE_SANDBOX'));
		$this->PUSH_SANDBOX_GATEWAY_URL = $this->Common_model->get_key_configuration(array('key'=>'PUSH_SANDBOX_GATEWAY_URL'));
		$this->PUSH_GATEWAY_URL = $this->Common_model->get_key_configuration(array('key'=>'PUSH_GATEWAY_URL'));
		$this->ANDROID_FCM_KEY = $this->Common_model->get_key_configuration(array('key'=>'ANDROID_FCM_KEY'));
	}
	public function send_push($token, $msg, $badge, $custom_msg,$type=1,$friend=null)
    {    		
		//echo $token;
		// Using Autoload all classes are loaded on-demand
		$deviceToken = $token;         
		$ctx = stream_context_create();
		// ck.pem is your certificate file
		stream_context_set_option($ctx, 'ssl', 'local_cert',FCPATH.'key/'.PEM_FILE);
		stream_context_set_option($ctx, 'ssl', 'passphrase', PUSH_PASSPHARSE);
		// Open a connection to the APNS server
		if($this->PUSH_ENABLE_SANDBOX=='true'){
			$fp = stream_socket_client($this->PUSH_SANDBOX_GATEWAY_URL, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		else{
			$fp = stream_socket_client($this->PUSH_GATEWAY_URL, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		if (!$fp)
		{	return 'Message not delivered' . PHP_EOL; }
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
			    'title' =>$msg,
                'body' => $custom_msg,
				'badge'=>0
			 ),
			'sound' => 'default'
		);
		/* data type */
		if($type==1){
				$body['data'] = array(
					'type' => '1'
				);
		}else{
			//type 2 and user firend details
			$body['data'] = array(
					'type' => $type,
					'friendid'=>$friend['friendid'],
					'friend_Fname'=>$friend['friend_Fname'],
					'friend_Lname'=>$friend['friend_Lname'],
					'friend_profileImg_url'=>$friend['friend_profileImg_url'],
					'friend_ejuser'=>$friend['friend_ejuser']
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
			echo 'Message not delivered' . PHP_EOL;
		else
			echo 'Message successfully delivered' . PHP_EOL;
		die;
		return true;
	}

	public function send_push_android($device_token, $mesg, $badge, $custom_msg,$type,$fdetail)
	{
		/*"notification"=>array(
			"body"=>"Cool offers. Get them before expiring!",
			"title"=>"Flat 50% discount",
			"icon"=>"appicon",
		),
		"data"=>array(
			"name"=>"LG LED TV S15",
			"product"=>"123",
			"final_price"=>"2500",
		)*/

		if($type==1){
			$fields = array
			(
				'to'		=> $device_token,
				"notification"=>array(
					"body"=>$custom_msg,
					"title"=>'Cupid love',
					"icon"=>"appicon",
				),
				'data'=>array(
					'type' =>$type,
					'message'=>$custom_msg,	
				),
			);
		}
		else if($type==4){
			$fields = array
			(
				'to'		=> $device_token,
				"notification"=>array(
					"body"=>$custom_msg,
					"title"=>'Cupid love',
					"icon"=>"appicon",
				),
				'data'=>array(
					'type' =>$type,
					'body'=>$custom_msg,	
				),
			);
		}
		else{
			$fields = array
			(
				'to'		=> $device_token,
				"notification"=>array(
					"body"=>$custom_msg,
					"title"=>'Cupid love',
					"icon"=>"appicon",
				),
				'data'=>array(
					'type' =>$type,
					'message'=>$custom_msg,	
					'friendid'=>$fdetail['friendid'],
					'friend_Fname'=>$fdetail['friend_Fname'],
					'friend_Lname'=>$fdetail['friend_Lname'],
					'friend_profileImg_url'=>$fdetail['friend_profileImg_url'],		
					'friend_ejuser'=>$fdetail['friend_ejuser']		
				),
			);
		}
		$headers = array
		(
			'Authorization: key=' . $this->ANDROID_FCM_KEY,
			'Content-Type: application/json'
		);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		return true;
	}
	/*public function send_push_android($device_token, $mesg, $badge, $custom_msg,$type,$fdetail)
	{
		#prep the bundle
			 $msg = array
				  (
				'body' 	=> $custom_msg,
				'title'	=> $mesg,
						'icon'	=> 'myicon',
						'sound' => 'mySound'
				  );
		if($type==1){
			$fields = array
			(
				'to'		=> $device_token,
				'data'=>array(
					'type' =>$type,
					'message'=>$custom_msg,	
				),
			);
		}
		else{
			$fields = array
			(
				'to'		=> $device_token,
				'data'=>array(
					'type' =>$type,
					'message'=>$custom_msg,	
					'friendid'=>$fdetail['friendid'],
					'friend_Fname'=>$fdetail['friend_Fname'],
					'friend_Lname'=>$fdetail['friend_Lname'],
					'friend_profileImg_url'=>$fdetail['friend_profileImg_url'],		
					'friend_ejuser'=>$fdetail['friend_ejuser']		
				),
			);
		}
		$headers = array
		(
			'Authorization: key=' . $this->ANDROID_FCM_KEY,
			'Content-Type: application/json'
		);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		return true;
	}*/
	public function send_push_android__OLD($device_token, $msg, $badge, $custom_msg,$fdetail)
	{
		return true;
	}
}
	?>