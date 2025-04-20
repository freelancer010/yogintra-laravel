<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AllTrainers extends CI_Controller
{
	public function __construct()
	{

		parent::__construct();
	}

	public function index()
	{
		$where = ['is_trainer' => 1,'show_trainer'=>1, 'status_trainer'=>1];
		$resp = $this->db->where($where)->order_by('id', 'desc')->get('trainer')->result_array();
		if (count($resp) > 0) {
			$response = [
				'success' => 1,
				'data' => $resp
			];
		} else {
			$response = [
				'success' => 0,
				'data' => 'No data found!'
			];
		}
		// echo json_encode($response);
		$this->load->view('trainers_view', $response);
	}

	public function getTrainers()
	{
		$where = ['is_trainer' => 1];
		$resp = $this->db->where($where)->order_by('id', 'desc')->get('trainer')->result_array();
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
}
