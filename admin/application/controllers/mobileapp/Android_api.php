<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use Aws\MediaConvert\MediaConvertClient;  
    use Aws\Exception\AwsException;
    use Aws\ElasticTranscoder\ElasticTranscoderClient;
    use Aws\Result;
class Android_api extends CI_Controller {
  private $config_aws;
  private $s3obj;
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
        $this->roomPath = $this->setting_model->getroompath();
        $this->roomSecretKet = $this->setting_model->getroomsecretkey();
        $this->current_session = $this->setting_model->getCurrentSession();
        include APPPATH . 'third_party/vendor/autoload.php';
        include APPPATH . 'third_party/initialize.php';
        
  date_default_timezone_set('Asia/Kolkata');
    }

    

 

 public function school_urls()
 {
  $postdata = file_get_contents("php://input");  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
            $module = $request->module; 
            $urls = $this->db->query("SELECT * from school_urls where is_active = 1 and module = $module order by sequence asc")->result();
         $outputarr['Status'] = true; 
         $outputarr['Urls'] = $urls; 
         $outputarr['Msg'] = "data found";
      }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "Invalid request";
      }
  }else{
      $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Invalid post";
  }
   echo json_encode($outputarr);
 }

 public function staff_login() {
      $postdata =  $this->input->post('data');
     if ($postdata != '')
                   {  

                    $request = json_decode($postdata);
                  
                    $data = array(
                    'moblie' => $request->phone,
                    'password' => $request->pass,
                    //'roles' => $request->role,
                    );

                $row = $this->Api_model->app_login($data);

                if ($row != false) {
                  if (!empty($request->token)) {
                    $updatedata['device_token'] = $request->token ; 
                    $this->db->where('contact_no',$request->phone);
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
 

public function class_section_list()
{    
  $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $staff = $request->id ;
   }      
$result = $this->Api_model->get_class_section_list_new($staff);
//print_r($this->db->last_query());die;
foreach ($result as $key => $val) {
    $result2 = array();
   $section = $val['section_id'] ; 
   $class = $val['class_id'] ; 
      $result2['id'] = $val['id'] ;
      $result2['class_id'] = $val['class_id'] ;
      $result2['staff_id'] = $val['teacher_id'] ;
      $result2['section_id'] = $val['section_id'] ;
      $result2['class'] = $val['class'] ;
      $result2['section'] = $val['section'] ;
     $result2['Studentcount'] = count($this->student_model->searchByClassSection($class, $section));
     $result3[]= $result2;
}
 if (!empty($result)) {
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Success";
    $outputarr['Result'] = $result3 ;
} else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
}  
echo json_encode($outputarr);
}


public function teacherDetails(){
    $postdata =  $this->input->post('data');    
    if ($postdata != '') {  
        $request = json_decode($postdata);
        $id = $request->id ;
      
        $result = $this->Api_model->getProfile($id);
      
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
     echo json_encode($outputarr);
    }

public function updatePassword()
{ 
    $postdata =  $this->input->post('data');    
    if ($postdata != '') {  
        $request = json_decode($postdata);
        $oldpw = $request->oldpw;
        $newpw = $request->newpw;
        $hashpw = $request->hashpw;
        $id = $request->id;
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
echo json_encode($outputarr);
}
public function changePassword()
{ 
  $oldPassword= $this->input->post('oldPassword') ;
  $newPassword= $this->input->post('newPassword') ;
  $confirmPassword= $this->input->post('confirmPassword') ;
  $id= $this->input->post('id') ;
  if($id > 0){
        if ($confirmPassword == $newPassword) {
            $updatedata['pass_code'] = $newPassword;
            $this->db->where("id",$id)->where("pass_code",$oldPassword)->update("staff", $updatedata);
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
echo json_encode($outputarr);
}

public function studentList(){    
    $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $class = $request->class ;
    $section = $request->section ;
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
    } 

    echo json_encode($outputarr);
}


public function studentList_att(){
    
    $postdata =  $this->input->post('data'); 
   
  if ($postdata != '') {   

    $request = json_decode($postdata);
	 $class = $request->class ;
    $section = $request->section ;
	$teacher_id=$request->teacher_id ;
	$isClassTeacher=$this->Api_Classteacher_model->check_classTeacher($class, $section, $teacher_id);
	if($isClassTeacher){
   
    $studentlist  = $this->student_model->searchByClassSection($class, $section);
    if (!empty($result)) {
        $outputarr['Status'] = true; 
        $outputarr['Msg'] = "Success";
        $outputarr['Result'] = $studentlist ;
    } 
    else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
       } 
	}  else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Not a Class Teacher";
       }    
    } 

    echo json_encode($outputarr);
}


public function sendHomework()
{     
    
  $class =  $this->input->post('class');    
  $section =  $this->input->post('section');    
  $message =  $this->input->post('message');    
  $teacher_id =  $this->input->post('teacher_id');    
  $subject_id =  $this->input->post('subject_id');    
  $subject_name =  $this->input->post('subject_name');    
  $title =  $this->input->post('title');    
if ($_FILES["File"]["name"]) {    
  $fileInfo = pathinfo($_FILES["File"]["name"]);
  $img_name ='homework'.date('Ymdhis'). '.' . $fileInfo['extension'];
  move_uploaded_file($_FILES["File"]["tmp_name"], "./uploads/attachments/" . $img_name);   
 $img_path =  'uploads/attachments/'.$img_name ;
  } else{
    $img_path = '' ;      
  }        
  $studentlist  = $this->student_model->searchByClassSection($class, $section);
  if(!empty($studentlist) ){
   foreach($studentlist as $value){  
    if($value['guardian_phone']){   
             $data1 = array(
            'is_class' => 1,
            'title' => $title,
            'subject_id'=>$subject_id,
            'subject_name'=>$subject_name,
            'type' => 3,
            'message' => $message,
            'send_mail' => 0,
            'send_sms' => 'Homework',
            'user_list' => $value['guardian_phone'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $this->current_session,
            'sms_status' => 1,
            'department' =>'',
            'msg_type' =>0,
            'other_number'=>'',            
            'student_session_id' =>$value['student_session_id'] ,
            'teacher_id'=>$teacher_id,
            'path'=>$img_path
      );
        $msgId = $this->messages_model->add($data1); 
        $device_token= $value['device_token'];
        $student_id = $value['id'];
        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        
      if($device_token!=''){        
        $top['to'] = $device_token ;
                $message1 = "Homework From School";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// Homework', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
       }
      }
     }
    $outputarr['Status'] =true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
}else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
      }  
      echo json_encode($outputarr);  
}


public function sendComplain()
{     
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section');    
    $message =  $this->input->post('message');    
    $teacher_id =  $this->input->post('teacher_id');   
    $studentlist  = json_decode($this->input->post('s_list')); 
    $title =  $this->input->post('title');  
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

    if ($_FILES["File"]["name"]) {
    $fileInfo = pathinfo($_FILES["File"]["name"]);
    $img_name ='notice'.date('Ymdhis'). '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["File"]["tmp_name"], "./uploads/attachments/" . $img_name);
      $img_path =  'uploads/attachments/'.$img_name ;
    } else{
      $img_path = '' ;      
    }  
    foreach($studentlist as $value){
      $student_detail = $this->student_model->get($value); 
      if($student_detail['guardian_phone']){
      $data1 = array(
     'is_class' => 1,
     'title' => $title,
     'subject_id'=>0,
     'subject_name'=>'',
     'type' => 4,
     'message' => $message,
     'send_mail' => 0,
     'send_sms' => 'Notice',
     'user_list' => $student_detail['guardian_phone'],
     'class_id' => $class,
     'section_id' => $section,
     'session_id' => $this->current_session,
     'sms_status' => 1,
     'department' =>'',
     'msg_type' =>0,
     'other_number'=>'',
     'student_session_id'=> $student_detail['student_session_id'],
     'teacher_id'=> $teacher_id,
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
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy/ / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);
        }
      } 
        if($device_token!=''){
               
          $top['to'] = $device_token ;
                    $message1 = "Notice From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy/ / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);
          
        }     
      }   
    }      
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
    echo json_encode($outputarr);  
}


public function studymaterial(){
  $postdata =  $this->input->post('data');
/*   echo file_put_contents('ab.txt',$postdata);    
  die('pranav'); */
  if ($postdata != '')
  {  
    $request = json_decode($postdata);
    if(!empty($request))
    {  
      $class_id = $request->class_id; 
      $section_id = $request->section_id; 				 
      $teacher_id = $request->teacher_id;
      $url = base_url();
      $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,CONCAT('$url',messages.path)  as filepath FROM messages where class_id = $class_id and section_id = $section_id and type=5 and teacher_id='$teacher_id' group by message ORDER BY id  DESC limit 100");       /* if($date == '0000-00-00'){
     $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,CONCAT('$url',messages.path)  as filepath FROM messages where class_id = $class_id and section_id = $section_id and send_sms = 'studymater' and teacher_id=$teacher_id group by message ORDER BY id  DESC limit 40"); 
      }else{
        $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*,DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p')as created_at,CONCAT('$url',messages.path)  as filepath FROM messages where class_id = $class_id and section_id = $section_id and send_sms = 'studymater' and teacher_id=$teacher_id and created_at like '%$date%' group by message ORDER BY id  DESC"); 
      } */

      if(!empty($hw)){
        $data['msg'] = 'Data found' ; 
        $data['Status'] = true ; 
        $data['StudyMaterial'] = $hw ; 
      }else{
        $data['msg'] = 'No Studymaterial found'; 
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

public function stuattendence(){
    
    $postdata =  $this->input->post('data'); 
   // $postdata = '{"class":"29","section":"17","teacher_id":"134"}';
    $outputarr = array();   
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $class = $request->class ;
    $section = $request->section ;
	  $teacher_id=$request->teacher_id ;
    $date = date('Y-m-d',strtotime($request->date)) ;
   // $date = date('Y-m-d');
  
  $isClassTeacher=$this->Api_Classteacher_model->check_classTeacher($class, $section, $teacher_id,$this->current_session);
	if($isClassTeacher){    
  $result = $this->Api_model->searchAttendenceClassSectionPrepare_api($class, $section, $date);
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
  else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "Post Data Not Found";
       } 
echo json_encode($outputarr);  
}

public function stuattendence_save()
{   
  $postdata =  $this->input->post('data');  
    if ($postdata != '') {   
    $request = json_decode($postdata);
    foreach ($request as  $value) {
       $ssessionid = 0 ;
      if ( $value->attendence_id !=0) {
        $ssessionid = $value->student_session_id;
        $arr = array(
          'id' => $value->attendence_id,
          'student_session_id' => $value->student_session_id,
          'attendence_type_id' => $value->attendence_type_id,
          'remark' =>'' ,
          'date' => date('Y-m-d',strtotime($value->date)),
        );
      }
      else{
        $arr = array(                
          'student_session_id' => $value->student_session_id,
          'attendence_type_id' => $value->attendence_type_id,
          'remark' =>'' ,
          'date' =>  date('Y-m-d',strtotime($value->date)),
        );           
      }
      $insert_id = $this->stuattendence_model->add($arr);
      $attendn = $this->db->query("SELECT type from attendence_type where id = '".$value->attendence_type_id."'")->row()->type; 
   $device_tokens= array();

   $device_token= $this->db->query("SELECT students.device_token, students.* from students join student_session on students.id = student_session.student_id where student_session.id = $ssessionid")->row();
     
      $student_id = $device_token->id;
        if ( $student_id != '') {
          $device_tokens = $this->db->query("SELECT * from devicetoken where student_id = $student_id")->result();     
        }
     
      if($device_token !=''){
        $top['to'] = $device_token->device_token ;
        $stuname = $device_token->firstname.' '.$device_token->lastname ;
        $message1 = $device_token->firstname.' '.$device_token->lastname." is ".$attendn." on ".$todate." || J P Academy";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d H:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
      }
 
if (!empty($device_tokens)) { 
      foreach ($device_tokens as $key => $value) {
        $top['to'] = $value->device_token ;
        $message1 = $stuname." is ".$attendn." on ".$todate." || J P Academy";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy/ / Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d H:i:s')));
        $result=   $this->sendPushNotificationTwo($top); 
      }  
}
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Attendance Saved Successfully";               
    } 
     
  } 
  else { 
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Record Found";
  }  
  echo json_encode($outputarr);
}


public function gatepass_list($value='')
{
  $postdata = file_get_contents("php://input");
  //$postdata =  $this->input->post('data'); 
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

public function gatepasspending()
{
  $postdata = file_get_contents("php://input");
  if ($postdata != '') {   

    $request = json_decode($postdata);
    $date = $request->date ;
    $result = $this->db->query()->result();
if (!empty($result)) {
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "Success";
    $outputarr['Result'] = $result ;
} else { 
    $outputarr['Status'] = 0; 
    $outputarr['Msg'] = "No Record Found";
} 
  }else{
  		 $outputarr['Status'] = 0; 
          $outputarr['Msg'] = "No Postdata Found";
  }      

 
echo json_encode($outputarr);
}

 public function get_student_gp()
 {
    $postdata = file_get_contents("php://input");
    //$postdata = $this->input->post('data');
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
      $url = "";// "http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username=casjbp&password=password&sender=CASJBP&to=$phone&message=$msg&reqid=1&format={json|text}&route_id=113";
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



    
  public function ClassList()
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
     $postdata =  $this->input->post('data');    
   if ($postdata != '')
      {  
       $request = json_decode($postdata);
       if(!empty($request))
        {  
        $class_id = $request->class_id  ; 
         $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
            sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class_id."' order by sections.section ";
            $sections = $this->db->query($qry)->result_array();
            if(!empty($sections)){
            $result['sections'] =  $sections;
            $result['Status'] =  1 ; 
            $result['msg'] = 'Section found' ; 
            }else{
              $result['msg'] = 'Section not found' ; 
              $result['Status'] = 0 ;
            }
          } else{
            $result['msg'] = 'Section not found' ; 
            $result['Status'] = 0 ; 
        }
    }else{
        $result['msg'] = 'Section not found' ; 
        $result['Status'] = 0 ; 
    }
    echo json_encode($result);    
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
                                $events = $this->Api_Classteacher_model->get_data_by_query("SELECT *, start_date as startdate ,date_format(start_date  , '%d/%m/%Y') as start_date , date_format(end_date  , '%d/%m/%Y') as end_date  FROM events where start_date  >=  '$today' order by startdate desc ");         
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
                 $url = base_url();
                 if($date == '0000-00-00'){                   
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  messages.*, DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p') as created_at,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and send_sms = 'Homework' and teacher_id=$teacher_id GROUP by message ORDER BY id DESC limit 40"); 
               }else{
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  messages.*, DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p') as created_at,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and send_sms = 'Homework' and teacher_id=$teacher_id and created_at like '%$date%' GROUP by message ORDER BY id  DESC"); 
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
             $url = base_url();
             if($date == '0000-00-00'){
              $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*, DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p') as created_at,CONCAT('$url',messages.path)  as filepath FROM messages where class_id = $class_id and section_id = $section_id and  (send_sms = 'Complain' or send_sms ='Notice') and teacher_id=$teacher_id group by message ORDER BY id  DESC  limit 40" ); 
             }else{
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT messages.*, DATE_FORMAT(messages.created_at, '%d-%m-%Y %h:%i %p') as created_at,CONCAT('$url',messages.path)  as filepath FROM messages where class_id = $class_id and section_id = $section_id and (send_sms = 'Complain' or send_sms ='Notice') and teacher_id=$teacher_id and created_at like '%$date%' group by message ORDER BY id  DESC"); 
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
    $postdata =  $this->input->post('data');    
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



public function teacher_timetable()
{
  //echo "<pre>";
    $postdata =  $this->input->post('data');   
     //$postdata = '{"teacher_id":"189"}' ;   
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
     //$postdata = '{"student_id":"42"}' ;   
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
             {   $url = base_url();
                 $class_id = $request->class_id; 
                 $section_id = $request->section_id; 
                 $date = $request->date; 
                 if($date == '0000-00-00'){
                   
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  *,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' group by message ORDER BY id  DESC limit 15"); 
               }else{
                    $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  *,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and title = 'Homework' and created_at like '%$date%' group by message ORDER BY id  DESC"); 
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
  
  
  $result = $this->Api_model->searchAttendenceClassSectionPrepare_api($class, $section, date('Y-m-d', strtotime($date)));
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
         {  $url = base_url();
             $class_id = $request->class_id; 
             $section_id = $request->section_id;
             $date = $request->date; 
             if($date == '0000-00-00'){
             $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT *,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and title = 'Complain' group by message ORDER BY id  DESC  limit 15"); 
             }else{
                $hw = $this->Api_Classteacher_model->get_data_by_query("SELECT  *,CONCAT('$url',messages.path) as filepath FROM messages where class_id = $class_id and section_id = $section_id and title = 'Complain' and created_at like '%$date%' group by message ORDER BY id  DESC"); 
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
 $postdata =  $this->input->post('data');    
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
                            header('Content-Type: bitmap; charset=utf-8');
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
   $postdata =  $this->input->post('data');  
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






 public function appupdates()
 {
   $postdata =  $this->input->post('data');    
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
   $outputarray['gallery_album'] = $gal_data;
   $outputarray['status'] = true;
   $outputarray['msg'] = "Data Found";
   echo json_encode($outputarray); 
}


public function add_group($value='')
{
  $postdata =  $this->input->post('data');    
    if ($postdata != '')
       {  
        $request = json_decode($postdata);
        if(!empty($request))
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
        $album_id =  $this->input->post('album_id'); 
        if(!empty($album_id))
          {
                if ($_FILES["File"]["name"]) {                  
                  $fileInfo = pathinfo($_FILES["File"]["name"]); 
                  $img_name ='img'.date('Ymdhis'). '.' . $fileInfo['extension'];
                  move_uploaded_file($_FILES["File"]["tmp_name"], "uploads/gallery/media/" . $img_name);   
                  $img_path =  'uploads/gallery/media/'.$img_name ;
                  
                  $imagedata['thumb_path'] = 'uploads/gallery/media/thumb/';
                  $imagedata['dir_path'] = 'uploads/gallery/media/';
                  $imagedata['img_name'] = $img_name ; 
                  $imagedata['thumb_name'] = $img_name ;
                  $imagedata['file_type'] = '' ;
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
                  }else{
                     $outputarray['Status'] = false;
                     $outputarray['msg'] = "image not Found";
                  }

           }else{
             $outputarray['status'] = false;
             $outputarray['msg'] = "request data not found";
       }

   echo json_encode($outputarray); 
}

//download history
public function DownloadHistory()
{
  $postdata =  $this->input->post('data');  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
            $class_id = $request->class_id; 
            $section_id = $request->section_id;
            if (!empty($class_id)) {
                if (!empty($section_id)) {
                    $result = $this->db->query("SELECT students.*, classes.class, sections.section from students join student_session on
                     student_session.student_id = students.id join classes on classes.id = student_session.class_id
                      join sections on sections.id = student_session.section_id  where student_session.class_id = $class_id and 
                     student_session.section_id = $section_id and student_session.session_id = $this->current_session order by students.firstname")->result(); 
                     if (!empty($result)) {
                      $installed = 0;
                      $uninstalled = 0;
                      $totalstudents = 0;
                      foreach ($result as $key => $value) {
                        if ($value->device_token != '') {
                           $value->app_installed = "Yes" ;
                           $value->app_seen = 'Last Seen on - '.$value->app_seen_date ;
                           $installed++ ;
                        }else{
                           $value->app_installed = "No" ;
                           $value->app_seen = '--';
                            $uninstalled++;
                           }    
                           $totalstudents++;                  
                          }  
                        $percent = ($installed /$totalstudents)*100;
                        $data['result'] =  $result;  
                        $data['installed'] =  round($percent);  
                        $data['notinstall'] =   round($percent-100);  
                        $data['msg'] = " $installed Out Of $totalstudents J P Academy Parent App Downloads ";  
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


public function getstudentAddress()
{
    $postdata =  $this->input->post('data');  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
           $scholar_no = $request->scholar_no;
            if (!empty($scholar_no)){              
               $result = $this->db->query("SELECT * from gps_address where scholar_no =  $scholar_no")->row(); 
                     if (!empty($result)) {
                        $data['result'] =  $result;  
                        $data['msg'] = "Result Found";  
                        $data['status'] = true;
                     }else{
                       $data['msg'] = "No result  found";  
                       $data['status'] = false;
                     }      
              }else{
                 $data['msg'] = "No scholar no is found";  
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
/* class test module Start here*/

public function CreateTest()
{
     $insert['class_id'] = $this->input->post('class_id');
     $insert['section_id'] = $this->input->post('section_id'); 
     $insert['testname'] = $this->input->post('testname'); 
     $insert['subject_id'] = $this->input->post('subject_id'); 
     $insert['max_marks'] = $this->input->post('max_marks'); 
     $insert['passing_marks'] = $this->input->post('passing_marks'); 
     $insert['test_date'] = $this->input->post('test_date'); 
     $insert['session_id'] = $this->input->post('session_id'); 
     $insert['teacher_id'] = $this->input->post('teacher_id'); 
     $insert['status'] = 0; 
     $this->db->insert('class_test',$insert);
     if ($this->db->insert_id()) {
        $data['status'] = true;         
        $data['msg'] ='Test Created Successfully';
     }else{
        $data['status'] = false;         
        $data['msg'] ='Something went wrong. Please Try again later';
     }    
     echo json_encode($data);
}

public function TestList()
{    $class_id = $this->input->post('class_id');
     $section_id = $this->input->post('section_id'); 
     $testdata = $this->db->query("SELECT * from class_test where class_id = $class_id and section_id = $section_id ")->result();
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

public function TestMarks()
{   
     $class_id = $this->input->post('class_id');
     $test_id = $this->input->post('test_id');
     $section_id = $this->input->post('section_id'); 
     $testdata = $this->db->query("SELECT * from class_test where class_id = $class_id and section_id = $section_id and id = $test_id and status = 0")->row();

   if ($testdata) {
    $students = $this->Student_model->searchByClassSection($class_id,$section_id);
  $base = base_url();
    foreach ($students as $key => $student) {      
        $d['student_id'] = $student['id'];
        $d['rollno'] = $student['roll_no'];
        $d['student_name'] = $student['firstname'].' '.$student['lastname'];
        $d['student_image'] = $base.'/'.$student['image'];
        $d['student_session_id'] = $student['student_session_id'];
        $d['obt_marks'] = 0;
        $d['max_marks'] = $testdata->max_marks;
        $d['test_id'] = $testdata->id;
        $d['remark'] = '';
        $testdatas[] = $d;
    } 
        $data['result'] = $testdatas;         
        $data['status'] = true;         
        $data['msg'] ='Data Found';
     }else{
        $data['status'] = false;         
        $data['msg'] ='Marks Already added';
     }   
     echo json_encode($data);
}

public function TestMarksSave()
{   
     $exammarks = $this->input->post('exammarks');
     $test_id = $this->input->post('test_id');
    if ($exammarks) {
      foreach ($exammarks as $key => $exammark){
        $d['student_id'] = $exammark->student_id;
        $d['student_name'] = $exammark->student_name;
        $d['student_session_id'] = $exammark->student_session_id;
        $d['obt_marks'] = $exammark->obt_marks;
        $d['max_marks'] = $exammark->max_marks;
        $d['test_id'] = $exammark->test_id;     
        $d['remark'] = $exammark->remark;     
        $this->db->insert('class_test_marks', $d);   
       }     
        $this->db->where('id',$test_id);
        $this->db->update('class_test',array('status'=>1));  
        $testdata = $this->db->query("SELECT * from class_test_marks where id = $test_id")->result();   
        $data['result'] = $testdata;         
        $data['status'] = true;         
        $data['msg'] ='Data added Successfully';
     }else{
        $data['status'] = false;         
        $data['msg'] ='Something went wrong.Please try after sometime...';
     }   
     echo json_encode($data);
}

public function smileyBenifits()
{
 
    $postdata =  $this->input->post('data');    
    //$postdata =  '{"id":"14"}';    
     if ($postdata != '') {  
        $request = json_decode($postdata);
        $id = $request->id ;      
        $result = $this->Api_model->getProfile($id);
    //print_r($result);

        if ($result) {
            $smiley_count = $this->db->query("SELECT SUM(smiley_count) as totalsmileys from messages where teacher_id = '$id' ")->row()->totalsmileys;
            $lastsmileyupdate = $this->db->query("SELECT created_at from messages where teacher_id = '$id' order by id desc limit 1")->row()->created_at;
            $response = new stdClass();
            $response->smiley_count = $smiley_count;
            $response->name = $result['name'].' '.$result['surname'];
            $response->image = $result['image'];
            $response->text = "<h3>What are Smiley</h3><p>Smileys are redeemable points which you can use for multiple purposes. </p>";
            $response->last_update = $lastsmileyupdate;
            $outputarr['Status'] = true; 
            $outputarr['Msg'] = "Success";
            $outputarr['Result'] = $response ;
           
        }else{
            $outputarr['Status'] = false; 
            $outputarr['Msg'] = "No Record Found";
        }
    
     }else{
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Postdata  Found";
     }
     echo json_encode($outputarr);
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


//--------------------------- Video -----------------------------------------//

public function subjectlists()
{
  $postdata =  1;//$this->input->post('data');  
    if ($postdata != '')
       {     
        $request =  1;//json_decode($postdata);
         if(!empty($request))
          {             
            $data['subjects'] = $this->db->query("SELECT * from subjects")->result();
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


public function classSubject()
{
  $class_id =  $this->input->post('class_id');  
    if ($class_id)
       {          
        $result =  $this->db->query("SELECT teacher_timetable.subject_id, subjects.name from teacher_timetable 
        JOIN subjects on subjects.id = teacher_timetable.subject_id
        where teacher_timetable.class_id= $class_id and teacher_timetable.status = 1 group by teacher_timetable.subject_id ")->result();
    
         if(!empty($result))
          {             
            $data['subjects'] = 
            $data['msg'] = "Result found";  
            $data['status'] = true;
          }else{
            $data['msg'] = "No Result found";  
            $data['status'] = false;
         }
       }else{
            $data['msg'] = "No postdata found";  
            $data['status'] = false;
          }
        echo json_encode($data); 
}


public function addVideo()
{          
          $res['video_type'] = $this->input->post('video_type');          
          $res['subject_id'] = $this->input->post('subject_id'); 
          $res['topic'] = $this->input->post('topic'); 
          $res['teacher_id'] = $this->input->post('teacher_id'); 
          $res['tag'] = ($this->input->post('tag'))?$this->input->post('tag'):'tag';
          $res['class_id'] =   $this->input->post('class_id');        
          $sections =  json_decode($this->input->post('section_id'));        
          $res['name'] = $this->input->post('videoname'); 
          if($this->input->post('video_type'))            { 
              $filename = $_FILES["video"]["name"];          
              if (!empty($filename)) {
                //$temp = explode(".", $_FILES["video"]["name"]);
                $fileInfo = pathinfo($_FILES["video"]["name"]);               
                $newfilename = md5(uniqid()) . '.' . $fileInfo['extension'];
                $checkFileName = $this->db->select('*')->from('tvideo')->where('url',$newfilename)->get();
                if ($checkFileName->num_rows > 0) {
                  $newfilename = md5(uniqid()) . '.' . $fileInfo['extension'];
                }
                $s = $this->s3obj->putObject([ 
                      'Bucket' => $this->config_aws['s3-access']['bucket'], 
                      'Key' => 'jpa/upload/videos/'.$newfilename, # 'from_php_script' will be our folder on s3 (this would be automatically created) 
                      'Body' => fopen($_FILES['video']['tmp_name'], 'rb'), # reading the file in the 'binary' mode 
                      'ACL' => $this->config_aws['s3-access']['acl'] 
                ]);
                $res['url'] = $newfilename;
              }
            }else{
              $res['url'] = $this->input->post('url');;
            }
           
            foreach ($sections as  $section) {
             
               $res['section_id'] = $section ;
               $this->db->insert('tvideo',$res);
            }
        
          if($this->db->insert_id()){
            $data['msg'] = "Added Successfully";            
            $data['status'] = true;
          }else{
            $data['msg'] = "Failed to add data";  
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
            $topics = $this->db->query("SELECT * from topics where subject_id= '$subject_id' ")->result();
            if(!empty($topics)){
              $data['topicList'] = $topics;
              $data['msg'] = "Result found";  
              $data['status'] = true;
            }else{
              $data['msg'] = "No data found";  
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

public function addTopic()
{
  $postdata =  $this->input->post('data');  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          { 
            $res['subject_id'] = $request->subject_id;
          
            $res['name'] = $request->name;
            $res['status'] = 1; 
            $this->db->insert('topics',$res);
           
            $data['id'] = $this->db->insert_id();// $this->db->query("SELECT * from topics where subject_id= '".$res['subject_id']."'  ")->result(); 
            $data['msg'] = "Topic Added Successfuly";  
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



public function commentVideo()
{
    $postdata =  $this->input->post('data');  
    if ($postdata != '')
       {     
        $request = json_decode($postdata);
         if(!empty($request))
          {
            $res['video_id'] = $request->video_id;
            $res['student_id'] = 0;
            $res['teacher_id'] = $request->teacher_id;
            $res['message'] = $request->message;
            $res['message_by'] = 0 ;
           $this->db->insert('videomessage',$res);
             $id = $this->db->insert_id();
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

// modified
public function ListVideo()
{
   $postdata =  $this->input->post('data');  
   if ($postdata != '')
      {     
       $request = json_decode($postdata);
        if(!empty($request))
         { 
          $class_id = $request->class_id;
          $teacher_id = $request->teacher_id;
      
           $videos = $this->db->query("SELECT tvideo.id,tvideo.url,tvideo.subject_id,CONCAT(staff.name,' ' ,staff.surname) as teacherName,
           tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,
           tvideo.status,tvideo.view_count,
           DATE_FORMAT(tvideo.created_at, '%d-%m-%Y %h:%i %p') as createDate,tvideo.created_at,
            tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
            from tvideo left join topics on topics.id = tvideo.topic 
            left join staff on staff.id = tvideo.teacher_id
            left join subjects on subjects.id = tvideo.subject_id
            where tvideo.teacher_id = '$teacher_id' group by tvideo.url order by tvideo.id desc ")->result();
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
            $teacher_id = $request->teacher_id;
           $messages =  $this->db->query("SELECT videomessage.*,CONCAT(students.firstname,' ',students.lastname) as student_name,CONCAT('$url',students.image) as student_image,
            CONCAT(staff.name,' ',staff.surname) as teacher_name, CONCAT('$url',staff.image) as teacher_image  from videomessage
             left join students on students.id = videomessage.student_id  
             left join staff on staff.id = videomessage.teacher_id  
            where videomessage.video_id = '$video_id'  ")->result();

            $videos = $this->db->query("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,
            tvideo.view_count, DATE_FORMAT(tvideo.created_at, '%d-%m-%Y %h:%i %p') as created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
            from tvideo left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id 
            where tvideo.id= '$video_id' ")->row();

            if (!empty($videos)) {
                         
                $videos->like = $this->db->query("SELECT count(videolike.id) as likecount from videolike where video_id = '$videos->id' ")->row()->likecount;
                if($videos->video_type == 1)
                  $videos->url =  SCHOOLEYE_BUCKET_URL."/jpa/upload/videos/".$videos->url;
          
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


public function sendStudyMaterial()
{     
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section');    
    $desc =  $this->input->post('desc');   
    $teacher_id =  $this->input->post('teacher_id');    
    // $subject_id =  $this->input->post('subject_id');    
    // $subject_name =  $this->input->post('subject_name');    
    $title =  $this->input->post('title'); 
      
if ($_FILES["File"]["name"]) {    
    $fileInfo = pathinfo($_FILES["File"]["name"]);
    $img_name ='studymater'.date('Ymdhis'). '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["File"]["tmp_name"], "./uploads/attachments/" . $img_name);   
   $img_path =  'uploads/attachments/'.$img_name ;
    } else{
      $img_path = '' ;      
    }        
    $studentlist  = $this->student_model->searchByClassSection($class, $section);
    //print_r($studentlist); die;
    if(!empty($studentlist) ){
    foreach($studentlist as $value){     
      if ($value['guardian_phone']) {
              $data1 = array(
              'is_class' => 1,
              'title' => $title,
              'subject_id'=>0,
              'subject_name'=>'studymaterial',
              'type' => 5,
              'message' => $desc,
              'send_mail' => 0,
              'send_sms' => 'studymater',
              'user_list' => $value['guardian_phone'],
              'class_id' => $class,
              'section_id' => $section,
              'session_id' => $this->current_session,
              'sms_status' => 1,
              'department' =>'',
              'msg_type' =>0,
              'other_number'=>'',            
              'student_session_id' =>$value['student_session_id'] ,
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
                $message1 = " New Study Material Updated";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// studymaterial', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
        }
      }
      if($device_token!=''){        
        $top['to'] = $device_token ;
                $message1 =  "New Study Material Updated";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// studymaterial', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
       }
      }
     }
    $outputarr['Status'] =true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
}else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
      }  
      echo json_encode($outputarr);  
}


//modified n new
public function sendStudyMaterial_new()
{     
    $class =  $this->input->post('class');    
    $sections =  json_decode($this->input->post('section'));    
    $desc =  $this->input->post('desc');   
    $teacher_id =  $this->input->post('teacher_id');    
    $subject_id =  $this->input->post('subject_id');    
    $subject_name =  $this->input->post('subject_name');    
    $title =  $this->input->post('title'); 
      
if ($_FILES["File"]["name"]) {    
    $fileInfo = pathinfo($_FILES["File"]["name"]);
    $img_name ='studymater'.date('Ymdhis'). '.' . $fileInfo['extension'];
    move_uploaded_file($_FILES["File"]["tmp_name"], "./uploads/attachments/" . $img_name);   
   $img_path =  'uploads/attachments/'.$img_name ;
    } else{
      $img_path = '' ;      
    }  

    foreach ($sections as $key => $section) {
         
    $studentlist  = $this->student_model->searchByClassSection($class, $section);
    if(!empty($studentlist) ){
    foreach($studentlist as $value){   
      if($value['guardian_phone']){
               $data1 = array(
              'is_class' => 1,
              'title' => $title,
              'subject_id'=>$subject_id,
              'subject_name'=>$subject_name,
              'type' => 5,
              'message' => $desc,
              'send_mail' => 0,
              'send_sms' => 'studymater',
              'user_list' => $value['guardian_phone'],
              'class_id' => $class,
              'section_id' => $section,
              'session_id' => $this->current_session,
              'sms_status' => 1,
              'department' =>'',
              'msg_type' =>0,
              'other_number'=>'',            
              'student_session_id' =>$value['student_session_id'] ,
              'teacher_id'=>$teacher_id,
              'path'=>$img_path
        );
        $msgId = $this->messages_model->add($data1); 
        $device_token= $value['device_token'];
        $student_id = $value['id'];
        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        /* if (!empty($device_tokens)) {         
        foreach ($device_tokens as $key => $val) {
          $top['to'] = $val->device_token ;
                $message1 = " New Study Material Updated";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// studymaterial', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
        }
      } */
      if($device_token!=''){        
        $top['to'] = $device_token ;
                $message1 =  "New Study Material Updated";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy// studymaterial', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
       }
      }
     }
  
}
$outputarr['Status'] =true; 
$outputarr['Msg'] = "Messsage Send Successfully";
    } //SECTIONS LOOP END

/*  */ 
      echo json_encode($outputarr);  
}
public function sendmessage()
{     
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section');    
    $message =  $this->input->post('message');    
    $title =  $this->input->post('title');    
    $teacher_id =  $this->input->post('teacher_id');   
    $studentlist  = json_decode($this->input->post('s_list')); 
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
        $img_path = '' ;      
   
    foreach($studentlist as $value){
    $student_detail = $this->student_model->get($value); 
             $data1 = array(
            'is_class' => 1,
            'title' =>  $title,
            'message' => $message,
            'send_mail' => 0,
            'send_sms' => 'Message',
            'user_list' => $student_detail['guardian_phone'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $this->current_session,
            'sms_status' => 0,
            'department' =>'',
            'msg_type' =>0,
            'other_number'=>'',
            'student_session_id'=> $student_detail['student_session_id'],
            'teacher_id'=> $teacher_id,
            'path'=>$img_path,
        );
         $msgId = $this->messages_model->add($data1); 
         $device_token=$student_detail['device_token'];
         $student_id = $student_detail['id'];
         $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id ")->result();
       if (!empty($device_tokens)) {         
        foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token ;
                $message1 = "Message From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy/ / Message', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);
        }
      }
        if($device_token!=''){
               
          $top['to'] = $device_token ;
                    $message1 = "Message From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'J P Academy/ / Message', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
          $result=   $this->sendPushNotificationTwo($top);
          
        }        
    }      
    $outputarr['Status'] = true; 
    $outputarr['Msg'] = "Messsage Send Successfully";
    echo json_encode($outputarr);  
}

/* video conferencing apis */



public function createRegularRoom($teacher_id)
{    
    $session_id =  $this->current_session ;         
    $rand = rand();
    $teache_tt= $this->staff_model->getdetails_by_teacherID($teacher_id);  
if(!empty($teache_tt)){
 
 foreach ($teache_tt as $key => $teache_t) {
   $days = array('Sunday','Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
   $today = date('l');
if($days[$teache_t->days] == $today){
  if($teache_t->class_id> 0 && $teache_t->section_id > 0 && $teache_t->subject_id > 0){ 
  $name = $teache_t->period_id.$teache_t->subname.$teacher_id.$teache_t->subject_id.$rand;
  $chiper = md5($name); 
  //$url = "https://meet.helpeye.in/".$chiper;
    /* bbb start */ 
  
  /* bbb end */
  $date =date('d-m-Y');
 /*  $resdata = $this->db->query("SELECT id from classroom where classroom.teacher_id = '$teache_t->teacher_id' and classroom.lecture_id = '$teache_t->period_id' 
  and classroom.days = $teache_t->days and classroom.date = '$date' ")->row();    */
  $resdata = "";
  $this->db->select('id');
  $this->db->from('classroom');
  $this->db->where('teacher_id',$teache_t->teacher_id);
  $this->db->where('lecture_id',$teache_t->period_id);
  $this->db->where('days',$teache_t->days);
  $this->db->where('date',$date);
  $query = $this->db->get();

  if ( $query->num_rows() > 0 )
  {
    $resdata= $query->row();      
  }
  
    if(empty($resdata)){
      $url = $chiper; 
      $meetingName= "teacher";
      $meetingID=$chiper ;
      $mpassword= "Teachers@123";
      $apassword= "Students@123";
      $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
      //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
      $apipath = $this->roomPath."api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apipath);
      //curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $result = curl_exec($ch); 
      $studentlist  = $this->student_model->searchByClassSection_api($teache_t->class_id, $teache_t->section_id, $session_id);   
      $data2 = array(                        
       'class_id' => $teache_t->class_id,
       'section_id' => $teache_t->section_id,
       'session_id' => $session_id,
       'link' => $url,
       'title' => "Lecture - ".$teache_t->period_id.' - '.date('d-m-Y'),
       'description' =>  $teache_t->tname.'-'.$teache_t->subname,
       'classroom_active' =>0,
       'teacher_id'=>$teacher_id,
       'lecture_id'=> $teache_t->period_id,
       'days'=> $teache_t->days,
       'date'=> date('d-m-Y'),    
       'iscancel'=>0,
       'exceedTimeValue'=>15,
       'isvalid'=>1,
       'starttime'=>$teache_t->timefrom,
       'endTime'=>$teache_t->timeto,
       'startdate'=>date('d-m-Y'),
     );
     $this->db->insert('classroom',$data2); 
   //  print_r($this->db->last_query()); die;  
     $classcreated =  $this->db->insert_id();
    
     $classroomdata= array(    
       'link' => $url,
       'title' => $teache_t->subname.'-'.date('d-m-Y'),
       'classroom_id' =>$classcreated,
       'classroom_active' =>1     
     );
     $this->db->insert('classroomdata',$classroomdata);
    //  $senddata = array("title"=>$teache_t->subname.'-'.date('d-m-Y') , "url"=>$url);
    //  $burl = "http://schooleye.org/schooleye/mobileapi/Android_api/insertRoomcurl";
    //  $ch = curl_init();
    //  curl_setopt($ch, CURLOPT_URL, $burl);
    //  curl_setopt($ch, CURLOPT_POST, true);
    //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //  curl_setopt($ch, CURLOPT_POSTFIELDS, $senddata);
    //  $result = curl_exec($ch);
                             if(!empty($studentlist) && $classcreated > 0 ){
                             foreach($studentlist as $value){       
                                       $data1 = array(             
                                       'student_session_id' => $value['student_session_id'],
                                       'student_id' => $value['id'],
                                       'class_id' => $teache_t->class_id,
                                       'section_id' => $teache_t->section_id,
                                       'session_id' => $session_id,
                                       'link' => $url,
                                       'classroom_id' =>$classcreated,
                                       'joined'=>0,
                                       'is_student'=>1,
                                       'teacher_id'=>$teacher_id            
                                 );
                                 $msgId = $this->Api_model->addclassroom($data1); 
                                 $device_token= $value['device_token'];
                                 $student_id = $value['id'];
                                 if($device_token!=''){        
                                 $top['to'] = $device_token ;
                                 $message1 = "Join Active Class Room";
                                 $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
                                 $result=   $this->sendPushNotificationTwo($top);
                               }
                             /* End copy */
                           }
 
                                                            $success = 0;
                                                             if(!empty($sections)){                                                               
 
                                                                 $outputarrs['classroomdata'] =$classroomdata; 
                                                                 $outputarrs['classRoomId'] =$classcreated; 
                                                                 $outputarrs['Status'] =true; 
                                                                 $outputarrs['Msg'] = "Class Room Created Successfully";   
                                                               $success++;
                                                             }else { 
                                                               if($success > 0){
                                                                 $outputarrs['classroomdata'] =$classroomdata; 
                                                                 $outputarrs['classRoomId'] =$classcreated; 
                                                                 $outputarrs['Status'] =true; 
                                                                 $outputarrs['Msg'] = "Class Room Created Successfully";  
                                                               }else{
                                                                 $outputarrs['Status'] = false; 
                                                                 $outputarrs['Msg'] = "No Records Found";
                                                               }
                                                                 
                                                               }  
                           }
    }
  }
}
}//foreach teachertimetable
  }
 

     // echo json_encode($outputarrs);  
      
}

public function editclassroom()
{
  $id =  $this->input->post('id');
  $details['endTime'] =  $this->input->post('endTime');    
  $details['exceedTimeValue'] =  $this->input->post('exceedTimeValue');    
  $details['starttime'] =  $this->input->post('starttime');    
  $details['startdate'] =  $this->input->post('startdate');    
    
  if($id > 0 ){
    $this->db->where('id',$id);
    $this->db->update('classroom',$details);
    $outputarrs['Status'] = true; 
    $outputarrs['Msg'] = " Class room Updated";
  }
  else{
    $outputarrs['Status'] = false; 
    $outputarrs['Msg'] = "No Class room id Found";
  }
  echo json_encode($outputarrs);  
}

public function generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword)
{
  $string = sha1("createattendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName.$this->roomSecretKet );
  return $string ;
} 

 public function roomtest( )
 {
  $name =  $this->input->post('name');    
  $chiper = $this->input->post('chiper');    
  $meetingName= $name;
  $meetingID=$chiper ;
  $mpassword= "Teachers@123";
  $apassword= "Students@123";
   $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
  //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
   $apipath = $this->roomPath."api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $apipath);
//curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  echo $result = curl_exec($ch);
 }

public function createRoom()
{     
    $class =  $this->input->post('class');    
    $sections =  json_decode($this->input->post('section'));    
    $teacher_id =  $this->input->post('teacher_id');     
    $title =  $this->input->post('title');  
    $title = str_replace(' ', '_', $title);  
    $description =  $this->input->post('description'); 
    $startdate = $this->input->post('startdate');
    $starttime = $this->input->post('starttime');
    $endTime = $this->input->post('endTime');
    $exceedTimeValue = $this->input->post('exceedTimeValue');
    $session_id =  $this->current_session ;         
    $rand = rand();
    $name = $class.$teacher_id.$title.$rand."JPSCHOOLMERRUT".$rand;
    $chiper = md5($name);
    //$url = "https://meet.helpeye.in/".$chiper;   
    $url =  $chiper;   

   /* bbb start */
   $meetingName= $name;
   $meetingID=$chiper ;
   $mpassword= "Teachers@123";
   $apassword= "Students@123";
   $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
   //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
   $apipath = $this->roomPath."api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $apipath);
 //curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   $result = curl_exec($ch);
   /* bbb end */
    
 if(!empty($sections)){
  $success = 0;
foreach ($sections as $section) {
  $studentlist  = $this->student_model->searchByClassSection_api($class, $section, $session_id);   
    $data2 = array(                        
      'class_id' => $class,
      'section_id' => $section,
      'session_id' => $session_id,
      'link' => $url,
      'title' => $title,
      'description' => $description,
      'classroom_active' =>0,
      'teacher_id'=>$teacher_id,
      'iscancel'=>0,
      'startdate'=>date('d-m-Y',strtotime($startdate)),
      'starttime'=>$starttime,
      'endTime'=>$endTime,
      'exceedTimeValue'=>$exceedTimeValue,
      'isvalid'=>1,
      'date'=>date('d-m-Y',strtotime($startdate)),
    );
    $this->db->insert('classroom',$data2);   
    $classcreated =  $this->db->insert_id();
    $classroomdata= array(    
      'link' => $url,
      'title' => $title,
      'classroom_id' =>$classcreated,
      'classroom_active' =>1     
    );
    $this->db->insert('classroomdata',$classroomdata);
  /*   $senddata = array("title"=>$title , "url"=>$url);
    $burl = "http://schooleye.org/schooleye/mobileapi/Android_api/insertRoomcurl";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $burl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $senddata);
    $result = curl_exec($ch); */
  if(!empty($studentlist) && $classcreated > 0 ){
  foreach($studentlist as $value){       
             $data1 = array(             
            'student_session_id' => $value['student_session_id'],
            'student_id' => $value['id'],
            'class_id' => $class,
            'section_id' => $section,
            'session_id' => $session_id,
            'link' => $url,
            'classroom_id' =>$classcreated,
            'joined'=>0,
            'is_student'=>1,
            'teacher_id'=>$teacher_id            
      );
      $msgId = $this->Api_model->addclassroom($data1); 
      $device_token= $value['device_token'];
      $student_id = $value['id'];
      if($device_token!=''){        
      $top['to'] = $device_token ;
      $message1 = "Join Active Class Room";
      $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
      $result=   $this->sendPushNotificationTwo($top);
     }
       /*  $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        foreach ($device_tokens as $key => $val) {
        $top['to'] = $val->device_token ;
        $message1 = "Join Active Class Room";
        $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result= $this->sendPushNotificationTwo($top);
        } */
  }
     $outputarr['classroomdata'] =$classroomdata; 
     $outputarr['classRoomId'] =$classcreated; 
     $outputarr['Status'] =true; 
     $outputarr['Msg'] = "Class Room Created Successfully";   
   $success++;
}else { 
  if($success){
    $outputarr['classroomdata'] =$classroomdata; 
    $outputarr['classRoomId'] =$classcreated; 
    $outputarr['Status'] =true; 
    $outputarr['Msg'] = "Class Room Created Successfully";  
  }else{
    $outputarr['Status'] = false; 
    $outputarr['Msg'] = "No Records Found";
  }
     
     }  
}
}else{
  $outputarr['Status'] = false; 
  $outputarr['Msg'] = "No Sections Found";
}  
    echo json_encode($outputarr);       
}



 public function cancelClassRoom()
 {
  $classroomid =$this->input->post('roomId');    
  if(!empty($classroomid > 0 )){
       $this->db->where('id',$classroomid);
       $this->db->update('classroom',array('iscancel'=>1,'classroom_active'=>0,'isvalid'=>0));

       $outputarr['Status'] = true; 
       $outputarr['Msg'] = "Class Canceled Successfully";
    }else{
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No Sections Found";
    }  
        echo json_encode($outputarr); 
 }



 public function generatechecksumteacher($teachername,$meetingid,$password)
{
  $string = sha1("joinfullName=".$teachername."&meetingID=".$meetingid."&password=".$password."&redirect=true".$this->roomSecretKet);
  return $string ;
} 

 public function startClassRoom()
 {
  $classroomid =$this->input->post('roomId');    
  if(!empty($classroomid > 0 )){
        $currentclass = $this->db->query("SELECT classroom.* from classroom  where id = $classroomid ")->row();
        if(!empty($currentclass)){
         
          $this->todayteachertendance($currentclass->teacher_id);
          $activeclasses = $this->db->query("SELECT * from classroom where classroom.class_id =  '$currentclass->class_id' and classroom.section_id =  '$currentclass->section_id'
          and classroom.session_id  =  '$currentclass->session_id' and classroom.classroom_active  = 1 and classroom.date = '$currentclass->date' ")->row();
          //print_r($activeclasses);;die;
        //  if(empty($activeclasses)){
            $meetingid = $currentclass->link;
            $teachername = "teacher";
            $password = "Teachers@123";
            $checksum = $this->generatechecksumteacher($teachername,$meetingid,$password);             
            $outputarr['Status'] = true; 
            $outputarr['url'] =$urrrl = $this->roomPath."api/join?fullName=".$teachername."&meetingID=". $meetingid."&password=".$password."&redirect=true&checksum=".$checksum; 
            $outputarr['Msg'] = "Class Start Successfully";
            $this->db->where('id',$classroomid);
            $this->db->update('classroom',array('classroom_active'=>1,'isvalid'=>0,'classroomlink'=>$urrrl));
       /*    }else{
            $outputarr['Status'] = false; 
             $outputarr['Msg'] = "Class Room is Still occupied by other teacher";
          }    */            
        }else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Class Room not found";
        }      
         
  
    }else{
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No Sections Found";
    }  
        echo json_encode($outputarr); 
 }



 public function startClassRoomadmin($classroomid)
 {
   
  if(!empty($classroomid > 0 )){
        $currentclass = $this->db->query("SELECT classroom.* from classroom  where id = $classroomid ")->row();
        if(!empty($currentclass)){
          $this->todayteachertendance($currentclass->teacher_id);
          $activeclasses = $this->db->query("SELECT * from classroom where classroom.class_id =  '$currentclass->class_id' and classroom.section_id =  '$currentclass->section_id'
          and classroom.session_id  =  '$currentclass->session_id' and classroom.classroom_active  = 1 and classroom.date = '$currentclass->date' ")->row();
          //print_r($activeclasses);;die;
          if(empty($activeclasses)){
         //  print_r($activeclasses);die;
            $meetingid = $currentclass->link;
            $teachername = "Teacher";
            $password = "Teachers@123";
            $checksum = $this->generatechecksumteacher($teachername,$meetingid,$password);             
            $outputarr['Status'] = true; 
            $outputarr['url'] = $urrrl = $this->roomPath."api/join?fullName=".$teachername."&meetingID=". $meetingid."&password=".$password."&redirect=true&checksum=".$checksum; 
            $outputarr['Msg'] = "Class Start Successfully";
            $this->db->where('id',$classroomid);
            $this->db->update('classroom',array('classroom_active'=>1,'isvalid'=>0,'classroomlink'=>$urrrl));
            redirect($urrrl);
          }else{
           $meetingid = $currentclass->link;
           $teachername = "Teacher";
           $password = "Teachers@123";
           $checksum = $this->generatechecksumteacher($teachername,$meetingid,$password);             
           $outputarr['Status'] = true; 
           $outputarr['url'] = $urrrl = $this->roomPath."api/join?fullName=".$teachername."&meetingID=". $meetingid."&password=".$password."&redirect=true&checksum=".$checksum; 
           $outputarr['Msg'] = "Class Start Successfully";
           $this->db->where('id',$classroomid);
           $this->db->update('classroom',array('classroom_active'=>1,'isvalid'=>0,'classroomlink'=>$urrrl));
           redirect($urrrl);
          }               
        }else{
          redirect('http://jpacademymeerut.com/admin/student/classroom');
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Class Room not found";
        }     
    }else{
      redirect('http://jpacademymeerut.com/admin/student/classroom');
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No Sections Found";
    }  
       // echo json_encode($outputarr); 
 }
 
 public function todayteachertendance($tid)
 {
     $date =date('Y-m-d') ;
     $check = $this->db->select('*')->where('staff_id',$tid)->where('date','$date')->get('staff_attendance')->row();  
     if(empty($check))
     {
        $attendance['date'] = $date ;
        $attendance['staff_id'] = $tid ;
        $attendance['staff_attendance_type_id'] = 1;
        $attendance['remark'] = "Online Classes" ;
        $attendance['is_active'] = 1 ; 
        $this->db->insert('staff_attendance',$attendance);
      }else{
        $this->db->where('date', $date);
        $this->db->where('staff_id',$tid);
        $this->db->update('staff_attendance',$attendance);
      }
      return 1;
  }
 /* video conferencing apis */
 
 

 public function reJoinClassRoom()
 {
  $classroomid =$this->input->post('roomId');    
  if(!empty($classroomid > 0 )){
        $currentclass = $this->db->query("SELECT classroom.* from classroom  where id = $classroomid ")->row();     
          $classroomlink = $currentclass->classroomlink;
            // $meetingid = $currentclass->link;
            // $teachername = "teacher";
            // $password = "Teachers@123";
            // $checksum = $this->generatechecksumteacher($teachername,$meetingid,$password);         
            $outputarr['Status'] = true; 
            $outputarr['url'] =  $classroomlink;//"https://room.classeye.in/bigbluebutton/api/join?fullName=".$teachername."&meetingID=". $meetingid."&password=".$password."&redirect=true&checksum=".$checksum; 
            $outputarr['Msg'] = "Class Rejoined Url found Successfully";       
    }else{
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No Sections Found";
    }  
      echo json_encode($outputarr); 
 }


 

public function createRoomprincipal()
{     
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section');    
    $teacher_id =  $this->input->post('teacher_id');     
    $title =  $this->input->post('title');    
    $session_id = $this->current_session;        
    $studentlist  = $this->student_model->searchByClassSection_api($class, $section, $session_id);
    $rand = rand();
    $name = $class.$section.$teacher_id.$title.$rand;
    $chiper = md5($name);
    $url = "https://meet.helpeye.in/".$chiper;
    $data2 = array(                        
      'class_id' => $class,
      'section_id' => $section,
      'session_id' => $session_id,
      'link' => $url,
      'title' => $title,
      'classroom_active' =>1,
      'teacher_id'=>$teacher_id
    );
    $this->db->insert('classroom',$data2);   
    $classcreated =  $this->db->insert_id();
    $classroomdata= array(    
      'link' => $url,
      'title' => $title,
      'classroom_id' =>$classcreated,
      'classroom_active' =>1     
    );
    $this->db->insert('classroomdata',$classroomdata);
    if(!empty($studentlist) && $classcreated > 0 ){
      $count =1;     
    //  print_r($studentlist);die;
    foreach($studentlist as $value){   
    $count++;
               $data1 = array(             
              'student_session_id' => $value['student_session_id'],
              'student_id' => $value['id'],
              'class_id' => $class,
              'section_id' => $section,
              'session_id' => $session_id,
              'link' => $url,
              'classroom_id' =>$classcreated,
              'joined'=>0,
              'is_student'=>1,
              'teacher_id'=>$teacher_id
            
        );
        $this->db->insert('classroomStudents',$data1);
       
        $device_token= $value['device_token'];
        $student_id = $value['id'];
        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        foreach ($device_tokens as $key => $val) {
          $top['to'] = $val->device_token ;
           $message1 = "Join Active Class Room";
        $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);

          }
        if($device_token!=''){        
        $top['to'] = $device_token ;
        $message1 = "Join Active Class Room";
        $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
       }       
    }   
       $outputarr['classroomdata'] =$classroomdata; 
       $outputarr['classRoomId'] =$classcreated; 
       $outputarr['Status'] =true; 
       $outputarr['Msg'] = "Class Room Created Successfully";
}else { 
        $outputarr['Status'] = false; 
        $outputarr['Msg'] = "No Record Found";
      }  
      echo json_encode($outputarr);  
}



public function createteacherRoomprincipal()
{
  $teacher_ids =  $this->input->post('teacher_ids');    
  $url =  $this->input->post('url');  
  $title =  $this->input->post('title');  
    foreach ($teacher_ids as $key => $teacher_id) {
      $classroomteachers['title'] = $title;
      $classroomteachers['link'] = $url;
      $classroomteachers['teacher_id'] = $teacher_id->id;
      $this->db->insert('classroomteachers',$classroomteachers);
      $device_token = $this->db->query("SELECT device_token from staff where id = '$teacher_id->id'")->row()->device_token;
      $top['to'] = $device_token ;
      $message1 = "Join Active Teacher Meeting Room";
     
      $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'J P Academy/Join Active Teacher`s Meeting Room', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
      $result=   $this->sendPushNotificationTwo($top);
    }
    return 1;
}



 public function endClassRoomteacher()
 {   
  $teacher_id =  $this->input->post('teacher_id');    
  $classroomid =  $this->input->post('classroomid');  
    
    if($teacher_id > 0 &&  $classroomid  > 0 ){
      $classroom_link = $this->db->query("SELECT classroom.link from classroom where id = '$classroomid' ")->row()->link;
      $datertime = date('Y-m-d H:i:s');
      $this->db->where('teacher_id',$teacher_id);
      $this->db->where('link',$classroom_link);
      $this->db->update('classroom',array('classroom_active'=> 0,'end_classAt'=> $datertime ));

      $this->db->where('link',$classroom_link);
      $this->db->update('classroomdata',array('classroom_active'=> 0 ));
      $meetingid = $classroom_link;
      $password = "Teachers@123";
      $checksum = $this->generatechecksumteacherEndClass($meetingid,$password);
      $apipath = $this->roomPath."api/end?meetingID=".$meetingid."&password=".$password."&checksum=".$checksum;    
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apipath);
    //curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);

      $outputarr['Status'] =true; 
      $outputarr['Msg'] = "Class Room End Now";
}else { 
       $outputarr['Status'] = false; 
       $outputarr['Msg'] = "Failed To End Class Room";
      }  
    echo json_encode($outputarr);  
 }
 

 

public function endClassRoomteacheradmin($teacher_id,$classroomid)
{   
/*  $teacher_id =  $this->input->post('teacher_id');    
 $classroomid =  $this->input->post('classroomid');   */
   
   if($teacher_id > 0 &&  $classroomid  > 0 ){
     $classroom_link = $this->db->query("SELECT classroom.link from classroom where id = '$classroomid' ")->row()->link;
     $datertime = date('Y-m-d H:i:s');
     $this->db->where('teacher_id',$teacher_id);
     $this->db->where('link',$classroom_link);
     $this->db->update('classroom',array('classroom_active'=> 0,'end_classAt'=> $datertime ));

     $this->db->where('link',$classroom_link);
     $this->db->update('classroomdata',array('classroom_active'=> 0 ));
     $meetingid = $classroom_link;
     $password = "Teachers@123";
    /*  $checksum = $this->generatechecksumteacherEndClass($meetingid,$password);
     $apipath = "https://room.classeye.in/bigbluebutton/api/end?meetingID=".$meetingid."&password=".$password."&checksum=".$checksum;    
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $apipath);
   //curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     $result = curl_exec($ch); */
     redirect('http://jpacademymeerut.com/admin/student/classroom');
 } 
}

 public function generatechecksumteacherEndClass($meetingid,$password)
 {
   $string = sha1("endmeetingID=".$meetingid."&password=".$password.$this->roomSecretKet);
   return $string ;
 } 
 

 public function endClassRoom()
 {   
  $teacher_id =  $this->input->post('teacher_id');    
  $classroomid =  $this->input->post('classroomid');  
    
    if($teacher_id > 0 &&  $classroomid  > 0 ){
      $datertime = date('d-m-Y H:i:s');
      $this->db->where('teacher_id',$teacher_id);
      $this->db->where('id',$classroomid);
      $this->db->update('classroom',array('classroom_active'=> 0,'end_classAt'=> $datertime,'isvalid'=>0 ));
      $this->db->where('classroom_id',$classroomid);
      $this->db->update('classroomdata',array('classroom_active'=> 0 ));
    
  $outputarr['Status'] =true; 
  $outputarr['Msg'] = "Class Room End Now";
}else { 
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "Failed To End Class Room";
    }  
    echo json_encode($outputarr);  
 }


 public function classRoomList()
 {
  $teacher_id =  $this->input->post('teacher_id');    
   if($teacher_id > 0){   

     $this->createRegularRoom($teacher_id);
  /*    
     $result =  $this->db->query("SELECT classroom.*,classes.class,sections.section from classroom  
     join classes on classroom.class_id = classes.id 
     join sections on classroom.section_id = sections.id 
     where teacher_id = '$teacher_id'  order by classroom.classroom_active desc ")->result();
 */
     $todaydate = date('d-m-Y'); 
      $result =  $this->db->query("SELECT classroom.*,DATE_FORMAT(classroom.created_at, '%d-%m-%Y %h:%i %p'),classes.class,sections.section from classroom  
     join classes on classroom.class_id = classes.id 
     join sections on classroom.section_id = sections.id 
     where teacher_id = '$teacher_id' and classroom.date = '$todaydate'  group by classroom.link order by classroom.classroom_active desc,lecture_id asc ")->result(); 

   /*  $x = new stdClass();
      $count = 0;
      $class = 0;
      $link = 0;
      foreach($result as $key => $value) {
        if($link == $value->link){
          $x->section .=$value->section.',' ;
         }else{
          $class =$value->class ;
          $x->id =$value->id ;
          $x->class_id =$value->class_id ;
          $x->section_id =$value->section_id ;
          $x->session_id =$value->session_id ;
          $x->link =$value->link ;
          $x->classroom_active =$value->classroom_active ;
          $x->title =$value->title ;
          $x->teacher_id =$value->teacher_id ;
          $x->created_at =$value->created_at ;
          $x->end_classAt =$value->end_classAt ;          
        }
        //  $x->section = $section;
          $link = $link;
          $result[] = $x ;
     }   */
       if(!empty($result)) {
      $outputarr['ClassRoomList'] =$result; 
      $outputarr['Status'] =true; 
      $outputarr['Msg'] = "Class Room Found";
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

public function check_isactive()
{
  $url =  $this->input->post('url');   
   
   if(!empty($url)){   
     $result =  $this->db->query("SELECT id from classroomdata where link = '$url' and classroom_active = 1 ")->row();
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


public function newRoom()
{   
   $data['title'] =  $title = $this->input->post('title');    
   $chiper = md5($title);
   $url = "https://meet.helpeye.in/".$chiper;
   $data['link'] =$url;
   $this->db->insert('classroomdata',$data);
   $id = $this->db->insert_id();
   if (!empty($id)) {
     $outputarr['link'] =$url; 
     $outputarr['Status'] =true; 
     $outputarr['Msg'] = "Class Room Active And Genuine";
   }else{
     $outputarr['Status'] = false; 
     $outputarr['Msg'] = "No Class Room Found";
   }  
   echo json_encode($outputarr);
}


 
/* video conferencing apis  end*/

public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AAAAWkI6xLM:APA91bHrLkdwg6CEPsZICzhIK0Wodps3MEtI01P1dOvQz8baXzVQc8oeeKCFXPIbl722WhGEkh7n7LygqxQKCyRCK7yCeupEjbqcJkSKD4mpnvdmu6JMm9rHLWgKVxSSoPAho5YCrnkw';
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



public function deleteVideo()
{
   $id = $this->input->post('videoid');
  if(!empty($id)){    
    $this->db->where('id',$id);
    $this->db->delete('tvideo');
    /* echo $this->db->last_query(); 
    die; */
    $outputarr['Status'] = 1; 
    $outputarr['Msg'] = "Video Delete Successfully";
  }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Fail To delete Video";
  }
  echo json_encode($outputarr); 
}

public function deleteMessages()
{
  $message_id = $this->input->post('message_id');	   
  if(!empty($message_id)){  
   $messagedetails = $this->db->query("SELECT * from messages where id = '$message_id'")->row();  
   if (!empty($messagedetails)) {
   $this->db->where('title',$messagedetails->title);
    $this->db->where('class_id',$messagedetails->class_id);
    $this->db->where('section_id',$messagedetails->section_id);
    $this->db->where('message',$messagedetails->message);
    $this->db->where('teacher_id',$messagedetails->teacher_id);
    $this->db->delete('messages');
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Message Content Delete Successfully";
   }else{
   	  $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Fail To delete Message Content";
   }
   
  }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Fail To delete Message Content";
  }
  echo json_encode($outputarr);
}




public function createRegularRoomadmin($teacher_id )
{    
    //$teacher_id = $this->input->post('teacher_id');
    $session_id =  $this->current_session ;     
  //  print_r($session_id); die;    
    $rand = rand();
    $teache_tt= $this->staff_model->getdetails_by_teacherID($teacher_id); 

if(!empty($teache_tt)){
 foreach ($teache_tt as $key => $teache_t) {
   $days = array('Sunday','Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
   $today = date('l');
if($days[$teache_t->days] == $today){
  if($teache_t->class_id> 0 && $teache_t->section_id > 0 && $teache_t->subject_id > 0){ 
  $name = $teache_t->period_id.$teache_t->subname.$teacher_id.$teache_t->subject_id.$rand;
 
   
  /* bbb end */
  $date =date('d-m-Y');
  $resdata = $this->db->query("SELECT id from classroom where classroom.teacher_id = '$teache_t->teacher_id' and classroom.lecture_id = '$teache_t->period_id' 
  and classroom.days = $teache_t->days and classroom.date = '$date' ")->row();   

    if(empty($resdata)){
      $chiper = md5($name); 
        
       $url = $chiper;
     
       $meetingName= "teacher";
       $meetingID=$chiper ;
       $mpassword= "Teachers@123";
       $apassword= "Students@123";
       $checksum = $this->generatechecksumClassroom($meetingName,$meetingID,$mpassword,$apassword);    
     //$apipath = "https://room.classeye.in/bigbluebutton/api/create?allowStartStopRecording=false&attendeePW=".$apassword."&autoStartRecording=false&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&record=false&voiceBridge=78105&welcome=Welcome&checksum=".$checksum;    
       $apipath = $this->roomPath."api/create?attendeePW=".$apassword."&meetingID=".$meetingID."&moderatorPW=".$mpassword."&name=".$meetingName."&checksum=".$checksum;    
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $apipath);
     //curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
    
      $studentlist  = $this->student_model->searchByClassSection_api($teache_t->class_id, $teache_t->section_id, $session_id);
      $data2 = array(                        
       'class_id' => $teache_t->class_id,
       'section_id' => $teache_t->section_id,
       'session_id' => $session_id,
       'link' => $url,
       'title' => "Lecture - ".$teache_t->period_id.' - '.date('d-m-Y'),
       'description' =>  $teache_t->tname.'-'.$teache_t->subname,
       'classroom_active' =>0,
       'teacher_id'=>$teacher_id,
       'lecture_id'=> $teache_t->period_id,
       'days'=> $teache_t->days,
       'date'=> date('d-m-Y'),    
       'iscancel'=>0,
       'exceedTimeValue'=>15,
       'isvalid'=>1,
       'starttime'=>$teache_t->timefrom,
       'endTime'=>$teache_t->timeto,
       'startdate'=>date('d-m-Y'),
     );
     $this->db->insert('classroom',$data2); 
     //print_r($this->db->last_query()); die;  
     $classcreated =  $this->db->insert_id();
    
     if(!empty($studentlist) && $classcreated > 0 ){
                             foreach($studentlist as $value){       
                                 
                                 $device_token= $value['device_token'];
                                 $student_id = $value['id'];
                                 if($device_token!=''){        
                                 $top['to'] = $device_token ;
                                 $message1 = "Join Active Class Room";
                                 $top['data'] = array('data' => array('classroom'=>$url,'type' => 'RequestVideoURL', 'title' => 'Little Kingdom School/Join Active ClassRoom', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
                                 $result=   $this->sendPushNotificationTwo($top);
                               }
                             /* End copy */
                           }
 
                                                            $success = 0;
                                                             if(!empty($sections)){                                                               
 
                                                                
                                                                 $outputarrs['classRoomId'] =$classcreated; 
                                                                 $outputarrs['Status'] =true; 
                                                                 $outputarrs['Msg'] = "Class Room Created Successfully";   
                                                               $success++;
                                                             }else { 
                                                               if($success > 0){
                                                               
                                                                 $outputarrs['classRoomId'] =$classcreated; 
                                                                 $outputarrs['Status'] =true; 
                                                                 $outputarrs['Msg'] = "Class Room Created Successfully";  
                                                               }else{
                                                                 $outputarrs['Status'] = false; 
                                                                 $outputarrs['Msg'] = "No Records Found";
                                                               }
                                                                 
                                                               }  
                 }
    }
  }
}
}//foreach teachertimetable
  }
else{
  $outputarrs['Status'] = false; 
  $outputarrs['Msg'] = "No Lecture Found";
}
   
  echo json_encode($outputarrs);  
      
}

public function gmeetList()
{
  $teacher_id = $this->input->post('teacher_id'); 
      $mydate = date('Y-m-d');
       $day=date('l', strtotime($mydate)); 
  
  if(!empty($teacher_id)){
   
        $conf = $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where teacher_id = $teacher_id and DATE_FORMAT(`class_date`, '%W') ='$day'")->result();
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
        $outputarr['Msg'] = "Satff Id Not Found";
       }
        echo json_encode($outputarr);   
}

// ---------------------------------end class-----------------------------//
}



?>

