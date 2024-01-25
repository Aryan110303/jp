  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  //defined('BASEPATH') OR exit('No direct script access allowed');

  class Proxy_api extends CI_Controller {
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

  public function SessionList()
  {
    $arr['session_result'] = $this->session_model->get();
    $arr['status'] = 1;
    echo json_encode($arr);
  }

 public function teacherList()
 { 
   $postdata = file_get_contents("php://input");
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
 if ($postdata != '')
 {  
   $request = json_decode($postdata);
   if(!empty($request))
   { 

   $id =  $request->id;
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



public function get_proxy_teacher()
 {
    $postdata = file_get_contents("php://input");   
     if(!empty($postdata))
     {
       $request = json_decode($postdata);
        if(!empty($request))
            {            
               $type = $request->type;
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
                       $resultdata = $this->Proxy_model->get_timetable($teacher_id,$proxydate,$type); 
                       $teacher_group = $this->db->query("SELECT teachers_group.group from staff join teachers_group on staff.teachers_group = teachers_group.id where staff.id  = $teacher_id")->row()->group;
                                            
                       if ($teacher_group == 0 ) {
                              $teacher_group = 3 ;
                            }
                          $engagged_teacher = 0;
                                     
                     foreach ($resultdata as $key => $value)
                     {  
                      $this->loopCount=0;

                      $pyteacher = $this->get_proxy($value->class_id,$value->subject_id,$value->period_id,$value->days,$proxydate,$value->section_id,$teacher_id, $teacher_group ,$engagged_teacher); 
                       
                           $tid = $pyteacher->teacher_id ;
                       if(!empty($tid)){
                            $engagged_teacher = $engagged_teacher.','.$tid;
                            $value->proxyteacher = $pyteacher ; 
                       }else{    

                          $pyteacher = $this->get_proxy($value->class_id,$value->subject_id,$value->period_id,$value->days,$proxydate,$value->section_id,$teacher_id, $teacher_group ,$engagged_teacher); 
              
                           $tid = $pyteacher->teacher_id ;
                               if(!empty($tid)){
                                     $engagged_teacher = $engagged_teacher.','.$tid;
                                      $value->proxyteacher = $pyteacher ; 
                               }else{  
                                   $object = new stdClass();
                                   $object->name = " ";
                                   $object->teacher_id = "0";
                                   $object->surname = " ";
                                   $object->class = $this->Proxy_model->findfield('classes','id',$value->class_id,'class');
                                   $object->section =  $this->Proxy_model->findfield('sections','id',$value->section_id,'section');
                                   $object->subject =  $this->Proxy_model->findfield('subjects','id',$value->subject_id,'name');
                                   $object->period =  $this->Proxy_model->findfield('periods','id',$value->period_id,'value');
                                   $object->day =  $this->Proxy_model->findfield('days','id',$value->days,'value');
                                   $object->period_id = $value->period_id;
                                   $value->proxyteacher = $object ;
                                }  
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
      if ($this->loopCount >500) {
         $object = new stdClass();
         $object->name = " ";
         $object->teacher_id = "0";
         $object->surname = " ";
         $object->class = $this->Proxy_model->findfield('classes','id',$class_id,'class');
         $object->section = $this->Proxy_model->findfield('sections','id',$section,'section');
         $object->subject = $this->Proxy_model->findfield('subjects','id',$subject,'name');
         $object->period = $this->Proxy_model->findfield('periods','id',$period,'value');
         $object->day = $this->Proxy_model->findfield('days','id',$day,'value');
         $object->period_id = $period;
         return $object;
         }
           $resarry =  array();
           if ($period == 1 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where staff_attendance_type_id in(3,6,7,8) and `date`= '".$date."' ")->result();
           }elseif ($period == 2 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where staff_attendance_type_id in(3,6,7,8) and `date`= '".$date."' ")->result();
           }elseif ($period == 3 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id  in(3,8)  and `date`= '".$date."' ")->result();
            }elseif ($period == 4 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id  in(3,8) and `date`= '".$date."' ")->result();
            }elseif ($period == 5 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id in(3,9) and `date`= '".$date."' ")->result();
            }elseif ($period == 6 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id in(3,9) and `date`= '".$date."' ")->result();
            }elseif ($period == 7 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id  in(3,9) and `date`= '".$date."' ")->result();
            }elseif ($period == 8 ) {
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id in(3,9) and `date`= '".$date."' ")->result();
            }else{
              $absent_teacher = $this->db->query("SELECT staff_id from staff_attendance where  staff_attendance_type_id in(3,9) and `date`= '".$date."' ")->result();
            }   
            if (!empty($absent_teacher)) {
              foreach ($absent_teacher as $key => $value)
                {
                 $resarry[] = $value->staff_id ;
                }
             }

      $busyteachers_array = array();
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
  
      $minlecture = 6 ;
      $freeteachers = $this->db->query("SELECT staff.id , staff.name , staff.surname ,count(teacher_id) as countid from staff left join teacher_timetable on staff.id = teacher_timetable.teacher_id where staff.teachers_group != 0 and staff.teachers_group in ($teacher_group) and teacher_timetable.class_id != 0 and teacher_timetable.section_id != 0 and teacher_timetable.subject_id != 0 and days = $day and staff.id not in ( $busyteachers ) and staff.id not in ($engagged_teacher) and staff.id not in (SELECT extrateacher_id from proxyteachers where `date` = '".$date."' and period_id = $period) group by teacher_timetable.teacher_id having count(teacher_timetable.teacher_id) <= $minlecture ")->result();

     if (!empty($freeteachers)) {
       foreach ($freeteachers as $key => $value){                        
           if (in_array($value->id, $resarry)){ 
             continue ; 
              }else {    
                     $object = new stdClass();
                     $object->name = $value->name;
                     $object->teacher_id = $value->id;
                     $object->surname = $value->surname ;
                     $object->class = $this->Proxy_model->findfield('classes','id',$class_id,'class');
                     $object->section = $this->Proxy_model->findfield('sections','id',$section,'section');
                     $object->subject = $this->Proxy_model->findfield('subjects','id',$subject,'name');
                     $object->period =  $this->Proxy_model->findfield('periods','id',$period,'value');
                     $object->day =  $this->Proxy_model->findfield('days','id',$day,'value');
                     $object->period_id = $period;
                     return $object;
                      break; 
                     }

                        }
             }else{
                $object = new stdClass();
                     $object->name = '';
                     $object->teacher_id = '';
                     $object->surname = '' ;
                     $object->class =  ($this->Proxy_model->findfield('classes','id',$class_id,'class') != '')?$this->Proxy_model->findfield('classes','id',$class_id,'class'):'0';
                     $object->section =  ($this->Proxy_model->findfield('sections','id',$section,'section')!= '')? $this->Proxy_model->findfield('sections','id',$section,'section') : '0';
                     $object->subject =  ($this->Proxy_model->findfield('subjects','id',$subject,'name') != '') ? $this->Proxy_model->findfield('subjects','id',$subject,'name') :'0';
                     $object->period = ($this->Proxy_model->findfield('periods','id',$period,'value') != '')?$this->Proxy_model->findfield('periods','id',$period,'value'): '0';
                     $object->day =  ($this->Proxy_model->findfield('days','id',$day,'value') != '')?$this->Proxy_model->findfield('days','id',$day,'value'):'0' ;
                     $object->period_id = $period;
                     return $object;
                   }    
            }
    
    
public function checkgroup($grp=0)
 {
   $group = array(1,2,3,4,5,6);  
   $min = min($group);
   $max = max($group);
   $newval = $grp ;
        if ($grp > $min) {
          for ($i=1; $i <= $max; $i++){ 
                if ($grp > $min && $newval <= $max){
                      $pre[] =  $newval ;
                     $newval = $grp+$i ;
                }
             }
             for ($i=$max; $i >= 1 ; $i--){ 
                if ($grp > $min && $newval1 <= $max){
                      $post[] =  $newval1 ;
                     $newval1 = $grp-$i ;
                }
             }
             $newpre = array_diff($group,$pre);  
             rsort($newpre);  

             $result1 = array_merge($pre, $newpre);
             $result = array_merge($result1, $post);
           }else{
              $result = $group ;
             }  
   return $result ;
 }





public function save_proxy()
{
    $postdata = file_get_contents("php://input");
    if(!empty($postdata))
      {
         $request = json_decode($postdata);
         if(!empty($request))
            {
              $arrry = $request->proxydata; 
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
              $ip_avail =  $this->db->query("SELECT ip from ipaddress WHERE FIND_IN_SET('".$ip."', ip) > 0")->row()->ip;
             //print_r($this->db->last_query());
              if (!empty($ip_avail)){
                $data['status'] = 1; 
              }else
                 $data['status'] = $ip_avail;
                //$data['status'] = 1; 
            }
          else
          {
              $data['status'] = 1; 
          }
      }
        else
          {
              $data['status'] = 1; 
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
                  if (!empty($number)) {
                        $msg_data=array(
                        'title'=>'Substitution Management',
                        'message'=>"Dear $extrateacher you have been substituted in $class $section for period $period of $subject on $day $date .",
                        'session_id'=>$session_id,
                        'user_list'=> $number ,
                        'sms_status'=>0,
                        'student_session_id'=>0
                      );
                    $this->Messages_model->add($msg_data);
                  }
                }
               $data['Msg'] = "Message sent Successfully";
               $data['status'] = 1;
            }
             else{
           $data['Msg'] = "Request data not found";
           $data['status'] = 0;
           }   
        } 
         else{
           $data['Msg'] = "Post data not found";
           $data['status'] =  0;
           }
    echo json_encode($data);     
 }
    
public function AllteacherList()
{
      $teachers = $this->Api_model->allteachers();
      $outputarr['result'] = $teachers; 
      $outputarr['Status'] = 1; 
      $outputarr['Msg'] = "Data found ";
      echo json_encode($outputarr);
}


  //////////////end class
 
}

   ?>

