<?php
defined('BASEPATH') or exit('No direct script access allowed');

class YogaBooking extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check();

		// $this->rbac->check_module_access();

		// if($this->uri->segment(2) != '')
		// $this->rbac->check_operation_access();
	}

	public function index()
	{
		if (@$_POST['yoga'] == 'getData') {

			if (@$_POST['startDate'] != '') {
				$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
			}

			if (@$_POST['endDate'] != '') {
				$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
			}

			if (@$_POST['due_type'] != '') {
				$this->db->where('totalPayAmount < package');
			}

			$this->db->where('status', 1);

			$resp = $this->db->order_by('created_date', 'desc')->get('yoga')->result_array();
			if (count($resp) > 0) {
				$response['success'] = 1;
				$response['data'] = $resp;
			} else {
				$response['success'] = 0;
				$response['message'] = 'No data found!';
			}
			echo json_encode($response);
		} else {
			$this->load->view('yoga');
		}
	}
	public function editEvents()
	{
		$this->load->view('editYoga');
	}
	public function addEvents()
	{
		if ($this->input->post('name')) {
			$data['client_name'] = $this->input->post('name');
			$data['client_number'] = $this->input->post('number');
			$data['email'] = $this->input->post('email');
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			$data['class_type'] = $this->input->post('class_type');
			$data['event_name'] = $this->input->post('eventName');
			$data['created_date'] = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d h:i:s');
			$data['created_by'] = $_SESSION['username'];
			$data['s_date'] = $this->input->post('date') ?? $this->input->post('start_date');
			$data['e_date'] = $this->input->post('date') ?? $this->input->post('end_date');
			$data['package'] = $this->input->post('package');
			$data['payment_type'] = $this->input->post('payment_type');

			if ($this->input->post('payment_type') == 'Full Payment') {
				$data['totalPayAmount'] = $this->input->post('totalPayAmount');
				$data['totalPayDate'] = $this->input->post('totalPayDate');
			} else if ($this->input->post('payment_type') == 'Partition Payment') {
				$data['totalPayAmount'] = array_sum($this->input->post('fullPayment'));
				$data['totalPayDate'] = $this->input->post('fullPaymentDate[0]');
			}

			$eventId = '';
			if (@$_POST['eventId']) {
				$eventId = $this->input->post('eventId');
				$this->db->where('id', $eventId);
				$resp = $this->db->update('yoga', $data);
			} else {
				$resp = $this->db->insert('yoga', $data);
				$eventId = $this->db->insert_id();
			}

			if ($resp == true) {
				if ($this->input->post('payment_type') == 'Partition Payment') {
					$batchInsert = [];
					foreach ($this->input->post('fullPayment') as $key => $row) {
						if ($row > 0) {
							$batchInsert[] = [
								'leadId' => $eventId,
								'amount' => $row,
								'created_date' => str_replace('T', ' ', $this->input->post('fullPaymentDate')[$key]),
								'created_by' => $_SESSION['username'],
								'status' => 1,
								'type' => 'yoga'
							];
						}
					}

					if (@$_POST['eventId']) {
						if (count($batchInsert) > 0) {
							$this->db->where(['leadId' => $eventId, 'type' => 'yoga'])->update('paymentdata', ['status' => 0]);
							$this->db->insert_batch('paymentdata', $batchInsert);
						}
					} else {
						if (count($batchInsert) > 0) {
							$this->db->insert_batch('paymentdata', $batchInsert);
						}
					}
				}

				// if (!empty($data['e_date']) && empty($_POST['eventId'])) {
				// 	$renew_data = [
				// 		'lead_id' => $eventId,
				// 		'renew_date' => date('Y-m-d H:i:s'),
				// 		'renew_amount' => $data['totalPayAmount'],
				// 		'type' => 'yoga',
				// 		'created_by' => $_SESSION['username'],
				// 		'created_date' => date('Y-m-d H:i:s')
				// 	];
				// 	$this->db->replace('package_renew_detail', $renew_data);
				// }

				$response = [
					'success' => 1,
					'message' => 'Inserted Successfully'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Some error occured!'
				];
			}

			echo json_encode($response);
		} else {
			$this->load->view('addYoga');
		}
	}

	public function getBookingProfile()
	{
		if (@$_POST['bookingId']) {
			$id = $_POST['bookingId'];
			$resp = $this->db->where('id', $id)->get('yoga')->row_array();
			$resp['paymentDetails'] = $this->getPayments($id);
			if (count($resp) > 0) {
				$response['renew_details'] = $this->db->where(['lead_id' => $id, 'type' => 'yoga'])->get('package_renew_detail')->result_array();
				$response['success'] = 1;
				$response['data'] = $resp;
			} else {
				$response['success'] = 0;
				$response['message'] = 'No data found!';
			}
			echo json_encode($response);
		} else {
			$this->load->view('yogaDetails');
		}
	}

	public function getPayments($leadId)
	{
		$where = ['status' => 1, 'leadId' => $leadId, 'type' => 'yoga'];
		$response = $this->db->where($where)->get('paymentdata')->result_array();
		return $response;
	}
	public function deleteData()
	{
		$id = $_POST['id'];
		$resp = $this->db->where('id', $id)->delete('yoga');
		if ($resp) {
			$response = [
				'success' => 1,
				'message' => 'Event deleted Successfully'
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
