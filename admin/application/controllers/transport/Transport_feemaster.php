<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transport_feemaster extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->lang->load('message', 'english');
    }

    function index() {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transport/feemaster');
        $data['title'] = ' Transport Feemaster List';
        $feegroup = $this->route_model->get();
        $data['routelist'] = $feegroup;
        $feetype = $this->transportfeetype_model->get();
        $data['feetypeList'] = $feetype;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup2();
        $data['feemasterList'] = $feegroup_result;
        $this->form_validation->set_rules('fee_groups_id', 'FeeGroup', 'required');
        $this->form_validation->set_rules('feetype_id', 'FeeType', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $insert_array = array(
                'fee_groups_id' => $this->input->post('fee_groups_id'),
                'feetype_id' => $this->input->post('feetype_id'),
                'amount' => $this->input->post('amount'),
                'due_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('due_date'))),
                'session_id' => $this->setting_model->getCurrentSession()
            );
            $feegroup_result = $this->feesessiongroup_model->add2($insert_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fees Master added successfully</div>');
            redirect('transport/transport_feemaster/index');
        }
        $this->load->view('layout/transport/header', $data);
        $this->load->view('transport/transport_feemaster/feemasterList', $data);
        $this->load->view('layout/transport/footer', $data);
    }

    function delete($id) {
        $data['title'] = 'Fees Master List';
        $this->feegrouptype_model->remove($id);
        redirect('transport/transport_feemaster/index');
    }

    function deletegrp($id) {
        $data['title'] = 'Fees Master List';
        $this->feesessiongroup_model->remove($id);
        redirect('transport/transport_feemaster');
    }

    function edit($id) {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transport/feemaster');
        $data['id'] = $id;
        $feegroup_type = $this->feegrouptype_model->get($id);
        $data['feegroup_type'] = $feegroup_type;
        $feegroup = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup;
        $feetype = $this->transportfeetype_model->get();
        $data['feetypeList'] = $feetype;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup();
        $data['feemasterList'] = $feegroup_result;
        $this->form_validation->set_rules('fee_groups_id', 'FeeGroup', 'required');
        $this->form_validation->set_rules('feetype_id', 'FeeType', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/transport/header', $data);
            $this->load->view('transport/transport_feemaster/feemasterEdit', $data);
            $this->load->view('layout/transport/footer', $data);
        } else {
            $insert_array = array(
                'id' => $this->input->post('id'),
                'feetype_id' => $this->input->post('feetype_id'),
                'due_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('due_date'))),
                'amount' => $this->input->post('amount')
            );
            $feegroup_result = $this->feegrouptype_model->add($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fees Master updated successfully</div>');
            redirect('transport/transport_feemaster/index');
        }
    }

    function assign($id) {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transport/feemaster');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup2($id);
        $data['feegroupList'] = $feegroup_result;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender'] = $this->input->post('gender');
            $data['rte_status'] = $this->input->post('rte');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');

            $resultlist = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/transport/header', $data);
        $this->load->view('transport/transport_feemaster/assign', $data);
        $this->load->view('layout/transport/footer', $data);
    }

}

?>