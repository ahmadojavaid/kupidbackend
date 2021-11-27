<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class online_users_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}

	//getting all session data
	function get_all_session_data(){
		$query=$this->db->get('ci_sessions');
		return $query->result_array();
	}
	function get_data(){

	   $array_items = array('username', 'email', 'logged_in');
	   $this->session->unset_userdata($array_items);
	   $query = $this->db->query('SELECT * FROM ci_sessions');
	   foreach ($query->result() as $row) { 
	          $user_data = $row->data;
	          $online=$user_data['logged_in'];
	          $online=$row->userdata('logged_in');
	          if ($online) $i++;
	          }
	   $data['stillOnline'] = $query->num_rows();
	   echo "<pre>";
	   print_r($data);
	   echo "</pre>";
	}
}
?>