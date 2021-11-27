<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class SiteErrorLog extends CI_Model {
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
	 * addData function.
	 * @param error,userid
	 * @access public
	 * @return void
	 */
	public function addData($error,$userid=0)
	{
		$ip=$this->input->ip_address(); 
		if ( ! $this->input->valid_ip($ip))
		{
			$ip="IP Note Found";
		}
		return $this->db->insert("site_error_log",array("erroemsg"=>$error,"userid"=>$userid,"client_ip"=>$ip));
	}
	/**
	 * getdata function.
	 * @param condition
	 * @access public
	 * @return error data
	 */
	public function getdata($condition=NULL)
	{
		$this->db->select('*');
		$this->db->from('site_error_log');
		$this->db->join('users', 'users.id = site_error_log.userid','left');
		if($condition)
		{
			$this->db->where($condition);
		}
		return $this->db->get()->result_array();
	}
	/**
	 * getrow function.
	 * @param condition
	 * @access public
	 * @return particular row error data
	 */
	public function getrow($condition=NULL)
	{
		if($condition)
		{
			$this->db->where($condition);
		}
		return $this->db->get("site_error_log")->row_array();
	}
}
?>