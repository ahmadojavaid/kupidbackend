<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin_model class.
 * 
 * @extends CI_Model
 */
class Common_model extends CI_Model {
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
	public function fetch_data($table,$condition=null)
	{
		if(!empty($condition))
			$this->db->where($condition);
		return $this->db->get($table)->result_array();
	}
	public function rtl_lang($condition){
		$this->db->where($condition);
		$result=$this->db->get("language")->row_array();
		if(!empty($result)){
			return true;
		}
		else{
			return false;
		}
	}
	public function get_selectedLanguage(){
		$this->db->select("status");
		$this->db->where('mode="select_language"');
		return $this->db->get("site_setting")->row_array();
	}
	public function get_key_configuration($condition=null){
		//echo "<pre>";print_r($data);die;
		$this->db->select("value");
		$this->db->where($condition);
		return $this->db->get("configuration")->row('value');
	}
	public function is_rtl(){
		$q=$this->db->query("select rtl from language where name = (select status from site_setting where mode='default_language')");
		$q=$q->row_array();
		return $q['rtl'];
	}
	public function get_logo(){
		$this->db->where("mode='site_logo'");
		$query = $this->db->get("site_setting")->row_array();
		return $query['status'];
	}
}