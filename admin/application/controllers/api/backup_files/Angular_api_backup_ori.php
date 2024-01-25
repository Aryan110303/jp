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
     /*  $url = "http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username=casjbp&password=password&sender=CASJBP&to=$phone&message=$msg&reqid=1&format={json|text}&route_id=113";
      */ $ch=curl_init();
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
  
             $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
             $studentList  = $this->student_model->searchByClassSection($class_id, $section_id);
          
           
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
              $session = $this->setting_model->getCurrentSession();
              $class=$request->class_id ; 
              $section=$request->section_id ;
              $student_id=$request->student_id ;
          $examList = $this->examschedule_model->getExamByClassandSection($class, $section);
           if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
              $sort_result_perexam=array();
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student_id;
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student_id);
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
//    $class=30; 
//    $section=17;
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
                
          $studetails['gender'] = $stuval['gender'];
          $sessionname = $stuval['sessionname'];  

          $exam_subjects = $this->examschedule_model->getresultByStudentandExam_pdf($exam_id, $stuval['id']);
       // echo $this->db->last_query(); 
          $exam_subjects_1[] =  $exam_subjects ;
        /*  echo"<pre>";
         print_r($exam_subjects);*/
          if (!empty($exam_subjects)) {                 
            foreach ($exam_subjects as $key => $value) {
              $exam_array = array();
              $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
              $exam_array['exam_id'] = $value['exam_id'];
              $optional = $value['optional_status'];
              if( $optional == 1){$exam_array['full_marks'] = 0 ;}else{ $exam_array['full_marks'] = $value['full_marks']; }
             
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
            $marks[]=$sort_result_perexam_val;
          }
        }     
$finalmax=[];
$finalobt= [];

if ($stuval['class'] == 'IX') {

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

$html = '';
$html.='<!DOCTYPE html>
<html lang="en">
<head>
  <title> MarkSheet </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
      .main{
          font-family: sans-serif;
          margin: 0px auto;
          padding: 0px;
          color: #000;
          font-size: 13px;
         
      }     
      pre{
        white-space: unset;
      }
  </style>
</head>
<body class="main">

 
 <table cellpadding="0" cellspacing="0" width="100%" >
    <tr>
      <td>
     
            <table cellpadding="0" cellspacing="0" width="100%" >
                <tbody>
                <tr>
                    <td width="100"><img src="'.base_url().'uploads/logo2.jpg" alt="logo" style="height: 100px; width:80px"></td>
                    <td style="text-align: center; text-transform: uppercase">
                    
                        <div style="color: #ef3035;font-weight: 600;font-size: 28px;">Central Academy Senior Secondary School  </div>
                        <div style="font-size: 12px;font-weight: bold;">
                            Kanchan Vihar, Vijay Nagar, Jabalpur(M.P.),  PHONE : 0761-4928811  <br>
                            EMAIL : MANAGEMENT@CENTRALACADEMYJABALPUR.COM,, Website : www.centralacademyjabalpur.com <br>
                            (Affiliated to CBSE, New Delhi)
                        </div>
                    </td>
                </tr>

                <tr style="font-size:13px;font-weight: 600;line-height: 22px;">

               <td colspan="3">
               <br><br>
                     <table width="100%"  >
                     <tr>
                     <td style="margin-left:5px; "> <b>Affiliation No.: 1030293</b></td>
                      <td>  <div style="font-size: 15px;color: green;font-weight: bold;"> SESSION : '.$sessionname.'</div></td>
                         <td style="text-align:right;">  </td>
                     </tr>
                     </table>
                 </td>
                </tr>
       

     
                </tbody>
            </table>
            </td>
            </tr>
    
<tr>
  <td>
      <table style="width: 100%">
          <tbody>
          <tr>  
              <td>
                  <table>
                      <tbody>
                      <tr><td style="font-weight: bold"> Scholar Number</td><td> '. $studetails['scholar_no'].'</td></tr>
                      <tr><td  style="font-weight: bold"> Roll Number </td><td> '. $studetails['rollno'].'</td></tr>
                      <tr><td  style="font-weight: bold"> Student`s Name </td><td> '.$studetails['name'].'</td></tr>
                      <tr><td  style="font-weight: bold"> Father`s Name </td><td>'. $studetails['fname'].' </td></tr>
                      <tr><td  style="font-weight: bold">  Mother`s Name</td><td> '.$studetails['mname'].'</td></tr>
                   
                      </tbody>
                  </table>
              </td>
              <td style="float: right">
                  <table>
                      <tbody>
                      <tr>  
                      <td  style="font-weight: bold">  School Code No. :</td><td> 14151</td>   </tr>   <tr> 
                      <td  style="font-weight: bold">  Class/ Section :</td><td> '.$studetails['class'] .' '.$studetails['section'].'</td> 

                       <tr><td  style="font-weight: bold">  Date of Birth :</td><td> '.date("d-m-Y",strtotime($studetails['dob'])).'</td></tr>
                  
                      </tbody>
                  </table>
              </td>
              <td style="float: right">
              <table>
                  <tbody>
                  <tr>  
                 
                  <td rowspan="5" > <img src="'.base_url($studetails['image']).'" style="width: 90px;text-align: center;border: 1px solid #464646;height: 100px;"></td></tr>
                 
                  </tbody>
              </table>
          </td>
          </tr>
          </tbody>
      </table>
      </td>
      </tr>';
    $html.='
    <tr>
    <td>
    <br><br>
    <table style="text-align: center">
          <tbody>
          <tr>
              <td>
                  <table style=" border-collapse: collapse;width: 100%" border="1" >
                      <tbody>
                      <tr><td colspan="2" width="26%" style="text-align:center"><b>SUBJECT </b></td> ';
                    
          
                 
foreach ($new_array as $key => $nary) {                        
 $html.= '<td colspan="2" style="text-align:center" ><b>'.$nary['exam_name'].'</b> </td>';                         
}                       
  $html.= '</tr>
          <tr>
        <td colspan="2"> </td>';
    foreach ($new_array as $key => $nary) {                        
            $html.= ' <td><b> MAX MARKS </b></td>
                      <td> <b>OBT. MARKS</b></td>';     
}      
  $html.= '</tr>';

$html.= '<tr>
<td colspan="2" style="border:none">
<table border="1" cellspacing="0" style="width:100% ;" >

<tbody>';
foreach ($new_array[1]['exam_result'] as $key => $nary) {
$html.= '<tr>
    <td height="18px"; > 
    '.ucfirst(strtolower($nary['exam_subject_name'])).'
   </td>
 </tr>';
}
$html.= '</tbody>
</table>
</td>';
foreach ($new_array as $key => $nary) {
$html.= '<td colspan="2" style="border:none">';

  $html.= '<table style="width: 100%;  height:100%;" border="1"  cellspacing="0" >';
  foreach ($nary['exam_result']  as $key => $nary1) {
   
             $html.='  <tr>
                                <td>'.$nary1['full_marks'].' </td>
                                <td>'.round($nary1['get_marks'],2).' </td>
                             </tr>';

     
           }
           $html.= '</table> ';
$html.= '</td>';
}
  $html.= ' </tr> </tbody>
               </table>     
             
          

        </td>

                  <td width="30%"> ';


            $html.= '     <table style="width: 100%; " border="1" cellspacing="0">
                      <tbody>
                      <tr>
                          <td colspan="2" style="text-align:center"> <b>GRAND TOTAL</b> </td>
                          <td colspan="2" style="text-align:center"><b> YEAR`S AVERAGE </b> </td>
                      </tr>

                      <tr>
                          <td> <b>MAX MARKS</b> </td>
                          <td><b>OBT. MARKS </b></td>
                          <td><b>MAX MARKS </b></td>
                          <td><b>OBT. MARKS</b></td>
                      </tr>';

for ($l=0; $l <count($finalmax); $l++) { 
                 $html.='<tr>

                          <td>'.$finalmax[$l].'</td><td>'.$finalobt[$l].'</td><td>'.round(($finalmax[$l]/count($new_array)),2).'</td><td>'.round($finalobt[$l]/count($new_array),2).'</td> 

                         </tr> ';
                      } 
                      $html.=' 
                     </tbody>
                  </table>';





          $html.='     </td>
          </tr>


          

          </tbody>
      </table>
      
      
      </td></tr>
      <tr><td >  ';
       
$html.='<table  style="width: 100%; "  >
            <tbody>
               <tr>
              
                    <td width="18.5%">
                                    <table border="1" style="text-align:center;  width:100%" cellspacing="0" >
                                    <tr>
                                    <td height="18px">TOTAL</td>

                                    </tr>
                                    <tr>
                                    <td height="18px" >PERCENTAGE</td> 
                                    </tr>
                                    <tr >
                                    <td height="18px">ATTENDANCE</td> 
                                    </tr>
                                    <tr rowspan="3">
                                    <td height="54px"></td> 
                                    </tr>
                                 
                                  
                                   
                                    </table>
                    </td>

                    <td>

                    <table style="width:100%; text-align:center;" cellspacing="0"  >';
                    $totfim= 0;
                    $totfio=  0;
                    $totfima= 0;
                    $totfioa= 0;
                    for ($l=0; $l <count($finalmax); $l++) {
                        $totfim+= $finalmax[$l];
            $totfio+=  $finalobt[$l];
            $totfima+=  round($finalmax[$l]/count($new_array),2);
            $totfioa+=  round($finalobt[$l]/count($new_array),2);

            }
 
        $html.='     <tr>';
        
    foreach ($new_array as $key => $narys) {
        $html.='   <td width="30%"> 
                          <table style="width:100%; " border="1" cellspacing="0" >
                                                    <tr> ';      
                                                        $gm=0;
                                                        $om=0;
                                                                foreach ($narys['exam_result']  as $key => $nary1s) {                                                                
                                                                    $gm += $nary1s['full_marks'];
                                                                    $om += $nary1s['get_marks'];

                                                                 }
                                                                 
                                                        $html.=' <td colspan="2">'.$gm.'</td>
                                                                 <td colspan="2">'.$om.'</td>';

                         $html.='</tr>
                                <tr>
                                    <td colspan="4">'.round(($om*100/$gm),2).' %</td>                                                                       
                                </tr>
                         </table>
                                                    
              </td>';
            }
                                    
                        $html.='</tr>
                            <tr>
                            <td  height="18px" colspan="'.count($new_array).'" ></td>   
                            </tr>
                            <tr>
                            <td  height="18px" colspan="'.count($new_array).'" ></td>  
                            </tr>

                            <tr>
                            <td height="18px"  colspan="'.count($new_array).'" ></td>                  
                            </tr>

                            <tr>
                            <td height="18px" colspan="'.count($new_array).'"  > </td>   
                
                            </tr>
     </table>
                    </td>

                    <td width="30%">
                    <table border="1" style="text-align:center; border-collapse: collapse; width:100%;"  >                                                <tr>
                                                <td >'.$totfim.'</td>                                            
                                                <td>'.$totfio.'</td> 
                                                <td>'.$totfima.'</td>
                                                <td>'.$totfioa.'</td> 
                                                </tr>
                                                    <tr>
                                                    <td colspan="4"><b> '.round(($totfioa*100)/$totfima,2)
                                                  .' % </b></td> 
                                                    </tr>
                                            <tr>
                                            <td colspan="4" ;rowspan="4" height="80px"></td> 
                                            </tr>
                                        </table>
                    </td>
               </tr>
            </tbody>
         </table>
         
         </td>
         </tr>
   <tr>
   <td>';
  
   if($class < 33){
       $gr = round(($totfioa*100)/$totfima,2) ;
       if($gr < 50){
$grd = "D";
       }
       elseif($gr < 60){
        $grd = "C";
               }
               elseif($gr < 70){
                $grd = "B";
                       }
                       elseif($gr <80){
                        $grd = "B+";
                               }
                               elseif($gr < 90){
                                $grd = "A";
                                       }
                                       elseif($gr < 100){
                                        $grd = "A+";
                                               }
    $grading = $this->student_model->get_grade_student($studetails['scholar_no']);  
  //  print_r($grading) ; die;         
      $html.='  <table style=" border-collapse: collapse;width: 100%" border="1">
          <tbody>
          <tr>
              <td>CO-SCHOLASTIC AREAS <br> (On a 5 point grading scale : A-E)</td>
              <td style="text-align:center"> GRADE</td>
            
              <td style="text-align:center">OverAll GRADE</td>
          </tr>
  
          <tr>
              <td> Drawing</td>
              <td  style="text-align: center">'.$grading->drawing.' </td>
              <td rowspan="6" style="text-align: center">'.$grd.' </td>
          </tr>

          <tr>
              <td> Neatness</td>
              <td style="text-align: center"> '.$grading->neatness.'</td>
          </tr>

          <tr>
              <td> Puntuality </td>
              <td style="text-align: center"> '.$grading->punctuality.'</td>
          </tr>

          <tr>
              <td>Courteousness</td>
              <td style="text-align: center"> '.$grading->courteousness.' </td>
          </tr>

          <tr>
              <td > Obedience</td>
              <td style="text-align: center"> '.$grading->obedience.'</td>
          </tr>
          <tr>
          <td> Discipline</td>
          <td style="text-align: center"> '.$grading->discipline.'</td>
         </tr>

          </tbody>
      </table>';            
              
           }
$remrkkk = round(($totfioa*100)/$totfima,2) ;
if ($remrkkk  < 60) {
  $remak = "Average";
}elseif ($remrkkk  < 76) {
  $remak = "Good";
}elseif ($remrkkk  < 60) {
  $remak = "Very Good";
}elseif ($remrkkk  < 60) {
  $remak = "Excellent";
}

      $html.=' <table style="width: 100%;" >
          <tbody>
        
          <tr> 

              <td> 
                  <table width="100%">
                      <tbody>
                      <tr style="border-bottom: 1px display:block;margin: 5px 0px;">
                          <td style="background: #fff;display: inline-block;margin-bottom: -5px;">Class Teacher`s Remarks &nbsp; <b>'. $remak  .'</b></td>
                      </tr>
                      </tbody></table>
              </td>
              <td>

              </td>
              <td>
                  <table width="100%">
                      <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                        
                      </tr>
                      </tbody></table>
              </td>
          </tr>
          </tbody>
      </table>

      <div style="margin: 0px;padding: 0px"> <h4 style="margin: 3px 0px"><b>RESULT </b> PROMOTED TO CLASS </h4> </div>
         <br> <br> <br> 
      <table style="width: 100%;">
          <tbody>
          <tr>
          
              <td  style="text-align:left ; height:60px; " > <br><br>Place :  JABALPUR <br> Date : '.date("d-m-Y").'</td>
              <td style="text-align:left ;height:60px;  padding:5%;" ><br><br>Class Teacher`s Sign  </td>

              <td style="text-align:right;">
              <table style="text-align:right;">
              <tr>
              <td style="text-align:right;">
                <img style="padding-right:2px;" width="100" height="50" src="'.base_url().'/uploads/principal_sign.jpg">
              </td>
              </tr>
              <tr>
                <td style="text-align:right;">
                Principal`s Sign
                </td>
              </tr>
              </table>
              
               </td>
          </tr>
          </tbody>
      </table>
   </td>
   </tr>
         
       </table>
         ';  
         
         

 $html.=
 
 
 ' 

</body>
</html>';

//ends heere
//echo $html; die;
$code=$studetails['scholar_no'];
$m_pdf = new mPDF();
$m_pdf->SetDisplayMode('real');
    
$m_pdf->WriteHTML($html);
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

///new api for all student markshet end 
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





public function studentadmission()
{
     $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
     if ($postdata != '')
        {  
         $request = json_decode($postdata);
         if(!empty($request))
          {  
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
          
           $entry = array(
                'exp_head_id' => $exp_head_id,
                'name' => $name,
                'date' => $date,
                'amount' => $amount,
                'invoice_no' => $invoice_no,
                'note' => $description,               
                          
            );

            $insert_id = $this->expense_model->add($entry);
           /*    if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])){
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/school_expense/" . $img_name);
                $data_img = array('id' => $insert_id, 'documents' => 'uploads/school_expense/' . $img_name);
                $this->expense_model->add($data_img);
                   $data['msg']='Addes Successfully' ;
                   $data['Status'] = 1 ;
             }
               else{
             $data['msg']='Id not Found' ;
             $data['Status']= 0 ;
            }  'documents' => $documents,     */  
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
          $this->db->insert('extra_fees',$data);
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
    //$postdata = file_get_contents("php://input");
   $postdata =  $this->input->post('data');  
      $data= ''; 
      $arr= ''; 
   if ($postdata != '')
      {  
         $request = json_decode($postdata);
         if(!empty($request))
        {  
       
          $class = $request->class_id ;
          $section = $request->section_id ;
          $arr['resultlist'] = $this->student_model->searchByClassSection($class, $section);       
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
       $data['student'] = $this->student_model->get($id);
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
//end class
}

?>

angular_api_backup