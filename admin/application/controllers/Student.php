<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->load->model('transport_custom_model');
        $this->blood_group = $this->config->item('bloodgroup');
        $this->role;
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->load->model('mobileapp/Api_model','Api_model');
    }

    function index() {
        $data['title'] = 'Student List';
        $student_result = $this->student_model->get();
        $data['studentlist'] = $student_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentList', $data);
        $this->load->view('layout/footer', $data);
    }



       function uploadtc() {
         $student_id = $this->input->post('student_id');
         $student_session_id = $this->input->post('student_session_id');

        if (isset($_FILES["tc"]) && !empty($_FILES['tc']['name'])) {
            $uploaddir = 'uploads/students_tc/';
            if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                die("Error creating folder $uploaddir");
            }
            //print_r($_FILES["tc"]); die;
            $fileInfo = pathinfo($_FILES["tc"]["name"]);            
            $img_name = $uploaddir.$student_session_id.'.pdf';
            move_uploaded_file($_FILES["tc"]["tmp_name"], $img_name);
            $data_img = array('student_id' => $student_id, 'student_tc' => $img_name);
            $this->student_model->addtc($data_img);
          }
          $this->disablestudent($student_id);
      //  redirect('student/view/' . $student_id);
      }
    function studentreport() {
        if (!$this->rbac->hasPrivilege('student_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/studentreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $userdata = $this->customlib->getUserData();
        $carray = array();
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentReport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('student/studentReport', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $class = $this->input->post('class_id');
                $section = $this->input->post('section_id');
                $category_id = $this->input->post('category_id');
                $gender = $this->input->post('gender');
                $rte = $this->input->post('rte');
                $search = $this->input->post('search');
                if (isset($search)) {
                    if ($search == 'search_filter') {
                        $resultlist = $this->student_model->searchByClassSectionCategoryGenderRte($class, $section, $category_id, $gender, $rte);
                        $data['resultlist'] = $resultlist;
                    }
                    $data['class_id'] = $class;
                    $data['section_id'] = $section;
                    $data['category_id'] = $category_id;
                    $data['gender'] = $gender;
                    $data['rte_status'] = $rte;
                    $this->load->view('layout/header', $data);
                    $this->load->view('student/studentReport', $data);
                    $this->load->view('layout/footer', $data);
                }
            }
        }
    }

    public function download($student_id, $doc) {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/$student_id/" . $this->uri->segment(4);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(6);
        force_download($name, $data);
    }

    function view($id) {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Student Details';
        $student = $this->student_model->get($id);
        $gradeList = $this->grade_model->get();
        $studentSession = $this->student_model->getStudentSession($id);
        $timeline = $this->timeline_model->getStudentTimeline($id, $status = '');
        $data["timeline_list"] = $timeline;

        $student_session_id = $studentSession["student_session_id"];

        $student_session = $studentSession["session"];
        $data["session"] = $student_session;
        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student_session_id);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $siblings = $this->student_model->getMySiblings($student['parent_id'], $student['id']);

        $examList = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
        $data['examSchedule'] = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student['id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student['id']);
                foreach ($exam_subjects as $key => $value) {
                    $exam_array = array();
                    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                    $exam_array['exam_id'] = $value['exam_id'];
                    $exam_array['full_marks'] = $value['full_marks'];
                    $exam_array['passing_marks'] = $value['passing_marks'];
                    $exam_array['exam_name'] = $value['name'];
                    $exam_array['exam_type'] = $value['type'];
                    $exam_array['attendence'] = $value['attendence'];
                    $exam_array['get_marks'] = $value['get_marks'];
                    $x[] = $exam_array;
                }
                $array['exam_name'] = $ex_value['name'];
                $array['exam_result'] = $x;
                $new_array[] = $array;
            }
            $data['examSchedule'] = $new_array;
        }
        $student_doc = $this->student_model->getstudentdoc($id);
        $data['student_doc'] = $student_doc;
        $data['student_doc_id'] = $id;
        $category_list = $this->category_model->get();
        $data['category_list'] = $category_list;
        $data['gradeList'] = $gradeList;
        $data['student'] = $student;
        $data['siblings'] = $siblings;
        $class_section = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"] = $class_section;
        $studentlistbysection = $this->student_model->getStudentClassSection($student["class_id"]);
        $data["studentlistbysection"] = $studentlistbysection;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function exportformat() {
        $this->load->helper('download');
        $filepath = "./backend/import/import_student_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_student_sample_file.csv';

        force_download($name, $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('student', 'can_delete')) {
            access_denied();
        }
        $this->student_model->remove($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Record Deleted Successfully');
        redirect('student/search');
    }

    function doc_delete($id, $student_id) {
        $this->student_model->doc_delete($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Document Deleted Successfully');
        redirect('student/view/' . $student_id);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('student', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/create');
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $data['title'] = 'Add Student';
        $data['title_list'] = 'Recently Added Student';
        $session = $this->setting_model->getCurrentSession();
        $student_result = $this->student_model->getRecentRecord();
        $data['studentlist'] = $student_result;
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $houses = $this->student_model->gethouselist();
        $data['houses'] = $houses;
        $data["bloodgroup"] = $this->blood_group;
        $hostelList = $this->hostel_model->get();
        $data['hostelList'] = $hostelList;
        $vehroute_result = $this->vehroute_model->get();
        $data['vehroutelist'] = $vehroute_result;
        $vehicles_result = $this->vehicle_model->get_1();
        $data['vehiclelist'] = $vehicles_result;
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_is', 'Guardian', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('rte', 'RTE', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_phone', 'Guardian Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('admission_no', 'Admission No', 'trim|required|xss_clean|is_unique[students.admission_no]');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        $this->form_validation->set_rules(
                'roll_no', 'Roll No.', array('trim',
            array('check_exists', array($this->student_model, 'valid_student_roll'))
                )
        );

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/studentCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $fees_discount = $this->input->post('fees_discount');
            $vehroute_id = $this->input->post('vehroute_id');
            $vehicle_id = $this->input->post('vehicles_id');
            $transport_fees = $this->input->post('fare');
            $hostel_room_id = $this->input->post('hostel_room_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }
            $data = array(
                'admission_no' => $this->input->post('admission_no'),
                'roll_no' => $this->input->post('roll_no'),
                'admission_date' => date('Y-m-d', strtotime($this->input->post('admission_date'))),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'mobileno' => $this->input->post('mobileno'),
                'rte' => $this->input->post('rte'),
                'email' => $this->input->post('email'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'guardian_is' => $this->input->post('guardian_is'),
                'pincode' => $this->input->post('pincode'),
                'religion' => $this->input->post('religion'),
                'cast' => $this->input->post('cast'),
                'previous_school' => $this->input->post('previous_school'),
                'dob' => date('Y-m-d', strtotime($this->input->post('dob'))),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'image' => 'uploads/student_images/no_image.png',
                'category_id' => $this->input->post('category_id'),
                'adhar_no' => $this->input->post('adhar_no'),
                'samagra_id' => $this->input->post('samagra_id'),
                'bank_account_no' => $this->input->post('bank_account_no'),
                'bank_name' => $this->input->post('bank_name'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'father_name' => $this->input->post('father_name'),
                'father_phone' => $this->input->post('father_phone'),
                'father_occupation' => $this->input->post('father_occupation'),
                'mother_name' => $this->input->post('mother_name'),
                'mother_phone' => $this->input->post('mother_phone'),
                'mother_occupation' => $this->input->post('mother_occupation'),
                'guardian_occupation' => $this->input->post('guardian_occupation'),
                'guardian_email' => $this->input->post('guardian_email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone' => $this->input->post('guardian_phone'),
                'guardian_address' => $this->input->post('guardian_address'),
                'vehroute_id' => $vehroute_id,
                'hostel_room_id' => $hostel_room_id,
                'school_house_id' => $this->input->post('house'),
                'blood_group' => $this->input->post('blood_group'),
                'height' => $this->input->post('height'),
                'weight' => $this->input->post('weight'),
                'note' => $this->input->post('note'),
                'is_active' => 'yes',
                'measurement_date' => date('Y-m-d',strtotime($this->input->post('measure_date')))
            );
            $insert_id = $this->student_model->add($data);

            $data_new = array(
                'student_id' => $insert_id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $session,
                'route_id' => $vehroute_id,
                'vehicle_id' => $vehicle_id,
                'transport_fees' => $transport_fees,
                'fees_discount' => $fees_discount
            );
            $student_session_id = $this->student_model->add_student_session($data_new);
            $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $sibling_id = $this->input->post('sibling_id');
            $data_student_login = array(
                'username' => $this->student_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id' => $insert_id,
                'role' => 'student'
            );
            $this->user_model->add($data_student_login);
            if ($vehicle_id) {
                $student_fee = $this->db->get_where('student_session', array('student_id' => $insert_id))->row();
                $mydate = "10-04-" . date('Y');
                $time = strtotime("$mydate");
                $j = 0;
                for ($i = 1; $i <= 11; $i++) {
                    // $time = strtotime("date(Y).4.10");
                    $final = date("Y-m-d", strtotime("+$j month", $time));
                    if ($i == 2) {
                        $j++;
                        $final = date("Y-m-d", strtotime("+$j month", $time));
                        $insert_array = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => 0,
                            'due_date' => $final,
                            'fees_amount' => $student_fee->transport_fees / 2,
                        );
                    } else {
                        $insert_array = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => 0,
                            'due_date' => $final,
                            'fees_amount' => $student_fee->transport_fees,
                        );
                    }
                    $inserted_id = $this->transport_custom_model->add3($insert_array);
                    $preserve_record[] = $inserted_id;
                    $j++;
                }
            }

            if ($sibling_id > 0) {
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student = array(
                    'id' => $insert_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                $temp = $insert_id;
                $data_parent_login = array(
                    'username' => $this->parent_login_prefix . $insert_id,
                    'password' => $parent_password,
                    'user_id' => 0,
                    'role' => 'parent',
                    'childs' => $temp
                );

                $ins_parent_id = $this->user_model->add($data_parent_login);
                $update_student = array(
                    'id' => $insert_id,
                    'parent_id' => $ins_parent_id
                );
                $this->student_model->add($update_student);
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }
            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = $this->input->post('first_title');
                $img_name = $uploaddir . basename($_FILES['first_doc']['name']);
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => basename($_FILES['first_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["second_doc"]["name"]);
                $second_title = $this->input->post('second_title');
                $img_name = $uploaddir . basename($_FILES['second_doc']['name']);
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => basename($_FILES['second_doc']['name']));
                $this->student_model->adddoc($data_img);
            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = $this->input->post('fourth_title');
                $img_name = $uploaddir . basename($_FILES['fourth_doc']['name']);
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => basename($_FILES['fourth_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fifth_doc"]["name"]);
                $fifth_title = $this->input->post('fifth_title');
                $img_name = $uploaddir . basename($_FILES['fifth_doc']['name']);
                move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => basename($_FILES['fifth_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            $sender_details = array('student_id' => $insert_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
            $this->mailsmsconf->mailsms('student_admission', $sender_details);
            $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

//    ***************************************        searchByClassSectionWithSession

            $userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session);
            
            if (!empty($userlisting)) {
                foreach ($userlisting as $userlisting_key => $userlisting_value) {
                    if ($userlisting_value['guardian_phone']) {
                        $arr[] = $userlisting_value['guardian_phone'];
                        $user_array = implode(',', $arr);
                    }
                    else{
                        $arr[] = $userlisting_value['mobileno'];
                        $user_array = implode(',', $arr);
                    }
                }
            }
            $students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session);
            if (!empty($students_no)) {
                $data_num = array(
                    'id' => $students_no['id'],
                    'numbers' => $user_array,
                );
            } else {
                $data_num = array(
                    'session_id' => $session,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'numbers' => $user_array,
                );
            }

            $this->student_model->add_all_student_no($data_num);
//*******************************************************************************
            if ($sibling_id > 0) {
                
            } else {
                $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Student added Successfully</div>');
            redirect('student/create');
        }
    }

    function create_doc() {
        $student_id = $this->input->post('student_id');
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
            $uploaddir = './uploads/student_documents/' . $student_id . '/';
            if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                die("Error creating folder $uploaddir");
            }
            $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
            $first_title = $this->input->post('first_title');
            $img_name = $uploaddir . basename($_FILES['first_doc']['name']);
            move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
            $data_img = array('student_id' => $student_id, 'title' => $first_title, 'doc' => basename($_FILES['first_doc']['name']));
            $this->student_model->adddoc($data_img);
        }
        redirect('student/view/' . $student_id);
    }

    function handle_upload() {
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png','JPG','PNG');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                    $_FILES["file"]["type"] != 'image/jpeg' &&
                    $_FILES["file"]["type"] != 'image/png') {
                $this->form_validation->set_message('handle_upload', 'File type not allowed');
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('handle_upload', 'Extension not allowed');
                return false;
            }
            if ($_FILES["file"]["size"] > 1024000) {
                $this->form_validation->set_message('handle_upload', 'File size shoud be less than 1 mb');
                return false;
            }
            return true;
        } else {
            return true;
        }
    }
//new fun for update exist data date of birth ////

//new fun for update exist data date of birth ////

function import_birthdate() {
    if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
        access_denied();
    }
    $data['title'] = 'Import Student';
    $data['title_list'] = 'Recently Added Student';
    $session = $this->setting_model->getCurrentSession();
    $class = $this->class_model->get('', $classteacher = 'yes');
    $data['classlist'] = $class;
    $userdata = $this->customlib->getUserData();
    $category = $this->category_model->get();
    $fields = array('admission_no', 'roll_no', 'firstname', 'lastname', 'dob', 'religion', 'cast', 'mobileno', 'email', 'admission_date', 'blood_group', 'height', 'weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'ifsc_code', 'adhar_no', 'samagra_id', 'rte', 'previous_school', 'note');
    $data["fields"] = $fields;
    $data['categorylist'] = $category;
    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
    $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
    $this->form_validation->set_rules('file', 'Image', 'callback_handle_csv_upload');
    if ($this->form_validation->run() == FALSE) {
        $this->load->view('layout/header', $data);
        $this->load->view('student/import', $data);
        $this->load->view('layout/footer', $data);
    } else {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session = $this->setting_model->getCurrentSession();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);
//                    print_r($result);die;
                if (!empty($result)) {
                    $rowcount = 0;
                    for ($i = 1; $i <= count($result); $i++){

                        $student_data[$i] = array();
                        $n = 0;
                        foreach ($result[$i] as $key => $value) {
                            $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                            $student_data[$i]['is_active'] = 'yes';

                            $n++;
                        }

                        $roll_no = $student_data[$i]["roll_no"];
                        $mobile_no = $student_data[$i]["mobileno"];
                        $email = $student_data[$i]["email"];
                        $guardian_phone = $student_data[$i]["guardian_phone"];
                        $guardian_email = $student_data[$i]["guardian_email"];                     

                        //if ($this->student_model->check_rollno_exists_pranav($roll_no, 0, $class_id, $section_id)) {
                        if (1) {
                                  $this->student_model->add_pranav($student_data[$i]);
                                 // echo $this->db->last_query(); 
                                 $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Record   exists but update by pranav.</div>');
                            
                        } else{

                              $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No chaling.</div>');
                        }
                    } 
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                }
            } else {

                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
            }
        }

        redirect('student/import');
    }
}


// end here

// end here
    function import() {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Import Student';
        $data['title_list'] = 'Recently Added Student';
        $session = $this->setting_model->getCurrentSession();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $category = $this->category_model->get();
        $fields = array('admission_no', 'roll_no', 'firstname', 'lastname', 'dob', 'religion', 'cast', 'mobileno', 'email', 'admission_date', 'blood_group', 'height', 'weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'ifsc_code', 'adhar_no', 'samagra_id', 'rte', 'previous_school', 'note');
        $data["fields"] = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_csv_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/import', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $session = $this->setting_model->getCurrentSession();
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
//                    print_r($result);die;
                    if (!empty($result)) {
                        $rowcount = 0;
                        for ($i = 1; $i <= count($result); $i++) {

                            $student_data[$i] = array();
                            $n = 0;
                            foreach ($result[$i] as $key => $value) {
                                $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                                $student_data[$i]['is_active'] = 'yes';
                                $n++;
                            }

                            $roll_no = $student_data[$i]["roll_no"];
                            $mobile_no = $student_data[$i]["mobileno"];
                            $email = $student_data[$i]["email"];
                            $guardian_phone = $student_data[$i]["guardian_phone"];
                            $guardian_email = $student_data[$i]["guardian_email"];

                            if ($this->student_model->check_rollno_exists_pranav($roll_no, 0, $class_id, $section_id)) {

                                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Record already exists.</div>');
                            } else {

                                $insert_id = $this->student_model->add($student_data[$i]);
                               
                                if (!empty($insert_id)) {
                                    $data_new = array(
                                        'student_id' => $insert_id,
                                        'class_id' => $class_id,
                                        'section_id' => $section_id,
                                        'session_id' => $session
                                    );
                                    $this->student_model->add_student_session($data_new);
                                    $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                    $sibling_id = $this->input->post('sibling_id');
                                    $data_student_login = array(
                                        'username' => $this->student_login_prefix . $insert_id,
                                        'password' => $user_password,
                                        'user_id' => $insert_id,
                                        'role' => 'student'
                                    );
                                    $this->user_model->add($data_student_login);
                                    $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                    $temp = $insert_id;
                                    $data_parent_login = array(
                                        'username' => $this->parent_login_prefix . $insert_id,
                                        'password' => $parent_password,
                                        'user_id' => $insert_id,
                                        'role' => 'parent',
                                        'childs' => $temp
                                    );
                                    $ins_id = $this->user_model->add($data_parent_login);
                                    $update_student = array(
                                        'id' => $insert_id,
                                        'parent_id' => $ins_id
                                    );
                                    $this->student_model->add($update_student);
                                    $sender_details = array('student_id' => $insert_id, 'contact_no' => $guardian_phone, 'email' => $guardian_email);
                                    $this->mailsmsconf->mailsms('student_admission', $sender_details);
                                    $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $mobile_no, 'email' => $email);
                                    $this->mailsmsconf->mailsms('login_credential', $student_login_detail);
                                    $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $guardian_phone, 'email' => $guardian_email);
                                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                                }
                                $data['csvData'] = $result;
                              
                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Students imported successfully</div>');
                                $rowcount++;
                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                            }
                        }
                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                    }
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
                }
            }

            redirect('student/import');
        }
    }

    function handle_csv_upload() {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', 'File type not allowed');
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', 'Extension not allowed');
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', 'Please Select file');
            return false;
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Student';
        $data['id'] = $id;
        $student = $this->student_model->get($id);
        $genderList = $this->customlib->getGender();
        $data['student'] = $student;
        $data['genderList'] = $genderList;
        $session = $this->setting_model->getCurrentSession();
        $vehroute_result = $this->vehroute_model->get();
        $data['vehroutelist'] = $vehroute_result;
        $vehicles_result = $this->vehicle_model->get_1();
        $data['vehiclelist'] = $vehicles_result;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $hostelList = $this->hostel_model->get();
        $data['hostelList'] = $hostelList;
        $houses = $this->student_model->gethouselist();
        $data['houses'] = $houses;
        $data["bloodgroup"] = $this->blood_group;
        $siblings = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $data['siblings'] = $siblings;
        $data['siblings_counts'] = count($siblings);
        // $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');

        // $this->form_validation->set_rules('guardian_is', 'Guardian', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('rte', 'RTE', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_phone', 'Guardian Phone', 'trim|required|xss_clean');
       /* $this->form_validation->set_rules(
                'roll_no', 'Roll No.', array('trim',
            array('check_exists', array($this->student_model, 'valid_student_roll'))
                )
        );*/

        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_id = $this->input->post('student_id');
            $student = $this->student_model->get($student_id);
            $sibling_id = $this->input->post('sibling_id');
            $siblings_counts = $this->input->post('siblings_counts');
            $siblings = $this->student_model->getMySiblings($student['parent_id'], $student_id);
            $total_siblings = count($siblings);

            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $hostel_room_id = $this->input->post('hostel_room_id');
            $fees_discount = $this->input->post('fees_discount');
            $vehroute_id = $this->input->post('vehroute_id');
            $vehicle_id = $this->input->post('vehicles_id');
            $transport_fees = $this->input->post('fare');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }
            
            $data = array(
                'id' => $id,
                'admission_no' => $this->input->post('admission_no'),
                'roll_no' => $this->input->post('roll_no'),
                'admission_date' => $this->input->post('admission_date'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'rte' => $this->input->post('rte'),
                'mobileno' => $this->input->post('mobileno'),
                'email' => $this->input->post('email'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'previous_school' => $this->input->post('previous_school'),
                'guardian_is' => $this->input->post('guardian_is'),
                'pincode' => $this->input->post('pincode'),
                'religion' => $this->input->post('religion'),
                'dob' => $this->input->post('dob'),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'category_id' => $this->input->post('category_id'),
                'adhar_no' => $this->input->post('adhar_no'),
                'samagra_id' => $this->input->post('samagra_id'),
                'bank_account_no' => $this->input->post('bank_account_no'),
                'bank_name' => $this->input->post('bank_name'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'cast' => $this->input->post('cast'),
                'father_name' => $this->input->post('father_name'),
                'father_phone' => $this->input->post('father_phone'),
                'father_occupation' => $this->input->post('father_occupation'),
                'mother_name' => $this->input->post('mother_name'),
                'mother_phone' => $this->input->post('mother_phone'),
                'mother_occupation' => $this->input->post('mother_occupation'),
                'guardian_occupation' => $this->input->post('guardian_occupation'),
                'guardian_email' => $this->input->post('guardian_email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone' => $this->input->post('guardian_phone'),
                'guardian_address' => $this->input->post('guardian_address'),
                'vehroute_id' => $vehroute_id,
                'hostel_room_id' => $hostel_room_id,
                'school_house_id' => $this->input->post('house'),
                'blood_group' => $this->input->post('blood_group'),
                'height' => $this->input->post('height'),
                'weight' => $this->input->post('weight'),
                'note' => $this->input->post('note'),
                'measurement_date' => $this->input->post('measure_date')
            );
            $this->student_model->add($data);
            $data_new = array(
                'student_id' => $id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $session,
                'route_id' => $vehroute_id,
                'vehicle_id' => $vehicle_id,
                'transport_fees' => $transport_fees,
                'fees_discount' => $fees_discount
            );
            $insert_id = $this->student_model->add_student_session($data_new);
            
            if ($vehicle_id) {
                $student_fee = $this->db->get_where('student_session', array('student_id' => $id))->row();
                $student_session_id = $student_fee->id;
                $mydate = "10-04-" . date('Y');
                $time = strtotime("$mydate");
                $j = 0;
                for ($i = 1; $i <= 11; $i++) {
                    // $time = strtotime("date(Y).4.10");
                    $final = date("Y-m-d", strtotime("+$j month", $time));
                    if ($i == 2) {
                        $j++;
                        $final = date("Y-m-d", strtotime("+$j month", $time));
                        $insert_array = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => 0,
                            'due_date' => $final,
                            'fees_amount' => $student_fee->transport_fees / 2,
                        );
                    } else {
                        $insert_array = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => 0,
                            'due_date' => $final,
                            'fees_amount' => $student_fee->transport_fees,
                        );
                    }
                    $inserted_id1 = $this->transport_custom_model->add3($insert_array);
                    $preserve_record[] = $inserted_id1;
                    $j++;
                }
            }
      //      print_r($insert_array);die;
            
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                $img_name = $id . "father" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'father_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }


            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                $img_name = $id . "mother" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }


            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                $img_name = $id . "guardian" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            $userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session);
            if (!empty($userlisting)) {
                foreach ($userlisting as $userlisting_key => $userlisting_value) {
                    if ($userlisting_value['guardian_phone']) {
                        $arr[] = $userlisting_value['guardian_phone'];
                        $user_array = implode(',', $arr);
                    }
                     else{
                        $arr[] = $userlisting_value['mobileno'];
                        $user_array = implode(',', $arr);
                    }
                }
            }
            $students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session);
            if (!empty($students_no)) {
                $data_num = array(
                    'id' => $students_no['id'],
                    'numbers' => $user_array,
                );
            } else {
                $data_num = array(
                    'session_id' => $session,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'numbers' => $user_array,
                );
            }

            $this->student_model->add_all_student_no($data_num);

            if (isset($siblings_counts) && ($total_siblings == $siblings_counts)) {
                //if there is no change in sibling
            } else if (!isset($siblings_counts) && $sibling_id == 0 && $total_siblings > 0) {
                // add for new parent
                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $data_parent_login = array(
                    'username' => $this->parent_login_prefix . $student_id . "_1",
                    'password' => $parent_password,
                    'user_id' => "",
                    'role' => 'parent',
                );

                $update_student = array(
                    'id' => $student_id,
                    'parent_id' => 0,
                );
                $ins_id = $this->user_model->addNewParent($data_parent_login, $update_student);
            } else if ($sibling_id != 0) {
                //join to student with new parent
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student = array(
                    'id' => $student_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
                
            }


            $this->session->set_flashdata('msg', '<div student="alert alert-success text-left">Student Record Updated successfully</div>');
            redirect('student/search');
        }
    }

     public function updaterollno()
     {
         $id =  $this->input->post('id');
         $rollno =  $this->input->post('rollno');
         $c = count($id); 
         for ($i=0; $i < $c; $i++) { 
             $data['roll_no'] = $rollno[$i];
             $this->db->where('id',$id[$i]);            
             $this->db->update('students',$data);
         }
        
          redirect('student/search');

     }

     public function uploadmarksheet()
     {
         $id =  $this->input->post('id');
         $c = count($id); 
         for ($i=0; $i < $c; $i++){ 
            $filename = 'fileToUpload_'.$id[$i];
            if($_FILES[$filename] && !empty($_FILES[$filename]['name'])){
                $fileInfo = pathinfo($_FILES[$filename]["name"]);
                $img_name = $id[$i] . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES[$filename]["tmp_name"], "./uploads/student_marksheet/" . $img_name);
                $data_img = 'uploads/student_marksheet/' . $img_name;
                $datas['path']=$data_img;
                $datas['student_id']=$id[$i];
                $this->db->insert('frontmarksheet', $datas);
             }      
            }
      redirect('student/uploadExamResult');
     }

     

    function search() {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text, $carray);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }



    
    function uploadExamResult() {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentSearchuploadresult', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentSearchuploadresult', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function getByClassAndSection() {
        $class = $this->input->get('class_id');
        $section = $this->input->get('section_id');
        $resultlist = $this->student_model->searchByClassSection($class, $section);
        echo json_encode($resultlist);
    }

    function getStudentRecordByID() {
        $student_id = $this->input->get('student_id');
        $resultlist = $this->student_model->get($student_id);
        echo json_encode($resultlist);
    }

    function uploadimage($id) {
        $data['title'] = 'Add Image';
        $data['id'] = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('student/uploadimage', $data);
        $this->load->view('layout/footer', $data);
    }

    public function doupload($id) {
        $config = array(
            'upload_path' => "./uploads/student_images/",
            'allowed_types' => "gif|jpg|png|jpeg|df",
            'overwrite' => TRUE,
        );
        $config['file_name'] = $id . ".jpg";
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $data_record = array('id' => $id, 'image' => $upload_data['file_name']);
            $this->setting_model->add($data_record);

            $this->load->view('upload_success', $data);
        } else {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('file_view', $error);
        }
    }

    function getlogindetail() {
        if (!$this->rbac->hasPrivilege('student_parent_login_details', 'can_view')) {
            access_denied();
        }
        $student_id = $this->input->post('student_id');
        $examSchedule = $this->user_model->getStudentLoginDetails($student_id);
        echo json_encode($examSchedule);
    }

    function getUserLoginDetails() {
        $studentid = $this->input->post("student_id");
        $result = $this->user_model->getUserLoginDetails($studentid);
        echo json_encode($result);
    }

    function guardianreport() {
        if (!$this->rbac->hasPrivilege('guardian_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/guardianreport');
        $data['title'] = 'Student Guardian Report';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $class_id = $this->input->post("class_id");
        $section_id = $this->input->post("section_id");

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $resultlist = $this->student_model->studentGuardianDetails($carray);
            $data["resultlist"] = $resultlist;
        } else {


            $resultlist = $this->student_model->searchGuardianDetails($class_id, $section_id);
            $data["resultlist"] = $resultlist;
        }

        $this->load->view("layout/header", $data);
        $this->load->view("student/guardianReport", $data);
        $this->load->view("layout/footer", $data);
    }

    function missing_number() {
        /* if (!$this->rbac->hasPrivilege('guardian_report', 'can_view')) {
          access_denied();
          } */

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/missing_number');
        $data['title'] = 'Missing Guardian Number';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $class_id = $this->input->post("class_id");
        $section_id = $this->input->post("section_id");

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $resultlist = $this->student_model->studentGuardianDetails_number($carray);
            $data["resultlist"] = $resultlist;
        } else {


            $resultlist = $this->student_model->searchGuardianDetails($class_id, $section_id);
            $data["resultlist"] = $resultlist;
        }

        $this->load->view("layout/header", $data);
        $this->load->view("student/missingNumbers", $data);
        $this->load->view("layout/footer", $data);
    }

    public function missing_number_add() {
        $msg = "Not Updated !";
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        if ($this->input->post("phone_no")) {
            $data = array(
                'id' => $this->input->post("stuId"),
                'guardian_phone' => $this->input->post("phone_no"),
            );
            $this->student_model->add($data);
            $msg = "Updated Successfully";
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }



        echo json_encode($array);
    }

    function disablestudentslist() {

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disablestudentslist');
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $result = $this->student_model->getdisableStudent();
        $data["resultlist"] = $result;

        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');

                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->disablestudentByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->disablestudentFullText($search_text);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }
        }

        $this->load->view("layout/header", $data);
        $this->load->view("student/disablestudents", $data);
        $this->load->view("layout/footer", $data);
    }

    function disablestudent($id) {

        $data = array('is_active' => "no", 'disable_at' => date("Y-m-d"));
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

    function enablestudent($id) {

        $data = array('is_active' => "yes");
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

    //*********************************************

    function get_route($vehicle_id = '') {

        $routes = $this->db->get_where('vehicle_routes', array('vehicle_id' => $vehicle_id))->result_array();
        foreach ($routes as $row)
            echo '<option value="' . $row['route_id'] . '">' . $route_name = $this->db->get_where('transport_route', array('id' => $row['route_id']))->row()->route_title . '</option>';
    }


 public function addstudentoes(){
 $class = $this->class_model->get();
 $data['classlist'] = $class;
 $result = $this->student_model->getdisableStudent();
 $data["resultlist"] = $result;
    $class = $this->input->post('class_id');
    $section = $this->input->post('section_id');
    $search = $this->input->post('search');

        if ($search == 'search_filter') {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $data['searchby'] = "filter";
                $data['class_id'] = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $data['search_text'] = $this->input->post('search_text');
                $resultlist = $this->student_model->searchByClassSection($class, $section);
                $data['resultlist'] = $resultlist;
                $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
            }
        }
  
    $this->load->view("layout/header", $data);
    $this->load->view("student/addstudentoes", $data);
    $this->load->view("layout/footer", $data);
 }
    public function updateRecordsOES()
    {
        $name = $_POST['name'];
        $scholarno = $_POST['scholarno'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $slug = $_POST['slug'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $count = count($address);
        for ($i=0; $i < $count ; $i++) { 
            $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://13.126.237.21/oes/pranavapi.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"name=$name[$i]&scholarno=$scholarno[$i]&username=$username[$i]&email=$email[$i]&password=$password[$i]&slug=$slug[$i]&phone=$phone[$i]&address=$address[$i]");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        }
        redirect("student/addstudentoes");
       
    }

    
    public function classroom( )
    {
        $userdata = $this->session->userdata('admin');  
        $id = $userdata['id'];     
        
   
        /* call api for dynamic classroom generation */
      
          
           $apipath1 = "http://jpacademymeerut.com/admin/mobileapp/Android_api/createRegularRoomadmin/".$id; 
           $ch1 = curl_init();
          curl_setopt($ch1, CURLOPT_URL, $apipath1);
          curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
          $result = curl_exec($ch1);
       
        /* call api for dynamic classroom generation */   

         $date = date('d-m-Y');
         
        $classroom = $this->db->query("SELECT * from classroom where startdate = '$date' and teacher_id =  $id ")->result_array();
        $data['classrooms'] = $classroom;         
        $data['title'] = "Manage Digital Class Room ";         
        $this->load->view("layout/header", $data);
        $this->load->view("student/classroom", $data);
        $this->load->view("layout/footer", $data);
    }
public function createclassroom()
{
    $class = $this->class_model->get();
    $data['classlist'] = $class;
        $data['title'] = "Manage Digital Class Room ";         
        $this->load->view("layout/header", $data);
        $this->load->view("student/classroomcreate", $data);
        $this->load->view("layout/footer", $data);
}

    public function generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword)
    {
      $string = sha1("createattendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."VX4pnXv8XOGpD07ssrbPWqkRthbYq8S581wA6kd5uxU");
      return $string ;
    } 


    public function createRoomAdminPRanav()
    {     
        $userdata = $this->session->userdata('admin');  
        $id = $userdata['id']; 
        $class =  $this->input->post('class_id');    
        $sections =   $this->input->post('section');    
        $teacher_id =  $userdata['id'];            
        $title =  $this->input->post('title');  
        $title = str_replace(' ', '_', $title);     
        $description =  $this->input->post('description'); 
        $startdate =date('d-m-Y',strtotime($this->input->post('startdate'))) ;
        $starttime = $this->input->post('starttime');
        $endTime = $this->input->post('endTime');
        $exceedTimeValue = $this->input->post('exceedTimeValue');
        $session_id = $this->current_session  ;         
        $rand = rand();
        $name = $class.$teacher_id.$title.$rand;
        $chiper = md5($name);
         $url =  $chiper;   
         if($sections == 101){
            $sectionsarray = array();
            $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
            sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class."' order by sections.section ";
            $sectionslist = $this->db->query($qry)->result();
             foreach ($sectionslist as $key => $value) {
                $sectionsarray[] = $value->id ;
              } 
        }else{
              $sectionsarray = array($sections);
            }
    
       /* bbb start */
       $meetingName= $name;
       $meetingID=$chiper ;
       $mpassword= "Teachers@123";
       $apassword= "Students@123";       
       $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
       //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
       $apipath = "https://room.classeye.in/bigbluebutton/api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $apipath);   
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $result = curl_exec($ch);
       
       /* bbb end */
        
      $data2 = array(                        
        'class_id' => $class,
        'section_id' => $sections,
        'session_id' => $session_id,
        'link' => $url,
        'title' => $title,
        'description' => $description,
        'classroom_active' =>0,
        'teacher_id'=>$teacher_id,
        'iscancel'=>0,
        'startdate'=>date('d-m-Y',strtotime($startdate)),
        'starttime'=>$starttime,
        'endTime'=>$endTime,
        'exceedTimeValue'=>$exceedTimeValue,
        'isvalid'=>1,
        'date'=>date('d-m-Y',strtotime($startdate)),
  
      );


      $this->db->insert('classroom',$data2);  
     if(!empty($sectionsarray)){
      $success = 0;

      $data2 = array(                        
        'class_id' => $class,
        'section_id' => $sections,
        'session_id' => $session_id,
        'link' => $url,
        'title' => $title,
        'description' => $description,
        'classroom_active' =>0,
        'teacher_id'=>$teacher_id,
        'iscancel'=>0,
        'startdate'=>date('d-m-Y',strtotime($startdate)),
        'starttime'=>$starttime,
        'endTime'=>$endTime,
        'exceedTimeValue'=>$exceedTimeValue,
        'isvalid'=>1,
        'date'=>date('d-m-Y',strtotime($startdate)),
  
      );
      $this->db->insert('classroom',$data2);   
      $classcreated =  $this->db->insert_id();
    foreach ($sectionsarray as $section) {
      $studentlist  = $this->student_model->searchByClassSection_api($class, $section, $session_id);   
       
   
        $senddata = array("title"=>$title , "url"=>$url);
      if(!empty($studentlist) && $classcreated > 0 ){
      foreach($studentlist as $value){       
                 $data1 = array(             
                'student_session_id' => $value['student_session_id'],
                'student_id' => $value['id'],
                'class_id' => $class,
                'section_id' => $section,
                'session_id' => $session_id,
                'link' => $url,
                'classroom_id' =>$classcreated,
                'joined'=>0,
                'is_student'=>1,
                'teacher_id'=>$teacher_id            
          );
          $msgId = $this->Api_model->addclassroom($data1); 
          $device_token= $value['device_token'];
          $student_id = $value['id'];
          if($device_token!=''){        
          $top['to'] = $device_token ;
          $message1 = "Join Active Class Room";
          $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'Central Academy School Jabalur/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);
         }
          
      }
 
       $success++;
        }  
      }
    }  
    redirect('student/classroom');
 }




    public function createRoomAdmin()
    {     
        $userdata = $this->session->userdata('admin');  
        $id = $userdata['id']; 
        $class =  $this->input->post('class_id');    
        $sections =   $this->input->post('section');    
        $teacher_id =  $userdata['id'];      
        $title =   str_replace(' ', '', $this->input->post('title'));  
        $description =     str_replace(' ', '', $this->input->post('description')); 
        $startdate =date('d-m-Y',strtotime($this->input->post('startdate'))) ;
        $starttime = $this->input->post('starttime');
        $endTime = $this->input->post('endTime');
        $exceedTimeValue = $this->input->post('exceedTimeValue');
        $session_id = $this->current_session  ;         
        $rand = rand();
        $name = $class.$teacher_id.$rand."_jpSchoolMerrut".$rand;
        $chiper = md5($name);
         $url =  $chiper;   
        
       /* bbb start */
       $meetingName= $name;
       $meetingID=$chiper ;
       $mpassword= "Teachers@123";
       $apassword= "Students@123";       
       $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
       //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
       $apipath = "https://room.classeye.in/bigbluebutton/api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $apipath);   
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $result = curl_exec($ch);
       
       /* bbb end */
        
      $data2 = array(                        
        'class_id' => $class,
        'section_id' => $sections,
        'session_id' => $session_id,
        'link' => $url,
        'title' => $title,
        'description' => $description,
        'classroom_active' =>0,
        'teacher_id'=>$teacher_id,
        'iscancel'=>0,
        'startdate'=>date('d-m-Y',strtotime($startdate)),
        'starttime'=>$starttime,
        'endTime'=>$endTime,
        'exceedTimeValue'=>$exceedTimeValue,
        'isvalid'=>1,
        'date'=>date('d-m-Y',strtotime($startdate)),
  
      );
      $this->db->insert('classroom',$data2);  
  
    redirect('student/classroom');
 }

   
    public function classroomdeleteall()
    {
        $date = date('d-m-Y');
        $userdata = $this->session->userdata('admin');  
        $id = $userdata['id'];  
        if($id >0 ){
            $classrooms = $this->db->query("SELECT * from classroom where startdate = '$date' and teacher_id =  $id ")->result();

            foreach ($classrooms as $classroom) {
    
                $this->db->where('classroom_id', $classroom->id);
                $this->db->delete('classroomStudents');
            }
        }
      
        $this->db->where('date', $date);
        $this->db->delete('classroom');
        redirect('student/classroom');
    }


    public function classroomdelete($id)
    {   
        if($id  > 0){
        $this->db->where('classroom_id', $id);
        $this->db->delete('classroomStudents');
        
        $this->db->where('id', $id);
        $this->db->delete('classroom');
    }
        redirect('student/classroom');
    }


public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AIzaSyCT-A54uBY2jeKTEg0rkkfKzA8GLcPFUso';
$url = 'https://fcm.googleapis.com/fcm/send'; 
$headers = array(
'Authorization: key=' . $FIREBASE_API_KEY_TWO,
'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

// echo json_encode($fields);
// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);

curl_close($ch);

return $result;
}

    //*********************************************    
}

?>