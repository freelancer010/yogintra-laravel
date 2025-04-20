<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounting extends CI_Controller
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
		if (@$_POST['class_type']) {
			$where = '';
			if ($_POST['class_type'] != 'all') {
				$where .= " AND temp.class_type = '{$_POST['class_type']}'";
			}
			if ($_POST['startDate'] != '') {
				$where .= " AND DATE(temp.created_date) >= '{$_POST['startDate']}'";
			}

			if ($_POST['endDate'] != '') {
				$where .= " AND DATE(temp.created_date) <= '{$_POST['endDate']}'";
			}

			$customQuery = "SELECT * FROM 
								(SELECT
									`leads`.`name`,
									`leads`.`full_payment`,
									`leads`.`created_date`,
									`leads`.`payTotrainer`,
									`leads`.`class_type`,
									`trainer`.`name` AS `trainerName`,
									`trainer`.`created_date` AS `trainer_created_date`,
									`trainer`.`salary`
								FROM
									`leads`
								LEFT JOIN `trainer` ON `trainer`.`id` = `leads`.`trainer_id`
								WHERE
									`status` IN (3)
							UNION ALL
								SELECT
									`yoga`.`client_name` as name,
									`yoga`.`totalPayAmount` as full_payment,
									`yoga`.`created_date`,
									0 `payTotrainer`,
									'Yoga Center' `class_type`,
									'' `trainerName`,
									'' `trainer_created_date`,
									0 `salary`
								FROM
									`yoga`
								WHERE
									`status` = 1
							) as temp WHERE 1=1 $where ORDER BY temp.created_date DESC";

			$customers = $this->db->query($customQuery)->result_array();

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
		} else {
			$this->load->view('ledger');
		}
	}

	public function ledger()
	{
		$this->load->view('ledger');
	}

	public function summary()
	{
		if (@$_POST['class_type']) {

			// $this->db->where_in('status', [2, 3]);

			// if ($_POST['class_type'] != 'all') {
			// 	$where['class_type'] = $_POST['class_type'];
			// }
			$filterwhere = '';
			if ($_POST['startDate'] != '') {
				$filterwhere .= " and created_date >= '{$_POST['startDate']}'";
			}

			if ($_POST['endDate'] != '') {
				$filterwhere .= " and created_date <= '{$_POST['endDate']}'";
			}

			$query = "(SELECT class_type, sum(full_payment) full_payment, sum(payTotrainer) payTotrainer FROM leads WHERE status NOT IN (0,6) and class_type != '' {$filterwhere} group by class_type) 
                    UNION ALL (SELECT 'Expense' class_type, '0' full_payment, sum(expenseAmount) payTotrainer FROM expense where 1=1 {$filterwhere} ) 
                    UNION ALL (SELECT class_type, sum(totalPayAmount) full_payment, '0' payTotrainer FROM events where 1=1 {$filterwhere}  group by class_type)
                    UNION ALL (SELECT 'Client Yoga Center' class_type, sum(totalPayAmount) full_payment, '0' payTotrainer FROM yoga where 1=1 {$filterwhere}  group by class_type)";
			$customers = $this->db->query($query)->result_array();

			// Combine and rename class types
			$combinedData = [];
			foreach ($customers as $customer) {
				$classType = $customer['class_type'];
				$fullPayment = (int) $customer['full_payment'];
				$payToTrainer = (int) $customer['payTotrainer'];

				// Combine Home Visit Yoga and Home Visit Yoga
				if ($classType === 'Home Visit Yoga' || $classType === 'Home Visit Yoga') {
					$classType = 'Home Visit Yoga';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				}

				// Private Online Yoga
				elseif ($classType === 'Private Online Yoga') {
					$classType = 'Private Online Yoga';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				}

				// Combine Group Online Yoga and Group Online Yoga
				elseif ($classType === 'Group Online Yoga' || $classType === 'Group Online Yoga') {
					$classType = 'Group Online Yoga';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				}

				// Combine Corporate Yoga
				elseif ($classType === 'Corporate Yoga') {
					$classType = 'Corporate Yoga';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				}
				// Combine TTC
				elseif ($classType === 'TTC') {
					$classType = 'TTC';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				} elseif ($classType === 'Client Yoga Center' || $classType === 'Yoga Center') {
					$classType = 'Yoga Center';
					if (isset($combinedData[$classType])) {
						$combinedData[$classType]['full_payment'] += $fullPayment;
						$combinedData[$classType]['payTotrainer'] += $payToTrainer;
					} else {
						$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
					}
				}
				// For other class types, keep them unchanged
				else {
					$combinedData[$classType] = ['class_type' => $classType, 'full_payment' => $fullPayment, 'payTotrainer' => $payToTrainer];
				}
			}

			// Convert the associative array back to indexed array
			$combinedData = array_values($combinedData);

			if (count($combinedData) > 0) {
				$response = [
					'success' => 1,
					'data' => $combinedData,
					'totalCredit' => array_sum(array_column($combinedData, 'full_payment')),
					'totalDebit' => array_sum(array_column($combinedData, 'payTotrainer')),
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'No data found!'
				];
			}

			echo json_encode($response);
		} else {
			$this->load->view('summary');
		}
	}

	public function officeExpences()
	{
		if (@$_POST['class_type']) {
			if ($_POST['startDate'] != '') {
				$this->db->where(['date(created_date)>=' => $_POST['startDate']]);
			}

			if ($_POST['endDate'] != '') {
				$this->db->where(['date(created_date)<=' => $_POST['endDate']]);
			}
			$customers = $this->db->order_by('created_date', 'desc')->get('expense')->result_array();
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
		} else {
			$this->load->view('officeExpences');
		}
	}

	public function addExpenses()
	{
		if ($_POST) {
			$data['expenseType'] = $this->input->post('expenseType');
			$data['expenseAmount'] = $this->input->post('expenseAmount');
			$data['created_by'] = $_SESSION['fullName'];
			$data['created_date'] = $this->input->post('expenseDate');
			$data['note'] = $this->input->post('note');
			$data['payee'] = $this->input->post('payee');
			$resp = $this->db->insert('expense', $data);
			if ($resp == true) {
				$response = [
					'success' => 1,
					'message' => 'You Expense Added Successfully'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Some error occured!'
				];
			}

			echo json_encode($response);
		} else {
			$this->load->view('addExpenses');
		}
	}

	public function editExpenses($id)
	{
		if ($_POST) {
			$data['expenseType'] = $this->input->post('expenseType');
			$data['expenseAmount'] = $this->input->post('expenseAmount');
			$data['created_date'] = $this->input->post('expenseDate');
			$data['note'] = $this->input->post('note');
			$data['payee'] = $this->input->post('payee');
			$this->db->where('id', $id);
			$resp = $this->db->update('expense', $data);
			if ($resp == true) {
				$response = [
					'success' => 1,
					'message' => 'You Expense Added Updated'
				];
			} else {
				$response = [
					'success' => 0,
					'message' => 'Some error occured!'
				];
			}

			echo json_encode($response);
		} else {
			$this->db->where('id', $id);
			$data['expense'] = $this->db->get('expense')->row();
			$this->load->view('editExpenses', $data);
		}
	}

	public function deleteExpenses($id)
	{
		$this->db->where('id', $id);
		echo $this->db->delete('expense');
	}

}
