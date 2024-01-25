<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timetable extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("classteacher_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'timetable/index');
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Marks';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get();
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //    if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $result_subjects = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
            $getDaysnameList = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $final_array = array();
            if (!empty($result_subjects)) {
                foreach ($result_subjects as $subject_k => $subject_v) {
                    $result_array = array();
                    foreach ($getDaysnameList as $day_key => $day_value) {
                        $where_array = array(
                            'teacher_subject_id' => $subject_v['id'],
                            'day_name' => $day_value
                        );
                        $result = $this->timetable_model->get($where_array);
                        if (!empty($result)) {
                            $obj = new stdClass();
                            $obj->status = "Yes";
                            $obj->start_time = $result[0]['start_time'];
                            $obj->end_time = $result[0]['end_time'];
                            $obj->room_no = $result[0]['room_no'];
                            $result_array[$day_value] = $obj;
                        } else {
                            $obj = new stdClass();
                            $obj->status = "No";
                            $obj->start_time = "N/A";
                            $obj->end_time = "N/A";
                            $obj->room_no = "N/A";
                            $result_array[$day_value] = $obj;
                        }
                    }
                    $final_array[$subject_v['name']] = $result_array;
                }
            }
            $data['result_array'] = $final_array;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Mark List';
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/timetableShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/timetable/index');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_add')) {
            access_denied();
        }
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Schedule';
        $data['subject_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist'] = $exam;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);
        // }    
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id = $this->input->post('feecategory_id');
            $subject_id = $this->input->post('subject_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['subject_id'] = $subject_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $getDaysnameList = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $array = array();
            $data['timetableSchedule'] = array();
            foreach ($getDaysnameList as $key => $value) {
                $where_array = array(
                    'teacher_subject_id' => $subject_id,
                    'day_name' => $value
                );
                $result = $this->timetable_model->get($where_array);
                if (empty($result)) {
                    $obj = new stdClass();
                    $obj->starting_time = "";
                    $obj->post_id = 0;
                    $obj->ending_time = "";
                    $obj->room_no = "";
                } else {
                    $obj = new stdClass();
                    $obj->starting_time = $result[0]['start_time'];
                    $obj->post_id = $result[0]['id'];
                    $obj->ending_time = $result[0]['end_time'];
                    $obj->room_no = $result[0]['room_no'];
                }
                $array[$value] = $obj;
            }
            $data['timetableSchedule'] = $array;
            if ($this->input->post('save_exam') == "save_exam") {
                $loop = $this->input->post('i');
                foreach ($loop as $key => $value) {
                    $data = array(
                        'day_name' => $value,
                        'teacher_subject_id' => $this->input->post('subject_id'),
                        'start_time' => $this->input->post('stime_' . $value),
                        'end_time' => $this->input->post('etime_' . $value),
                        'room_no' => $this->input->post('room_' . $value),
                        'id' => $this->input->post('edit_' . $value),
                    );
                    $this->timetable_model->add($data);
                }
                redirect('admin/timetable');
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Mark';
        $data['id'] = $id;
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->form_validation->set_rules('name', 'Mark', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">Employee added successfully</div>');
            redirect('admin/timetable/index');
        }
    }

/* 

public function add_teachertt() {
    if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
        access_denied();
    }
    $this->session->set_userdata('top_menu', 'Academics');
    $this->session->set_userdata('sub_menu', 'timetable/index');
    $session = $this->setting_model->getCurrentSession();
    $data['title'] = 'Timtable';
    $data['exam_id'] = "";
    $data['class_id'] = "";
    $data['section_id'] = "";
    $exam = $this->exam_model->get();
    $class = $this->class_model->get();
    $section = $this->section_model->get();
    $teacher = $this->staff_model->get_staff();
    $subject = $this->subject_model->get();
    $data['teachers'] = $teacher;
    $data['classlists'] = $class;
    $data['sections'] = $section;
    $data['subjects'] = $subject;
    $data['periods']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
    $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
    $userdata = $this->customlib->getUserData();
    $feecategory = $this->feecategory_model->get();
    $data['feecategorylist'] = $feecategory;       
    if ($_POST) {
        //print_r($_POST); die;
            $timefrom1 = $this->input->post('timefrom1');
            $timeto1 = $this->input->post('timeto1'); 
            $timefrom2 = $this->input->post('timefrom2');
            $timeto2 = $this->input->post('timeto2'); 
            $timefrom3 = $this->input->post('timefrom3');
            $timeto3 = $this->input->post('timeto3'); 
            $timefrom4 = $this->input->post('timefrom4');
            $timeto4 = $this->input->post('timeto4'); 
            $timefrom5 = $this->input->post('timefrom5');
            $timeto5 = $this->input->post('timeto5');
            $timefrom6 = $this->input->post('timefrom6');
            $timeto6 = $this->input->post('timeto6');
            $timefrom7 = $this->input->post('timefrom7');
            $timeto7 = $this->input->post('timeto7');
            $timefrom8 = $this->input->post('timefrom8');
            $timeto8 = $this->input->post('timeto8');
        for ($i=1; $i <=8 ; $i++) {
          
        $save['teacher_id'] = $this->input->post('teacher_id');
        $save['subject_id'] = $this->input->post('subject_id_m'.$i);
        $save['section_id'] = $this->input->post('section_id_m'.$i);
        $save['class_id'] = $this->input->post('class_id_m'.$i);
        $save['days'] =  1;
        $save['timefrom'] = $timefrom1;
        $save['timeto'] = $timeto1;
        $save['period_id'] = $i;
        $save['status'] = 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }

         for ($i=1; $i <=8 ; $i++) { 

        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_t'.$i);
        $save['section_id']= $this->input->post('section_id_t'.$i);
        $save['class_id']= $this->input->post('class_id_t'.$i);
        $save['timefrom'] = $timefrom2;
        $save['timeto'] = $timeto2;
        $save['days']=  2;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }


         for ($i=1; $i <=8 ; $i++) { 

            $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_w'.$i);
        $save['section_id']= $this->input->post('section_id_w'.$i);
        $save['class_id']= $this->input->post('class_id_w'.$i);
           $save['timefrom'] = $timefrom3;
        $save['timeto'] = $timeto3;
        $save['days']= 3;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }


         for ($i=1; $i <=8 ; $i++) { 

        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_th'.$i);
        $save['section_id']= $this->input->post('section_id_th'.$i);
        $save['class_id']= $this->input->post('class_id_th'.$i);
        $save['days']=4;
        $save['timefrom'] = $timefrom4;
        $save['timeto'] = $timeto4;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }

      for ($i=1; $i <=8 ; $i++) { 
        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_f'.$i);
        $save['section_id']= $this->input->post('section_id_f'.$i);
        $save['class_id']= $this->input->post('class_id_f'.$i);
        $save['days']= 5;
        $save['timefrom'] = $timefrom5;
        $save['timeto'] = $timeto5;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }
      for ($i=1; $i <=8 ; $i++) { 
        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_s'.$i);
        $save['section_id']= $this->input->post('section_id_s'.$i);
        $save['class_id']= $this->input->post('class_id_s'.$i);
        $save['days']=  6;
        $save['timefrom'] = $timefrom6;
        $save['timeto'] = $timeto6;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }
        
      }  
    $this->load->view('layout/header', $data);
    $this->load->view('admin/timetable/add_teachertt', $data);
    $this->load->view('layout/footer', $data);    
}
 */


 

public function add_teachertt() {
    if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
        access_denied();
    }
    $this->session->set_userdata('top_menu', 'Academics');
    $this->session->set_userdata('sub_menu', 'timetable/index');
    $session = $this->setting_model->getCurrentSession();
    $data['title'] = 'Timtable';
    $data['exam_id'] = "";
    $data['class_id'] = "";
    $data['section_id'] = "";
    $exam = $this->exam_model->get();
    $class = $this->class_model->get();
    $section = $this->section_model->get();
    $teacher = $this->staff_model->get_staff();
    $subject = $this->subject_model->get();
    $data['teachers'] = $teacher;
    $data['classlists'] = $class;
    $data['sections'] = $section;
    $data['subjects'] = $subject;
    $data['periods']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
    $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
    $userdata = $this->customlib->getUserData();
    $feecategory = $this->feecategory_model->get();
    $data['feecategorylist'] = $feecategory;       
    if ($_POST) {
        //print_r($_POST); die;
            $timefrom1 = $this->input->post('timefrom1');
            $timeto1 = $this->input->post('timeto1'); 
            $timefrom2 = $this->input->post('timefrom2');
            $timeto2 = $this->input->post('timeto2'); 
            $timefrom3 = $this->input->post('timefrom3');
            $timeto3 = $this->input->post('timeto3'); 
            $timefrom4 = $this->input->post('timefrom4');
            $timeto4 = $this->input->post('timeto4'); 
            $timefrom5 = $this->input->post('timefrom5');
            $timeto5 = $this->input->post('timeto5');
            $timefrom6 = $this->input->post('timefrom6');
            $timeto6 = $this->input->post('timeto6');
            $timefrom7 = $this->input->post('timefrom7');
            $timeto7 = $this->input->post('timeto7');
            $timefrom8 = $this->input->post('timefrom8');
            $timeto8 = $this->input->post('timeto8');
        for ($i=1; $i <=8 ; $i++) {
          
        $save['teacher_id'] = $this->input->post('teacher_id');
        $save['subject_id'] = $this->input->post('subject_id_m'.$i);
        $save['section_id'] = $this->input->post('section_id_m'.$i);
        $save['class_id'] = $this->input->post('class_id_m'.$i);
        $save['days'] =  1;
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['period_id'] = $i;
        $save['status'] = 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }

         for ($i=1; $i <=8 ; $i++) { 

        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_t'.$i);
        $save['section_id']= $this->input->post('section_id_t'.$i);
        $save['class_id']= $this->input->post('class_id_t'.$i);
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['days']=  2;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }


         for ($i=1; $i <=8 ; $i++) { 

            $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_w'.$i);
        $save['section_id']= $this->input->post('section_id_w'.$i);
        $save['class_id']= $this->input->post('class_id_w'.$i);
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['days']= 3;
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }


         for ($i=1; $i <=8 ; $i++) { 

        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_th'.$i);
        $save['section_id']= $this->input->post('section_id_th'.$i);
        $save['class_id']= $this->input->post('class_id_th'.$i);
        $save['days']=4;
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }

      for ($i=1; $i <=8 ; $i++) { 
        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_f'.$i);
        $save['section_id']= $this->input->post('section_id_f'.$i);
        $save['class_id']= $this->input->post('class_id_f'.$i);
        $save['days']= 5;
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }
      for ($i=1; $i <=8 ; $i++) { 
        $save['teacher_id']= $this->input->post('teacher_id');
        $save['subject_id']= $this->input->post('subject_id_s'.$i);
        $save['section_id']= $this->input->post('section_id_s'.$i);
        $save['class_id']= $this->input->post('class_id_s'.$i);
        $save['days']=  6;
        $save['timefrom'] = $this->input->post('timefrom'.$i);
        $save['timeto'] = $this->input->post('timeto'.$i);
        $save['period_id']= $i;
        $save['status']= 1 ;
        $this->db->insert('teacher_timetable',$save);             
        }        
      }  
    $this->load->view('layout/header', $data);
    $this->load->view('admin/timetable/add_teachertt', $data);
    $this->load->view('layout/footer', $data);    
}

public function edit_teachertt($teacher=0)
{   
     $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'timetable/index');
        $session = $this->setting_model->getCurrentSession();
        $data['title'] = 'Exam Marks';
        $data['exam_id'] = "";
        $data['class_id'] = "";
        $data['section_id'] = "";
        $exam = $this->exam_model->get();
        $class = $this->class_model->get();
        $section = $this->section_model->get();
        $teacher = $this->staff_model->get_staff();
        $subject = $this->subject_model->get();
        $data['teachers'] = $teacher;
        $data['classlists'] = $class;
        $data['sections'] = $section;
        $data['subjects'] = $subject;
        $data['periods']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
        $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
        $userdata = $this->customlib->getUserData();
        $feecategory = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory; 
        $teacher_id=$this->input->post('teacher_id'); 
        
        if(!empty($_POST['ids'])){
           // print_r($_POST); die;
      //  $days=$this->input->post('days');
       
        $ids=$this->input->post('ids');
       // print_r($_POST); die();
        $periods=$this->input->post('periods');
       
  /*   $days= array('0'=>"1",'1'=>"1",'2'=>"1",'3'=>"1",'4'=>"1",'5'=>"1", 
         '6'=>"2",'7'=>"2", '8'=>"2",'9'=>"2",'10'=>"2",'11'=>"2",
        '12'=>"3", '13'=>"3", '14'=>"3", '15'=>"3", '16'=>"3",'17'=>"3",
        '18'=>"4", '19'=>"4",'20'=>"4",'21'=>"4", '22'=>"4", '23'=>"4",
         '24'=>"5",  '25'=>"5",'26'=>"5",'27'=>"5",'28'=>"5",'29'=>"5",
          '30'=>"6", '31'=>"6", '32'=>"6",'33'=>"6",'34'=>"6",'35'=>"6",
          '36'=>"7", '37'=>"7", '38'=>"7", '39'=>"7", '40'=>"7",'41'=>"7",
          '42'=>"8", '43'=>"8",'44'=>"8",'45'=>"8", '46'=>"8", '47'=>"8" ); */

           $days= array( '1'=>"1",'2'=>"2",'3'=>"3",'4'=>"4",'5'=>"5", '6'=>"6",'7'=>"7", '8'=>"8",

                         '9'=>"1",'10'=>"2",'11'=>"3",'12'=>"4",'13'=>"5", '14'=>"6", '15'=>"7", '16'=>"8",

                         '17'=>"1",'18'=>"2", '19'=>"3",'20'=>"4",'21'=>"5", '22'=>"6", '23'=>"7", '24'=>"8",
   
                          '25'=>"1",'26'=>"2",'27'=>"3",'28'=>"4",'29'=>"5", '30'=>"6",'31'=>"7", '32'=>"8",
                      
                          '33'=>"1",'34'=>"2",'35'=>"3",'36'=>"4",'37'=>"5", '38'=>"6", '39'=>"7", '40'=>"8",

                          '41'=>"1",'42'=>"2",'43'=>"3",'44'=>"4",'45'=>"5", '46'=>"6", '47'=>"7", '48'=>"8"

                    ); 
        
        // 
        $i = 1 ;
        foreach($ids as $id){
             $save['subject_id']= $this->input->post('subject_id_'.$id);
             $save['section_id']= $this->input->post('section_id_'.$id);
             $save['class_id']= $this->input->post('class_id_'.$id);
             $save['timefrom']= $this->input->post('timefrom'.$days[$i]);
             $save['timeto']= $this->input->post('timeto'.$days[$i]);
            
             $this->db->where('id',$id);
             $this->db->update('teacher_timetable',$save);
            $i++;  
             
              
          }  
          
        }
         $teacherstimetable = $this->staff_model->getdetails_by_teacherID1($teacher_id);
         if(empty($teacherstimetable))         
          redirect('admin/timetable/add_teachertt');
         //print_r($teacherstimetable); die;
            $data['timatable']=$teacherstimetable;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/add_teachertt_edit', $data);
            $this->load->view('layout/footer', $data);
}


public function index3() {
    if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
        access_denied();
    }
    $this->session->set_userdata('top_menu', 'Academics');
    $this->session->set_userdata('sub_menu', 'timetable/index');
    $session = $this->setting_model->getCurrentSession();
    $data['title'] = 'Exam Marks';
    $data['exam_id'] = "";
    $data['class_id'] = "";
    $data['section_id'] = "";
    $exam = $this->exam_model->get();
    $class = $this->class_model->get();
    $section = $this->section_model->get();
    $teacher = $this->staff_model->get_staff();
    $subject = $this->subject_model->get();
    $data['teachers'] = $teacher;
    $data['classlists'] = $class;
    $data['sections'] = $section;
    $data['subjects'] = $subject;
    $data['period']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
    $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
    $userdata = $this->customlib->getUserData();
    $feecategory = $this->feecategory_model->get();
    $data['feecategorylist'] = $feecategory;         
        // $getDaysnameList = $this->customlib->getDaysname();
        // $data['getDaysnameList'] = $getDaysnameList;
        $final_array = array();
        $result_subjects = $this->staff_model->get_teacher_timetable();
     //  print_r($section); die;
      // print_r($result_subjects); die;

       // $data['result_array'] = $result_subjects;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/teacher_timetable_byclass', $data);
        $this->load->view('layout/footer', $data);
    
}





public function gettimetable_by_teacher()
{ 
$teacher = $this->staff_model->get_staff();
$data['teachers'] = $teacher;
$teacher_id = $this->input->post('teacher_id'); 
$data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
$data['result_array1'] = $this->staff_model->getdetails_by_teacherID($teacher_id); 
$this->load->view('layout/header', $data);
$this->load->view('admin/timetable/timetableteacherList', $data);
$this->load->view('layout/footer', $data);
}


public function gettimetable_by_class()
{ 
 $class = $this->class_model->get();
 $data['classlists'] = $class;
 $class_id = $this->input->post('class_id'); 
 $section_id = $this->input->post('section_id'); 
 $data['days']= $this->db->get('days')->result_array();
 $data['period']=$this->db->get('periods')->result_array();
 $data['class_name']=$this->db->get_where('classes',array('id'=>$class_id))->row()->class;
 $data['section_name']=$this->db->get_where('sections',array('id'=>$section_id))->row()->section;


 $data['result_array1']=array();
 foreach($data['days'] as $days_key=>$days_value)
 {
    foreach($data['period'] as $period_value) 
    {
         $data['result_array1'][$days_value['value']][] = $this->staff_model->getdetails_by_classID11($class_id, $section_id ,$days_value['id'],$period_value['id']);
    }  
  }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/teacher_timetable_byclass', $data);
        $this->load->view('layout/footer', $data);
}


public function index2() {
    if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
        access_denied();
    }
    $this->session->set_userdata('top_menu', 'Academics');
    $this->session->set_userdata('sub_menu', 'timetable/index');
    $session = $this->setting_model->getCurrentSession();
    $data['title'] = 'Exam Marks';
    $data['exam_id'] = "";
    $data['class_id'] = "";
    $data['section_id'] = "";
    $exam = $this->exam_model->get();
    $class = $this->class_model->get();
    $section = $this->section_model->get();
    $teacher = $this->staff_model->get_staff();
    $subject = $this->subject_model->get();
    $data['teachers'] = $teacher;
    $data['classlists'] = $class;
    $data['sections'] = $section;
    $data['subjects'] = $subject;
    $data['period']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
    $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
    $userdata = $this->customlib->getUserData();
    $feecategory = $this->feecategory_model->get();
    $data['feecategorylist'] = $feecategory;         
        // $getDaysnameList = $this->customlib->getDaysname();
        // $data['getDaysnameList'] = $getDaysnameList;
        $final_array = array();
        $result_subjects = $this->staff_model->get_teacher_timetable();
     //  print_r($section); die;
      // print_r($result_subjects); die;

       // $data['result_array'] = $result_subjects;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/timetableteacherList', $data);
        $this->load->view('layout/footer', $data);
    
}


//end class

}

?>