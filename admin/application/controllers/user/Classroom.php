<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classroom extends Student_Controller {

    function __construct() {
        parent::__construct();
    }

    function index_old() {
        $this->session->set_userdata('top_menu', 'Classroom');
        $student_id = $this->customlib->getStudentSessionUserID();
        $student = $this->student_model->get($student_id);
        //print_r($this->db->last_query()); die;
        $class_id = $student['class_id'];
        $section_id = $student['section_id'];
        $data['title'] = 'Exam Marks';
        $data['class_id'] = $class_id;
        $data['section_id'] = $section_id;
        $todate = date('d-m-Y');
        $day=date('l', strtotime($todate)); 
        $classroom = $this->db->query("Select classroom.*,  classroom.classroom_active,classroom.title, classroom.description,
         classroom.description,classroom.iscancel,classroom.end_classAt,classroom.startdate, classroom.isvalid from classroom 
           where classroom.class_id = $class_id and DATE_FORMAT(classroom.created_at, '%W') ='$day'  ")->result();       
        $final_array = array();
        if (!empty($classroom)) {
            $data['result'] = $classroom;
        }
       else{
        $data['result'] = array();  
       }
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/classroom', $data);
        $this->load->view('layout/student/footer', $data);
    
   }

   public function index()
   {
       $mydate = date('Y-m-d');
       $day=date('l', strtotime($mydate)); 
       $this->session->set_userdata('top_menu', 'conference');
       $this->session->set_userdata('sub_menu', 'conference/gmeet');
       $data['staffList'] =  $this->db->query("SELECT * from staff where 1 ")->result();
       $data['classes'] =  $this->db->query("SELECT * from classes where 1 ")->result();
       $data['sections'] =  $this->db->query("SELECT * from sections where 1 ")->result();
       $student=$this->session->userdata('student'); 

       $student_id=$student['student_id']; 
       $section= $this->db->query("SELECT section_id from student_session where student_id=$student_id")->row(); 
       $class= $this->db->query("SELECT class_id from student_session where student_id=$student_id")->row(); 
      //  print_r($section_id->section_id);
       $section_id=$section->section_id; 
       $class_id=$class->class_id; 
       
       if($section_id!=0)
       $cond = "class_id = $class_id and section_id = $section_id and DATE_FORMAT(`class_date`, '%W') ='$day' " ;
       else{
         $cond = "class_id = $class_id and DATE_FORMAT(`class_date`, '%W') ='$day'";
       }
      
       $data['conferences'] =  $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where $cond ")->result();

       
       if (empty($_POST)){
           $data['title'] = 'G Meet List';
           $this->load->view('layout/student/header', $data);
           $this->load->view('user/gmeet', $data);
           $this->load->view('layout/student/footer', $data);
       } else {
           $meetid=$this->input->post('meetid'); 
           if($meetid==''){
           $data_insert = array(
               'class_id' => $this->input->post('class_id'),
               'section_id' => $this->input->post('section_id'),
               'teacher_id' => $this->input->post('teacher_id'),
               'url' => $this->input->post('url'),
               'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
               'topic' => $this->input->post('topic'),
               'is_active' => 1 );
           $this->db->insert('meet_classroom',$data_insert);
       }else{
           $data_update2 = array(
               'teacher_id' => $this->input->post('teacher_id'),
               'url' => $this->input->post('url'),
               'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
               'topic' => $this->input->post('topic'),
               //'school_id' => $this->school_id,
               'is_active' => 1 );
              
           $this->db->where('id', $meetid);
           $this->db->update('meet_classroom', $data_update2);
       }
           $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('add_successfully') . '</div>');
           $response = array('status' => 1, 'message' => "Added Successfully");
           return $this->output
           ->set_content_type('application/json')
           ->set_status_header(200)
           ->set_output(json_encode($response));
       }
   }
   

   public function generatechecksumClassroom($studentName,$meetingid,$password)
   {
     $string = sha1("joinfullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=trueVX4pnXv8XOGpD07ssrbPWqkRthbYq8S581wA6kd5uxU");
     return $string ;
   } 
   


   public function joinRoom($videoId){  
    $urllll = base_url();  
  
    $student_id = $this->customlib->getStudentSessionUserID();
    $student = $this->student_model->get($student_id);
    if (!empty($videoId)) 
    {       
       $classdetails=  $this->db->query("SELECT CONCAT(students.firstname,students.lastname) as stu_name,classroom.classroom_active,
       classroom.link, classroom.end_classAt,classroom.startdate,classroom.starttime,
       classroom.exceedTimeValue
       from classroom       
       join students on  students.id = $student_id  
       where classroom.id = '$videoId' and classroom.classroom_active = 1")->row();
       $meetingid = $classdetails->link;
       $studentName = preg_replace('/\s+/', '', $classdetails->stu_name);
       $password = "Students@123";
       $checksum = $this->generatechecksumClassroom($studentName,$meetingid,$password); 
      
      if(!empty($classdetails)){
             if ($classdetails->exceedTimeValue == 0) {
  
             $this->db->where('id',$videoId);
             $this->db->update('classroomStudents',array('joined'=>1));
             $data['status'] = true;
             $data['msg'] = "Joined Active Class Successfully";
             $data['status'] = true;            
              $data['url'] = $urllll = "https://room.classeye.in/bigbluebutton/api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
              $data['msg'] = "Joined Active Class Successfully"; 
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1,'classroomlink'=>$urllll));
             }else{  
             $selectedTime = $classdetails->starttime;
             $endTime = strtotime("+$classdetails->exceedTimeValue minutes", strtotime($selectedTime));
             $newtime =  date('h:i:s', $endTime);        
            // if($newtime <= $currenttime ){
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1));
              $data['status'] = true;            
              $data['url'] = $urllll = "https://room.classeye.in/bigbluebutton/api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
              $data['msg'] = "Joined Active Class Successfully"; 
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1,'classroomlink'=>$urllll));
            /* }else{
              $data['status'] = false;
              $data['msg'] = "Exceed the time limit to join Classroom";     
             }*/
          }          
      }            
   } 
   redirect($urllll);
  }
  


 public function joinRoomPranav($videoId){  
    $urllll = base_url();  
    if (!empty($videoId)) 
    {  
       
       $classdetails=  $this->db->query("SELECT classroomStudents.*,CONCAT(students.firstname,students.lastname) as stu_name,classroom.classroom_active,
       classroom.link, classroom.end_classAt,classroom.startdate,classroom.starttime,
       classroom.exceedTimeValue
       from classroomStudents 
       join classroom on classroom.id = classroomStudents.classroom_id 
       join students on classroomStudents.student_id = students.id 
       where classroomStudents.id = '$videoId' and classroom.classroom_active = 1")->row();
       $meetingid = $classdetails->link;
       $studentName = preg_replace('/\s+/', '', $classdetails->stu_name);
       $password = "Students@123";
       $checksum = $this->generatechecksumClassroom($studentName,$meetingid,$password); 
      
      if(!empty($classdetails)){
             if ($classdetails->exceedTimeValue == 0) {

             $this->db->where('id',$videoId);
             $this->db->update('classroomStudents',array('joined'=>1));
             $data['status'] = true;
             $data['msg'] = "Joined Active Class Successfully";
             $data['status'] = true;            
              $data['url'] = $urllll = "https://room.classeye.in/bigbluebutton/api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
              $data['msg'] = "Joined Active Class Successfully"; 
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1,'classroomlink'=>$urllll));
             }else{  
             $selectedTime = $classdetails->starttime;
             $endTime = strtotime("+$classdetails->exceedTimeValue minutes", strtotime($selectedTime));
             $newtime =  date('h:i:s', $endTime);        
            // if($newtime <= $currenttime ){
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1));
              $data['status'] = true;            
              $data['url'] = $urllll = "https://room.classeye.in/bigbluebutton/api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
              $data['msg'] = "Joined Active Class Successfully"; 
              $this->db->where('id',$videoId);
              $this->db->update('classroomStudents',array('joined'=>1,'classroomlink'=>$urllll));
            /* }else{
              $data['status'] = false;
              $data['msg'] = "Exceed the time limit to join Classroom";     
             }*/
          }          
      }            
   } 
   redirect($urllll);
 }



}

?>