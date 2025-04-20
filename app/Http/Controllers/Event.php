<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends CI_Controller {
	
	public function __construct(){

		parent::__construct();

		auth_check();

		// $this->rbac->check_module_access();

		// if($this->uri->segment(2) != '')
		// $this->rbac->check_operation_access();
	}
	
	public function index()
	{
 		if(@$_POST['event']=='getData'){
			// $curl = curl_init();
			
			// curl_setopt_array($curl, array(
			// 	CURLOPT_URL => 'https://yogintra.com/wp-json/wc/v2/orders',
			// 	CURLOPT_RETURNTRANSFER => true,
			// 	CURLOPT_ENCODING => '',
			// 	CURLOPT_MAXREDIRS => 10,
			// 	CURLOPT_TIMEOUT => 0,
			// 	CURLOPT_FOLLOWLOCATION => true,
			// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			// 	CURLOPT_CUSTOMREQUEST => 'GET',
			// 	CURLOPT_HTTPHEADER => array(
			// 		'Authorization: Basic Y2tfMGRlMjFmMWI5YjFhYWJkM2IyMThkMGYwZDZiM2VhNDA0MjFhMjQ5MDpjc185MDdkNjQ2MWMyNDFlN2ZhNmU2M2YzNTY4NjQ1YjBkNWU2OGI3YTk0'
			// 	),
			// ));
			
			// $data = curl_exec($curl);
			// curl_close($curl);

			// $data=json_decode($data,true);

			// function myfunction($row){
			// 	$object['id'] = $row['id'];
			// 	$object['name'] = $row['billing']['first_name'];
			// 	$object['date_created'] = $row['date_created'];
			// 	$object['status'] = $row['status'];
			// 	$object['total'] = $row['total'];
			// 	return $object;
			// }
			// $resp = array_map("myfunction",$data);
			if(@$_POST['startDate'] != ''){
				$this->db->where(['date(created_date)>='=>  $_POST['startDate']]);
			}

			if(@$_POST['endDate'] != ''){
				$this->db->where(['date(created_date)<='=>  $_POST['endDate']]);
			}

			if(@$_POST['due_type'] != ''){
				$this->db->where('totalPayAmount < package');
			}

			$resp = $this->db->get('events')->result_array();
			foreach ($resp as &$event) {
                if (isset($event['class_type'])) {
                    // Convert 'TTC' to 'TTC'
                    if ($event['class_type'] == 'TTC') {
                        $event['class_type'] = 'TTC';
                    }
                }
            }
			if (count($resp) > 0) {
				$response['success'] =1;
				$response['data'] =$resp;
			} else {
				$response['success'] = 0;
				$response['message'] ='No data found!';
			}
			echo json_encode($response);
		}else{
			$this->load->view('events');
		}
	}


	public function editEvents(){
		$this->load->view('editEvents');
	}
	public function addEvents()
	{
		if ($this->input->post('name')) {
			$data['client_name'] 	= $this->input->post('name');
			$data['event_name'] 	= $this->input->post('eventName');
			$data['client_number'] = $this->input->post('number');
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			$data['email'] = $this->input->post('email');
			$data['class_type'] = $this->input->post('class');
			$data['created_date'] = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d h:i:s');
			$data['package'] = $this->input->post('package');
			$data['payment_type'] = $this->input->post('payment_type');

			if($this->input->post('payment_type') == 'Full Payment'){
				$data['totalPayAmount'] = $this->input->post('totalPayAmount');
				$data['totalPayDate'] = $this->input->post('totalPayDate');
			}else if($this->input->post('payment_type')  == 'Partition Payment'){
				$data['totalPayAmount'] = array_sum($this->input->post('fullPayment'));
				$data['totalPayDate'] = $this->input->post('fullPaymentDate[0]');
			}

			$eventId = '';
			if(@$_POST['eventId']){
				$eventId = $this->input->post('eventId');
				$this->db->where('id', $eventId);
				$resp = $this->db->update('events', $data);
			}else{
				$resp = $this->db->insert('events', $data);
				$eventId = $this->db->insert_id();
			}

			if ($resp == true) {
				if($this->input->post('payment_type') == 'Partition Payment'){
					$batchInsert = [];
					foreach($this->input->post('fullPayment') as $key => $row){
						if($row>0){
							$batchInsert[] = [
								'leadId' 		=> $eventId,
								'amount' 		=> $row,
								'created_date'	=> str_replace('T', ' ',$this->input->post('fullPaymentDate')[$key]),
								'created_by'	=> $_SESSION['username'],
								'status' 		=> 1,
								'type'			=> 'event'
							];
						}
					}

					if(@$_POST['eventId']){
						if(count($batchInsert)>0){
							$this->db->where(['leadId'=>$eventId,'type'=>'event'])->update('paymentdata',['status'=>0]);
							$this->db->insert_batch('paymentdata',$batchInsert);
						}
					}else{
						if(count($batchInsert)>0){
							$this->db->insert_batch('paymentdata',$batchInsert);
						}
					}
					
				}
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
			$this->load->view('addEvents');
		}
	}

	public function getBookingProfile()
	{
		if(@$_POST['bookingId']){
			$id = $_POST['bookingId'];
			$resp = $this->db->where('id', $id)->get('events')->row_array();
			$resp['paymentDetails'] = $this->getPayments($id);
			if (count($resp) > 0) {
				$response['success'] =1;
				$response['data'] =$resp;
			} else {
				$response['success'] = 0;
				$response['message'] ='No data found!';
			}
			echo json_encode($response);
		}else{
			$this->load->view('bookingDetails');
		}
	}

	public function getPayments($leadId)
	{
		$where = ['status' => 1,'leadId'=>$leadId,'type'=>'event'];
		$response = $this->db->where($where)->get('paymentdata')->result_array();
		return $response;
	}
	public function deleteData()
	{
		$id = $_POST['id'];
		$resp = $this->db->where('id', $id)->delete('events');
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
