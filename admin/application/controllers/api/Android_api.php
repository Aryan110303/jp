<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//defined('BASEPATH') OR exit('No direct script access allowed');

class Android_api extends CI_Controller {

    public function __construct() {

        parent::__construct(); 
        $this->load->model('Api_model');
        $this->load->library('Enc_lib'); 
        $this->load->library('Customlib'); 
        $this->load->library('form_validation'); 
    }


    public function staff_login() {
      $postdata =  $this->input->post('data');
     if ($postdata != '')
     {  
            $request = json_decode($postdata);
            $data = array(
            'moblie' => $request->phone,
            'password' => $request->pass,
            'roles' => $request->role
              );
          $row = $this->Api_model->app_login($data);
          if ($row != false) {
              $outputarr['Status'] = true; 
              $outputarr['Msg'] = "login Successfully";
              $outputarr['Result'] = $row ;
          } else {
              $outputarr['Status'] = false; 
              $outputarr['Msg'] = "Invalid User";
          }
    }
    else{
       $outputarr['Status'] = false; 
       $outputarr['Msg'] = "Invalid User";
  }

   echo json_encode($outputarr);


}

public function class_section_list()
{
  
  $postdata =  $this->input->post('data'); 
   
  if ($postdata != '') {   

    $request = json_decode($postdata);
    $staff = $request->id ;
  }      

$result = $this->Api_model->get_class_section_list($staff);
if (!empty($result)) {
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Success";
    $outputarr['Result'] = $result ;
} else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
}  
echo json_encode($outputarr);


}



public function stuattendence(){
    
    $postdata =  $this->input->post('data'); 
   
  if ($postdata != '') {   

    $request = json_decode($postdata);
    $class = $request->class ;
    $section = $request->section ;
    $date = $request->date ;
   
  } 

  
  $result = $this->Api_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', strtotime($date)));
  //echo $this->db->last_query();
if (!empty($result)) {
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Success";
    $outputarr['Result'] = $result ;
} else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
}  
echo json_encode($outputarr);  
}

public function stuattendence_save()
{   
  $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
  //  $session_ary = $request->student_session ;
  foreach ($request as  $value) {
if ( $value->attendence_id !=0) {

            $arr = array(
                'id' => $value->attendence_id,
                'student_session_id' => $value->student_session_id,
                'attendence_type_id' => $value->attendence_type_id,
                'remark' =>'' ,
                'date' => date('Y-m-d', strtotime($value->date))
            );
          }
            else{
                $arr = array(                
                'student_session_id' => $value->student_session_id,
                'attendence_type_id' => $value->attendence_type_id,
                'remark' =>'' ,
                'date' => date('Y-m-d', strtotime($value->date))
            );
           
            }
           
            $insert_id = $this->stuattendence_model->add($arr);
             
    } 

     $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Attendance Saved Successfully";  
  
  }      

//$result = $this->Api_model->get_class_section_list($staff);
 else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
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
      $data = $this->Api_model->get_all_student();
          
       if (!empty($data)) {
           $outputarr['Status'] = 1; 
           $outputarr['Msg'] = " Student data Available";
                      $outputarr['Result'] = $data ;
       } else {

           $outputarr['Status'] = 0; 
           $outputarr['Msg'] = "No Student Available";
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
  //  $text = 'test';
    $result = $this->Api_model->get_search_result($text);
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


// public function fee_details()
// {            $postdata = file_get_contents("php://input");
//  //$postdata =  $this->input->post('data');    
//  if ($postdata != ''){     
//     $request = json_decode($postdata);
//     $id = $request->id ;
//        $student = $this->Student_model->get($id);        
//        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
//        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
//        $fee['student'] = $student;
//        $fee['student_discount_fee'] = $student_discount_fee;
//        $fee['student_due_fee'] = $student_due_fee;

//      if (!empty($fee))
//          {
//            $outputarr['Result'] = $fee ;
//            $outputarr['Status'] = 1; 
//            $outputarr['Msg'] = "Result Found";
//           }
//   else { 
//        $outputarr['Status'] = 0; 
//        $outputarr['Msg'] = "Failed To Search 1";
//         }     
//      }  
//      else { 
//        $outputarr['Status'] = 0; 
//        $outputarr['Msg'] = "Failed To Search 2";
//       }     
//    echo json_encode($outputarr);
// }



// public function add_fee()
// {    
//       $postdata = file_get_contents("php://input");
//       // $postdata =  $this->input->post('data');    
//  if ($postdata != ''){     
//     $request = json_decode($postdata);
//     if(!empty($request)){
    
//     foreach($request as $val){
//          if (isset($val->student_fees_discount_id)) {
//             $student_fees_discount_id = $val->student_fees_discount_id;
//            }else{
//              $student_fees_discount_id = 0 ;
//            }

//             $collected_by = " Collected By:Accountant ";// . $this->customlib->getAdminSessionUserName();
//           $student_fees_discount_id =$val->student_fees_discount_id;
//           $json_array = array(
//               'amount' => $val->amount,
//               'date' => date('Y-m-d', $this->customlib->datetostrtotime($val->date)),
//               'amount_discount' => $val->amount_discount,
//               'amount_fine' => $val->amount_fine,
//               'description' => $val->description . $collected_by,
//               'payment_mode' => $val->payment_mode
//           );
//           $data = array(
//               'student_fees_master_id' => $val->student_fees_master_id,
//               'fee_groups_feetype_id' => $val->fee_groups_feetype_id,
//               'amount_detail' => $json_array
//           );
//                  $inserted_id = $this->Api_model->fee_deposit($data, $student_fees_discount_id);
//                 $outputarr['Result'][] = $inserted_id ; 
//                  $outputarr['Status'] = 1; 
                 
//                 $outputarr['Msg'] = "Fess Deposit";
    
//     }
    
//     }
//     else { 
//        $outputarr['Status'] = 0; 
//        $outputarr['Msg'] = "Failed";
//       } 
         
//      }  
//      else {  
//        $outputarr['Status'] = 0; 
//        $outputarr['Msg'] = "Failed";
//       }     
//    echo json_encode($outputarr);
// }




public function fee_details()
{ 
$postdata = file_get_contents("php://input");
// $postdata =  $this->input->post('data');    
 if ($postdata != ''){     
    $request = json_decode($postdata);
       $id = $request->id ;
       $student = $this->Student_model->get($id);        
       $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
       $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);

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
       $main_arr=array();
       // print_r($student_due_fee) ;die;
       /* $jfile= json_decode($fee['student_due_fee']['fees']['amount_details']);*/  
        //$obj = new stdClass ;         
           foreach ($student_due_fee as $key => $fee)
            {          
                                         //$obj1 = new stdClass ; 
                                  // $amount_detail1  ='';
                                  $obj1  = array() ;
                                foreach ($fee->fees as $fee_key => $fee_value) 
                                        {    
                                                                         
                                           if (!empty($fee_value->amount_detail)) {
                                                 $amount_detail = json_decode(($fee_value->amount_detail)); 
                                                //print_r($amount_detail);
                                                foreach ($amount_detail as $key => $value) {
                                                  $fee_value->amount_detail=$value;
                                                }
                                                //$fee_value->amount_detail
                                              //$fee['student_due_fee']->fees->amount_detail =  $fee_deposits ;
                                            }

                                            $obj1[$fee_key] = $fee_value ; 
                                           $amount_detail1=$obj1;
                                          
                                        }
                                            
                                          // $amount_detail1=$obj1;
                                          //main foreach
                                          $fee->fees = $amount_detail1 ;
                                          //print_r($fee) ; 
                                         $main_arr[]=$fee;                                       
                                         $fee=array();                                       
                   }  //die;
                   
                                        //print_r($main_arr) ; die;
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
 if ($postdata != '' ){     
    $request = json_decode($postdata);
    if(!empty($request)){
        //16-01-2018   date("d-m-Y", strtotime($originalDate));
    $date_from = date("d-m-Y",strtotime($request->date_from)) ;     
    $date_to = date("d-m-Y",strtotime($request->date_to)) ;    
    $date_f = date('Y-m-d',strtotime($date_from));
    $qry =  "select status from datestatus where dl_date ='".$date_f."' " ; 
    $print = $this->db->query($qry)->row();
    if ($print != '') {
      
    if ($print->status == 1 ) {
        $print = 'false' ;
        }else
          { 
            $print = 'true' ; 
        }
    }  else  $print = 'true' ; 

    $feeList = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to);
    if ($feeList) {
        $outputarr['feesdeatils'] = $feeList ; 
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
       $outputarr['Msg'] = "Failed";
      }   
   }
     else {  
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed";
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
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feesessiongroup = $this->feesessiongroup_model->getFeesByGroup(); 
        $data['feesessiongrouplist'] = $feesessiongroup;
        $data['Status'] = 1 ;

        echo json_encode($data);
  }


 public function duefeelist()
 {    
       $postdata = file_get_contents("php://input");
     // $postdata =  $this->input->post('data'); 
        
 if ($postdata != ''){     
    $request = json_decode($postdata);
 
            $feegroup_id =$request->feegroup_id ;
            $feegroup = explode("-", $feegroup_id);
            $feegroup_id = $feegroup[0];
            $fee_groups_feetype_id = $feegroup[1];
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


    public function sendsms(Type $var = null)
    {
        $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data');   
      
         
        
   if ($postdata != ''){     
       $request = json_decode($postdata);
      $id =  $request->student_id ; 
      if($id){
      $phone = $this->db->select('guardian_phone')->where('id',$id)->get('students')->row()->guardian_phone;  
      $msg = 'hello pranavv';
      $url = "http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username=casjbp&password=password&sender=CASJBP&to=$phone&message=$msg&reqid=1&format={json|text}&route_id=113";
      $ch=curl_init();
      //curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $output=curl_exec($ch);
      curl_close($ch);
      //echo $output;
      //return $output;    
       
       $data['message'] = 'sms sent successfully' ; 
       $data['Status'] = 1 ; 

      
    }else{
        $data['Status'] = 0; 
        $data['message'] = 'number not found' ; 
   
    }
    
        }else{
            $data['Status'] = 0; 
            $data['message'] = 'sms send failed' ; 
       
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
            $data['next_date'] = date('Y-m-d', strtotime($request->date. ' + 1 days'));
          if ($data['status'] == 'true')
             {
                $data['status'] = 1 ;
                 $this->db->insert('datestatus',$data);
             $result['msg']= 'today date report Downloaded';
             $result['Status']=1;
              }
            else
            {
             $result['msg']= 'Multi-times Downloaded';
             $result['Status']=1;
            }           
          }
          else{
             $result['msg']= 'Status not yet received';
             $result['Status']=0;          
          }
          echo json_encode($result);
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
          $section='';
          $resultlist = $this->student_model->searchByClassSection($class, $section);
          $data['studentlist'] =  $resultlist ; 
         $data['Status'] = 1;
         }else{
           $data['Msg'] = 'No result Found';
           $data['Status'] = 0;
         }

         echo json_encode($data);        
     }
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
              $session = $this->setting_model->getCurrentSession();
              $class=$request->class_id ; 
              $section=$request->section_id ;
              $student_id=$request->student_id ;
          $examList = $this->examschedule_model->getExamByClassandSection($class, $section);
           if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student_id;
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student_id);
                foreach ($exam_subjects as $key => $value) {
                    $exam_array = array();
                    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                    $exam_array['exam_id'] = $value['exam_id'];
                    $exam_array['full_marks'] = $value['full_marks'];
                    $exam_array['passing_marks'] = $value['passing_marks'];
                    $exam_array['exam_name'] = $value['name'];
                    $exam_array['exam_type'] = $value['type'];
                    $exam_array['attendence'] = $value['attendence'];
                    $exam_array['get_marks'] = $value['get_marks'];
                    $x[] = $exam_array;
                }
                $array['exam_name'] = $ex_value['name'];
                $array['exam_result'] = $x;
                $new_array[] = $array;
            }
            $data['examSchedule'] = $new_array;
        }
          $data['examlist'] =  $examList ; 


         $data['Status'] = 1;
         }else{
           $data['Msg'] = 'No result Found';
           $data['Status'] = 0;
         }

         echo json_encode($data);        
     }
  }
 
public function studentadmission()
{
     $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
     if ($postdata != '')
        {  
         $request = json_decode($postdata);
         if(!empty($request))
          {  
/*   
$data[''] = $request->admission_number;      $data[''] = $request->roll_number;      $data[''] = $request->class;    $data[''] = $request->section;
$data[''] = $request->first_name;   $data[''] = $request->last_name;     $data[''] = $request->gender; $data[''] = $request->dob;
$data[''] = $request->category; $data[''] = $request->religion;  $data[''] = $request->caste; $data[''] = $request->mobile_number;
$data[''] = $request->mail;       $data[''] = $request->admission_date; $data[''] = $request->student_photo; $data[''] = $request->blood_group;
$data[''] = $request->student_house;   $data[''] = $request->height; $data[''] = $request->weight; $data[''] = $request->as_on_date;
$data[''] = $request->father_name;      $data[''] = $request->father_phone; $data[''] = $request->father_occupation;  $data[''] = $request->father_photo;
$data[''] = $request->mother_name;  $data[''] = $request->mother_phone; $data[''] = $request->mother_occupation;   $data[''] = $request->mother_photo;            
$data[''] = $request->guardian_name;  $data[''] = $request->guardian_relation;  $data[''] = $request->guardian_email;   $data[''] = $request->guardian_photo;
$data[''] = $request->guardian_phone; $data[''] = $request->guardian_occupation;  $data[''] = $request->guardian_address;                  
$data[''] = $request->vehicles; $data[''] = $request->route_list; $data[''] = $request->fare;    $data[''] = $request->hostel;
$data[''] = $request->room_number;   $data[''] = $request->bank_account_number;      $data[''] = $request->bank_name;
$data[''] = $request->ifsc_code; $data[''] = $request->national_identification_number;  $data[''] = $request->local_identification_number;                      
$data[''] = $request->rte;  $data[''] = $request->previous_school_details; $data[''] = $request->note; $data[''] = $request->current_address_type;
$data[''] = $request->permanent_address_type;
*/
$session = $this->setting_model->getCurrentSession();

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
    'rte' =>$request->rte,
    'email' => $request->mail,
    'guardian_is' => $request->if_guardian_is,
    'religion' => $request->religion,
    'cast' => $request->caste,
    'previous_school' => $request->previous_school_details,
    'dob' => date('Y-m-d', strtotime($request->dob)),
    'current_address' => $request->current_address_type,
    'permanent_address' => $request->permanent_address_type,
    'image' => 'uploads/student_images/no_image.png',
    'category_id' => $request->category,
    'adhar_no' => $request->category,
    'samagra_id' => $request->local_identification_number,
    'bank_account_no' =>  $request->bank_account_number,
    'bank_name' => $request->bank_name,
    'ifsc_code' =>  $request->ifsc_code,
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
    'school_house_id' =>  $request->student_house,
    'blood_group' =>  $request->blood_group,
    'height' => $request->height,
    'weight' => $request->weight,
    'note' => $request->note,
    'is_active' => 'yes',
   // 'measurement_date' => date('Y-m-d', strtotime($this->input->post('measure_date')))
);

$insert_id = $this->student_model->add($data);


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

/* if ($sibling_id > 0) {
    $student_sibling = $this->student_model->get($sibling_id);
    $update_student = array(
        'id' => $insert_id,
        'parent_id' => $student_sibling['parent_id'],
    );
    $student_sibling = $this->student_model->add($update_student);

} else { */ 
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
  
  
//}

/* 
if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
    $fileInfo = pathinfo($_FILES["file"]["name"]);
    $img_name = $insert_id . '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
    $data_img = array('id' => $insert_id, 'image' => 'uploads/student_images/' . $img_name);
    $this->student_model->add($data_img);
}

if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
    $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
    $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
    $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/student_images/' . $img_name);
    $this->student_model->add($data_img);
}
if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
    $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
    $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
    $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
    $this->student_model->add($data_img);
}

if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
    $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
    $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
    $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
    $this->student_model->add($data_img);
}

if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
        die("Error creating folder $uploaddir");
    }
    $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
    $first_title = $this->input->post('first_title');
    $img_name = $uploaddir . basename($_FILES['first_doc']['name']);
    move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
    $data_img = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => basename($_FILES['first_doc']['name']));
    $this->student_model->adddoc($data_img);
}
if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
        die("Error creating folder $uploaddir");
    }
    $fileInfo = pathinfo($_FILES["second_doc"]["name"]);
    $second_title = $this->input->post('second_title');
    $img_name = $uploaddir . basename($_FILES['second_doc']['name']);
    move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
    $data_img = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => basename($_FILES['second_doc']['name']));
    $this->student_model->adddoc($data_img);
}

if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
        die("Error creating folder $uploaddir");
    }
    $fileInfo = pathinfo($_FILES["fourth_doc"]["name"]);
    $fourth_title = $this->input->post('fourth_title');
    $img_name = $uploaddir . basename($_FILES['fourth_doc']['name']);
    move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
    $data_img = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => basename($_FILES['fourth_doc']['name']));
    $this->student_model->adddoc($data_img);
}
if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
        die("Error creating folder $uploaddir");
    }
    $fileInfo = pathinfo($_FILES["fifth_doc"]["name"]);
    $fifth_title = $this->input->post('fifth_title');
    $img_name = $uploaddir . basename($_FILES['fifth_doc']['name']);
    move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
    $data_img = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => basename($_FILES['fifth_doc']['name']));
    $this->student_model->adddoc($data_img);
} */



$userlisting = $this->student_model->searchByClassSectionWithSession($class_id, $section_id, $session);

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
$students_no = $this->student_model->get_all_student_no($class_id, $section_id, $session);
if (!empty($students_no)) {
    $data_num = array(
        'id' => $students_no['id'],
        'numbers' => $user_array,
    );
} else {
    $data_num = array(
        'session_id' => $session,
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
//end class
}

?>

