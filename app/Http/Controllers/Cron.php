<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}

	public function updateRenewData()
	{
		$this->db->where(['package_end_date >=' => date('Y-m-d H:i:s'), 'status' => 5])->update('leads', ['status' => 3]);
		$this->db->where(['e_date >=' => date('Y-m-d H:i:s'), 'status' => 5])->update('yoga', ['status' => 1]);
		$this->db->where(['package_end_date <' => date('Y-m-d H:i:s'), 'renew_skip' => 0, 'package_end_date is not null'])->update('leads', ['status' => 5]);
		$this->db->where(['e_date <' => date('Y-m-d H:i:s'), 'renew_skip' => 0, 'package_end_date is not null'])->update('yoga', ['status' => 5]);

		$response = [
			'success' => 1,
			'message' => 'Lead status updated successfully'
		];

		echo json_encode($response);
	}
}