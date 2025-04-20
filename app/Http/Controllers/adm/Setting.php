<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Core_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function application_setting()
    {
        $data['title'] = 'Application Setting';
        $data['app_setting'] = $this->setting_model->get_all_app_setting(); 
        $this->load->view('includes/header', $data);
        $this->load->view('settings/application_setting');
        $this->load->view('includes/footer');
    }

    public function update_application_setting()
    {
        
        if ($this->setting_model->update_application_setting()) {
            $this->session->set_userdata('success', "Data Successfully Updated");
            redirect($this->agent->referrer());
        } else {
            $this->session->set_userdata('error', "Data Update Faild");
            redirect($this->agent->referrer());
        }
    }

    public function visual_setting()
    {
        $data['title'] = 'Visual Setting';
        $data['app_setting'] = $this->setting_model->get_all_app_setting(); 
        $data['visual_setting'] = $this->setting_model->get_visual_setting();   
        $this->load->view('includes/header', $data);
        $this->load->view('settings/visual_setting');
        $this->load->view('includes/footer');
    }

    public function update_visual_setting()
    {
        
        if ($this->setting_model->update_visual_setting()) {
            $this->session->set_userdata('success', "Data Successfully Updated");
            redirect($this->agent->referrer());
        } else {
            $this->session->set_userdata('error', "Data Update Faild");
            redirect($this->agent->referrer());
        }
    }

   
}