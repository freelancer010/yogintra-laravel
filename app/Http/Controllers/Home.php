<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

	}

	public function index()
	{
		$data['page'] = 'home';
		$data['app_setting'] = $this->setting_model->get_all_app_setting();
		$data['title'] = $data['app_setting']->app_meta_title;
		$this->load->view('front/index', $data);
	}
	
	public function get_all_trainer()
	{
	    $this->db->where('show_trainer', 1);
		$this->db->where('is_trainer', 1);
		$this->db->where('status_trainer', 1);
		$this->db->where('is_featured_trainer', 0);
		$this->db->order_by('id', "DESC");
// 		$this->db->limit(5);
        $all_trainer = $this->db->get('trainer')->result();
        $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($all_trainer));
	}
	
	public function get_all_trainer_limit()
	{
	    $this->db->limit(8);
		$this->db->order_by('RAND()', 'desc');
	    $this->db->where('show_trainer', 1);
		$this->db->where('is_trainer', 1);
		$this->db->where('is_featured_trainer', 1);
		$this->db->where('status_trainer', 1);
        $all_trainer = $this->db->get('trainer')->result();
        $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($all_trainer));
	}
	
	public function addLeads()
	{
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
		$data['created_date'] = $this->input->post('created_date');

		$main_data = $this->db->insert('leads', $data);
		
		echo 1;
		
	}
	
	public function addRecruitments()
	{
		$data['name'] = $this->input->post('name') ?? '';
		$data['number'] = $this->input->post('number') ?? '';
		$data['email'] = $this->input->post('email') ?? '';
		$data['country'] = $this->input->post('country') ?? '';
		$data['state'] = $this->input->post('state') ?? '';
		$data['city'] = $this->input->post('city') ?? '';
        $data['is_trainer'] = 0;
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['dob'] = $this->input->post('dob') ?? '';
        $data['Education'] = $this->input->post('education') ?? '';
        $data['certification'] = $this->input->post('certification') ?? '';
        $data['Other_Certificate'] = $this->input->post('Other_Certificate') ?? '';
        $data['experience'] = $this->input->post('experience') ?? '';
        $data['address'] = $this->input->post('address') ?? '';
        $data['read_status'] = 0;
        $data['status_trainer'] = 1;
        $data['show_trainer'] = 1;
        
		$main_data = $this->db->insert('trainer', $data);
		
		echo 1;
		
	}
	
	function addEventData()
	{
		$data_api['client_name'] 	= $this->input->post('client_name');
		$data_api['event_name'] 	= $this->input->post('event_name');
		$data_api['client_number'] 	= $this->input->post('client_number');
		$data_api['created_date'] 	= date('Y-m-d h:i:s');
		$data_api['country'] 		= $this->input->post('country');
		$data_api['state'] 			= $this->input->post('state');
		$data_api['city'] 			= $this->input->post('city');
		$data_api['package'] 		= $this->input->post('package');
		$data_api['email'] 			= $this->input->post('email');
		$data_api['class_type'] 	= $this->input->post('class_type');
		$data_api['payment_type'] 	= "Full Payment";
		$data_api['totalPayAmount'] = $this->input->post('totalPayAmount');
        
		$main_data = $this->db->insert('events', $data_api);
		
		echo 1;
	}
	function getTrainerSearchData()
{
    $data = $this->input->post('data');
    
    $this->db->group_start();
    $this->db->like('name', $data);
    $this->db->or_like('city', $data);
    $this->db->or_like('state', $data);
    $this->db->or_like('country', $data);
    $this->db->group_end();

    $this->db->where('is_trainer', 1);
    $this->db->where('show_trainer', 1);
    $this->db->where('status_trainer', 1);
    $this->db->where('is_featured_trainer', 0);
    

    $main_data = $this->db->get('trainer')->result();
    echo json_encode($main_data);
}



	
}