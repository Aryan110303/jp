  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  //defined('BASEPATH') OR exit('No direct script access allowed');

  class Registration_api extends CI_Controller {
    

    public function __construct(){
      parent::__construct(); 
      $this->load->model('Api_model');
      $this->load->model('Proxy_model');
      $this->load->model('Messages_model');
      $this->load->model('Studentfeemaster_model');
      $this->load->library('Enc_lib'); 
      $this->load->library('form_validation'); 
      $this->loopCount = 0;      
    }


    public function staff_login(){
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
  
  //--------FEES SOFTWARE aPI START FROM HERE --------//

  public function Feestaff_login() {

    $postdata = file_get_contents("php://input");
  //$postdata = $this->input->post('data');
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
       $current_date = $this->getValidDate();
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
     
    if ($postdata != ''){           
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



 
  public function formdetails()
  {    
      $class_list =  $this->class_model->get();
      $session_list =  $this->db->query("SELECT * from sessions")->result();
      $data = $this->Api_model->get_admission_reciept();
      $outputarr['receipt_number'] = $data['receipt_no']+1;  
      $outputarr['registration_form_no'] = '' ;//$data['registration_id']+1;  
      $outputarr['registration_form_name'] = "CAS";  
      $outputarr['feesAmount'] = 400;  
      $outputarr['feesHead'] = "Prospectus Fees ";  
      $outputarr['class_list'] = $class_list; 
      $outputarr['session_list'] = $session_list; 
      $outputarr['Status'] = 1 ;  
      
     echo json_encode($outputarr);
  }

  //for admission entrance add data

  public function Add_admission_ent()
  {  
   $postdata = file_get_contents("php://input");
    if ($postdata != '')
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {
       $data['fname'] =$request->fname ;
       $data['lname'] =$request->lname ;
       $data['father_name'] =$request->father_name ;
       $data['mother_name'] =$request->mother_name ;
       $data['phone'] =$request->phone ;
       $data['class'] =$request->class_id ;
       $data['session_id'] =$request->session_id  ;
       $data['result'] = 0 ;
       $data['registration_form_no'] = $request->registration_form_no ;
       $data['examDate'] =$request->examDate;
       $reciept = $request->reciept_no ;
       $this->db->insert('admission_soft',$data);
       $id = $this->db->insert_id();
       if (!empty($id)) {
        $data2['date'] = date('Y-m-d') ;
        $data2['receipt_no'] = $request->reciept_no ;
        $data2['fees_amount'] = $request->fees_amount ;
        $data2['session_id'] =  $request->session_id  ;
        $data2['registration_id'] = $id;
         $this->db->insert('student_admission_fees',$data2);
         $result = $this->Api_model->get($id); 
         $outputarr['Result'] =  $result;
         $outputarr['Msg'] =  "Successfully Added";
         $outputarr['Status'] = 1 ; 
       }  
        else{
          $outputarr['Msg'] =  "Data Not Posted";
          $outputarr['Status'] = 0;  
       }       
     }
     else{ 
          $outputarr['Msg'] =  "Data Not Posted";
          $outputarr['Status'] = 0;      
       }
  }else{ 
    $outputarr['Msg'] =  "Data Not Posted";
    $outputarr['Status'] = 0;     
  }
echo json_encode($outputarr);

  }

  public function ClassList()
  {
    $class = $this->class_model->get();
    $data['classlist'] = $class;
    $data['Status'] = 1;
    $data['Msg'] = "Class List Found";
    echo json_encode($data);
  }

public function RegistrationList()
{
  $postdata = file_get_contents("php://input");
   //$postdata  = '{"date":"2019-12-04","examDate":""}';
   if (!empty($postdata))
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
      $class_id = $request->class_id ;
      $examDate = $request->examDate ;

    if ($request->class_id != '' ) 
         {
          $data = $this->db->query("SELECT 
              admission_soft.registration_form_no,
              admission_soft.fname,
              admission_soft.lname,
              admission_soft.father_name,
              admission_soft.mother_name,
              admission_soft.phone,
              admission_soft.result,
              admission_soft.examDate,
              admission_soft.session_id,
              admission_soft.created_at,
              admission_soft.class as class_id,
              classes.class
               from admission_soft join classes on classes.id = admission_soft.class where admission_soft.class >= '$class_id'")->result();
         }elseif($request->examDate != '')
        {
         $data = $this->db->query("SELECT 
              admission_soft.registration_form_no,
              admission_soft.fname,
              admission_soft.lname,
              admission_soft.father_name,
              admission_soft.mother_name,
              admission_soft.phone,
              admission_soft.result,
              admission_soft.examDate,
              admission_soft.session_id,
              admission_soft.created_at,
              admission_soft.class as class_id,
              classes.class from admission_soft join classes on classes.id = admission_soft.class where examDate = '$examDate'")->result();
        }else{
              $data = $this->db->query("SELECT  admission_soft.registration_form_no,
              admission_soft.fname,
              admission_soft.lname,
              admission_soft.father_name,
              admission_soft.mother_name,
              admission_soft.phone,
              admission_soft.result,
              admission_soft.examDate,
              admission_soft.session_id,
              admission_soft.created_at,
              admission_soft.class as class_id,
              classes.class
              from admission_soft join classes on classes.id = admission_soft.class ")->result();
             }
                $outputarr['Data'] = $data;
                $outputarr['Msg'] =  "Request found";
                $outputarr['Status'] = 1; 
     }else{
          $outputarr['Msg'] =  "Request Data Not Posted";
          $outputarr['Status'] = 0; 
     }
    }else{
          $outputarr['Msg'] =  "Post Data Not Posted";
          $outputarr['Status'] = 0;     
    } 
  echo json_encode($outputarr);
}
    
public function admissionDailyfeesReport()
{
  $postdata = file_get_contents("php://input");
  //$postdata  = '{"date":"2019-12-04"}';
   if (!empty($postdata))
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     { 
      $date = $request->date ;
      $feesList = $this->Api_model->getadmissiondailyreport($date);
        if (!empty($feesList)) {
           $outputarr['feesList'] = $feesList;
           $outputarr['Msg'] =  "Data Found";
           $outputarr['Status'] = 1; 
        }else{
            $outputarr['Msg'] =  "Data Not found";
            $outputarr['Status'] = 0; 
        }
          
     }else{
          $outputarr['Msg'] =  "Data Not Posted";
          $outputarr['Status'] = 0; 
    }
   }else{
          $outputarr['Msg'] =  "Data Not Posted";
          $outputarr['Status'] = 0; 
   }   
    echo json_encode($outputarr);
}

public function enquiryList()
{
  $result = $this->db->query("SELECT * from enquiry where 1")->result();
  if (!empty($result)) {
          $outputarr['Result'] = $result;
          $outputarr['Msg'] =  "Data Found";
          $outputarr['Status'] = 1; 
  }else{
          $outputarr['Msg'] =  "Data Not Found";
          $outputarr['Status'] = 0; 
   }   
    echo json_encode($outputarr);
}
  

  public function saveEnquiry()
  {
   $postdata = file_get_contents("php://input");
   if (!empty($postdata))
   {  
     $request = json_decode($postdata);
     if(!empty($request))
     { 
           $data['name'] = $request->name;
           $data['contact'] = $request->contact;
           $data['address'] = $request->address;
           $data['reference'] = $request->reference;
           $data['date'] = $request->date;
           $data['description'] = $request->description;
           $data['follow_up_date'] = $request->follow_up_date;
           $data['note'] = $request->note;
           $data['source'] = $request->source;
           $data['email'] = $request->email;
           $data['no_of_child'] = $request->no_of_child;
           $data['class'] = $request->class;
           $data['status'] = $request->status;

       $this->db->insert('enquiry',$data);
       $res = $this->db->insert_id();
        if ($res) {
           $outputarr['Msg'] =  "Enquiry Added Successfully";
           $outputarr['Status'] = 1; 
        }else{
             $outputarr['Msg'] =  "Not Added Successfully";
             $outputarr['Status'] = 0; 
            }          
      }else{
          $outputarr['Msg'] =  "Request Data Not Posted";
          $outputarr['Status'] = 0; 
         } 
   }else{
          $outputarr['Msg'] =  "Post Data Not Posted";
          $outputarr['Status'] = 0; 
   }   
    echo json_encode($outputarr);
}

public function deleteEnquiry()
  {
   $postdata = file_get_contents("php://input");
   if (!empty($postdata))
   {  
     $request = json_decode($postdata);
     if(!empty($request->id))
     { 
       $id = $request->id;
       $this->db->where('enquiry.id', $id);
       $this->db->delete('enquiry');      
       $outputarr['Msg'] =  "Record Deleted Successfully";
       $outputarr['Status'] = 1; 
              
      }else{
          $outputarr['Msg'] =  "Request Data Not Posted";
          $outputarr['Status'] = 0; 
         } 
   }else{
          $outputarr['Msg'] =  "Post Data Not Posted";
          $outputarr['Status'] = 0; 
        }   
     echo json_encode($outputarr);
  }

      // ----------- End Class ----------- //
}


