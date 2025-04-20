<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Telecalling extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check();

		$this->rbac->check_module_access();

		if ($this->uri->segment(2) != '')
			$this->rbac->check_operation_access();
	}

	public function index()
	{
		$this->load->view('telecalling');
	}

	public function getTellcalling()
	{
		error_reporting(0);
		if ($_POST['startDate'] != '') {
			$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
		}

		if ($_POST['endDate'] != '') {
			$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
		}
		if ($_SESSION['admin_role_id'] == 3) {
			$where = [
				'status' => 2,
				'created_by' => $_SESSION['username']
			];
		} else {
			$where = [
				'status' => 2,
			];
		}
		$resp = $this->db->where($where)->order_by('created_date', 'desc')->get('leads')->result_array();

		if (count($resp) > 0) {
			// Iterate through the result array and remove "Session" from the "class_type" values
			foreach ($resp as &$item) {
				if (isset($item['class_type'])) {
					$item['class_type'] = str_replace(' Session', '', $item['class_type']);
				}
			}

			$response = [
				'success' => 1,
				'data' => $resp
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}

		echo json_encode($response);
	}

	public function changeStatus()
	{
		$id = $_POST['id'];

		$dataM = $this->db->select('*')->where(['id' => $id])->get('leads')->row_array();

		if ($dataM['class_type'] == 'Yoga Center') {
			$dataMain = [
				'client_name' => $dataM['name'],
				'client_number' => $dataM['number'],
				'email' => $dataM['email'],
				'country' => $dataM['country'],
				'state' => $dataM['state'],
				'city' => $dataM['city'],
				'lead_transfer_id' => $dataM['id']
			];
			$this->db->insert('yoga', $dataMain);

			// $data = $this->db->select('attempt1,attempt2,attempt3')->where(['id' => $id])->get('leads')->row_array();
			// $resp = false;

			$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 6]);

		} else {
			$data = $this->db->select('attempt1,attempt2,attempt3')->where(['id' => $id])->get('leads')->row_array();
			$resp = false;
			if ($data['attempt1'] == 1 || $data['attempt2'] == 1 || $data['attempt3'] == 1) {
				$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 3]);
			}
		}

		if ($resp) {
			$response = [
				'success' => 1,
				'message' => 'Status Changed Successfully'
			];
		} else {
			$response = [
				'success' => 1,
				'message' => 'No attempts for telecalling found!'
			];
		}
		echo json_encode($response);
	}

	public function changeStatusToLeads()
	{
		$id = $_POST['id'];
		$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 1]);
		if ($resp) {
			$response = [
				'success' => 1,
				'message' => 'Status Changed Successfully'
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}
		echo json_encode($response);
	}

	public function deleteData()
	{
		$id = $_POST['id'];
		$resp = $this->db->where('id', $id)->update('leads', ['status' => 0]);
		if ($resp) {
			$response = [
				'success' => 1,
				'message' => 'Lead deleted Successfully'
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No records found!'
			];
		}
		echo json_encode($response);
	}
}