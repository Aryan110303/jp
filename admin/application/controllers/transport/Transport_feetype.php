<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transport_feetype extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model('Transportfeetype_model');
        $this->lang->load('message', 'english');
    }

    function index() {
        $this->session->set_userdata('top_menu', 'Transport Fees Collection');
        $this->session->set_userdata('sub_menu', 'transport_fee_type/index');
        $data['title'] = 'Add Feetype';
        $data['title_list'] = 'Recent FeeType';

        $this->form_validation->set_rules(
                'code', 'Code', array(
            'required',
            array('check_exists', array($this->transportfeetype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $data = array(
                'type' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->transportfeetype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fees Type added successfully</div>');
            redirect('transport/transport_feetype/index');
        }
        $feegroup_result = $this->transportfeetype_model->get();
        $data['feetypeList'] = $feegroup_result;

        $this->load->view('layout/transport/header', $data);
        $this->load->view('transport/transport_feetype/feetypeList', $data);
        $this->load->view('layout/transport/footer', $data);
    }

    function delete($id) {
        $data['title'] = 'Fees Master List';
        $this->transportfeetype_model->remove($id);
        redirect('transport/transport_feetype/index');
    }

    function edit($id) {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feetype/index');
        $data['id'] = $id;
        $feetype = $this->transportfeetype_model->get($id);
        $data['feetype'] = $feetype;
        $feegroup_result = $this->transportfeetype_model->get();
        $data['feetypeList'] = $feegroup_result;
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->transportfeetype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/transport/header', $data);
            $this->load->view('transport/transport_feetype/feetypeEdit', $data);
            $this->load->view('layout/transport/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'type' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->transportfeetype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fees Type updated successfully</div>');
            redirect('transport/transport_feetype/index');
        }
    }

}

?>