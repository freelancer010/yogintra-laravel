<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check();

		$this->rbac->check_module_access();

		if ($this->uri->segment(2) != '')
			$this->rbac->check_operation_access();
	}

	public function savedata()
	{
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name') ?? $this->input->post('name');
			$data['number'] = $this->input->post('number') ?? $this->input->post('number');
			$data['country'] = $this->input->post('country') ?? $this->input->post('country');
			$data['state'] = $this->input->post('state') ?? $this->input->post('state');
			$data['city'] = $this->input->post('city') ?? $this->input->post('city');
			$data['source'] = $this->input->post('lead-source') ?? $this->input->post('lead-source');
			$data['email'] = $this->input->post('email') ?? $this->input->post('email');
			$data['class_type'] = $this->input->post('class') ?? $this->input->post('class');
			$data['call_from'] = $this->input->post('call-from') ?? $this->input->post('call-from');
			$data['call_to'] = $this->input->post('call-to') ?? $this->input->post('call-to');
			$data['message'] = $this->input->post('client-message') ?? $this->input->post('client-message');
			$data['created_date'] = $this->input->post('date') ?? $this->input->post('date');
			$data['package'] = $this->input->post('package') ?? $this->input->post('package') ?? '0';
			$data['quotation'] = $this->input->post('quote') ?? $this->input->post('quote');
			$data['dempay'] = $this->input->post('demoPay') ?? $this->input->post('demoPay');
			$data['attempt1'] = '1';
			$data['attendeeName'] = $this->input->post('attendeeName') ?? $this->input->post('attendeeName');
			$data['trainer_id'] = $this->input->post('trainer') ?? $this->input->post('trainer');
			$data['created_by'] = $_SESSION['username'] ?? $_SESSION['username'];
			$data['payTotrainer'] = $this->input->post('trainerPayment') ?? $this->input->post('trainerPayment') ?? '0';
			$data['trainerPayDate'] = $this->input->post('trainerPayDate') ?? $this->input->post('trainerPayDate');
			$data['demDate'] = str_replace('T', ' ', $this->input->post('demDate'));
			if (!empty($_POST['packageEndDate'])) {
				$data['package_end_date'] = $this->input->post('packageEndDate');
			}
			$data['totalPayDate'] = $this->input->post('totalPayDate') ? $this->input->post('totalPayDate') : '';
			$data['payableAmount'] = $this->input->post('payableAmount') ? $this->input->post('payableAmount') : '';
			$data['payment_type'] = $this->input->post('payment_type');
			$data['status'] = 3;

			if ($this->input->post('payment_type') == 'Full Payment') {
				$data['full_payment'] = $this->input->post('totalPayAmount');
				$data['totalPayDate'] = str_replace('T', ' ', $this->input->post('totalPayDate'));
			} else if ($this->input->post('payment_type') == 'Partition Payment') {
				$data['full_payment'] = array_sum($this->input->post('fullPayment'));
			}


			$resp = $this->db->insert('leads', $data);
			$leadId = $this->db->insert_id();
			if ($resp == true) {
				if ($this->input->post('payment_type') == 'Partition Payment') {
					$batchInsert = [];
					foreach ($this->input->post('fullPayment') as $key => $row) {
						if ($row > 0) {
							$batchInsert[] = [
								'leadId' => $leadId,
								'amount' => $row,
								'created_date' => $this->input->post('fullPaymentDate')[$key],
								'created_by' => $_SESSION['username'],
								'status' => 1,
								'type' => 'lead'
							];
						}
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
				// 		'type'	=>	'lead',
				// 		'created_by' => $_SESSION['username']
				// 	];
				// 	$this->db->replace('package_renew_detail', $renew_data);
				// }
				$response = [
					'success' => 1,
					'message' => 'Customer Added Successfully'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Unable to add customer!'
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

	public function index()
	{
		$this->load->view('customers');
	}

	public function getCustomer()
	{
		if ($_SESSION['admin_role_id'] == 3) {

			$user_name = $_SESSION['username'];

			$where = "AND `leads`.`created_by` IN ('$user_name')";

			if (@$_POST['startDate'] != '') {
				$where .= " AND date(leads.created_date) >=  '{$_POST['startDate']}'";
			}

			if (@$_POST['endDate'] != '') {
				$where .= " AND date(leads.created_date) <=  '{$_POST['endDate']}'";
			}

			if (@$_POST['due_type'] != '') {
				if ($_POST['due_type'] == 'full_payment') {
					$where .= " AND `leads`.`payableAmount` > `leads`.`full_payment`";
					// --  AND `leads`.`created_by` IN ('$user_name','sadmin','admin')";
				} else {
					$where .= " AND `leads`.`payTotrainer`='0'";
				}
			}

			$query = "SELECT `leads`.*,
					 `trainer`.`name` as `trainerName`, 
					 `trainer`.`created_date` as `trainer_created_date`, 
					 `trainer`.`number` as `trainer_number`, 
					 `trainer`.`salary` 
					 
					FROM `leads` 
					LEFT JOIN `trainer` 
					ON `trainer`.`id` = `leads`.`trainer_id`
			 
			 		WHERE 1=1 AND `status` = 3 {$where}
					
					ORDER BY `created_date` DESC";

			$customers = $this->db->query($query)->result_array();

		} else {
			$where = '';
			if (@$_POST['startDate'] != '') {
				$where .= " AND date(leads.created_date) >=  '{$_POST['startDate']}'";
			}

			if (@$_POST['endDate'] != '') {
				$where .= " AND date(leads.created_date) <=  '{$_POST['endDate']}'";
			}

			if (@$_POST['due_type'] != '') {
				if ($_POST['due_type'] == 'full_payment') {
					$where .= " AND `leads`.`payableAmount` > `leads`.`full_payment`";
				} else {
					$where .= " AND `leads`.`payTotrainer`='0'";
				}
			}

			$query = "SELECT `leads`.*, `trainer`.`name` as `trainerName`, `trainer`.`created_date` as `trainer_created_date`, `trainer`.`number` as `trainer_number`, `trainer`.`salary` FROM `leads` LEFT JOIN `trainer` ON `trainer`.`id` = `leads`.`trainer_id`
			 WHERE 1=1 AND `status` = 3 {$where}
			ORDER BY `created_date` DESC";

			$customers = $this->db->query($query)->result_array();
		}
		if (count($customers) > 0) {
			$response = [
				'success' => 1,
				'data' => $customers
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}
		echo json_encode($response);
	}

	public function changeStatusToTelecalling()
	{
		$id = $_POST['id'];
		$resp = $this->db->where(['id' => $id])->update('leads', ['status' => 2]);
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

	public function changeStatusToTelecallingFromYogaCenter()
	{
		$lead_id = $_POST['lead_id'];
		$id = $_POST['id'];

		$resp = $this->db->where(['id' => $lead_id])->update('leads', ['status' => 2]);
		if ($resp) {
			$this->db->where('id', $id);
			$this->db->delete('yoga');
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
