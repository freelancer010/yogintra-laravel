<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EditProfile extends CI_Controller {

	public function __construct(){

		parent::__construct();

		auth_check();

		$this->rbac->check_module_access();

		if($this->uri->segment(2) != '')
		$this->rbac->check_operation_access();
	}
	
	public function index()
	{
		$this->load->view('edit-profile');
	}

	public function getProfile($id){
		// $id = 2;
		$resp = $this->db->where(['id'=>$id])->get('leads')->row_array();
		echo json_encode($resp);
	}
}
