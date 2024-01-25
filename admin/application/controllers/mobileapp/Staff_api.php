<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
//header('Content-Type: bitmap; charset=utf-8');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
       //defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_api extends CI_Controller { 
    public function __construct() {
        parent::__construct(); 
        $this->load->model('mobileapp/Api_model','Api_model');
        $this->load->model('Admin_model');
        $this->load->model('Examschedule_model');
        $this->load->model("mobileapp/Api_Classteacher_model","Api_Classteacher_model");
        $this->load->model('staff_model');
        $this->load->library('Enc_lib'); 
        $this->load->library('Customlib'); 
        $this->load->library('form_validation'); 
        $this->current_session = $this->setting_model->getCurrentSession();
    }


    public function staff_login() {
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {  
      $request = json_decode($postdata);
      
                  $phone =$request->phone;
                  $pass =$request->pass;
                  $token =$request->token;
                    $data = array(
                    'moblie' => $phone,
                    'password' => $pass,
                   );

                $row = $this->Api_model->app_login($data);

                if ($row != false) {
                  if (!empty($token)) {
                    $updatedata['device_token'] = $token ; 
                    $this->db->where('contact_no',$phone);
                    $this->db->update('staff', $updatedata);
                  }
                           
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
 

 public function ClassList($value='')
  {
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['Status'] = 1;
        echo json_encode($class);
  }



public function Sectionbyclassid()
{
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {  
    
       $request = json_decode($postdata);
   $class_id =  $request->class_id;    
   if ($class_id != '')
      {  
   $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
            sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class_id."' order by sections.section ";
   $result = $this->db->query($qry)->result_array();
   $data['sections'] =$result ;
   $data['Status'] =  1 ; 
    }else{
        $data['msg'] = 'no section found' ; 
        $data['Status'] = 0 ; 
    }
  }
    else{
        $data['msg'] = 'Post Data not found' ; 
        $data['Status'] = 0 ; 
    }
    echo json_encode($result);
}


public function teacherDetails(){
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {  
    
    $request = json_decode($postdata);

    if ($request != '') {  
          $teacher_id =  $request->teacher_id;    

        $result = $this->Api_model->getProfile($teacher_id);
      
        if ($result) {
            $outputarr['Status'] = true; 
            $outputarr['Msg'] = "Success";
            $outputarr['Result'] = $result ;
           
        }else{
            $outputarr['Status'] = false; 
            $outputarr['Msg'] = "No Record Found";
        }
    
     }else{
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Postdata  Found";
     }
   }else
      {
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Postdata  Found";
     }
     echo json_encode($outputarr);
    }



public function updatePassword()
{ 
  $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {  
    
    $request = json_decode($postdata);

    $oldpw =  $request->oldpw;    
    $newpw =  $request->newpw;    
    $hashpw =  $request->hashpw;    
    $id =  $request->id;    
    if ($id != '' ) {  
        $newdata = array(
            'id' => $id,
            'password' => $this->enc_lib->passHashEnc($newpw)
        );
    $check = $this->enc_lib->passHashDyc($oldpw, $hashpw);   
        if ($check) {
            $query2 = $this->admin_model->saveNewPass($newdata);            
            $outputarr['Status'] = true; 
            $outputarr['Msg'] = "Password changed Successfully";
        }else{
            $outputarr['Status'] = false; 
            $outputarr['Msg'] = "Password changing fail";
        }

}else{
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No postdata Found";
}
}else{
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No postdata Found";
}
echo json_encode($outputarr);
}
   



public function studentList(){
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
        $request = json_decode($postdata);
        $class =  $request->class; 
        $section =  $request->section; 
   
  if ($class != '' && $section!= '') {   
    $studentlist  = $this->student_model->searchByClassSection($class, $section);
    if (!empty($studentlist)) {
        $outputarr['Status'] = true; 
        $outputarr['Msg'] = "Success";
        $outputarr['Result'] = $studentlist ;
    } 
    else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
       }  
    }else{
         $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
    } 

     } 
    else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
       } 

    echo json_encode($outputarr);
}


public function studentList_new(){
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
        $request = json_decode($postdata);
        $class =  $request->class; 
        $section =  $request->section; 
   
  if ($class != '' && $section!= '') {   
    $studentlist  = $this->student_model->studetailByClassSection($class, $section);

    if (!empty($studentlist)) {
        foreach ($studentlist as $key => $student) {
          $data  = new stdClass ; 
           $data->display = $student->firstname.' '.$student->lastname ;
           $data->value = $student->student_id ;
           $outputarr[] =$data;
         }
    } 
    else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
       }  
    }else{
         $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
    } 

     } 
    else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
       } 

    echo json_encode($outputarr);
}


public function sendHomework()
{    
    $postdata = file_get_contents("php://input");
    if ($postdata != '')
     {       
        $request = json_decode($postdata);       
            $class =  $request->class;    
            $section =  $request->section;    
            $message =  $request->message;    
            $teacher_id =  $request->teacher_id;    
            $file =  $request->file;    
            $file_type =  $request->file_type;    
    if (!empty($file)) {    
         $base = $file;                          
        $file_name ='homework'.date('Ymdhis'). '.' .$file_type; 
        // Decode Image
        $binary=base64_decode($base);
       
        //$binary =imagejpeg($binary, NULL, 50);
        $file = fopen('uploads/attachments/'.$file_name, 'wb');
        // Create File
        fwrite($file, $binary);
        fclose($file);
        $path = 'uploads/attachments/'.$file_name;
        $img_path = $path;    
         } 
         else{
           $img_path = '';      
             }        
    $studentlist  = $this->student_model->searchByClassSection($class, $section);
    if(!empty($studentlist) ){
    foreach($studentlist as $value){       
               $data1 = array(
              'is_class' => 1,
              'title' => 'Homework',
              'message' => $message,
              'send_mail' => 0,
              'send_sms' => 'Homework',
              'user_list' => $value['guardian_phone'],
              'class_id' => $class,
              'section_id' => $section,
              'session_id' => $this->current_session,
              'sms_status' => 1,
              'department' =>'',
              'msg_type' =>1,
              'other_number'=>'',
              'teacher_id'=>$teacher_id,
              'path'=>$img_path
        );
        $msgId = $this->messages_model->add($data1); 
        $device_token= $value['device_token'];
        $student_id = $value['id'];
        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        if (!empty($device_tokens)) {
          foreach ($device_tokens as $key => $val) {
          $top['to'] = $val->device_token ;
                $message1 = "Homework From School";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Homework', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);

        }
      }
        if($device_token!=''){
        
        $top['to'] = $device_token ;
                $message1 = "Homework From School";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Homework', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
       }
    }
    $outputarr['Status'] =true; 
    $outputarr['Msg'] = "Messsage Send Successfully";

        }else { 
                $outputarr['Status'] = false; 
                $outputarr['Msg'] = "No Record Found";
              }  
      }else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Post Data Found";
      }  
      echo json_encode($outputarr);  
}


public function sendNotice()
 {     
  $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
       $request = json_decode($postdata); 
            $class =  $request->class;    
            $section =  $request->section;    
            $message =  $request->message;    
            $teacher_id =  $request->teacher_id;   
            $studentlist  = $request->s_list;
    if (empty($class))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Class Not Found";
          echo json_encode($outputarr); 
          die; 
        }  
        if (empty($section))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "section Not Found";
          echo json_encode($outputarr); 
          die; 
        } 
        if (empty($message))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "message Not Found";
          echo json_encode($outputarr); 
          die; 
        }
         if (empty($teacher_id))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "teacher Not Found";
          echo json_encode($outputarr); 
          die; 
        }   
            $file =  $request->file;    
            $file_type =  $request->file_type;    
    if (!empty($file)) {    
         $base = $file;                          
        $file_name ='notice'.date('Ymdhis'). '.' .$file_type; 
        // Decode Image
        $binary=base64_decode($base);
        $file = fopen('uploads/attachments/'.$file_name, 'wb');
        // Create File
        fwrite($file, $binary);
        fclose($file);
        $path = 'uploads/attachments/'.$file_name;
        $img_path = $path;    
         } 
         else{
           $img_path = '';      
             }      
    foreach($studentlist as $value){

      $student_detail= $this->student_model->get($value); 
        $data1 = array(
            'is_class' => 1,
            'title' => 'Notice',
            'message' => $message,
            'send_mail' => 0,
            'send_sms' => 'Notice',
            'user_list' => $student_detail['guardian_phone'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $this->current_session,
            'sms_status' => 1,
            'department' =>'',
            'msg_type' =>1,
            'other_number'=>'',
            'teacher_id'=>$teacher_id,
            'path'=>$img_path,
        );
         $msgId = $this->messages_model->add($data1); 

         $device_token=$student_detail['device_token'];

         $student_id = $student_detail['id'];

        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id ")->result();
 if (!empty($device_tokens)) {
        foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token ;
                $message1 = "Notice From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);

        }
      }
        if($device_token!=''){
               
          $top['to'] = $device_token ;
                    $message1 = "Notice From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);
          
        }        
    }
      
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
    } 
    else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "No Post Data Found";
    }
     echo json_encode($outputarr);  
}


public function sendComplain()
{     
  $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
       $request = json_decode($postdata); 
            $class =  $request->class;    
            $section =  $request->section;    
            $message =  $request->message;    
            $teacher_id =  $request->teacher_id;   
            $studentlist  = $request->s_list;
    if (empty($class))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Class Not Found";
          echo json_encode($outputarr); 
          die; 
        }  
        if (empty($section))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "section Not Found";
          echo json_encode($outputarr); 
          die; 
        } 
        if (empty($message))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "message Not Found";
          echo json_encode($outputarr); 
          die; 
        }
         if (empty($teacher_id))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "teacher Not Found";
          echo json_encode($outputarr); 
          die; 
        }   
            $file =  $request->file;    
            $file_type = $request->file_type;    
    if (!empty($file)) {    
         $base = $file;                          
        $file_name ='complain'.date('Ymdhis'). '.' .$file_type; 
        // Decode Image
        $binary=base64_decode($base);
        $file = fopen('uploads/attachments/'.$file_name, 'wb');
        // Create File
        fwrite($file, $binary);
        fclose($file);
        $path = 'uploads/attachments/'.$file_name;
        $img_path = $path;    
         } 
         else{
           $img_path = '';      
             }      
    foreach($studentlist as $value){
        $student_detail= $this->student_model->get($value);
             $data1 = array(
            'is_class' => 1,
            'title' => 'Complain',
            'message' => $message,
            'send_mail' => 0,
            'send_sms' => 'Complain',
            'user_list' => $student_detail['guardian_phone'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $this->current_session,
            'sms_status' => 1,
            'department' =>'',
            'msg_type' =>1,
            'other_number'=>'',
            'teacher_id'=>$teacher_id,
            'path'=>$img_path,
        );
         $msgId = $this->messages_model->add($data1); 

         $device_token=$student_detail['device_token'];

         $student_id = $student_detail['id'];

        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id ")->result();
        if (!empty($device_tokens)) {
         foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token;
                $message1 = "Complain From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Complain', 'is_background' => false, 'message' => $message1, 'image' => '','payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);
        }
      }
        if($device_token!=''){
               
          $top['to'] = $device_token ;
                    $message1 = "Notice From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);          
        }        
    }      
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
  } else{
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Post Data Found";
  }
     echo json_encode($outputarr);  
}


public function sendMessages()
{     
   $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
       $request = json_decode($postdata); 
            $class =  $request->class;    
            $section =  $request->section;    
            $message =  $request->message;    
            $message_type =  $request->message_type;    
            $teacher_id =  $request->teacher_id;   
            $studentlist  = $request->s_list;
    if (empty($class))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Class Not Found";
          echo json_encode($outputarr); 
          die; 
        }  
        if (empty($section))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "section Not Found";
          echo json_encode($outputarr); 
          die; 
        } 
        if (empty($message))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "message Not Found";
          echo json_encode($outputarr); 
          die; 
        }
         if (empty($teacher_id))
        {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "teacher Not Found";
          echo json_encode($outputarr); 
          die; 
        }   
                 
    foreach($studentlist as $value){
        $student_detail= $this->student_model->get($value);
             $data1 = array(
            'is_class' => 1,
            'title' => 'message',
            'message' => $message,
            'send_mail' => 0,
            'send_sms' => 'message',
            'user_list' => $student_detail['guardian_phone'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $this->current_session,
            'sms_status' => 0,
            'department' =>'',
            'msg_type' =>$message_type,
            'other_number'=>'',
            'teacher_id'=>$teacher_id,
            'path'=> '',
        );
         $msgId = $this->messages_model->add($data1); 
         $device_token=$student_detail['device_token'];
         $student_id = $student_detail['id'];
         $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id ")->result();
         if (!empty($device_tokens )) {
         
        foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token;
                $message1 = "New Message From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Message', 'is_background' => false, 'message' => $message1, 'image' => '','payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);
        }
      }
        if($device_token!=''){               
          $top['to'] = $device_token ;
         $message1 = "New Message From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Message', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);          
        }        
    }      
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
  } else{
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Post Data Found";
  }
     echo json_encode($outputarr);  
}

public function sendDailyActivities()
{    
 $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {      
      $request = json_decode($postdata);  
      $class =  $request->class;    
      $section =  $request->section;    
      $message =  $request->message;    
      $teacher_id =  $request->teacher_id;    
      $files =  $request->files; 
      $file_type = 'jpg';   
    if (!empty($files)) { 
        foreach ($files as $key => $filee) {        
              $base = $filee;                          
              $file_name ='activities'.date('Ymdhis').'.'.$file_type; 
              $binary=base64_decode($base);
              $file = fopen('uploads/attachments/'.$file_name, 'wb');
              fwrite($file, $binary);
              fclose($file);
              $path = 'uploads/attachments/'.$file_name;
              $activityImg['image_path'] = $path; 
              $this->db->insert('dailyactivities_images',$activityImg);
              $img_ids[] = $this->db->insert_id();
            }
             $img_idstring = implode(',', $img_ids) ;
          }else{
              $img_idstring =null;      
               }        
     $studentlist  = $this->student_model->searchByClassSection($class, $section);
    if(!empty($studentlist)){
              foreach($studentlist as $value){       
                         $data1 = array(
                        'is_class' => 1,
                        'title' => 'DailyActivities',
                        'message' => $message,
                        'send_mail' => 0,
                        'send_sms' => '0',
                        'user_list' => $value['guardian_phone'],
                        'class_id' => $class,
                        'sms_status' => 1,
                        'department' => 0,
                        'msg_type' => 0,
                        'section_id' => $section,
                        'other_number' => 0,
                        'session_id' => $this->current_session,             
                        'teacher_id'=>$teacher_id,
                        'path'=>$img_idstring,
                    );
                  $msgId = $this->Api_model->addDailyActivities($data1);
                  if ($msgId) {                    
                    $device_token= $value['device_token'];
                    $student_id = $value['id'];
                    $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
                    if (!empty( $device_tokens)) {
                       foreach ($device_tokens as $key => $val) {
                             $top['to'] = $val->device_token ;
                                  $message1 = "Daily Activities From School";
                          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ DailyActivities', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
                          $result=   $this->sendPushNotificationTwo($top);
                          }
                        }
                              if($device_token!=''){        
                              $top['to'] = $device_token ;
                                      $message1 = "DailyActivities From School";
                              $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ DailyActivities', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
                               $result=   $this->sendPushNotificationTwo($top);
                              }

                                 $outputarr['Status'] =true; 
                                 $outputarr['Msg'] = "Messsage Send Successfully"; 
                   }else{
                       $outputarr['Status'] =true; 
                       $outputarr['Msg'] = "Activities Sending Fail"; 
                   }
                 }  
           
        }else { 
                $outputarr['Status'] = false; 
                $outputarr['Msg'] = "No Record Found";
              }  
           }else { 
                $outputarr['Status'] = false; 
                $outputarr['Msg'] = "Post Data Not Found";
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



public function student_attendance(){    
    $postdata = file_get_contents("php://input");
    $outputarr = array();   
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $class = $request->class ;
    $section = $request->section ;
    $date = date('y-m-d');

    $result = $this->Api_model->searchAttendenceClassSectionPrepare_api($class, $section, date('Y-m-d', strtotime($date))); 
      if (!empty($result)) {
          $outputarr['Status'] = true; 
          $outputarr['Msg'] = "Success";
          $outputarr['Result'] = $result ;
      } else { 
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "No Record Found";
      }  
    } 
  else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Post Data Not Found";
       } 
echo json_encode($outputarr);  
}

public function student_attendance_save()
{   
  $postdata = file_get_contents("php://input"); 
     //file_put_contents('mith.txt', $postdata);
   if ($postdata != '') {   
     $request = json_decode($postdata);
 
      $requests =  $request->data ;
      $requestsarray = json_decode($requests);
      $todate = date('Y-m-d') ;
    foreach ($requestsarray as $value) {
            
            $ssessionid = $value->student_session_id;
     if( $value->attendence_id !=0) {
           
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
            $attendn = $this->db->query("SELECT type from attendence_type where id = '".$value->attendence_type_id."'")->row()->type; 
            $device_token= $this->db->query("SELECT students.device_token, students.* from student_session join students on students.id = student_session.student_id where student_session.id = $ssessionid  ")->row();
          $device_tokens = array() ;
           $student_id = $device_token->id;
           if ($student_id != '') {
             $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
           }
          
    if($device_token!=''){
        $stuname = $device_token->firstname.' '.$device_token->lastname ;
        $top['to'] = $device_token->device_token ;
                $message1 = $device_token->firstname.' '.$device_token->lastname." is $attendn on ".$todate." || Central Academy";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
           }         
               if (!empty($device_tokens)) {                
            foreach ($device_tokens as $key => $value) {
              $top['to'] = $value->device_token ;
            $message1 = $stuname." is $attendn on ".$todate." || Central Academy";
            $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
             $result=   $this->sendPushNotificationTwo($top); 
            }  

            }      
    }  

    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Attendance Saved Successfully";    
  }      
 else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
}  
echo json_encode($outputarr);

}


//download history
public function DownloadHistory()
{
   $postdata = file_get_contents("php://input");  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
            $class_id = $request->class_id; 
            $section_id = $request->section_id;
            if (!empty($class_id)) {
              $base = base_url();
                if (!empty($section_id)) {
                    $result = $this->db->query("SELECT students.*, CONCAT('$base',students.image) as image, classes.class, sections.section from students join student_session on student_session.student_id = students.id join classes on classes.id = student_session.class_id join sections on sections.id = student_session.section_id  where student_session.class_id = $class_id and student_session.section_id = $section_id  ")->result(); 
                     if (!empty($result)) {

                      foreach ($result as $key => $value) {
                        if ($value->app_seen_date > '0000-00-00 00:00:00') {
                           $value->app_installed = "Yes" ;
                           $value->app_seen = 'Last Seen on - '.$value->app_seen_date ;
                        }else{
                           $value->app_installed = "No" ;
                           $value->app_seen = '--';
                           }                       
                          }  
                        $data['result'] =  $result;  
                        $data['msg'] = "Result Found";  
                        $data['status'] = true;
                     }else{
                       $data['msg'] = "No result  found";  
                       $data['status'] = false;
                     }
                     
                }else{
                    $data['msg'] = "No Section Id found";  
                    $data['status'] = false;
                }
             
            }else{
               $data['msg'] = "No class Id found";  
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




public function search()
{
      $postdata = file_get_contents("php://input");
   
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



  

public function events(){
    $postdata =  $this->input->post('data');    

   if ($postdata != '')
      {  
       $request = json_decode($postdata);
       if(!empty($request))
        {                        
                            $today = $request->date;
                            $today .= " 00:00:00" ;            
                                $events = $this->Api_Classteacher_model->get_data_by_query("SELECT *, start_date as startdate ,date_format(start_date  , '%d/%m/%Y') as start_date , date_format(end_date  , '%d/%m/%Y') as end_date  FROM events where start_date  >=  '$today' order by startdate asc ");         
                                if ($events) {
                                            $data['records'] = $events;                       
                                            $data['msg'] = "success result found";  
                                            $data['status'] =true;
                                       }
                                else{
                                    $data['records'] = " ";                       
                                            $data['msg'] = "No result found";  
                                            $data['status'] = false;
                                    }
                            }else{
                                $data['msg'] = 'no Post data found 1' ; 
                                $data['Status'] =false ; 
                            }       
                            }else{
                                $data['msg'] = 'no Post data found 21' ; 
                                $data['Status'] = false ; 
                            }                    
                            echo json_encode($data); 
      }  



      public function homework(){
        $postdata =  $this->input->post('data');    
        if ($postdata != '')
           {  
            $request = json_decode($postdata);
            if(!empty($request))
             {  
                 $class_id = $request->class_id; 
                 $section_id = $request->section_id; 
				 
				 $teacher_id = $request->teacher_id;
                 $date = $request->date; 
                 if($date == '0000-00-00'){
                   
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' and teacher_id=$teacher_id ORDER BY id  DESC limit 15"); 
               }else{
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' and teacher_id=$teacher_id and created_at like '%$date%' ORDER BY id  DESC"); 
                 }

                  if(!empty($hw)){
                $data['msg'] = 'Data found' ; 
                $data['Status'] = true ; 
                $data['homeworkList'] = $hw ; 
             }else{
                $data['msg'] = 'Data not found' ; 
                $data['Status'] = false ; 
                }

             }else{
                $data['msg'] = 'no Post data found' ; 
                $data['Status'] = false ; 
             }
             }else{
                $data['msg'] = 'no Post data found' ; 
                $data['Status'] = false ; 
             }       
    
         echo json_encode($data); 
   }
   
   public function notice(){
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         {  
             $class_id = $request->class_id; 
             $section_id = $request->section_id;
             $date = $request->date; 
		      	 $teacher_id=$request->teacher_id; 
             if($date == '0000-00-00'){
             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and  (send_sms = 'Complain' or send_sms ='Notice') and teacher_id=$teacher_id group by message ORDER BY id  DESC  limit 15"); 
             }else{
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and (send_sms = 'Complain' or send_sms ='Notice') and teacher_id=$teacher_id and created_at like '%$date%' group by message ORDER BY id  DESC"); 
              }
             if(!empty($hw)){
                $data['msg'] = 'Data found' ; 
                $data['Status'] = true ; 
                $data['homeworkList'] = $hw ; 
             }else{
                $data['msg'] = 'Data not found' ; 
                $data['Status'] = false ; 
                }
            

         }else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }
         }else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }       

     echo json_encode($data); 
}



  public function examList()
  {
    $postdata = file_get_contents("php://input");  
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
          $class_id  = $request->class_id ;  
          $section_id  = $request->section_id;  
          $examSchedule = $this->examschedule_model->getExamByClassandSection($class_id, $section_id);
          $data['msg'] = 'result found' ; 
          $data['Status'] = true ; 
          $data['examsList'] = $examSchedule ; 
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



  public function examschedule()
  {
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
          $exam_id  = $request->exam_id;  
          $class_id  = $request->class_id;  
          $section_id  = $request->section_id;  
          $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id,$exam_id);

          if(!empty($examSchedule)){
            $data['msg'] = 'result found' ; 
            $data['Status'] = true ; 
            $data['examsList'] = $examSchedule ; 
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

public function prarent_attendance()
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



public function teacher_timetable()
{
  //echo "<pre>";
    $postdata =  $this->input->post('data');   
    // $postdata = '{"teacher_id":"14"}' ;   
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
          
          $teacher_id  = $request->teacher_id;  
          $days= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
          $teache_tt= $this->staff_model->getdetails_by_teacherID($teacher_id);
        
         	 foreach ($teache_tt as $key => $value) {
         		  if (($value->subject_id != 0 ) && ($value->class_id != 0) ) {
                  $days_arr[$days[$value->days]][]=$value;
              }
         	

         		}




            if(!empty($days_arr)){
            $data['msg'] = 'result found' ; 
            $data['Status'] = true ; 
            $data['TimeTableList'] = $days_arr ; 
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


public function parent_timetable()
{
    $postdata =  $this->input->post('data');  
      
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         { 
         	$student_id=$request->student_id;
         $student=$this->Api_model->get_student($student_id);
          $class_id  = $student->class_id;  
          $section_id  = $student->section_id; 
          $days= $this->db->get('days')->result_array();
 		 $period=$this->db->get('periods')->result_array(); 

          $TimeTableList=array();
 foreach($days as $days_key=>$days_value)
 {
    foreach($period as $period_value) 
    {
         $TimeTableList[$days_value['value']][] = $this->staff_model->getdetails_by_classID11($class_id, $section_id ,$days_value['id'],$period_value['id']);
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

 public function admin_login() {
      $postdata =  $this->input->post('data'); 
     if ($postdata != '')
                      {   
                    $request = json_decode($postdata);
                  
                    $data = array(
                    'username' => $request->username,
                    'password' => $request->pass,
                    'roles' => $request->role
                    );
                  //  print_r($data); die;
                $row = $this->Api_model->software_login($data);
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

public function admin_events(){
    $postdata =  $this->input->post('data');    

   if ($postdata != '')
      {  
       $request = json_decode($postdata);
       if(!empty($request))
        {                        
                            $today = $request->date;
                            $today .= " 00:00:00" ;            
                                $events = $this->Api_Classteacher_model->get_data_by_query("SELECT *, start_date as startdate ,date_format(start_date  , '%d/%m/%Y') as start_date , date_format(end_date  , '%d/%m/%Y') as end_date  FROM events where start_date  >=  '$today' order by startdate asc ");         
                                if ($events) {
                                            $data['records'] = $events;                       
                                            $data['msg'] = "success result found";  
                                            $data['status'] =true;
                                       }
                                else{
                                    $data['records'] = " ";                       
                                            $data['msg'] = "No result found";  
                                            $data['status'] = false;
                                    }
                            }else{
                                $data['msg'] = 'no Post data found 1' ; 
                                $data['Status'] =false ; 
                            }       
                            }else{
                                $data['msg'] = 'no Post data found 21' ; 
                                $data['Status'] = false ; 
                            }                    
                            echo json_encode($data); 
      }  

public function admin_homework(){
        $postdata =  $this->input->post('data');    
        if ($postdata != '')
           {  
            $request = json_decode($postdata);
            if(!empty($request))
             {  
                 $class_id = $request->class_id; 
                 $section_id = $request->section_id; 
                 $date = $request->date; 
                 if($date == '0000-00-00'){
                   
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' group by message ORDER BY id  DESC limit 15"); 
               }else{
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' and created_at like '%$date%' group by message ORDER BY id  DESC"); 
                 }

                  if(!empty($hw)){
                $data['msg'] = 'Data found' ; 
                $data['Status'] = true ; 
                $data['homeworkList'] = $hw ; 
             }else{
                $data['msg'] = 'Data not found' ; 
                $data['Status'] = false ; 
                }

             }else{
                $data['msg'] = 'no Post data found' ; 
                $data['Status'] = false ; 
             }
             }else{
                $data['msg'] = 'no Post data found' ; 
                $data['Status'] = false ; 
             }       
    
         echo json_encode($data); 
   }

public function admin_stuattendence(){
    
    $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $class = $request->class_id ;
    $section = $request->section_id ;
	//$teacher_id=$request->teacher_id ;
    $date = $request->date ;
   //$date = date('y-m-d');
  
  //$isClassTeacher=$this->Api_Classteacher_model->check_classTeacher($class, $section, $teacher_id,$this->current_session);
	if(1){
  
  
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
}else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Not a Class Teacher";
       }   
} 
echo json_encode($outputarr);  
}


 public function admin_notice(){
    $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
         {  
             $class_id = $request->class_id; 
             $section_id = $request->section_id;
             $date = $request->date; 
             if($date == '0000-00-00'){
             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Complain' group by message ORDER BY id  DESC  limit 15"); 
             }else{
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT * FROM messages where class_id = $class_id and section_id = $section_id and title = 'Complain' and created_at like '%$date%' group by message ORDER BY id  DESC"); 
              }
             if(!empty($hw)){
                $data['msg'] = 'Data found' ; 
                $data['Status'] = true ; 
                $data['homeworkList'] = $hw ; 
             }else{
                $data['msg'] = 'Data not found' ; 
                $data['Status'] = false ; 
                }
            

         }else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }
         }else{
            $data['msg'] = 'no Post data found' ; 
            $data['Status'] = false ; 
         }       

     echo json_encode($data); 
}

//edit teachers  profile  starts here 
public function Edit_teacherProfile()
{
 $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          {   
               $array['name'] = $request->name ;
               $array['surname'] = $request->surname ;
               $array['father_name'] = $request->father_name ;
               $array['mother_name'] = $request->mother_name ;
               $array['contact_no'] = $request->contact_no ;
               $array['email'] = $request->email ;
               $array['marital_status'] = $request->marital_status ;
               $array['local_address'] = $request->local_addresslocal_address ;
               $array['permanent_address'] = $request->permanent_address;
               
               if ($request->id ){
               $this->db->where('id',$request->id );
               $this->db->update('staff',$array);
                $data['msg'] = 'Data Updated Successfully' ; 
                $data['Status'] = true ;
                      } 
                       else{
                        $data['msg'] = 'Post id not found' ; 
                        $data['Status'] = false ;                
                      }
             }
        else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
            }
       }
         else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
             }
       echo json_encode($data); 
}


public function Change_teacherpassword()
{
 $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          {   
           $password =  $this->enc_lib->passHashEnc($request->password);
           $array['password'] = $password;
               if ($request->id ){
                $this->db->where('id',$request->id ) ;
                $this->db->update('staff',$array);
                $data['msg'] = 'Data Updated Successfully' ; 
                $data['Status'] = true ;
                      } 
                       else{
                        $data['msg'] = 'Post id not found' ; 
                        $data['Status'] = false ;                
                      }
                }
        else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
            }
       }
         else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
             }
       echo json_encode($data); 
}



public function upload_Profileimage()
{
 $postdata = $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          {   
           if(!empty($request->image))
                           { 
                            $base = $request->image;
                            $file_name=$request->id;
                            $file_name.=date("Ymdhis"); 
                            // Decode Image
                            $binary=base64_decode($base);
                           
                           // $binary =imagejpeg($binary, NULL, 50);
                            $file = fopen('uploads/staff_images'.$file_name.'.jpg', 'wb');
                            // Create File
                            fwrite($file, $binary);
                            fclose($file);
                            $path = $file_name.'.jpg';

                            $array['image'] = $path;
                            if ($request->id > 1 ){
                            $this->db->where('id',$request->id );
                            $this->db->update('staff',$array);
                            $data['msg'] = 'Data Updated Successfully' ; 
                            $data['Status'] = true ;
                                  }  
                                  else{
                                    $data['msg'] = 'Post id not found' ; 
                                    $data['Status'] = false ;                
                              }
                           } else{
                              $data['msg'] = 'Post image not found' ; 
                              $data['Status'] = false ;                
                            }

                           
                
                      
            }
        else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
            }
       }
         else{
              $data['msg'] = 'no Post data found' ; 
              $data['Status'] = false ; 
             }
       echo json_encode($data); 
}
//proxy notification
public function proxy_notification()
{
   $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
            $contact_no = $request->contact_no ; 
            $proxy_notification  = $this->db->query("SELECT * from Proxy_notification where contact_no = $contact_no order by id desc limit 6")->result();
            if (!empty($proxy_notification)) {
                $data['msg'] = 'Data found' ; 
                $data['Status'] = true ; 
                $data['proxy_notification'] = $proxy_notification ; 
            }else{
               $data['msg'] = 'Notifications UnAvailable' ; 
               $data['Status'] = false ; 
            }
               
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
 
//complaints

public function complaintList()
{
     $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          { 
            $teacher_id = $request->id; 
            $class_id = $this->db->query("SELECT class_id from class_teacher_2 where staff_id = $teacher_id")->row()->class_id;
            $result1 = $this->db->query("SELECT parent_teacher_message.*, students.firstname, students.lastname, classes.class, sections.section ,student_session.class_id from parent_teacher_message join students on parent_teacher_message.student_id = students.id join student_session on parent_teacher_message.student_id = student_session.student_id join classes on student_session.class_id = classes.id join sections on sections.id = student_session.section_id where status = 1 and student_session.class_id = $class_id ")->result();
            
            $result0 = $this->db->query("SELECT parent_teacher_message.*, students.firstname, students.lastname, classes.class, sections.section from parent_teacher_message join students on parent_teacher_message.student_id = students.id join student_session on parent_teacher_message.student_id = student_session.student_id join classes on student_session.class_id = classes.id join sections on sections.id = student_session.section_id   where status = 0 and student_session.class_id = $class_id")->result();
            if (!empty($result0)) { $data['pending'] = $result0;}
            else{  $data['pending'] = [] ; }

            if (!empty($result1)) { $data['solved'] = $result1 ; } 
            else{ $data['solved'] = [] ; } 
              $data['status'] = true;  
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


// appointment apis 
  public function Appointments()
 {
  
      $result['pending'] = $this->db->query("SELECT * form appointments where status = 0 ")->result(); 
      $result['approved'] = $this->db->query("SELECT * form appointments where status = 1 ")->result(); 
      $result['cancel'] = $this->db->query("SELECT * form appointments where status = 2 ")->result(); 
      $data['result'] = $result ; 
      $data['msg'] = "Appointment List Found " ;  
      $data['status'] = true;
      
       echo json_encode($data);  
 }


public function approvecancel_appointment()
{
    $postdata = file_get_contents("php://input");  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
            $id = $request->id ;
            $status = $request->status;
            $ary['status'] = $status ;
            $this->db->where('id',$id); 
            $this->db->update('appointments',$ary);
            $data['msg'] = "Appointment approved successfully" ;  
            $data['status'] = true;
          }
          else{
              $data['msg'] = "No result found";  
              $data['status'] = false;
             }
        }
        else{
             $data['msg'] = "No postdata found";  
             $data['status'] = false;
            }    
       echo json_encode($data); 
}
//appointment apis ends here 


public function check()
{
   $device_token=  'fWRC-YDss7A:APA91bFhidKxYgkKhpQB7ljSX1CPK5lHCAY4r2a36Ir0WeXB2mdWdVbObtFoK8zmIACR-hRAFuXgJFQPmOB4C8u9amjp8_uiTw87scRAOAtJtCVk38I2wgmbzAE-zapH_npYoFXJoSC-';
   $message = "helloo pranav";
        if($device_token!=''){
          $top['to'] = $device_token ;
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Homework', 'is_background' => false, 'message' => $message, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);
          print_r($result); 
           }

}



 public function appupdates()
 {
     $postdata = file_get_contents("php://input"); 
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
          {
        $details['staff_id'] = $request->staff_id;
        $details['datetime'] =$request->datetime;//date('Y-m-d h:i:s');         
        $details['module'] = $request->module;
        $details['type'] =  'staff';
        $detail['datetime'] = $request->datetime;//date('Y-m-d h:i:s');

        if (empty($details['staff_id'])) {
                 $outputarray['status'] = false;
             $outputarray['msg'] = "staff_id not Found"; 
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

        $check = $this->db->query("SELECT id from appdetails where staff_id = '".$details['staff_id']."' and module = '".$details['module']."' ")->row();
        
        if ($check) 
        {
          $this->db->where('module',$request->module);
          $this->db->where('staff_id',$request->staff_id);
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




public function gallery_group($value='')
{
   $gal_data = $this->db->query("SELECT * from front_cms_programs where type = 'gallery' ")->result(); 
 /*  $outputarray['gallery_album'] = $gal_data;
   $outputarray['status'] = true;
   $outputarray['msg'] = "Data Found";*/
   if (!empty($gal_data)) {
    echo json_encode($gal_data); 
   }else{
      $outputarray['gallery_album'] = $gal_data;
      $outputarray['status'] = true;
      $outputarray['msg'] = "Data Found";
     echo json_encode($outputarray); 
   }
 
}



public function add_group($value='')
{
  $postdata = file_get_contents("php://input");   
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request->title) && !empty($request->description))
          {
             $data['type'] ='gallery'; 
             $data['slug'] = $request->title ; 
             $data['title'] = $request->title ; 
             $data['description'] = $request->description ; 
             $this->db->insert('front_cms_programs',$data);
             $outputarray['status'] = true;
             $outputarray['msg'] = "group Added successfully";
          }else{
                $outputarray['Status'] = false;
                $outputarray['msg'] = "Request Data not Found";
          }
       }
   else{
               $outputarray['Status'] = false;
               $outputarray['msg'] = " Post Data not Found";
       }
         echo json_encode($outputarray); 
}


public function gallery_upload()
{
       $postdata = file_get_contents("php://input"); 
       // $album_id =  $this->input->post('album_id'); 
      
        if(!empty($postdata))
          {
              $request = json_decode($postdata);
              $album_id =  $request->album_id; 
            if(!empty($album_id))
              {
                $files = $request->files ; 
                $file_type = 'jpg'; 
                if (!empty($files)) {   
                $count =  0 ;
                   foreach ($files as $key => $filee) {

                              $base = $filee;                          
                              $file_name ='img'.date('Ymdhis').$count.'.'.$file_type; 
                              $binary=base64_decode($base);
                              $file = fopen('uploads/gallery/media/'.$file_name, 'wb');
                              fwrite($file, $binary);
                              fclose($file);
                              $path = 'uploads/gallery/'.$file_name;                            
                           
                        $imagedata['thumb_path'] = 'uploads/gallery/media/thumb/';
                        $imagedata['dir_path'] = 'uploads/gallery/media/';
                        $imagedata['img_name'] = $file_name ; 
                        $imagedata['thumb_name'] = $file_name ;
                        $imagedata['file_type'] = $file_type;
                        $this->db->insert('front_cms_media_gallery', $imagedata );
                        $inserted_id  = $this->db->insert_id();
                       if ($inserted_id) {
                         $album_id = $album_id;
                          $imagealbumdata['program_id'] =  $album_id ;
                          $imagealbumdata['media_gallery_id'] =  $inserted_id ;
                          $this->db->insert('front_cms_program_photos', $imagealbumdata );
                         $outputarray['Status'] = true;
                         $outputarray['msg'] = "Image Added successfully";
                       }else{
                         $outputarray['Status'] = false;
                         $outputarray['msg'] = "image album not inserted";
                       }
                       $count++; 
                  }//end foreach

 
                  }else{
                     $outputarray['Status'] = false;
                     $outputarray['msg'] = "image not Found";
                  }


              }else{
                  $outputarray['status'] = false;
                  $outputarray['msg'] = "request data not found";
              }

           }else{
             $outputarray['status'] = false;
             $outputarray['msg'] = "post data not found";
       }

   echo json_encode($outputarray); 
}


public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AIzaSyCT-A54uBY2jeKTEg0rkkfKzA8GLcPFUso';//AIzaSyCG-4lOV1y7TwkZiOIan58iNRbQtQjxqaM';//'AIzaSyCO04Q-DB0SX5JXrsz3gqj4oFAbv3m8MwQ';
//this key2 is a legacy key so always use legacy key not a 
//$FIREBASE_API_KEY_TWO = 'AIzaSyD4dJqnFRb-5ka75wH3HKdNcd7XEm7jLc0';
// Set POST variables
$url = 'https://fcm.googleapis.com/fcm/send'; 
$headers = array(
'Authorization: key=' . $FIREBASE_API_KEY_TWO,
'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

// echo json_encode($fields);
// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);

curl_close($ch);

return $result;
}

// ---------------------------------end class-----------------------------//
}



?>

