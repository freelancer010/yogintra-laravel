<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lead extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check();
		$this->rbac->check_module_access();

		if ($this->uri->segment(2) != '')
			$this->rbac->check_operation_access();
	}

	public function viewAllData()
	{
		$this->load->view('allData');
	}

	public function index()
	{
		$this->load->view('leads');
	}

	public function addLeads()
	{
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name') ?? '';
			$data['number'] = $this->input->post('number') ?? '';
			$data['country'] = $this->input->post('country') ?? '';
			$data['state'] = $this->input->post('state') ?? '';
			$data['city'] = $this->input->post('city') ?? '';
			$data['source'] = $this->input->post('lead-source') ?? '';
			$data['email'] = $this->input->post('email') ?? '';
			$data['class_type'] = $this->input->post('class') ?? '';
			$data['call_from'] = $this->input->post('call-from') ?? '';
			$data['call_to'] = $this->input->post('call-to') ?? '';
			$data['message'] = $this->input->post('client-message') ?? '';
			$data['created_date'] = $this->input->post('date') ?? '';
			$data['package'] = $this->input->post('package') ?? '';
			$data['quotation'] = $this->input->post('quote') ?? '';
			$data['dempay'] = $this->input->post('demoPay') ?? '';
			$data['attempt1'] = $this->input->post('attempt1') ? $this->input->post('attempt1') : '';
			$data['attempt2'] = $this->input->post('attempt2') ? $this->input->post('attempt2') : '';
			$data['attempt3'] = $this->input->post('attempt3') ? $this->input->post('attempt3') : '';
			$data['attempt1Remarks'] = $this->input->post('remarks1') ? $this->input->post('remarks1') : '';
			$data['attempt2Remarks'] = $this->input->post('remarks2') ? $this->input->post('remarks2') : '';
			$data['attempt3Remarks'] = $this->input->post('remarks3') ? $this->input->post('remarks3') : '';
			$data['attempt1Date'] = $this->input->post('atemptDate1') ? $this->input->post('atemptDate1') : '';
			$data['attempt2Date'] = $this->input->post('atemptDate2') ? $this->input->post('atemptDate2') : '';
			$data['attempt3Date'] = $this->input->post('atemptDate3') ? $this->input->post('atemptDate3') : '';
			$data['attendeeName'] = $this->input->post('attendeeName') ? $this->input->post('attendeeName') : '';
			$data['created_by'] = $_SESSION['username'];
			$data['status'] = 1;

			$resp = $this->db->insert('leads', $data);

			if ($resp == true) {
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
			$this->load->view('addLeads');
		}
	}

	public function addCustomer()
	{
		$this->load->view('addCustomers');
	}

	public function getLeads()
	{
		$resp = [];
		$response = [];
		if (@$_POST['id']) {
			$id = $_POST['id'];
			$resp = $this->db->where('id', $id)->get('leads')->row_array();
			$response['trainers'] = $this->getTrainers();
			$response['paymentDetails'] = $this->getPayments($id);
		} else {
			$url = 'https://yogintra.com/wp-json/wp/v2/cf7/quote';

			$data = getCurl($url, [], 'GET');
			function myfunction($row)
			{
				$newrow = unserialize($row['form_value']);
				$object['form_id'] = $row['form_id'];
				$object['name'] = $newrow['your-name'];
				$object['number'] = $newrow['phone-number'];
				$object['email'] = $newrow['email-idl'];
				$object['country'] = $newrow['country'];
				$object['state'] = $newrow['state'];
				$object['city'] = $newrow['city'];
				// $object['source'] = $newrow['vx_url'] ? $newrow['vx_url'] : 'CRM';
				$object['source'] = 'WEBSITE';
				$object['class_type'] = $newrow['service-menu'][0];
				$object['call_from'] = $newrow['call-from'];
				$object['call_to'] = $newrow['call-to'];
				$object['message'] = $newrow['your-message'];
				$object['created_date'] = $row['form_date'];
				$object['created_by'] = 'sadmin';
				$object['dump'] = json_encode($row);
				return $object;
			}
			if ($data && @$data['code'] != 'empty_product') {
				$respons = array_map("myfunction", $data);
				foreach ($respons as $row) {
					$formId = $row['form_id'];
					$resp = $this->db->where(['form_id' => $formId])->get('leads')->num_rows();
					if ($resp == 0) {
						$this->db->insert('leads', $row);
					}
				}
			}
			if ($_SESSION['admin_role_id'] == 3) {
				if ($_POST['startDate'] != '') {
					$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
				}

				if ($_POST['endDate'] != '') {
					$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
				}
				$data1 = $this->db->where([
					'status' => 1
				])->group_start()
					->where('created_by', $_SESSION['username'])
					->or_where('created_by', '')
					->group_end()
					->order_by('created_date', 'desc')
					->get('leads')
					->result_array();

				$data2 = $this->db->where([
					'created_by' => 'sadmin',
					'status' => 1
				])->order_by('created_date', 'desc')->get('leads')->result_array();

				$data3 = $this->db->where([
					'created_by' => 'admin',
					'status' => 1
				])->order_by('created_date', 'desc')->get('leads')->result_array();

				$resp1 = array_merge($data1, $data2);
				$resp = array_merge($resp1, $data3);
			} else {
				if ($_POST['startDate'] != '') {
					$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
				}

				if ($_POST['endDate'] != '') {
					$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
				}
				$resp = $this->db->where([
					'status' => 1
				])->order_by('created_date', 'desc')->get('leads')->result_array();
			}
		}
		foreach ($resp as &$item) {
			if (isset($item['class_type'])) {
				$item['class_type'] = str_replace(' Session', '', $item['class_type']);
			}
		}
		if (count($resp) > 0) {
			$response['success'] = 1;
			$response['data'] = $resp;
		} else {
			$response['success'] = 0;
			$response['message'] = 'No data found!';
		}
		echo json_encode($response);
	}

	public function updatedata()
	{
		if ($this->input->post('leadId')) {
			$leadId = $this->input->post('leadId');
			$data['name'] = $this->input->post('name');
			$data['number'] = $this->input->post('number');
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			$data['source'] = $this->input->post('lead-source');
			$data['email'] = $this->input->post('email');
			$data['class_type'] = $this->input->post('class') ?? $this->input->post('hidden_class');
			$data['call_from'] = $this->input->post('call-from');
			$data['call_to'] = $this->input->post('call-to');
			$data['message'] = $this->input->post('client-message');
			$data['created_date'] = $this->input->post('date');
			$data['package'] = $this->input->post('package');
			$data['quotation'] = $this->input->post('quote');
			$data['dempay'] = $this->input->post('demoPay');
			$data['attempt1'] = $this->input->post('attempt1');
			$data['attempt2'] = $this->input->post('attempt2');
			$data['attempt3'] = $this->input->post('attempt3');
			$data['attempt1Remarks'] = $this->input->post('remarks1');
			$data['attempt2Remarks'] = $this->input->post('remarks2');
			$data['attempt3Remarks'] = $this->input->post('remarks3');
			$data['attempt1Date'] = $this->input->post('atemptDate1');
			$data['attempt2Date'] = $this->input->post('atemptDate2');
			$data['attempt3Date'] = $this->input->post('atemptDate3');
			$data['attendeeName'] = $this->input->post('attendeeName');
			$data['trainer_id'] = $this->input->post('trainer');
			// $data['created_by'] = $_SESSION['username'];
			$data['payTotrainer'] = $this->input->post('trainerPayment');
			$data['payableAmount'] = $this->input->post('payableAmount');

			$data['demDate'] = str_replace('T', ' ', $this->input->post('demDate'));
			$data['trainerPayDate'] = $this->input->post('trainerPayDate') ?? $this->input->post('trainerPayDate');

			$status = ($_POST['attempt1'] == 1 ? '3' : ($_POST['attempt1'] == 2 ? '4' : 0));

			if (!empty($status)) {
				$data['status'] = $status;
			}

			$data['payment_type'] = $this->input->post('payment_type');
			if (!empty($_POST['packageEndDate'])) {
				$data['package_end_date'] = $this->input->post('packageEndDate');
			}
			if ($this->input->post('payment_type') == 'Full Payment') {
				$data['full_payment'] = $this->input->post('totalPayAmount');
				$data['totalPayDate'] = str_replace('T', ' ', $this->input->post('totalPayDate'));
			} else if ($this->input->post('payment_type') == 'Partition Payment') {
				$data['full_payment'] = array_sum((array) $this->input->post('fullPayment'));
			}

			$this->db->where('id', $leadId);
			$resp = $this->db->update('leads', $data);

			if ($resp == true) {
				if ($this->input->post('payment_type') == 'Partition Payment') {
					$batchInsert = [];
					foreach ((array) $this->input->post('fullPayment') as $key => $row) {
						if ($row > 0) {
							$batchInsert[] = [
								'leadId' => $leadId,
								'amount' => $row,
								'created_date' => str_replace('T', ' ', $this->input->post('fullPaymentDate')[$key]),
								'created_by' => $_SESSION['username'],
								'status' => 1,
								'type' => 'lead'
							];
						}
                        
                        $totalPayDate_chnge = str_replace('T', ' ', $this->input->post('fullPaymentDate')[$key]);
					}

					if(!empty($totalPayDate_chnge)){
						$this->db->where(['id' => $leadId])->update('leads', ['totalPayDate' => $totalPayDate_chnge]);
					}
					

					if (count($batchInsert) > 0) {
						$this->db->where(['leadId' => $leadId, 'type' => 'lead'])->update('paymentdata', ['status' => 0]);
						$this->db->insert_batch('paymentdata', $batchInsert);
					}
				}

				// if (!empty($_POST['packageEndDate'])) {
				// 	$renew_data = [
				// 		'lead_id' => $leadId,
				// 		'renew_date' => date('Y-m-d H:i:s'),
				// 		'renew_amount' => $data['full_payment'],
				// 		'type' => 'leads',
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
		} else {
			$response = [
				'success' => 0,
				'message' => 'Some error occured!'
			];
		}
		echo json_encode($response);
	}

	public function changeStatus()
	{
		$id = $_POST['id'];

		$username = $_SESSION['username'];
		$aggingToUser = $this->db->where(['id' => $id])->update('leads', ['created_by' => $username]);

		$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 2]);
		if ($resp && $username) {
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

	public function getLeadById()
	{
		$id = $_POST['id'];
		$resp = $this->db->where('id', $id)->get('leads')->row_array();
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

	public function countLeads()
	{
		$resp = $this->db->where([
			'status' => 1
		])->order_by('id', 'desc')->get('leads')->result_array();

		echo count($resp);
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
		$resp = $this->db->order_by('id', 'desc')->get('leads')->result_array();
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
