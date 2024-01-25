<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class About extends Admin_Controller {

    function __construct() {
        parent::__construct();
     
        $this->load->library('slug');
        $this->load->config('ci-blog');
        $this->load->library('imageResize');
        $this->load->model('Cms_about_model');
    }

    function index() {
      
        $data = array();
        $listResult = $this->Cms_about_model->get_all();

        $data['listResult'] = $listResult;
        $this->load->view('layout/header');
        $this->load->view('admin/front/about/index', $data);
        $this->load->view('layout/footer');
    }

     function create() {
     
       
      
        $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header');
            $this->load->view('admin/front/about/create');
            $this->load->view('layout/footer');
        } else {

           

            $data = array(
                     'title' => $this->input->post('title'),
                     'description' => htmlspecialchars_decode($this->input->post('description')),              
                     'image' => $this->input->post('image'),
                     'status' =>1,              
            );

           
            $this->Cms_about_model->addfeature($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Feature added successfully</div>');
            redirect('admin/front/about');
        }
    }

    function edit($id) {
       
     
        // $this->session->set_userdata('top_menu', 'Front CMS');
        // $this->session->set_userdata('sub_menu', 'admin/front/notice');
        $result = $this->Cms_about_model->getBy_id($id);
        $data['result'] = $result;
        $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $listbook = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/front/about/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'title' => $this->input->post('title'),
               
                'description' => htmlspecialchars_decode($this->input->post('description')),
             
                'image' => $this->input->post('image'),
             
            );


            // $data['id'] = $this->slug->create_uri($data, $this->input->post('id'));
            $this->Cms_about_model->update($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book updated successfully</div>');
            redirect('admin/front/about');
        }
    }

    function delete($id) {
        $res=$this->Cms_about_model->getBy_id($id);
        if($res){

        // $data['title'] = 'Fees Master List';
        $this->Cms_about_model->delete($id);
    }
        redirect('admin/front/about');
    }

}

?>