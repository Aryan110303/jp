<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Holidays extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("Holidays_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/holidays');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            // $data['CallList'] = $CallList;
            $data['holidays_list'] = $this->Holidays_model->holidays_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/holidayview', $data);
            $this->load->view('layout/footer');
        } else {
            $holidays = array(               
                'name' => $this->input->post('name'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    );
          
            $holiday_id = $this->Holidays_model->add($holidays);

            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Holiday added successfully</div>');
            redirect('admin/holidays');
        }
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_delete')) {
            access_denied();
        }
        $this->Holidays_model->delete($id);
    }
    
    public function edit($id) {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('date', 'date', 'required');
        if ($this->form_validation->run() == FALSE) {
            
           
            $data['holiday_list'] = $this->Holidays_model->holidays_list();
            $data['holiday_data'] = $this->Holidays_model->holidays_list($id);
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/holidayeditview', $data);
            $this->load->view('layout/footer');
        } else {
            $holidays = array(              
                'name' => $this->input->post('name'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
            );
           
            $this->Holidays_model->update($id, $holidays);
        }
    }
    //--------------------------end class------------------------ 
}
