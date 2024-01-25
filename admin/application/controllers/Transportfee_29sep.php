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

    public function test($id=null) {
        $setting_result = $this->setting_model->get();
        print_r($setting_result[0]['fine_amount']);
        die;
        $student_due_fee = $this->transport_custom_model->getStudenttransportFeesrow($id);
        echo'<pre>';
        print_r($student_due_fee);
        echo'</pre>';
        foreach ($student_due_fee as $value) {
            echo $value->fee_groups_feetype_id;
        }
    }

    function index() {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup = $this->route_model->get();
        $data['routelist'] = $feegroup;
        $vehicles_result = $this->vehicle_model->get_1();
        $data['vehiclelist'] = $vehicles_result;
        $resultlist = $this->student_model->searchByroute1();
        $data['resultlist'] = $resultlist;
        $resultlist_staff = $this->transport_custom_model->searchByroute1();
        $data['resultlist_staff'] = $resultlist_staff;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    function search() {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
        $data['title'] = 'Student Search';
        $vehicles_result = $this->vehicle_model->get_1();
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
            $row = $this->studentsession_model->get_by_id($vehicle);
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
                        $resultlist_staff = $this->transport_custom_model->searchByroute1($vehicle);
                        $data['resultlist_staff'] = $resultlist_staff;
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
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
        $data['title'] = 'Student Detail';
        $student = $this->student_model->get($id);
        $data['student'] = $student;
        $student_due_fee = $this->transport_custom_model->get_fees($student['student_session_id']);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
    }

    function editfee($id) {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
        $data['title'] = 'Student Detail';
        $student = $this->student_model->get($id);
        $data['student'] = $student;
        $student_due_fee = $this->transport_custom_model->get_fees($student['student_session_id']);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/studenteditfee', $data);
        $this->load->view('layout/footer', $data);
    }

    
    function addfee_staff($id) {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
        $data['title'] = 'Staff Detail';
        $staff = $this->staff_model->get_1($id);
        $data['staff'] = $staff;
        $student_due_fee = $this->transport_custom_model->get_fees($staff['student_session_id']);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($staff['student_session_id']);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('transport_feetype/staffAddfee', $data);
        $this->load->view('layout/footer', $data);
    }

    function addfeegroup() {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
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
                for ($i = 1; $i <= 9; $i++) {
                    $insert_array = array(
                        'student_session_id' => $value,
                        'fee_session_group_id' => $fee_session_groups,
                    );
                    $inserted_id = $this->transport_custom_model->add3($insert_array);
                    $preserve_record[] = $inserted_id;

                    if ($i == 9) {
                        $student_due_fee = $this->transport_custom_model->getStudenttransportFeesrow($value);
                        $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
                        $json_array = array(
                            'amount' => 0,
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime(date('d-m-Y'))),
                            'amount_discount' => $student_due_fee->fees[0]->amount / 2,
                            'amount_fine' => 0,
                            'description' => 'Last month Discount' . $collected_by . 'Super admin',
                            'payment_mode' => "Cash"
                        );
                        $data = array(
                            'student_fees_master_id' => $inserted_id,
                            'fee_groups_feetype_id' => $student_due_fee->fees[0]->fee_groups_feetype_id,
                            'amount_detail' => $json_array
                        );
                        $send_to = "";
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
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
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
            $amount_balance = 0;
            $result = $this->transport_custom_model->transportDeposit2($data);
            $amount_balance = $result->due_amount;
            $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance);
            echo json_encode($array);
        }
    }

    function geBalanceFee() {
//         if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
//            access_denied();
//        }
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
            //********************Fine condition******************************
            $date1 = date_create($result->due_date);
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1, $date2);
            $delay = $diff->format("%m");
            if ($result->due_date < date('Y-m-d')) {
                if ($delay > 1) {
                    $diff = date_diff($date1, $date2);
                    $delay_days = $diff->format("%a");
                    $fine = $delay_days * 20;
                } else {
                    $fine = 20;
                }
            } else {
                $fine = 0;
            }
            //********************Fine condition end******************************
            $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance, 'amount' => $result->fees_amount, 'amount_fine' => $fine);
            echo json_encode($array);
        }
    }
    
    function geBalanceFee_edit() {
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $final_amount_balance = 0;
        $final_fees_amount = 0;
        $final_fine = 0;
        foreach ($record_array as $key => $value) {
            $data = array();
            $data['student_fees_master_id'] = $value;
      
        }
        $array = array('status' => 'success', 'error' => '','master_list' => $record_array);
        echo json_encode($array);
    }
    public function geBalanceFee3() {
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $final_amount_balance = 0;
        $final_fees_amount = 0;
        $final_fine = 0;
        foreach ($record_array as $key => $value) {
            $data = array();
            $data['student_fees_master_id'] = $value;
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
            //********************Fine condition******************************
            $date1 = date_create($result->due_date);
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1, $date2);
            $delay = $diff->format("%m");
            if ($result->due_date < date('Y-m-d')) {
                if ($delay > 1) {
                    $diff = date_diff($date1, $date2);
                    $delay_days = $diff->format("%a");
                    $fine = $delay_days * 20;
                } else {
                    $fine = 20;
                }
            } else {
                $fine = 0;
            }
            //********************Fine condition end******************************
            $final_amount_balance += $amount_balance;
            $final_fees_amount += $result->fees_amount;

            $final_fine += $fine;
        }
        $array = array('status' => 'success', 'error' => '', 'balance' => $final_amount_balance, 'amount' => $final_fees_amount, 'amount_fine' => $final_fine, 'master_list' => $record_array);
        echo json_encode($array);
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
            $due_amount = $result->due_amount;
            $fee_amount = $this->input->post('amount');
            $fee_amount_discount = $this->input->post('amount_discount');
            $fee_amount_fine = $this->input->post('amount_fine');
            $due_fee = $due_amount - $fee_amount - $fee_amount_discount + $fee_amount_fine;
            $student_master_data = array(
                'id' => $this->input->post('student_fees_master_id'),
                'due_amount' => $due_fee,
            );
            $this->transport_custom_model->update_student_transport($student_master_data);
            $send_to = $this->input->post('guardian_phone');
            $email = $this->input->post('guardian_email');
            $inserted_id = $this->transport_custom_model->fee_deposit2($data, $send_to);

            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }
      function editstudentfee() {
        $this->form_validation->set_rules('student_fees_master_id', 'Fee Master', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_master_data = array(
                'id' => $this->input->post('student_fees_master_id'),
                'fees_amount' => $this->input->post('amount'),
            );
            $this->transport_custom_model->update_student_transport($student_master_data);
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }
	
    function addstudentfee_lst(){
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
            $student_fees_master_id_arr = explode(',',$this->input->post('student_fees_master_id'));
//            echo   $this->input->post('student_fees_master_id');
//            print_r($student_fees_master_id_arr);
//            die;
            $submit_amount = $this->input->post('amount');

            foreach ($student_fees_master_id_arr as $student_fees_master_id) {
                $data1 = array(
                    'student_fees_master_id' => $student_fees_master_id
                );

                /* $json_array = array(
                  'amount' => $this->input->post('amount'),
                  'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                  'amount_discount' => $this->input->post('amount_discount'),
                  'amount_fine' => $this->input->post('amount_fine'),
                  'description' => $this->input->post('description') . $collected_by,
                  'payment_mode' => $this->input->post('payment_mode')
                  );

                  $data = array(
                  'student_fees_master_id' => $student_fees_master_id,
                  'amount_detail' => $json_array
                  );

                  $notify_data = array(
                  'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                  'fee_groups_feetype_id' => $this->input->post('fee_groups_feetype_id'),
                  'amount_detail' => $json_array
                  ); */
                //*****************************************************************
                                    $total_amount = 0;
                                    $total_deposite_amount = 0;
                                    $total_fine_amount = 0;
                                    $total_discount_amount = 0;
                                    $total_balance_amount = 0;
                                    $alot_fee_discount = 0;
                                    $i=0;
                                    $result = $this->transport_custom_model->transportDeposit2($data1);
                                    $fee_value=$this->transport_custom_model->transportDeposit3($student_fees_master_id); 
//                                        foreach ($fee->fees as $fee_key => $fee_value) {
                                            $fee_paid = 0;
                                            $fee_discount = 0;
                                            $fee_fine = 0;

                                            if (!empty($fee_value->amount_detail)) {
                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                    $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                                }
                                            }
                                           
                                            $total_amount = $total_amount + $result->fees_amount;    
                                            $total_discount_amount = $total_discount_amount + $fee_discount;
                                            $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                            $total_fine_amount = $total_fine_amount + $fee_fine;
                                            $feetype_balance =  $result->fees_amount - ($fee_paid + $fee_discount) ;
                
                //*****************************************************************

                
                // $due_amount = $result->fees_amount;//
                 $due_amount = $feetype_balance;//
                if ($submit_amount > $due_amount) {
                    $fee_amount = $due_amount;
                } else {
                    $fee_amount = $submit_amount;
                }
                $submit_amount = $submit_amount - $due_amount;
                if ($submit_amount <= 0) {
                    $fee_amount_discount = $this->input->post('amount_discount');
                    $fee_amount_fine = $this->input->post('amount_fine');
                } else {
                    $fee_amount_discount = 0; // $this->input->post('amount_discount');
                    $fee_amount_fine = 0; //$this->input->post('amount_fine');
                }
                $due_fee = $due_amount - $fee_amount - $fee_amount_discount + $fee_amount_fine;
                $student_master_data = array(
                    'id' => $student_fees_master_id,
                    'due_amount' => $due_fee,
                );
//                echo $fee_amount;
//                print_r($student_master_data);
//                die;
                $this->transport_custom_model->update_student_transport($student_master_data);
                $send_to = $this->input->post('guardian_phone');
                $email = $this->input->post('guardian_email');

                /* ---------------- */
                $json_array = array(
                    'amount' => $fee_amount,
                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'amount_discount' => $fee_amount_discount,
                    'amount_fine' => $fee_amount_fine,
                    'description' => $this->input->post('description') . $collected_by,
                    'payment_mode' => $this->input->post('payment_mode')
                );

                $data = array(
                    'student_fees_master_id' => $student_fees_master_id,
                    'amount_detail' => $json_array
                );
                /* --------------------- */

                $inserted_id = $this->transport_custom_model->fee_deposit2($data, $send_to);
                if ($submit_amount <= 0)
                    break;
            }
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }
    
    function editstudentfee_lst() {
        $this->form_validation->set_rules('student_fees_master_id', 'Fee Master', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } 
        else {
            $student_fees_master_id_arr = explode(',', $this->input->post('student_fees_master_id'));
            $amount = $this->input->post('amount');
            foreach ($student_fees_master_id_arr as $student_fees_master_id) {
                $student_master_data = array(
                    'id' => $student_fees_master_id,
                    'fees_amount' => $this->input->post('amount'),
                );
                $this->transport_custom_model->update_student_transport($student_master_data);
            }
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

    function printFeesByName_staff() {
        $data = array('payment' => "0");
        $record = $this->input->post('data');
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice_id = $this->input->post('sub_invoice');
        $student_session_id = $this->input->post('student_session_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $student = $this->studentsession_model->searchStudentsBySession_staff($student_session_id);
        $fee_record = $this->transport_custom_model->getFeeByInvoice2($invoice_id, $sub_invoice_id);
        $data['student'] = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['feeList'] = $fee_record;
        $this->load->view('print/printFeesByName_staff', $data);
    }

    function printFeesByGroup() {
//      $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id = $this->input->post('fee_master_id');
//      $fee_session_group_id = $this->input->post('fee_session_group_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['feeList'] = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_master_id);
        $this->load->view('print/printFeesByGroup_1', $data);
    }

    function printFeesByGroup_staff() {
//        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id = $this->input->post('fee_master_id');
//        $fee_session_group_id = $this->input->post('fee_session_group_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['feeList'] = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype_staff($fee_master_id);
        $this->load->view('print/printFeesByGroup_staff', $data);
    }

    function printFeesByGroupArray() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $fees_array = array();
        foreach ($record_array as $key => $value) {
//          $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id = $value->fee_master_id;
            $fee_session_group_id = $value->fee_session_group_id;
            $feeList = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[] = $feeList;
        }
        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArray_1', $data);
    }
    
    
    function printFeesByGroupArray_3() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = explode(',', $record);
        
        $fees_array = array();
        foreach ($record_array as $key => $value) {
           
            $fee_master_id = $value;
            $feeList = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_master_id);
//            array_push($fees_array, $feeList);
           $fees_array[] = $feeList;
        }
        
        $data['feearray'] = $fees_array;
       
        $this->load->view('print/printFeesByGroupArray_1', $data);
    }
    function print_test() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = '6,7';
      
        $record_array = explode(',', $record);
        
        $fees_array = array();
        foreach ($record_array as $key => $value) {
           
            $fee_master_id = $value;
            $feeList = $this->transport_custom_model->getDueFeeByFeeSessionGroupFeetype1($fee_master_id);
//            array_push($fees_array, $feeList);
           $fees_array[] = $feeList;
        }
        
        $data['feearray'] = $fees_array;
       
        $this->load->view('print/printFeesByGroupArray_1', $data);
       
                
        
        
    }

    public function get_notify($notify_data = null) {
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

    function studenttransportreport() {
        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transaction/studentacademicreport');
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentAcademicReport_1', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/studentAcademicReport_1', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $feetype = $this->input->post('feetype');
                $feetype_arr = $this->input->post('feetype_arr');
                $student_Array = array();
                $studentlist = $this->student_model->searchByClassSection($class_id, $section_id);
                if (!empty($studentlist)) {
                    foreach ($studentlist as $key => $eachstudent) {
                        $obj = new stdClass();
                        $obj->name = $eachstudent['firstname'] . " " . $eachstudent['lastname'];
                        $obj->class = $eachstudent['class'];
                        $obj->section = $eachstudent['section'];
                        $obj->admission_no = $eachstudent['admission_no'];
                        $obj->roll_no = $eachstudent['roll_no'];
                        $obj->father_name = $eachstudent['father_name'];
                        $student_session_id = $eachstudent['student_session_id'];
                        $student_total_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);

                        if (!empty($student_total_fees)) {
                            $totalfee = 0;
                            $deposit = 0;
                            $discount = 0;
                            $balance = 0;
                            $fine = 0;
                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                                if (!empty($student_total_fees_value->fees)) {
                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                        $totalfee = $totalfee + $each_fee_value->amount;
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                        // echo "<pre/>";
                                        // var_dump($amount_detail);
                                        // print_r($amount_detail);
                                        if (is_object($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit = $deposit + $amount_detail_value->amount;
                                                $fine = $fine + $amount_detail_value->amount_fine;
                                                $discount = $discount + $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                }
                            }
                            $obj->totalfee = $totalfee;
                            $obj->payment_mode = "N/A";
                            $obj->deposit = $deposit;
                            $obj->fine = $fine;
                            $obj->discount = $discount;
                            $obj->balance = $totalfee - ($deposit + $discount);
                        } else {

                            $obj->totalfee = "N/A";
                            $obj->payment_mode = "N/A";
                            $obj->deposit = "N/A";
                            $obj->fine = "N/A";
                            $obj->balance = "N/A";
                            $obj->discount = "N/A";
                        }
                        $student_Array[] = $obj;
                    }
                }


                $data['student_due_fee'] = $student_Array;
                // exit();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['feetype'] = $feetype;
                $data['feetype_arr'] = $feetype_arr;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/studentAcademicReport_1', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function test_1($param) {
        $feeList = $this->transport_custom_model->studenttransaction($param);
        print_r($feeList);
    }

    function feesearch() {
        if (!$this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/feesearch');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feesessiongroup = $this->feesessiongroup_model->getFeesByGroup();
        $data['feesessiongrouplist'] = $feesessiongroup;
        $this->form_validation->set_rules('feegroup_id', 'Fee Group', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $feegroup_id = $this->input->post('feegroup_id');
            $feegroup = explode("-", $feegroup_id);
            $feegroup_id = $feegroup[0];
            $fee_groups_feetype_id = $feegroup[1];
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_due_fee = $this->studentfee_model->getDueStudentFees($feegroup_id, $fee_groups_feetype_id, $class_id, $section_id);
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {
                    $amt_due = $student_due_fee_value['amount'];
                    $student_due_fee[$student_due_fee_key]['amount_discount'] = 0;
                    $student_due_fee[$student_due_fee_key]['amount_fine'] = 0;
                    $a = json_decode($student_due_fee_value['amount_detail']);
                    if (!empty($a)) {
                        $amount = 0;
                        $amount_discount = 0;
                        $amount_fine = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= $amount) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            $student_due_fee[$student_due_fee_key]['amount_detail'] = $amount;
                            $student_due_fee[$student_due_fee_key]['amount_discount'] = $amount_discount;
                            $student_due_fee[$student_due_fee_key]['amount_fine'] = $amount_fine;
                        }
                    }
                }
            }


            $data['student_due_fee'] = $student_due_fee;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        }
    }

}