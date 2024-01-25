<?php
 class Homeapi_model extends CI_model
 {
public function __construct() {
     $this->current_session = $this->setting_model->getCurrentSession();
}
    public function getByEmail($email) {
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function getStaffRoleAPi($role) {
       $this->db->select('*');
       $this->db->from('roles');
       $this->db->where('id',$role);
       $query = $this->db->get();
       if ($query->num_rows() > 0) {
         return $query->row();
       }
    }
    
    public function checkloginapi($loginData)
    {
        $record = $this->getByEmail($loginData['email']);
        if ($record) {
            $pass_verify = $this->enc_lib->passHashDyc($loginData['password'], $record->password);
            
            if ($pass_verify) {     
                $roles = $this->staffroles_model->getStaffRoles($record->id);
                $record->roles = $roles[0]->role_id;
                return $record;
            }
        }
        return false;
    }

    public function getClassList()
    {
        $this->db->select('*');
        $this->db->from('classes');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getsectionList($class)
    {
        $this->db->select('*');
        $this->db->from('sections AS SC');
        $this->db->join('class_sections AS CS','CS.section_id = SC.id');
        $this->db->where('CS.class_id',$class);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getStudentDetail($class,$section,$stName)
    {
        
        $this->db->select('*');
        $this->db->from('students AS S');
        $this->db->join('student_session AS SS','SS.student_id = S.id');
        $this->db->join('classes AS C','C.id = SS.class_id');
        $this->db->join('sections AS SC','SC.id = SS.section_id');
        if ($class != '') {
            $this->db->where('SS.class_id',$class);
            if ($section != '') {
                $this->db->where('SS.section_id',$section);
            } 
        }
        
        if ($stName != '') {
            $this->db->where('CONCAT(S.firstname , " ", S.lastname) LIKE "%'.$stName.'%"');
        }
        
        // $this->db->or_where(' "'.$stName.'"');
        
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result();
    }




    // public function searchStudent($stName)
    // {
    //     $this->db->select('*');
    //     $this->db->from('students AS S');
    //     $this->db->join('student_session AS SS','SS.student_id = S.id');
    //     $this->db->join('classes AS C','C.id = SS.class_id');
    //     $this->db->join('sections AS SC','SC.id = SS.section_id');
    //     $this->db->where('S.firstname LIKE "'.$stName.'"');
    //     $this->db->or_where('S.lastname LIKE "'.$stName.'"');
    //     $query = $this->db->get();
    //     // echo $this->db->last_query(); die();
    //     return $query->result();
    // }

    public function get_all_messages() {
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->order_by('id','Desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getStudentDetailByNum($number,$class_id,$section_id,$session_id)
    {
        $this->db->select('S.firstname,S.lastname,S.mobileno,C.class,SE.section,M.created_at,M.updated_at,M.sms_status,M.type,M.id');
        $this->db->from('messages AS M');
        $this->db->join('student_session AS SS','SS.session_id = M.session_id');
        $this->db->join('students AS S','S.id = SS.student_id');
        $this->db->join('classes AS C','C.id = M.class_id');
        $this->db->join('sections AS SE','SE.id = M.section_id');
        $this->db->where('M.class_id',$class_id);
        $this->db->where('M.section_id',$section_id);
        $this->db->where('M.session_id',$session_id);
        $this->db->where('S.mobileno',$number);
        // $this->db->where('SS.s')
        $query = $this->db->get();
        return $query->row();
    }

    public function getComplainStudentDetail($number,$class_id,$section_id,$session_id)
    {
        $this->db->select('S.firstname,S.lastname,S.mobileno,C.class,SE.section,M.created_at,M.updated_at,M.sms_status,M.type,M.id');
        $this->db->from('messages AS M');
        $this->db->join('student_session AS SS','SS.session_id = M.session_id');
        $this->db->join('students AS S','S.id = SS.student_id');
        $this->db->join('classes AS C','C.id = M.class_id');
        $this->db->join('sections AS SE','SE.id = M.section_id');
        $this->db->where('M.class_id',$class_id);
        $this->db->where('M.section_id',$section_id);
        $this->db->where('M.session_id',$session_id);
        $this->db->where('S.mobileno',$number);
        // $this->db->where('SS.s')
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    public function messageDetails($number)
    {
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->like('user_list',$number);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    public function checkNumberModel($number)
    {
        $this->db->select('*');
        $this->db->from('student_all_numbers');
        $this->db->like('numbers',$number);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    public function messageDetailByNum($number)
    {
        $this->db->select('*');
        $this->db->from('student_all_numbers');
        $this->db->like('numbers',$number);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    public function getMessageData($getnumber)
    {
        $this->db->select('message');
        $this->db->from('messages');
        $this->db->like('user_list',$getnumber);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getAllMessageReoprt($type,$session_id,$class_id,$section_id)
    {
       $this->db->select('M.message,M.updated_at,M.sms_status');
       $this->db->from('messages AS M');
       $this->db->where('M.class_id',$class_id);
       $this->db->where('M.section_id',$section_id);
       $this->db->where('M.session_id',$session_id);
       $this->db->where('M.type',$type);
       $query = $this->db->get();
       // echo $this->db->last_query();
       return $query->result();
       // 

    }

    public function getAllMessageReoprt2($number,$type,$session_id,$class_id,$section_id)
    {
       $this->db->select('S.firstname,M.message,M.updated_at,M.sms_status');
       $this->db->from('messages AS M');
       $this->db->join('student_session AS SS','SS.session_id = M.session_id');
       $this->db->join('students AS S','S.id = SS.student_id');
       $this->db->where('M.class_id',$class_id);
       $this->db->where('M.section_id',$section_id);
       $this->db->where('M.session_id',$session_id);
       $this->db->where('S.mobileno',$number);
       $this->db->where('M.type',$type);
       $query = $this->db->get();
       echo $this->db->last_query();
       return $query->result();
       // 

    }

    public function studentInfo($getnumber)
    {
        $this->db->select('firstname,lastname');
        $this->db->from('students');
        $this->db->like('mobileno',$getnumber);
        $this->db->or_like('guardian_phone',$getnumber);
        $query = $this->db->get();
        return $query->result();
    }
  public function checkNumberDetail($number)
    {
        $this->db->select('S.firstname,S.lastname,S.mobileno,S.guardian_phone ,SS.session_id,SS.class_id,SS.section_id');
        $this->db->from('students AS S');
        $this->db->join('student_session AS SS','S.id = SS.student_id');
        $this->db->like('S.mobileno',$number);
        $this->db->or_like('S.guardian_phone',$number);
        $this->db->limit(1,0);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getAllmessage($mobileno)
    {
        $this->db->select('message,created_at,updated_at');
        $this->db->from('messages');
        $this->db->like('user_list',$mobileno);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getAllmessagenumber($session_id,$section_id,$class_id)
    {
        $this->db->select('message,created_at,updated_at');
        $this->db->from('messages');
        $this->db->where('class_id',$class_id);
        $this->db->where('section_id',$section_id);
        $this->db->where('session_id',$session_id);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getVehicle()
    {
        $this->db->select('id,vehicle_no');
        $this->db->from('vehicles');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function searchList($id)
    {
        $this->db->select('S.id,S.firstname,S.lastname,S.guardian_phone,S.father_name,S.admission_no,C.class,SE.section')->from('student_session AS SS');
        $this->db->join('students AS S','S.id = SS.student_id');
        $this->db->join('classes AS C','C.id = SS.class_id');
        $this->db->join('sections AS SE','SE.id = SS.section_id');
        $this->db->where('SS.vehicle_id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }


     public function parent_login($number,$password){
        $this->db->select('id');
        $this->db->from('students'); 
        $this->db->where("(guardian_phone=$number or mobileno=$number or father_phone=$number or mother_phone=$number)");
        $this->db->where('password',$password);
        $this->db->where('is_active','yes');
       // $this->db->from('student_all_numbers'); 
       // $this->db->where("numbers LIKE '%$number%' ");
             $qry =   $this->db->get(); 
            return  $qry->row(); 
    }
    
    
     public function parent_password($password){
        $this->db->select('id');
        $this->db->from('students'); 
        $this->db->where('password',$password);
        $qry =   $this->db->get(); 
        return  $qry->row(); 
    }
      public function get_homework($class,$section){
             $this->db->select('description as description, submit_date as sent_date');
             $this->db->from('homework'); 
             $this->db->where('class_id',$class);
             $this->db->where('section_id',$section);
              $this->db->order_by("id", "desc");
              $qry =   $this->db->get()->result_array(); 
              return $qry; 

     }

     public function get_class($id){
          $this->db->select('*');
          $this->db->from('student_all_numbers'); 
          $this->db->where("id", $id);
          $qry =   $this->db->get(); 
          return  $qry->row();

     }
        public function get_class_by_number($id){
          $this->db->select('*');
          $this->db->from('student_session'); 
          $this->db->where("student_id", $id);          
          $this->db->where("session_id", $this->current_session);          
          $qry = $this->db->get(); 
          return  $qry->row();

     }
     
     
     
     public function add_section($data){
        $this->db->insert('sections',$data);
        $id = $this->db->insert_id();
        return $id ;
     }
	 
	 public function update_student($data){
	 if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('students', $data);
	return '1' ;
           
        }
	 
	 
	 }



  public function get_student($id){

$this->db->select('*')->from('student_session');
$this->db->where('student_id',$id);
$this->db->where('session_id',$this->current_session);

$query = $this->db->get();
    return $query->row();
} 
  
public function get_attendancestudent($id,$month,$year){

$this->db->select('*,DAY(date) as daeee,attendence_type.type')->from('student_attendences');
$this->db->join('attendence_type','attendence_type.id=student_attendences.attendence_type_id');

$this->db->where('student_session_id',$id);
$this->db->where("YEAR(date) = $year AND MONTH(date) = $month");

//$this->db->where('session_id',$this->current_session);

$query = $this->db->get();
    return $query->result();
} 
  

 }
?>