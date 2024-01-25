  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  //defined('BASEPATH') OR exit('No direct script access allowed');

  class Angular_api extends CI_Controller {
    public function __construct() {
      parent::__construct(); 
      $this->load->model('Api_model');
      $this->load->model('Proxy_model');
      $this->load->model('Messages_model');
      $this->load->model('Studentfeemaster_model');
      $this->load->library('Enc_lib'); 
      $this->load->library('form_validation'); 
        $this->loopCount = 0 ; 
     
    }
    public function staff_login() {
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
       $data = array(
        'username' => $request->user,
        'password' => $request->pass,
        'roles' => $request->role
      );

       $row = $this->Api_model->software_login($data);
       if ($row != false) {
        $session = $this->setting_model->getCurrentSession();
        $outputarr['currentSession'] = $session ;
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "login Successfully";
        $outputarr['Result'] = $row ;
      } else {

        $outputarr['Status'] = 0; 
        $outputarr['Msg'] = "Invalid User";
      }
    }else{
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Invalid User";
   }

   echo json_encode($outputarr);
  }

  public function gatepass_list($value='')
  {
    $postdata = file_get_contents("php://input");
    // $postdata =  $this->input->post('data'); 
    $page = 0 ;
    if ($postdata != '') {   

      $request = json_decode($postdata);
      $page = $request->page ;
    }      

    $result = $this->Api_model->get_Gatepass_list($page);
    if (!empty($result)) {
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Success";
      $outputarr['Result'] = $result ;
    } else { 
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "No Record Found";
    }  
    echo json_encode($outputarr);
  }

  public function get_student_gp()
  {
    $postdata = file_get_contents("php://input");
       
    if ($postdata != '') {   

      $request = json_decode($postdata);
      $rollno = $request->rollno ;
      if ( $rollno != '') { 
        $result = $this->Api_model->get_student_gp($rollno);
      }

      if (!empty($result)) {
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Success";
        $outputarr['Result'] = $result ;
      } else { 
        $outputarr['Status'] = 0; 
        $outputarr['Msg'] = "No Record Found";
      }  
    }  else { 
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "No Record Found";
    }     
    echo json_encode($outputarr);
  }

  public function add_gatepass()
  {
   $postdata = file_get_contents("php://input");
              //$postdata =  $this->input->post('data');  
   if ($postdata != '') {     
     $request = json_decode($postdata);  
     if(!empty($request->guardian_image))
     { 
      $base = $request->guardian_image;
      $file_name='guardian' ;
      $file_name.=date("Ymdhis"); 
              // Decode Image
      $binary=base64_decode($base);
      header('Content-Type: bitmap; charset=utf-8');
      $file = fopen('uploads/student_images/'.$file_name.'.jpg', 'wb');
              // Create File
      fwrite($file, $binary);
      fclose($file);
      $path = "uploads/student_images/".$file_name.'.jpg';

             // $this->Api_model->update_student($data);
              //=========== Insert ================//         
    } 
    $add['mobile_no'] = $request->mobile_no ;
    $add['relation'] = $request->relation ;
    $add['reason'] = $request->reason ;
    $add['student_id'] = $request->student_id ;
    $add['class'] = $request->class ;
    $add['section'] = $request->section ;
    $add['receiver_name'] = $request->receiver_name ;
    $add['father_name'] = $request->father_name ;
    $add['mother_name'] = $request->mother_name ;
    $add['student_name ']= $request->student_name ;
    $add['guardian_image'] = $path; 
    $add['student_image'] = $request->student_image;
    $add['parent_id'] = $request->parent_id;            
    $add['datetime ']= $request->datetime ;   
    $result = $this->Api_model->add_gatepass($add);

    if ($result > 0) {
      $return = $this->Api_model->get_Gatepass_by_id($result);
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Successfully Add gatepass";
      $outputarr['Result'] = $return;


    } else { 
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "Failed To add";
    }  
  }  else { 
    $outputarr['Status'] = 0; 
    $outputarr['Msg'] = "Post data Not Found";
  }     

  echo json_encode($outputarr);
  }



  public function add_tc_data()
  {
        $postdata = file_get_contents("php://input");

       
        if(!empty($postdata)){
          $request1 = json_decode($postdata);  
          if(!empty($request1))
           { 
              $data['admission_no']= ($request1->admission_no)?$request1->admission_no:0;
              $data['firstname']= ($request1->firstname)?$request1->firstname:0 ;
              $data['lastname']= ($request1->lastname)?$request1->lastname:0;
              $data['father_name']= ($request1->father_name)?$request1->father_name:0 ;
              $data['mother_name']= ($request1->mother_name)?$request1->mother_name:0 ;
              $data['nationality']=( $request1->nationality)?$request1->nationality:0 ;
              $data['admission_date']=( $request1->admission_date )? $request1->admission_date :0;
              $data['subject1']= ($request1->subject1)?$request1->subject1:0 ;      
              $data['subject2']= ($request1->subject2 )?$request1->subject2:0 ;      
              $data['subject3']= ($request1->subject3)?$request1->subject3:0 ;      
              $data['subject4']= ($request1->subject4)?$request1->subject4:0 ;      
              $data['subject5']=( $request1->subject5 )? $request1->subject5 :0;   
              $data['subject6']=( $request1->subject6 )? $request1->subject6 :0; 
              $data['booking_no']= ($request1->booking_no)?$request1->booking_no:0 ;   
              $data['s_i_no']= ($request1->s_i_no)?$request1->s_i_no:0 ;  
              $data['application_no']= ($request1->application_no)?$request1->application_no:0 ; 
              $data['gender']= ($request1->gender)?$request1->gender:'' ;
              $data['checkScSt']= ($request1->checkScSt)?$request1->checkScSt:0 ;         
              $data['class']= ($request1->class )?$request1->class :0;
              $data['section']= ($request1->section )?$request1->section :0;         
              $data['lastResult']= ($request1->lastResult) ?$request1->lastResult:0;
              $data['ifQualified']= ($request1->ifQualified)?$request1->ifQualified:0 ;
              $data['generalConduct']= ($request1->generalConduct)?$request1->generalConduct:0 ;
              $data['qualifiedClass']= ($request1->qualifiedClass)?$request1->qualifiedClass:0 ;
              $data['schoolDuesPaid']= ($request1->schoolDuesPaid)?$request1->schoolDuesPaid:0 ;
              $data['ifFailed']= ($request1->ifFailed)?$request1->ifFailed:0 ;
              $data['ifNcc']= ($request1->ifNcc)?$request1->ifNcc:0 ;            
              $data['gamePlayed']= ($request1->gamePlayed)?$request1->gamePlayed:0 ;
              $data['achievementLevel']= ($request1->achievementLevel)?$request1->achievementLevel:0 ; 
              $data['dob']= ($request1->dob)?$request1->dob:0 ; 
              $data['workingDays']= ($request1->workingDays)?$request1->workingDays:0 ;
              $data['daysPresent']= ($request1->daysPresent)?$request1->daysPresent:0 ;
              $data['dateOfApplication']= ($request1->dateOfApplication)?$request1->dateOfApplication:0 ;
              $data['dateOfIssue']=( $request1->dateOfIssue)? $request1->dateOfIssue:0 ;
              $data['reasonForLeaving']=( $request1->reasonForLeaving )? $request1->reasonForLeaving:0;
              $data['conceesionFeeNature']= ($request1->conceesionFeeNature)?$request1->conceesionFeeNature:0 ;     
              $data['otherRemarks']= ($request1->otherRemarks)?$request1->otherRemarks:0 ;     
  
            $exist  = $this->db->query("Select * from tc_data where  admission_no = $request1->admission_no")->row();
            if(!empty($exist)){
            $this->db->where('admission_no',$request1->admission_no);
            $this->db->update('tc_data',$data);
           
            }
            else
            $this->db->insert('tc_data',$data);
            $outputarr['Status'] = 1; 
            $outputarr['Msg'] = "Data Added Successfully";
           }
           else
           {
            $outputarr['Status'] = 0; 
            $outputarr['Msg'] = "Request data Not Found";
           }
     }
     else
     {
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "Post data Not Found";
     }

       $outputarr['pranav'] = $this->db->last_query();
     echo json_encode($outputarr);
    }


    
  public function add_tc_data_old()
  {
        $postdata = file_get_contents("php://input");

        // file_put_contents('pranav.html', $postdata);
        if(!empty($postdata)){
          $request1 = json_decode($postdata);  
          if(!empty($request1))
           { 
            $data['admission_no']= ($request1->admission_no)?$request1->admission_no:0;
            $data['first_name']= ($request1->firstname)?$request1->firstname:0 ;
            $data['last_name']= ($request1->lastname)?$request1->lastname:0;
            $data['father_name']= ($request1->father_name)?$request1->father_name:0 ;
            $data['mother_name']= ($request1->mother_name)?$request1->mother_name:0 ;
            $data['phone']=( $request1->mobileno)? $request1->mobileno:0 ;
            $data['address']= ($request1->permanent_address)?$request1->permanent_address:0 ;
            $data['nationality']=( $request1->nationality)?$request1->nationality:0 ;
            $data['isStSc']= ($request1->checkScSt)?$request1->checkScSt:0 ;
            $data['admission_date']=( $request1->admission_date )? $request1->admission_date :0;
            $data['last_class']= ($request1->class_id )?$request1->class_id :0;
            $data['last_result']= ($request1->lastResult) ?$request1->lastResult:0;
            $data['isfailed']= ($request1->ifFailed)?$request1->ifFailed:0 ;
            $data['is_qualified']= ($request1->ifQualified)?$request1->ifQualified:0 ;
            $data['qualified_in_class']= ($request1->qualifiedClass)?$request1->qualifiedClass:0 ;
            $data['lastduepaid']= ($request1->schoolDuesPaid)?$request1->schoolDuesPaid:0 ;
            $data['totalDays']= ($request1->workingDays)?$request1->workingDays:0 ;
            $data['presentDays']= ($request1->daysPresent)?$request1->daysPresent:0 ;
            $data['isncc']= ($request1->ifNcc)?$request1->ifNcc:0 ;
            $data['game']= ($request1->gamePlayed)?$request1->gamePlayed:0 ;
            $data['gender']= ($request1->gender)?$request1->gender:'' ;
            $data['general_conduct']= ($request1->generalConduct)?$request1->generalConduct:0 ;
            $data['apply_date']= ($request1->dateOfApplication)?$request1->dateOfApplication:0 ;
            $data['issue_date']=( $request1->dateOfIssue)? $request1->dateOfIssue:0 ;
            $data['reason']=( $request1->reasonForLeaving )? $request1->reasonForLeaving:0;
            $data['remark']=( $request1->remark )? $request1->remark:0;
            $data['subject1']= ($request1->subject1)?$request1->subject1:0 ;      
            $data['subject2']= ($request1->subject2 )?$request1->subject2:0 ;      
            $data['subject3']= ($request1->subject3)?$request1->subject3:0 ;      
            $data['subject4']= ($request1->subject4)?$request1->subject4:0 ;      
            $data['subject5']=( $request1->subject5 )? $request1->subject5 :0;   
            $data['booking_no']= ($request1->booking_no)?$request1->booking_no:0 ;      
            $data['s_i_no']= ($request1->s_i_no)?$request1->s_i_no:0 ;      
            $data['application_no']= ($request1->application_no)?$request1->application_no:0 ; 
            $exist  =   $this->db->query("Select * from issue_tc_data where  admission_no = $request1->admission_no")->row();
            if(!empty($exist)){
            $this->db->where('admission_no',$request1->admission_no);
            $this->db->update('issue_tc_data',$data);
           
            }
            else
            $this->db->insert('issue_tc_data',$data);
            $outputarr['Status'] = 1; 
            $outputarr['Msg'] = "Data Added Successfully";
           }
           else
           {
            $outputarr['Status'] = 0; 
            $outputarr['Msg'] = "Request data Not Found";
           }
     }
     else
     {
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = "Post data Not Found";
     }
     echo json_encode($outputarr);
    }


  public function delete_gp()
  {
   $postdata = file_get_contents("php://input");
   if ($postdata != '') {     
     $request = json_decode($postdata);
     $id = $request->id ;
     $this->Api_model->delete_gatepass($id);
     $outputarr['Status'] = 1; 
     $outputarr['Msg'] = "Deleted Successfully";
   }  else { 
    $outputarr['Status'] = 0; 
    $outputarr['Msg'] = "Failed To delete";
  }     
   echo json_encode($outputarr);

  }
  //------------------------------GATE PASS aPI eNDS HERE ----------------------------------//

  //------------------------------FEES SOFTWARE aPI START FROM HERE ----------------------------------//

  public function Feestaff_login() {

    $postdata = file_get_contents("php://input");
      // $postdata =  $this->input->post('data');
    if ($postdata != '')
    {            

     $request = json_decode($postdata);
     $data = array(
       'username' => $request->user,
       'password' => $request->pass,
       'roles' => $request->role,

     );     
     $row = $this->Api_model->software_login($data);

     if ($row != false) {
      $todate = date("Y-m-d");
         $current_date  = $this->db->query("SELECT next_date from datestatus order by id desc limit 1 ")->row()->next_date;
       if ($todate > $current_date) 
         {
           $current_date =  $todate  ;
          } 
       $session = $this->setting_model->getCurrentSession();
       $qry = "SELECT * from sessions where id = $session";
       $sess= $this->db->query($qry)->row();
       $outputarr['currentSession'] = $sess ;
       $outputarr['date'] = $current_date ;
       $outputarr['Status'] = 1; 
       $outputarr['Msg'] = "login Successfully";
       $outputarr['Result'] = $row ;
     } else {

       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Invalid User";
     }
   }else{
    $outputarr['Status'] = 0; 
    $outputarr['Msg'] = "Invalid User";
  }

  echo json_encode($outputarr);


  }


  public function All_student() {
    $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data'); 
    if ($postdata != ''){     
      ///$outputarr['da'] = $postdata ; 

      $request = json_decode($postdata);
      $session_id = $request->session_id ;     

      if ($session_id) {      
        $data = $this->Api_model->get_all_student($session_id);         
        if (!empty($data)) {
         $outputarr['Status'] = 1; 
         $outputarr['Msg'] = " Student data Available";
         $outputarr['Result'] = $data ;
       } else {
         $outputarr['Status'] = 0; 
         $outputarr['Msg'] = "No Student Available";
       }
     }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Please Select Session First";
     }
   }else{
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Post Data Not Found";
   }     
   echo json_encode($outputarr);


  }



  public function search()
  {
       $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data'); 
        // $postdata  = 'as';
    if ($postdata != ''){     
      $request = json_decode($postdata);
      $text = $request->text ;
      $session_id = $request->session_id ;
      
        $result = $this->Api_model->get_search_result($text,$session_id);
        $house_list = $this->Api_model->get_house();
   
      if (!empty($result))
      {
       $outputarr['Result'] = $result ;
       $outputarr['house_list'] = $house_list ;
       $outputarr['Status'] = 1; 
       $outputarr['Msg'] = "Result Found";
     }
     else { 
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed To Search 1";
     }     
   }  
   else { 
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Failed To Search 2";
   }     
   echo json_encode($outputarr);
  }

 public function houseList()
 {
  $house_list = $this->Api_model->get_house();
  if (!empty($house_list)) {
    $data['houses'] = $house_list; 
    $data['msg']='Data Found';
    $data['status']=1;
  }else{
    $data['msg']='No Data Found';
    $data['status']=0;

  }
  echo json_encode($data);
 }

  public function fee_details()
  { 
    $postdata = file_get_contents("php://input");
  if ($postdata != ''){     
      $request = json_decode($postdata);
      $id = $request->id ;
      $session_id = $request->session_id ;
      $student = $this->Student_model->api_get($id,$session_id);   
      if($student['promoted'] == '1'){
        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
      }else{
        $student_due_fee = $this->studentfeemaster_model->getStudentFees_newAdmisssion($student['student_session_id']);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
      }    
      $date1 = date('Y-m-d') ;
      $qry =  "select next_date from datestatus where dl_date ='".$date1."' and status = 1 ;" ; 
      $date = $this->db->query($qry)->row();
      if ($date != '') {
        $feeA['feesdate'] = $date->next_date ;
      }else{
       $feeA['feesdate'] = $date1 ;
     }
    
     $feeA['LateFee'] = 50;
     $feeA['student'] = $student;
     $feeA['student_discount_fee'] = $student_discount_fee;
     $feeA['student_due_fee'] = $student_due_fee;
         //print_r( $student_due_fee); die;
     $main_arr=array();

     foreach ($student_due_fee as $key => $fee)
     {          

      $obj1  = array() ;
      foreach ($fee->fees as $fee_key => $fee_value) 
      {    

       if (!empty($fee_value->amount_detail)) {

         $amount_detail = json_decode(($fee_value->amount_detail)); 
         foreach ($amount_detail as $key => $value) {
          $fee_value->amount_detail=$value;
        }

      }

      $obj1[$fee_key] = $fee_value ; 
      $amount_detail1=$obj1;

    }
                                            //main foreach
    $fee->fees = $amount_detail1 ;
    $main_arr[]=$fee;                                       
    $fee=array();                                       
  }  

  $feeA['student_due_fee']=$main_arr;
  if (!empty($feeA))
  {
   $outputarr['Result'] = $feeA ;
   $outputarr['Status'] = 1; 
   $outputarr['Msg'] = "Result Found";
  }
  else { 
   $outputarr['Status'] = 0; 
   $outputarr['Msg'] = "Failed To Search 1";
  }     
  }  
  else { 
   $outputarr['Status'] = 0; 
   $outputarr['Msg'] = "Failed To Search 2";
  }     
  echo json_encode($outputarr);
  }





  public function add_fee()
  {    
   $postdata = file_get_contents("php://input");
    // $postdata =  $this->input->post('data');  
   if ($postdata != '' ){     
    $request = json_decode($postdata);
    if(!empty($request)){
      $request = $request->PaymentDetails ;
      $count = 0 ;
      $fee_receipt_no= $this->Api_model->feeereceipt_no();
      foreach($request as $val){     
            $collected_by = " Collected By:Accountant ";// . $this->customlib->getAdminSessionUserName();
            if (isset($val->student_fees_discount_id)) {
             $student_fees_discount_id = $val->student_fees_discount_id;
           }else{
            $student_fees_discount_id = 0 ;
          }     
          $json_array = array(
            'amount' => $val->amount,
            'date' => date('Y-m-d',strtotime($val->date)),
            'amount_discount' => $val->amount_discount,
            'amount_fine' => $val->amount_fine,
            'description' => $val->description . $collected_by,
            'payment_mode' => $val->payment_mode
          );
          $data = array(
            'student_fees_master_id' => $val->student_fees_master_id,
            'fee_groups_feetype_id' => $val->fee_groups_feetype_id,
            'amount_detail' => $json_array,
            'receipt_no' => $fee_receipt_no
          );
          $inserted_id = $this->Api_model->fee_deposit($data, $student_fees_discount_id);
          if($inserted_id) {           
          $outputarr['Result'][] = $inserted_id ; 
          $outputarr['Status'] = 1; 
          $outputarr['receipt_no'] =$fee_receipt_no; 
          $outputarr['Msg'] = "Fess Deposit";
         } else{
           $count++ ; 
         }
       }

       if ($count > 0 ) {
             $outputarr['Status'] = 0; 
             $outputarr['Msg'] = "Failed To Add Fee Please Contact To admin";
         }  

      }
      else { 
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed";
     } 

   }  
   else {  
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Failed";
   }     
   echo json_encode($outputarr);
  }

  public function add_fee_old()
  {    
   $postdata = file_get_contents("php://input");
    // $postdata =  $this->input->post('data');  

   if ($postdata != '' ){     
    $request = json_decode($postdata);
    if(!empty($request)){
      $request = $request->PaymentDetails ;

      $fee_receipt_no= $this->Api_model->feeereceipt_no();
      foreach($request as $val){     
            $collected_by = " Collected By:Accountant ";// . $this->customlib->getAdminSessionUserName();
            if (isset($val->student_fees_discount_id)) {
             $student_fees_discount_id = $val->student_fees_discount_id;
           }else{
            $student_fees_discount_id = 0 ;
          }     
          $json_array = array(
            'amount' => $val->amount,
            'date' => date('Y-m-d',strtotime($val->date)),
            'amount_discount' => $val->amount_discount,
            'amount_fine' => $val->amount_fine,
            'description' => $val->description . $collected_by,
            'payment_mode' => $val->payment_mode
          );
          $data = array(
            'student_fees_master_id' => $val->student_fees_master_id,
            'fee_groups_feetype_id' => $val->fee_groups_feetype_id,
            'amount_detail' => $json_array,
            'receipt_no' => $fee_receipt_no
          );
          $inserted_id = $this->Api_model->fee_deposit($data, $student_fees_discount_id);
          $outputarr['Result'][] = $inserted_id ; 
          $outputarr['Status'] = 1; 
          $outputarr['receipt_no'] =$fee_receipt_no; 
          $outputarr['Msg'] = "Fess Deposit";

        }

      }
      else { 
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed";
     } 

   }  
   else {  
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Failed";
   }     
   echo json_encode($outputarr);
  }




  public function dailyReport()
  {   
   $postdata = file_get_contents("php://input");
    //$postdata = '{"date_from":"2019-03-30","date_to":"2019-04-15","session_id":"14"}';
   if ($postdata != '' ){   

    $request1= json_decode($postdata);
    if(!empty($request1)){
      $date_from = date("d-m-Y",strtotime($request1->date_from)) ;     
      $date_to = date("d-m-Y",strtotime($request1->date_to)) ;    
      $date_f = date('d/m/Y',strtotime($date_from));
      $session_id = $request1->session_id ;
      //print_r($session_id); die;
      $qry =  "select status from datestatus where dl_date like '".$date_f."' " ; 
      $print = $this->db->query($qry)->row();

      if (!empty($print)) {

        if ($print->status == 1 ) {
         $print = 'false' ;
       }else
       { 
        $print = 'true' ; 
      }
    }  
    else  $print = 'true' ;  
    $feelist_amount=0; 
     $feelist_revert_amount=0;
     $feelist_expenses_amount=0;
    $feeList = $this->studentfeemaster_model->getFeeBetweenDateapi($date_from, $date_to,$session_id); 	
    if(!empty($feeList)){
        
        foreach ($feeList as $value) {
        $feelist_amount+=  $value['amount'];
        }

    }



    $feeList_revert=$this->studentfeemaster_model->getrevertFeeBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to)));


    if(!empty($feeList_revert)){
      
        foreach ($feeList_revert as $value) {
                $value1=    json_decode($value->amount_details,true);

        $feelist_revert_amount+=  $value1['amount'];
        }

    }

     // echo $this->db->last_query(); die;
    $feeList_expenses =$this->studentfeemaster_model->getexpenseBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to))); 

       if(!empty($feeList_expenses)){
       
        foreach ($feeList_expenses as $value) {
        $feelist_expenses_amount+=  $value->amount;
        }

    }

    if (!empty($feeList)) {
      $outputarr['feesdeatils'] = $feeList ; 
      $outputarr['feesdeatils_revert'] = $feeList_revert ; 
      $outputarr['feesdeatils_expenses'] = $feeList_expenses ; 
      $outputarr['feelist_amount'] = $feelist_amount ; 
      $outputarr['feelist_revert_amount'] = $feelist_revert_amount ; 
      $outputarr['feelist_expenses_amount'] = $feelist_expenses_amount ; 

      $outputarr['print'] = $print ;  
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Fees Details for a particular  date ";
    }
    else {        
      $outputarr['Status'] = 0; 
      $outputarr['Msg'] = 'No Result Found' ;
    } 

  }
  else {  
   $outputarr['Status'] = 0; 
   $outputarr['Msg'] = "Failed 1";
  }   
  }
  else {  
   $outputarr['Status'] = 0; 
   $outputarr['Msg'] = "Failed 2";
  }   
  echo json_encode($outputarr);
  }

  
public function dailyReport_downloadMessage()
{   
 $postdata = file_get_contents("php://input");
  //$postdata = '{"date_from":"2019-03-30","date_to":"2019-04-15","session_id":"14"}';
 if ($postdata != '' ){   

  $request1= json_decode($postdata);
  if(!empty($request1)){
    $date_from = date("d-m-Y",strtotime($request1->date_from)) ;     
    $date_to = date("d-m-Y",strtotime($request1->date_to)) ;    
    $date_f = date('d/m/Y',strtotime($date_from));
    $session_id = $request1->session_id ;
    //print_r($session_id); die;
    $qry =  "select status from datestatus where dl_date like '".$date_f."' " ; 
    $print = $this->db->query($qry)->row();

    if (!empty($print)) {

      if ($print->status == 1 ) {
       $print = 'false' ;
     }else
     { 
      $print = 'true' ; 
     }
  }  
  else  $print = 'true' ;  
  $feelist_amount=0; 
   $feelist_revert_amount=0;
   $feelist_expenses_amount=0;
  $feeList = $this->studentfeemaster_model->getFeeBetweenDateapi($date_from, $date_to,$session_id);   
  if(!empty($feeList)){
      
      foreach ($feeList as $value) {
      $feelist_amount+=  $value['amount'];
      }

  }
  $feeList_revert=$this->studentfeemaster_model->getrevertFeeBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to)));


  if(!empty($feeList_revert)){
    
      foreach ($feeList_revert as $value) {
              $value1=    json_decode($value->amount_details,true);

      $feelist_revert_amount+=  $value1['amount'];
      }

  }

   // echo $this->db->last_query(); die;
  $feeList_expenses =$this->studentfeemaster_model->getexpenseBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to))); 

     if(!empty($feeList_expenses)){
     
      foreach ($feeList_expenses as $value) {
      $feelist_expenses_amount+=  $value->amount;
      }

  }

  if (!empty($feeList)) {
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "Message sent successfully";
    /*save message for admin*/
    $msg_data1=array(
          'title'=>'Daily Fee Report',
          'message'=>"Dear Admin (CAS JABALPUR), Daily Fees Report downloaded please tally the amount, Fee collection amount Rs. $feelist_amount, Fee Reverted amount Rs. $feelist_revert_amount, Total expenses Rs. $feelist_expenses_amount  .",
          'session_id'=>$session_id,
          'user_list'=> '9424900730' ,
          'student_session_id'=>0
        );
        $this->Messages_model->add($msg_data1);

          $msg_data2=array(
          'title'=>'Daily Fee Report',
         'message'=>"Dear Admin  (CAS JABALPUR), Daily Fees Report downloaded please tally the amount, Fee collection amount Rs. $feelist_amount, Fee Reverted amount Rs. $feelist_revert_amount, Total expenses Rs. $feelist_expenses_amount  .",
          'session_id'=>$session_id,
          'user_list'=> '9584295044' ,
          'student_session_id'=>0
        );
        $this->Messages_model->add($msg_data2);
        ///mailid : vasushukla09@gmail.com;
        $this->load->library('email');
          $msg="Dear Admin (CAS JABALPUR), Daily Fees Report downloaded please tally the amount, Fee collection amount Rs. $feelist_amount, Fee Reverted amount Rs. $feelist_revert_amount, Total expenses Rs. $feelist_expenses_amount  .";
          $config['charset'] = 'iso-8859-1';
          $config['newline'] = '\r\n';
          $config['mailtype'] = 'html';
          $config['wordwrap'] = TRUE;
          $this->email->initialize($config);
          $this->email->from('info@centralacademyjabalpur.com', 'Central Academy Jabalpur');
          $this->email->to('vasushukla09@gmail.com');
          //$this->email->to('pranavweb686@gmail.com');
          //$this->email->to('thewebguys@gmail.com'); 
          ///$this->email->reply_to($frommail);
          $this->email->subject($subject);
          $this->email->message($msg);
          $this->email->send();

  }
  else {        
    $outputarr['Status'] = 0; 
    $outputarr['Msg'] = 'No Result Found' ;
  } 

}
else {  
 $outputarr['Status'] = 0; 
 $outputarr['Msg'] = "Failed 1";
}   
}
else {  
 $outputarr['Status'] = 0; 
 $outputarr['Msg'] = "Failed 2";
}   
echo json_encode($outputarr);
}


  //daily report ends here

  ///-----------------------------------------------------------
  ///---------------------Admission process api's---------------
  ///-----------------------------------------------------------
  public function Reciept_number($value='')
  {  
    $data = $this->Api_model->get_admission_reciept(); 
    $class_list = $this->Api_model->get_class_list();

   //print_r( $class_list ); die;
    if(!empty($data)){
      $count = $data['datecount'] ;
      $date =  $data['ent_date'];
      $checkcount = $this->Api_model->checkcount($date);
          //print_r( $checkcount ); die;
      if ($checkcount['count'] > $count ) {
        $int_date = $date;
        $count = $data['datecount'] ;

      }else{ 
        $data1 = $this->Api_model->get_interview_next_detail($date); 
                 // print_r($this->db->last_query()); die;
        if(!empty($data1)){
          $int_date = $data1['int_date'] ;  
          $count = 0 ;  
        }else{
          $outputarr['Msg'] = 'No Date Available' ;  
          $outputarr['Status'] = 0 ;  
          echo json_encode($outputarr);
          die;
        }

      }       
      $outputarr['count'] = $count ;
      $outputarr['reciept_number'] = $data['reciept_number']+1;  
      $outputarr['class_list'] = $class_list; 
      $outputarr['ent_date'] = $int_date; 
      $outputarr['Status'] = 1 ;  
    } else {
      $data = $this->Api_model->get_interview_detail();
      $outputarr['count'] = 0 ;
      $outputarr['class_list'] = $class_list; 
      $outputarr['reciept_number'] = 1;  
      $outputarr['ent_date'] =$data['int_date'] ; 
      $outputarr['Status'] = 1 ;  
    }     

    echo json_encode($outputarr);
  }

  //for admission entrance add data
  //for admission entrance add data
  public function Add_admission_ent($value='')
  {  
   $postdata = file_get_contents("php://input");
        // $postdata =  $this->input->post('data');    
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {
       $data['appli_name'] =$request->name ;
       $data['father_name'] =$request->fname ;
       $data['class'] =$request->eclass ;
       $data['registration_form_no'] =$request->form_no ;
       $data['ent_date'] =  $request->edate ;
       $reciept = $request->reciept_no ;
       $data['reciept_number'] = $reciept ;
       $con = $request->count;
       $data['datecount'] =  $con + 1;
       $this->db->insert('admission_soft',$data);
       $id = $this->db->insert_id();
       $result = $this->Api_model->get($id); 
       $outputarr['Result'] =  $result;
       $outputarr['Msg'] =  "Successfully Added";
       $outputarr['Status'] = 1 ;
       echo json_encode($outputarr);
     }
     else{ 
      $outputarr['Msg'] =  "Data Not Posted";
      $outputarr['Status'] = 0;
      echo json_encode($outputarr);
    }
  }
  else{ 
    $outputarr['Msg'] =  "Data Not Posted";
    $outputarr['Status'] = 0;
    echo json_encode($outputarr);
  }


  }


  public function duefee($value='')
  {

   $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data'); 
        //$postdata='{class_id: "44", feegroup_id: "22-61"}';
   if ($postdata != ''){     
    $request = json_decode($postdata);

    $session_id = $request->session_id;
          // $session_id = 13;
             // echo "<pre>";
    $class = $this->class_model->get();
    $data['classlist'] = $class;
    $id= '';
    $feesessiongroup = $this->feesessiongroup_model->getFeesByGroup_api($id,$session_id); 
    $data['feesessiongrouplist'] = $feesessiongroup;
    $data['Status'] = 1 ;
    $data['msg'] = "Data Found" ;


  }else{
   $data['msg'] = "No Data Found" ;
   $data['Status'] = 0 ;
  }
  echo json_encode($data);
  }

  public function duefeelist()
  {    
    $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data'); 
     // $postdata='{class_id: "44", feegroup_id: "22-61"}';
    if ($postdata != ''){     
      $request = json_decode($postdata);

      $feegroup_id =$request->feegroup_id ; 
           //   $feegroup_id ="22-61" ; 
      $feegroup = explode("-", $feegroup_id);
      $feegroup_id = $feegroup[0];
      $fee_groups_feetype_id = $feegroup[1];
           //  $class_id = "44";            
      $class_id =    $request->class_id;            
      $data['student_due_fee'] = $this->studentfee_model->getDueStudentFeesapi($feegroup_id, $fee_groups_feetype_id, $class_id); 
      $data['Status']= 1; 
                         //echo $this->db->last_query();         
      echo json_encode($data);   
    }else{
      $data['msg']= "no result found";
      $data['Status']= 0;
      echo json_encode($data);
    }          
  }

  //
  public function sendsms_feeDue()
  {
    $postdata = file_get_contents("php://input");
         // $postdata =  $this->input->post('data');           
    if ($postdata != ''){     
      $request = json_decode($postdata);

      $month_name= $request->month_name ; 
      foreach($request->studentData as $student){

        $session_id =  $student->session_id ; 
        $mobnumber =  $student->mobileno ; 
        $student_session_id =  $student->student_session_id ; 
        if($student_session_id){
          $msg_data=array(
            'title'=>'Due Fees',
            'message'=>'Dear Parent, please pay your ward’s school fees for the month of $month_name School Management.',
            'session_id'=>$session_id,
            'user_list'=>$mobnumber,
            'sms_status'=> 0,
            'student_session_id'=>$student_session_id
          );
          $this->Messages_model->add($msg_data);
          $data['message'] = 'SMS sent successfully' ; 
          $data['Status'] = 1 ; 
        }else{
          $data['Status'] = 0; 
          $data['message'] = 'Student not found' ; 
        }
      }
    }else{
      $data['Status'] = 0; 
      $data['message'] = 'SMS send failed' ; 

    }
    echo json_encode($data);
  }
  public function sendsms_feePaid()
  {
    $postdata = file_get_contents("php://input");
         // $postdata =  $this->input->post('data');     
        // $postdata = '{"defaulter" : "false","mobileno":"9826837084","month_name" : "June,July", "session_id"    :    "14",   "student_session_id"   : "2256"}';      
    if ($postdata != ''){     
      $request = json_decode($postdata);
      $id =  $request->student_id ; 
      $session_id =  $request->session_id ; 
      $mobnumber =  $request->mobileno ; 
      $student_session_id =  $request->student_session_id ; 
      $month_name= $request->month_name; 
      if($student_session_id){
        $msg_data=array(
          'title'=>'Fees Paid',
          'message'=>" Dear Parent, your ward’s school fee has been received for the month $month_name. Thanks for the payment. School Management.",
          'session_id'=>$session_id,
          'user_list'=>$mobnumber,
          'student_session_id'=>$student_session_id
        );
        $this->Messages_model->add($msg_data);
        //echo $this->db->last_query();
        $data['message'] = 'SMS sent successfully' ; 
        $data['Status'] = 1 ; 
      }else{
        $data['Status'] = 0; 
        $data['message'] = 'Student not found' ; 
      }
    }else{
      $data['Status'] = 0; 
      $data['message'] = 'SMS send failed' ; 

    }
    echo json_encode($data);
  }




  public function ClassList($value='')
  {
    $class = $this->class_model->get();
    $data['classlist'] = $class;
    $data['Status'] = 1;
    echo json_encode($data);
  }


    //new api for fee date setting
  public function datestatus()
  {   
    $postdata = file_get_contents("php://input");
         // $postdata =  $this->input->post('data');   

    if ($postdata != '')
    { 
      $request = json_decode($postdata); 
      $data['status'] = $request->print; 
      $data['dl_date'] = $request->date; 
      $data['next_date'] =  date('Y-m-d',strtotime($request->date. ' + 1 days'));

      if ($data['status'] == 'true')
      {
        $data['status'] = 1 ;
        $this->db->insert('datestatus',$data);
        $result['date']= date('Y-m-d',strtotime($request->date. ' + 1 days'));
        $result['Msg']= 'today date report Downloaded';
        $result['Status']=1;
      }
      else
      {
       $result['Msg']= 'Multi-times Downloaded';
       $result['Status']=1;
     }           
   }
   else{
     $result['Msg']= 'Status not yet received';
     $result['Status']=0;          
   }
   echo json_encode($result);
  } 

  // autoincrement dont use it again
  public function autoincrement1()
  {
    $qry1 = "show TABLES";
    $result  = $this->db->query($qry1)->result();
     // echo "<pre>";
     // print_r($result);
    foreach ($result as $key => $value) {
      $tablename = $value->Tables_in_central_fee;
      $qry =  "ALTER TABLE  $tablename  CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT";
      $this->db->query($qry);
    }

  }


  public function MarksGenerator()
  {
    $arr  = array();
    $postdata = file_get_contents("php://input");
       //  $postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
       $class_id= $request->class_id;

       $section_id= $request->section_id;

       $exam_id= $request->exam_id;
     //$session_id= $request->session_id;
      //$session_id= 13;

       $examSchedule = $this->examschedule_model->getDetailbyClsandSection_api($class_id, $section_id, $exam_id, $session_id);

       $studentList  = $this->student_model->searchByClassSection($class_id, $section_id, $session_id);  

       if(!empty($examSchedule)){
        $new_array = array();
        foreach ($studentList as $stu_key => $stu_value) {
          $array = array();
          $grade = array();
          $array['student_id'] = $stu_value['id'];
          $array['admission_no'] = $stu_value['admission_no'];
          $array['roll_no'] = $stu_value['roll_no'];
          $array['firstname'] = $stu_value['firstname'];
          $array['lastname'] = $stu_value['lastname'];
          $array['dob'] = $stu_value['dob'];
          $array['class'] = $stu_value['class'];
          $array['father_name'] = $stu_value['father_name'];
          $grade= $this->student_model->get_grade_student($stu_value['admission_no']); 
          if (empty($grade)) {
            $grade = array('student_admission_no' => '','drawing' => '','punctuality' => '','neatness' => '','courteousness' => '',
              'obedience' => '','discipline' => '' ); ;
          }
          $array['gradedata'] =  $grade ; 
          $x = array();
          foreach ($examSchedule as $ex_key => $ex_value) {
            $exam_array = array();
            $exam_array['exam_schedule_id'] = $ex_value['id'];
            $exam_array['exam_id'] = $ex_value['exam_id'];
            $exam_array['subject_id'] = $ex_value['subject_id'];
            $exam_array['full_marks'] = $ex_value['full_marks'];
            $exam_array['passing_marks'] = $ex_value['passing_marks'];
            $exam_array['exam_name'] = $ex_value['name'];
            $exam_array['exam_type'] = $ex_value['type'];
            $student_exam_result = $this->examresult_model->get_exam_result($ex_value['id'], $stu_value['id']);
            $exam_array['attendence'] = $student_exam_result->attendence;
            $exam_array['get_marks'] = $student_exam_result->get_marks;
            $exam_array['optional_status'] = $student_exam_result->optional_status;
            $exam_array['teacher_approval'] = $student_exam_result->teacher_approval;
            $array['teacher_approval'] =  $student_exam_result->teacher_approval;
            $array['approvedBy']= $student_exam_result->approvedBy;
            $x[] = $exam_array;
          }
          $array['exam_array'] = $x;

          $new_array[] = $array;
        }
        $arr['examSchedule'] = $new_array;
        $arr['msg'] = 'Exam found';
        $arr['Status'] =  1 ; 
      }
      else{
        $arr['msg'] = 'Exam Subjects not found';
        $arr['Status'] =  0 ;
      }


    }else{
      $arr['msg'] = 'Class id, Section id, Exam id  not found';
      $arr['Status'] =  0 ;
    }
  }else{
    $arr['msg'] = 'Post data not found';
    $arr['Status'] =  0 ;
  }
  echo json_encode($arr) ;
  }
  public function pending_report()
  {
    $postdata = file_get_contents("php://input");
        //$postdata =  $this->input->post('data');  
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {      
      $class_id=$request->class_id ;
      $section_id=$request->section_id ;
      $session_id=$request->session_id ;
      $student_due_fee = $this->studentfee_model->getDueFeeBystudentSection($class_id, $section_id, $session_id);
      $arr['result'] =  $student_due_fee ;
    }else{
      $arr['msg'] = 'Class_id  not found';
      $arr['Status'] =  0 ;
    }
  }
  else{
    $arr['msg'] = 'Post data not found';
    $arr['Status'] =  0 ;  
  }
  $arr['msg'] = 'Result found';
  $arr['Status'] = 1 ;


  }



  public function Save_MarksGenerator()
  {
        //$arr['pranav']='Sahni';
    $postdata = file_get_contents("php://input");
        //$postdata =  $this->input->post('data');  
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {            
      $ex_array = array();

      foreach ($request->student_array as $key => $grade) {               
        $grad['student_admission_no'] = $grade->gradedata->student_admission_no ;
        $grad['drawing'] =  $grade->gradedata->drawing;
        $grad['punctuality'] =  $grade->gradedata->punctuality;
        $grad['neatness'] =  $grade->gradedata->neatness;
        $grad['courteousness'] =  $grade->gradedata->courteousness;
        $grad['obedience'] =  $grade->gradedata->obedience;
        $grad['discipline'] =  $grade->gradedata->discipline;
        $gradn = $grade->gradedata->student_admission_no ;                          
        $this->student_model->add_grade_student($grad,$gradn);                       
      }

      $student_array = $request->student_array; 
                  //print_r($student_array);          
      foreach ($student_array as $key => $student) {

        foreach ($student->exam_array as $key => $exam) {
          $record['get_marks'] = 0;
          $record['attendence'] = "abs";
          if ($exam->attendence == 'pre') {
           $record['get_marks'] = $exam->get_marks;
           $record['attendence'] = $exam->attendence;
           $record['optional_status'] = $exam->optional_status;
         } 
         $record['exam_schedule_id'] = $exam->exam_schedule_id;
         $record['student_id'] = $student->student_id;
         $inserted_id = $this->examresult_model->add_exam_result($record);


         if ($inserted_id) {
           $arr['msg'] = 'Marks Successfully Saved';
           $arr['Status'] =  1 ;
         }
         else{
          $arr['msg'] = 'Marks Updated Successfully';
          $arr['Status'] =  1 ;
        }
      }
    }   

  }
  else{
    $arr['msg'] = 'Class_id,section_id, exammaarks data not found';
    $arr['Status'] =  0 ;
  }
  }else{
    $arr['msg'] = 'Post data not found';
    $arr['Status'] =  0 ;
  }

  echo json_encode($arr); 
  }


  // for teacher approved marks list
  public function Approve_MarksGenerator()
  {
    $arr  = array();
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');     
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {            
      $ex_array = array();
        // print_r($request) ; die;
      foreach ($request->student_array as $key => $grade) {     
        $grad['student_admission_no'] = $grade->gradedata->student_admission_no ;
        $grad['drawing'] =  $grade->gradedata->drawing;
        $grad['punctuality'] =  $grade->gradedata->punctuality;
        $grad['neatness'] =  $grade->gradedata->neatness;
        $grad['courteousness'] =  $grade->gradedata->courteousness;
        $grad['obedience'] =  $grade->gradedata->obedience;
        $grad['discipline'] =  $grade->gradedata->discipline;
        $gradn = $grade->gradedata->student_admission_no ;

        $this->student_model->add_grade_student($grad,$gradn);
      }
                  // print_r($grad); die;

      $student_array = $request->student_array;           
      foreach ($student_array as $key => $student) {
        foreach ($student->exam_array as $key => $exam) {
          $record['get_marks'] = 0;
          $record['attendence'] = "abs";
          if ($exam->attendence == 'pre') {
           $record['get_marks'] = $exam->get_marks;
           $record['attendence'] = $exam->attendence;
           $record['optional_status'] = $exam->optional_status;
           $record['teacher_approval'] = 1 ;

         } 
         $record['approvedBy'] = $student->approvedBy;
         $record['exam_schedule_id'] = $exam->exam_schedule_id;
         $record['student_id'] = $student->student_id;
         $inserted_id = $this->examresult_model->add_exam_result($record);
         if ($inserted_id) {
           $arr['msg'] = 'Marks Successfully Saved';
           $arr['Status'] =  1 ;
         }else{

          $arr['msg'] = 'Marks Updated Successfully';
          $arr['Status'] =  1 ;
        }
      }
    }              
  }
  else{
    $arr['msg'] = 'Class_id,section_id, exammaarks data not found';
    $arr['Status'] =  0 ;
  }
  }else{
    $arr['msg'] = 'Post data not found';
    $arr['Status'] =  0 ;
  }
  echo json_encode($arr) ; 
  }

  public function MarksheetStudentlistByClass()
  {
   $postdata = file_get_contents("php://input");
      // $postdata =  $this->input->post('data');    
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     { 
      $class=$request->class_id ;
      $section=$request->section_id ;      
      $session_id=$request->session_id ; 
        
      $resultlist = $this->student_model->searchByClassSection_api($class, $section,$session_id);

      $data['studentlist'] =  $resultlist ; 
      $data['Status'] = 1;
    }else{
     $data['Msg'] = 'No result Found';
     $data['Status'] = 0;
   }

   echo json_encode($data);        
  }
  }




  public function Examlist()
  {
    $exam = $this->exam_model->get();
    $arr['ExamList'] =   $exam ;
    echo json_encode($arr) ;

  }


  public function MarksheetlistByClass()
  {
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     { 
                //$session = $this->setting_model->getCurrentSession();
      $class=$request->class_id ; 
      $section=$request->section_id ;
      $student_id=$request->student_id ;
      $session_id=$request->session_id ;
      $examList = $this->examschedule_model->getExamByClassandSection_api($class, $section, $session_id);
      if (!empty($examList)) {
        $new_array = array();
        foreach ($examList as $ex_key => $ex_value) {
          $sort_result_perexam=array();
          $array = array();
          $x = array();
          $exam_id = $ex_value['exam_id'];
          $student_id;
          $exam_subjects = $this->examschedule_model->getresultByStudentandExam_api($exam_id, $student_id, $session_id);
          if (!empty($exam_subjects)) {                 
            foreach ($exam_subjects as $key => $value) {
              $exam_array = array();
              $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
              $exam_array['exam_id'] = $value['exam_id'];
              $exam_array['full_marks'] = $value['full_marks'];
              $exam_array['passing_marks'] = $value['passing_marks'];
              $exam_array['exam_subject_name'] = $value['name'];
              $exam_array['exam_type'] = $value['type'];
              $exam_array['attendence'] = $value['attendence'];
              $exam_array['get_marks'] = $value['get_marks'];
              $x[] = $exam_array;
                 } 
            $sort_result_perexam = $this->array_sort($x, 'exam_subject_name', SORT_ASC);
            $sort_result_perexam_val = array_values($sort_result_perexam);
            $array['exam_name'] = $ex_value['name'];
            $array['exam_result'] = $sort_result_perexam_val;
            $new_array[] = $array;
          }

        }
        $data['examSchedule'] = $new_array;
      }
      $data['examlist'] =  $examList ; 
      $data['Status'] = 1;
    }else{
     $data['Msg'] = 'No result Found';
     $data['Status'] = 0;
   }
  //print_r($data);
   echo json_encode($data);        
  }
  }



  // marksheet for all student of a class


  public function All_MarksheetlistByClass()
  {
    $postdata = file_get_contents("php://input");
  //$postdata= $this->input->post('data');
  //$postdata='{"a":"a"}';
    if ($postdata != '')
    { 
      $request = json_decode($postdata);
      if(!empty($request))
      { 
        include_once APPPATH . '/third_party/mpdf/mpdf.php';
        $file = FCPATH . "uploads/marksheetPdf";
        $file2 = FCPATH . "archive.zip";
        if (is_dir($file)) {
  $this->load->helper("file"); // load the helper
  delete_files($file, true); // delete all files/folders
  }
  if (file_exists($file2)) {
  unlink($file2); // delete all files/folders
  }
  //$session = $this->setting_model->getCurrentSession();
  //  $class=36; 
  //  $section=17;
  $class=$request->class_id ; 
  $section=$request->section_id ;
  $session=$request->session_id ;

  $examList = $this->examschedule_model->getExamByClassandSection_api($class, $section,$session);

  $studentlist = $this->Student_model->searchByClassSection($class, $section); 


  if (!empty($examList)) {
    foreach ($studentlist as $key => $stuval) {
      $finalmax= array();
      $finalobt= array();
      $max=array();
      $obt=array();
      $marks=[];
      $new_array = array();
      foreach ($examList as $ex_key => $ex_value) {

        $sort_result_perexam=array();
        $array = array();
        $x = array();

        $exam_id = $ex_value['exam_id'];


        $studetails['name'] = $stuval['firstname'].' '.$stuval['lastname'];
        $studetails['image'] = $stuval['image'];
        $studetails['fname'] = $stuval['father_name'];
        $studetails['mname'] = $stuval['mother_name'];
        $studetails['scholar_no'] = $stuval['admission_no'];
        $studetails['rollno'] = $stuval['roll_no'];
        $studetails['address'] = $stuval['permanent_address'];
        $studetails['class'] = $stuval['class'];
        $studetails['section'] = $stuval['section'];
        $studetails['dob'] = $stuval['dob'];
        $studetails['adhar_no'] = $stuval['adhar_no'];
        $studetails['smagra'] = $stuval['samagra_id'];
        $studetails['session'] = $stuval['sessionname']; 
        $session = $stuval['sessionname']; 
        $studetails['gender'] = $stuval['gender'];
        $studetails['height'] = $stuval['height'];
        $studetails['weight'] = $stuval['weight'];
        $studetails['total_days'] = $stuval['total_days'];
        $studetails['present_days'] = $stuval['present_days'];

        $exam_subjects = $this->examschedule_model->getresultByStudentandExam_pdf($exam_id, $stuval['id']);
  // echo $this->db->last_query(); 
        $exam_subjects_1[] = $exam_subjects ;

  // print_r($exam_subjects);
  // die;
  /*print_r($exam_subjects); 
  die;
  */ if (!empty($exam_subjects)) { 

  foreach ($exam_subjects as $key => $value) {
    $exam_array = array();
    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
    $exam_array['exam_id'] = $value['exam_id'];
    if ($value['optional_status'] == 1 ) {
      $exam_array['full_marks'] = 0 ;
    }else{
      $exam_array['full_marks'] = $value['full_marks'];
    }
    $exam_array['passing_marks'] = $value['passing_marks'];
    $exam_array['exam_subject_name'] = $value['name'];
    $exam_array['exam_type'] = $value['type'];
    $exam_array['attendence'] = $value['attendence'];
    $exam_array['get_marks'] = $value['get_marks'];
    $x[] = $exam_array;
    $subject[]= $exam_array['exam_subject_name'];
  }

  $sort_result_perexam = $this->array_sort($x, 'exam_schedule_id', SORT_ASC);
  $sort_result_perexam_val = array_values($sort_result_perexam);

  $array['exam_name'] = $ex_value['name'];
  $array['note'] = $ex_value['examnote'];
  $array['exam_result'] = $sort_result_perexam_val;
  $new_array[] = $array;
  $marks[]=$sort_result_perexam_val;

  }

  }

  $finalmax=[];
  $finalobt= [];

  $grading = $this->student_model->get_grade_student($studetails['scholar_no']); 

  $sub = array();

  $all_subjects = array_unique($subject);
  foreach ($new_array as $first_arr) {
    foreach ($first_arr['exam_result'] as $first_arr1) {

      $sub[$first_arr1['exam_subject_name']] = $first_arr1['full_marks'] . ' | ' . intval($first_arr1['get_marks']);
    }
    $exam_arr[$first_arr['exam_name']] = $sub;
  }
  $term[]=array(



  );
  foreach ($exam_arr as $value) {
  }



  $data = array(
    'studetails'=> $studetails,
    'new_array'=> $new_array,
    'sessionname'=> $session,
    'grading'=> $grading,


  );

  // echo $html=$this->load->view('marksheet/marksheet_11', $data, true);
  // die;

  //load the view and saved it into $html variable
  if($stuval['class'] == 'IX'){
  //**************************************************************************** 



  //      $first_arr= array();
  //  $highmark= array();
    $sub_array_marks= array();
    $all_subjects= array_unique($subject);
    foreach ($new_array as $first_arr) {
      if($first_arr['exam_name']== 'Final Examination')
      {
        foreach ($first_arr['exam_result'] as $key => $exam_arr) { 
          if (in_array($exam_arr['exam_subject_name'], $all_subjects)) {
            $sub_array_final_marks[$exam_arr['exam_subject_name']] = $exam_arr['get_marks'];
          }
        }
      }else{
        foreach ($first_arr['exam_result'] as $key => $exam_arr) {
          if (in_array($exam_arr['exam_subject_name'], $all_subjects)) {
            $sub_array_marks[$exam_arr['exam_subject_name']][] = $exam_arr['get_marks'];

          }

        }

      }
    }
  /* foreach ($sub_array_marks as $key1 => $value) {
          sort($value);
          $highmark[$key1]= ($value[1] +$value[2])/2 ;
     
        }  */
  //echo '<pre>';

        foreach ($sub_array_marks as $key1 => $valuesort) {
          sort($valuesort);
          $cary =count($valuesort);
          if ($cary == 3) {
            $highmark[$key1]= ($valuesort[1] +$valuesort[2]) /2 ;
          }
          if ($cary == 2) {
            $highmark[$key1]= ($valuesort[0] +$valuesort[1]) /2 ;
          }




        } 

              $data = array(
                'student'=> $studetails,
                'final_exam'=> $sub_array_final_marks,
                'all_exam'=> $highmark,
                'subjects'=> $all_subjects,
                'session'=> $session,
              ); 
              $html=$this->load->view('marksheet/marksheet_ix', $data, true); 
          } 

      else if($stuval['class'] == 'XI'){
        $sub = array();

        $all_subjects = array_unique($subject);
        foreach ($new_array as $first_arr) {

          foreach ($first_arr['exam_result'] as $first_arr1) {
            $sub[$first_arr1['exam_subject_name']]['full'] = intval($first_arr1['full_marks']) ;
            $sub[$first_arr1['exam_subject_name']]['get'] =  intval($first_arr1['get_marks']);
          }
          $exam_arr[$first_arr['exam_name']] = $sub;

        }
       $data = array(
          'student'=> $studetails,
          'quaterly'=> $exam_arr['Quarterly Examination'],
          'halfyearly'=> $exam_arr['Half Yearly Examination'],
          'final'=> $exam_arr['Final Examination'],
          'sessionname'=> $studetails['session'],
          'subject'=> $all_subjects,
        );

        $html=$this->load->view('marksheet/marksheet_xi', $data, true); 
      } 

      elseif ($stuval['class'] == 'VI' || $stuval['class'] == 'VII'|| $stuval['class'] == 'VIII') {

        $sub = array();
        $sub1 = array();

        $all_subjects = array_unique($subject);

        foreach ($new_array as $first_arr) {
          foreach ($first_arr['exam_result'] as $first_arr1) {
            $sub[$first_arr1['exam_subject_name']] = $first_arr1['full_marks'];

          }

          $exam_arr[$first_arr['exam_name']] = $sub;

        }


   //print_r($exam_name); die;
        foreach ($new_array as $first_arr2) {
          foreach ($first_arr2['exam_result'] as $first_arr3) {
            $sub1[$first_arr3['exam_subject_name']] = intval($first_arr3['get_marks']);
          }
          $exam_arr1[$first_arr2['exam_name']] = $sub1;
        }


        $periodicI=$exam_arr1['Periodic-I'] ;
        $periodicII=$exam_arr1['Periodic-2'];
        $periodicIII=$exam_arr1['Periodic-3'];
        $final_term=$exam_arr1['Final Examination'];



        $data = array(
         'subject'=> $all_subjects,
         'student'=> $studetails,
         'final_term'=> $final_term,
         'periodicI'=> $periodicI,
         'periodicII'=> $periodicII,
         'periodicIII'=> $periodicIII,
         'sessionname'=> $studetails['session'],
       );
  //anand sir code ends here

        $html=$this->load->view('marksheet/marksheet_vi', $data, true); 
      } 

      else{
  //*****************************************************************************************

        $sub = array();

        $all_subjects = array_unique($subject);
        foreach ($new_array as $first_arr) {

          foreach ($first_arr['exam_result'] as $first_arr1) {
            $sub[$first_arr1['exam_subject_name']]['full'] = intval($first_arr1['full_marks']) ;
            $sub[$first_arr1['exam_subject_name']]['get'] =  intval($first_arr1['get_marks']);
          }
          $exam_arr[$first_arr['exam_name']] = $sub;
    //  echo '<pre>';
        }

        $grading = $this->student_model->get_grade_student($studetails['scholar_no']); 
  //print_r($exam_arr);
        $data = array(
  // 'studetails'=> $studetails,
  // 'exam'=> $exam_arr,
  // 'sessionname'=> $studetails['session'],
          'student'=> $studetails,
          'quaterly'=> $exam_arr['Quarterly Examination'],
          'halfyearly'=> $exam_arr['Half Yearly Examination'],
          'final'=> $exam_arr['Final Examination'],
          'sessionname'=> $studetails['session'],
          'subject'=> $all_subjects,
          'grade'=>$grading ,
        );

  //*****************************************************************************************

        $html=$this->load->view('marksheet/marksheet_v', $data, true); 
      }

  // echo $html ;
  // die;
  //ends heere

      $code=$studetails['scholar_no'];
      $m_pdf = new mPDF();
      $m_pdf->SetDisplayMode('real');
  //q $m_pdf->shrink_tables_to_fit = 1;
      $m_pdf->WriteHTML($html);
      $m_pdf->Output(FCPATH . "uploads/marksheetPdf/$code.pdf", 'F');

  //die;
    }

  // Create zip
    $this->load->library('zip');
    $zip = new ZipArchive();
    $filename = "./archive.zip";
    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
      exit("cannot open <$filename>\n");

    }
    $dir = 'uploads/marksheetPdf/';
    $this->createZip($zip,$dir);

    $zip->close();

    $filename = "archive.zip";

    $datass['Status'] = 1;
    $datass['zippath']=base_url().'archive.zip'; 
  }else{
    $datass['Msg'] = 'Exam Not Found';
    $datass['Status'] = 0;
  }
  }else{
    $datass['Msg'] = 'No result Found';
    $datass['Status'] = 0;
  }
  echo json_encode($datass); 
  }

  }


  function createZip($zip,$dir){
    if (is_dir($dir)){

      if ($dh = opendir($dir)){
       while (($file = readdir($dh)) !== false){

    // If file
        if (is_file($dir.$file)) {
         if($file != '' && $file != '.' && $file != '..'){

          $zip->addFile($dir.$file);
        }
      }else{
     // If directory
       if(is_dir($dir.$file) ){

        if($file != '' && $file != '.' && $file != '..'){

       // Add empty directory
         $zip->addEmptyDir($dir.$file);

         $folder = $dir.$file.'/';

       // Read data of the folder
         $this->createZip($zip,$folder);
       }
     }

   }

  }
  closedir($dh);
  }
  }
  }
    ///new api for all student markshet end 


  //student admission with fee 

  public function studentadmission_old_modified()
  {
  $postdata = file_get_contents("php://input");
   //$postdata =  $this->input->post('data');    
   /*$postdata = '{
    "admission_number": "44444",
    "first_name": "ddddddddd",
    "last_name" : "ddddddddd",
    "class_id": "28",
    "section": "17",
    "gender": "Male",
    "dob": "2019-03-20",
    "admission_date": "2019-03-20",
    "adhar_no": "fdsfdsfdsfdsfds",
    "samagra_id": "fdsfdfdfdsfd",
    "bank_no": "errewrewredfsdf",
    "guardian_address": "sfdsfdsfdsfdsfd",
    "mother_name": "dsdfldf",
    "mother_phone": "43434",
    "father_name": "gfdgdfgd",
    "father_phone": "43434",
    "guardian_relation": "Mother",
    "guardian_phone": "43434",
    "guardian_name": "dsdfldf",
    "session_id": "13",
    "is_bpl" :false
  }'; */

  if ($postdata != '')
  {  
    $request = json_decode($postdata);
    if(!empty($request))
    {  
      $session_id = $request->session_id;
      $class_id = $request->class_id;
      $section_id = $request->section;
      $is_bpl = $request->is_bpl;
      $fees_discount = 0;
      $vehroute_id =  '';
                    //$vehicle_id =  $request->vehicles;
      $vehicle_id =  '';
      $transport_fees = 0;    
      $hostel_room_id =0;            
      if (empty($vehroute_id)) {
        $vehroute_id = 0;
      }
      if (empty($transport_fees)) {
        $transport_fees = 0;
      }
      if (empty($hostel_room_id)) {
        $hostel_room_id = 0;
      }
      $data = array(
        'admission_no' =>$request->admission_number,
        'admission_date' => date('Y-m-d', strtotime( $request->admission_date)),
        'firstname' => $request->first_name,
        'lastname' =>$request->last_name,
        'mobileno' => $request->guardian_phone,
        'rte' => 'No',  
        'dob' => date('Y-m-d', strtotime($request->dob)),
        'current_address' => ($request->guardian_address)?$request->guardian_address:'No',
        'permanent_address' => ($request->guardian_address)?$request->guardian_address:'No',
        'image' => 'uploads/student_images/no_image.png',
        'category_id' => 1,
        'adhar_no' => ($request->adhar_no)?$request->adhar_no:'No',
        'samagra_id' => ($request->samagra_id)?$request->samagra_id:'No',
        'bank_account_no' => ($request->bank_no)?$request->bank_no:0,
        'father_name' =>  $request->father_name,
        'father_phone' => $request->father_phone,
        'mother_name' => $request->mother_name,
        'mother_phone' => $request->mother_phone,
        'gender' => $request->gender,
        'guardian_name' => $request->guardian_name,
        'guardian_relation' => $request->guardian_relation,
        'guardian_phone' => $request->guardian_phone,
        'guardian_address' => $request->guardian_address,
        'vehroute_id' =>  ($vehroute_id)?$vehroute_id:0,
        'hostel_room_id' => ($hostel_room_id)?$hostel_room_id:0,
        'school_house_id' => 0,
        'is_active' => 'yes',
        'guardian_is' =>'father'
     // 'measurement_date' => date('Y-m-d', strtotime($this->input->post('measure_date')))
      );

      $ifexist = $this->student_model->check_AdmissionNo($request->admission_number);
      if($ifexist){
       $outputarry['msg']  = 'This '.$request->admission_number.' Admission Number Already Exist';              
       $outputarry['Status']  = 0; 
     } 
     else
     {
      $insert_id = $this->student_model->add($data);
      $data_new = array(
        'student_id' => $insert_id,
        'class_id' => $class_id,
        'section_id' => $section_id,
        'session_id' => $session_id,
        'route_id' => $vehroute_id,
        'vehicle_id' => $vehicle_id,
        'transport_fees' => $transport_fees,
        'fees_discount' => $fees_discount
      );

      $student_session_id = $this->student_model->add_student_session($data_new);

      $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
      $sibling_id = $this->input->post('sibling_id');
      $data_student_login = array(
        'username' => $this->student_login_prefix . $insert_id,
        'password' => $user_password,
        'user_id' => $insert_id,
        'role' => 'student');

      $this->user_model->add($data_student_login);

      if ($vehicle_id) {
        $student_fee = $this->db->get_where('student_session', array('student_id' => $insert_id))->row();
        $mydate = "10-04-" .date('Y');
        $time = strtotime("$mydate");
        $j = 0;
        for ($i = 1; $i <= 11; $i++) {
          $final = date("Y-m-d", strtotime("+$j month", $time));
          if ($i == 2) {
            $j++;
            $final = date("Y-m-d", strtotime("+$j month", $time));
            $insert_array = 
            array(
              'student_session_id' => $student_session_id,
              'fee_session_group_id' => 0,
              'due_date' => $final,
              'fees_amount' => $student_fee->transport_fees / 2,
            );
          } else {
            $insert_array = array(
              'student_session_id' => $student_session_id,
              'fee_session_group_id' => 0,
              'due_date' => $final,
              'fees_amount' => $student_fee->transport_fees,
            );
          }
          $inserted_id = $this->transport_custom_model->add3($insert_array);
          $preserve_record[] = $inserted_id;
          $j++;
        }
      }
      $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
      $temp = $insert_id;
      $data_parent_login = array(
        'username' => $this->parent_login_prefix . $insert_id,
        'password' => $parent_password,
        'user_id' => 0,
        'role' => 'parent',
        'childs' => $temp
      );

      $ins_parent_id = $this->user_model->add($data_parent_login);
      $update_student = array(
        'id' => $insert_id,
        'parent_id' => $ins_parent_id
      );
      $this->student_model->add($update_student);


      $userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session_id);

      if (!empty($userlisting)) {
        foreach ($userlisting as $userlisting_key => $userlisting_value) {
          if ($userlisting_value['guardian_phone']) {
            $arr[] = $userlisting_value['guardian_phone'];
            $user_array = implode(',', $arr);
          }
          else{
            $arr[] = $userlisting_value['mobileno'];
            $user_array = implode(',', $arr);
          }
        }
      }
      $students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session_id);
      if (!empty($students_no))
      {
        $data_num = array(
          'id' => $students_no['id'],
          'numbers' => $user_array,
        );
      } else 
      {
        $data_num = array(
          'session_id' => $session_id,
          'class_id' => $class_id,
          'section_id' => $section_id,
          'numbers' => $user_array,
        );
      } 
      $this->student_model->add_all_student_no($data_num); 
      /* student fees add*/  

      if ($is_bpl) 
      {

        $outputarry['msg']  = 'BPL Student Added Successfully No Fees Charges Apply';              
        $outputarry['Status']  = 1; 
      } 
      else 
      {
        $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($class_id,$session_id) ;
        $feesession_group_ida= $feesession_group_id->id;
        $qrie= "SELECT fee_session_group_id, fee_groups_id, feetype_id, session_id,due_date, amount_II as amount , is_active, created_at, fee_bydefault , shows from fee_groups_feetype where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";

        $fees_result1 = $this->db->query($qrie)->result();
         // print_r($fees_result1); die;    
        $fee_receipt_no=$this->Api_model->feeereceipt_no();

        foreach ($fees_result1 as $key => $fees_result)
        {  
          $fees_result->fee_session_group_id ; 
          $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
          $data_fee['student_session_id'] =  $student_session_id ;
          $master_id = $this->Studentfeemaster_model->add($data_fee);
                                          //add fee start
          $json_array = array(
            'amount' => $fees_result->amount,
            'date' => date('Y-m-d'),
            'amount_discount' => 0,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode' => 'cash'
          );
          $data_master = array(
            'student_fees_master_id' => $master_id,
            'fee_groups_feetype_id' => $fees_result->id,
            'receipt_no' => $fee_receipt_no,
            'amount_detail' => $json_array
          );
          $student_fees_discount_id = 0 ;
          $inserted_id = $this->Api_model->fee_deposit($data_master, $student_fees_discount_id);
          $jsonary = json_decode( $inserted_id, true);
          $outputarry['msg']  = 'Student Added successfully And All Asigned  Fee Paid For First Time'; 
          $outputarry['Status'] = 1;   
          $outputarry['receipt_no'] = $fee_receipt_no;   
          $outputarry['fee_data'][] =$this->Api_model->get_fees_data( $jsonary['invoice_id'] );    
          $outputarry['fee_details'][] = $this->Api_model->fees_result_admissioin($fees_result->id);  
          $outputarry['student_session_id']  = $student_session_id;   
        }
                      }// else end for bpl
                  }  // else end for admission no exist
               }  // if end for request data
               else{
                $outputarry['msg']  = 'Fail To Add Data';              
                $outputarry['Status']  = 0; 
              }     
            } 
            else{
              $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
            }         
            echo json_encode($outputarry);
          }  
  //student admission with fee ends here









          public function studentadmission_withoutfee()
          {
           $postdata = file_get_contents("php://input");
          //$postdata =  $this->input->post('data');    
           if ($postdata != '')
           {  
             $request = json_decode($postdata);
             if(!empty($request))
             {  
              $session_id = $request->session_id;

              $class_id = $request->class;
              $section_id = $request->section;
              $fees_discount = 0;
              $vehroute_id =  $request->route_list;
    //$vehicle_id =  $request->vehicles;
              $vehicle_id =  '';
              $transport_fees =  $request->fare;    
              $hostel_room_id = $request->room_number;            
              if (empty($vehroute_id)) {
                $vehroute_id = 0;
              }
              if (empty($transport_fees)) {
                $transport_fees = 0;
              }
              if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
              }
              $data = array(
                'admission_no' =>$request->admission_number,
                'roll_no' => $request->roll_number,
                'admission_date' => date('Y-m-d', strtotime( $request->admission_date)),
                'firstname' => $request->first_name,
                'lastname' =>$request->last_name,
                'mobileno' => $request->mobile_number,
                'rte' => ($request->rte)?$request->rte:'No',
                'email' => $request->email,
                'guardian_is' => $request->if_guardian_is,
                'religion' => $request->religion,
                'cast' => $request->caste,
                'previous_school' =>($request->previous_school_details)?$request->previous_school_details:'No',
                'dob' => date('Y-m-d', strtotime($request->dob)),
                'current_address' => ($request->current_address_type)?$request->current_address_type:'No',
                'permanent_address' => ($request->permanent_address_type)?$request->permanent_address_type:'No',
                'image' => 'uploads/student_images/no_image.png',
                'category_id' => 1,
                'adhar_no' => ($request->national_identification_number)?$request->national_identification_number:'No',
                'samagra_id' => ($request->local_identification_number)?$request->local_identification_number:'No',
                'bank_account_no' => ($request->bank_account_number)?$request->bank_account_number:0,
                'bank_name' => ($request->bank_name)?$request->bank_name:'No',
                'ifsc_code' => ($request->ifsc_code)?$request->ifsc_code:'No',
                'father_name' =>  $request->father_name,
                'father_phone' => $request->father_phone,
                'father_occupation' => $request->father_occupation,
                'mother_name' => $request->mother_name,
                'mother_phone' => $request->mother_phone,
                'mother_occupation' => $request->mother_occupation,
                'guardian_occupation' => $request->guardian_occupation,
                'guardian_email' => $request->guardian_email,
                'gender' => $request->gender,
                'guardian_name' => $request->guardian_name,
                'guardian_relation' => $request->guardian_relation,
                'guardian_phone' => $request->guardian_phone,
                'guardian_address' => $request->guardian_address,
                'vehroute_id' =>  ($request->vehroute_id)?$request->vehroute_id:0,
                'hostel_room_id' => ($request->hostel_room_id)?$request->hostel_room_id:0,
                'school_house_id' => 0,
                'blood_group' =>  $request->blood_group,
                'height' => $request->height,
                'weight' => $request->weight,
                'note' =>($request->note)?$request->note:'no',
                'is_active' => 'yes',
       // 'measurement_date' => date('Y-m-d', strtotime($this->input->post('measure_date')))
              );

              $insert_id = $this->student_model->add($data);

    /* $outputarry['query']  =$this->db->last_query(); 
    echo json_encode($outputarry); die; */
    $data_new = array(
      'student_id' => $insert_id,
      'class_id' => $class_id,
      'section_id' => $section_id,
      'session_id' => $session,
      'route_id' => $vehroute_id,
      'vehicle_id' => $vehicle_id,
      'transport_fees' => $transport_fees,
      'fees_discount' => $fees_discount
    );
    
    $student_session_id = $this->student_model->add_student_session($data_new);
    /* $outputarry['query']  =$this->db->last_query(); 
    echo json_encode($outputarry); die; */
    $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
    $sibling_id = $this->input->post('sibling_id');
    $data_student_login = array(
      'username' => $this->student_login_prefix . $insert_id,
      'password' => $user_password,
      'user_id' => $insert_id,
      'role' => 'student'
    );
    
    $this->user_model->add($data_student_login);
    
    if ($vehicle_id) {
      $student_fee = $this->db->get_where('student_session', array('student_id' => $insert_id))->row();
      $mydate = "10-04-" . date('Y');
      $time = strtotime("$mydate");
      $j = 0;
      for ($i = 1; $i <= 11; $i++) {
          
        $final = date("Y-m-d", strtotime("+$j month", $time));
        if ($i == 2) {
          $j++;
          $final = date("Y-m-d", strtotime("+$j month", $time));
          $insert_array = array(
            'student_session_id' => $student_session_id,
            'fee_session_group_id' => 0,
            'due_date' => $final,
            'fees_amount' => $student_fee->transport_fees / 2,
          );
        } else {
          $insert_array = array(
            'student_session_id' => $student_session_id,
            'fee_session_group_id' => 0,
            'due_date' => $final,
            'fees_amount' => $student_fee->transport_fees,
          );
        }
        $inserted_id = $this->transport_custom_model->add3($insert_array);
        $preserve_record[] = $inserted_id;
        $j++;
      }
    }
    
    
    $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
    $temp = $insert_id;
    $data_parent_login = array(
      'username' => $this->parent_login_prefix . $insert_id,
      'password' => $parent_password,
      'user_id' => 0,
      'role' => 'parent',
      'childs' => $temp
    );
    
    $ins_parent_id = $this->user_model->add($data_parent_login);
    $update_student = array(
      'id' => $insert_id,
      'parent_id' => $ins_parent_id
    );
    $this->student_model->add($update_student);

    
    $userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session_id);
    
    if (!empty($userlisting)) {
      foreach ($userlisting as $userlisting_key => $userlisting_value) {
        if ($userlisting_value['guardian_phone']) {
          $arr[] = $userlisting_value['guardian_phone'];
          $user_array = implode(',', $arr);
        }
        else{
          $arr[] = $userlisting_value['mobileno'];
          $user_array = implode(',', $arr);
        }
      }
    }
    $students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session_id);
    if (!empty($students_no)) {
      $data_num = array(
        'id' => $students_no['id'],
        'numbers' => $user_array,
      );
    } else {
      $data_num = array(
        'session_id' => $session_id,
        'class_id' => $class_id,
        'section_id' => $section_id,
        'numbers' => $user_array,
      );
    }
    
    $this->student_model->add_all_student_no($data_num);

    $outputarry['msg']  = 'Added successfully';              

    $outputarry['Status']  = 1;              
    
  }
  else{
    $outputarry['msg']  = 'Fail To Add Data';              
    $outputarry['Status']  = 0; 
  }


  } else{
    $outputarry['msg']  = 'Empty Posted Data';              
    $outputarry['Status']  = 0; 
  }

  echo json_encode($outputarry);
  }


  public function Sectionbyclassid()
  {
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
      $class_id = $request->class_id  ; 


      $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
      sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class_id."' order by sections.section ";
      $result['sections'] = $this->db->query($qry)->result_array();
    /*  echo '<pre>';
     print_r($result);
     echo '</pre>'; */
     $result[Status] =  1 ; 
     echo json_encode($result);

   } else{
    $data['msg'] = 'no section found' ; 
    $data['Status'] = 0 ; 
  }
  }else{
    $data['msg'] = 'no section found' ; 
    $data['Status'] = 0 ; 
  }

  }


  public function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $on) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }

      switch ($order) {
        case SORT_ASC:
        asort($sortable_array);
        break;
        case SORT_DESC:
        arsort($sortable_array);
        break;
      }

      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }

    return $new_array;
  }


  public function RevertFee()
  {
   $postdata = file_get_contents("php://input");
  
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {     
      $todayDate  = date('Y-m-d');
      $revert = $request->revert ; 
      foreach ($revert as $key => $value) 
      {
          $invoice_id = $value->main_invoice ;         
          $sub_invoice = $value->sub_invoice ;
          $qry  = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as dates from student_fees_deposite where id = $invoice_id ";
          $checkdate = $this->db->query($qry)->row()->dates ;
         $qry  = "SELECT dl_date as dlDate from datestatus where 1 order by id desc limit 1";
         $DlDate = $this->db->query($qry)->row()->dlDate ;
      
        if ($todayDate == $checkdate && $todayDate > $DlDate) {
               //revert function if off due to some reasons
               $this->studentfee_model->remove($invoice_id, $sub_invoice);
                $array['msg'] = 'Fee Revert Successfully' ; 
                $array['Status'] = 1 ;  
          }
          else
            { 
               $array['msg'] = 'Fee Revert Failed ! Daily Report downloaded.... ' ; 
               $array['Status'] = 0 ;  
            }
      }  
   }else
    {
      $array['msg'] = 'Invoice id Not Found' ; 
      $array['Status'] = 0 ; 
    } 

  }else
  {
    $array['msg'] = 'Invalid Data' ; 
    $array['Status'] = 0 ;        
  }
  echo json_encode($array);
  }

  //expense Vouchers 
  public function expenseHead()
  {
    $data['category'] = $this->expensehead_model->get();  
    $data['expense_result'] = $this->expense_model->get();
    $data['Status'] =1;
    echo json_encode($data);
  }



  function expenseAdd() {
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  

      $exp_head_id = $request->exp_head_id;    
      $date = $request->date;    
      $name = $request->name;    
      $amount = $request->amount;    
      $description = $request->note;    
      $invoice_no = $request->invoice_no;    
      $documents = $request->documents;    
      $session_id = $request->session_id;    

      $entry = array(
        'exp_head_id' => $exp_head_id,
        'name' => $name,
        'date' => $date,
        'amount' => $amount,
        'invoice_no' => $invoice_no,
        'note' => $description,               
        'session_id' => $session_id,             
      );

      $insert_id = $this->expense_model->add($entry);
      if(!empty($documents))
      {
       $base =  $documents;           
       $file_name=date("Ymdhis"); 
               // Decode Image
       $binary=base64_decode($base);
       header('Content-Type: bitmap; charset=utf-8');
       $file = fopen('./uploads/school_expense/'.$file_name.'.jpg', 'wb');
               // Create File
       fwrite($file, $binary);
       fclose($file);
       $path = "uploads/school_expense/".$file_name.'.jpg';

       $data_img = array('id' => $insert_id, 'documents' => $path);
       $this->expense_model->add($data_img);
     } 

     $data['msg']='Addes Successfully' ;
     $data['Status'] = 1 ; 
   }
   else{
     $data['msg']='Id not Found' ;
     $data['Status']= 0 ;
   }
  }else{
   $data['msg']='Deletion Failed' ;
   $data['Status'] = 0 ;
  }
  echo json_encode($data);
  }



  function expensedelete() {
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
       $id = $request->id;    
       $this->expense_model->remove($id);
       $data['msg']='Deleted Successfully' ;
       $data['Status']= 1 ;
     }
     else{
       $data['msg']='Id not Found' ;
       $data['Status']= 0 ;
     }
   }else{
     $data['msg']='Deletion Failed' ;
     $data['Status'] = 0 ;
   }
   echo json_encode($data);
  }

  public function issueDFeeCard()
  { 
   $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data');  
   $data= ''; 
   $arr= ''; 
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
      $data['student_session_id'] = $request->student_id ;
      $data['fee_type'] = $request->fee_type ;
      $data['fee_amount'] = $request->fee_amount ;
      $this->db->insert('extra_fees',$data);
      if($this->db->insert_id()) {
       $arr['msg']  = 'Duplicate FeesCard Fees Recieved ';
       $arr['Status'] = 1;
     }else{
      $arr['msg']  = 'Fees Not Saved';
      $arr['Status'] = 0;
    }
  }else{
    $arr['msg']  =  'Student Post Data not found';
    $arr['Status'] = 0;
  }
  }else{
    $arr['msg']  =  'Post Data not found';
    $arr['Status'] = 0;
  }
  echo json_encode($arr) ;
  }


  public function issueTCFee()
  {   
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');  
    $data= ''; 
    $arr= ''; 
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
      $data['student_session_id'] = $request->student_id ;
      $data['fee_type'] = $request->fee_type ;
      $data['fee_amount'] = $request->fee_amount ;
      $data['session'] = $request->session ;
      $this->db->insert('extra_fees',$data);
                  ///tc actual fee by tc fee type and group  fee  master
      $class_post = 1001 ;
      $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($class_post,$session) ;
                  /// print_r($feesession_group_id);
                 //echo $this->db->last_query();
      if(!empty($feesession_group_id)){
        $feesession_group_ida= $feesession_group_id->id;
                   //$qrie= " SELECT * from fee_groups_feetype where fee_session_group_id = $feesession_group_ida order by feetype_id asc limit 1";
        $fee_receipt_no=$this->Api_model->feeereceipt_no();
        $qrie= "SELECT * from fee_groups_feetype where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";


        $fees_results = $this->db->query($qrie)->result();  
        foreach ($fees_results as $key => $fees_result) {
          $fees_result->fee_session_group_id ; 
          $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
          $data_fee['student_session_id'] =  $student_session_id_new ;
          $master_id = $this->Studentfeemaster_model->add($data_fee);
                      //add fee start 
          $json_array = array(
            'amount' => $fees_result->amount,
            'date' => date('Y-m-d'),
            'amount_discount' => 0,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode'=> 'cash'
          );
          $data_master = array(
            'student_fees_master_id' => $master_id,
            'fee_groups_feetype_id' => $fees_result->id,
            'receipt_no' => $fee_receipt_no,
            'amount_detail' => $json_array
          );
          $student_fees_discount_id = 0 ;
          $inserted_id = $this->Api_model->fee_deposit($data_master, $student_fees_discount_id);
          $jsonary = json_decode( $inserted_id, true);

          $data['fee_data'][] =$this->Api_model->get_fees_data( $jsonary['invoice_id'] );    
          $data['fee_details'][] = $this->Api_model->fees_result($fees_result->id); 
        }
      }
  ///tc actual fee by tc fee type and group  fee  master END 
      $arr['msg']  = 'Issue-TC Fees Recieved ';
      $arr['Status'] = 1;
    }else{
      $arr['msg']  =  'Student Post Data not found';
      $arr['Status'] = 0;
    }
  }else{
    $arr['msg']  =  'Post Data not found';
    $arr['Status'] = 0;
  }
  echo json_encode($arr) ;
  }

  public function tcList()
  {   
    $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data');  
     //    $data= ''; 
     //    $arr= ''; 
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  

      $class = $request->class_id ;
      $section = $request->section_id ;
      $session_id = $request->session_id ;
      $arr['resultlist'] = $this->student_model->searchByClassSection_api($class, $section, $session_id );       
      $arr['msg']  = 'student List for TC';
      $arr['Status'] = 1;
      echo json_encode($arr) ;
    }
    else{
      $arr['msg']  =  'Student Post Data not found';
      $arr['Status'] = 0;
    }
  }
  else{
    $arr['msg']  =  'Post Data not found';
    $arr['Status'] = 0;
  }
  echo json_encode($arr) ;
  }


  public function issueTC()
  {
    $postdata = file_get_contents("php://input");
    //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
       $id = $request->student_id;
       $session_id = $request->session_id;
       $data['student'] = $this->Student_model->api_get($id,$session_id);  
       $data['msg'] = 'Record Found' ; 
       $data['Status'] = 1 ; 
     }else{
      $data['msg'] = 'No Record Found' ; 
      $data['Status'] = 0 ; 
    }
  }else{
   $data['msg'] = 'No Record Found' ; 
   $data['Status'] = 0 ; 
  }
  echo json_encode($data);
  }
  public function PromotestudentList()
  {
   $postdata = file_get_contents("php://input");   
    //$postdata =  $this->input->post('data');    
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {          
      $class = $request->class_id;
      $section =  $request->section_id;
      $session_result = $this->session_model->get();
      $data['sessionlist'] = $session_result;
      $data['class_post'] = $class;
      $data['section_post'] = $section;
      $resultlist = $this->student_model->searchByClassSection($class, $section);
      $data['resultlist'] = $resultlist;
      $data['Status'] =    1 ; 
    }else{
      $data['msg'] = 'No Record Found' ; 
      $data['Status'] =    0 ; 
    }
  }else{
   $data['msg'] = 'No Record Found' ; 
   $data['Status'] = 0 ; 
  }
  echo json_encode($data);
  }



  public function SessionList()
  {
    $arr['session_result'] = $this->session_model->get();
    $arr['status'] = 1;
    echo json_encode($arr);
  }

 

  public function PromoteNow_without_fee()
  {
   $postdata = file_get_contents("php://input");
   // $postdata =  $this->input->post('data');    
  // $postdata = '{"id":124}';
   if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {     
      $student_id = $request->id;   
      $student = $this->student_model->get($student_id);  
      $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
      $count = 0  ;
      $checkDueFee = 0 ;
      foreach ($student_due_fee as $key => $val) {
        foreach ($val->fees as $key => $value) {
         if ($value->amount_detail == "0") {
          $count++ ;                   
        }

               //  }

      }
    }
    if ($count == 0 ) {
      $checkDueFee = 1 ;
    }


    if ($checkDueFee == 1) { 
      $result = $request->result;
      $session_status = $request->Session_status;
      if ($result == "Pass" && $session_status == "Continue") {
        $promoted_class = $request->class_promote_id;
        $promoted_section =$request->section_promote_id ;
        $promoted_session =$request->session_id;
        $data_new = array(
          'student_id' => $student_id,
          'class_id' => $promoted_class,
          'section_id' => $promoted_section,
          'session_id' => $promoted_session,
          'transport_fees' => 0,
          'fees_discount' => 0
        );
        $this->student_model->add_student_session($data_new);

      }
      elseif ($result == "Fail" && $session_status == "Continue") {
        $promoted_session = $request->session_id;
        $class_post = $request->class_post;
        $promoted_section =$request->section_promote_id ;
        $section_post = $request->section_post;
        $data_new = array(
          'student_id' => $student_id,
          'class_id' =>   $class_post,
          'section_id' => $section_post,
          'session_id' => $promoted_session,
          'transport_fees' => 0,
          'fees_discount' => 0
        );
        $this->student_model->add_student_session($data_new);
      }
      $data['msg'] = 'Student Promoted Successfully' ; 
      $data['Status'] =    1 ; 
    } else{
     $data['msg'] = 'Please Clear Previous Due for '.$count.' months' ; 
     $data['Status'] =    0 ; 
   }
  }else{
    $data['msg'] = 'No Record Found' ; 
    $data['Status'] =    0 ; 
  }
  }else{
   $data['msg'] = 'No Record Found' ; 
   $data['Status'] = 0 ; 
  }
  echo json_encode($data);
  }


  public function PromoteNow()
  {
   $postdata = file_get_contents("php://input");

   if ($postdata != '')
   {  
   
  $request = json_decode($postdata);
  if(!empty($request))
  {     
   $student_id = $request->id;   
   $session_id = $request->session_id;   
   $student = $this->student_model->api_get($student_id,$session_id);  
   $student_session_id = $student['student_session_id'];
   $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
   $count = 0  ;
   $checkDueFee = 0 ;
   $qry =  "SELECT mobileno from students where id = $student_id" ;
   $mobileno = $this->db->query($qry)->row()->mobileno;

   foreach ($student_due_fee as $key => $val) {
    foreach ($val->fees as $key => $value) {
     if ($value->amount_detail == "0") {
      $count++ ;                   
    }           
  }
  }
  if ($count == 0 ) {
    $checkDueFee = 1 ;
  }


  if ($checkDueFee == 1) 
  { 
    $result = $request->result;
    $session_status = $request->Session_status;
    if ($result == "Pass" && $session_status == "Continue") {
      $promoted_class = $request->class_promote_id;
      $promoted_section =$request->section_promote_id ;
      $promoted_session =$request->promoted_session_id;
      $class_post = $request->class_id;
      $promoted_class_id = $request->class_promote_id;
      $section_post = $request->section_id;
      $promotedate = $request->promotedate;//promotedate
      $data_new = array(
        'student_id' => $student_id,
        'class_id' => $promoted_class,
        'section_id' => $promoted_section,
        'session_id' => $promoted_session,
        'transport_fees' => 0,
        'promoted' => 1,
        'fees_discount' => 0
      );
      $student_session_id_new = $this->student_model->add_student_session($data_new);
    }
    elseif ($result == "Fail" && $session_status == "Continue") {
      $class_post = $request->class_id;
      $promoted_class_id = $request->class_id;
      $promoted_section =$request->section_promote_id ;
      $section_post = $request->section_id;
      $data_new = array(
        'student_id' => $student_id,
        'class_id' =>   $class_post,
        'section_id' => $section_post,
        'session_id' => $promoted_session,
        'transport_fees' => 0,
        'promoted' => 1,
        'fees_discount' => 0
      );
      $student_session_id_new= $this->student_model->add_student_session($data_new);
    }
 //-----------------------------------------------message
 if (!empty($mobileno)) {
  $msg_data=array(
     'title'=>'Promoted Successfully',
     'message'=>" Dear Parent, your ward has been Promoted to next Session. School Management - Central Academy Jabalpur.",
     'session_id'=>$session_id,
     'user_list'=>$mobileno,
     'student_session_id'=>$student_session_id_new
   );
  $this->Messages_model->add($msg_data);
}
 //---------------------------------------------------
    /* student fees add*/

    $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($promoted_class_id,$promoted_session) ;
                        /// print_r($feesession_group_id);
                               //echo $this->db->last_query();
    if(!empty($feesession_group_id->id)){
      $feesession_group_ida= $feesession_group_id->id;
                                

      $fee_receipt_no=$this->Api_model->feeereceipt_no();
     /* $qrie= "SELECT * from fee_groups_feetype where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";
      $fees_results = $this->db->query($qrie)->result();  */
        $qrie= "SELECT fee_groups_feetype.fee_session_group_id,fee_groups_feetype.id as feeid,feetype.code ,fee_groups.name,fee_groups_feetype.fee_groups_id, fee_groups_feetype.feetype_id, fee_groups_feetype.session_id,fee_groups_feetype.due_date, fee_groups_feetype.amount , fee_groups_feetype.is_active, fee_groups_feetype.created_at, fee_groups_feetype.fee_bydefault , fee_groups_feetype.shows from fee_groups_feetype join feetype on fee_groups_feetype.feetype_id = feetype.id   join  fee_groups on fee_groups_feetype.fee_groups_id = fee_groups.id    where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";
           $fees_result1 = $this->db->query($qrie)->result();
           $fee_receipt_no=$this->Api_model->feeereceipt_no();
           
                $data['msg']  = 'Student Added Successfully ! Please Fill up fees Data';              
                $data['Status']  = 1; 
                $data['fees_result']  =$fees_result1; 
                $data['student_session_id']  = $student_session_id_new;  
                $data['promotedate']  =  $promotedate;  
/*
      foreach ($fees_results as $key => $fees_result) {
        $fees_result->fee_session_group_id ; 
        $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
        $data_fee['student_session_id'] =  $student_session_id_new ;
        $master_id = $this->Studentfeemaster_model->add($data_fee);
                                    //add fee start 
        $json_array = array(
          'amount' => $fees_result->amount,
          'date' => date('Y-m-d'),
          'amount_discount' => 0,
          'amount_fine' => 0,
          'description' => 'Admin',
          'payment_mode' => 'cash'
        );
        $data_master = array(
          'student_fees_master_id' => $master_id,
          'fee_groups_feetype_id' => $fees_result->id,
          'amount_detail' => $json_array
        );
        $student_fees_discount_id = 0 ;
        $inserted_id = $this->Api_model->fee_deposit($data_master, $student_fees_discount_id);
        $jsonary = json_decode( $inserted_id, true);

        $data['fee_data'][] =$this->Api_model->get_fees_data( $jsonary['invoice_id'] );    
        $data['fee_details'][] = $this->Api_model->fees_result($fees_result->id); 
      }
*/                             
                                 
    }
    else{
      $data['msg']  = 'Fees Not Assigned'; 
      $data['Status'] = 0;   
    }
           } 
              else{
               $data['msg'] = 'Please Clear Previous Due for '.$count.' months' ; 
               $data['Status'] =    0 ; 
             }
           } else{
            $data['msg'] = 'No Record ss Found' ; 
            $data['Status'] =    0 ; 
          }
        }
        else{
         $data['msg'] = 'No Record Found' ; 
         $data['Status'] = 0 ; 
       }
       echo json_encode($data);
     }
//new function for admission with discount fee//



  public function studentadmission()
  {
   $postdata = file_get_contents("php://input");
   //$postdata =  $this->input->post('data');    
  /* $postdata = '{
    "admission_number": "545455",
    "first_name": "ddddddddd",
    "last_name" : "ddddddddd",
    "class_id": "28",
    "section": "17",
    "gender": "Male",
    "dob": "2019-03-20",
    "admission_date": "2019-03-20",
    "adhar_no": "fdsfdsfdsfdsfds",
    "samagra_id": "fdsfdfdfdsfd",
    "bank_no": "errewrewredfsdf",
    "guardian_address": "sfdsfdsfdsfdsfd",
    "mother_name": "dsdfldf",
    "mother_phone": "43434",
    "father_name": "gfdgdfgd",
    "father_phone": "43434",
    "guardian_relation": "Mother",
    "guardian_phone": "43434",
    "guardian_name": "dsdfldf",
    "session_id": "13",
    "is_bpl" :false
  }'; */
  if ($postdata != '')
  {  
    $request = json_decode($postdata);
    if(!empty($request))
    {  
      $session_id = $request->session_id;
      $class_id = $request->class_id;
      $section_id = $request->section;
      $is_bpl = $request->is_bpl;
      $fees_discount = 0;
      $vehroute_id =  '';
      $vehicle_id =  '';
      $transport_fees = 0;    
      $hostel_room_id =0;            
      if (empty($vehroute_id)) {
        $vehroute_id = 0;
      }
      if (empty($transport_fees)) {
        $transport_fees = 0;
      }
      if (empty($hostel_room_id)) {
        $hostel_room_id = 0;
      }
      
      $mobnumber = $request->guardian_phone ;
      $data = array(
        'admission_no' =>$request->admission_number,
        'admission_date' => date('Y-m-d', strtotime( $request->admission_date)),
        'firstname' => $request->first_name,
        'lastname' =>$request->last_name,
        'mobileno' => $request->guardian_phone,
        'rte' => 'No',  
        'dob' => date('Y-m-d', strtotime($request->dob)),
        'current_address' => ($request->guardian_address)?$request->guardian_address:'No',
        'permanent_address' => ($request->guardian_address)?$request->guardian_address:'No',
        'image' => 'uploads/student_images/no_image.png',
        'category_id' => 1,
        'adhar_no' => ($request->adhar_no)?$request->adhar_no:'No',
        'samagra_id' => ($request->samagra_id)?$request->samagra_id:'No',
        'bank_account_no' => ($request->bank_no)?$request->bank_no:0,
        'father_name' =>  $request->father_name,
        'father_phone' => $request->father_phone,
        'mother_name' => $request->mother_name,
        'mother_phone' => $request->mother_phone,
        'gender' => $request->gender,
        'guardian_name' => $request->guardian_name,
        'guardian_relation' => $request->guardian_relation,
        'guardian_phone' => $request->guardian_phone,
        'guardian_address' => $request->guardian_address,
        'vehroute_id' =>  ($vehroute_id)?$vehroute_id:0,
        'hostel_room_id' => ($hostel_room_id)?$hostel_room_id:0,
        'school_house_id' => 0,
        'is_active' => 'yes',
        'guardian_is' =>'father'
     // 'measurement_date' => date('Y-m-d', strtotime($this->input->post('measure_date')))
      );

      $ifexist = $this->student_model->check_AdmissionNo($request->admission_number);
      if($ifexist){
       $outputarry['msg']  = 'This '.$request->admission_number.' Admission Number Already Exist';              
       $outputarry['Status']  = 0; 
     } 
     else
     {

      $insert_id = $this->student_model->add($data);
      $data_new = array(
        'student_id' => $insert_id,
        'class_id' => $class_id,
        'section_id' => $section_id,
        'session_id' => $session_id,
        'route_id' => $vehroute_id,
        'vehicle_id' => $vehicle_id,
        'transport_fees' => $transport_fees,
        'fees_discount' => $fees_discount
      );

      $student_session_id = $this->student_model->add_student_session($data_new);

      $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
      $sibling_id = $this->input->post('sibling_id');
      $data_student_login = array(
        'username' => $this->student_login_prefix . $insert_id,
        'password' => $user_password,
        'user_id' => $insert_id,
        'role' => 'student');

      $this->user_model->add($data_student_login);

      if ($vehicle_id) {
        $student_fee = $this->db->get_where('student_session', array('student_id' => $insert_id))->row();
        $mydate = "10-04-" .date('Y');
        $time = strtotime("$mydate");
        $j = 0;
        for ($i = 1; $i <= 11; $i++) {
          $final = date("Y-m-d", strtotime("+$j month", $time));
          if ($i == 2) {
            $j++;
            $final = date("Y-m-d", strtotime("+$j month", $time));
            $insert_array = 
            array(
              'student_session_id' => $student_session_id,
              'fee_session_group_id' => 0,
              'due_date' => $final,
              'fees_amount' => $student_fee->transport_fees / 2,
            );
          } else {
            $insert_array = array(
              'student_session_id' => $student_session_id,
              'fee_session_group_id' => 0,
              'due_date' => $final,
              'fees_amount' => $student_fee->transport_fees,
            );
          }
          $inserted_id = $this->transport_custom_model->add3($insert_array);
          $preserve_record[] = $inserted_id;
          $j++;
        }
      }
      $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
      $temp = $insert_id;
      $data_parent_login = array(
        'username' => $this->parent_login_prefix . $insert_id,
        'password' => $parent_password,
        'user_id' => 0,
        'role' => 'parent',
        'childs' => $temp
      );

      $ins_parent_id = $this->user_model->add($data_parent_login);
      $update_student = array(
        'id' => $insert_id,
        'parent_id' => $ins_parent_id
      );
      $this->student_model->add($update_student);


      $userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session_id);

      if (!empty($userlisting)) {
        foreach ($userlisting as $userlisting_key => $userlisting_value) {
          if ($userlisting_value['guardian_phone']) {
            $arr[] = $userlisting_value['guardian_phone'];
            $user_array = implode(',', $arr);
          }
          else{
            $arr[] = $userlisting_value['mobileno'];
            $user_array = implode(',', $arr);
          }
        }
      }
      $students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session_id);
      if (!empty($students_no))
      {
        $data_num = array(
          'id' => $students_no['id'],
          'numbers' => $user_array,
        );
      } else 
      {
        $data_num = array(
          'session_id' => $session_id,
          'class_id' => $class_id,
          'section_id' => $section_id,
          'numbers' => $user_array,
        );
      } 
      $this->student_model->add_all_student_no($data_num); 
      /* student fees add*/  
      //message sending from here
      if (!empty($mobnumber)) {
        $msg_data=array(
           'title'=>'Admission Successfully',
           'message'=>" Dear Parent, your ward has been register with Us . Thanks for the Admission. School Management - Central Academy Jabalpur.",
           'session_id'=>$session_id,
           'user_list'=>$mobnumber,
           'student_session_id'=>$student_session_id
         );
        $this->Messages_model->add($msg_data);
      }
      //------------------------------------------------
      if ($is_bpl) 
      {

        $outputarry['msg']  = 'BPL Student Added Successfully No Fees Charges Apply';              
        $outputarry['Status']  = 1; 
      } 
      else 
      {
        $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($class_id,$session_id) ;
        $feesession_group_ida= $feesession_group_id->id;
        $qrie= "SELECT fee_groups_feetype.fee_session_group_id,fee_groups_feetype.id as feeid,feetype.code ,fee_groups.name,fee_groups_feetype.fee_groups_id, fee_groups_feetype.feetype_id, fee_groups_feetype.session_id,fee_groups_feetype.due_date, fee_groups_feetype.amount_II as amount , fee_groups_feetype.is_active, fee_groups_feetype.created_at, fee_groups_feetype.fee_bydefault , fee_groups_feetype.shows from fee_groups_feetype join feetype on fee_groups_feetype.feetype_id = feetype.id   join  fee_groups on fee_groups_feetype.fee_groups_id = fee_groups.id    where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";
           $fees_result1 = $this->db->query($qrie)->result();
           $fee_receipt_no=$this->Api_model->feeereceipt_no();
            
                $outputarry['msg']  = 'Student Added Successfully ! Please Fill up fees Data';              
                $outputarry['Status']  = 1; 
                $outputarry['fees_result']  =$fees_result1; 
                $outputarry['student_session_id']  = $student_session_id;    
                $outputarry['admissiondate']  = date('Y-m-d', strtotime( $request->admission_date));          
                
         }               // else end for bpl
       }                // else end for admission no exist
     }                 // if end for request data 
               else{
                $outputarry['msg']  = 'Fail To Add Data';              
                $outputarry['Status']  = 0; 
              }     
            } 
            else{
              $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
            }         
            echo json_encode($outputarry);
   }  


//student admission with fee ends here
//admission fee new function
public function add_promote_fee()
{
     $postdata = file_get_contents("php://input");
     //file_put_contents('test.html', $postdata);  
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   {  
          $fees_result1 = $request->fees_result;
          $discount = $request->discount;
          $student_session_id = $request->student_session_id;  
          if($request->promotedate) 
          $promotedate = $request->promotedate;
          else{
            $promotedate = date('Y-m-d')  ;  
          }        
                
          
         $fee_receipt_no=$this->Api_model->feeereceipt_no();
        foreach ($fees_result1 as $key => $fees_result)
        {  
          $fees_result->fee_session_group_id ; 
          $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
          $data_fee['student_session_id'] =  $student_session_id ;
          $master_id = $this->Studentfeemaster_model->add($data_fee);
         //add fee start

          if ($fees_result->shows) {
           
           $json_array = array(
            'amount' => $fees_result->amount,
            'date' =>  $promotedate,
            'amount_discount' => $discount,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode' => 'cash'
          );
          }else{
             $json_array = array(
            'amount' => $fees_result->amount,
            'date' =>  $promotedate,
            'amount_discount' =>0,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode' => 'cash'
          );
          }
         

          $data_master = array(
            'student_fees_master_id' => $master_id,
            'fee_groups_feetype_id' => $fees_result->feeid,
            'receipt_no' => $fee_receipt_no,
            'amount_detail' => $json_array
          );
          $student_fees_discount_id = 0;
          $inserted_id = $this->Api_model->fee_deposit($data_master, $student_fees_discount_id);
          if($inserted_id){
          $jsonary = json_decode( $inserted_id, true);
          $outputarry['msg']  = 'Student Added successfully And All Assigned Fee Paid For First Time'; 
          $outputarry['Status'] = 1;   
          $outputarry['receipt_no'] = $fee_receipt_no;   
          $outputarry['fee_data'][] =$this->Api_model->get_fees_data($jsonary['invoice_id']);    
          $outputarry['fee_details'][] = $this->Api_model->fees_result($fees_result->feeid);  
          $outputarry['student_session_id']  = $student_session_id;   
        }else{
          $outputarry['msg']  = 'Fees Not Submitted Due to Some Server Issue .Please Send Student details to Admin '; 
          $outputarry['Status'] = 0;  
         }
        }
      }
      else{
        $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
      }
    }
    else{
      $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
    }

      echo json_encode($outputarry);
}
         


      public function add_admission_fee()
{
     $postdata = file_get_contents("php://input");
     //file_put_contents('test.html', $postdata);
  
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   {  
          $fees_result1 = $request->fees_result;
          $discount = $request->discount;
          $student_session_id = $request->student_session_id;        
          $admission_date = $request->admissiondate;    
          
         $fee_receipt_no=$this->Api_model->feeereceipt_no();
        foreach ($fees_result1 as $key => $fees_result)
        {  
          $fees_result->fee_session_group_id ; 
          $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
          $data_fee['student_session_id'] =  $student_session_id ;
          $master_id = $this->Studentfeemaster_model->add($data_fee);
         //add fee start

          if ($fees_result->shows) {
           // print_r($fees_result->shows) ; die;
           $json_array = array(
            'amount' => $fees_result->amount,
            'date' => $admission_date,
            'amount_discount' => $discount,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode' => 'cash'
          );
          }else{
             $json_array = array(
            'amount' => $fees_result->amount,
            'date' => $admission_date,
            'amount_discount' =>0,
            'amount_fine' => 0,
            'description' => 'Admin',
            'payment_mode' => 'cash'
          );
          }
          $data_master = array(
            'student_fees_master_id' => $master_id,
            'fee_groups_feetype_id' => $fees_result->feeid,
            'receipt_no' => $fee_receipt_no,
            'amount_detail' => $json_array
          );
          $student_fees_discount_id = 0;
          $inserted_id = $this->Api_model->fee_deposit($data_master, $student_fees_discount_id);
          if($inserted_id){
          $jsonary = json_decode( $inserted_id, true);
          $outputarry['msg']  = 'Student Added successfully And All Assigned Fee Paid For First Time'; 
          $outputarry['Status'] = 1;   
          $outputarry['receipt_no'] = $fee_receipt_no;   
          $outputarry['fee_data'][] =$this->Api_model->get_fees_data($jsonary['invoice_id']);    
          $outputarry['fee_details'][] = $this->Api_model->fees_result_admissioin($fees_result->feeid);  
          $outputarry['student_session_id']  = $student_session_id;   
        }else{
          $outputarry['msg']  = 'Fees Not Submitted Due to Some Server Issue .Please Send Student details to Admin '; 
          $outputarry['Status'] = 0;  
         }
        }
      }
      else{
        $outputarry['msg']  = 'Empty Posted Data';              
        $outputarry['Status']  = 0; 
      }
    }
    else{
      $outputarry['msg']  = 'Empty Posted Data';              
      $outputarry['Status'] = 0; 
    }
     echo json_encode($outputarry);
}
      
      //id card new apis


 public function updateStudent()
 {
        $postdata = file_get_contents("php://input");
         //file_put_contents('test.html', $postdata);  
     if ($postdata != '')
     {  
       $request = json_decode($postdata);
       if(!empty($request))
       { 
       /* $data['id'] = $request->id ;*/
        $data['firstname'] = $request->firstname ;
        $data['lastname'] = $request->lastname;
        $data['father_name'] = $request->father_name ;
        $data['mother_name'] = $request->mother_name ;
        $data['mobileno'] = $request->mobileno ;
        $data['permanent_address'] = $request->address ;
        $data['dob'] = $request->dob ;
        $data['school_house_id'] = $request->house_name ;
        //$data['image'] = $request->image ;
        $base = $request->image ;
        if (!empty($base)) {
          $saveName = "stu".date("siHdmY").".jpg";
        //file_put_contents('test.html',$_REQUEST['myFile']);
        $saveAs = "uploads/student_images/".$saveName; 
        $binary=base64_decode($base);
        
          header('Content-Type: bitmap; charset=utf-8');
    
          $file = fopen(FCPATH.'/uploads/student_images/'.$saveName, 'wb');
          // Create File
          fwrite($file, $binary);
          fclose($file);
          $data['image'] = $saveAs ; 
        }
       
        $updated = $this->Api_model->update_student($data,$request->id );
         if($updated){
                     $outputarry['msg']  = 'Student`s Details Updated Successfully';              
                     $outputarry['Status'] = 1; 
         }
          else{
                  $outputarry['msg'] = 'Updation failed';              
                  $outputarry['Status'] = 0; 
             }
        }else{
                  $outputarry['msg'] = 'Empty request Data';              
                  $outputarry['Status'] = 0; 
           }
        }
    else{
                  $outputarry['msg']  = 'Empty Posted Data';              
                  $outputarry['Status']  = 0; 
      }
      echo json_encode($outputarry);
 }

 public function teacherList()
 { 
  $postdata = file_get_contents("php://input");
  //$postdata =$this->input->post('data');
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request->date))
   { 
   $todaydate =  $request->date;
    $data_result= $this->db->query("SELECT staff.*, staff_attendance.*, staff_attendance.staff_attendance_type_id as attendance_type, staff_attendance.id as staff_attendance_id from staff join staff_attendance on staff_attendance.staff_id = staff.id where  staff_attendance.date = '".$todaydate."'")->result();
 $outputarry['Msg']  = 'Staff Data Found';              
$outputarry['Status']  = 1; 
$outputarry['attendance_status']  = 2; 
$outputarry['teacherList'] =$data_result;
if(empty($data_result)){

 $data_result =  $this->Api_model->allteachers();
   $outputarry['Msg']  = 'Staff Data Found';              
   $outputarry['Status']  = 1; 
   $outputarry['attendance_status']  = 1; 
 
$outputarry['teacherList'] =$data_result;
}
 }
 else{
 $data_result =  $this->Api_model->allteachers();
   $outputarry['Msg']  = 'Staff Data Found';              
   $outputarry['Status']  = 1; 
  $outputarry['attendance_status']  = 1; 
$outputarry['teacherList'] =$data_result;
   
    
  }

}
echo json_encode($outputarry);
 }


 public function teacherById()
 { 

  $postdata = file_get_contents("php://input");
 // $postdata = 1 ; 
     //file_put_contents('test.html', $postdata);
  
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   { 

   $id =  $request->id;
   // $id = 1;
   $teacher =  $this->Api_model->teacher_by_id($id);
   $outputarry['msg']  = 'Successfully';              
   $outputarry['Status']  = 1; 
   $outputarry['teacherDetail'] = $teacher;
    
   }else{
     $outputarry['msg']  = 'Empty request Data';              
              $outputarry['Status']  = 0; 
   }
    }else{
   $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
  }
    echo json_encode($outputarry);
 }


public function updateTeacher()
 {
    $postdata = file_get_contents("php://input");
     //file_put_contents('test.html', $postdata);
  
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   {    
    $data['name'] = $request->name ;
    $data['surname'] = $request->surname;
    $data['father_name'] = $request->father_name;
    $data['designation'] = $request->designation;
    $data['contact_no'] = $request->contact_no;
    $data['permanent_address'] = $request->permanent_address;
      $base = $request->image ;
 if (!empty($base)) {
    $saveName = "tea".date("siHdmY").".jpg";
        //file_put_contents('test.html',$_REQUEST['myFile']);
        $saveAs = "uploads/staff_images/".$saveName; 
        $binary=base64_decode($base);
        
          header('Content-Type: bitmap; charset=utf-8');
    
          $file = fopen(FCPATH.'/uploads/staff_images/'.$saveName, 'wb');
          // Create File
          fwrite($file, $binary);
          fclose($file);
          $data['image'] = $saveAs ; 
 }
       
    $updated = $this->Api_model->update_teacher($data,$request->id);
     if($updated){
                  $outputarry['msg']  = 'Teacher`s Details Updated Successfully';              
                  $outputarry['Status']  = 1; 
     }
      else{
              $outputarry['msg']  = 'Updation failed';              
              $outputarry['Status']  = 0; 
      }
    }else{
              $outputarry['msg']  = 'Empty request Data';              
              $outputarry['Status']  = 0; 
         }
    }
else{
              $outputarry['msg']  = 'Empty Posted Data';              
              $outputarry['Status']  = 0; 
    }
  echo json_encode($outputarry);
 }

//due fee by class section 
public function duefee_byclassSection()
{
      $postdata = file_get_contents("php://input");      
      //$postdata='{"class_id": "28","section_id":"17","session_id":"13"}';
     if ($postdata != ''){   
          $request = json_decode($postdata); 
      if (!empty($request)){        
       $class_id = $request->class_id; 
       $section = $request->section_id ; 
       $session = $request->session_id ; 
       $student_list = $this->student_model->searchByClassSection_api($class_id, $section,$session);

   foreach ($student_list as $key => $value) { 
   $datas = array();    
  
           $student_due_fee = $this->studentfeemaster_model->getStudentFees($value['student_session_id']);    
              
    foreach ($student_due_fee as $key => $val) {
   $finalfee = [] ;
        foreach ($val->fees as $key => $feee) {
       
                 if ($value['promote'] == '0' ) {
                    $fee['amount']= $feee->amount_II ;
                  }
                  else{
                    $fee['amount']= $feee->amount ;
                  }  

              if ($feee->amount_detail == '0' ) {
                      $fee['status']= 'Unpaid' ; 
                  }
                  else{
                      $fee['status']= 'Paid' ; 
                  }   
               $fee['remark']= $feee->name ;
               $fee['due_date']= $feee->due_date ;
               $fee['code']= $feee->code ;             
   if ($feee->amount_detail == '0'  && $fee['amount'] != '0.00') {
                $finalfee[] = $fee  ;
              }
        }
        $datas['student_session_id'] = $value['student_session_id'];
        $datas['mobile'] = $value['mobile_number'];
        $datas['admission_no'] = $value['admission_no'];
               $datas['firstname'] = $value['firstname'];
               $datas['lastname'] = $value['lastname'];
               $datas['fathername'] = $value['father_name'];
               $datas['class'] = $value['class'];
               $datas['section'] = $value['section'];
               $datas['phone'] = $value['guardian_phone'];
               $datas['fees'] =  $finalfee;
         }
                 $datai[] =   $datas; 
      }
        $data['feestu'][][]  =$datai ;
        $data['msg'] = "Result  Found" ;
        $data['Status'] = 1 ;
   }else{
     $data['msg'] = "Request Data Not Found" ;
     $data['Status'] = 0 ;
       }
  }else{
     $data['msg'] = "Post Data Not Found" ;
     $data['Status'] = 0 ;
  } 
     echo json_encode($data);
}




// due fee by class only
public function duefee_byclass()
{       
       $postdata = file_get_contents("php://input");      
       //$postdata='{"class_id": "28","session_id":"13"}';
     if ($postdata != ''){   
          $request = json_decode($postdata); 
         
      if (!empty($request)){        
       $class_id = $request->class_id; 
       $sections = $this->section_model->getClassBySection($class_id);

       $session = $request->session_id ; 

foreach ($sections as $key => $section) {
  $datai = array();  
     $student_list = $this->student_model->searchByClassSection_api($class_id, $section['section_id'],$session);     
     foreach ($student_list as $key => $value) { 
      $datas = array();    
    
             $student_due_fee = $this->studentfeemaster_model->getStudentFees($value['student_session_id']);    
        // print_r($value) ; die;            
      foreach ($student_due_fee as $key => $val) {
     $finalfee = [] ;
          foreach ($val->fees as $key => $feee) {
         
                   if ($value['promote'] == '0' ) {
                      $fee['amount']= $feee->amount_II ;
                    }
                    else{
                      $fee['amount']= $feee->amount ;
                    }  

                if ($feee->amount_detail == '0' ) {
                        $fee['status']= 'Unpaid' ; 
                    }
                    else{
                        $fee['status']= 'Paid' ; 
                    }   
                 $fee['remark']= $feee->name ;
                 $fee['due_date']= $feee->due_date ;
                 $fee['code']= $feee->code ;             
            if ($feee->amount_detail == '0'  && $fee['amount'] != '0.00') {
                     $finalfee[] = $fee  ;
                }
              }
                  $datas['student_session_id'] = $value['student_session_id'];
                  $datas['mobile'] = $value['mobile_number'];
                  $datas['admission_no'] = $value['admission_no'];
                 $datas['firstname'] = $value['firstname'];
                 $datas['lastname'] = $value['lastname'];
                 $datas['fathername'] = $value['father_name'];
                 $datas['class'] = $value['class'];
                 $datas['section'] = $value['section'];
                 $datas['phone'] = $value['guardian_phone'];
                 $datas['fees'] =  $finalfee;
           }
           if ($section['section_id'] == $value['section_id']) {           
                
                   $datai[] =   $datas; 
              }
  }
          $finaldata[][]=$datai ; 

}

  //print_r($data);die;
      ///////////////////////////////////////////////
      $data['feestu']  =$finaldata ;
       $data['msg'] = "Result  Found" ;
       $data['Status'] = 1 ;
      
   }else{
     $data['msg'] = "Request Data Not Found" ;
     $data['Status'] = 0 ;
       }
  }else{
     $data['msg'] = "Post Data Not Found" ;
     $data['Status'] = 0 ;
  } 
     echo json_encode($data);
}

// due fee list by month class section

public function duefee_byMonth_classSection()
  {
    $postdata = file_get_contents("php://input");      
  // $postdata='{"class_id": "40","session_id":"14","month":"01"}';
       if ($postdata != ''){   
            $request = json_decode($postdata); 
        if (!empty($request)){        
         $class_id = $request->class_id; 
         //$section = $request->section_id ;          
         $session = $request->session_id ; 
         $month = $request->month ;


         $sections = $this->section_model->getClassBySection($class_id);
        
         foreach ($sections as $key => $section) {
          $student_list = array();
          $datai = array();
           
             $student_list = $this->student_model->searchByClassSection_api($class_id, $section['section_id'],$session); 
        // $student_list = $this->student_model->searchByClassSection_api($class_id, $section,$session);
   
foreach ($student_list as $key => $value) { 
      $datas = array();   
  $montharray = $this->getmonth_array($month);
   //print_r($montharray); die;
      $student_due_fee = $this->studentfeemaster_model->getStudentFees_by_month($value['student_session_id'],$montharray);          
      foreach ($student_due_fee as $key => $val) {
     $finalfee = [] ;
          foreach ($val->fees as $key => $feee) {
         
                   if ($value['promote'] == '0' ) {
                      $fee['amount']= $feee->amount_II ;
                    }
                    else{
                      $fee['amount']= $feee->amount ;
                    }  

                if ($feee->amount_detail == '0' ) {
                        $fee['status']= 'Unpaid' ; 
                    }
                    else{
                        $fee['status']= 'Paid' ; 
                    }   
                 $fee['remark']= $feee->name ;
                 $fee['due_date']= $feee->due_date ;
                 $fee['code']= $feee->code ;             
     if ($feee->amount_detail == '0'  && $fee['amount'] != '0.00') {              
                  $finalfee[] = $fee ;                                
                }
          }
              $datas['student_session_id'] = $value['student_session_id'];
              $datas['mobile'] = $value['mobile_number'];
              $datas['admission_no'] = $value['admission_no'];
              $datas['firstname'] = $value['firstname'];
              $datas['lastname'] = $value['lastname'];
              $datas['fathername'] = $value['father_name'];
              $datas['class'] = $value['class'];
              $datas['section'] = $value['section'];
              $datas['phone'] = $value['guardian_phone'];
              $datas['fees'] =  $finalfee;
           }
                         
                  if ($section['section_id'] == $value['section_id']) { 
                    if(!empty($finalfee)){
                      $datai[] =   $datas; 
                    }        
                 }



             } //student list end
              if($datai){
                $final[]=$datai ; 
              }
                  
       }//section end
       $finals[]=$final ; 
      $data['feestu']  = $finals ;
     $data['msg'] = "Result  Found" ;
         $data['Status'] = 1 ;
     }else{
       $data['msg'] = "Request Data Not Found" ;
       $data['Status'] = 0 ;
         }
    }else{
       $data['msg'] = "Post Data Not Found" ;
       $data['Status'] = 0 ;
    } 
       echo json_encode($data);
  }


  


//////////////////////////////////////

public function duefee_by_Month()
  { 
   // echo "<pre>";
   $postdata = file_get_contents("php://input");      
  //$postdata='{"month":"07","session_id":"14"}';
 //$postdata = $this->input->post('data');  
       if ($postdata != ''){   
            $request = json_decode($postdata); 
        if (!empty($request)){        
         $session = $request->session_id ; 
         $month = $request->month ; 
         $class_list = $this->class_model->get();
         $classsss = array();
foreach ($class_list as $key => $class) {
       
       $sections = $this->section_model->getClassBySection($class['id']);
       $session = $request->session_id ; 
       $final = array(); 
foreach ($sections as $key => $section) {
  $student_list = array();
  
     $datai = array();
     $student_list = $this->student_model->searchByClassSection_api($class['id'], $section['section_id'],$session);
    
     if (!empty($student_list)) {
       
     foreach ($student_list as $key => $value) { 
     $datas = array();    
     $montharray = $this->getmonth_array($month);
             $student_due_fee = $this->studentfeemaster_model->getStudentFees_by_month($value['student_session_id'],$montharray);             
             
      foreach ($student_due_fee as $key => $val) {
     $finalfee = [] ;
          foreach ($val->fees as $key => $feee) {
         
                   if ($value['promote'] == '0' ){
                      $fee['amount']= $feee->amount_II ;
                    }else{
                      $fee['amount']= $feee->amount ;
                         }  
                if ($feee->amount_detail == '0' ) {
                        $fee['status']= 'Unpaid' ; 
                    }
                    else{
                        $fee['status']= 'Paid' ; 
                    }   
                 $fee['remark']= $feee->name ;
                 $fee['due_date']= $feee->due_date ;
                 $fee['code']= $feee->code ;             
     if ($feee->amount_detail == '0' && $fee['amount'] != '0.00'){
                  $finalfee[] = $fee  ;
                }
             }
                 $datas['student_session_id'] = $value['student_session_id'];
                 $datas['mobile'] = $value['mobile_number'];
                 $datas['admission_no'] = $value['admission_no'];
                 $datas['firstname'] = $value['firstname'];
                 $datas['lastname'] = $value['lastname'];
                 $datas['fathername'] = $value['father_name'];
                 $datas['class'] = $value['class'];
                 $datas['section'] = $value['section'];
                 $datas['phone'] = $value['guardian_phone'];
                 $datas['fees'] =  $finalfee;
           }
           if ($section['section_id'] == $value['section_id']) { 
              if (!empty($finalfee)) {
                if (!empty( $datas)) {
                  $datai[] =   $datas; 
                }
               

                }         
              }
        }//student list end     
        //  if ($class['id'] == $value['class_id']) {            
        //            $dataj[] =   $datai; 
        //       }
        if (!empty($datai)) {
          $final[]=$datai ; 
        }
         
           }//if ends
          /*  else{
            $final = 0 ;
           } */
          
     }//section end
   ///  print_r($final);
     if(!empty($final) && $final != ''){
      $classsss[]  = $final ; 
     }
          
       } ////class ends here
         
       if ($classsss !=  0 ) {
         
        //print_r($classsss);
       
         $data['feestu'] = $classsss ;
         $data['msg'] = "Result  Found" ;
         $data['Status'] = 1 ;
       }else{
        $data['msg'] = "Request Data Not Found" ;
        $data['Status'] = 0 ;
       }
     }else{
       $data['msg'] = "Request Data Not Found" ;
       $data['Status'] = 0 ;
         }
    }else{
       $data['msg'] = "Post Data Not Found" ;
       $data['Status'] = 0 ;
    } 
       echo json_encode($data);
  } 




  public function editStudent()
  {

     $postdata = file_get_contents("php://input");      
         //$postdata='{"class_id": "28","section_id":"17","session_id":"13","month":"06"}';
         if ($postdata != ''){   
              $request = json_decode($postdata); 
          if (!empty($request)){  
    $base = $request->student_photo;
  /* if (!empty($base)) {
      $saveName = "stu".date("siHdmY").".jpg";
          //file_put_contents('test.html',$_REQUEST['myFile']);
          $saveAs = "uploads/student_images/".$saveName ; 
          $binary=base64_decode($base);          
            header('Content-Type: bitmap; charset=utf-8');      
            $file = fopen(FCPATH.'/uploads/student_images/'.$saveName, 'wb');
            // Create File
            fwrite($file, $binary);
            fclose($file);
            // $datas['image'] = $saveAs; 
                 } */
           $student_id  =  $request->id;
           $student_session_id  =  $request->student_session_id;
$datas = array(
    'admission_no' =>$request->admission_number,
    'roll_no' => $request->roll_number,
    'admission_date' => date('Y-m-d', strtotime($request->admission_date)),
    'dob' => date('Y-m-d',strtotime($request->dob)),
    'firstname' => $request->first_name,
    'lastname' =>$request->last_name,
    'mobileno' => ($request->mobile_number)?$request->mobile_number:'0',
    'rte' => ($request->rte)?$request->rte:'No',
    'email' => ($request->email)?$request->email:'No',
    'guardian_is' => $request->if_guardian_is,
    'religion' =>($request->religion)?$request->religion:'Indian', 
    'cast' => ($request->caste)?$request->caste:'No', 
    'previous_school' =>($request->previous_school_details)?$request->previous_school_details:'No',
    'current_address' => ($request->current_address_type)?$request->current_address_type:'No',
    'permanent_address' => ($request->permanent_address_type)?$request->permanent_address_type:'No',                  
    'category_id' => ($request->category)?$request->category:'1',
    'adhar_no' => ($request->national_identification_number)?$request->national_identification_number:'No',
    'samagra_id' => ($request->local_identification_number)?$request->local_identification_number:'No',
    'bank_account_no' => ($request->bank_account_number)?$request->bank_account_number:0,
    'bank_name' => ($request->bank_name)?$request->bank_name:'No',
    'ifsc_code' => ($request->ifsc_code)?$request->ifsc_code:'No',
    'father_name' =>  $request->father_name,
    'father_phone' => $request->father_phone,
    'father_occupation' => ($request->father_occupation)?$request->father_occupation:'No',
    'mother_name' => $request->mother_name,
    'mother_phone' => $request->mother_phone,
    'mother_occupation' => ($request->mother_occupation)?$request->mother_occupation:'No',
    'guardian_occupation' =>($request->guardian_occupation)?$request->guardian_occupation:'No', 
    'guardian_email' => ($request->guardian_email)?$request->guardian_email:'No',
    'gender' => $request->gender,
    'guardian_name' => $request->guardian_name,
    'guardian_relation' => $request->guardian_relation,
    'guardian_phone' => $request->guardian_phone,
    'guardian_address' => $request->guardian_address,               
    'school_house_id' => ($request->student_house)?$request->student_house:0,
    'blood_group' => ($request->blood_group)?$request->blood_group:0, 
    'height' => ($request->height)?$request->height:0,  
    'weight' =>  ($request->weight)?$request->weight:0,               
    ) ;
             $datas2 = array(
             'section_id' =>  ($request->section_id)?$request->section_id:0,  
                );
            if($student_session_id > 0){
            $this->db->where('id',$student_session_id);
            $this->db->update('student_session',$datas2);
          }
           if($student_id > 0){
            $this->db->where('id',$student_id);
            $this->db->update('students',$datas);
            $data['msg'] = "Student Updated Successfully" ;
           $data['Status'] = 1 ;
           }   
            else{
            $data['msg'] = "Failed To Save" ;
            $data['Status'] = 0;
           } 
           
           
          }else{
            $data['msg'] = "Request Data Not Found" ;
             $data['Status'] = 0;
          }
        }else{
          $data['msg'] = "Post Data Not Found" ;
         $data['Status'] = 0;
        }
        echo json_encode($data);
  }



  public function categoryList()
  {
     $category = $this->category_model->get();
     if (!empty($category)) {
       $data['category'] =  $category  ;
       $data['Status'] =1 ;
       $data['msg'] =  "Category found" ;
     }else{
      $data['msg'] =  "No Category found " ;
      $data['Status'] =0 ;
     }
     echo json_encode($data);
  }
    


  public function studentDetails()
  {
    $postdata = file_get_contents("php://input");      
      if (!empty($postdata)) {
        $request = json_decode($postdata); 
        $id = $request->id;
        $session_id = $request->session_id ;
        $student_details = $this->Student_model->api_get($id,$session_id);       
        if (!empty($request)){
          $data['details'] =  $student_details ; 
          $data['msg'] =  "Result found " ;
          $data['Status'] =1 ;
        }else{
          $data['msg'] =  "No request found " ;
          $data['Status'] =0 ;
        } 
      }else{
        $data['msg'] =  "No Postdata found " ;
        $data['Status'] =0 ;
      }
      echo json_encode($data);
  }

  
  
   // new disable student api
public function disable_student()
{
      $postdata = file_get_contents("php://input");      
        if (!empty($postdata)) {
        $request = json_decode($postdata); 
        $id = $request->id;
        if (!empty($id)){        
                        $data = array('is_active' => "no", 'disable_at' => date("Y-m-d"));
                        $this->student_model->disableStudent($id, $data);
                           $data['msg'] =  "Student Disable Successfully" ;
                           $data['Status'] =1 ;
                        }else{
                           $data['msg'] =  "No Requestdata found" ;
                           $data['Status'] =0 ;
                        }
            }else{
             $data['msg'] =  "No Postdata found " ;
             $data['Status'] =0 ;
             }
             echo json_encode($data);
}

// new enable student api
    
public function enable_student()
{
      $postdata = file_get_contents("php://input");      
        if (!empty($postdata)) {
        $request = json_decode($postdata); 
        $id = $request->id;
        if (!empty($id)){        
                        $data = array('is_active' => "yes");
                        $this->student_model->disableStudent($id, $data);
                           $data['msg'] =  "Student Disable Successfully" ;
                           $data['Status'] =1;
                        }else{
                           $data['msg'] =  "No Requestdata found" ;
                           $data['Status'] =0 ;
                        }
            }else{
             $data['msg'] =  "No Postdata found " ;
             $data['Status'] =0 ;
             }
             echo json_encode($data);
}



 public function get_tc_data(){
  $postdata = file_get_contents("php://input");
    // $postdata =  $this->input->post('data');  
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   { 
    $admission_no=$request->admission_no ;
    $qry = "SELECT *  from tc_data where tc_data.admission_no =  $admission_no"  ;
    $resultlist = $this->db->query($qry)->row();
     if(!empty($resultlist)){
       $data['student'] = $resultlist ; 
       $data['Status'] = 1;
       $data['Msg'] = 'Result Found';
     }else{
       $data['student'] = array();
       $data['Msg'] = 'No result Found';
       $data['Status'] = 1;
     }   
  }
  else{
   $data['Msg'] = 'No result Found';
   $data['Status'] = 0;
      }              
     }
     else{
       $data['Msg'] = 'No result Found';
       $data['Status'] = 0;
            }
     echo json_encode($data);   
}

 public function get_tc_data_old(){
   $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data');  
     $admission_no=$request->admission_no ;
    
  if ($postdata != '')
  {  
    $request = json_decode($postdata);
    if(!empty($request))
    { 
     $admission_no=$request->admission_no ;
     $qry = "SELECT * ,first_name as firstname,last_name as lastname, classes.class  from issue_tc_data left join classes on classes.id =  issue_tc_data.last_class where issue_tc_data.admission_no =  $admission_no"  ;
     $resultlist = $this->db->query($qry)->row();
      if(!empty($resultlist)){
        $data['student'] = $resultlist ; 
        $data['Status'] = 1;
        $data['Msg'] = 'Result Found';
      }else{
        $data['student'] = array();
        $data['Msg'] = 'No result Found';
        $data['Status'] = 1;
      }   
   }
   else{
    $data['Msg'] = 'No result Found';
    $data['Status'] = 0;
       }              
      }
      else{
        $data['Msg'] = 'No result Found';
        $data['Status'] = 0;
             }
      echo json_encode($data);   
 }



// send sms for all pending fee
public function sendsms_feeDueAll()
{
  $postdata = file_get_contents("php://input");
  //$postdata =  $this->input->post('data'); 
 
if ($postdata != '')
{  
 $request = json_decode($postdata);
 if(!empty($request))
 {  
 
  foreach ($request->data as $key => $value) {
    foreach ($value as $key => $value1) {
      foreach ($value1 as $key => $value2) {
        $months= implode(',',$value2->month );
        $session = $this->setting_model->getCurrentSession();
      
        $msg_data=array(
          'title'=>'Due Fees',
          'message'=>"Please Pay Your Ward $value2->firstname $value2->lastname fee Dues for the months  $months  of Rupees $value2->totalAmount  .",
          'session_id'=>$session,
          'user_list'=>$value2->mobile,
          'sms_status'=>1,
          'student_session_id'=>$value2->student_session_id
        );
        $this->Messages_model->add($msg_data);
      }
    }
  }   

  $data['Msg'] = 'Message Sent Successfully';
  $data['Status'] = 1;
 }else{
  $data['Msg'] = 'No result Found';
  $data['Status'] = 0;
 }

}else{
  $data['Msg'] = 'No Postdata Found';
  $data['Status'] = 0;
}
echo json_encode($data);   

}




 public function getmonth_array($valu=4)
 {   
    switch ($valu) {
      case ($valu==4):
        return array(4);
        break;

      case ($valu > 4):
      for ($i=$valu; $i >=4 ; $i--) { 
        $num[] = $i ;
              }
        return  $num;
        break;

     case ($valu < 4):
        $num=array(4,5,6,7,8,9,10,11,12);
        for ($i=1; $i <= $valu ; $i++) { 
          array_push($num,$i) ;
                }
          return  $num;
          break;
    default :
        return array(4);
          }
 }

 
  //////////////////////************************/////////////////////////
  //end class




  public function save_Staffattendance()
  {
   $postdata = file_get_contents("php://input");
   if(!empty($postdata))
     {
       $request = json_decode($postdata);
        if(!empty($request))
           { 
         
            $user_type_ary = $request->attendance ;
            $statusss =$request->attendance_status ;

            
            $count = 0;
              foreach ($user_type_ary as $key => $value) {
                              $arr = array(
                                      'staff_id' => $value->staff_id,
                                      'staff_attendance_type_id' => $value->attendance_type,
                                      'remark' => "from Angular app",
                                      'date' => $value->date
                                    );  
                            $todaydate = $value->date; //attendance_status            
                        if($statusss == 1){
                            $insert_id = $this->staffattendancemodel->add($arr);
                            }  else{
                             $this->db->where('id',$value->id);
                             $this->db->where('date',$value->date);
                             $insert_id = $this->db->update('staff_attendance',$arr);
                          }  
                        $qrrry = $this->db->last_query();
                                                   
                              $count++; 

                              //print_r($this->db->last_query());
                            }
//die;
             $data['absent_teacher'] = $this->db->query("SELECT staff.* ,staff.id as id,staff_attendance.id as staff_attendanceid, staff_attendance.* from staff_attendance join staff on staff_attendance.staff_id = staff.id where staff_attendance.staff_attendance_type_id = 3 and staff_attendance.date ='".$todaydate."' AND staff.department BETWEEN 4 AND 7 group by staff.id")->result(); 

          
             
              $data['Msg'] = 'Data Added Successfully';
              $data['Status'] = 1;
              } else{
              $data['Msg'] = 'No Request data Found';
              $data['Status'] = 0;
                }

      }else{
              $data['Msg'] = 'No Postdata Found';
              $data['Status'] = 0;
             }
              echo json_encode($data);             
  }

public function get_proxy_teacher_dummy()
 {
    $data['Msg'] = 'No Postdata Found';
              $data['Status'] = 0; 
              echo json_encode($data);   
 }

//=========================================================================//

//////////////------------------------------------------------------////////

//////////////------------------------------------------------------////////


public function get_proxy_teacher()
 {
    $postdata = file_get_contents("php://input");
   // $postdata ='{"proxydate":"2019-07-10","id":"28","teacher_name":"Mrs. Jaspreet Kaur Rekhi"}';
     if(!empty($postdata))
     {

       $request = json_decode($postdata);
        if(!empty($request))
            {            
               $proxydate = $request->proxydate;
               $teacher_id = $request->id;
               $checkproxy = $this->Proxy_model->check_proxy($teacher_id,$proxydate); 
               if (!empty($checkproxy))
                 {
                     $data['Msg'] = 'Proxy Already Generated';
                     $data['Status'] = 0;
                 }
               else
                 {
                     $resultdata = $this->Proxy_model->get_timetable($teacher_id,$proxydate);   
                      $teacher_group = $this->db->query("SELECT teachers_group from staff where id  = $teacher_id")->row()->teachers_group; 
                       if ($teacher_group == 0 ) {
                              $teacher_group = 3 ;
                            }
                         $engagged_teacher = '0';            
                     foreach ($resultdata as $key => $value)
                     {   //$pyteacher-> ; 


                      $pyteacher =$this->get_proxy($value->class_id,$value->subject_id,$value->period_id,$value->days,$proxydate,$value->section_id,$teacher_id, $teacher_group ,$engagged_teacher); 
                     // print_r($pyteacher); die;
                       if (!empty($pyteacher)) {
                         $engagged_teacher = $engagged_teacher.','.$pyteacher->teacher_id;
                         $value->proxyteacher = $pyteacher ; 
                        }
                                                                  
                     }

               $data['teacher_name'] = $request->teacher_name;
               $data['result'] = $resultdata;
               $data['Msg'] = 'Result Found';
               $data['Status'] = 1;
                 }            
             } 
             else{
              $data['Msg'] = 'No Request data Found';
              $data['Status'] = 0;
                }
          }else{
              $data['Msg'] = 'No Postdata Found';
              $data['Status'] = 0;
            }

        echo json_encode($data);   
 } 





 public function get_proxy($class_id=0, $subject=0, $period=0, $day=0 ,$date=0,$section=0,$teacher_id, $teacher_group,$engagged_teacher)
 {        

      $this->loopCount++ ;
      if ($this->loopCount  >15) {
         $object = new stdClass();
         $object->name = "Library ";
         $object->teacher_id = "0";
         $object->surname = "/ Sports";
         $object->class = $this->Proxy_model->findfield('classes','id',$class_id,'class');
         $object->section =  $this->Proxy_model->findfield('sections','id',$section,'section');
         $object->subject =  $this->Proxy_model->findfield('subjects','id',$subject,'name');
         $object->period =  $this->Proxy_model->findfield('periods','id',$period,'value');
         $object->day =  $this->Proxy_model->findfield('days','id',$day,'value');
         $object->period_id = $period;
         return $object;
     }
      $resarry =  array();
      $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id = 3 and `date`= '".$date."'  ")->result();
            if (!empty($absent_teacher)) {
            foreach ($absent_teacher as $key => $value) {
              $resarry[] = $value->staff_id ;
              }
             }

      $busyteachers_array = array();
      $busyteachers = '0';
      $busyteacherss = $this->db->query("SELECT teacher_id from teacher_timetable where days = $day and period_id =$period and class_id != 0 and section_id != 0 group by teacher_id")->result(); 
            if (!empty($busyteacherss)) {
              foreach ($busyteacherss as $key => $value) {
                                  if ($value->teacher_id) {
                                    $busyteachers_array[] = $value->teacher_id ;
                                  }
                                
                               }
                   $busyteachers = implode(',', $busyteachers_array) ;   

             }else{
              $busyteachers = '0';
             }
  
     //print_r($this->db->last_query()); die;
      $minlecture = 6 ;

      $teachers_grp =$this->checkgroup($teacher_group);  
      for ($i=0; $i < count($teachers_grp) ; $i++) { 
      $teacher_grppp = $teachers_grp[$i] ;
     $freeteachers = $this->db->query("SELECT staff.id , staff.name , staff.surname ,count(teacher_id) as countid from staff join teacher_timetable on staff.id = teacher_timetable.teacher_id where staff.teachers_group =  $teacher_grppp and teacher_timetable.class_id != 0 and teacher_timetable.section_id != 0 and teacher_timetable.subject_id != 0 and days = $day and staff.id not in ( $busyteachers ) and staff.id not in ($engagged_teacher) and staff.id not in (SELECT extrateacher_id from proxyteachers where `date` = '".$date."' )  group by teacher_timetable.teacher_id having count(teacher_id) <= $minlecture order by countid asc limit 1 ")->row();

    if (!empty($freeteachers)) {
        break;
      }
      }
     
 if (!empty($freeteachers) )       
     {   
      if (in_array($freeteachers->id, $resarry)) { 
        $this->get_proxy($class_id, $subject, $period, $day ,$date,$section,$teacher_id,$teacher_grppp,$engagged_teacher);
      
      }else {    
     
         $object = new stdClass();
         $object->name = $freeteachers->name;
         $object->teacher_id = $freeteachers->id;
         $object->surname = $freeteachers->surname ;
         $object->class = $this->Proxy_model->findfield('classes','id',$class_id,'class');
         $object->section =  $this->Proxy_model->findfield('sections','id',$section,'section');
         $object->subject =  $this->Proxy_model->findfield('subjects','id',$subject,'name');
         $object->period =  $this->Proxy_model->findfield('periods','id',$period,'value');
         $object->day =  $this->Proxy_model->findfield('days','id',$day,'value');
         $object->period_id = $period;
         return $object;
        }  
      }
      else{ 
      
        $this->get_proxy($class_id, $subject, $period, $day ,$date,$section,$teacher_id,$teacher_grppp,$engagged_teacher);
        }       
 }


public function checkgroup($grp=0)
 {
   $group = array(1,2,3,4);  
   $min = min($group);
   $max = max($group);
   $newval = $grp ;
        if ($grp > $min) {
          for ($i=1; $i <= $max; $i++) { 
                if ($grp > $min && $newval <= $max) {
                      $pre[] =  $newval ;
                     $newval = $grp+$i ;
                }
             }
             $newpre = array_diff($group,$pre);  
             rsort($newpre);         
             $result = array_merge($pre, $newpre);
           }else{
              $result = $group ;
             }  
   return $result ;
 }


//////---------------------------------------------------------------------------////



public function get_proxy_teacher_old_15_07()
 {
$postdata = file_get_contents("php://input");
     if(!empty($postdata))
     {

       $request = json_decode($postdata);
        if(!empty($request))
            {            
               $proxydate = $request->proxydate;
               $teacher_id = $request->id;
               $checkproxy = $this->Proxy_model->check_proxy($teacher_id,$proxydate); 
                  
             
               if (!empty($checkproxy))
                 {
                     $data['Msg'] = 'Proxy Already Generated';
                     $data['Status'] = 0;
                 }
               else
                 {
                     $resultdata = $this->Proxy_model->get_timetable($teacher_id,$proxydate);   
                     $teacher_group = $this->db->query("SELECT teachers_group from staff where id  = $teacher_id")->row()->teachers_group; 
                       if ($teacher_group == 0 ) {
                              $teacher_group = 3 ;
                            }
                       $engagged_teacher = '0';            
                     foreach ($resultdata as $key => $value)
                     {   //$pyteacher-> ;  
                      $pyteacher =$this->get_proxy($value->class_id,$value->subject_id,$value->period_id,$value->days,$proxydate,$value->section_id,$teacher_id, $teacher_group ,$engagged_teacher); 
                     // print_r($pyteacher); die;
                       if (!empty($pyteacher)) {
                         $engagged_teacher = $engagged_teacher.','.$pyteacher->teacher_id;
                         $value->proxyteacher = $pyteacher ; 
                                    }
                                                                    
                     }

               $data['teacher_name'] = $request->teacher_name;
               $data['result'] = $resultdata;
               $data['Msg'] = 'Result Found';
               $data['Status'] = 1;
                 }            
             } 
             else{
              $data['Msg'] = 'No Request data Found';
              $data['Status'] = 0;
                }
          }else{
              $data['Msg'] = 'No Postdata Found';
              $data['Status'] = 0;
            }

        echo json_encode($data);   
 } 





 public function get_proxy_old_15_07($class_id=0, $subject=0, $period=0, $day=0 ,$date=0,$section=0,$teacher_id, $teacher_group,$engagged_teacher)
 {        
      $resarry =  array(0);
      $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id = 3 and `date`= '".$date."'  ")->result();
            if (!empty($absent_teacher)) {
            foreach ($absent_teacher as $key => $value) {
              $resarry[] = $value->staff_id ;
                        }
             }
      $busyteachers_array = array();
      $busyteacherss = $this->db->query("SELECT teacher_id from teacher_timetable where days = $day and period_id =$period and class_id != 0 and section_id != 0")->result(); 
            if (!empty($busyteacherss)) {
              foreach ($busyteacherss as $key => $value) {
                                  if ($value->teacher_id) {
                                    $busyteachers_array[] = $value->teacher_id ;
                                  }
                                
                               }
                   $busyteachers = implode(',', $busyteachers_array) ;   

             }else{
              $busyteachers = '0';
             }

    $minlecture = 6 ;

      $teachers_grp =$this->checkgroup($teacher_group);  
      for ($i=0; $i < count($teachers_grp) ; $i++) { 
      $teacher_grppp = $teachers_grp[$i] ;
    $freeteachers = $this->db->query("SELECT staff.id , staff.name , staff.surname ,count(teacher_id) as countid from staff join teacher_timetable on staff.id = teacher_timetable.teacher_id where staff.teachers_group =  $teacher_grppp and teacher_timetable.class_id != 0 and teacher_timetable.section_id != 0 and teacher_timetable.subject_id != 0 and days = $day and staff.id not in ( $busyteachers ) and staff.id not in ($engagged_teacher) and staff.id not in (SELECT extrateacher_id from proxyteachers where `date` = '".$date."' )  group by teacher_timetable.teacher_id having count(teacher_id) < $minlecture order by countid asc limit 1 ")->row();  
      if (!empty($freeteachers)) {
        break;
      }
      }

  if (!empty($freeteachers) && (!in_array($freeteachers->id, $resarry)))
     {
         $object = new stdClass();
         $object->name = $freeteachers->name;
         $object->teacher_id = $freeteachers->id;
         $object->surname = $freeteachers->surname ;
         $object->class = $this->Proxy_model->findfield('classes','id',$class_id,'class');
         $object->section =  $this->Proxy_model->findfield('sections','id',$section,'section');
         $object->subject =  $this->Proxy_model->findfield('subjects','id',$subject,'name');
         $object->period =  $this->Proxy_model->findfield('periods','id',$period,'value');
         $object->day =  $this->Proxy_model->findfield('days','id',$day,'value');
         $object->period_id = $period;
         return $object;
      } 
      else{        

           $this->get_proxy($class_id, $subject, $period, $day ,$date,$section,$teacher_id,$teacher_group,$engagged_teacher);
        }
         
                     
     
 }


public function checkgroup_old_15_07($grp=0)
 {
   $group = array(1,2,3,4);  
   $min = min($group);
   $max = max($group);
   $newval = $grp ;
        if ($grp > $min) {
          for ($i=1; $i <= $max; $i++) { 
                if ($grp > $min && $newval <= $max) {
                      $pre[] =  $newval ;
                     $newval = $grp+$i ;
                }
             }
             $newpre = array_diff($group,$pre);  
             rsort($newpre);         
             $result = array_merge($pre, $newpre);
           }else{
              $result = $group ;
             }  
   return $result ;
 }



//////---------------------------------------------------------------------------////
//=========================================================================//


public function save_proxy()
{
    $postdata = file_get_contents("php://input");
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {
              //file_put_contents('pranavSahnii.html', $postdata);
              $arrry = $request->proxydata; 
              //print_r($arrry); die;
              foreach ($arrry as $key => $val) {

              $ary = array('days'=> ($val->day)?$val->day:0,'periods'=> $val->period,'classes'=> $val->class,'sections'=> $val->section,'subjects'=> ($val->subject)?$val->subject:0,'extrateacher'=> $val->extrateacher,'on_behalf'=> $val->on_behalf,'date'=> $val->date,'extrateacher_id'=> $val->extrateacher_id,'period_id'=> $val->period_id,'teacher_id'=> $val->teacher_id) ;
          
                  $checkdata = $this->db->get_where('proxyteachers',$ary)->row();
                  if (!empty($checkdata)) {
                       $this->db->where('on_behalf',$val->on_behalf);
                       $this->db->where('date', $val->date);
                       $this->db->where('periods',$val->period);
                       $this->db->where('classes', $val->class);
                       $this->db->where('sections',$val->section);
                       $this->db->where('extrateacher', $val->extrateacher);
                       $this->db->where('extrateacher_id', $val->extrateacher_id);
                       $this->db->where('period_id', $val->period_id);
                       $this->db->update('proxyteachers',$ary);
                   }else{ 
                      $this->db->insert('proxyteachers',$ary);
                   }
              }  
               $data['Msg'] = "Data Inserted Successfully";
               $data['status'] = 1;
            }
            else{
               $data['Msg'] = "Request data no found";
               $data['status'] = 0;
             }
     } 
  else{
       $data['Msg'] = "Post data not found";
       $data['status'] =  0;
       }
  echo json_encode($data);
}



  public function ipcheck()
  {
    $postdata = file_get_contents("php://input");
     //$postdata ='{"ip":"192.168.2.142"}' ;
   if(!empty($postdata))
      {
        $request = json_decode($postdata);
         if(!empty($request))
            {
            $ip = $request->ip ; 
            // 
              $ip_avail =  $this->db->query("SELECT ip from ipaddress WHERE FIND_IN_SET('".$ip."', ip) >0")->row()->ip;
             //print_r($this->db->last_query());
              if (!empty($ip_avail)) {
                $data['status'] = 1; 
              }else
                $data['status'] = $ip_avail; 
            }
          else
          {
              $data['status'] = 0; 
          }
      }
        else
          {
              $data['status'] = 0; 
          }

          echo json_encode($data);
  }


public function getProxybyDate()
{
    $postdata = file_get_contents("php://input");
   // $postdata = '{"date":"2019-06-28"}';
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {
              $date = $request->date ;
              $data['result'] = $this->db->get_where('proxyteachers',array('date'=> $date ))->result();
               $data['Msg'] = "Data  found";
               $data['status'] = 1;
            }             
        else{
               $data['Msg'] = "Request data no found";
               $data['status'] = 0;
            }
          } 
      else{
           $data['Msg'] = "Post data not found";
           $data['status'] =  0;
           }
     echo json_encode($data);
}


  public function SaveAnswersheet_code()
  {
    $postdata = file_get_contents("php://input");
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {
               $datas['barcode'] = $request->barcode_no ; 
               $datas['scholar_no'] = $request->scholar_no ; 
               $datas['session_id'] = $request->session_id ; 
               $datass['barcode'] = $request->barcode_no ; 
               $scholar_n=  $datas['scholar_no'] ;
               $barcode=  $datas['barcode'] ;
              $already = $this->db->query("SELECT id from student_answersheet where barcode = $barcode")->row()->id;  
             if($already > 0 ){
                   $data['Msg'] = "Barcode already exist";
                   $data['status'] = 0;
             }else{
             $res = $this->db->query("SELECT id from student_answersheet where scholar_no = $scholar_n ")->row()->id;
               if ($res > 0) {
                  $this->db->where('scholar_no',$datas['scholar_no']);
                  $this->db->update('student_answersheet',$datass);
                   $data['Msg'] = "Data updates Successfully";
               }else{
                 $this->db->insert('student_answersheet',$datas);
                  $data['Msg'] = "Data Inserted Successfully";
                  }              
              
                  $data['status'] = 1;
                 }
             }
            else{
               $data['Msg'] = "Request data no found";
               $data['status'] = 0;
             }
         } 
      else{
           $data['Msg'] = "Post data not found";
           $data['status'] =  0;
           }
  echo json_encode($data);
  }

 public function sendmsg_proxy()
 {
   $postdata = file_get_contents("php://input");
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {   
            $proxydata = $request->proxydata;
            $session_id = $request->session_id;
            foreach ($proxydata as $key => $value) {  
                   $day = $value->day ;
                   $date = $value->date ;
                   $period = $value->period ;
                   $class = $value->class ;
                   $section = $value->section ;
                   $subject = $value->subject ;
                   $extrateacher = $value->extrateacher ;
                   $extrateacher_id = $value->extrateacher_id ;
                  $number = $this->db->query("SELECT contact_no from staff where id =  $extrateacher_id")->row()->contact_no;
                   $msg_data=array(
                  'title'=>'Substitution Management',
                  'message'=>"Dear $extrateacher you have been substituted in $class $section for period $period of $subject on $day $date .",
                  'session_id'=>$session_id,
                  'user_list'=> $number ,
                  'sms_status'=>0,
                  'student_session_id'=>0
                  );
                $this->Messages_model->add($msg_data);
              $data['qry'][] = $this->db->last_query();
              }
               $data['Msg'] = "Message sent Successfully";
               $data['status'] =  1;
            }
             else{
           $data['Msg'] = "Request data not found";
           $data['status'] =  0;
           }
  
      } 
         else{
           $data['Msg'] = "Post data not found";
           $data['status'] =  0;
           }
  echo json_encode($data);     
 }

 public function student_details_by_classsection()
  {
     $postdata = file_get_contents("php://input");
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {
               $class = $request->class_id ; 
               $section = $request->section_id ; 
               $session = $request->session_id ; 
               $details = $this->student_model->searchByClassSection_api($class, $section, $session);
               $data['data'] = $details ;
               $data['Msg'] = "Data Found";
               $data['status'] = 1;
             
            }
            else{
               $data['Msg'] = "Request data no found";
               $data['status'] = 0;
             }
         } 
      else{
           $data['Msg'] = "Post data not found";
           $data['status'] =  0;
           }
  echo json_encode($data);
  }


public function Paid_fee_details()
  { 
    $postdata = file_get_contents("php://input");
   //$postdata = '{"date":"2019-06-29","session_id":"14"}';
    if ($postdata != '')
    {     
      $request = json_decode($postdata);
       if (!empty($request)) {
      $session_id = $request->session_id ;
      $date = $request->date ;
      $qry  = "SELECT dl_date as dlDate from datestatus where 1 order by id desc limit 1";
      $DlDate = $this->db->query($qry)->row()->dlDate ;
      $todate  = date('Y-m-d');
        if($date >= $DlDate && $todate == $date) {
                $feeList = $this->studentfeemaster_model->getFeeBetweenDateapi($date, $date,$session_id);  
                if (!empty($feeList)) {
                   $outputarr['Status'] = 1; 
                $outputarr['Msg'] = "Data Found";       
                $outputarr['Result'] = $feeList; 
                } else
            { 
               $outputarr['Msg'] = 'Today Paid Fee data not Found' ; 
               $outputarr['Status'] = 0 ;  
            }  
            }
          else
            { 
               $outputarr['Msg'] = ' Daily Report downloaded....No record found to be Reverted ' ; 
               $outputarr['Status'] = 0 ;  
            }  
       }  
    else { 
         $outputarr['Status'] = 0; 
         $outputarr['Msg'] = "Post Data not found ";
         }   
    }else{
         $outputarr['Status'] = 0; 
         $outputarr['Msg'] = "Request Data not found ";
    }  
  echo json_encode($outputarr);
  }



 public function birthdaywishes()
 {
  //echo "<pre>";
  // $date1 =  date('m-d');
   $date2 =  date('d-m');
   $date3 =  date('d/m');
  // $date4 =  date('m/d');
  //$query = "SELECT * FROM demo WHERE MONTH(STR_TO_DATE(dob, '%d-%m-%Y')) = MONTH(NOW()) and DAY(STR_TO_DATE(dob, '%d-%m-%Y'))= DAY(NOW())";
   //$studentlist = $this->db->query("SELECT firstname , lastname, mobileno, dob from students where admission_no > 0 and mobileno !='' and  (students.dob like '%".$date1."%' or students.dob Like '%".$date2."%' or students.dob Like '%".$date3."%' or students.dob like '%".$date4."%') ")->result();
   $studentlist = $this->db->query("SELECT firstname , lastname, mobileno, dob from students where admission_no > 0 and mobileno !='' and  MONTH(STR_TO_DATE(dob, '%d-%m-%Y')) = MONTH(NOW()) and DAY(STR_TO_DATE(dob, '%d-%m-%Y'))= DAY(NOW()) ) ")->result();

   $stafflist = $this->db->query("SELECT name, surname, contact_no, dob from staff where contact_no !='' and  MONTH(STR_TO_DATE(dob, '%d-%m-%Y')) = MONTH(NOW()) and DAY(STR_TO_DATE(dob, '%d-%m-%Y'))= DAY(NOW()) ) ")->result();
  if (!empty($studentlist)) {   
  foreach ($studentlist as $key => $value) {
        $msg = "Dear $value->firstname  $value->lastname, Have a wonderful birthday. We wish your every day to be filled with lots of love, laughter, happiness and the warmth of sunshine.
          From, Central Academy Family";
            
                 $msg_data1=array(
                    'title'=>'Birthday Wishes',
                    'message'=> $msg,                   
                    'user_list'=> $value->mobileno,
                    'student_session_id'=>0
                  );
                //  $this->Messages_model->add($msg_data1);
           }

         }
if (!empty($stafflist)) {   
  foreach ($stafflist as $key => $value) {
        $msg = "Dear $value->name  $value->surname, Have a wonderful birthday. We wish your every day to be filled with lots of love, laughter, happiness and the warmth of sunshine.
          From, Central Academy Family";
            
                 $msg_data1=array(
                    'title'=>'Birthday Wishes',
                    'message'=> $msg,                   
                    'user_list'=> $value->contact_no,
                    'student_session_id'=>0
                  );
                  //$this->Messages_model->add($msg_data1);
              }

         }
   }
  //////////////end class
 
}

   ?>

