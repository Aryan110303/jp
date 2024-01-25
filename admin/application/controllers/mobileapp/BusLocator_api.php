<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//defined('BASEPATH') OR exit('No direct script access allowed');
/*

 ----  New Tables ---- 
      # driver_details
      # busride
      # pick_drop
      # bus_location

 */
class BusLocator_api extends CI_Controller {
  
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


public function driverLogin()
    {
     $postdata =  $this->input->post('data');    
      if ($postdata != '') {   
        $request = json_decode($postdata);
        $number = $request->number ;
        $password = $request->password ;
        $devicetoken = $request->devicetoken ;
        $mob_imei = $request->mob_imei ;
        $mob_model = $request->mob_model ;
        if (!empty($number) && !empty($password)){
         $details = $this->db->query("SELECT * from driver_details where mobile_number = $number and password = $password")->row();     
         if ($details->is_login == 0) {
               $key =rand() ;              
               $updateData['mob_model'] = $mob_model ;
               $updateData['mob_imei'] = $mob_imei;
               $updateData['devicetoken'] = $devicetoken;
               $updateData['key'] = $key;
               $updateData['is_login'] = 1 ;
               $this->db->where('id',$details->id);
               $this->db->update('driver_details',$updateData);   
               $detailsfinal = $this->db->uery("SELECT * from driver_details where mobile_number = $number and password = $password and key = $key ")->row();
               foreach ($detailsfinal as $key => $value) 
                 {
                  $value->session_id = $this->current_session;
                 }
               if (!empty($detailsfinal)) {
                           $outputarr['Result'] = $detailsfinal; 
                           $outputarr['Status'] = true; 
                           $outputarr['Msg'] = "Login Successfully";
                         }else{
                           $outputarr['Status'] = false; 
                           $outputarr['Msg'] = "Login Fail";
                         }          
             }else{             
              $outputarr['Status'] = false; 
              $outputarr['Msg'] = "User Logged in Different Handset";
             }          
         }else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Request  Data Not Found";
        }
      }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "Postdata Not Found";
      }
      echo json_encode($outputarr);      
    }


public function vehicleLogin()
    {
     $postdata =  $this->input->post('data');    
      if ($postdata != '') {   
        $request = json_decode($postdata);
        $userid = $request->userid ;
        $password = $request->password ;   
        $devicetoken = $request->devicetoken ;  
      
        if (!empty($userid) && !empty($password)){
         $details = $this->db->query("SELECT * from vehicles where userid = '".$userid."' and password = '".md5($password)."'")->row();     
       
            if (!empty($devicetoken)){
                   $updateData['devicetoken'] = $devicetoken;
               }              
               $updateData['is_login'] = 1 ;
               $this->db->where('id',$details->id);
               $this->db->update('vehicles',$updateData);
               $base = base_url();
               $detailsfinal = $this->db->query("SELECT vehicles.vehicle_no,vehicles.vehicle_model,vehicles.manufacture_year,vehicles.note ,vehicles.id , 
                driver_details.name, driver_details.address,CONCAT('$base',driver_details.profile_image) as profile_image , driver_details.licence_no, driver_details.aadhar_no,driver_details.mobile_number, driver_details.devicetoken from vehicles left join driver_details on vehicles.driver_id = driver_details.id where vehicles.userid = '".$userid."' and vehicles.password = '".md5($password)."' ")->row();
               if (!empty($detailsfinal)) {
                 
                     $detailsfinal->session_id = $this->current_session;
                 
               }
              

               if (!empty($detailsfinal)) {
                           $outputarr['Result'] = $detailsfinal; 
                           $outputarr['Status'] = true; 
                           $outputarr['Msg'] = "Login Successfully";
                         }else{
                           $outputarr['Status'] = false; 
                           $outputarr['Msg'] = "Login Fail";
                         }          
                    
         }else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Request  Data Not Found";
        }
      }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "Postdata Not Found";
      }
      echo json_encode($outputarr);      
    }

public function vehiclesList()
{
  $result = $this->db->query("SELECT vehicle_no, vehicle_model, manufacture_year, note from vehicles where 1 ")->result();
  if (!empty($result)) {
          $outputarr['Result'] = $result; 
          $outputarr['Status'] = true; 
          $outputarr['Msg'] = "Data Found";
       }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "Data Not Found";
      }
  echo json_encode($outputarr);  
}


public function studentListByVehicle($value='')
{
  $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $vehicle_id = $request->vehicle_id;
    $session_id = $request->session_id;
    if ($vehicle_id > 0) {
          $result = $this->db->query("SELECT students.*,classes.class,sections.section,student_session.id as student_session_id from students join student_session on student_session.student_id = students.id join classes on classes.id = student_session.class_id join sections on sections.id = student_session.section_id where student_session.vehicle_id = $vehicle_id and student_session.session_id = $session_id ")->result();
          $outputarr['Result'] = $result; 
          $outputarr['Status'] = true; 
          $outputarr['Msg'] = "Record Found";
    }else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "No Record Found";
       }
  }else { 
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "No Record Found";
        }  
   echo json_encode($outputarr);  
            
}

public function startEndRide()
{
 $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $session_id =$request->session_id;
    $id =$request->id;
    $startdata['driver_id'] = $request->driver_id;
    $startdata['vehicle_id'] = $request->vehicle_id;
    $startdata['session_id'] = $session_id;
    $startdata['ride_startdateTime'] = $request->ride_startdateTime;
    $startdata['start_latitude'] = $request->start_latitude;
    $startdata['start_longitude'] = $request->start_longitude;
    $startdata['ride_enddateTime'] = '';
    $startdata['end_latitude'] = '';
    $startdata['end_longitude'] = '';
    $startdata['updated_at'] = '0000-00-00 00:00:00';
     if ($id > 0){
        $enddata['ride_enddateTime'] = $request->ride_enddateTime;
        $enddata['end_latitude'] = $request->end_latitude;
        $enddata['end_longitude'] = $request->end_longitude;
        $enddata['updated_at'] = date('Y-m-d h:i:s');
        $this->db->where('id',$id);
        $this->db->update('busride',$enddata);
        $outputarr['Status'] = true; 
        $outputarr['Msg'] ="Ride is end";
      }else{

        $this->db->insert('busride',$startdata);
        $id = $this->db->insert_id();
        if ($id) {
         
        $location['busride_id'] = $id ;
        $location['date'] = date('Y-m-d') ;
        $jsonarray =  array(
          'id' => 1, 'latitude' => $request->start_latitude,
          'longitude' => $request->start_longitude ,
          'time'=> date('h:i:s'),
          'date'=> date('Y-m-d'),
           );
        $location['location'] = json_encode($jsonarray) ;           
        $this->db->insert('bus_location',$location); 
         $outputarr['Status'] = true; 
         $outputarr['Msg'] = "Ride is Start now";
       }else{
           $outputarr['Status'] = false; 
           $outputarr['Msg'] = "Ride Failed to locate";
       }
      }
   }else{
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "post data not Found";
        }  
   echo json_encode($outputarr);  
}



public function pick_drop()
{
 $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $pick_drop  = $request->pick_drop;
    $session_id = $request->session_id;
    $student_id = $request->student_id;
    $busride_id = $request->busride_id;
    if ($student_id > 0) {
           if ($pick_drop == 'picked') {
              $pickdata['busride_id'] = $busride_id;
              $pickdata['student_id'] = $student_id;
              $pickdata['session_id'] = $session_id;
              $pickdata['pick_drop']  = 0;
              $pickdata['pickdropdate'] = $date = date('Y-m-d');
              $pickdata['pickTime'] = date('h:i:s');
              $pickdata['updated_at'] ='0000-00-00 00:00:00';
              $this->insert('pick_drop',$pickdata );
              
              $outputarr['Msg'] = "Student Picked Up";
           }else{
              $date =  date('Y-m-d');
              $dropdata['pick_drop'] = 1;
              $dropdata['dropTime'] = date('h:i:s');
              $dropdata['updated_at'] = date('Y-m-d h:i:s');
              $this->db->where('busride_id',$busride_id);
              $this->db->where('student_id',$student_id);
              $this->db->where('pickdropdate',$date);
              $this->db->update('pick_drop',$dropdata);
              $outputarr['Msg'] = "Student Dropped";
             }
  $result = $this->db->query("SELECT * from pick_drop where busride_id = $busride_id and pickdropdate = '$date' ")->result();
  $outputarr['Result'] = $result;
  $outputarr['Status'] = true; 
     }else{
            $outputarr['Status'] = false; 
            $outputarr['Msg'] = "No Record Found";
          }
  }else { 
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "No Record Found";
        }  
   echo json_encode($outputarr);  
}
 
public function getLastLocation()
{
  $postdata =  $this->input->post('data');    
  if ($postdata != '') { 
    $request = json_decode($postdata);
    $busride_id = $request->busride_id ;
    if (!empty($busride_id)) {
     $location = $this->db->query("SELECT * from bus_location where busride_id = $busride_id")->row(); 
     if (!empty($location)) {
          $outputarr['Status'] = true; 
           $outputarr['Msg'] = "Location Found";
     }else{
      $outputarr['Status'] = false; 
         $outputarr['Msg'] = "No location Found";
     }
    }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "No ride id Found";
    }
     }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "No Postdata Found";
      }
   echo json_encode($outputarr);  
}

public function saveCurrentLocation()
{
  $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
      $request = json_decode($postdata);
      $busride_id = $request->busride_id;
      $longitude = $request->longitude;
      $latitude = $request->latitude;      
      $id = $request->id;
    if (!empty($busride_id)) {
        $lastdata = $this->db->query("SELECT * from bus_location where id = $id and busride_id = $busride_id")->row();
        $locations = json_decode($lastdata->location) ;
       
         $data['latitude'] = $latitude ;  
         $data['longitude'] = $longitude ;            
         $data['time'] = date('h:i:s') ;  
         $data['date'] = date('Y-m-d') ;
         $updateddata = array_push($locations, $data) ;
         $insertdata['location'] = $updateddata ; 
        $this->db->where('id',$id);
        $this->db->where('busride_id',$busride_id);
        $this->db->update('bus_location',$insertdata);
       
       if (!empty($lastdata)) {
          $outputarr['Status'] = false; 
          $outputarr['Msg'] = "Location Updated Successfully";
       }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "Failed To Save Location";
       }
      }else{
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "No busride id Found";
      }
   }else{    
         $outputarr['Status'] = false; 
         $outputarr['Msg'] = "No Postdata Found";
        }  
   echo json_encode($outputarr);  
}

public function class_section_list()
{    
  $postdata =  $this->input->post('data');    
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $staff = $request->id ;
    }else { 
      $outputarr['Status'] = false; 
      $outputarr['Msg'] = "No post data Found";
  }        
  $result = $this->Api_model->get_class_section_list_new($staff);

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
        $outputarr['Result'] = $resstudentlistult ;
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
    // $file =   $this->input->post('file');     
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
              'student_session_id' =>$value['student_session_id'] ,
              'teacher_id'=>$teacher_id,
              'path'=>$img_path
        );
        $msgId = $this->messages_model->add($data1); 
        $device_token= $value['device_token'];
        $student_id = $value['id'];
        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();
        foreach ($device_tokens as $key => $val) {
          $top['to'] = $val->device_token ;
                $message1 = "Homework From School";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Homework', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);

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
      echo json_encode($outputarr);  
}


public function sendComplain()
{     
    $class =  $this->input->post('class');    
    $section =  $this->input->post('section');    
    $message =  $this->input->post('message');    
    $teacher_id =  $this->input->post('teacher_id');   
    $studentlist  = json_decode($this->input->post('s_list')); 
    // $file =   $this->input->post('file'); 
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
            'student_session_id'=> $student_detail['student_session_id'],
            'teacher_id'=> $teacher_id,
            'path'=>$img_path,
        );
         $msgId = $this->messages_model->add($data1); 

         $device_token=$student_detail['device_token'];

         $student_id = $student_detail['id'];

        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id ")->result();

        foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token ;
                $message1 = "Notice From School";
          $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Notice', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top);

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
     echo json_encode($outputarr);  
}


 


public function stuattendence(){
    
    $postdata =  $this->input->post('data'); 
    //$postdata = '{"class":"40","section":"17","teacher_id":"14"}';
    $outputarr = array();   
  if ($postdata != '') {   
    $request = json_decode($postdata);
    $class = $request->class ;
    $section = $request->section ;
	  $teacher_id=$request->teacher_id ;
    $date = $request->date ;
   //$date = date('y-m-d');
  
  $isClassTeacher=$this->Api_Classteacher_model->check_classTeacher($class, $section, $teacher_id,$this->current_session);
	if($isClassTeacher){  
  
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
    $todate =  date('Y-m-d');
if ( $value->attendence_id !=0) {
        $ssessionid = $value->student_session_id;
            $arr = array(
                'id' => $value->attendence_id,
                'student_session_id' => $value->student_session_id,
                'attendence_type_id' => $value->attendence_type_id,
                'remark' =>'' ,
                'date' => date('Y-m-d')
            );
          }
            else{
                $arr = array(                
                'student_session_id' => $value->student_session_id,
                'attendence_type_id' => $value->attendence_type_id,
                'remark' =>'' ,
                'date' => date('Y-m-d')
                );           
               }
             $insert_id = $this->stuattendence_model->add($arr);
             $attendn = $this->db->query("SELECT type from attendence_type where id = '".$value->attendence_type_id."'")->row()->type; 
             $device_token= $this->db->query("SELECT students.device_token, students.* from student_session join students on students.id = student_session.student_id where student_session.id = $ssessionid  ")->row();

        $student_id = $device_token->id;

        $device_tokens = $this->db->query("SELECT * from  devicetoken where student_id = $student_id")->result();


        if($device_token!=''){
        
        $top['to'] = $device_token->device_token ;
               $stuname = $device_token->firstname.' '.$device_token->lastname ;
                $message1 = $device_token->firstname.' '.$device_token->lastname." is ".$attendn." on ".$todate." || Central Academy";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI/ Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
        $result=   $this->sendPushNotificationTwo($top);
           }
 foreach ($device_tokens as $key => $value) {
          $top['to'] = $value->device_token ;
        $message1 = $stuname." is ".$attendn." on ".$todate." || Central Academy";
        $top['data'] = array('data' => array('type' => 'Request', 'title' => 'Central Academy KATANGI / Attendance', 'is_background' => false, 'message' => $message1, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
         $result=   $this->sendPushNotificationTwo($top); 
        }               
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
    //$postdata = file_get_contents("php://input");
    $postdata =  $this->input->post('data');    
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
                    $result = $this->db->query("SELECT students.*, classes.class, sections.section from students join student_session on student_session.student_id = students.id join classes on classes.id = student_session.class_id join sections on sections.id = student_session.section_id  where student_session.class_id = $class_id and student_session.section_id = $section_id  ")->result(); 
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



public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AIzaSyCT-A54uBY2jeKTEg0rkkfKzA8GLcPFUso';
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

