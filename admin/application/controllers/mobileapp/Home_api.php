<?php
use Aws\MediaConvert\MediaConvertClient;  
use Aws\Exception\AwsException;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Aws\Result;
 class Home_api extends Public_Controller
 {
  private $config_aws;
  private $s3obj;
     public function __construct() {
        parent::__construct();
        $this->load->model("api/Homeapi_model","Api_model");
        $this->load->model("Api_model","Api_model_Fees");
        $this->load->model("api/Api_Classteacher_model","Api_Classteacher_model");
        $this->load->model("api/Api_Messages_model","Api_Messages_model");
        $this->load->model('staff_model');
        $this->load->model('Student_model');
        $this->load->model('Examschedule_model');
        $this->load->library('Enc_lib');
        $this->roomPath = $this->setting_model->getroompath();
        $this->roomSecretKet = $this->setting_model->getroomsecretkey();
        $this->current_session = $this->setting_model->getCurrentSession();
        include APPPATH . 'third_party/vendor/autoload.php';
        include APPPATH . 'third_party/initialize.php';
        
        date_default_timezone_set('Asia/Kolkata');
    }
 
    

 
    public function adminLoginapi()
    {
        $data = array();
        if (!is_null($this->input->post('admin_email'))) {
            $email = $this->input->post('admin_email');
        }else{
            $email = '';
        }
        if (!is_null($this->input->post('admin_password'))) {
            $password = $this->input->post('admin_password');
        }else{
            $password = '';
        }        
        if (($email == '') && ($password == '')) {
            $data['status'] = false;
            $data['msg'] = "Please enter email and password";            
          }else{
            $loginData = array(
                'email' => $email,
                'password' => $password
            );
             $result = $this->Api_model->checkloginapi($loginData);
            if(!empty($result)) {
                $data['status'] = true;
                $data['msg'] = "Login Successfully";
                $data['result'] = $result;
            }else{
                $data['status'] = false;
                $data['msg'] = "Invalid id and password";
            }
        }
        echo json_encode($data);
    }

    function startmonthandend() {
        $startmonth = $this->setting_model->getStartMonth();
        if ($startmonth == 1) {
            $endmonth = 12;
        } else {
            $endmonth = $startmonth - 1;
        }
        return array($startmonth, $endmonth);
    }

    function whatever($feecollection_array, $start_month_date, $end_month_date) {
        $return_amount = 0;
        $st_date = strtotime($start_month_date);
        $ed_date = strtotime($end_month_date);
        if (!empty($feecollection_array)) {
            while ($st_date <= $ed_date) {
                $date = date('Y-m-d', $st_date);
                foreach ($feecollection_array as $key => $value) {

                    if ($value['date'] == $date) {
                        $return_amount = $return_amount + $value['amount'] + $value['amount_fine'];
                    }
                }
                $st_date = $st_date + 86400;
            }
        } else {
            
        }

        return $return_amount;
    }

    function getExpensebyday($date) {
        $result = $this->admin_model->getExpensebyDay($date);
        if ($result[0]['amount'] == "") {
            $return = 0;
        } else {
            $return = $result[0]['amount'];
        }
        return $return;
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

    public function msgreport_api()
    {
        $data = array();
        $id = $this->input->post("id");
        // $id = 20;
        if (!empty($id) && !is_null($id)) {
            $details = $this->Api_Classteacher_model->get_data_by_query1("SELECT * from messages where id='$id' ");
            if (!empty($details)) {
                $class_id =   $details['class_id']; 
                $section_id =  $details['section_id'];  
                $session_id =  $details['session_id'];
                if ($details['type'] == '1') {
                    $number = $this->Api_messages_model->get_all_student_no($class_id, $section_id, $session_id);
                    $number=explode(',',$number['numbers']) ;
                    foreach ($number as $numberDetails) {
                        $getStudents = new stdClass();
                        $getStudents = $this->Api_model->getStudentDetailByNum($numberDetails,$class_id,$section_id,$session_id);
                        $getArray[] = $getStudents;
                    }
                    $data['status'] = true;
                    $data['studentList'] = $getArray;
                }else{
                    $number = $details['user_list'] ;
                    $number = explode(',',$number);
                    foreach ($number as $numberDetails) {
                        $getStudents = new stdClass();
                        $getStudents = $this->Api_model->getStudentDetailByNum($numberDetails,$class_id,$section_id,$session_id);
                        $getArray[] = $getStudents;
                    }
                    $data['status'] = true;
                    $data['studentList'] = $getArray;
                }   
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        
    echo json_encode($data);
    }

   

    public function checkNumber($number)
    {
        foreach ($number as $num) {
            $result = $this->Api_model->checkNumberDetail($num);
            if (!empty($result)) {
                return $result;
            }
        }        
    }


    public function all_message_api()
    {
        $data = array();
        // $mn = '9826584478';
        $mn = $this->input->post('number');
        if (!empty($mn)) {
            $mn= str_replace('%20','',$mn);    
            $number = explode(',', $mn);
            // print_r($number);
            $getData = $this->checkNumber($number);
            if (!empty($getData)) {
                // print_r($getData);
                $messageList = $this->Api_model->getAllmessage($getData[0]->mobileno);
                // print_r($messageList);
                $data['status'] = true;
                $data['studentName'] = $getData[0]->firstname.' '.$getData[0]->lastname;
                $data['messageList'] = $messageList;
                if (empty($messageList)) {
                    $session_id = $getData[0]->session_id;
                    $section_id = $getData[0]->section_id;
                    $class_id = $getData[0]->class_id;
                    $messageListResult = $this->Api_model->getAllmessagenumber($session_id,$section_id,$class_id);
                    // print_r($messageListResult);
                    $data['status'] = true;
                    $data['studentName'] = $getData[0]->firstname.' '.$getData[0]->lastname;
                    $data['messageList'] = $messageListResult;
                }
            }else{
                $data['status'] = false;
                $data['msg'] = "No records found";
            }
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }

        echo json_encode($data);
    }

    public function transportfee_search_api()
    {
        $data = array();
        $getVehicle = $this->Api_model->getVehicle();
        if (!empty($getVehicle)) {
            $data['status'] = true;
            $data['vehicleList'] = $getVehicle;
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        echo json_encode($data);
    }

    public function search_vehicle_students_api()
    {
        $data = array();
        $id = $this->input->post('id');
        // $id = 5;
        if (!empty($id)) {
            $searchList = $this->Api_model->searchList($id);
            if (!empty($searchList)) {
                $data['status'] = true;
                $data['studentList'] = $searchList;
            }else{
                $data['status'] = false;
                $data['msg'] = "No records found";
            }
        }else{
            $data['status'] = false;
            $data['msg'] = "No records found";
        }
        echo json_encode($data);
    }


    /* parent api starts here */     
    /*public function parent_login_old(){
         $data = array();
              $number = $this->input->post('number');
              $password = $this->input->post('password');
              $device_token =$this->input->post('device_token');
              if (!empty($number && $password)) {
                      $login = $this->Api_model->parent_login($number, $password );
                
                if (!empty($login))
                 {     $url = base_url();
                      $id = $login->id ;
                      $details = $this->Api_Classteacher_model->get_data_by_query("SELECT students.*,CONCAT('$url',image) as image,classes.class, sections.section from students 
                          join student_session on student_session.student_id = students.id 
                          join classes on student_session.class_id = classes.id
                          join sections on student_session.section_id = sections.id
                              where (guardian_phone=$number or mobileno=$number or father_phone=$number or mother_phone=$number ) and student_session.session_id =  '".$this->current_session."'") ; 
                      if($device_token){  
                        foreach ($details as $key => $value) {
                            $device['device_token'] = $device_token ;
                           $this->db->where('id',$value['id']);
                           $this->db->update('students',$device);
                           $student_id = $value['id'] ;
                           $isexist = $this->db->query("SELECT * from devicetoken where device_token = '".$device_token."' and student_id = '".$student_id."'")->row();
                           if(!empty($isexist)){
                            $date = date('Y-m-d h:i:s');
                             $this->db->where('id',$isexist->id);
                             $this->db->update('devicetoken',array('updated_at'=>'$date'));
                           }else{
                            $insertdata['student_id'] = $value['id'];
                            $insertdata['device_token'] = $device_token;
                            $insertdata['updated_at'] =  date('Y-m-d h:i:s');
                            $this->db->insert('devicetoken ',$insertdata);
                           }
                          }
                       } 
                  }else{
                        $id = '';                 
                       }
             
                if (!empty($id) ) {
                    if($this->current_session){
                $session_year_mix=    $this->db->get_where('sessions',array('id'=>$this->current_session))->row()->session;
                $session_year_mix_arr=explode('-', $session_year_mix);
                $session_start_date=$session_year_mix_arr[0].'-04-01 00:00:01';
            }else{$session_start_date=date('Y').'-04-01 00:00:01';}
                    $data['id'] = $id;
                    $data['details'] = $details;
                    $data['number'] = $number;
                    $data['session_id'] = $this->current_session;
                    $data['session_start_date'] = $session_start_date;
                    $data['status'] = true;
                    $data['msg'] = 'login Successfully';
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Number not found";
                }
            }else{
                $data['status'] = false;
                $data['msg'] = "please fill number";
            }
            echo json_encode($data);
          } */
          
    
	
    public function parent_login(){
         $data = array();
		 
              $number = $this->input->post('number');
              $password = $this->input->post('password');
              $device_token =$this->input->post('device_token');
              if (!empty($number && $password)) {
                      $login = $this->Api_model->parent_login($number, $password );                
                if (!empty($login))
                 { 
                      $url = base_url();
                      $id = $login->id;
                      $details = $this->Api_Classteacher_model->get_data_by_query("SELECT students.*,CONCAT('$url',image) as image,
                      sections.section,classes.class,sections.id as section_id,classes.id as class_id ,student_session.id as student_session_id from students 
                          join student_session on student_session.student_id = students.id 
                          join classes on student_session.class_id = classes.id
                          join sections on student_session.section_id = sections.id
                          where (guardian_phone=$number or mobileno=$number or father_phone=$number or mother_phone=$number ) 
                          and student_session.session_id = '".$this->current_session."'"); 
				    	                           
                      if($device_token){  
                        foreach ($details as $key => $value) {
                            $device['device_token'] = $device_token ;
                           $this->db->where('id',$value['id']);
                           $this->db->update('students',$device);
                           $student_id = $value['id'] ;
						    
                           $isexist = $this->db->query("SELECT * from devicetoken where device_token = '".$device_token."' and student_id = '".$student_id."'")->row();
                           if(!empty($isexist)){
                            $date = date('Y-m-d h:i:s');
                             $this->db->where('id',$isexist->id);
                             $this->db->update('devicetoken',array('updated_at'=>'$date'));
                           }else{
                            $insertdata['student_id'] = $value['id'];
                            $insertdata['device_token'] = $device_token;
                            $insertdata['updated_at'] =  date('Y-m-d h:i:s');
                            $this->db->insert('devicetoken ',$insertdata);
                           }
                          }					
                       } 
                  }else{
                      $id = '';                 
                   }
             
                if (!empty($id) ) {

                    if($this->current_session){
                $session_year_mix=    $this->db->get_where('sessions',array('id'=>$this->current_session))->row()->session;
                $session_year_mix_arr=explode('-', $session_year_mix);
                $session_start_date=$session_year_mix_arr[0].'-04-01 00:00:01';
				
              }else{
                    $session_start_date=date('Y').'-04-01 00:00:01';
                }
           $date = date('Y-m-d h:i:s');
  //      print_r($details);die;
			  foreach ($details as $key => $val){
               //   print_r($val) ; die;
                 $mother_phone =$val['mother_phone'];
                 $father_phone = $val['father_phone'];
                 $guardian_phone =$val['guardian_phone'];
                 $mobileno =$val['mobileno'];
                
              $number_array = ($mobileno)?$mobileno.',':'0,';
              $number_array .= ($father_phone)?$father_phone.',':'0,';
              $number_array .= ($mother_phone)?$mother_phone.',':'0,';
              $number_array .= ($guardian_phone)?$guardian_phone:'0';
          
              

				  $class_id = $val['class_id'] ;
				  $section_id = $val['section_id'] ;                    
			    $val['event_count'] = $this->db->query("SELECT count(id) as eventcount FROM events where start_date >=  '".$date."' order by
           start_date asc ")->row()->eventcount; 

					$val['homework_count'] = $this->db->query("SELECT count(id) as homeworkcount FROM messages where user_list IN ($number_array) and 
          (send_sms = 'homework' or send_sms ='Homework') and section_id=$section_id and class_id=$class_id  ")->row()->homeworkcount;
						               
					$val['notice_count'] = $this->db->query("SELECT count(id) as noticecount FROM messages where user_list IN ($number_array) and 
          (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id  ")->row()->noticecount;
						
							$newdeytail[] = $val ;     
					  }
							   
				           	$date_seen=date('Y-m-d h:i:s');
                    $this->db->query("update students set app_seen_date='$date_seen' where id = $id");	   
							  
			       
                    $data['id'] = $id;
                    $data['details'] = $newdeytail;
                    $data['number'] = $number;
                    $data['session_id'] = $this->current_session;
                    $data['session_start_date'] = $session_start_date;
                    $data['status'] = true;
                    $data['msg'] = 'login Successfully';
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Number not found";
                }
            }else{
                $data['status'] = false;
                $data['msg'] = "please fill number";
            }
            echo json_encode($data);
          } 
    /*end */  
    /*New api for parent login with imei no device*/  
    public function parent_login_new(){
      $data = array();  
           $number = $this->input->post('number');
           $password = $this->input->post('password');
           $device_token =$this->input->post('device_token');
           $imei =$this->input->post('imei');
           if (!empty($number && $password)) {
                   $login = $this->Api_model->parent_login($number, $password );                
             if (!empty($login))
              { 
                   $url = base_url();
                   $id = $login->id;
                   $details = $this->Api_Classteacher_model->get_data_by_query("SELECT students.*,CONCAT('$url',image) as image,
                   sections.section,classes.class,sections.id as section_id,classes.id as class_id ,student_session.id as student_session_id from students 
                       join student_session on student_session.student_id = students.id 
                       join classes on student_session.class_id = classes.id
                       join sections on student_session.section_id = sections.id
                       where (guardian_phone=$number or mobileno=$number or father_phone=$number or mother_phone=$number ) 
                       and student_session.session_id = '".$this->current_session."'"); 
                                      
                   if($device_token){  
                     foreach ($details as $key => $value) {
                         $device['device_token'] = $device_token ;
                        $this->db->where('id',$value['id']);
                        $this->db->update('students',$device);
                        $student_id = $value['id'] ;
             
                        $isexist = $this->db->query("SELECT * from devicetoken where device_token = '".$device_token."' and student_id = '".$student_id."' and imei ='".$imei."' ")->row();
                        if(empty($isexist)){    
                         $insertdata['student_id'] = $value['id'];
                         $insertdata['device_token'] = $device_token;
                         $insertdata['imei'] = $imei;
                         $insertdata['updated_at'] =  date('Y-m-d h:i:s');
                         $this->db->insert('devicetoken ',$insertdata);
                        }
                       }					
                    } 
               }else{
                   $id = '';                 
                }
          
             if (!empty($id) ) {

                 if($this->current_session){
             $session_year_mix=    $this->db->get_where('sessions',array('id'=>$this->current_session))->row()->session;
             $session_year_mix_arr=explode('-', $session_year_mix);
             $session_start_date=$session_year_mix_arr[0].'-04-01 00:00:01';
     
           }else{
                 $session_start_date=date('Y').'-04-01 00:00:01';
             }
        $date = date('Y-m-d h:i:s');
  //      print_r($details);die;
     foreach ($details as $key => $val){
            //   print_r($val) ; die;
              $mother_phone =$val['mother_phone'];
              $father_phone = $val['father_phone'];
              $guardian_phone =$val['guardian_phone'];
              $mobileno =$val['mobileno'];
             
           $number_array = ($mobileno)?$mobileno.',':'0,';
           $number_array .= ($father_phone)?$father_phone.',':'0,';
           $number_array .= ($mother_phone)?$mother_phone.',':'0,';
           $number_array .= ($guardian_phone)?$guardian_phone:'0';

       
             

       $class_id = $val['class_id'] ;
       $section_id = $val['section_id'] ;                    
       $val['event_count'] = $this->db->query("SELECT count(id) as eventcount FROM events where start_date >=  '".$date."' order by
        start_date asc ")->row()->eventcount; 

       $val['homework_count'] = $this->db->query("SELECT count(id) as homeworkcount FROM messages where user_list IN ($number_array) and 
       (send_sms = 'homework' or send_sms ='Homework') and section_id=$section_id and class_id=$class_id  ")->row()->homeworkcount;
                        
       $val['notice_count'] = $this->db->query("SELECT count(id) as noticecount FROM messages where user_list IN ($number_array) and 
       (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id  ")->row()->noticecount;
         
           $newdeytail[] = $val ;     
         }
              
       $date_seen=date('Y-m-d h:i:s');
                 $this->db->query("update students set app_seen_date='$date_seen' where id = $id");	   
             
          
                 $data['id'] = $id;
                 $data['details'] = $newdeytail;
                 $data['number'] = $number;
                 $data['session_id'] = $this->current_session;
                 $data['session_start_date'] = $session_start_date;
                 $data['status'] = true;
                 $data['msg'] = 'login Successfully';
             }else{
                 $data['status'] = false;
                 $data['msg'] = "Number not found";
             }
         }else{
             $data['status'] = false;
             $data['msg'] = "please fill number";
         }
         echo json_encode($data);
       } 

    public function homework(){
        
          $data = array();       
          $date = '';
          $number = $this->input->post('number');
          $id = $this->input->post('id');
          $date = $this->input->post('date');
          $a =array();
          $persons =array();          
          if (!empty($number) && !empty($id))    
          { 
              $numberdetails = $this->db->query("SELECT * from students where id = $id ")->row();
              $numberarray = ($numberdetails->mobileno)?$numberdetails->mobileno.',':'0,';
              $numberarray .= ($numberdetails->father_phone)?$numberdetails->father_phone.',':'0,';
              $numberarray .= ($numberdetails->mother_phone)?$numberdetails->mother_phone.',':'0,';
              $numberarray .= ($numberdetails->guardian_phone)?$numberdetails->guardian_phone:'0';
          
                        	 
			  $class = $this->Api_model->get_class_by_number($id);
              
              //$numberarray =  $numberdetails->mobileno.','.$numberdetails->father_phone.','.$numberdetails->mother_phone.','.$numberdetails->guardian_phone;             
              $url = base_url();
                if ($class!= $a) {  
                     $class_id=$class->class_id;
                     $section_id=$class->section_id;
                
                          if($date){ 
                            $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at, messages.path as filepath FROM messages where user_list IN ($numberarray) and type= 3 and section_id=$section_id  and class_id=$class_id  and  created_at > '$date' order by created_at desc"); 
                          }else{
                            $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at, messages.path as filepath FROM messages where user_list IN ($numberarray) and section_id = $section_id  and class_id=$class_id  and type = 3  order by created_at desc "); 
                              } 
                        if ($hw) {
                           $i = 0 ;                          
                           foreach($hw as $home){ 
						   	  $persons['id'] = $home['id'] ;
	                     	  $persons['title'] = $home['title'];
                              $persons['description'] = $home['message'];
                              $persons['path'] = ($home['filepath'] != '')?($url.'/'.$home['filepath']):'';
                              $persons['smiley_count'] = $home['smiley_count'];
                              $persons['smiley_status'] = $home['smiley_status'];  
                              $persons['subject_id'] = $home['subject_id'];                         
                              $persons['subject_name'] = $home['subject_name'];                         
                              $persons['teacher_name'] = $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'name') ;
                              $persons['teacher_designation'] =  'Employee ID : '.$this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'employee_id') ;
                              $timage= $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'image') ;
                              if (!empty($timage)) {
                                $persons['teacher_image'] = $url.$timage ;
                              }else
                              $persons['teacher_image'] = $url.'images/noimage.png' ;
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
              $numberdetails = $this->db->query("SELECT * from students where id = $id ")->row();
              $numberarray = ($numberdetails->mobileno)?$numberdetails->mobileno.',':'0,';
              $numberarray .= ($numberdetails->father_phone)?$numberdetails->father_phone.',':'0,';
              $numberarray .= ($numberdetails->mother_phone)?$numberdetails->mother_phone.',':'0,';
              $numberarray .= ($numberdetails->guardian_phone)?$numberdetails->guardian_phone:'0';
            
              
             // $numberarray =  $numberdetails->mobileno.','.$numberdetails->father_phone.','.$numberdetails->mother_phone.','.$numberdetails->guardian_phone;   
                //print_r($class);
                $url = base_url();
                if ($class!= $a) {  
                       $class_id=$class->class_id;
                       $section_id=$class->section_id;
      /*               if($date){
                           $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT *,messages.path as filepath FROM messages where user_list IN ($numberarray) and (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id    and created_at > '$date' "); 
                      }else{
                             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT *,messages.path as filepath FROM messages where user_list IN ($numberarray) and (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id  ");                 
                          }   */

                          if($date){
                            $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,messages.path as filepath FROM messages where user_list IN ($numberarray) and (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id    and created_at > '$date' order by created_at desc "); 
                       }else{
                            $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,messages.path as filepath FROM messages where user_list IN ($numberarray) and (send_sms = 'Complain' or send_sms ='Notice') and section_id=$section_id  and class_id=$class_id order by created_at desc ");                 
                           }  
                       if ($hw) {
                           $i = 0 ;                          
                           foreach($hw as $home){ 
						   	  $persons['id'] = $home['id'] ;
                              $persons['title'] = $home['title'] ;
                              $persons['description'] = $home['message'] ;
                               $persons['path'] = ($home['filepath'] != '')?($url.'/'.$home['filepath']):'';
                              $persons['smiley_count'] = $home['smiley_count'] ;
                              $persons['smiley_status'] = $home['smiley_status'] ;
                              $persons['teacher_name'] = $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'name') ;
                                $persons['teacher_designation'] =  'Employee ID : '.$this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'employee_id');
                             $timage=  $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'image');
                              if (!empty($timage)) {
                                $persons['teacher_image'] = $url.$timage ;
                              }else
                              $persons['teacher_image'] = $url.'images/noimage.png';                              
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
     
     
        public function S_fees(){
          $data = array();
          $id = $this->input->post('id');          
          $number = $this->input->post('number');          
          $persons =array();
          
          if (!empty($id) && !empty($number)) 
          {  
            $fee_details = $this->Api_Classteacher_model->get_data_by_query("SELECT * from student_fees where student_session_id = '".$id."' order by id desc ") ; 
                if ($fee_details) { 
                           $i = 0 ;                          
              foreach($fee_details as $fee){                             
                            $persons['amount'] = $fee['amount'] ;
                            $persons['amount_discount'] =$fee['amount_discount'] ;
                            $persons['amount_fine'] =$fee['amount_fine'] ;
                            $persons['description'] =$fee['description'] ;
                            $persons['payment_mode'] =$fee['payment_mode'] ; 
                            $persons['date'] =$fee['date'] ;   
                            $persons['mobile'] = $number ;
                       $name = $this->Api_Classteacher_model->get_data_by_query1("select * from students where id = '".$id."' and guardian_phone like '%$number%'") ; 
                      $persons['student_name'] = $name['firstname'].' '.$name['lastname'];                      
                      $data['records'][] = $persons;
                         } //end foreach
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
                     }     //end if fee details
                     else{
                        $data['status'] = false;
                        $data['msg'] = "No data Found From this Id"; 
                    }
                  }               
         else{
            $data['status'] = false;
            $data['msg'] = "Id not found";         
           }

           echo json_encode($data); 
     }
  

     
    public function eventbydate(){
      $date = $this->input->post('date');
    $events = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM events where start_date like '%$date%' ");         
      if ($events) {
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

 

public function events(){
     $date = $this->input->post('date');
    
    $cond = 1 ;
   
    $today = $date;
    $today .= " 00:00:00" ;            
    $events = $this->Api_Classteacher_model->get_data_by_query("SELECT *, start_date as startdate ,date_format(start_date  , '%d/%m/%Y') as start_date , date_format(end_date  , '%d/%m/%Y') as end_date  FROM events where start_date  >=  '$today' order by startdate desc ");         
                                
                             
      if (!empty($events)) {
                 $data['records'] = $events;                       
                 $data['msg'] = "Events found";  
                 $data['status'] = "true";
      }
      else{
                 $data['records'] = " ";                       
                 $data['msg'] = "No Events found ";  
                 $data['status'] = "false";
      }
     
       echo json_encode($data); 

 }   

 

 public function Event_list(){
  $events = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM events where 1 ");         
    if ($events) {
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


 public function Profile(){
          $data = array(); 
          $ifcheck = '' ;           
          $number = $this->input->post('number');    
          $id = $this->input->post('id');    
          if (!empty($number) && !empty($id) ) 
          {    
          $url = base_url();           
          $ifcheck = $this->Api_Classteacher_model->findfield('students','guardian_phone',$number,'id') ;
          if( $ifcheck != ''){
            $details = $this->Api_Classteacher_model->get_data_by_query("SELECT students.*,CONCAT('$url',students.image) as student_pic, CONCAT('$url',students.mother_pic) as mother_pic, CONCAT('$url',students.father_pic) as father_pic, CONCAT('$url',students.guardian_pic) as guardian_pic, classes.id AS `class_id`,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section FROM `students` left JOIN student_session on student_session.student_id=students.id left join classes on student_session.class_id=classes.id  left join sections ON sections.id=student_session.section_id  WHERE students.id ='$id' and student_session.session_id = '".$this->current_session."' group by students.id  ") ;

           if ($details) {
                       $data['records'] = $details;                       
                       $data['msg'] = "success result found";  
                       $data['status'] = "true";
            }
            else{
                $data['msg'] = "No result found";  
                $data['status'] = "false";
                }   
          }
          else{
            $data['msg'] = "Mobile number not matched ";  
            $data['status'] = "false";
            }  
          
              echo json_encode($data); 
            }

        }

public function Transport_fee_desc(){// fro admin
      
       
          $data = array();     
          $details =  array();          
                $student_list =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from student_session where route_id > 0 and vehicle_id > 0 ") ; 

            $vehicle_list =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from vehicles where id > 0   ") ; 
            $route_list =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from transport_route where id > 0 ") ; 
              $student =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from students where id > 0 ") ; 



          foreach ($vehicle_list as $key => $value) {
               $student_list[$key]['vehicle_id'] = $value['vehicle_no'] ;
                           }

            foreach ($route_list as $key => $value) {
                           $student_list[$key]['route_id'] = $value['route_title'] ;
                     }

                     foreach ($student as $key => $value) {
                           $student_list[$key]['student_name'] = $value['firstname'].' '.$value['lastname'] ;
                           $student_list[$key]['roll_no'] = $value['roll_no'] ;
                     
                                       }
           if ($student_list) {
                      $data['records'] =  $student_list  ;                 
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
                               
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
      
      
      

public function Trans_fee_detail_of_student(){// fro admin  
         $data = array();     
         $details =  array();      
         $number = $this->input->post('number');        
         $student_list =  $this->Api_Classteacher_model->get_data_by_query1("SELECT * from students where guardian_phone like '%$number%'  ") ;       
         $stu_session_id =  $this->Api_Classteacher_model->get_data_by_query1("SELECT * from student_session where student_id = '". $student_list['id']."'   ") ; 

         $tran_fee =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from student_transport_fees where student_session_id = '".$stu_session_id['id']."' ") ; 

        // $student =  $this->Api_Classteacher_model->get_data_by_query("SELECT * from students where id > 0 ") ; 


           if ($tran_fee) {
                      $data['records'] =  $tran_fee  ;                 
                      $data['msg'] = "success result found";  
                      $data['status'] = "true";
           }
           else{
                               
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



 public function Attendance(){
         $data = array();
         $id = $this->input->post('id'); 
         if($id){
            $session_id = $this->Api_Classteacher_model->get_data_by_query1("SELECT id FROM student_session where student_id = '$id' ");  
           
            if($session_id ){
                $attendance = $this->Api_Classteacher_model->get_data_by_query("SELECT date, attendence_type_id, remark FROM `student_attendences` WHERE `student_session_id` = '".$session_id['id']."'");  
                         }
                      else{
                      $data['records'] = " ";                       
                      $data['msg'] = "Empty Attendance";  
                      $data['status'] = "false";
                      echo json_encode($data); 
                    }
               } 

          if ($attendance) {
                      $data['records'] = $attendance;                       
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
      
   public function changePassword(){
        // send this   -> id, oldPassword, newPassword, confirmPassword
        $id = '' ;
        $oldPassword= $this->input->post('oldPassword') ;
        $newPassword= $this->input->post('newPassword') ;
        $confirmPassword= $this->input->post('confirmPassword') ;
        $id= $this->input->post('id') ;
        if($id)
         {
             if (!empty($oldPassword))
               { 
                  if ($newPassword == $confirmPassword)
                  {
                
                      $updatedata['password']  = $confirmPassword;
                      $this->db->where("id",$id)->where("password",$oldPassword)->update("students", $updatedata);
                      if($this->db->affected_rows() > 0){
                        $data['msg'] = 'Password Changed Successfully';
                        $data['status'] = true ;
                        }else{
                         $data['msg'] = 'Password Not Matched';
                         $data['status'] = false ;
                        }
                 }else
                 {
                      $data['msg'] = 'Password Not Matched' ;
                     $data['status'] = false ;
                 }
                

               }else
               {
                 $data['msg'] = 'Old Password Not Matched' ;
                 $data['status'] = false ;
               }
           
         }else
         {
              $data['msg'] = 'Old Password Not Matched' ;
              $data['status'] = false ;
         }

       echo json_encode($data) ;

      }

      public function updatePassword(){
        // send this   -> id, oldPassword, newPassword, confirmPassword
        $id = '' ;
        $oldPassword= $this->input->post('oldPassword') ;
        $newPassword= $this->input->post('newPassword') ;
        $confirmPassword= $this->input->post('confirmPassword') ;
        $id= $this->input->post('id') ;
        if($id)
         {
             if (!empty($oldPassword))
               { 
                  if ($newPassword == $confirmPassword)
                  {
                
                      $updatedata['password']  = $confirmPassword;
                      $this->db->where("id",$id)->where("password",$oldPassword)->update("students", $updatedata);
                      if($this->db->affected_rows() > 0){
                        $data['msg'] = 'Password Changed Successfully';
                        $data['status'] = true ;
                        }else{
                         $data['msg'] = 'Password Not Matched';
                         $data['status'] = false ;
                        }
                 }else
                 {
                      $data['msg'] = 'Password Not Matched' ;
                     $data['status'] = false ;
                 }
                

               }else
               {
                 $data['msg'] = 'Old Password Not Matched' ;
                 $data['status'] = false ;
               }
           
         }else
         {
              $data['msg'] = 'Old Password Not Matched' ;
              $data['status'] = false ;
         }

       echo json_encode($data) ;

      }
   public function edit_student_first(){
        // send this   -> id, oldPassword, newPassword, confirmPassword
        $id = '' ;
        
        $id= $this->input->post('id') ;
        if ($id)
         {
             
                 $data = array(
					'id' => $id,
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'gender' => $this->input->post('gender'),
					'dob' => date('Y-m-d', strtotime($this->input->post('dob'))), 					
					'cast' => $this->input->post('cast'),
					'religion' => $this->input->post('religion'),
					'mobileno' => $this->input->post('mobileno'),
					'email' => $this->input->post('email'),
					'blood_group' => $this->input->post('blood_group'),
					'height' => $this->input->post('height'),
					'weight' => $this->input->post('weight'),
					'measurement_date' => date('Y-m-d',strtotime($this->input->post('measurement_date'))),
'category_id' => $this->input->post('category_id')
					
            );



            	$this->Api_model->update_student($data);
                 $data['msg'] = 'Student Updated Successfully' ;
                 $data['status'] = true ;
                 

                 

               
           
         }else
         {
              $data['msg'] = 'Student Not Matched' ;
              $data['status'] = false ;
         }

       echo json_encode($data) ;

      }	  
	  
   public function edit_student_second(){        
        $id = '' ;         
        $id= $this->input->post('id') ;
        if ($id)
         {              
                 $data = array(
					'id' => $id,
					'father_name' => $this->input->post('father_name'),
					'father_phone' => $this->input->post('father_phone'),
					'father_occupation' => $this->input->post('father_occupation'),
					'mother_name' => $this->input->post('mother_name'),
					'mother_phone' => $this->input->post('mother_phone'),
					'mother_occupation' => $this->input->post('mother_occupation'),
					'guardian_is' => $this->input->post('guardian_is'),
					'guardian_name' => $this->input->post('guardian_name'),
					'guardian_relation' => $this->input->post('guardian_relation'),
					'guardian_email' => $this->input->post('guardian_email'),
					'guardian_phone' => $this->input->post('guardian_phone'),
					'guardian_occupation' => $this->input->post('guardian_occupation'),
					'guardian_address' => $this->input->post('guardian_address'),
					'current_address' => $this->input->post('current_address'),
					'permanent_address' => $this->input->post('permanent_address'),
            );
            	$this->Api_model->update_student($data);
                 $data['msg'] = 'Student Updated Successfully' ;
                 $data['status'] = true ;    
                 
         }else
         {
              $data['msg'] = 'Student Not Matched' ;
              $data['status'] = false ;
         }

       echo json_encode($data) ;

      }	  


function UpdateStudentAllImage() {
          $id = '';
          $id = $this->input->post('id');  
          $student = $this->input->post('student'); 
          $father= $this->input->post('father'); 
          $mother= $this->input->post('mother'); 
        $guardian= $this->input->post('guardian');  

    if($id)
     { 
 
           if(!empty($student))
           {
            $base = $_POST['student'];
            $file_name='stud' ;
            $file_name.=date("Ymdhis"); 
            // Decode Image
            $binary=base64_decode($base);
            header('Content-Type: bitmap; charset=utf-8');
            $file = fopen('uploads/student_images/'.$file_name.'.jpg', 'wb');
            // Create File
            fwrite($file, $binary);
            fclose($file);
            $path = "uploads/student_images/".$file_name.'.jpg';
            $data['image'] = $path ; 
           // $this->Api_model->update_student($data);
            //=========== Insert ================// 
       
 } 
              if(!empty($guardian))
{
                $base = $_POST['guardian'];
               $file_name='guard' ;
                $file_name .=date("Ymdhis"); 
                // Decode Image
                $binary=base64_decode($base);
                header('Content-Type: bitmap; charset=utf-8');
                $file = fopen('uploads/student_images/'.$file_name.'.jpg', 'wb');
                // Create File
                fwrite($file, $binary);
                fclose($file);
                $path1 = "uploads/student_images/".$file_name.'.jpg';
                $data['guardian_pic'] = $path1 ; 
               // $this->Api_model->update_student($data);
                //=========== Insert ================// 
               
                }
                  if(!empty($father))
{
                    $base = $_POST['father'];
		            $file_name='fath' ;
                    $file_name.=date("Ymdhis"); 
                    // Decode Image
                    $binary=base64_decode($base);
                    header('Content-Type: bitmap; charset=utf-8');
                    $file = fopen('uploads/student_images/'.$file_name.'.jpg', 'wb');
                    // Create File
                    fwrite($file, $binary);
                    fclose($file);
                    $path2 = "uploads/student_images/".$file_name.'.jpg';
                    $data['father_pic'] = $path2 ; 
                   // 
                    //=========== Insert ================// 
                   
                    }
                      if(!empty($mother))
{
                        $base = $_POST['mother'];
			$file_name='moth' ;
                        $file_name.=date("Ymdhis"); 
                        // Decode Image
                        $binary=base64_decode($base);
                        header('Content-Type: bitmap; charset=utf-8');
                        $file = fopen('uploads/student_images/'.$file_name.'.jpg', 'wb');
                        // Create File
                        fwrite($file, $binary);
                        fclose($file);
                        $path3 = "uploads/student_images/".$file_name.'.jpg';
                        $data['mother_pic'] = $path3 ; 
                       // 
                        //=========== Insert ================//        

                      }
                      $data['id'] =  $id ; 
                      if($this->Api_model->update_student($data)){
                            $res['msg'] = "Pictures Update Successfully" ;
                            $res['status']= true ; 

                      }else{
                        $res['msg'] = "Something went wrong ! Please try again later" ;
                        $res['status']= false ; 
                      }
               
     }else{
                        $res['msg'] = "Id Not matched" ;
                        $res['status']= false ; 
                      }

                    //  echo $this->db->last_query();
                      echo json_encode($res);

   } 



   public function prarent_attendance()
   {
       $postdata =  $this->input->post('data'); 
       
       
        //$postdata = '{"student_id":"1","month":"04","year":"2020"}' ;  
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
           //new
            $holidays = $this->db->query("SELECT school_holidays.name, school_holidays.date from school_holidays where YEAR(school_holidays.date) = $year AND MONTH(school_holidays.date) = $month")->result();
            
            if($month<10){
              $month = '0'.$month;
            }
            for($i=1;$i<=$num_of_days;$i++){
             $atten=array();
             
             if($i<10){
              $i = '0'.$i;
            }

             $atten['date']= $date = $year.'-'.$month.'-'.$i;
             $atten['attendence_type_id']=10;
             $atten['type']="None";
             $atten['remark']='';
   
   
            $holiday = $this->db->query("SELECT school_holidays.name, school_holidays.date from school_holidays where school_holidays.date = '$date'")->row();
                if (!empty($holiday)) {
                  $atten['date']=$holiday->date;
                  $atten['attendence_type_id']=11;
                  $atten['type']=$holiday->name;
                  $atten['remark']=$holiday->name;
                }else{
                  foreach($student_att as $attendace){
                    if($i==$attendace->daeee){
                    $atten['date']=$attendace->date;
                    $atten['attendence_type_id']=$attendace->attendence_type_id;
                    $atten['type']=$attendace->type;
                    $atten['remark']=$attendace->remark;
                    break;
                    }
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
   
   

public function prarent_attendance_old()
{
    $postdata =  $this->input->post('data'); 

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



public function parent_timetable()
{
    $postdata =  $this->input->post('data');  
    //$postdata = '{"student_id":"8"}' ;   
    if ($postdata != '')
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
     		 
     	}
     }  
  }

            if(!empty($TimeTableList)){
            $data['msg'] = 'result found' ; 
            $data['Status'] = true ; 
            $data['TimeTableList'] = $TimeTableList ; 
          }else{
            $data['msg'] = 'No result found' ; 
            $data['Status'] = false ; 
          }

      }else{
            $data['msg'] = 'No result found' ; 
            $data['Status'] = false ; 
          }
     
          }else{
            $data['msg'] = 'no Post data found' ; 
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



 public function activity_list()
 {// api for all teacher list with their description 
    $concaty='*';
    $base = base_url();
     $type =  ($this->input->post('type'))?$this->input->post('type'):1;  
        $data = array();
if ( $type==1) {
   $concaty="*,CONCAT('".$base."',image) as image";

}
            
               $details = $this->Api_Classteacher_model->get_data_by_query("SELECT $concaty from activity where type= $type   ") ; 
        // foreach ($details as $key => $value) {
        //    $details[$key]['designation']  = $this->Api_Classteacher_model->findfield('staff_designation','id',$value['designation'],'designation') ;
        // }
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


public function Appointments()
{ 
    $student_id =  $this->input->post('student_id');  
    // $postdata = '{"student_id":"42"}' ;   
    if (!empty($student_id))
       {  
 $data['new'] = $this->db->query("SELECT * From appointments where status = 0 and student_id  = $student_id ")->result();
 $data['approved'] = $this->db->query("SELECT * From appointments where status = 1 and student_id  = $student_id  ")->result();
 $data['completed'] = $this->db->query("SELECT * From appointments where status = 2 and student_id  = $student_id ")->result();
 $data['disapproved'] = $this->db->query("SELECT * From appointments where status = 3 and student_id  = $student_id ")->result();
     $outputarray['result'] = $data;
     $outputarray['status'] = true ;
     $outputarray['msg'] = "Appointment List Found";
  }
  else{
       $outputarray['msg'] = 'no Post data found' ; 
        $outputarray['Status'] = false ; 
  }
  echo json_encode($outputarray);  

}


public function Send_Appointment()
{
    $student_id  =  $this->input->post('student_id');
    $appointment_date  =  $this->input->post('appointment_date');
    $meet_to  =  $this->input->post('meet_to');
    $description  =  $this->input->post('description');
      if ($student_id != '')
       {         
           $insertdata['student_id'] =  $student_id;
           $insertdata['meet_to'] =  $meet_to;
           $insertdata['appointment_date'] =  $appointment_date;
           $insertdata['description'] = $description;
            $this->db->insert('appointments',$insertdata);
           $data['msg'] = 'appointment Send successfully' ; 
           $data['Status'] = true ;
         }        
       else
       {
        $data['msg'] = 'no Post data found' ; 
        $data['Status'] = false ; 
       }
        echo json_encode($data);  
  
}
/*
  public function student_fee_detail()
    {
        $student_id = $this->input->post('student_id');
        $session_id = $this->input->post('session_id');
        if($student_id!='' && $session_id!='')
        {
         $student= $this->Student_model->api_get($student_id,$session_id);
         $is_promoted = $student['promoted'];
        if (!empty($student)) {
             $student_total_fees = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
             if (!empty($student_total_fees)) {
                 foreach($student_total_fees as $key => $fees) 
                        {
                            foreach ($fees->fees as $key => $student_total_fee) {
                              
                          if ($is_promoted == 0){
                             $data['amount'] = $student_total_fee->amount_II;
                          }else{
                             $data['amount'] = $student_total_fee->amount;
                           }
                           $data['head'] = $student_total_fee->type;
                           $data['due_date'] = $student_total_fee->due_date;

                      if ($student_total_fee->amount_detail === '0' ){
                          $data['status'] = "Unpaid";
                          }else{
                            $data['status'] = "Paid";
                          }

                          $Feesdetails[] = $data ;
                           }
                        }
                            $totalfee = 0;
                            $deposit = 0;
                            $discount = 0;
                            $balance = 0;
                            $fine = 0;
                            $obj= new stdClass();

                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                                if (!empty($student_total_fees_value->fees)) {
                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                       if ($is_promoted == 0){
                                                $totalfee = $totalfee + $each_fee_value->amount_II;
                                          }else{
                                                $totalfee = $totalfee + $each_fee_value->amount;
                                          }

                                        

                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                       

                                         if (is_object($amount_detail)) {

                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit = $deposit + $amount_detail_value->amount;
                                                $fine = $fine + $amount_detail_value->amount_fine;
                                                $discount = $discount + $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                }
                            



                            }



                            $obj->totalfee = $totalfee;
                            $obj->payment_mode = "N/A";
                            $obj->deposit = $deposit;
                            $obj->fine = $fine;
                            $obj->discount = $discount;
                            $obj->balance = $totalfee - ($deposit + $discount);
                        } else {

                            $obj->totalfee = "N/A";
                            $obj->payment_mode = "N/A";
                            $obj->deposit = "N/A";
                            $obj->fine = "N/A";
                            $obj->balance = "N/A";
                            $obj->discount = "N/A";
                        }
                       
                      $data['Feesdetails'] = $Feesdetails;                       
                      $data['records'] = $obj;                     
                      $data['msg'] = "result found";  
                      $data['status'] = true;

            
        }
         else{
                      $data['records'] = " ";                       
                      $data['msg'] = "No result found";  
                      $data['status'] = false;
           }
       }
       else{
                      $data['records'] = " ";                       
                      $data['msg'] = "Invalid Request";  
                      $data['status'] = false;
           }

            echo json_encode($data); 

    }
*/

  public function student_fee_detail()
    {
        $student_id = $this->input->post('student_id');
        $session_id = $this->input->post('session_id');
        if($student_id!='' && $session_id!='')
        {
         $student= $this->Student_model->api_get($student_id,$session_id);
         $is_promoted = $student['promoted'];
        if (!empty($student)) {
            $currmonth = 0 ;
             $student_total_fees = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);

              
             if (!empty($student_total_fees)) {
                 foreach($student_total_fees as $key => $fees) 
                 {
                   foreach ($fees->fees as $key => $student_total_fee) { 
                   if ($student_total_fee->name == "Balance Master" || $student_total_fee->shows == 1) { 
                         $data['student_fees_discount_id'] =0;
                         $data['student_fees_master_id'] = $student_total_fee->id;
                         $data['fee_groups_feetype_id'] = $student_total_fee->fee_groups_feetype_id;
                     if ($is_promoted == 0)
                                    {      
                                      $data['amount'] = $student_total_fee->amount_II;    
                                      if($student_total_fee->name == "Balance Master")
                                      $data['amount'] = $student_total_fee->amount;
                                      
                                    }
                                else{
                                     $data['amount'] = $student_total_fee->amount;
                                    }                                   
                              $currmonth =date('m',strtotime($student_total_fee->due_date)); 
                              
                             
                   
                             if($student_total_fee->amount_detail === '0' )
                                  {

                                     /* new changes  Fine Calculation */
                                    $feesd =date('Y-m-d',strtotime($student_total_fee->due_date)); 
                                    $currdate = date('Y-m-d');
                                  
                                    $diff=date_diff(date_create($feesd),date_create($currdate));
                                   
                                     $dif =  $diff->format("%R%a");    
                                    
                                          if ($dif > 0) {
                                               /*  $num = $dif/30 ;                                    
                                               $finemon =  round($num);
                                               $amount_fine_charge =  $finemon*50; */ 
                                               $amount_fine_charge = 50; 
                                              }else{
                                              $amount_fine_charge = 0;
                                             }

                                             if ($student_total_fee->fine_apply == 0) {
                                              $amount_fine_charge = 0;
                                             }
                                          /* new changes */
                                          
                                   $data['status'] = "Unpaid";
                                   $data['paiddate'] = 'N/A';
                                   $data['amount_fine'] = $amount_fine_charge ;
                                   $data['payment_mode'] = 'N/A';
                                   $data['amount_discount'] = 0 ;
                                  }
                              else{
                                   $jsonobj = json_decode($student_total_fee->amount_detail);
                                   $p = 1; 
                                     $data['paiddate'] = $jsonobj->$p->date;
                                     $data['amount_fine'] = $jsonobj->$p->amount_fine;
                                     $data['payment_mode'] = $jsonobj->$p->payment_mode;
                                     $data['amount_discount'] = $jsonobj->$p->amount_discount;
                                     $data['status'] = "Paid";
                                  }

                              foreach ($fees->fees as $key => $student_total_fee2) {
                                  $feesmonth = date('m',strtotime($student_total_fee2->due_date )) ;
                                if ($student_total_fee->name != "Balance Master" && $currmonth == $feesmonth && $student_total_fee2->shows == 0) {
                                   if ($is_promoted == 0)
                                    {                                      
                                     $data['amount'] +=  round($student_total_fee2->amount_II);
                                    }
                                else{
                                     $data['amount'] +=  round($student_total_fee2->amount);
                                    }  
                          if($student_total_fee->amount_detail === '0' )
                                  {
                                  }
                              else{
                                     $jsonobj = json_decode($student_total_fee->amount_detail);
                                     $p = 1;                                     
                                     $data['amount_fine'] +=  round($jsonobj->$p->amount_fine);
                                     $data['amount_discount'] +=  round($jsonobj->$p->amount_discount);
                                  }
                                }
                               } #second foreach
                                $data['head'] = $student_total_fee->type;
                                $data['due_date'] = date('d-m-Y',strtotime($student_total_fee->due_date)); 
                                $Feesdetails[] = $data ; 
                        }                            
                }//#fees foreach end here...
            } //#student_total_fees foreach end here...
                            $totalfee = 0;
                            $deposit = 0;
                            $discount = 0;
                            $balance = 0;
                            $fine = 0;
                            $obj= new stdClass();
                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                                if (!empty($student_total_fees_value->fees)) {
                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                       if ($is_promoted == 0){
                                                $totalfee = $totalfee + $each_fee_value->amount_II;
                                          }else{
                                                $totalfee = $totalfee + $each_fee_value->amount;
                                          }         
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                         if (is_object($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                    $deposit = $deposit + $amount_detail_value->amount;
                                                    $fine = $fine + $amount_detail_value->amount_fine;
                                                    $discount = $discount + $amount_detail_value->amount_discount;
                                                }
                                              }
                                   }  
                                }
                              }
                            $obj->totalfee = round($totalfee);
                            $obj->payment_mode = "N/A";
                            $obj->deposit =  round($deposit - $discount);
                            $obj->fine = round($fine);
                            $obj->discount =  round($discount);
                            $obj->is_payable = 0;
                            $obj->balance =  round($totalfee - $deposit) ;
                        } else {
                            $obj->totalfee = 0;
                            $obj->payment_mode = "N/A";
                            $obj->deposit =0;
                            $obj->fine = 0;
                            $obj->balance = 0;
                            $obj->is_payable = 0;
                            $obj->discount = 0;
                          }
                      $datas['Feesdetails'] = $Feesdetails;                       
                      $datas['records'] = $obj;                     
                      $datas['msg'] = "result found";  
                      $datas['status'] = true;           
         }
         else{
                      $datas['records'] = " ";                       
                      $datas['msg'] = "No result found";  
                      $datas['status'] = false;
           }
       }
       else{
                      $datas['records'] = " ";                       
                      $datas['msg'] = "Invalid Request";  
                      $datas['status'] = false;
           }
         /*   print_r($datas);
           die; */
            echo json_encode($datas); 

    }

/*  //original code
  public function student_fee_detail()
    {
        $student_id = $this->input->post('student_id');
        $session_id = $this->input->post('session_id');
        if($student_id!='' && $session_id!='')
        {
         $student= $this->Student_model->api_get($student_id,$session_id);
         $is_promoted = $student['promoted'];
        if (!empty($student)) {
            $currmonth = 0 ;
             $student_total_fees = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);

  print_r($student_total_fees); die;
             if (!empty($student_total_fees)) {
                 foreach($student_total_fees as $key => $fees) 
                 {
                   foreach ($fees->fees as $key => $student_total_fee) { 
                   if (1 || $student_total_fee->shows == 1) { 
                         $data['student_fees_discount_id'] =0;
                         $data['student_fees_master_id'] = $student_total_fee->id;
                         $data['fee_groups_feetype_id'] = $student_total_fee->fee_groups_feetype_id;
                     if ($is_promoted == 0)
                                    {                                      
                                     $data['amount'] = $student_total_fee->amount_II;
                                    }
                                else{
                                     $data['amount'] = $student_total_fee->amount;
                                    }                                   
                              $currmonth =date('m',strtotime($student_total_fee->due_date)); 
                              
                             
                             
                             if($student_total_fee->amount_detail === '0' )
                                  {

                                    
                                    $feesd =date('Y-m-d',strtotime($student_total_fee->due_date)); 
                                    $currdate = date('Y-m-d');
                                  
                                    $diff=date_diff(date_create($feesd),date_create($currdate));
                                   
                                     $dif =  $diff->format("%R%a");    
                                    
                                          if ($dif > 0) {
                                    
                                               $amount_fine_charge = 50; 
                                              }else{
                                              $amount_fine_charge = 0;
                                             }

                                             if ($student_total_fee->fine_apply == 0) {
                                              $amount_fine_charge = 0;
                                             }
                                    
                                  
                                   $data['status'] = "Unpaid";
                                   $data['paiddate'] = 'N/A';
                                   $data['amount_fine'] = $amount_fine_charge ;
                                   $data['payment_mode'] = 'N/A';
                                   $data['amount_discount'] = 0 ;
                                  }
                              else{
                                   $jsonobj = json_decode($student_total_fee->amount_detail);
                                   $p = 1; 
                                     $data['paiddate'] = $jsonobj->$p->date;
                                     $data['amount_fine'] = $jsonobj->$p->amount_fine;
                                     $data['payment_mode'] = $jsonobj->$p->payment_mode;
                                     $data['amount_discount'] = $jsonobj->$p->amount_discount;
                                     $data['status'] = "Paid";
                                  }

                              foreach ($fees->fees as $key => $student_total_fee2) {
                                  $feesmonth = date('m',strtotime($student_total_fee2->due_date )) ;
                                if ($currmonth == $feesmonth && $student_total_fee2->shows == 0) {
                                   if ($is_promoted == 0)
                                    {                                      
                                     $data['amount'] +=  round($student_total_fee2->amount_II);
                                    }
                                else{
                                     $data['amount'] +=  round($student_total_fee2->amount);
                                    }  
                          if($student_total_fee->amount_detail === '0' )
                                  {
                                  }
                              else{
                                     $jsonobj = json_decode($student_total_fee->amount_detail);
                                     $p = 1;                                     
                                     $data['amount_fine'] +=  round($jsonobj->$p->amount_fine);
                                     $data['amount_discount'] +=  round($jsonobj->$p->amount_discount);
                                  }
                                }
                               } #second foreach
                                $data['head'] = $student_total_fee->type;
                                $data['due_date'] = $student_total_fee->due_date; 
                                $Feesdetails[] = $data ; 
                        }                            
                }//#fees foreach end here...
            } //#student_total_fees foreach end here...
                            $totalfee = 0;
                            $deposit = 0;
                            $discount = 0;
                            $balance = 0;
                            $fine = 0;
                            $obj= new stdClass();
                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                                if (!empty($student_total_fees_value->fees)) {
                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                       if ($is_promoted == 0){
                                                $totalfee = $totalfee + $each_fee_value->amount_II;
                                          }else{
                                                $totalfee = $totalfee + $each_fee_value->amount;
                                          }         
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                         if (is_object($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                    $deposit = $deposit + $amount_detail_value->amount;
                                                    $fine = $fine + $amount_detail_value->amount_fine;
                                                    $discount = $discount + $amount_detail_value->amount_discount;
                                                }
                                              }
                                   }  
                                }
                              }
                            $obj->totalfee = round($totalfee);
                            $obj->payment_mode = "N/A";
                            $obj->deposit =  round($deposit - $discount);
                            $obj->fine = round($fine);
                            $obj->discount =  round($discount);
                            $obj->is_payable = 1;
                            $obj->balance =  round($totalfee - $deposit) ;
                        } else {
                            $obj->totalfee = 0;
                            $obj->payment_mode = "N/A";
                            $obj->deposit =0;
                            $obj->fine = 0;
                            $obj->balance = 0;
                            $obj->is_payable = 1;
                            $obj->discount = 0;
                          }
                      $datas['Feesdetails'] = $Feesdetails;                       
                      $datas['records'] = $obj;                     
                      $datas['msg'] = "result found";  
                      $datas['status'] = true;           
         }
         else{
                      $datas['records'] = " ";                       
                      $datas['msg'] = "No result found";  
                      $datas['status'] = false;
           }
       }
       else{
                      $datas['records'] = " ";                       
                      $datas['msg'] = "Invalid Request";  
                      $datas['status'] = false;
           }
            echo json_encode($datas); 

    }

 */

  /*   START new function for add fees */
  
public function paypreviousfees($student_id,$session_id)
{
  $student= $this->Student_model->api_get($student_id,$session_id-1);
  $is_promoted = $student['promoted'];
 if (!empty($student)) {
      $currmonth = 0 ;
      $student_total_fees = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
       
      if (!empty($student_total_fees)) {
        foreach($student_total_fees as $key => $fees) 
        {
          foreach ($fees->fees as $key => $student_total_fee) { 
       
                $data['student_fees_discount_id'] =0;
                $data['student_fees_master_id'] = $student_total_fee->id;
                $data['fee_groups_feetype_id'] = $student_total_fee->fee_groups_feetype_id;
            if ($is_promoted == 0)
                           {      
                             $data['amount'] = $student_total_fee->amount_II;    
                             if($student_total_fee->name == "Balance Master")
                             $data['amount'] = $student_total_fee->amount;                                      
                           }
                       else{
                            $data['amount'] = $student_total_fee->amount;
                           }                                   
                     $currmonth =date('m',strtotime($student_total_fee->due_date));   
                     if($student_total_fee->amount_detail === '0' )
                         {
                             /*new changes  Fine Calculation*/
                           $feesd =date('Y-m-d',strtotime($student_total_fee->due_date)); 
                           $currdate = date('Y-m-d');
                         
                           $diff=date_diff(date_create($feesd),date_create($currdate));
                          
                            $dif =  $diff->format("%R%a");    
                           
                                 if ($dif > 0) {
                                    $amount_fine_charge = 50; 
                                     }else{
                                     $amount_fine_charge = 0;
                                    }

                                    if ($student_total_fee->fine_apply == 0) {
                                     $amount_fine_charge = 0;
                                    }
                                 /* new changes */
                                 
                          $data['status'] = "Unpaid";
                          $data['paiddate'] = 'N/A';
                          $data['amount_fine'] = $amount_fine_charge ;
                          $data['payment_mode'] = 'N/A';
                          $data['amount_discount'] = 0 ;
                         }else{
                          $data['status'] = "Paid";
                          $data['paiddate'] = 'N/A';
                          $data['amount_fine'] =  0 ;
                          $data['payment_mode'] = 'N/A';
                          $data['amount_discount'] = 0 ;
                         }
                    
                       $data['head'] = $student_total_fee->type;
                       $data['due_date'] = date('d-m-Y',strtotime($student_total_fee->due_date)); 
                       $Feesdetails[] = $data ; 
                                        
         }//#fees foreach end here...
      } //#student_total_fees foreach end here...

      
      $count = 0 ;
      $fee_receipt_no= $this->Api_model_Fees->feeereceipt_no();
     
          foreach($Feesdetails as $val){  
           // print_r($val['student_fees_discount_id']);  die;    
        if( $val['status'] == "Unpaid")  {
            $collected_by = "Collected By: Online Paytm"; //$this->customlib->getAdminSessionUserName();
            if (isset($val['student_fees_discount_id'])) {
             $student_fees_discount_id = $val['student_fees_discount_id'];
           }else{
            $student_fees_discount_id = 0 ;
            }     
          $json_array = array(
            'amount' => $val['amount'],
            'date' => date('Y-m-d'),
            'amount_discount' => ($val['amount_discount']>0)?$val['amount_discount']:0,
            'amount_fine' => ($val['amount_fine']>0)?$val['amount_fine']:0,
            'description' => "Carry forward fees pay by paytm  " .' '. $collected_by,
            'payment_mode' =>"online"
          );
          $data = array(
            'student_fees_master_id' => $val['student_fees_master_id'],
            'fee_groups_feetype_id' => $val['fee_groups_feetype_id'],
            'amount_detail' => $json_array,
            'receipt_no' => $fee_receipt_no
          );
          $inserted_id = $this->Api_model_Fees->fee_deposit($data, $student_fees_discount_id);
         // print_r($this->db->last_query());   
          if($inserted_id) {  
                 
          $outputarr['Result'][] = $inserted_id ;           
         } else{
           $count++ ; 
           }
       }  }
       return 1;      
    }//no studentfees found
     return 0;
    }//no student found
    return 0;
}
  public function add_fee()
  {    
   $postdata = $this->input->post('data');
   if ($postdata != '' ){     
    $request = json_decode($postdata);
    if(!empty($request)){
      $PaymentDetail = $request->PaymentDetails ;
      $transaction_detail = $request->transactionDetails;
      $student_id = $request->student_id;
      $class_id = $request->class_id;
      $section_id = $request->section_id;
      
      if ($request->order_id) {
        $order_id = $request->order_id;
      }else{
        $order_id = 1;
      }
    


      if ($student_id) {
      $count = 0 ;
      $fee_receipt_no= $this->Api_model_Fees->feeereceipt_no();

      $jsonarray = json_encode($request->transactionDetails);
      foreach ($transaction_detail as $value) {
        $transaction_data['txnid']=$value->TXNID;
        $transaction_data['checksuhash']=$value->CHECKSUMHASH;
        $chksumhash=$value->CHECKSUMHASH;
        $transaction_data['bankname']=$value->BANKNAME;
        $txnamouunt = $value->TXNAMOUNT;
        $transaction_data['txnamount']=$value->TXNAMOUNT;
        $transaction_data['txndate']=$value->TXNDATE;
        $transaction_data['mid']=$value->MID;
        $transaction_data['banktxnid']=$value->BANKTXNID;
        $transaction_data['respmsg']=$value->RESPMSG;
        $txnresmsg = $value->RESPMSG;
        $transaction_data['status']=$value->STATUS;
        $sts=$value->STATUS;
        $transaction_data['student_id']=$student_id;
        $transaction_data['class_id']=$class_id;
        $transaction_data['section_id']=$section_id;
        $transaction_data['json_array']=$jsonarray;
        $this->db->insert('transaction_details',$transaction_data);
                

       }
        $insrttransaction =  $this->db->insert_id();
        if($insrttransaction){         
        }else{
          $transaction_data1['json_array']=$jsonarray;
          $this->db->insert('transaction_details',$transaction_data1);
        }

       if($txnamouunt > 0 && $sts== "TXN_SUCCESS" ){      
      foreach($PaymentDetail as $val){     
            $collected_by = "Collected By: Online Paytm"; //$this->customlib->getAdminSessionUserName();
            if (isset($val->student_fees_discount_id)) {
             $student_fees_discount_id = $val->student_fees_discount_id;
           }else{
            $student_fees_discount_id = 0 ;
            }     
          $json_array = array(
            'amount' => $val->amount,
            'date' => date('Y-m-d'),
            'amount_discount' => ($val->amount_discount>0)?$val->amount_discount:0,
            'amount_fine' => ($val->amount_fine>0)?$val->amount_fine:0,
            'description' => $val->description . $collected_by,
            'payment_mode' =>"online"
          );
          $data = array(
            'student_fees_master_id' => $val->student_fees_master_id,
            'fee_groups_feetype_id' => $val->fee_groups_feetype_id,
            'amount_detail' => $json_array,
            'receipt_no' => $fee_receipt_no
          );
          if($val->head == "Previous Session Balance"){
            $farray['amount'] = '0.00';
            if($this->paypreviousfees($student_id, $this->current_session)){
              $this->db->where('id',$val->student_fees_master_id);
              $this->db->update('student_fees_master', $farray);
             }             
          }else{
            $inserted_id = $this->Api_model_Fees->fee_deposit($data, $student_fees_discount_id);
          }
         // $inserted_id = $this->Api_model_Fees->fee_deposit($data, $student_fees_discount_id);
          if($inserted_id) {           
          $outputarr['Result'][] = $inserted_id ;           
         } else{
           $count++ ; 
           }
       }
       if($order_id !=1){
       $this->db->where('order_id',$order_id);
       $this->db->update('temp_TransactionDetails',array('status'=>1));
      }
       $outputarr['Status'] = true; 
       $outputarr['receipt_no'] = $fee_receipt_no; 
       $outputarr['Msg'] = "Fess Deposit";
    }else{
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "Fees Not Deposit Transaction Fail";
    }
       if ($count > 0 ){            
             $outputarr['status'] = false; 
             $outputarr['msg'] = "Failed To Add Fee Please Contact To admin";
            }  
         } else{
          $outputarr['status'] = false; 
          $outputarr['msg'] = "Failed To Add Fee your payment failed";
        }

      }else { 
       $outputarr['status'] = false; 
       $outputarr['msg'] = "No request data found";
     } 
   }  
   else {  
     $outputarr['status'] = false; 
     $outputarr['msg'] = "No Post Data Found";
   }     
   echo json_encode($outputarr);
  }

  /* END  new function for add fees */


  

public function Save_parentMessage()
{
   $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
            $insert['message'] = $request->message ; 
            $insert['student_id'] = $request->student_id ;
            $insert['status'] = 0 ;
            $insert['principal_seen'] = 0  ;
            $insert['teacher_seen'] = 0  ; 
            $insert['reply_for'] = 0 ; 
            $this->db->insert('parent_teacher_message',$insert); 
            $data['msg'] = 'Message Saved Successfully' ; 
            $data['Status'] = true ;  
          }
       else{
            $data['msg'] = 'no Request data found' ; 
            $data['Status'] = false ; 
         }
     }
     else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }

          echo json_encode($data);  
}

public function Save_replyMessage()
{
   $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
            $insert['message'] = $request->message ; 
            $insert['student_id'] = $request->student_id ;
            $insert['status'] = 0 ;
            $insert['principal_seen'] = $request->principal_seen ;
            $insert['teacher_seen'] = $request->teacher_seen ;
            $insert['parent_seen'] = $request->parent_seen ;
            $insert['reply_for'] = $request->id  ; 
            $this->db->insert('parent_teacher_message',$insert); 
            $data['msg'] = 'Message Saved Successfully' ; 
            $data['Status'] = true ;  
          }
       else{
            $data['msg'] = 'no Request data found' ; 
            $data['Status'] = false ; 
         }

     }
     else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }

          echo json_encode($data);  
}


public function messageList()
{
   $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
          
            $student_id= $request->student_id ;
            $result1 = $this->db->query("SELECT * from parent_teacher_message where student_id = $student_id and status = 1")->result();
            $result0 = $this->db->query("SELECT * from parent_teacher_message where student_id = $student_id and status =0")->result();
            if (!empty($result0)) {
               $data['pending'] = $result0;
            }else{
               $data['pending'] = [] ;
            }

            if (!empty($result1)) {
              $data['solved'] = $result1 ;
            }else{
               $data['solved'] = [] ;
            }         
            $data['msg'] = 'List Found'; 
            $data['Status'] = true ;  
          }
       else{
            $data['msg'] = 'no Request data found' ; 
            $data['Status'] = false ; 
           }

     }
     else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }
          echo json_encode($data);  
}





public function sidebar_info()
      {

           $data['title'] = "J P Academy" ;
           $data['subtitle'] = "";//"<h4> Live as if you were to die tomorrow, learn as if you were to live forever.” </h4><br> <h5> Mrs.Ashima John </h5><h5> Principal </h5>";

           $data['msg'] = "";//"<p>Learning is a life time endeavor which has neither age nor classifications, but a sweet feeling of moving ahead step by step as to reach greater heights. Having proper guidance, designing the goal and being justified and willingness to confront the challenges are essential for anyone to succeed in life. <br>Our mission is to develop core skills in children besides textual information through value-based, technology aided and life oriented education that will best prepare them for the future. We believe in excellence in education and overall development of every child. <br> We take pride in educating our students to develop new skills, attitudes moral values and eventually character formation which transforms a child to an able, responsible and successful human being who can shoulder the responsibility of oneself and the society. <br>We are delighted to take you through matchless realms which have never been the dream of. A small decision which is taken in a fraction of second can invert everything and reach you far beyond your sight. We don’t want you to miss that sweet smile on your face when you retrospect and see you being victorious after many years. Our happiness lies in your success for which we are committed. I wish you a marvelous time ahead.</p>" ;

           $outputarray['principal_desk'] = $data;

           $datas['title'] = "J P Academy" ;
           $datas['subtitle'] = "About School";
           $datas['msg'] = "";//"<p>With the inspiration from the great educationist late Dr. Sarvapalli Radha Krishnan, the Scholar's Education Trust of India constituted the Central Academy Educational Society, which made a long-term programme to establish CENTRAL ACADEMY institutions at various places all over the country. All these schools are affiliated to C.B.S.E./I.C.S.E. or to the State Boards.<br>The chain of J P Academys throughout the country has a common curriculum, examination pattern, and uniform. Parents with transferable jobs are provided with the facility to shift their wards conveniently from one branch to another, even during the mid-session. <br>In Rajasthan, many branches have already celebrated Silver Jubilee and in Uttar Pradesh, our schools have completed a few years over a decade. So far the Trust has been able to achieve its goal partially, by opening and successfully running the schools in several states and cities like New Delhi, Kolkata, Lucknow, Barabanki, Varanasi, Gorakhpur, Deoria, Allahabad, Agra, Haridwar, Patna, Ranchi, Jamshedpur, Gwalior, Rewa, Sahdol, Bhopal, Ramgarh, etc. to name a few in the country and in most of the cities of Rajasthan at Jaipur, Jodhpur, Pali, Sikar, Udaipur, Bikaner, Alwar, Beawar, Ganganagar, Chittorgarh, Bharatpur, Bhilwara.  <br><strong>In Madhya Pradesh, we have schools in Jabalpur, Rewa, Shadhol, Malajkhand, Umaria, Maihar and Gwalior.</strong></p>" ;
           $outputarray['about_school'] = $datas;


           $datat['title'] = "J P Academy" ;
           $datat['subtitle'] = "CHAIRMAN DESK";
           $datat['msg'] = "";//"<p>Do not train a child to learn by force or harshness; but direct them to it by what amuses their minds, so that you may be better able to discover with accuracy the peculiar bent of the genius of each. <br> We are a happy school because happy children learn better than unhappy ones. We are, as one parent described us, “the school that children run to in the mornings”. The warmth of this school is apparent the moment you walk through the doors, as is the energy. Our classrooms are seldom quiet because they are filled with activity as children (and teachers) actively learn with enthusiasm. The engagement of the children is obvious; learning is a joyful experience which is energetically pursued. <br> Our children learn together because research teaches us that a collaborative environment is the most fertile one for learning to grow. We are a happy, international learning community, flourishing in an extraordinary facility which is unique to Jabalpur.<br> Our facilities and resources are outstanding because we believe children are very important. Our teachers are skilled and dedicated because they understand how important children are.<br> Come and join us, join the adventure</p>" ;

        $outputarray['chairman_desk'] = $datat;
        $outputarray['status'] = true;
        $outputarray['msg'] = "data Found";

         echo json_encode($outputarray); 
}

public function gallery()
{
    $base_url  = base_url();
  $gal_data = $this->db->query("SELECT * from front_cms_programs where type = 'gallery' ")->result(); 

     foreach ($gal_data as $key => $gal_datas) {
     $gal_datas->page_contents =  $this->db->query("SELECT front_cms_media_gallery.dir_path,front_cms_media_gallery.img_name, front_cms_media_gallery.thumb_path, CONCAT( '".$base_url."',front_cms_media_gallery.dir_path, front_cms_media_gallery.img_name) as imagepath, front_cms_media_gallery.thumb_name from front_cms_programs 
    join front_cms_program_photos on front_cms_program_photos.program_id = front_cms_programs.id 
    join front_cms_media_gallery on front_cms_media_gallery.id = front_cms_program_photos.media_gallery_id
    where front_cms_programs.type = 'gallery' and front_cms_programs.id =   $gal_datas->id")->result(); 
  }
  if (!empty($gal_data)) {
        
        $outputarray['result'] =  $gal_data;
        $outputarray['status'] = true;
        $outputarray['msg'] = "Gallery Found";
  }else{
        $outputarray['status'] = false;
        $outputarray['msg'] = "data not Found";
     }

   //print_r($outputarray); 
   echo json_encode($outputarray); 
}

//new apis starts here 

public function gatepassrequest()
{
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request->student_id))
          { 
            $data['student_id'] = $request->student_id;
            $data['class_id'] = $request->class_id;
            $data['section_id'] = $request->section_id;
            $data['session_id'] = $request->session_id;
            $data['date'] = $request->date;
            $data['reason'] = $request->reason;            
            $data['receiverimage'] = $request->receiverimage;   
            $data['receiverdocimage'] = $request->receiverdocimage;
            $data['status'] = 0;
            $this->db->insert('gatepass_request',$data);
            if ($this->db->insert_id()) {
                $outputarray['msg']= "Request Saved Successfully";
                $outputarray['status']= true;
            }else{
                  $outputarray['msg']= "Request Saved Failed";
                  $outputarray['status']= false;
               }
          }else{
                $outputarray['msg']= "Invalid Student ID";
                $outputarray['status']= false;
               }
       }else{
                $outputarray['msg']= "Invalid Request";
                $outputarray['status']= false;
            }
    echo json_encode($outputarray); 
}

public function gatepassList()
{
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
            $data['student_id'] = $request->student_id;
            $data['class_id'] = $request->class_id;
            $data['section_id'] = $request->section_id;
            $data['session_id'] = $request->session_id;
            $data['status'] = 1;
            $result = $this->db->query("SELECT * from gatepass_request where student_id = '$request->student_id' and class_id = '$request->class_id' 
            and section_id = '$request->section_id'
            and gatepass_request.session_id = '$request->session_id' and gatepass_request.status=1 ")->result();
            if (!empty($result)) {
                $outputarray['msg']= "Previous Request Found";
                $outputarray['status']= true;
                $outputarray['result']= $result;
            }else{
                 $outputarray['msg']= "No Previous Request Found";
                 $outputarray['status']= false;
            }
          }else{
                $outputarray['msg']= "Invalid Request";
                $outputarray['status']= false;
               }
       }else{
             $outputarray['msg']= "Invalid Postdata";
             $outputarray['status']= false;
            }
    echo json_encode($outputarray); 
}

public function examlist()
{
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request->session_id))
          { 
            $data['class_id'] = $request->class_id;
            $data['section_id'] = $request->section_id;
            $data['session_id'] = $request->session_id;
            $result =  $this->examschedule_model->getExamByClassandSection_api($data['class_id'], $data['section_id'],$data['session_id']);
          
            if (!empty($result)) {
                   $outputarray['msg']= "Result found";
                   $outputarray['status']= true;  
                   $outputarray['result']= $result;  
            }else{
                   $outputarray['msg']= "Result Not Found";
                   $outputarray['status']= false;  
                 }
                 
          }else{
                   $outputarray['msg']= "Invalid Request";
                   $outputarray['status']= false;  
               }
       }else{
                   $outputarray['msg']= "Invalid Post data";
                   $outputarray['status']= false;  
            }

  echo json_encode($outputarray); 
}

public function examdetail()
{
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request->exam_id))
          { 
            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $exam_id = $request->exam_id;
            $result = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);          
            if (!empty($result)) {
                   $outputarray['msg']= "Exam-Schedule found";
                   $outputarray['status']= true;  
                   $outputarray['result']= $result;  
            }else{
                   $outputarray['msg']= "Result Not Found";
                   $outputarray['status']= false;  
                 }                 
          }else{
                   $outputarray['msg']= "Invalid Request";
                   $outputarray['status']= false;  
               }
          }else{
               $outputarray['msg']= "Invalid Post data";
               $outputarray['status']= false;  
              }

  echo json_encode($outputarray); 
}


public function payfees()
{
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 

          }else{

               }
       }else{

            }
 echo json_encode($outputarray); 
}

 public function saveGpsLocation()
 { 
    // gps_address
    $postdata = $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
            $address = $request->address; 
            $longitude = $request->longitude; 
            $latitude = $request->latitude; 
            $scholar_no = $request->scholar_no;
            $check =  $this->db->query("SELECT count(scholar_no) as counts from gps_address where scholar_no = '$scholar_no' ")->row()->counts;
            $data = array(
                'address'=>$address,
                'longitude'=>$longitude,
                'latitude'=>$latitude,
                'scholar_no'=>$scholar_no,
            );
            if ($check) {
                $data['updated_at'] = date('Y-m-d h:i:s');
                $this->db->where('scholar_no',$scholar_no);
                $this->db->update('gps_address',$data);
               }else{
                     $this->db->insert('gps_address',$data); 
                    }  
                 $outputarray['status'] = true;
                 $outputarray['msg'] = "Record Saved Successfully";
          }else{
                 $outputarray['status'] = false;
                 $outputarray['msg'] = "Request not Found";
               }
             }else{
                 $outputarray['status'] = false;
                 $outputarray['msg'] = "Post data not Found";
               }
     echo json_encode($outputarray);
 }

 public function appupdates()
 {
 	 $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          {
 		    $details['student_id'] = $request->student_id;
 		    $details['datetime'] = $request->datetime;//date('Y-m-d h:i:s');   		   
 		    $details['module'] = $request->module;
 		    $details['type'] =  'student';
 		    $detail['datetime'] = $request->datetime;//date('Y-m-d h:i:s');

 		    if (empty($details['student_id'])) {
                 $outputarray['status'] = false;
		         $outputarray['msg'] = "Student_id not Found"; 
 		         echo json_encode($outputarray); 
 		        die;
 		    }

 		    if (empty($details['module'])) {
                 $outputarray['status'] = false;
		         $outputarray['msg'] = "Module not Found"; 
 		         echo json_encode($outputarray); 
 		        die;
 		    }

 		    if (empty($details['datetime'])) {
                 $outputarray['status'] = false;
		         $outputarray['msg'] = "Datetime not Found"; 
 		         echo json_encode($outputarray); 
 		        die;
 		    }

 		    $check = $this->db->query("SELECT id from appdetails where student_id = '".$details['student_id']."' and module = '".$details['module']."' ")->row();
 		    if ($check) 
 		    {
 		    	$this->db->where('module',$request->module);
 		    	$this->db->where('student_id',$request->student_id);
 		    	$this->db->update('appdetails',$detail);
 		    	 $outputarray['status'] = true;
		         $outputarray['msg'] = "Data Updated Successfully"; 
 		    }
 		    else
 		    {
 		        $this->db->insert('appdetails',$details); 
 		        $outputarray['status'] = true;
		        $outputarray['msg'] = "Data inserted Successfully";  
 		    }
 	      }
          else
          {
		        $outputarray['status'] = false;
		        $outputarray['msg'] = "request data not Found";
          }
       }
      else
         {
	        $outputarray['status'] = false;
	        $outputarray['msg'] = "post data not Found";
         }
 	  echo json_encode($outputarray); 
 }


 
public function smiley_update()
{
  $postdata =  $this->input->post('data'); 
  if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { $message_id =$request->message_id;
            $smiley_count =$request->smiley_count;
            $this->db->where('id', $message_id);
            $this->db->update('messages', array('smiley_count'=>$smiley_count, 'smiley_status'=> 1));
            $outputarray['record'] = $this->db->query("SELECT * from messages where id = $message_id")->row();
            $outputarray['status'] = true;
            $outputarray['msg'] = "Simley Count Updated Successfully";

          } 
      else
         {
            $outputarray['status'] = false;
            $outputarray['msg'] = "request data not Found";
         }
   } 
   else
         {
            $outputarray['status'] = false;
            $outputarray['msg'] = "post data not Found";
         }
      echo json_encode($outputarray);
}



public function subjectlist()
{
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section'); 
    if (!empty($class) && !empty($section)) {
      $subjects = $this->db->query("SELECT teacher_timetable.subject_id, subjects.name from teacher_timetable 
         JOIN subjects on subjects.id = teacher_timetable.subject_id
         where teacher_timetable.class_id= $class and teacher_timetable.section_id = $section and teacher_timetable.status = 1 group by teacher_timetable.subject_id ")->result();
        $outputarr['Result'] = $subjects; 
        $outputarr['Status'] = true; 
        $outputarr['Msg'] = "Result Found";
    }else{
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Postdata Not Found";
    }
     echo json_encode($outputarr);
}



public function getChildLocation()
{
 $postdata =  $this->input->post('data');    
 if ($postdata != '')
   {  
    $request = json_decode($postdata);
    if(!empty($request))
      { 

        $student_id = $request->student_id;
        $date = $request->date;
        $session_id = $request->session_id;
        $pickdrop = $this->db->query("SELECT * from pick_drop where student_id = $student_id and session_id = $session_id and pickdropdate = '".$date."' order by id desc")->row();
        if (!empty($pickdrop)) {
           $rideid = $pickdrop->busride_id; 
          $ridedetails = $this->db->query("SELECT busride.*,bus_location.location from busride join bus_location on busride.id = bus_location.busride_id where busride.id = $rideid")->row();
        $outputarray['pickdrop'] = $pickdrop;
        $outputarray['ridedetails'] = $ridedetails;
        $outputarray['status'] = true;
        $outputarray['msg'] = "Record Found";
        }else{
             $outputarray['status'] = false;
            $outputarray['msg'] = "Ride data not Found";
        }
        
      }else
         {
            $outputarray['status'] = false;
            $outputarray['msg'] = "request data not Found";
         }
    }else
         {
           $outputarray['status'] = false;
           $outputarray['msg'] = "post data not Found";
         }
  echo json_encode($outputarray);
}




public function TestList()
{    
     $baseurl = base_url();
     $class_id = $this->input->post('class_id');
     $section_id = $this->input->post('section_id'); 
     $testdata = $this->db->query("SELECT class_test.* , staff.name, staff.surname, CONCAT('$baseurl',staff.image) as image from class_test join staff on staff.id = class_test.teacher_id where class_id = $class_id and section_id = $section_id ")->result();
     if ($testdata) {
        $data['result'] = $testdata;         
        $data['status'] = true;         
        $data['msg'] ='Test List Found';
     }else{
        $data['status'] = false;         
        $data['msg'] ='No test found';
     }    
  echo json_encode($data);
}

public function studenttestMarks()
{    $student_id = $this->input->post('student_id');
     $test_id = $this->input->post('test_id'); 
     $testdata = $this->db->query("SELECT * from class_test_marks where student_id = $student_id and test_id = $test_id")->row();
     if ($testdata) {
        $data['result'] = $testdata;         
        $data['status'] = true;         
        $data['msg'] ='Test List Found';
     }else{
        $data['status'] = false;         
        $data['msg'] ='No Test Result found';
     }    
  echo json_encode($data);
}


 public function todaysattendance()
 {
    $student_session_id = $this->input->post('student_session_id');
    $date = date('Y-m-d');
    //$date = '2019-02-21';
    $result = $this->db->query("SELECT * from student_attendences where student_session_id = $student_session_id and `date` = '$date' ")->row();
    if (!empty($result)) {
      if($result->attendence_type_id == 1){
         $data['status'] = true;         
         $data['msg'] ='Attendance found';
         $data['result'] ='Present';
      }elseif($result->attendence_type_id == 4){
         $data['status'] = true;         
         $data['msg'] ='Attendance found';
         $data['result'] ='Absent';
      }else{
         $data['status'] = true;         
         $data['msg'] ='Attendance found';
         $data['result'] ='Holiday';
      }
    }else{
         $data['status'] = false;         
         $data['msg'] ='Attendance not found';
    }
   echo json_encode($data);
    
 }


 public function checksum_api()
 {
 
     //$data[] = "";
     // following files need to be included
     require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
     require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");
     $checkSum = "";
     $paramList = array();
    //"ORDS" . rand(10000,99999999);//intval($this->input->post("ORDER_ID"));
    
    // $store_id = $this->input->post("store_id");
           // $user_id = intval($this->input->post("user_id"));
     // Create an array having all required parameters for creating checksum.
     $CUST_ID = $this->input->post('custId');
     $ORDER_ID = $ORDER_ID = "ORDS" . rand(10000,99999999);;
     // $INDUSTRY_TYPE_ID = "Retail";
     //$INDUSTRY_TYPE_ID = "PrivateEducation"; //intval($this->input->post("INDUSTRY_TYPE_ID"));
     // $CHANNEL_ID = "WAP"; //intval($this->input->post("CHANNEL_ID"));
     $TXN_AMOUNT = $this->input->post('amount'); 
     //$paramList["MID"] = "Centra96677453879384";
     $paramList["MID"] = "";
     $paramList["ORDER_ID"] = $ORDER_ID;
     $paramList["CUST_ID"] = $CUST_ID;
     //$paramList["INDUSTRY_TYPE_ID"] = "Retail";
     $paramList["INDUSTRY_TYPE_ID"] = "PrivateEducation";
     $paramList["CHANNEL_ID"] = "WAP";
     $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
     $paramList["WEBSITE"] = "DEFAULT";
     // $paramList["CHECKSUMHASH"] = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
      $paramList["CALLBACK_URL"] = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=".$ORDER_ID;
     //$paramList["CALLBACK_URL"] = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=".$ORDER_ID;

     // print_r($paramList);
     // die();

     $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
     
     if (!empty($checkSum)) {
         $data['success'] = 1;
         $data['result'] = $checkSum;
         $data['order_id'] = $ORDER_ID;
         $data['data'] = $paramList;

         $addarray = array(
           'order_id'=>$ORDER_ID,
           'amount'=>$TXN_AMOUNT,
           'student_id'=>$CUST_ID,
           'checksum'=>$checkSum,
           'status'=>0
            );
            $this->db->insert('temp_TransactionDetails', $addarray);
     }else{
         $data['success'] = 0;
         $data['message'] = "Operation Failed! Please try again..";
     }
     echo json_encode($data);
 }

//--------------------------------Video ----------------------//

public function ListVideo_12_05()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
           $class_id = $request->class_id;
           $subject_id = $request->subject_id;
           $topic_id = $request->topic_id;
           if (!empty($subject_id) && !empty($topic_id)&& !empty($class_id)) {
           $cond = "tvideo.class_id= '$class_id' and tvideo.topic = '$topic_id' and tvideo.subject_id ='$subject_id'";
           }else{
            $cond = "tvideo.class_id= '$class_id'";
           }
           $videos = $this->db->query("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,
           tvideo.status,tvideo.view_count, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as 
           subject_name,DATE_FORMAT(tvideo.created_at, '%d-%m-%Y %h:%i %p')as created_at from tvideo left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id where
           $cond ")->result();
           if (!empty($videos)) {
             foreach ($videos as $key => $video) {
                $video->like = $this->db->query("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ")->row()->likecount;
                if($video->video_type)
                $video->url =  SCHOOLEYE_BUCKET_URL."/jpa/upload/videos/".$video->url;
             }
             $data['video'] =$videos ;
             $data['msg'] = "Result found";  
             $data['status'] = true;
           }else{
             $data['msg'] = "Result not found";  
             $data['status'] = false;
           }
           
         }else{
           $data['msg'] = "No Requestdata found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}


public function ListVideo()
{
   $postdata =  $this->input->post('data'); 
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
       
        if(!empty($request))
         { 
           $class_id = $request->class_id;          
           $section_id = $request->section_id;
           $subject_id = $request->subject_id;
           //$topic_id = $request->topic_id;
           if (!empty($subject_id) && !empty($class_id)) {
            $cond = "tvideo.class_id= '$class_id' and tvideo.section_id= '$section_id' and tvideo.subject_id ='$subject_id'";
           }else{
            $cond ="tvideo.class_id= '$class_id' and tvideo.section_id= '$section_id' ";
           }
           $videos = $this->db->query("SELECT tvideo.id,CONCAT(staff.name,' ' ,staff.surname) as teacherName,
           tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,
           tvideo.status,tvideo.view_count, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as 
           subject_name,DATE_FORMAT(tvideo.created_at, '%d-%m-%Y %h:%i %p')as created_at,tvideo.created_at as createDate
           from tvideo left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id
           left join staff on staff.id = tvideo.teacher_id where
           $cond group by tvideo.url order by tvideo.created_at desc")->result();
           if (!empty($videos)) {
             foreach ($videos as $key => $video) {
                $video->like = $this->db->query("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ")->row()->likecount;
                if($video->video_type)
                $video->url =  SCHOOLEYE_BUCKET_URL."/jpa/upload/videos/".$video->url;
             }
             $data['video'] =$videos ;
             $data['msg'] = "Result found";  
             $data['status'] = true;
           }else{
             $data['msg'] = "Result not found";  
             $data['status'] = false;
           }
           
         }else{
           $data['msg'] = "No Requestdata found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}

public function topiclist()
{
 $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
           $subject_id = $request->subject_id;
   
           $data['topicList'] = $this->db->query("SELECT * from topics where subject_id= '$subject_id' ")->result();
           $data['msg'] = "Result found";  
           $data['status'] = true;
         }else{
           $data['msg'] = "No Requestdata found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 

}

public function likeVideo()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
          
           $video_id = $request->video_id;
           $student_id = $request->student_id;
           $checkexist = $this->db->query("SELECT count(id) as existcount from videolike where video_id = '$video_id' and student_id = '$student_id' ")->row()->existcount;
         
           if($checkexist > 0){
             $insert = 1 ;
           }else{
             $this->db->insert('videolike',array(
               'student_id'=>$student_id,
               'video_id'=>$video_id,
             ));
             $insert = $this->db->insert_id();
           }           
           if ($insert > 0) {            
           $data['msg'] = "Liked Successfully";  
           $data['status'] = true;
           }else{
             $data['msg'] = "Alredy Liked By User";  
             $data['status'] = false;
           }           
         }else{
           $data['msg'] = "No Requestdata found";  
           $data['status'] = false;
          }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}


// modified
public function viewVideo()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         {     
            
           $video_id = $request->video_id;
           $student_id = $request->student_id;

           $rec = $this->db->query("select id from videoView where video_id = '$video_id' and student_id = '$student_id'")->row() ; 
           if(!empty($rec)){
            $this->db->insert('videoView',array(
              'video_id'=>$video_id,
              'student_id'=>$student_id
            ));
            $view_count = $this->db->query("SELECT view_count as tvideo from tvideo where id = '$video_id' ")->row()->view_count;
            $newcount = $view_count+1 ;
            $this->db->where('id', $video_id);
            $this->db->update('tvideo',array('view_count'=>$newcount));
            $data['msg'] = "Viewed";  
            $data['status'] = true;
           }else{
            $data['msg'] = "Already Viewed";  
            $data['status'] = false;
         }
          
         }else{
           $data['msg'] = "No Request data found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}



public function viewVideoDetails()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
           $url = base_url();           
          
           $video_id = $request->video_id;
           $student_id = $request->student_id;
          $messages =  $this->db->query("SELECT videomessage.*,DATE_FORMAT(videomessage.created_at, '%d-%m-%Y %h:%i %p')as created_at ,CONCAT(students.firstname,' ',students.lastname) as student_name,CONCAT('$url',students.image) as student_image,
           CONCAT(staff.name,' ',staff.surname) as teacher_name, CONCAT('$url',staff.image) as teacher_image  from videomessage
            left join students on students.id = videomessage.student_id  
            left join staff on staff.id = videomessage.teacher_id  
           where videomessage.video_id = '$video_id' ")->result();

          $is_liked =  $this->db->query("SELECT * from videolike where video_id = '$video_id' and student_id = '$student_id'")->row();
         

           $videos = $this->db->query("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,
           tvideo.view_count,tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
           from tvideo left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id 
           where tvideo.id= '$video_id' ")->row();

           if (!empty($videos)) {
         
              if (!empty($is_liked)) {
               $videos->is_liked = 1 ;   
              }  else {
               $videos->is_liked = 0 ;   
              }                       
               $videos->like = $this->db->query("SELECT count(videolike.id) as likecount from videolike where video_id = '$videos->id' ")->row()->likecount;
               if($videos->video_type == 1)
                 $videos->url =  SCHOOLEYE_BUCKET_URL."/jpa/upload/videos/".$videos->url;
             //   }
           $data['video'] =$videos ;
           $data['comments'] =$messages ;
           $data['msg'] = "Details Found";  
           $data['status'] = true;
       
           } else{
             $data['msg'] = "No data found";  
             $data['status'] = false;
           }
         }else{
           $data['msg'] = "No Request data found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}

public function searchVideo()
{
 $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
           $class_id = $request->class_id;
           $text = $request->text;
           $videos = $this->db->query("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,tvideo.view_count,
           tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name from tvideo 
           left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id 
           where tvideo.class_id= '$class_id'  
           and (subjects.name like '%".$text."%' OR topics.name like '%".$text."%' OR tvideo.tag like '%".$text."%'  )")->result();
           if (!empty($videos)) {
             foreach ($videos as $key => $video) {
                $video->like = $this->db->query("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ")->row()->likecount;
                if($video->video_type)
                $video->url =  SCHOOLEYE_BUCKET_URL."/jpa/upload/videos/".$video->url;
             }
                 $data['video'] =$videos ;
                 $data['msg'] = "Result found";  
                 $data['status'] = true;
           } else{
             $data['msg'] = "No data found";  
             $data['status'] = false;
           }
         }else{
               $data['msg'] = "No Request data found";  
               $data['status'] = false;
              }
      } else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
         }
       echo json_encode($data); 
}


public function subjectlists()
{

           $data['subjects'] = $this->db->query("SELECT * from subjects")->result();
           $data['msg'] = "Result found";  
           $data['status'] = true;
       
       echo json_encode($data); 
}



public function commentVideo()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
           $res['video_id'] = $request->video_id;
           $res['student_id'] = $request->student_id;
           $res['teacher_id'] = 0;
           $res['message'] = $request->message;
           $res['message_by'] = 1 ;
      /*     $this->db->insert('videomessage',$res);
          $id = $this->db->insert_id(); */
          $id = 0; 
          $url = base_url();   
          $result = $this->db->query("SELECT videomessage.* ,CONCAT(students.firstname,' ',students.lastname) as student_name,
          CONCAT('$url',students.image) as student_image,
          CONCAT(staff.name,' ',staff.surname) as teacher_name,
          CONCAT('$url',staff.image) as teacher_image from videomessage 
           left join students on students.id = videomessage.student_id  
           left join staff on staff.id = videomessage.teacher_id where videomessage.id = '$id'")->row();  
           $data['msg'] = "Comment saved successfully";  
           $data['status'] = true;
           $data['result'] = $result;
         }else{
           $data['msg'] = "No Requestdata found";  
           $data['status'] = false;
        }
      }else{
           $data['msg'] = "No postdata found";  
           $data['status'] = false;
          }
       echo json_encode($data); 
}



public function studyMaterial(){
  $data = array();         
  $date = '';
  $number = $this->input->post('number');
  $id = $this->input->post('id');
  $date = $this->input->post('date');
  $a =array();
  $persons =array();          
  if (!empty($number) && !empty($id)) 
  {               	 
      $class = $this->Api_model->get_class_by_number($id);
      $numberdetails = $this->db->query("SELECT * from students where id = $id ")->row();
      $numberarray = ($numberdetails->mobileno)?$numberdetails->mobileno.',':'0,';
      $numberarray .= ($numberdetails->father_phone)?$numberdetails->father_phone.',':'0,';
      $numberarray .= ($numberdetails->mother_phone)?$numberdetails->mother_phone.',':'0,';
      $numberarray .= ($numberdetails->guardian_phone)?$numberdetails->guardian_phone:'0';
    
      // $numberdetails->mobileno.','.$numberdetails->father_phone.','.$numberdetails->mother_phone.','.$numberdetails->guardian_phone;             
      $url = base_url();
        if ($class!= $a) {  
             $class_id=$class->class_id;
             $section_id=$class->section_id;
          
              if($date){ 
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,messages.path as filepath FROM messages where user_list IN ($numberarray) and type= 5 and section_id=$section_id  and class_id=$class_id  and  created_at > '$date' order by id desc"); 
              }else{
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,messages.path as filepath FROM messages where user_list IN ($numberarray) and section_id = $section_id  and class_id=$class_id  and type = 5 order by id desc"); 
                                 
                  }                       
                if ($hw) {
                   $i = 0 ;                          
                   foreach($hw as $home){ 
           $persons['id'] = $home['id'] ;
                   $persons['title'] = $home['title'];
                      $persons['description'] = $home['message'];
                      $persons['path'] = ($home['filepath'] != '')?($url.'/'.$home['filepath']):'';
                      $persons['smiley_count'] = $home['smiley_count'];
                      $persons['smiley_status'] = $home['smiley_status'];                         
                      $persons['subject_id'] = $home['subject_id'];                         
                      $persons['subject_name'] = $home['subject_name'];                         
                      $persons['teacher_name'] = $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'name') ;
                      $persons['teacher_designation'] =  'Employee ID : '.$this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'employee_id') ;
                      $timage= $this->Api_Classteacher_model->findfield('staff','id',$home['teacher_id'],'image') ;
                      if (!empty($timage)) {
                        $persons['teacher_image'] = $url.$timage ;
                      }else
                      $persons['teacher_image'] = $url.'images/noimage.png' ;
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
    $data['msg'] = "Post Data not found";         
   }

   echo json_encode($data); 
}

 //appointments module apis ends here 
/* api for classroom timetable start*/

public function classRoomTimetable($sid)
{      
  $student=$this->Api_model->get_student($sid);
          if(!empty($student)){
          $class_id  = $student->class_id;  
          $section_id  = $student->section_id; 
        //  $days= $this->db->get('days')->result_array();
          $period=$this->db->get('periods')->result_array();
          $TimeTableList=array();
 /* foreach($days as $days_key=>$days_value)
 { */


  $day = date('N');
  $dayname = date('l');
    foreach($period as $period_value) 
    {
    	$timetable_obj=$this->staff_model->getdetails_by_classID12($class_id, $section_id ,$day,$period_value['id']);
    	if(!empty($timetable_obj))
         $TimeTableList['day'][] = $timetable_obj;
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
     		 
     	}
     }  
  //}

if(!empty($TimeTableList)){
return $TimeTableList ;
}else{
return 0 ;
}

      }else{
           return 0 ;
          }
     
        
     
}

/* api for classroom timetable  end*/
 public function activeRoomList(){  

  $id = $this->input->post('studentid');
  $class_id = $this->input->post('class_id');
  $section_id = $this->input->post('section_id');
  $todaydate = date('d-m-Y');
 if (!empty($id)) 
  {  
    $timetable = $timetables= $this->classRoomTimetable($id) ;
     $listActiveClass = $this->db->query("SELECT classroomStudents.*,classroom.title,classroom.starttime,classroom.lecture_id,classroom.endTime,classroom.iscancel, classroom.description,classroom.classroom_active,classroom.end_classAt,
     CONCAT(staff.name,' ',staff.surname) as teacher_name 
      from classroomStudents
      Join classroom on classroomStudents.classroom_id = classroom.id 
      left join staff on classroomStudents.teacher_id = staff.id
      where classroomStudents.student_id = '$id' and classroom.date = '$todaydate'
      and classroomStudents.class_id = '$class_id' and classroomStudents.section_id = '$section_id' group by classroom.link,classroom.created_at order by classroom.classroom_active desc , classroom.lecture_id asc ")->result();  	 
   
     if (!empty($listActiveClass)){
        
       $data['activeClassList'] = $listActiveClass;
       $data['timetable'] = $timetables;
       $data['status'] = true;
       $data['msg'] = "Active Class found"; 
     }else{
      $data['timetable'] = $timetable;
      $data['status'] = true;
      $data['msg'] = "No Active Classroom Found"; 
      $data['activeClassList'] = array();
     }       
 }               
 else{
    $data['status'] = false;
    $data['msg'] = "Post Data not found";         
   }
   echo json_encode($data); 
}

/* 
public function activeRoomListnew(){  

  $id = $this->input->post('studentid');
  $class_id = $this->input->post('class_id');
  $section_id = $this->input->post('section_id');
  $todaydate = date('d-m-Y');
 if (!empty($id)) 
  {  
    $timetable = $timetables= $this->classRoomTimetable($id) ;


     $listActiveClass = $this->db->query("SELECT classroomStudents.*,classroom.title,classroom.starttime,classroom.lecture_id,classroom.endTime,classroom.iscancel, classroom.description,classroom.classroom_active,classroom.end_classAt,
     CONCAT(staff.name,' ',staff.surname) as teacher_name 
      from classroomStudents
      Join classroom on classroomStudents.classroom_id = classroom.id 
      left join staff on classroomStudents.teacher_id = staff.id
      where classroomStudents.student_id = '$id' and classroom.date = '$todaydate'
      and classroomStudents.class_id = '$class_id' and classroomStudents.section_id = '$section_id' group by classroom.link,classroom.created_at order by classroom.classroom_active desc , classroom.lecture_id asc ")->result();  	 
  // print_r($timetables);
  // print_r($listActiveClass);die;
     if (!empty($listActiveClass)){
         
       $barray = array();
       $a=1 ;
            foreach ($timetables['day'] as $key1 => $tt) {   
                    
                      foreach ($listActiveClass as $key => $ac) {

                        if($tt->period_id == $ac->lecture_id){                      
                            $tt->activeClass = $ac;
                            $a =2;
                        }     
                      //  $barray[] = $tt;                   
                    }    
                    if($a==1)  
                     $tt->activeClass = new stdClass();
                    else{
                      $a=1 ;
                     }
          }

       $data['newtimetable'] = $timetables;
     //  $data['activeClassList'] = $listActiveClass;
     //  $data['timetable'] = $timetable;
       $data['status'] = true;
       $data['msg'] = "Active Class found"; 
     }else{
      $data['timetable'] = $timetable;
      $data['status'] = true;
      $data['msg'] = "No Active Classroom Found"; 
      $data['activeClassList'] = array();
     }       
 }               
 else{
    $data['status'] = false;
    $data['msg'] = "Post Data not found";         
   }
   echo json_encode($data); 
}
public function generatechecksumClassroom($studentName,$meetingid,$password)
{
  $string = sha1("joinfullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=trueVX4pnXv8XOGpD07ssrbPWqkRthbYq8S581wA6kd5uxU");
  return $string ;
} 
 */
public function generatechecksumClassroom($studentName,$meetingid,$password)
{
 // $string = sha1("joinfullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=trueVX4pnXv8XOGpD07ssrbPWqkRthbYq8S581wA6kd5uxU");
 $string = sha1("joinfullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true".$this->roomSecretKet);

  return $string ;
}
public function activeRoomListnew(){    
  $id = $this->input->post('studentid');
  $class_id = $this->input->post('class_id');
  $section_id = $this->input->post('section_id');
  $todaydate = date('d-m-Y');
 if (!empty($id)) 
  {  
     $listActiveClass = $this->db->query("SELECT classroom.* from classroom where  classroom.date = '$todaydate' and classroom.class_id = '$class_id'  
     group by classroom.link,classroom.created_at order by classroom.classroom_active desc , classroom.lecture_id asc ")->result();  	 
     if (!empty($listActiveClass)){  
       $data['newtimetable'] = $listActiveClass;
       $data['status'] = true;
       $data['msg'] = "Active Class found"; 
     }else{
      $data['status'] = true;
      $data['msg'] = "No Active Classroom Found"; 
      $data['activeClassList'] = array();
     }       
 }               
 else{
    $data['status'] = false;
    $data['msg'] = "Post Data not found";         
   }
   echo json_encode($data); 
}


public function joinRoom(){  
  $videoId = $this->input->post('videoId');  
  $studentId = $this->input->post('studentId');  
  $currenttime = $this->input->post('currenttime');  
  if (!empty($videoId)) 
  {  
     $classdetails=  $this->db->query("SELECT CONCAT(students.firstname,students.lastname) as stu_name,classroom.classroom_active,classroom.link, classroom.end_classAt,classroom.startdate,classroom.starttime,
     classroom.exceedTimeValue
     from classroom       
     join students on students.id = $studentId
     where classroom.id = '$videoId' and classroom.classroom_active = 1")->row();
     $meetingid = $classdetails->link;
     $studentName = preg_replace('/\s+/', '', $classdetails->stu_name);
     $password = "Students@123";
     $checksum = $this->generatechecksumClassroom($studentName,$meetingid,$password);   
    if(!empty($classdetails)){ 
           $data['status'] = true;
           $data['msg'] = "Joined Active Class Successfully"; 
           $data['status'] = true;            
          // $data['url'] = $urllll = "https://room.classeye.in/bigbluebutton/api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
           $data['url'] = $urllll =$this->roomPath."api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
           $data['msg'] = "Joined Active Class Successfully";                        
    }else{
      $data['status'] = false;
      $data['msg'] = "Unable to join Classroom";         
    }          
 }               
 else{
    $data['status'] = false;
    $data['msg'] = "Post Data not found";         
   }

   echo json_encode($data); 
}

public function joinRoom_old(){  
  $videoId = $this->input->post('videoId');  
  $currenttime = $this->input->post('currenttime');  
  if (!empty($videoId)) 
  {  

     $classdetails=  $this->db->query("SELECT classroomStudents.*,CONCAT(students.firstname,students.lastname) as stu_name,classroom.classroom_active,classroom.link, classroom.end_classAt,classroom.startdate,classroom.starttime,
     classroom.exceedTimeValue
     from classroomStudents 
     join classroom on classroom.id = classroomStudents.classroom_id 
     join students on classroomStudents.student_id = students.id 
     where classroomStudents.id = '$videoId' and classroom.classroom_active = 1 and students.is_active = 'yes'")->row();
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
           }else{

           $selectedTime = $classdetails->starttime;
           $endTime = strtotime("+$classdetails->exceedTimeValue minutes", strtotime($selectedTime));
           $newtime =  date('h:i:s', $endTime);        
          // if($newtime <= $currenttime ){
            $this->db->where('id',$videoId);
            $this->db->update('classroomStudents',array('joined'=>1));
            $data['status'] = true;            
            $data['url'] = $urllll = $this->roomPath."api/join?fullName=".$studentName."&meetingID=".$meetingid."&password=".$password."&redirect=true&checksum=".$checksum;
            $data['msg'] = "Joined Active Class Successfully"; 
            $this->db->where('id',$videoId);
            $this->db->update('classroomStudents',array('joined'=>1,'classroomlink'=>$urllll));
         /*   }else{
            $data['status'] = false;
            $data['msg'] = "Exceed the time limit to join Classroom";     
           } */
        }          
    }else{
      $data['status'] = false;
      $data['msg'] = "Unable to join Classroom | Must Logout and Try Login Again";         
    }
          
 }               
 else{
    $data['status'] = false;
    $data['msg'] = "Post Data not found";         
   }

   echo json_encode($data); 
}
public function reJoinRoom(){  
  $videoId = $this->input->post('videoId');   
  if (!empty($videoId)) 
  {  
    $classroom = $this->db->query("SELECT classroomStudents.* from classroomStudents join classroom on classroomStudents.classroom_id = classroom.id where classroomStudents.id = '$videoId' and classroom.classroom_active = 1 ")->row();
    if(!empty($classroom)){
      $data['url'] = $classroom->classroomlink ;
      $data['Status'] = true;
      $data['Msg'] = "Url found You Can Rejoin";  
    }else{
      $data['Status'] = false;
      $data['Msg'] = "Class is Not Active";         
    }
      
  }else{    
        $data['Status'] = false;
        $data['Msg'] = "Post Data not found";         
       }
  
     echo json_encode($data); 
   
}

public function check_isactive()
{
  $url =  $this->input->post('url');    
   if(!empty($url)){   
     $result =  $this->db->query("SELECT id from classroomdata where link =  '$url' and classroom_active = 1 ")->row();
    if (!empty($result)) {
      $outputarr['Status'] =true; 
      $outputarr['Msg'] = "Class Room Active And Genuine";
    }else{
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No Class Room Found";
    }  
  }else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Failed To Found Post Data";
      }  
    echo json_encode($outputarr); 
}


public function holidays()
{
  $holidays = $this->db->query("SELECT name, date from school_holidays")->result();
  if(!empty($holidays)){
    $outputarr['holidays'] = $holidays; 
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "holidays list found";
  }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "no holidays found";
  }
  echo json_encode($outputarr); 
}


public function updateAppSeen()
{
  $id = $this->input->post('student_id');
  if(!empty($id)){
    $date_seen=date('Y-m-d h:i:s');
    $this->db->query("update students set app_seen_date='$date_seen' where id = $id");
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "App Seen Updated";
  }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Fail To Update App Seen";
  }
  echo json_encode($outputarr); 
 
}


public function GatePassReason()
{
  $reasons = $this->db->query("SELECT id, reason from gatepass_reason where 1")->result();
  if(!empty($reasons)){
 
    $outputarr['result'] = $reasons; 
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "Data Found";
  }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Data Not Found";
  }
  echo json_encode($outputarr); 
}

/* Onlline test module starts here  */

/* exams list */
public function onlineTestList()
{
    $class_id = $this->input->post('class_id');
    $student_id = $this->input->post('student_id');
    $abcd =  array();
    if(!empty($class_id)){
      $date = date('Y-m-d');
        $examList = $this->db->query("SELECT onlineexams.id,onlineexams.examname,onlineexams.class_id,onlineexams.subject,onlineexams.topic, 
        onlineexams.max_attempt,onlineexams.startDate,onlineexams.endDate,onlineexams.note,onlineexams.time from onlineexams where class_id = $class_id and startDate = '$date' ")->result();
        if(!empty( $examList)){
           foreach ($examList as $key => $examL) {    
                     $QUESCOUNT = $this->db->query("SELECT count(examquestions.id) as questioncount from examquestions where exam_id = $examL->id")->row()->questioncount;                   
                     $Sattempts = $this->db->query("SELECT count(onlineexamsresult.id) as rescount from onlineexamsresult  where onlineexamsresult.student_id = $student_id and onlineexamsresult.exam_id = $examL->id ")->row()->rescount;
                      
                     $attempts =    $Sattempts /   $QUESCOUNT;  
                                        
                     if(($attempts > 0 )){
                          $examL->previousresult = '';
                        if($attempts  < $examL->max_attempt )
                          $examL->attemptremain  = $examL->max_attempt - $attempts ;
                        else  
                          $examL->attemptremain  = 0 ;
                    }else{                        
                          $examL->attemptremain  =  $examL->max_attempt ;
                          $examL->previousresult = $abcd ; 
                         }    
                         if( $examL->attemptremain > 0 )
                            $new_examList[] =    $examL; 
          }
          $outputarr['examlist'] = $new_examList; 
          $outputarr['Status'] = 1; 
          $outputarr['Msg'] = "Exam Found";
         }else{
          $outputarr['Status'] = 0; 
          $outputarr['Msg'] = "Exam Not Found";
         }      
    }else{
         $outputarr['Status'] = 0; 
         $outputarr['Msg'] = "Class Id Not Found";
         }
    echo json_encode($outputarr); 
}


public function examQuestions()
{
  $exam_id = $this->input->post('exam_id');
    if(!empty($exam_id)){
      $quesList = $this->db->query("SELECT examquestions.* from examquestions where exam_id = $exam_id")->result();
      if(!empty($quesList)){
        $outputarr['quesList'] = $quesList; 
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Exam Question List Found";
      } else{
        $outputarr['Status'] = 0; 
        $outputarr['Msg'] = "No Question Listed for this Exam";
      }       
  }else{
        $outputarr['Status'] = 0; 
        $outputarr['Msg'] = " Exam Id Not Found";
       }
echo json_encode($outputarr); 
}

public function saveResult()
{
 $exam_result = json_decode($this->input->post('exam_result'));        
  if(!empty($exam_result)){  
      foreach($exam_result as $key => $value) {           
        $res['exam_id'] = $value->examId;
        $res['student_id	'] = $value->user_id;
        $res['ques_id'] = $value->ques_id;
        $res['student_answer'] = $value->student_answer;
        $res['correct_answer'] = $value->correct_answer;
        $res['obt_mark'] = $value->result;
        $res['max_marks'] = 1;
        $this->db->insert('onlineexamsresult',$res);            
      }          
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "Marks saved successfully"; 
}else{
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = " Student Id Not Found";
     }
   echo json_encode($outputarr); 
}



public function viewResult()
{
 $exam_id = $this->input->post('exam_id');
 $student_id = $this->input->post('student_id');

  if(!empty($student_id) && !empty($exam_id)){         
      $resList = $this->db->query("SELECT onlineexamsresult.*,examquestions.question, examquestions.a,examquestions.b ,examquestions.c ,examquestions.d from onlineexamsresult
           join examquestions on examquestions.id = onlineexamsresult.ques_id where onlineexamsresult.exam_id = $exam_id and onlineexamsresult.student_id = $student_id")->result();
         //  print_r($this->db->last_query()); die;
    if(!empty($resList)){
      $outputarr['ResultList'] = $resList; 
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Result Found";
    } else{
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "No Result Listed for this Exam";
    }   
}else{
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = " Student Id Not Found";
     }
   echo json_encode($outputarr); 
}
//===========================Online test module ends ================================//
  

public function gmeetList()
{
  $mydate = date('Y-m-d');
 $day=date('l', strtotime($mydate)); 
  $class_id = $this->input->post('class_id'); 
  $section_id = $this->input->post('section_id'); 
  if(!empty($class_id )){
   if($section_id)
    $cond = "class_id = $class_id and section_id = $section_id and DATE_FORMAT(`class_date`, '%W') ='$day' " ;
    else{
      $cond = "class_id = $class_id and DATE_FORMAT(`class_date`, '%W') ='$day'";
    }
        $conf = $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where $cond")->result();
        if(!empty($conf)){         
          $outputarr['Classes'] = $conf; 
          $outputarr['Status'] =1; 
          $outputarr['Msg'] = "Classes Found";
        }else{
          $outputarr['Status'] =0; 
          $outputarr['Msg'] = "No Classes Found";
          }
     
      }else{
        $outputarr['Status'] = 0; 
        $outputarr['Msg'] = " Class Id Not Found";
       }
        echo json_encode($outputarr);   
}

//===========================end class================================//
     
 } 
 
?>