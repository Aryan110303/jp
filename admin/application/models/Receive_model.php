<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receive_model extends CI_Model
{

    public $table = 'receive';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }
    public function get_all()
    {
      $this->db->order_by("id", "desc");
     return $this->db->get($this->table)->result();
       
    }
    public function get_stu_details(){
        $this->db->select('schlor_no,student_name,fhather_name,mother_name');
        $this->db->from('');
        $this->db->where('schlor_no',$data);
        return  $this->db->get()->row();
    }

    public function insert($data)
    {
       $this->db->insert($this->table,$data);
    }
    public function update($data)
    {
       $this->db->where('id',$data['id']);
       $this->db->update($this->table,$data);
        
    }
    
   public function get_data_by_query($qry){
    $query = $this->db->query($qry); 
    return $query->get->row();
   }


   public function get_sessionbyid($id){
          $this->db->select('sessions.session');
          $this->db->from('sessions');
          $this->db->join('student_session', 'sessions.id= student_session.session_id');
          $this->db->where('student_session.id', $id);
         return $this->db->get()->row(); 

   }
 public function getdetails($number){
     // $this->db->where('admission_no',$number);
    $qry =  "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id WHERE students.admission_no = '$number' " ; 
      return  $this->db->query($qry)->row();
 
 }

    public function get_by_id($id)
    {
        $this->db->where($this->id,$id);
      return  $this->db->get($this->table)->row();
    }

    public function delete($id)
    {
        $this->db->where($this->id,$id);
        $this->db->delete($this->table);
    }

    public function interview_data()
    {
      $this->db->order_by("id", "desc");
      $data = $this->db->get('interview_date')->result();
      if (!empty($data)) {
       return $data ; 
      }else{
        return $data = '' ;

      }       
    
    }


public function assigned_data($date)
    {
      $this->db->select('datecount');
      $this->db->where("ent_date", $date);
      $this->db->order_by("id", "desc");
      $this->db->limit(1);
      $data = $this->db->get('admission_soft')->row_array();
      if (!empty($data)) {
       return $data['datecount'] ; 
      }else{
        return $data = '' ;

      }
      
    }

public function assigned_studentlist($date)
    {
      $this->db->select('*');
      $this->db->where("ent_date", $date);
      $data = $this->db->get('admission_soft')->result();
      if (!empty($data)) {
        return $data ; 
      }else{
        return $data = '' ;

      }
      
    }


public function updaterecord($val,$id)
    {     
       $data['result'] = $val ; 
       $this->db->where('id',$id);
       $this->db->update('admission_soft', $data);
      
    }
    
    }

