<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Trainer extends CI_Controller
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
		$this->load->view('welcome_message');
	}

	public function addRecruit()
	{
		$this->load->view('addRecruits');
	}

	public function recruiter()
	{
		if(@$_POST['recruiter']){
			$url = 'https://yogintra.com/wp-json/wp/v2/cf7/recruitement';

			$data = getCurl($url, [], 'GET');
			function myfunction($row)
			{
				$newrow = unserialize($row['form_value']);
				$object['form_id'] = $row['form_id'];
				$object['form_post_id'] = $row['form_post_id'];
				$object['created_date'] = $row['form_date'];
				$object['cfdb7_status'] = $newrow['cfdb7_status'];
				$object['name'] = $newrow['your-name'];
				$object['number'] = $newrow['phone-number'];
				$object['email'] = $newrow['email-idl'];
				$object['dob'] = $newrow['dob'];
				$object['gender'] = $newrow['gender'][0];
				$object['country'] = $newrow['country'];
				$object['state'] = $newrow['state'];
				$object['city'] = $newrow['city'];
				$object['Education'] = $newrow['Education'];
				$object['experience'] = $newrow['experience'];
				$object['certification'] = $newrow['certification'];
				$object['Other_Certificate'] = $newrow['Other-Certificate'];
				$object['address'] = $newrow['your-address'];
				$object['vx_width'] = $newrow['vx_width'];
				$object['vx_height'] = $newrow['vx_height'];
				// $object['cv_filecfdb7_file'] = $newrow['cv-filecfdb7_file']?$newrow['cv-filecfdb7_file']:'';
				$object['cv_filecfdb7_file'] = '';
				$object['cf7mls_step_1'] = $newrow['cf7mls_step-1'];
				$object['cf7mls_step_2'] = $newrow['cf7mls_step-2'];
				$object['cf7mls_step_3'] = $newrow['cf7mls_step-3'];
				$object['dump'] = json_encode($newrow);
				return $object;
			}
			
			if($data && @$data['code'] != 'empty_product'){
				$respons = array_map("myfunction", $data);
		
				foreach ($respons as $row) {
					$formId = $row['form_id'];
					$resp = $this->db->where(['form_id' => $formId])->get('trainer')->num_rows();
					if ($resp == 0) {
						$this->db->insert('trainer', $row);
					}
				}
			}
			if($_POST['startDate'] != ''){
				$this->db->where(['date(created_date)>='=>  $_POST['startDate']]);
			}

			if($_POST['endDate'] != ''){
				$this->db->where(['date(created_date)<='=>  $_POST['endDate']]);
			}
			$resp = $this->db->where([
				'is_trainer' => 0,
				'name <>' => '',
				'status_trainer' => 1
				])->order_by('created_date', 'desc')->get('trainer')->result_array();
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
		}else{
			$this->load->view('recruiter');
		}
	}

	public function viewTrainersProfile()
	{
		if(@$_POST['id']){
			$id = $_POST['id'];
			$resp = $this->db->where(['id' => $id])->get('trainer')->row_array();
			$response = [
				'success' => 1,
				'data' => $resp
			];
			echo json_encode($response);
		}else{
			$this->load->view('trainerProfile');
		}
	}

	public function savedata()
	{
		if ($this->input->post('name')) {
			$id = $this->input->post('trainerId')??'';
			$data['name'] = $this->input->post('name');
			$data['number'] = $this->input->post('number');
			$data['email'] = $this->input->post('email');
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			$data['message'] = $this->input->post('client-message');
			$data['created_date'] = $this->input->post('date');
			$data['Education'] = $this->input->post('education');
			$data['experience'] = $this->input->post('experience');
			$data['certification'] = $this->input->post('certification');
			$data['dob'] = $this->input->post('dob');
			$data['gender'] = $this->input->post('gender');
			$data['Other_Certificate'] = $this->input->post('Other_Certificate');
			$data['address'] = $this->input->post('address');
			$data['package'] = $this->input->post('package') ? $this->input->post('package'):'';

			// profile image
			if(@$_FILES['profileImage']){
				$target_dir = "uploads/";
				$target_file = $target_dir .date('dmYHis'). basename($_FILES["profileImage"]["name"]);
				$uploadedFile = move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file);
				if ($uploadedFile) {
					$data['profile_image'] = $target_file;
				}
			}
			//trainer doc
			if(@$_FILES['trainerDocumnt']){
				$target_dir2 = "trainerDoc/";
				$target_file2 = $target_dir2 .date('dmYHis'). basename($_FILES["trainerDocumnt"]["name"]);
				$uploadedFile2 = move_uploaded_file($_FILES["trainerDocumnt"]["tmp_name"], $target_file2);
				if ($uploadedFile2) {
					$data['doc'] = $target_file2;
				}
			}

			//trainer cv
			if(@$_FILES['trainerCv']){
				$target_dir3 = "TrainerCV/";
				$target_file3 = $target_dir3.date('dmYHis').basename($_FILES["trainerCv"]["name"]);
				$uploadedFile2 = move_uploaded_file($_FILES["trainerCv"]["tmp_name"], $target_file3);
				if ($uploadedFile2) {
					$data['cv_filecfdb7_file'] = $target_file3;
				}
			}

			$resp = false;
			if($id!=''){
				$resp = $this->db->where(['id'=>$id])->update('trainer', $data);
			}else{
				$resp = $this->db->insert('trainer', $data);
			}
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
		$status = $_POST['status'] == 0 ? 1 : 0;
		$resp = $this->db->where(['id' => $id])->update('trainer', ['is_trainer' => $status]);
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
		$resp = $this->db->where('id', $id)->update('trainer', ['status_trainer' => 0]);
		if ($resp) {
			$response = [
				'success' => 1,
				'message' => 'Deleted Successfully'
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}
		echo json_encode($response);
	}

	public function viewTrainers()
	{
		if(@$_POST['trainers'] && $_POST['trainers'] == 'trainers'){
			if($_POST['startDate'] != ''){
				$this->db->where(['date(created_date)>='=>  $_POST['startDate']]);
			}

			if($_POST['endDate'] != ''){
				$this->db->where(['date(created_date)<='=>  $_POST['endDate']]);
			}
			$where = ['is_trainer' => 1,'status_trainer'=>1];
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
		}else{
			$this->load->view('trainers');
		}
	}

	public function viewTrainerbyId()
	{
		if(@$_POST['id']){
			$id = $_POST['id'];
			$resp = $this->db->where(['id' => $id])->get('trainer')->row_array();
			$response = [
				'success' => 1,
				'data' => $resp
			];
			echo json_encode($response);
		}else{
			$this->load->view('edit-trainer-profile');
		}
	}

	public function changeReadStatus()
	{
		if($this->input->post('id'))
		{
			$data['read_status']='1';

			$resp=$this->db->where(['id'=>$this->input->post('id')])->update('trainer',$data);
			if($resp==true){
				$response = [
					'success'=> 1,
					'message'	=> 'Updated Successfully'
				];
			}else{
				$response = [
					'success'=> 0,
					'message'	=> 'Some error occured!'
				];
			}
			echo json_encode($response);
		}else{
			$this->load->view('leads');
		}
	}

	public function showTrainer()
	{
		if($this->input->post('id'))
		{
			$data['show_trainer'] = $_POST['status'] == 0 ? 1 : 0;

			$resp=$this->db->where(['id'=>$this->input->post('id')])->update('trainer',$data);
			if($resp==true){
				$response = [
					'success'=> 1,
					'message'	=> 'Updated Successfully'
				];
			}else{
				$response = [
					'success'=> 0,
					'message'	=> 'Some error occured!'
				];
			}
			echo json_encode($response);
		}else{
			$this->load->view('leads');
		}
	}

	public function is_featured_trainer()
	{
		if($this->input->post('id'))
		{
			$data['is_featured_trainer'] = $_POST['status'] == 0 ? 1 : 0;

			$resp=$this->db->where(['id'=>$this->input->post('id')])->update('trainer',$data);
			if($resp==true){
				$response = [
					'success'=> 1,
					'message'	=> 'Updated Successfully'
				];
			}else{
				$response = [
					'success'=> 0,
					'message'	=> 'Some error occured!'
				];
			}
			echo json_encode($response);
		}else{
			$this->load->view('leads');
		}
	}
}