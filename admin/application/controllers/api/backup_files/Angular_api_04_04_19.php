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
        $this->load->model('Messages_model');
        $this->load->model('Studentfeemaster_model');
        $this->load->library('Enc_lib'); 
        $this->load->library('form_validation'); 
    }


    public function staff_login() {

     $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data');
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
      //  $postdata =  $this->input->post('data');
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


public function delete_gp()
{
       $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data');       

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
      //$postdata =  $this->input->post('data'); 
      // $postdata  = 'as';
 if ($postdata != ''){     
    $request = json_decode($postdata);
    $text = $request->text ;
    $session_id = $request->session_id ;
    //$session_id = 13 ;

  //  $text = 'test';
    $result = $this->Api_model->get_search_result($text,$session_id);
     if (!empty($result))
         {
           $outputarr['Result'] = $result ;
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



public function fee_details()
{ 
$postdata = file_get_contents("php://input");
// $postdata =  $this->input->post('data');    
 //  $postdata= '{"id": "2185", "session_id": "14"}';
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
       $date1 = date('d/m/Y') ;
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
              'amount_detail' => $json_array
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
//$postdata =  $this->input->post('data');   
//     $postdata = '{
//    "date_from": "2019/02/04",
//    "date_to": "2019/02/04",
//    "session_id":"13"
//  }';  

   //$postdata= '{"date_from": "2019/04/03", "date_to": "2019/04/03", "session_id": "13"}';
 if ($postdata != '' ){   
   
    $request1= json_decode($postdata);
  
  
    if(!empty($request1)){

       
              // 16-01-2018   date("d-m-Y", strtotime($originalDate));
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
    $feeList = $this->studentfeemaster_model->getFeeBetweenDateapi($date_from, $date_to,$session_id); 	
    $feeList_revert=$this->studentfeemaster_model->getrevertFeeBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to)));
   // echo $this->db->last_query(); die;
	$feeList_expenses =$this->studentfeemaster_model->getexpenseBetweenDate(date("Y-m-d",strtotime($request1->date_from)), date("Y-m-d",strtotime($request1->date_to))); 
    if (!empty($feeList)) {
        $outputarr['feesdeatils'] = $feeList ; 
		$outputarr['feesdeatils_revert'] = $feeList_revert ; 
		$outputarr['feesdeatils_expenses'] = $feeList_expenses ; 
        $outputarr['print'] = $print ;  
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Fees Details for a particular  date  ";
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
     //  $postdata = '{"defaulter" : "false","mobileno":"321","month_name" : "June,July", "session_id"    :    "14",   "student_session_id"   : "2256"}';      
   if ($postdata != ''){     
      $request = json_decode($postdata);
      $id =  $request->student_id ; 
      $session_id =  $request->session_id ; 
      $mobnumber =  $request->mobileno ; 
      $student_session_id =  $request->student_session_id ; 
      $month_name= $request->month_name; 
      if($student_session_id){
      $msg_data=array(
        'title'=>'Due Fees',
        'message'=>" Dear Parent, your ward’s school fee has been received for the month $month_name. Thanks for the payment. School Management.",
        'session_id'=>$session_id,
        'user_list'=>$mobnumber,
        'student_session_id'=>$student_session_id
    );
    $this->Messages_model->add($msg_data);
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
            $data['next_date'] =  date('d/m/Y',strtotime($request->date. ' + 1 days'));
          if ($data['status'] == 'true')
             {
                $data['status'] = 1 ;
                 $this->db->insert('datestatus',$data);
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
             $session_id= $request->session_id;
  
             $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id, $session_id);

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
        $session_id=$request->session_id ; 
        $section='';        
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
           $examList = $this->examschedule_model->getExamByClassandSection($class, $section, $session_id);
           if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
              $sort_result_perexam=array();
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student_id;
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student_id, $session_id);
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
                 //print_r($sort_result_perexam);
                 //die;
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
$session = $this->setting_model->getCurrentSession();
//  $class=36; 
//  $section=17;
$class=$request->class_id ; 
$section=$request->section_id ;

$examList = $this->examschedule_model->getExamByClassandSection($class, $section);

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
   
 
  


//print_r($highmark); 
//die;
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
            // $total += $first_arr1['full_marks'] ;
            // $obtain += $first_arr1['get_marks'] ;
        }
        $exam_arr[$first_arr['exam_name']] = $sub;
      
    }
  // echo "<pre>";
  // print_r($new_array); die;
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

public function All_MarksheetlistByClass_oldddddd()
{
   //$postdata = file_get_contents("php://input");
  //$postdata= $this->input->post('data');
  $postdata='{"a":"a"}';
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
    $session = $this->setting_model->getCurrentSession();
        $class=36; 
        $section=17;
   // $class=$request->class_id ; 
   // $section=$request->section_id ;

    $examList = $this->examschedule_model->getExamByClassandSection($class, $section);

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
          $studetails['gender'] = $stuval['gender'];
          $studetails['height'] = $stuval['height'];
          $studetails['weight'] = $stuval['weight'];
          $studetails['total_days'] = $stuval['total_days'];
          $studetails['present_days'] = $stuval['present_days'];

          $exam_subjects = $this->examschedule_model->getresultByStudentandExam_pdf($exam_id, $stuval['id']);
       // echo $this->db->last_query(); 
          $exam_subjects_1[] =  $exam_subjects ;

          
         /*print_r($exam_subjects);
         die;
         */ if (!empty($exam_subjects)) {                 
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
            $array['note'] = $ex_value['examnote'];
            $array['exam_result'] = $sort_result_perexam_val;
            $new_array[] = $array;
            $marks[]=$sort_result_perexam_val;

          }

        }
//      echo "<pre>";
//      print_r($exam_array);
      
$finalmax=[];
$finalobt= [];
 
if ($stuval['class'] == 'IX') {

//echo "<pre>";

 foreach ($marks[0] as $key => $value1) 
 {
 // print_r($value1);
  $preodic1getmarks[] = $value1['get_marks'] ;
  $preodic1maxmarks[] = $value1['full_marks'] ;
  $total1 +=  $value1['get_marks'] ; 
 }

 foreach ($marks[1] as $key => $value2) 
 {
     $preodic2getmarks[] = $value2['get_marks'] ;
     $preodic2maxmarks[] = $value2['full_marks'] ;
     $total2 +=  $value2['get_marks'] ; 
 }

 foreach ($marks[2] as $key => $value3)
  {
      $preodic3getmarks[] = $value3['get_marks'] ;
      $preodic3maxmarks[] = $value3['full_marks'] ;
      $total3 +=  $value3['get_marks'] ; 
   }
   foreach ($marks[3] as $key => $value4)
  {
      $preodic4getmarks[] = $value4['get_marks'] ;
      $preodic4maxmarks[] = $value4['full_marks'] ;
      $total4 +=  $value4['get_marks'] ; 
   } 

$marksa = max($total3,$total2,$total1);
if ($marksa == $total1) {
 $finalmax11 = $preodic1maxmarks;
 $finalobt11 =$preodic1getmarks ;
}
if ($marksa == $total2) {
  $finalmax11 = $preodic2maxmarks;
 $finalobt11 =$preodic2getmarks ;
}
if ($marksa == $total3) {
$finalmax11 = $preodic3maxmarks;
 $finalobt11 =$preodic3getmarks ;
}


        $finalmax = array_map(function () {
  return array_sum(func_get_args());
}, $finalmax11, $preodic4maxmarks);
$finalobt = array_map(function () {
  return array_sum(func_get_args());
}, $finalobt11, $preodic4getmarks);
  }else{


      for ($k=0; $k < count($marks); $k++) { 
        $max=array();
        $obt=array();
           foreach ($marks[$k] as $key => $value) {
               $max[]=$value['full_marks'];
               $obt[]=$value['get_marks'];

           }
          // $finalmax[]= ;
           //print_r($finalmax);
           $finalmax = array_map(function () {
  return array_sum(func_get_args());
}, $finalmax, $max);
$finalobt = array_map(function () {
  return array_sum(func_get_args());
}, $finalobt, $obt);
      }
  }    
      //
//html design start
$grading = $this->student_model->get_grade_student($studetails['scholar_no']);  
$data = array(
'studetails'=> $studetails,
'new_array'=> $new_array,
'sessionname'=> $studetails['session'],
'grading'=> $grading,

 );
//  echo "<pre>";
//  print_r($data);
//  echo "</pre>";

 
  
  
  foreach ($new_array as $first_arr) {
    
//     echo "<pre>";
//  print_r($first_arr);
//  echo "</pre>"; 
       
  
  foreach ($first_arr['exam_result'] as $exam_arr) {
     
     echo "<pre>";
  print_r($exam_arr);
  echo "</pre>"; 
  }
  }
 die;
// echo $html=$this->load->view('marksheet/marksheet_11', $data, true);
// die;
 
        //load the view and saved it into $html variable
if($stuval['class'] == 'IX'){
    $html=$this->load->view('marksheet/marksheet_ix', $data, true);   
 } else if($stuval['class'] == 'XI'){
    $html=$this->load->view('marksheet/marksheet_xi', $data, true);  
 } elseif ($stuval['class'] == 'VI' || $stuval['class'] == 'VII'|| $stuval['class'] == 'VIII') {
   $html=$this->load->view('marksheet/marksheet_vii', $data, true);  
 } else{
   $html=$this->load->view('marksheet/marksheet_v', $data, true);  
 }
// echo $html = $this->load->view('marksheet',true);

//ends heere

$code=$studetails['scholar_no'];
$m_pdf = new mPDF();
$m_pdf->SetDisplayMode('real');
//q $m_pdf->shrink_tables_to_fit = 1;
$m_pdf->WriteHTML('<style>@page {
        margin-top: 250px;
        line-height:50px;
        }</style>' .$html);
$m_pdf->Output(FCPATH . "uploads/marksheetPdf/$code.pdf", 'F');

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

public function studentadmission()
{
 $postdata = file_get_contents("php://input");
// $postdata =  $this->input->post('data');    
/* $postdata = '{
"admission_number": "098765",
"first_name": "ateek",
"last_name": "khan",
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
"mother_phone": 43434,
"father_name": "gfdgdfgd",
"father_phone": 43434,
"guardian_relation": "Mother",
"guardian_phone": 43434,
"guardian_name": "dsdfldf",
"session_id": "13"
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
} else{
$insert_id = $this->student_model->add($data);

/* $outputarry['query']  =$this->db->last_query(); 
echo json_encode($outputarry); die; */
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
/* $outputarry['query']  =$this->db->last_query(); 
echo json_encode($outputarry); die; */
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
        // $time = strtotime("date(Y).4.10");
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
/* student fees add*/  

if ($is_bpl) {
                          
        $outputarry['msg']  = 'BPL Student Added Successfully No Fees Charges Apply';              
        $outputarry['Status']  = 1; 
    } 
else {
    $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($class_id,$session_id) ;
    /// print_r($feesession_group_id);
    //echo $this->db->last_query();


    $feesession_group_ida= $feesession_group_id->id;

    //$qrie= " SELECT * from fee_groups_feetype where fee_session_group_id = $feesession_group_ida order by feetype_id asc limit 1";
    $qrie= "SELECT * from fee_groups_feetype where fee_session_group_id = $feesession_group_ida and fee_bydefault = 1 ";
    $fees_result1 = $this->db->query($qrie)->result();
//   print_r($fees_result1);
      $fee_receipt_no=$this->Api_model->feeereceipt_no();
    foreach ($fees_result1 as $key => $fees_result) {      
  
    $fees_result->fee_session_group_id ; 
    $data_fee['fee_session_group_id'] =  $fees_result->fee_session_group_id;
    $data_fee['student_session_id'] =  $student_session_id ;
    $master_id = $this->Studentfeemaster_model->add($data_fee);

    //add fee start

      $json_array = array(
              'amount' => $fees_result->amount_II,
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
}
  
    

      }// else end for admission no exist      
                            
             }  // if end for request data
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
          // $time = strtotime("date(Y).4.10");
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
    //$postdata =  $this->input->post('data');    
   if ($postdata != '')
      {  
       $request = json_decode($postdata);
       if(!empty($request))
        {      
        $invoice_id = $request->main_invoice ;
        $sub_invoice = $request->sub_invoice ;
        if (!empty($invoice_id)) {

            $this->studentfee_model->remove($invoice_id, $sub_invoice);
          }
         $array['msg'] = 'Fee Revert Successfully' ; 
         $array['Status'] = 1 ;   
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



public function PromoteNow_old()
{
 $postdata = file_get_contents("php://input");
 // $postdata =  $this->input->post('data');    
 if ($postdata != '')
    {  
     $request = json_decode($postdata);
       if(!empty($request))
       {          
            $student_list =  $request->student_list; 


            foreach ($student_list as $key => $value) {
                $student_id = $value->id;
                $result = $value->result;
                $session_status = $value->Session_status;
                if ($result == "pass" && $session_status == "countinue") {
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
                 elseif ($result == "fail" && $session_status == "countinue") {
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
            }
            $data['msg'] = 'Student Promoted Successfully' ; 
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

public function PromoteNow_ateek_sir()
{
 $postdata = file_get_contents("php://input");
 // $postdata =  $this->input->post('data');    
 if ($postdata != '')
    {  
     $request = json_decode($postdata);
       if(!empty($request))
       {          
            //$student_list =  $request->student_list; 


            
                $student_id = $request->id;
                $result = $request->result;
                $session_status = $request->Session_status;
                if ($result == "pass" && $session_status == "countinue") {
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
                 elseif ($result == "fail" && $session_status == "countinue") {
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
 /* Session_status:"Continue"
class_id:"28"
class_promote_id:"28"
father_name:"YOGESH PANDEY"
id:"43"
mobileno:"9424626105"
mother_name:undefined
result:"Pass"
section_id:"17"
section_promote_id:"17"
session_id:"14" */
     $request = json_decode($postdata);
           if(!empty($request))
       {     
           $student_id = $request->id;   
           $student = $this->student_model->get($student_id);  
           $student_session_id = $student['student_session_id'];
           $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
           $count = 0  ;
           $checkDueFee = 0 ;
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
                               $promoted_session =$request->session_id;
                               $class_post = $request->class_id;
                           
                              $section_post = $request->section_id;
                              $data_new = array(
                                  'student_id' => $student_id,
                                  'class_id' => $promoted_class,
                                  'section_id' => $promoted_section,
                                  'session_id' => $promoted_session,
                                  'transport_fees' => 0,
                                  'promoted' => 1,
                                  'fees_discount' => 0
                              );
                              $student_session_id_new=$this->student_model->add_student_session($data_new);

                          }
                           elseif ($result == "Fail" && $session_status == "Continue") {
                              $promoted_session = $request->session_id;
                              $class_post = $request->class_id;
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

                                /* student fees add*/
                                 
                                $feesession_group_id =$this->Api_model->get_fee_session_groups_idByclass_id($class_post,$promoted_session) ;
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
                                             $data['msg']  = 'Student Added successfully And Fee Paid For First month'; 
                                             $data['Status'] = 1;                                   
                                             $data['receipt_no'] = $fee_receipt_no;                                   
                              }
                              else{
                                $data['msg']  = 'Fees Not Assigned'; 
                                $data['Status'] = 0;   
                             }

                               
            } //mainif
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

//end class
}

?>

