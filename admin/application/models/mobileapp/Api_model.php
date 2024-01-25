<?php

class Api_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->model('Student_model');
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    } 


public function software_login($value='')
{
    //CONCAT('http://gyanganga.ac.in/admin/uploads/staff_images/',staff.image) as image
          $base = base_url();
        $this->db->select("staff.*,CONCAT('$base',staff.image) as image,roles.name as user_type,roles.id as role_id")->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left");

            
            $this->db->where('staff.is_active', 1); 
            $this->db->where('staff.email',$value['username']);
           // $this->db->where('staff.password',$value['password']);

            $this->db->where('roles.name',$value['roles']);
                
             $query = $this->db->get();
        //echo $this->db->last_query();die;      
            $record =  $query->row_array();
            if (!empty($record)) {
                 $pass_verify = $this->enc_lib->passHashDyc($value['password'], $record['password']);
            if ($pass_verify) {
               return  $record  ;
            }else{
                  return false;
            }
            }else{
                  return false;
            }
           
     
    }

  public function addDailyActivities($value)
  {
    $this->db->insert('dailyactivities',$value);
    return $this->db->insert_id();
  }
    public function get_Gatepass_list($offset=0)
    {
        // $this->db->select('*');
        // $this->db->select(CONCAT('http://softwares.centralacademyjabalpur.com','/','guardian_image'));
        // $this->db->from('receive');        
        // $this->db->limit(30,$offset);
        // return $this->db->get()->result_array();
        $base = base_url();
        $qry =  "SELECT *,CONCAT('$base',guardian_image) as g_image ,CONCAT('$base',student_image) as s_image from  receive order by id desc" ; 
            return  $this->db->query($qry)->result_array();

    }
    
    public function get_Gatepass_by_id($id)
    {        
        $base = base_url();
        $qry =  "SELECT *,CONCAT('$base',guardian_image) as g_image ,CONCAT('http://softwares.centralacademyjabalpur.com','/',student_image) as s_image from  receive where id = $id limit 1 " ; 
            return  $this->db->query($qry)->row_array();

    }
   
   

    public function get_student_gp($rollno)
    {
         $qry =  "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id WHERE students.admission_no = '$rollno' " ; 
            return  $this->db->query($qry)->row_array();
    
    }

     public function add_gatepass($adddata)
    {
        $this->db->insert('receive',$adddata);
        return $this->db->insert_id();

    }

     public function delete_gatepass($id = 0) 
    {
        $this->db->delete('receive',array('id' =>$id));
        return 1; 
    }


public function get_search_result($searchterm = ''){
  $base = base_url();
           $this->db->select("id,parent_id,admission_no,roll_no,email,state,city,pincode,religion,cast,dob,
            admission_date,firstname,lastname,mobileno,gender,current_address,father_name,mother_name,guardian_name,
           CONCAT('$base',image) as image");
          //  $this->db->select('*');
            $this->db->from('students');
            $this->db->where('students.admission_no', $searchterm);
           // $this->db->like('students.firstname', $searchterm);
            //$this->db->or_like('students.lastname', $searchterm);
            
          //  $this->db->or_like('students.email', $searchterm);
            $query = $this->db->get();
            return $query->result_array();     
}
/*

public function get_all_student()
{
           //  $qry =   "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
             $qry =   "SELECT students.roll_no,students.admission_no,students.firstname,students.lastname,students.mobileno,students.gender,students.father_name, classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
            return  $this->db->query($qry)->result_array(); 
}
   
*/
public function get_all_student()
{
           //  $qry =   "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
             $qry =   "SELECT  CONCAT(students.firstname,' ',students.lastname) as sname, CONCAT(students.firstname,' ',students.lastname,'-',students.admission_no) as namer,CONCAT(students.admission_no,'-',students.firstname,' ',students.lastname) as rname,students.roll_no,students.admission_no,students.firstname,students.lastname,students.mobileno,students.gender,students.father_name, classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
            return  $this->db->query($qry)->result_array(); 
}    
    /*admission software*/ 
 public function get_admission_reciept($value='')
 {
     $qry = "SELECT * from admission_soft order by id desc limit 1"; 
       return $this->db->query($qry)->row_array();
 }  

 public function get_interview_detail($value='')
 {
     $qry = "SELECT * from interview_date order by id asc limit 1 "; 
       return $this->db->query($qry)->row_array();
 } 
 
public function get_interview_next_detail($date)
 {
     $qry = "SELECT * from interview_date where int_date > '$date' order by id desc limit 1 "; 
       return $this->db->query($qry)->row_array();
 } 

  public function checkcount($date)
 {
     $qry = "SELECT count from interview_date where int_date = '$date'  "; 
     return $this->db->query($qry)->row_array();
 } 

public function get($id)
 {
     $qry = "SELECT * from admission_soft where id = $id limit 1"; 
     return $this->db->query($qry)->row_array();
 }   


  public function get_class_list()
  {
    $this->db->select('class')->from('classes');    
    $this->db->order_by('id');
    $query = $this->db->get();
    return $classlist = $query->result_array();
   }   
 


    public function fee_deposit($data, $student_fees_discount_id) {
        $this->db->where('student_fees_master_id', $data['student_fees_master_id']);
        $this->db->where('fee_groups_feetype_id', $data['fee_groups_feetype_id']);
        $q = $this->db->get('student_fees_deposite');
        if ($q->num_rows() > 0) {
            $desc = $data['amount_detail']['description'];
            $this->db->trans_start(); // Query will be rolled back
            $row = $q->row();
            $this->db->where('id', $row->id);
            $a = json_decode($row->amount_detail, true);
            $inv_no = max(array_keys($a)) + 1;
            $data['amount_detail']['inv_no'] = $inv_no;
            $a[$inv_no] = $data['amount_detail'];
            $data['amount_detail'] = json_encode($a);
            $this->db->update('student_fees_deposite', $data);

            if ($student_fees_discount_id != "") {
                $this->db->where('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $row->id . "/" . $inv_no));
            }


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                return FALSE;
            } else {
                $this->db->trans_commit();
                return json_encode(array('invoice_id' => $row->id, 'sub_invoice_id' => $inv_no));
            }
        } else {

            $this->db->trans_start(); // Query will be rolled back
            $data['amount_detail']['inv_no'] = 1;
            $desc = $data['amount_detail']['description'];
            $data['amount_detail'] = json_encode(array('1' => $data['amount_detail']));
            $this->db->insert('student_fees_deposite', $data);
            $inserted_id = $this->db->insert_id();
            if ($student_fees_discount_id != "") {
                $this->db->where('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $inserted_id . "/" . "1"));
            }

            $this->db->trans_complete(); # Completing transaction

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return json_encode(array('invoice_id' => $inserted_id, 'sub_invoice_id' => 1));
            }
        }
    }

 
   public function getstud($id = null) {
      $base = base_url();
    $this->db->select("student_session.transport_fees,student_session.vehroute_id,student_session.route_id,student_session.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
        students.guardian_is,students.parent_id,CONCAT('$base',students.student_image) as s_image,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email')->from('students');
    $this->db->join('student_session', 'student_session.student_id = students.id");
    $this->db->join('classes', 'student_session.class_id = classes.id');
    $this->db->join('sections', 'sections.id = student_session.section_id');
    $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
    $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
    $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
    $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
    $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
    $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
    $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
    $this->db->where('student_session.session_id', $this->current_session);

    if ($id != null) {
        $this->db->where('students.id', $id);
    } else {
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('students.id', 'desc');
    }
    $query = $this->db->get();
    if ($id != null) {
        return $query->row_array();
    } else {
        return $query->result_array();
    }
}

//////////////////////android appli model functions/////////////////////////


public function app_login($value='')
    {
        $base= base_url();
        $this->db->select("staff.*,staff_designation.designation as s_designation,CONCAT('Employee ID : ',staff.employee_id) as designation, CONCAT('$base' ,staff.image) as image,roles.name as user_type,roles.id as role_id")
        ->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")
        ->join("staff_designation", "staff_designation.id = staff.designation", "left")
        ->join("roles", "staff_roles.role_id = roles.id", "left");            
            $this->db->where('staff.is_active', 1); 
            $this->db->where('staff.contact_no',$value['moblie']);
            $this->db->where('staff.pass_code',$value['password']);
            //$this->db->where('roles.name',$value['roles']);                
             $query = $this->db->get();
        //echo $this->db->last_query();die;      
            $record =  $query->row_array();
            if (!empty($record)) {
              return  $record  ;
               //  $pass_verify = $this->enc_lib->passHashDyc($value['password'], $record['password']);
           /* if ($pass_verify) {
               return  $record  ;
            }else{
                  return false;
            }*/
            }else{
                  return false;
            }          
     
    }
    
    
    public function get_class_section_list($staff)
  {
    $this->db->select('class_teacher.*,classes.class,sections.section')->from('class_teacher');
     $this->db->join('classes', 'class_teacher.class_id = classes.id');
    $this->db->join('sections', 'class_teacher.section_id = sections.id');
    $this->db->where('class_teacher.staff_id', $staff);
    $this->db->order_by('id');
    $query = $this->db->get();
    return $classlist = $query->result_array();
   }  
   
   
     public function get_class_section_list_new($staff)
  {
    $this->db->select('teacher_timetable.*,classes.class,sections.section')->from('teacher_timetable');
     $this->db->join('classes', 'teacher_timetable.class_id = classes.id','left');
    $this->db->join('sections', 'teacher_timetable.section_id = sections.id','left');
    $this->db->where('teacher_timetable.teacher_id', $staff);
    $this->db->where('teacher_timetable.class_id !=', 0);
    $this->db->where('teacher_timetable.section_id !=', 0);
	  $this->db->group_by('teacher_timetable.class_id, teacher_timetable.section_id');
    $this->db->order_by('id');
    $query = $this->db->get();
    return $classlist = $query->result_array();
   }  
   
   
     public function searchAttendenceClassSectionPrepare_api($class_id, $section_id, $date) {
        $base = base_url() ;
        $sql = "select student_sessions.attendence_id,students.firstname,CONCAT('$base', students.image) as image,student_sessions.date,student_sessions.remark,
        students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id,
         attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,
         IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,
          IFNULL(student_attendences.attendence_type_id,1) as attendence_type_id FROM `student_session` 
          LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  
          and student_attendences.date=" .$this->db->escape($date). " 
          where student_session.session_id=" . $this->db->escape($this->current_session) . " 
          and student_session.class_id=" . $this->db->escape($class_id) . " 
          and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions 
          LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id
           where student_sessions.student_id = students.id and students.is_active = 'yes' group by students.id order by students.firstname, students.lastname asc";


        $query = $this->db->query($sql);
        return $query->result();
    }
   
    public function searchAttendenceClassSectionPrepare($class_id, $section_id, $date) {
        
        $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' group by students.id";


        $query = $this->db->query($sql);
        return $query->result();
    }

    public function searchByClassSectionCategoryGenderRte($class_id = null, $section_id = null)
     {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id, categories.category,   students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }
   // CMS API FUNCTION  
    public function get_class_list_cms()
  {
    $this->db->select('id,class')->from('classes');    
    $this->db->order_by('id');
    $query = $this->db->get();
    return $classlist = $query->result_array();
   }   
 
  public function add_front_registration($data){
  
   $this->db->insert('front_registration',$data);
        return $this->db->insert_id();
  
  } 

   public function add_camp_registration($data){
  
   $this->db->insert('summer_camp',$data);
        return $this->db->insert_id();
  
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
  




function getProfile($id) {
        $base = base_url() ;
        $this->db->select("staff.*,CONCAT('$base',staff.image) as image,staff_designation.designation as designation,staff_roles.role_id, department.department_name as department,roles.name as user_type");
        $this->db->join("staff_designation", "staff_designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");

        $this->db->where("staff.id", $id);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function addclassroom($data)
    {
         $this->db->insert('classroomStudents',$data);
         return $this->db->insert_id();
    }


// end class
}

?>