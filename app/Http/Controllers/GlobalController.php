<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GlobalController extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check();

		// $this->rbac->check_module_access();
		// if($this->uri->segment(3) != '')
		// $this->rbac->check_operation_access();
	}

	public function index()
	{
		$this->load->view('index');
	}

	public function counts()
	{
		if ($_SESSION['admin_role_id'] == 3) {
			$data1 = $this->db
				->where('status', 1)
				->group_start()
				->where('created_by', $_SESSION['username'])
				->or_where('created_by', null)
				->or_where('created_by', '')
				->group_end()
				->order_by('created_date', 'desc')
				->get('leads')
				->result_array();


			$data2 = $this->db->where([
				'created_by' => 'sadmin',
				'status' => 1
			])->order_by('created_date', 'desc')->get('leads')->result_array();

			$lead = array_merge($data1, $data2);
		} else {
			$lead = $this->db->where([
				'status' => 1
			])->order_by('created_date', 'desc')->get('leads')->result_array();
		}


		if ($_SESSION['admin_role_id'] == 3) {
			$data1 = $this->db->where([
				'created_by' => $_SESSION['username'],
				'status' => 1,
				'read_status' => 0
			])->order_by('created_date', 'desc')->get('leads')->result_array();

			$data2 = $this->db->where([
				'created_by' => 'sadmin',
				'status' => 1,
				'read_status' => 0
			])->order_by('created_date', 'desc')->get('leads')->result_array();

			$unreadLeads = array_merge($data1, $data2);
		} else {
			$unreadLeads = $this->db->where([
				'status' => 1
			])->order_by('created_date', 'desc')->get('leads')->result_array();
		}

		//recruits
		$unreadRecruits = $this->db->where([
			'is_trainer' => 0,
			'status_trainer' => 1,
			'read_status' => 0
		])->order_by('id', 'desc')->get('trainer')->result_array();
		$recruits = $this->db->where([
			'is_trainer' => 0,
			'status_trainer' => 1
		])->order_by('id', 'desc')->get('trainer')->result_array();


		//rejected
		$rejected = $this->db->where([
			'status' => 4
		])->order_by('id', 'desc')->get('leads')->result_array();


		//trainer
		$unreadTrainer = $this->db->where([
			'is_trainer' => 1,
			'status_trainer' => 1,
			'read_status' => 0
		])->order_by('id', 'desc')->get('trainer')->result_array();
		$trainer = $this->db->where([
			'is_trainer' => 1,
			'status_trainer' => 1
		])->order_by('id', 'desc')->get('trainer')->result_array();

		$yoga = $this->db->get('yoga')->result_array();
		$events = $this->db->get('events')->result_array();


		//customers
		if ($_SESSION['admin_role_id'] == 3) {
			$data1 = $this->db->where([
				'created_by' => $_SESSION['username'],
				'status' => 3
			])->order_by('created_date', 'desc')->get('leads')->result_array();

			$data2 = $this->db->where([
				'created_by' => 'sadmin',
				'status' => 3
			])->order_by('created_date', 'desc')->get('leads')->result_array();

			$customer = array_merge($data1, $data2);
		} else {
			$customer = $this->db->where([
				'status' => 3
			])->order_by('created_date', 'desc')->get('leads')->result_array();
		}

		//telecallers
		if ($_SESSION['admin_role_id'] == 3) {
			$where = [
				'status' => 2,
				'created_by' => $_SESSION['username']
			];
		} else {
			$where = [
				'status' => 2
			];
		}
		$telecaller = $this->db->where($where)->order_by('id', 'desc')->get('leads')->result_array();

		$where['class_type'] = 'all';

		$filterwhere = '';

		$query = "(SELECT class_type,sum(full_payment) full_payment,sum(payTotrainer) payTotrainer FROM leads WHERE status='3' and class_type != '' {$filterwhere} group by class_type) UNION ALL (SELECT 'Expense' class_type, '0' full_payment, sum(expenseAmount) payTotrainer FROM expense where 1=1 {$filterwhere} ) 
		UNION ALL (SELECT class_type, sum(totalPayAmount) full_payment, '0' payTotrainer FROM events where 1=1 {$filterwhere}  group by class_type)";

		$customers = $this->db->query($query)->result_array();

		if (count($customers) > 0) {

			$result = array(
				'lead' => count($lead),
				'unreadLeads' => count($unreadLeads),

				'customer' => count($customer),
				'telecaller' => count($telecaller),

				'recruits' => count($recruits),
				'unreadRecruits' => count($unreadRecruits),

				'trainer' => count($trainer),
				'unreadTrainer' => count($unreadTrainer),

				'rejected' => count($rejected),

				'yoga' => count($yoga),
				'events' => count($events),

				'totalCredit' => array_sum(array_column($customers, 'full_payment')),
				'totalDebit' => array_sum(array_column($customers, 'payTotrainer'))
			);
		} else {
			$result = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}

		echo json_encode($result);
	}
}
