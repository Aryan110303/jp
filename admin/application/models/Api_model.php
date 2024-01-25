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
        $this->db->select('staff.*,roles.name as user_type,roles.id as role_id')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left");            
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
   

    public function get_Gatepass_list($offset=0,$session_id)
    {
        
      $qry =  "SELECT *,CONCAT('http://softwares.centralacademyjabalpur.com','/',guardian_image) as g_image ,CONCAT('http://softwares.centralacademyjabalpur.com','/',student_image) as s_image from receive where session_id = $session_id order by id desc" ; 
            return  $this->db->query($qry)->result_array();

    }
    
    public function get_Gatepass_by_id($id)
    {        
        $qry =  "SELECT *,CONCAT('http://softwares.centralacademyjabalpur.com','/',guardian_image) as g_image ,CONCAT('http://softwares.centralacademyjabalpur.com','/',student_image) as s_image from  receive where id = $id limit 1 " ; 
            return  $this->db->query($qry)->row_array();

    }
   
    public function update_student($data , $id)
    {
        $qry = "SELECT * from students where id  = $id";
       $return =  $this->db->query($qry)->row_array();   
     if ($return) {
       $this->db->where('id',$id);
       $this->db->update('students',$data);      
      return  1 ; 
     }
     else
      { 
        return 0 ;
     }
  }

    public function update_teacher($data , $id)
    {
         $qry = "SELECT * from staff where id  = $id";
       $return =  $this->db->query($qry)->row_array();  
        if ($return) {
         $this->db->where('id',$id);
         $this->db->update('staff',$data);
      
      return  1 ; 
     }
     else
      { 
        return 0 ;
     }
  }


    public function get_student_gp($rollno,$student_session_id)
    {
         $qry =  "SELECT students.*,CONCAT('http://softwares.centralacademyjabalpur.com/',students.image) as image,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id WHERE students.admission_no = '$rollno' and student_session.session_id = '".$student_session_id."' " ; 
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

   public function get_house()
   {
      $qry = "SELECT * from school_houses ";
      return $this->db->query($qry)->result();  
   }

   
public function get_search_result($searchterm = '' , $session_id){
       
       
        $qry =   "SELECT  CONCAT(students.firstname,' ',students.lastname) as sname, CONCAT(students.firstname,' ',students.lastname,'-',students.admission_no) as namer,CONCAT(students.admission_no,'-',students.firstname,' ',students.lastname) as rname,students.roll_no,students.admission_no,students.firstname,students.lastname,students.permanent_address as address,students.mobileno,students.id,students.dob,students.gender,students.father_name , CONCAT('http://softwares.centralacademyjabalpur.com/',students.image) as image, classes.id AS `class_id`,classes.class AS class ,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section as section ,students.mother_name,school_houses.house_name as house,students.school_house_id as house_name  FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id left join school_houses on students.school_house_id = school_houses.id where students.admission_no = '$searchterm'  and student_session.session_id = $session_id "; 
              
            return  $this->db->query($qry)->result_array();    
}

public function get_search_result2($searchterm = '' , $session_id){
       
       
        $qry =   "SELECT  CONCAT(students.firstname,' ',students.lastname) as sname, CONCAT(students.firstname,' ',students.lastname,'-',students.admission_no) as namer,CONCAT(students.admission_no,'-',students.firstname,' ',students.lastname) as rname,students.roll_no,students.admission_no,students.firstname,students.lastname,students.permanent_address as address,students.mobileno,students.id,students.dob,students.gender,students.father_name , CONCAT('http://softwares.centralacademyjabalpur.com/',students.image) as image, classes.id AS `class_id`,classes.class AS class ,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section as section ,students.mother_name,school_houses.house_name as house,students.school_house_id as house_name  FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id left join school_houses on students.school_house_id = school_houses.id where students.admission_no = $searchterm  and student_session.session_id = $session_id "; 
              
            return  $this->db->query($qry)->result();    
}
/*

public function get_all_student()
{
           //  $qry =   "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
             $qry =   "SELECT students.roll_no,students.admission_no,students.firstname,students.lastname,students.mobileno,students.gender,students.father_name, classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
            return  $this->db->query($qry)->result_array(); 
}
   
*/
public function get_all_student($session_id)
{
           //  $qry =   "SELECT students.*,classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id left join sections ON sections.id=student_session.section_id "; 
            
             $qry =   "SELECT  CONCAT(students.firstname,' ',students.lastname) as sname, CONCAT(students.firstname,' ',students.lastname,'-',students.admission_no) as namer,CONCAT(students.admission_no,'-',students.firstname,' ',students.lastname) as rname,students.roll_no,students.school_house_id,students.admission_no,students.firstname,students.lastname,students.id,students.mobileno,students.gender,classes.class as bass,student_session.promoted,students.father_name,school_houses.house_name, classes.id AS `class_id`,classes.class AS class ,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section as section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id  left join school_houses on students.school_house_id=school_houses.id left join sections ON sections.id=student_session.section_id where student_session.session_id = $session_id " ; 
                        
                   return  $this->db->query($qry)->result_array(); 
}    
    /*admission software*/ 

    public function getadmissiondailyreport($date)
    {
       $qry = "SELECT student_admission_fees.*,admission_soft.fname,admission_soft.lname,admission_soft.father_name,admission_soft.mother_name, admission_soft.phone, classes.class,sessions.session from student_admission_fees 
        join admission_soft on admission_soft.id = student_admission_fees.registration_id 
        join sessions on sessions.id = admission_soft.session_id 
        join classes on classes.id = admission_soft.class where student_admission_fees.date = '$date' order by id desc "; 
        return $this->db->query($qry)->result();
    }

 public function get_admission_reciept($value='')
 {
       $qry = "SELECT * from student_admission_fees order by id desc limit 1"; 
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
   
 /*  public function feeereceipt_no()
   {
     $this->db->select('receipt_no');
     $this->db->from('student_fees_deposite');
     $this->db->order_by('receipt_no','Desc');
     return $this->db->get()->row()->receipt_no+1;
   }
*/
public function feeereceipt_no()
   {
     $this->db->select('receipt_no');
     $this->db->from('student_fees_deposite');
     $this->db->order_by('receipt_no','Desc');
     $rec_no = $this->db->get()->row()->receipt_no ; 
    return $this->checkrevert($rec_no+1);     

   }

   public function checkrevert($value)
   {       
      if ($this->db->get_where('studentRevert_fee',array('receipt_no' => $value))->num_rows()) {
         $value += 1; 
         return $this->checkrevert($value);
      }else{
        return $value ;
      }
   }


   public function fee_deposit($data, $student_fees_discount_id) {       

    $this->db->trans_start(); // Query will be rolled back
    $data['amount_detail']['inv_no'] = 1;
    $desc = $data['amount_detail']['description'];
    $data['amount_detail'] = json_encode(array('1' => $data['amount_detail']));
    $this->db->insert('student_fees_deposite', $data);
    $inserted_id = $this->db->insert_id();           
    $this->db->trans_complete(); # Completing transaction

    if ($this->db->trans_status() === FALSE) {

        $this->db->trans_rollback();
        return FALSE;
    } else {
        $this->db->trans_commit();
        return json_encode(array('invoice_id' => $inserted_id, 'sub_invoice_id' => 1));
    }

}

    public function fee_deposit_OLD($data, $student_fees_discount_id) {
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
            $data['receipt_no'] = $data['receipt_no'];
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
    $this->db->select("student_session.transport_fees,student_session.vehroute_id,student_session.route_id,student_session.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
        students.guardian_is,students.parent_id,CONCAT('http://softwares.centralacademyjabalpur.com','/',students.student_image) as s_image,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email')->from('students');
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
        $this->db->select('staff.*,roles.name as user_type,roles.id as role_id')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left");            
            $this->db->where('staff.is_active', 1); 
            $this->db->where('staff.contact_no',$value['moblie']);
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


   public function allteachers()
   {    
     $this->db->select('*,id as staff_id')->from('staff');
     $this->db->order_by('id');
     $query = $this->db->get();
     return $query->result_array();     
   }

     public function teacher_by_id($id)
     {
     
        $sql = "SELECT *, CONCAT('http://softwares.centralacademyjabalpur.com/',image ) as image from staff where id = $id";
   
        $query = $this->db->query($sql);
        return $query->result();
     }
   

   
    public function searchAttendenceClassSectionPrepare($class_id, $section_id, $date) {
        $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function checkpreviousBal($student_id,$session_id,$amount)
    {
       $data = $this->db->query(" SELECT student_session.id from student_session 
               where student_session.student_id = '$student_id' 
               and student_session.session_id = '$session_id'+1 ")->row();
       $student_session_id = $data->id;
       if($student_session_id){
         $updatedata['amount'] = "0.00";
         $this->db->where("student_session_id",$student_session_id);
         $this->db->where("is_system",1);
         $this->db->where("amount >=",$amount);
         $this->db->update("student_fees_master",$updatedata);
         return 1;
       }else{
         return 0;
       }
      
    }

public function get_fee_session_groups_idByclass_id($class_id=0,$session_id=0)
{
  $this->db->SELECT('fee_session_groups.id');
  $this->db->from(' fee_session_groups');
  $this->db->join('fee_groups','fee_groups.id=fee_session_groups.fee_groups_id');
  $this->db->where('fee_groups.class_id', $class_id);
  $this->db->where('fee_session_groups.session_id', $session_id);
  $query = $this->db->get();
  return $result = $query->row();
}

public function get_fees_data($value=0)
{
    $this->db->SELECT('student_fees_deposite.*');
     $this->db->from(' student_fees_deposite');
    $this->db->where('student_fees_deposite.id', $value);
    $query = $this->db->get();
    return $result = $query->row();
}


public function fees_result($val=0)
{
 // echo $val;
  $this->db->SELECT('fee_groups_feetype.*,fee_groups.name as group,feetype.type as month');
  $this->db->from(' fee_groups_feetype');
  $this->db->join('fee_groups','fee_groups.id=fee_groups_feetype.fee_groups_id');
  $this->db->join('feetype','feetype.id=fee_groups_feetype.feetype_id');
  $this->db->where('fee_groups_feetype.id', $val);
   $query = $this->db->get();
                   
 return $result = $query->row();
}

public function fees_result_admissioin($val=0)
{
 // echo $val;
  $this->db->SELECT('fee_groups_feetype.fee_groups_id ,fee_groups_feetype.feetype_id,fee_groups_feetype.id,fee_groups_feetype.fee_session_group_id,fee_groups_feetype.amount_II as amount,fee_bydefault,fee_groups.name as group,feetype.type as month');
  $this->db->from(' fee_groups_feetype');
  $this->db->join('fee_groups','fee_groups.id=fee_groups_feetype.fee_groups_id');
  $this->db->join('feetype','feetype.id=fee_groups_feetype.feetype_id');
  $this->db->where('fee_groups_feetype.id', $val);
   $query = $this->db->get();
                   
 return $result = $query->row();
}





//--------------transport fee --------------------//


public function get_depositetransport_fee($student_session_id,$session_id)
{
  $data = $this->db->query("SELECT * from student_transport_fee_deposite where student_session_id = $student_session_id and session_id = $session_id")->result();
  if (!empty($data)) {
      return $data;
  }else return 0 ;
}



  
   public function transportFeeereceipt_no()
   {
     $this->db->select('receipt_no');
     $this->db->from('student_transport_fee_deposite');
     $this->db->order_by('receipt_no','Desc');
     return $this->db->get()->row()->receipt_no+1;
   }

   public function transportfee_deposit($data) {       

    $this->db->trans_start(); // Query will be rolled back
    $this->db->insert('student_transport_fee_deposite', $data);
    $inserted_id = $this->db->insert_id();           
    $this->db->trans_complete(); # Completing transaction
    if ($this->db->trans_status() === FALSE) {

        $this->db->trans_rollback();
        return FALSE;
    } else {
        $this->db->trans_commit();
        return $this->db->inserted_id();
    }

  }


 public function get_student_ByclassSection($class, $section, $session)
 {
  $base  =base_url();
    $this->db->select("student_session.transport_fees,student_session.vehroute_id,student_session.route_id,student_session.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
        students.guardian_is,students.parent_id,CONCAT('$base',students.image) as s_image,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email")->from('students');


    $this->db->join("student_session "," student_session.student_id = students.id");
    $this->db->join('classes', 'student_session.class_id = classes.id');
    $this->db->join('sections', 'sections.id = student_session.section_id');
    $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
    $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
    $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
    $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
    $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
    $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
    $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
    $this->db->where('student_session.session_id', $session);
    $this->db->where('student_session.class_id', $class);
    $this->db->where('student_session.section_id', $section);
    $this->db->where('students.is_active', 'yes');
    $this->db->order_by('students.firstname','asc');
    $query = $this->db->get();
    return $query->result_array();

 }


 public function get_student_Byclass($class, $session)
 {
    $this->db->select("student_session.transport_fees,student_session.vehroute_id,student_session.route_id,student_session.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
        students.guardian_is,students.parent_id,CONCAT('http://softwares.centralacademyjabalpur.com','/',students.image) as s_image,students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email")->from('students');


    $this->db->join("student_session "," student_session.student_id = students.id");
    $this->db->join('classes', 'student_session.class_id = classes.id');
    $this->db->join('sections', 'sections.id = student_session.section_id');
    $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
    $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
    $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
    $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
    $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
    $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
    $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
    $this->db->where('student_session.session_id', $session);
    $this->db->where('student_session.class_id', $class);
    $this->db->where('students.is_active', 'yes');
    $this->db->order_by('students.firstname','asc');
    $query = $this->db->get();
    return $query->result_array();

 }
//----------------------------------//

// end class
}

?>