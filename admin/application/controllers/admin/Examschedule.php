<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ExamSchedule extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("classteacher_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examschedule/index');
        $data['title'] = 'Exam Schedule';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $examSchedule = $this->examschedule_model->getExamByClassandSection($data['class_id'], $data['section_id']);

            $data['examSchedule'] = $examSchedule;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Exam Schedule List';
        $exam = $this->exam_model->get($id);
        $data['exam'] = $exam;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam_schedule/examShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        //  if(!$this->rbac->hasPrivilege('exam_schedule','can_delete')){
        // access_denied();
        // }
        $data['title'] = 'Exam Schedule List';
        $this->exam_model->remove($id);
        redirect('admin/exam_schedule/index');
    }


    function create_old() {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_add')) {
            access_denied();
        }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            $data['examSchedule'] = $examSchedule;
            if ($this->input->post('save_exam') == "save_exam") {
                if ($class_id != 38 && $class_id != 39) {
                    $sectionResult = $this->teachersubject_model->getAllSection($class_id);
                    foreach ($sectionResult as $section_id_array) {
                        $section_id1 = $section_id_array['section_id'];
                        $examSchedule1 = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id1, $exam_id);
                        print_r($examSchedule1);
                        $i = $this->input->post('i');
                        $j =  0;
                        foreach ($examSchedule1 as $key => $value) {
                           /* $data = array(
                                'session_id' => $session,
                                'teacher_subject_id' => $value['id'],
                                'exam_id' => $this->input->post('exam_id'),
                                'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $i[$j]))),
                                'start_to' => $this->input->post('stime_' . $i[$j]),
                                'end_from' => $this->input->post('etime_' . $i[$j]),
                                // 'room_no' => $this->input->post('room_' . $value),
                                'full_marks' => $this->input->post('fmark_' . $i[$j]),
                                'passing_marks' => $this->input->post('pmarks_' . $i[$j])
                            );

                            $this->exam_model->add_exam_schedule($data);*/
                            $j++;
                        }
                    }
                    // redirect('admin/examschedule');
                }else{
                    /*$section_id = $this->input->post('section_id');
                    $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);*/
                    $i = $this->input->post('i');
                    foreach ($i as $key => $value) {
                        $data = array(
                            'session_id' => $session,
                            'teacher_subject_id' => $value,
                            'exam_id' => $this->input->post('exam_id'),
                            'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $value))),
                            'start_to' => $this->input->post('stime_' . $value),
                            'end_from' => $this->input->post('etime_' . $value),
                            // 'room_no' => $this->input->post('room_' . $value),
                            'full_marks' => $this->input->post('fmark_' . $value),
                            'passing_marks' => $this->input->post('pmarks_' . $value)
                        );

                        $this->exam_model->add_exam_schedule($data);
                    }
                    redirect('admin/examschedule');
                }
            }
        }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
    }


    function create() {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_add')) {
            access_denied();
        }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //     if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            // echo "<pre>"; print_r($examSchedule); echo "</pre>"; die();
            $data['examSchedule'] = $examSchedule;
            if ($this->input->post('save_exam') == "save_exam") {
                if ($class_id != 38 && $class_id != 39) {
                    $sectionResult = $this->teachersubject_model->getAllSection($class_id);
                    foreach ($sectionResult as $section_id_array) {
                        $section_id = $section_id_array['section_id'];
                        $examSchedule1 = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
                        $i = $this->input->post('i');
                        $j =  0;
                        foreach ($examSchedule1 as $key => $value) {
                            $data = array(
                                'session_id' => $session,
                                'teacher_subject_id' => $value['id'],
                                'exam_id' => $this->input->post('exam_id'),
                                'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $i[$j]))),
                                'start_to' => $this->input->post('stime_' . $i[$j]),
                                'end_from' => $this->input->post('etime_' . $i[$j]),
                                'full_marks' => $this->input->post('fmark_' . $i[$j]),
                                'passing_marks' => $this->input->post('pmarks_' . $i[$j])
                            );

                            $this->exam_model->add_exam_schedule($data);
                            $j++;
                        }
                    }
                    redirect('admin/examschedule');
                }else{
                    $i = $this->input->post('i');
                              
                    foreach ($i as $key => $value) {
                        
                        if($this->input->post('yesorno'.$value)==1){

                            $data = array(
                                'session_id' => $session,
                                'teacher_subject_id' => $value,
                                'exam_id' => $this->input->post('exam_id'),
                                'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $value))),
                                'start_to' => $this->input->post('stime_' . $value),
                                'end_from' => $this->input->post('etime_' . $value),
                                'full_marks' => $this->input->post('fmark_' . $value),
                                'passing_marks' => $this->input->post('pmarks_' . $value)
                            );                           

                        }
                       else{
                        $data = array(
                            'session_id' => $session,
                            'teacher_subject_id' => $value,
                            'exam_id' => $this->input->post('exam_id'),
                            'date_of_exam' => 0,
                            'start_to' => 0,
                            'end_from' => 0,
                            'full_marks' => 0,
                            'passing_marks' => 0
                        );
                       }
                        $this->exam_model->add_exam_schedule($data);
                    }
                    redirect('admin/examschedule');
                }//else ends 
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    /*function create() {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_add')) {
            access_denied();
        }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //     if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            // $section_id = $this->input->post('section_id');
            $data['exam_id'] = $exam_id;
            $data['class_id'] = $class_id;
            // $data['section_id'] = $section_id;
            // $class_id = $this->input->post('class_id');
            if ($class_id != 38 && $class_id != 39) {
                $sectionResult = $this->teachersubject_model->getAllSection($class_id);
                // print_r($sectionResult[0]['section_id']);
                foreach ($sectionResult as $section_id_array) {
                    // print_r($section_id_array['section_id']);
                    $section_id = $section_id_array['section_id'];
                    $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
                    $data['examSchedule'] = $examSchedule;
                    // echo "<pre>"; print_r($examSchedule);
                    if ($this->input->post('save_exam') == "save_exam") {
                       // echo "<pre>"; print_r($_POST); echo "</pre>"; 
                        $i = $this->input->post('i');
                        $j =  0;
                        foreach ($examSchedule as $key => $value) {
                            $data = array(
                                'session_id' => $session,
                                'teacher_subject_id' => $value['id'],
                                'exam_id' => $this->input->post('exam_id'),
                                'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $i[$j]))),
                                'start_to' => $this->input->post('stime_' . $i[$j]),
                                'end_from' => $this->input->post('etime_' . $i[$j]),
                                // 'room_no' => $this->input->post('room_' . $value),
                                'full_marks' => $this->input->post('fmark_' . $i[$j]),
                                'passing_marks' => $this->input->post('pmarks_' . $i[$j])
                            );

                            $this->exam_model->add_exam_schedule($data);
                            $j++;
                        }
                        // redirect('admin/examschedule');
                    }
                    
                    // echo "<pre>"; print_r($examSchedule); echo "</pre>";
                }

            }else{
                $section_id = $this->input->post('section_id');
                $examSchedule = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
                    $data['examSchedule'] = $examSchedule;
                    if ($this->input->post('save_exam') == "save_exam") {
                        $i = $this->input->post('i');
                        foreach ($i as $key => $value) {
                            $data = array(
                                'session_id' => $session,
                                'teacher_subject_id' => $value,
                                'exam_id' => $this->input->post('exam_id'),
                                'date_of_exam' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $value))),
                                'start_to' => $this->input->post('stime_' . $value),
                                'end_from' => $this->input->post('etime_' . $value),
                                // 'room_no' => $this->input->post('room_' . $value),
                                'full_marks' => $this->input->post('fmark_' . $value),
                                'passing_marks' => $this->input->post('pmarks_' . $value)
                            );

                            $this->exam_model->add_exam_schedule($data);
                        }
                        redirect('admin/examschedule');
                    }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }*/

    function edit($id) {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Exam Schedule';
        $data['id'] = $id;
        $exam = $this->exam_model->get($id);
        $data['exam'] = $exam;
        $this->form_validation->set_rules('name', 'Exam Schedule', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div exam="alert alert-success text-center">Employee details added to Database!!!</div>');
            redirect('admin/exam_schedule/index');
        }
    }

    function getexamscheduledetail() {
        $exam_id = $this->input->post('exam_id');
        $section_id = $this->input->post('section_id');
        $class_id = $this->input->post('class_id');
        $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
       // print_r($examSchedule); die;
        echo json_encode($examSchedule);
    }

}

?>