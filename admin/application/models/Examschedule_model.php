<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Examschedule_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getDetailbyClsandSection($class_id, $section_id, $exam_id) {
        $query = $this->db->query("SELECT exam_schedules.*,subjects.name,subjects.id as subject_id,subjects.type 
        FROM exam_schedules,teacher_subjects,exams,class_sections,subjects 
        WHERE exam_schedules.teacher_subject_id = teacher_subjects.id and exam_schedules.exam_id =exams.id 
        and class_sections.id =teacher_subjects.class_section_id and teacher_subjects.subject_id=subjects.id 
        and class_sections.class_id =" . $this->db->escape($class_id) . " 
        and class_sections.section_id=" . $this->db->escape($section_id) . " 
        and exam_id =" . $this->db->escape($exam_id) . " and teacher_subjects.session_id=" . $this->db->escape($this->current_session) ." order by  exam_schedules.date_of_exam asc" );
        return $query->result_array();
    }
     

     public function getDetailbyClsandSection_api($class_id, $section_id, $exam_id, $session_id) {
        $query = $this->db->query("SELECT exam_schedules.*,subjects.name,subjects.id as subject_id,subjects.type 
        FROM exam_schedules,teacher_subjects,exams,class_sections,subjects 
        WHERE exam_schedules.teacher_subject_id = teacher_subjects.id and exam_schedules.exam_id =exams.id 
        and class_sections.id =teacher_subjects.class_section_id and teacher_subjects.subject_id=subjects.id 
        and class_sections.class_id =" . $this->db->escape($class_id) . " 
        and class_sections.section_id=" . $this->db->escape($section_id) . " 
        and exam_id =" . $this->db->escape($exam_id) . " and exam_schedules.session_id=" . $this->db->escape($session_id) ." and teacher_subjects.session_id=" . $this->db->escape($session_id) ." order by  exam_schedules.date_of_exam asc" );
        return $query->result_array();
    }


    public function getTeacherSubjects($class_id, $section_id, $id) {

        $query = $this->db->select("teacher_subjects.subject_id")->join("class_sections", "class_sections.id = teacher_subjects.class_section_id")->where(array("class_sections.class_id" => $class_id, "class_sections.section_id" => $section_id, "teacher_subjects.teacher_id" => $id))->get("teacher_subjects");

        return $query->result_array();
    }

    public function getExamByClassandSection($class_id, $section_id) {  
        $sql = "SELECT * FROM exams INNER JOIN (SELECT exam_schedules.*,teacher_subjects.class_id,teacher_subjects.class_name ,teacher_subjects.section_id,teacher_subjects.section_name FROM `exam_schedules` INNER JOIN (SELECT teacher_subjects.*,classes.id as `class_id`,classes.class as `class_name` ,sections.id as `section_id`,sections.section as `section_name` FROM `class_sections` 
        INNER JOIN teacher_subjects on teacher_subjects.class_section_id=class_sections.id
        INNER JOIN classes on classes.id=class_sections.class_id
        INNER JOIN sections on sections.id=class_sections.section_id
        WHERE class_sections.class_id =" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id) . " and teacher_subjects.session_id=" . $this->db->escape($this->current_session). ") as teacher_subjects on teacher_subjects.id=teacher_subject_id group by exam_schedules.exam_id) as exam_schedules on exams.id=exam_schedules.exam_id order by exams.id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

     public function getExamByClassandSection_api($class_id, $section_id, $session_id ){

        $sql = "SELECT * FROM exams INNER JOIN (SELECT exam_schedules.*,teacher_subjects.class_id,teacher_subjects.class_name ,teacher_subjects.section_id,teacher_subjects.section_name FROM `exam_schedules` INNER JOIN (SELECT teacher_subjects.*,classes.id as `class_id`,classes.class as `class_name` ,sections.id as `section_id`,sections.section as `section_name` FROM `class_sections` 
        INNER JOIN teacher_subjects on teacher_subjects.class_section_id=class_sections.id
        INNER JOIN classes on classes.id=class_sections.class_id
        INNER JOIN sections on sections.id=class_sections.section_id
        WHERE class_sections.class_id =" .$class_id. " and class_sections.section_id=".$section_id." 
        and teacher_subjects.session_id=".$session_id.") as teacher_subjects on teacher_subjects.id=teacher_subject_id group by exam_schedules.exam_id ) as exam_schedules on exams.id=exam_schedules.exam_id
         order by exams.id";
        $query = $this->db->query($sql);
        return $query->result_array();
        
        /*
        $sql = "SELECT * FROM exams INNER JOIN (SELECT exam_schedules.*,teacher_subjects.class_id,teacher_subjects.class_name ,teacher_subjects.section_id,teacher_subjects.section_name FROM `exam_schedules` INNER JOIN (SELECT teacher_subjects.*,classes.id as `class_id`,classes.class as `class_name` ,sections.id as `section_id`,sections.section as `section_name` FROM `class_sections` 
        INNER JOIN teacher_subjects on teacher_subjects.class_section_id=class_sections.id
        INNER JOIN classes on classes.id=class_sections.class_id
        INNER JOIN sections on sections.id=class_sections.section_id
        WHERE class_sections.class_id =" .$class_id. " and class_sections.section_id=".$section_id." and teacher_subjects.session_id=".$session_id.") as teacher_subjects on teacher_subjects.id=teacher_subject_id group by exam_schedules.exam_id where exam_schedules.session_id = $session_id ) as exam_schedules on exams.id=exam_schedules.exam_id where exam_schedules.session_id = '".$session_id."' order by exams.id";
        $query = $this->db->query($sql);
        return $query->result_array();*/
    }

    
    public function getresultByStudentandExam_pdf($exam_id, $student_id,$session_id) {
        $query = $this->db->query("SELECT exam_schedules.id as `exam_schedule_id`,exam_schedules.full_marks,exam_schedules.exam_id as `exam_id`,
            exam_schedules.passing_marks,exam_results.attendence,exam_results.get_marks,exam_results.optional_status,exam_results.note, 
            subjects.name,subjects.code,subjects.type ,subjects.parent,subjects.id as sub_parent_id 
            FROM `exam_schedules` INNER JOIN teacher_subjects ON teacher_subjects.id=exam_schedules.teacher_subject_id 
            INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id 
            INNER JOIN subjects ON teacher_subjects.subject_id=subjects.id  
            WHERE exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and teacher_subjects.session_id=" . $this->db->escape($session_id) . "
             and exam_results.student_id=" . $this->db->escape($student_id) . " and teacher_subjects.session_id=" . $this->db->escape($session_id). " 
             and exam_results.teacher_approval = 1 and subjects.parent = 0 ORDER BY sequence ASC");
        return $query->result_array();
    }  

     public function getresultByStudentandExam_pdf_child($exam_id, $student_id,$parent,$session_id) {
        $query = $this->db->query("SELECT exam_schedules.id as `exam_schedule_id`,exam_schedules.full_marks,exam_schedules.exam_id as `exam_id`,
            exam_schedules.passing_marks,exam_results.attendence,exam_results.get_marks,exam_results.optional_status,exam_results.note, subjects.name,subjects.code,subjects.type ,subjects.parent,subjects.id as parent_id FROM `exam_schedules`
                INNER JOIN teacher_subjects ON teacher_subjects.id=exam_schedules.teacher_subject_id 
              INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN subjects ON teacher_subjects.subject_id=subjects.id  
              WHERE exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and teacher_subjects.session_id=" . $this->db->escape($session_id) . " 
              and exam_results.student_id=" . $this->db->escape($student_id) . " and teacher_subjects.session_id=" . $this->db->escape($session_id). " and exam_results.teacher_approval = 1 and subjects.parent = '".$parent."' ORDER BY sequence ASC");
        return $query->result_array();
    }



    public function getExamByClassandSection1($class_id, $section_id) {

        $sql = "SELECT * FROM exams INNER JOIN (SELECT exam_schedules.*,teacher_subjects.class_id,teacher_subjects.class_name ,teacher_subjects.section_id,teacher_subjects.section_name FROM `exam_schedules` INNER JOIN (SELECT teacher_subjects.*,classes.id as `class_id`,classes.class as `class_name` ,sections.id as `section_id`,sections.section as `section_name` FROM `class_sections` 
        INNER JOIN teacher_subjects on teacher_subjects.class_section_id=class_sections.id
        INNER JOIN classes on classes.id=class_sections.class_id
        INNER JOIN sections on sections.id=class_sections.section_id
        WHERE class_sections.class_id =" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id) . " and teacher_subjects.session_id=" . $this->db->escape($this->current_session) . ") as teacher_subjects on teacher_subjects.id=teacher_subject_id group by exam_schedules.exam_id) as exam_schedules on exams.id=exam_schedules.exam_id order by exams.id desc ";



        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function getresultByStudentandExam($exam_id, $student_id) {
        $query = $this->db->query("SELECT exam_schedules.id as `exam_schedule_id`,exam_schedules.full_marks,exam_schedules.exam_id as `exam_id`,
            exam_schedules.passing_marks,exam_results.attendence,exam_results.get_marks,exam_results.note, subjects.name,subjects.code,subjects.type  FROM `exam_schedules` INNER JOIN teacher_subjects ON teacher_subjects.id=exam_schedules.teacher_subject_id  INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN subjects ON teacher_subjects.subject_id=subjects.id  WHERE exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and teacher_subjects.session_id=" . $this->db->escape($this->current_session) . " and exam_results.student_id=" . $this->db->escape($student_id) . " and teacher_subjects.session_id=" . $this->db->escape($this->current_session));
        return $query->result_array();
    }

    public function getresultByStudentandExam_api($exam_id, $student_id, $session_id ) {
        $query = $this->db->query("SELECT exam_schedules.id as `exam_schedule_id`,exam_schedules.full_marks,exam_schedules.exam_id as `exam_id`,
            exam_schedules.passing_marks,exam_results.attendence,exam_results.get_marks,exam_results.note, subjects.name,subjects.code,subjects.type  FROM `exam_schedules` INNER JOIN teacher_subjects ON teacher_subjects.id=exam_schedules.teacher_subject_id  INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN subjects ON teacher_subjects.subject_id=subjects.id  WHERE exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and teacher_subjects.session_id=" . $this->db->escape( $session_id) . " and exam_results.student_id=" . $this->db->escape($student_id) . " and teacher_subjects.session_id=" . $this->db->escape( $session_id));
        return $query->result_array();
    }


    public function getclassandsectionbyexam($exam_id) {
       //  $query = $this->db->query("SELECT exam_schedules.exam_id,classes.id as `class_id`,sections.id as `section_id`,classes.class as `class`,sections.section as `section` FROM `exam_schedules`,`teacher_subjects`,`class_sections`,classes,sections WHERE exam_schedules.teacher_subject_id = teacher_subjects.id and class_sections.id =teacher_subjects.class_section_id and class_sections.class_id =classes.id and class_sections.section_id=sections.id and exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and exam_schedules.session_id=" . $this->db->escape($this->current_session) . " group by exam_schedules.exam_id");
      
        $query = $this->db->query("SELECT exam_schedules.exam_id,classes.id as `class_id`,sections.id as `section_id`,classes.class as `class`,sections.section as `section` FROM `exam_schedules`,`teacher_subjects`,`class_sections`,classes,sections WHERE exam_schedules.teacher_subject_id = teacher_subjects.id and class_sections.id =teacher_subjects.class_section_id and class_sections.class_id =classes.id and class_sections.section_id=sections.id and exam_schedules.exam_id=" . $this->db->escape($exam_id) . " and exam_schedules.session_id=" . $this->db->escape($this->current_session) . " group by teacher_subjects.class_section_id");
        // echo $this->db->last_query(); die();
        return $query->result_array();
    }

   


    function getstudentdetails($id){
        
        $qry = "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id WHERE  students.id = '".$id."'" ;
        return  $this->db->query($qry)->row();
    }

}
