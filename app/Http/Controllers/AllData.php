<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AllData extends CI_Controller
{
	public function viewAllData()
	{
		$this->load->view('allData');
	}


	public function allData()
	{
		if ($_POST['startDate'] != '') {
			$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
		}

		if ($_POST['endDate'] != '') {
			$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
		}
		$this->db->where_in('status', [1, 2, 3, 4, 5]);

		$resp = $this->db->order_by('created_date', 'desc')->get('leads')->result_array();

		foreach ($resp as &$item) {
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace(' Session', '', $item['class_type']);
			}
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace(' Booking', '', $item['class_type']);
			}
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online', 'Private Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online Yoga', 'Private Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online', 'Private Online Yoga', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online', 'Group Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online Yoga', 'Group Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online', 'Group Online Yoga', $item['class_type']);
			}
		}

		$resp2 = $this->db->order_by('created_date', 'desc')->get('yoga')->result_array();
		if (count($resp) > 0) {
			$response = [
				'success' => 1,
				'data' => $resp,
				'yoga' => $resp2
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}
		echo json_encode($response);
	}

	///rejectced data

	public function rejected()
	{
		if ($_SESSION['admin_role_id'] == 3) {
			$where = [
				'status' => 4,
				'created_by' => $_SESSION['username']
			];
		} else {
			$where = [
				'status' => 4
			];
		}
		$resp = $this->db->where($where)->order_by('id', 'desc')->get('leads')->result_array();
		foreach ($resp as &$item) {
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace(' Session', '', $item['class_type']);
			}
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace(' Booking', '', $item['class_type']);
			}
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online', 'Private Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online Yoga', 'Private Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Private Online', 'Private Online Yoga', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online', 'Group Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online Yoga', 'Group Online', $item['class_type']);
			}

			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace('Group Online', 'Group Online Yoga', $item['class_type']);
			}
		}
		if (count($resp) > 0) {
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

	public function rejectedView()
	{
		$this->load->view('rejected');
	}

	public function toReject()
	{
		$id = $_POST['id'];
		$resp = false;
		$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 4]);
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

	public function restrore()
	{
		$id = $_POST['id'];
		$resp = false;
		$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 1, 'attempt1' => 0]);
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
}