<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
		$this->load->view('profile');
	}

	public function getProfile()
	{
		$id = $_POST['id'];
		$response['leads'] = $this->db->select('leads.*, trainer.name as trainerName')->where(['leads.id' => $id])->from('leads')->join('trainer', 'trainer.id = leads.trainer_id', 'LEFT')->get()->row_array();

		$response['renew_details'] = $this->db->where('lead_id', $id)->get('package_renew_detail')->result_array();
		// Remove "session" from class_type
		$response['leads']['class_type'] = str_replace(" Session", "", $response['leads']['class_type']);

		$response['leads']['class_type'] = str_replace(" Booking", "", $response['leads']['class_type']);

		$response['trainers'] = $this->getTrainers();
		$response['paymentDetails'] = $this->getPayments($id);
		echo json_encode($response);

	}


	public function editProfile()
	{
		if ($this->input->post('leadId')) {
			$data['name'] = $this->input->post('name');
			$data['number'] = $this->input->post('number');
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			$data['source'] = $this->input->post('lead-source');
			$data['email'] = $this->input->post('email');
			$data['class_type'] = $this->input->post('class');
			$data['call_from'] = $this->input->post('call-from');
			$data['call_to'] = $this->input->post('call-to');
			$data['message'] = $this->input->post('message');

			$resp = $this->db->where(['id' => $this->input->post('leadId')])->update('leads', $data);
			if ($resp == true) {
				$response = [
					'success' => 1,
					'message' => 'Updated Successfully'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Some error occured!'
				];
			}
			echo json_encode($response);
		} else {
			$id = $_GET['id'];
			$resp['row'] = $this->db->where(['id' => $id])->get('leads')->row_array();
			$this->load->view('edit-profile', $resp);
		}
	}

	public function changeReadStatus()
	{
		if ($this->input->post('id')) {
			$data['read_status'] = '1';

			$resp = $this->db->where(['id' => $this->input->post('id')])->update('leads', $data);
			if ($resp == true) {
				$response = [
					'success' => 1,
					'message' => 'Updated Successfully'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Some error occured!'
				];
			}
			echo json_encode($response);
		} else {
			$id = $_GET['id'];
			$resp['row'] = $this->db->where(['id' => $id])->get('leads')->row_array();
			$this->load->view('edit-profile', $resp);
		}
	}

	public function getTrainers()
	{
		$where = ['is_trainer' => 1];
		$response = $this->db->where($where)->order_by('id', 'desc')->get('trainer')->result_array();
		return $response;
	}

	public function getPayments($leadId)
	{
		$where = ['status' => 1, 'leadId' => $leadId, 'type' => 'lead'];
		$response = $this->db->where($where)->get('paymentdata')->result_array();
		return $response;
	}
}