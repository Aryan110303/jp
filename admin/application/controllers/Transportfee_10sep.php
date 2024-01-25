<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transportfee
 *
 * @author 7 eye
 */
class Transportfee extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('customlib');
        $this->load->helper('file');
        $this->load->model('transport_custom_model');
        // $this->lang->load('message', 'english');
    }
    
    
    public function test($id) {
     $student_due_fee = $this->transport_custom_model->getStudenttransportFeesrow($id);   
     echo'<pre>';
          print_r($student_due_fee);
          echo'</pre>';
          
          foreach ($student_due_fee as $value) {
              echo $value->fee_groups_feetype_id;
          }
    }
     function index() {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup = $this->route_model->get();
        $data['routelist'] = $feegroup;
        $vehicles_result=$this->vehicle_model->get_1();
        $data['vehiclelist'] = $vehicles_result;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    function search() {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $data['title'] = 'Student Search';
        $vehicles_result=$this->vehicle_model->get_1();
        $data['vehiclelist'] = $vehicles_result;
        $feegroup = $this->route_model->get();
        $data['routelist'] = $feegroup;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('transport_feetype/studentfeeSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $vehicle = $this->input->post('vehicles_id');
//          echo $route;die;
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            $row=$this->studentsession_model->get_by_id($vehicle);
//          print_r($row);die;
            $section = $row['section_id'];
            $class = $row['class_id'];
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('vehicles_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $resultlist = $this->student_model->searchByroute1($vehicle);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
                $this->load->view('layout/header', $data);
                $this->load->view('transport_feetype/studentfeeSearch', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }
    
    function addfee($id) {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $data['title'] = 'Student Detail';
        $student = $this->student_model->get($id);
        $data['student'] = $student;
        $student_due_fee = $this->transport_custom_model->get_fees($student['student_session_id']);
//        print_r($student_due_fee);
//        die;
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
//      print_r($student_due_fee);
//      die;
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function addfeegroup() {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $this->form_validation->set_rules('fee_session_groups', 'Fee Group', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_session_id[]', 'Student', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_session_groups' => form_error('fee_session_groups'),
                'student_session_id[]' => form_error('student_session_id[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $fee_session_groups = $this->input->post('fee_session_groups');
            $student_sesssion_array = $this->input->post('student_session_id');
            $student_ids = $this->input->post('student_ids');
            $delete_student = array_diff($student_ids, $student_sesssion_array);
            $preserve_record = array();
            //********************************************************************************
            foreach ($student_sesssion_array as $key => $value) {
            for($i=1;$i<=9;$i++){
                     $insert_array = array(
                    'student_session_id' => $value,
                    'fee_session_group_id' => $fee_session_groups,
                );
                $inserted_id = $this->transport_custom_model->add3($insert_array);
                $preserve_record[] = $inserted_id;
                
                 if($i==9){
                   $student_due_fee = $this->transport_custom_model->getStudenttransportFeesrow($value);   
                   $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
                        $json_array = array(
                            'amount' => 0,
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime(date('d-m-Y'))),
                            'amount_discount' => $student_due_fee->fees[0]->amount/2,
                            'amount_fine' => 0,
                            'description' => 'Last month Discount' . $collected_by.'Super admin',
                            'payment_mode' => "Cash"
                        );
                        $data = array(
                            'student_fees_master_id' => $inserted_id,
                            'fee_groups_feetype_id' => $student_due_fee->fees[0]->fee_groups_feetype_id,
                            'amount_detail' => $json_array
                        );
                        $send_to="";
                        $inserted_id = $this->transport_custom_model->fee_deposit2($data, $send_to);
                    }
                }
            }
         //**************************************************************************************   
            if (!empty($delete_student)) {
                $this->studentfeemaster_model->delete($fee_session_groups, $delete_student);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
    public function geBalanceFee2() {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $this->form_validation->set_rules('student_fees_master_id', 'student_fees_master_id', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(

                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        }else{
          $data = array();
            $data['student_fees_master_id'] = $this->input->post('student_fees_master_id');   
             $amount_balance = 0;
              $result = $this->transport_custom_model->transportDeposit2($data);
               $amount_balance = $result->due_amount;
             $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance);
            echo json_encode($array);
        }
    }
    
    
    function geBalanceFee() {
        //  if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
        //     access_denied();
        // }
        $this->form_validation->set_rules('student_fees_master_id', 'student_fees_master_id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array();
            $data['student_fees_master_id'] = $this->input->post('student_fees_master_id');
            $result = $this->transport_custom_model->transportDeposit2($data);
           
            $amount_balance = 0;
            $amount = 0;
            $amount_fine = 0;
            $amount_discount = 0;
            $amount_detail = json_decode($result->amount_detail);
            if (is_object($amount_detail)) {

                foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                    $amount = $amount + $amount_detail_value->amount;
                    $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                    $amount_fine = $amount_fine + $amount_detail_value->amount_fine;
                }
            }
            $amount_balance = $result->fees_amount - ($amount + $amount_discount);
            $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance,'amount' => $result->fees_amount);
            echo json_encode($array);
        }
    }
    
    public function test_array($id) {
        $data = array(
                'student_fees_master_id' => $id,
                'amount_detail' => $json_array
            );
        $result = $this->transport_custom_model->transportDeposit2($data);
        print_r($result);
    }
    
    function addstudentfee() {
        $this->form_validation->set_rules('student_fees_master_id', 'Fee Master', 'required|trim|xss_clean');
       
        $this->form_validation->set_rules('amount', 'Amount', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount_discount', 'Discount', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount_fine', 'Fine', 'required|trim|xss_clean');
        $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'amount_discount' => form_error('amount_discount'),
                'amount_fine' => form_error('amount_fine'),
                'payment_mode' => form_error('payment_mode'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
            $json_array = array(
                'amount' => $this->input->post('amount'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount_discount' => $this->input->post('amount_discount'),
                'amount_fine' => $this->input->post('amount_fine'),
                'description' => $this->input->post('description') . $collected_by,
                'payment_mode' => $this->input->post('payment_mode')
            );
            $data = array(
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'amount_detail' => $json_array
            );
            $notify_data = array(
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'fee_groups_feetype_id' => $this->input->post('fee_groups_feetype_id'),
                'amount_detail' => $json_array
            );
           
            $result = $this->transport_custom_model->transportDeposit2($data);
            // $due_amount=$result->due_amount;
            $fee_amount = $this->input->post('amount');
            $fee_amount_discount = $this->input->post('amount_discount');
            $fee_amount_fine = $this->input->post('amount_fine');
            $due_fee= $fee_amount - $fee_amount_discount + $fee_amount_fine;
            $student_master_data=array(
                'id' => $this->input->post('student_fees_master_id'),
                // 'due_amount' => $due_fee,
            );
            $this->transport_custom_model->update_student_transport($student_master_data);
            
            $send_to = $this->input->post('guardian_phone');
            $email = $this->input->post('guardian_email');
            $inserted_id = $this->transport_custom_model->fee_deposit2($data, $send_to);
//          $sender_details = array('invoice' => $inserted_id, 'contact_no' => $send_to, 'email' => $email);
//          $this->mailsmsconf->mailsms('fee_submission', $sender_details);
//          $this->get_notify($notify_data);
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }
    
    function deleteFee() {

        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice = $this->input->post('sub_invoice');
        if (!empty($invoice_id)) {
            $this->transport_custom_model->remove1($invoice_id, $sub_invoice);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

       function printFeesByName() {
        $data = array('payment' => "0");
        $record = $this->input->post('data');
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice_id = $this->input->post('sub_invoice');
        $student_session_id = $this->input->post('student_session_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $student = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $fee_record = $this->transport_custom_model->getFeeByInvoice1($invoice_id, $sub_invoice_id);
        $data['student'] = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['feeList'] = $fee_record;
        $this->load->view('print/printFeesByName_1', $data);
    }

    function printFeesByGroup() {
//        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id = $this->input->post('fee_master_id');
//        $fee_session_group_id = $this->input->post('fee_session_group_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['feeList'] = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_master_id);
        $this->load->view('print/printFeesByGroup_1', $data);
    }

    function printFeesByGroupArray() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id = $value->fee_master_id;
            $fee_session_group_id = $value->fee_session_group_id;
            $feeList = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[] = $feeList;
        }
        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArray_1', $data);
    }
    
    public function get_notify($notify_data=null) {
        $id = $this->session->userdata('admin', 'id')['id'];
        $token_no = $this->db->get_where('admin', array('id' => $id))->row()->token;
        $data = array(
            'token' => $token_no,
            'title' => 'Fee collection',
            'message' => 'New Fee collect',
            'desc' => $notify_data['amount_detail'],
        );
        $this->customlib->request_accept($data);
    }
    
}
