<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proxy_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('timetables');
    }

    public function add($data) {
        if (($data['id']) != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('timetables', $data); 
        } else {
            $this->db->insert('timetables', $data); 
            return $this->db->insert_id();
        }
    }

public function get_list()
{
 $result = $this->db->query("SELECT proxyteachers.* ,staff.name, staff.surname  from proxyteachers join staff on staff.id =  proxyteachers.on_behalf where 1 group by on_behalf  order by id desc") ; 
 return $result->result();
}
    

public function get_teachername($tid = 0)
{
  $qry = "SELECT CONCAT(name,surname) as tname from staff where id = $tid ";
        $result = $this->db->query($qry)->row()->tname;

       return $result;
}

    public function get_timetable($teacher_id,$p_date)
    {   
            $day = date('w',strtotime($p_date));
            $data = array('teacher_id'=> $teacher_id,'days'=>$day ,'class_id !='=>0 , 'section_id !='=>0,'status'=>1) ;
            $query = $this->db->get_where('teacher_timetable', $data);
            return $query->result();
    }
    
  public function check_proxy($teacher_id,$date)
    {   
      $this->db->where('teacher_id', $teacher_id);
      $this->db->where('date',$date);
      $query = $this->db->get('proxyteachers');
      return $query->result();
    }


    public function get_teachers($period,$days,$date)
       {
        $ary = array('I'=>1,'II'=>2,'III' =>3, 'IV'=>4, 'V'=>5, 'VI'=>6, 'VII'=>7,'VIII'=>8);
 foreach ($ary as $key => $value) {
   if ($value ==  $period) {
       $periods = $key ;
   }
 }
       $query = "SELECT extrateacher from proxyteachers where periods = '".$periods."' and `date` = '".$date."' "; 
       $qry = "SELECT teacher_timetable.* ,staff.* from teacher_timetable join staff on teacher_timetable.teacher_id = staff.id where teacher_timetable.period_id = $period and teacher_timetable.days = $days and teacher_timetable.class_id= 0 and teacher_timetable.section_id = 0 and staff.id not in ($query)  group by teacher_timetable.teacher_id ";
        $result = $this->db->query($qry)->result_array();

       return $result;
    }

    public function get_count($tid,$day = '')
    {
         $qry = "SELECT count(id) as total_lecture from teacher_timetable where teacher_id = $tid and days = $day and class_id != 0 and section_id != 0  ";
         $result = $this->db->query($qry)->row()->total_lecture;
         return $result ;
    }

    public function findfield($table,$fieldname1,$fieldvalue1,$returnfield)
      {
      $this->db->select($returnfield);
      $this->db->from($table);
      $this->db->where($fieldname1,$fieldvalue1);
      $query = $this->db->get();
      foreach ($query->result() as $value)
      {}
      return @$value->$returnfield;
        }
    //left join proxyteachers on staff.id = proxyteachers.extrateacher 
  //and  proxyteachers.extrateacher != staff.id 
}
