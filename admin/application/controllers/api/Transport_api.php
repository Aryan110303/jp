  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  //defined('BASEPATH') OR exit('No direct script access allowed');

  class Transport_api extends CI_Controller {
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

//------------------------Transport fee ----------------------------------//

  public function Transportfee_details()
  { 
     $postdata = file_get_contents("php://input");
     //$postdata = '{"id":"761","session_id":"14"}';
     if (!empty($postdata)){  
  
      $request = json_decode($postdata);

      $id = $request->id ;
      $session_id = $request->session_id ;

      $student = $this->Student_model->api_get($id,$session_id);   
      $studenttransport_paidfee = $this->Api_model->get_depositetransport_fee($student['student_session_id'] ,$session_id);  
      /*print_r(count($studenttransport_paidfee)) ; die;*/
        $paidfee_count =count($studenttransport_paidfee);
      if ($student['transport_fees'] > 0 ) {
         $singleamount = $student['transport_fees'] /10 ;
            $installment = array() ;        

         for ($i=0; $i < 10 ; $i++) { 
            $c= $i;
            $installment['name'] = 'installment'.++$c ;              
            $installment['amount'] = $singleamount ;
            if ($paidfee_count > 0) {
               $installment['status'] = 'Paid' ;  
            }else{
            $installment['status'] = 'Unpaid' ; 
            } 
            $installment_array[] = $installment ;   
            $paidfee_count-- ;  
           }
            $date1 = date('Y-m-d') ;
            $qry =  "SELECT next_date from datestatustransport where dl_date ='".$date1."' and status = 1 ;" ; 
            $date = $this->db->query($qry)->row();
                if ($date != '') {
                  $feeA['feesdate'] = $date->next_date ;
                }else{
                 $feeA['feesdate'] = $date1 ;
                 }
           
           $feeA['student'] = $student;
           $feeA['student_transport_due_fee'] = $installment_array;
      }
     if (!empty($feeA))
      {
       $outputarr['Result'] = $feeA ;
       $outputarr['Status'] = 1; 
       $outputarr['Msg'] = "Result Found";
      }
      else { 
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed To Search ";
      }     
  }  
      else { 
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Failed To Search ";
      }     
    echo json_encode($outputarr);
  }


  public function add_transportfee()
  {    
   $postdata = file_get_contents("php://input");
    // $postdata =  $this->input->post('data');  
   if ($postdata != '' ){     
    $request = json_decode($postdata);
    if(!empty($request)){
      $request = $request->PaymentDetails ;
      $count = 0;
      $fee_receipt_no= $this->Api_model->transportFeeereceipt_no();
      foreach($request as $val){     
           $collected_by = "Collected By:Accountant";             
          $data = array(      
            'student_session_id' => $val->student_session_id,
            'session_id' => $val->session_id,   
            'description' => $val->description . $collected_by,
            'amount' => $val->amount,
            'payment_mode' => $val->payment_mode,  
            'amount_discount' => $val->amount_discount,
            'date' => $val->date,
            'receipt_no' => $fee_receipt_no,
            'amount_fine' => $val->amount_fine         
          );
          $inserted_id = $this->Api_model->transportfee_deposit($data, $student_fees_discount_id);
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




 //---------------------------transport fee end----------------------------------//
  ///////////////////End Class/////////////////////////
 
}

   ?>

