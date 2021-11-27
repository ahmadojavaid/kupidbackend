<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function index(){
		redirect("admin/admin/login");
	}
}