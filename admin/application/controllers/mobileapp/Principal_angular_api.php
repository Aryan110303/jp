<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');

 class Principal_angular_api extends Public_Controller
 {
    
     public function __construct() {
        parent::__construct();
        $this->load->model("api/Homeapi_model","Api_model");
        $this->load->model("api/Api_Classteacher_model","Api_Classteacher_model");
        $this->load->model("api/Api_Messages_model","Api_Messages_model");
        $this->load->model('staff_model');
        $this->load->library('Enc_lib');
        $this->current_session = $this->setting_model->getCurrentSession();
    }
    


 
   
    function startmonthandend()
       {
        $startmonth = $this->setting_model->getStartMonth();
        if ($startmonth == 1) {
            $endmonth = 12;
        } else {
            $endmonth = $startmonth - 1;
        }
        return array($startmonth, $endmonth);
       }


    public function dashboardapi()
    {
        $data = array();
        if (!is_null($this->input->post('role'))) {
            $data['status'] = true;
            $roleId = $this->input->post('role');
            $getrole = $this->Api_model->getStaffRoleAPi($roleId);
            // print_r($getrole); exit();
            $role_id = $getrole->id;
            $staffid = $this->customlib->getStaffID();
            $notifications = $this->notification_model->getUnreadStaffNotification($staffid, $role_id);

            $data['notifications'] = $notifications;
            $input = $this->setting_model->getCurrentSessionName();

            list($a, $b) = explode('-', $input);
            $Current_year = $a;
            if (strlen($b) == 2) {
                $Next_year = substr($a, 0, 2) . $b;
            } else {
                $Next_year = $b;
            }

            //========================== Current Attendence ==========================
            $current_date = date('Y-m-d');
            $data['title'] = 'Dashboard';
            $data['fee_collection_month'] = date("F").' '.date("Y");
            $data['fee_collection_session'] = $this->setting_model->getCurrentSessionName();
            $Current_start_date = date('01');
            $Current_date = date('d');
            $Current_month = date('m');
            $month_collection = 0;
            $month_expense = 0;
            $total_students = 0;
            $total_teachers = 0;
            $ar = $this->startmonthandend();
            $year_str_month = $Current_year . '-' . $ar[0] . '-01';
            $year_end_month = date("Y-m-t", strtotime($Next_year . '-' . $ar[1] . '-01'));
            $getDepositeAmount = $this->studentfeemaster_model->getDepositAmountBetweenDate($year_str_month, $year_end_month);

            //======================Current Month Collection ==============================
            $first_day_this_month = date('Y-m-01');

            $month_collection = $this->whatever($getDepositeAmount, $first_day_this_month, $current_date);
            $expense = $this->expense_model->getTotalExpenseBwdate($first_day_this_month, $current_date);
            if (!empty($expense))
                $month_expense = $month_expense + $expense->amount;

            $data['month_collection'] = $month_collection;
            $data['month_expense'] = $month_expense;

            $tot_students = $this->studentsession_model->getTotalStudentBySession();
            if (!empty($tot_students))
                $total_students = $tot_students->total_student;
            $data['total_students'] = $total_students;

            $tot_roles = $this->role_model->get();
            foreach ($tot_roles as $key => $value) {
                if ($value["id"] != 1) {
                    $count_roles[$value["name"]] = $this->role_model->count_roles($value["id"]);
                    $data[$value["name"]] = $count_roles[$value["name"]];
                }
            }
            // $data["roles"] = $count_roles;

            //======================== get collection by month ==========================
            $start_month = strtotime($year_str_month);
            $start = strtotime($year_str_month);
            $end = strtotime($year_end_month);
            $coll_month = array();
            $s = array();
            $total_month = array();
            while ($start_month <= $end) {
                $total_month[] = date('M', $start_month);
                $month_start = date('Y-m-d', $start_month);
                $month_end = date("Y-m-t", $start_month);
                $return = $this->whatever($getDepositeAmount, $month_start, $month_end);
                if ($return) {
                    $s[] = $return;
                } else {
                    $s[] = "0.00";
                }

                $start_month = strtotime("+1 month", $start_month);
            }
            //======================== getexpense by month ==============================
            $ex = array();
            $start_session_month = strtotime($year_str_month);
            while ($start_session_month <= $end) {


                $month_start = date('Y-m-d', $start_session_month);
                $month_end = date("Y-m-t", $start_session_month);

                $expense_monthly = $this->expense_model->getTotalExpenseBwdate($month_start, $month_end);

                if (!empty($expense_monthly)) {
                    $amt = 0;
                    $ex[] = $amt + $expense_monthly->amount;
                }

                $start_session_month = strtotime("+1 month", $start_session_month);
                
            }
            
            for ($i=0; $i < count($s); $i++) { 
                $recordArray = array('Paid' => $s[$i],'unpaid' => $ex[$i],'month' => $total_month[$i]);
                $data['month_records'][] = $recordArray;
            }
            // $data['yearly_collection'] = $s;
            // $data['yearly_expense'] = $ex;
            // $data['total_month'] = $total_month;
            //======================= current month collection /expense ===================
            // hardcoded '01' for first day
            $startdate = date('m/01/Y');
            $enddate = date('m/t/Y');
            $start = strtotime($startdate);
            $end = strtotime($enddate);
            $currentdate = $start;
            $month_days = array();
            $days_collection = array();
            while ($currentdate <= $end) {
                $cur_date = date('Y-m-d', $currentdate);
                $month_days[] = date('d', $currentdate);
                $coll_amt = $this->whatever($getDepositeAmount, $cur_date, $cur_date);
                $days_collection[] = $coll_amt;
                $currentdate = strtotime('+1 day', $currentdate);
            }
            
            // $data['current_month_days'] = $month_days;
            // $data['days_collection'] = $days_collection;
            //======================= current month /expense ==============================
            // hardcoded '01' for first day
            $startdate = date('m/01/Y');
            $enddate = date('m/t/Y');
            $start = strtotime($startdate);
            $end = strtotime($enddate);
            $currentdate = $start;
            $days_expense = array();
            while ($currentdate <= $end) {
                $cur_date = date('Y-m-d', $currentdate);
                $month_days[] = date('d', $currentdate);
                $currentdate = strtotime('+1 day', $currentdate);
                $ct = $this->getExpensebyday($cur_date);
                $days_expense[] = $ct;
            }
            // for ($i=0; $i < count($month_days); $i++) { 
            //     $dayrecordArray = array('Paid' => $days_collection[$i],'unpaid' => $days_expense[$i],'month' => $month_days[$i]);
            //     $data['daye_records'][] = $dayrecordArray;
            // }
            // $data['days_expense'] = $days_expense;
            for ($i=0; $i < count($days_expense); $i++) { 
                $dayrecordArray = array('Paid' => $days_collection[$i],'unpaid' => $days_expense[$i],'day' => $month_days[$i]);
                $data['day_records'][] = $dayrecordArray;
            }
            $student_fee_history = $this->studentfee_model->getTodayStudentFees();
            $data['student_fee_history'] = $student_fee_history;

            $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
            // $data["event_colors"] = $event_colors;
            $userdata = $this->customlib->getUserData();
        }else{
            $data['status'] = false;
            $data['msg'] = "Connection failed";
            $roleId = '';
        }
        
        // $data["role"] = $userdata["user_type"];
        echo json_encode($data);
    }

    public function classListApi()
    {
        $data = array();
        $result = $this->Api_model->getClassList();
        // print_r($result);
        if (!empty($result)) {
            $data['status'] = true;
            $data['courseList'] = $result;
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }

        echo json_encode($data);
    }

    public function sectionListApi()
    {
        $data = array();
        if (!is_null($this->input->post('class'))) {
            $class = $this->input->post('class');
        }else{
            $class = '';
        }
        $result = $this->Api_model->getsectionList($class);
        // print_r($result);
        if (!empty($result)) {
            $data['status'] = true;
            $data['sectionList'] = $result;
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }

        echo json_encode($data);
    }

    public function studentsDetailsApi()
    {
        $data = array();
        if (!is_null($this->input->post('class'))) {
            $class = $this->input->post('class');
        }else{
            $class = '';
        }

        if (!is_null($this->input->post('section'))) {
            $section = $this->input->post('section');
        }else{
            $section = '';
        }

        if (!is_null($this->input->post('student_name'))) {
            $stName = $this->input->post('student_name');
        }else{
            $stName = '';
        }
        if ($class == null && $section == null && $stName == null) {
            # code...
        }else if($class == null && $section != null){
            $data['status'] = false;
            $data['msg'] = "Please select class first";
        }else
        $studentDetails = $this->Api_model->getStudentDetail($class,$section,$stName);
        if (!empty($studentDetails)) {
            $data['status'] = true;
            $data['studentDetails'] = $studentDetails;
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        echo json_encode($data);
    }
 
    public function get_reports_api()
    {
        $data = array();
        $result = $this->Api_model->get_all_messages();
        if (!empty($result)) {
            $data['status'] = true;
            $data["result"] = $result;
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        echo json_encode($data);
    }

    


    

    public function homework(){
          $data = array();
          $number = $this->input->post('number');
          $id = $this->input->post('id');
          $date = '';
          $date = $this->input->post('date');
          $a =array();
          $persons =array();
          
          if (!empty($number) && !empty($id)) 
          {  
             $date_seen=date('Y-m-d h:i:s');
			 $this->db->query("update students set app_seen_date='$date_seen' where id=$id");
			 
			  $class = $this->Api_model->get_class_by_number($id);
           
                if ($class!= $a) {  
                      //  $hw = $this->Api_model->get_homework($class->class_id,$class->section_id); 
                      if($date){
                           $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where user_list LIKE '%$number%' and title='Homework' and created_at > '$date' ORDER BY id  DESC"); 
                      }else{
                             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where user_list LIKE '%$number%' and title='Homework' ORDER BY id  DESC"); 
                                         
                      }                       
                        if ($hw) {
                           $i = 0 ;                          
                           foreach($hw as $home){ 
						   	   $persons['id'] = $home['id'] ;
	                     	  $persons['title'] = $home['title'];
                              $persons['description'] = $home['message'];
                              $persons['sent_date'] =date("d-m-Y", strtotime($home['created_at']));
                              $persons['mobile'] = $number ;
                              $name = $this->Api_Classteacher_model->get_data_by_query1("select firstname, lastname from students where id = '$id'") ;
							  $persons['student_id'] =$id;
                              $persons['student_name'] = $name['firstname'].' '.$name['lastname'];
                              $persons['class'] = $this->Api_Classteacher_model->findfield('classes','id',$class->class_id,'class') ;
                              $persons['section'] =$this->Api_Classteacher_model->findfield('sections','id',$class->section_id,'section') ;  
                              $data['records'][] = $persons;
                            } 
                 
                
                             $data['msg'] = "success result found";  
                             $data['status'] = "true"; 
                 
                      }else{
                        $data['status'] = false;
                        $data['msg'] = "No Result Found"; 
                    }

                    }     
                    else{
                        $data['status'] = false;
                        $data['msg'] = "Number not matched"; 
                    }
              }               
         else{
            $data['status'] = false;
            $data['msg'] = "Number not found";         
           }

           echo json_encode($data); 
     }
     
     
    public function S_notice(){
          $data = array();
          $number = $this->input->post('number');
          $id = $this->input->post('id');
          $date = '';
          $date = $this->input->post('date');
          $a =array();
          $persons =array();
          
          if (!empty($number) && !empty($id)) 
          {  
              $class = $this->Api_model->get_class_by_number($id);
              //print_r($class);
           
                if ($class!= $a) {  
                    $class_id=$class->class_id;
                    $section_id=$class->section_id;
                      //  $hw = $this->Api_model->get_homework($class->class_id,$class->section_id); 
                      if($date){
                           $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where user_list LIKE '%$number%' and send_sms='Complain' and section_id=$section_id  and class_id=$class_id    and created_at > '$date' ORDER BY id  DESC"); 
                      }else{
                             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where user_list LIKE '%$number%' and send_sms='Complain' and section_id=$section_id  and class_id=$class_id  ORDER BY id  DESC"); 
                                         
                      }  
                      //echo $this->db->last_query();

                        if ($hw) {
                           $i = 0 ;                          
                           foreach($hw as $home){ 
						   	  $persons['id'] = $home['id'] ;
                              $persons['title'] = $home['title'] ;
                              $persons['description'] = $home['message'] ;
                              $persons['sent_date'] =date("d-m-Y", strtotime($home['created_at']));
                              $persons['mobile'] = $number ;
                              $name = $this->Api_Classteacher_model->get_data_by_query1("select firstname, lastname from students where id = '$id'") ;
							  $persons['student_id'] =$id;
                              $persons['student_name'] = $name['firstname'].' '.$name['lastname'];
                              $persons['class'] = $this->Api_Classteacher_model->findfield('classes','id',$class->class_id,'class') ;
                              $persons['section'] =$this->Api_Classteacher_model->findfield('sections','id',$class->section_id,'section') ;  
                              $data['records'][] = $persons;
                            } 
                 
                
                             $data['msg'] = "success result found";  
                             $data['status'] = "true"; 
                 
                      }else{
                            $data['msg'] = "No result found";  
                             $data['status'] = false;

                      }

                    }     
                    else{
                        $data['status'] = false;
                        $data['msg'] = "Number not matched"; 
                    }
              }               
         else{
            $data['status'] = false;
            $data['msg'] = "Number not found";         
           }

           echo json_encode($data); 
     }
     
     
     
     

      

  public function events(){
    $today = date('Y-m-d');

    $today .= " 00:00:00" ;
           $date = $this->input->post('date');
         $events = $this->Api_Classteacher_model->get_data_by_query("SELECT *, start_date as startdate ,date_format(start_date  , '%d/%m/%Y') as start_date , date_format(end_date  , '%d/%m/%Y') as end_date  FROM events where start_date  >=  '$today' and created_at > '$date' order by startdate asc ");         
           if (!empty($events)) {
                      $data['records'] = $events;                       
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
             $data['records'] = " ";                       
                      $data['msg'] = "No result found";  
                      $data['status'] = "false";
           }
          
            echo json_encode($data); 

      }  

      
      
     public function Teacher_list(){// api for all teacher list with their description 
        $data = array();
               $details = $this->Api_Classteacher_model->get_data_by_query("SELECT * from staff where designation > 6   ") ; 
        foreach ($details as $key => $value) {
           $details[$key]['designation']  = $this->Api_Classteacher_model->findfield('staff_designation','id',$value['designation'],'designation') ;
        }
           if ($details) {
                      $data['records'] = $details;                       
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
             $data['records'] = " ";                       
                      $data['msg'] = "No result found";  
                      $data['status'] = "false";
           }
          
            echo json_encode($data); 

        }
      
      
      
      
      
  public function Attendance_report(){
               $data = array();
               $number = $this->input->post('number'); 
                 $student_id =$this->input->post('id'); 
              // $student_id = $this->Api_Classteacher_model->get_data_by_query1("SELECT id from students where guardian_phone like '%$number%' "); 
              // $student_session = $this->Api_Classteacher_model->get_data_by_query1("SELECT id from student_session where student_id =  '".$student_id['id']."' ");      
                $student_session = $this->Api_Classteacher_model->get_data_by_query1("SELECT id from student_session where student_id =  '$student_id' ");  
               $details = $this->Api_Classteacher_model->get_data_by_query("SELECT * from student_attendences where student_session_id = '".$student_session['id']."' ") ;
 
               $atten = $this->Api_Classteacher_model->get_data_by_query("SELECT * from attendence_type where 1 ") ;
        
           foreach ($details as $key1 => $value)
                 {    
                  foreach ($atten as $key => $val) {                
                    if ($value['attendence_type_id'] === $val['id']) 
                         {
                            
                    $details[$key]['attendence_type_id'] = $val['type'];
                         }
                  }
             }
               
           if ($details) {
                      $data['records'] = $details;                       
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
             $data['records'] = " ";                       
                      $data['msg'] = "No result found";  
                      $data['status'] = "false";
           }
          
            echo json_encode($data); 

      }
      
      
  public function Expences_report(){
               $data = array();
                $month = $this->input->post('month'); 
                $expence = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM expenses where DATE_FORMAT(`date`, '%m') ='$month' ");         
          
               
           if ($expence) {
                      $data['records'] = $expence;                       
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
             $data['records'] = " ";                       
                      $data['msg'] = "No result found";  
                      $data['status'] = "false";
           }
          
            echo json_encode($data); 

      }



public function StudentAttendance()
{
    $postdata =  $this->input->post('data'); 
    
    
     //$postdata = '{"student_id":"1","month":"4","year":"2019"}' ;  
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
          $student_id  = $request->student_id;  
          $month  = $request->month;  
          $year  = $request->year;  
         //$examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id,$exam_id);
         
         $student=$this->Api_model->get_student($student_id);
         
         $student_att=$this->Api_model->get_attendancestudent($student->id,$month ,$year);
         $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
         
         for($i=1;$i<=$num_of_days;$i++){
          $atten=array();
          $atten['date']=$year.'-'.$month.'-'.$i;
          $atten['attendence_type_id']=10;
          $atten['type']="None";
          $atten['remark']='';
          foreach($student_att as $attendace){
          if($i==$attendace->daeee){
          $atten['date']=$attendace->date;
          $atten['attendence_type_id']=$attendace->attendence_type_id;
          $atten['type']=$attendace->type;
          $atten['remark']=$attendace->remark;
          break;
          }
         
         }
          $atten_list[]=$atten;
         }
        
         

          if(!empty($atten_list)){
            $data['msg'] = 'Result found' ; 
            $data['Status'] = true ; 
            $data['attList'] = $atten_list ; 
          }else{
            $data['msg'] = 'No result found' ; 
            $data['Status'] = false ; 
          }
     
          }else{
            $data['msg'] = 'No Post data found' ; 
            $data['Status'] = false ; 
          }
       }
       else
       {
        $data['msg'] = 'no Post data found' ; 
        $data['Status'] = false ; 
       }
        echo json_encode($data);  
}




public function Classtimetable()
{
    $postdata =  $this->input->post('data');  
   
    if (!empty($postdata))
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
            $student_id=$request->student_id;
         $student=$this->Api_model->get_student($student_id);
         //print_r($student);
         if(!empty($student)){
          $class_id  = $student->class_id;  
          $section_id  = $student->section_id; 
          $days= $this->db->get('days')->result_array();
         $period=$this->db->get('periods')->result_array(); 

          $TimeTableList=array();
 foreach($days as $days_key=>$days_value)
 {
    foreach($period as $period_value) 
    {
    	$timetable_obj=$this->staff_model->getdetails_by_classID12($class_id, $section_id ,$days_value['id'],$period_value['id']);
    	if(!empty($timetable_obj))
         $TimeTableList[$days_value['value']][] = $timetable_obj;
     	else{

     		$timetable_obj=new stdClass;
     		$timetable_obj->id='';
     		$timetable_obj->teacher_id='';
     		$timetable_obj->period_id='';
     		$timetable_obj->days='';
     		$timetable_obj->class_id='';
     		$timetable_obj->section_id='';
     		$timetable_obj->subject_id='';
     		$timetable_obj->created_at='';
     		$timetable_obj->status='';
     		$timetable_obj->tname='';
     		$timetable_obj->surname='';
     		$timetable_obj->subname='';
     		$timetable_obj->section='';
     		$timetable_obj->class='';
     		 

     		 $TimeTableList[$days_value['value']][] = $timetable_obj;
     	}
    }  
  }

            if(!empty($TimeTableList)){
            $data['msg'] = 'result found' ; 
            $data['Status'] = '1' ; 
            $data['TimeTableList'] = $TimeTableList ; 
          }else{
            $data['msg'] = 'No result found' ; 
            $data['Status'] = '0' ; 
          }

      }else{
            $data['msg'] = 'No result found' ; 
            $data['Status'] = '0' ; 
          }
     
          }else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = '0' ; 
          }
       }
       else
       {
        $data['msg'] = 'no Post data found' ; 
        $data['Status'] = '0' ; 
       }
        echo json_encode($data);  
}



///----------------------------End-Class------------------------------///     
 } 
 
?>