<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proxymanagment extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->load->model("proxy_model");
    }

public function index() {
    $this->session->set_userdata('top_menu', 'Academics');
    $this->session->set_userdata('sub_menu', 'proxy/index');
    $session = $this->setting_model->getCurrentSession();
    $data['title'] = 'Exam Marks';
    $data['exam_id'] = "";
    $data['class_id'] = "";
    $data['section_id'] = "";
    $exam = $this->exam_model->get();
    $class = $this->class_model->get();
    $section = $this->section_model->get();
    $teacher = $this->staff_model->get_staff();
    $subject = $this->subject_model->get();
    $proxylist = $this->proxy_model->get_list();
    $data['teachers'] = $teacher;
    $data['classlists'] = $class;
    $data['sections'] = $section;
    $data['subjects'] = $subject;
    $data['proxylist'] = $proxylist;
    $userdata = $this->customlib->getUserData();
    $feecategory = $this->feecategory_model->get();
    $data['feecategorylist'] = $feecategory;         
        $this->load->view('layout/header', $data);
        $this->load->view('admin/proxy/proxyteacher_list', $data);
        $this->load->view('layout/footer', $data);    
   }




 public function get_freeteacher()
 {

   if(!empty($_POST)) {
     $data['periods']= array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V", '6'=>"VI", '7'=>"VII", '8'=>"VIII");
     $data['days']= array('1'=>"Monday",'2'=>"Tuesday",'3'=>"Wednesday",'4'=>"Thrusday",'5'=>"Friday", '6'=>"Saturday");
     $proxydate = $this->input->post('proxydate');
     $teacher_id = $this->input->post('teacher_id');
     $data['date'] = $proxydate ;

     $data['resultdata'] = $this->proxy_model->get_timetable($teacher_id,$proxydate);

     }
                $this->session->set_userdata('top_menu', 'Academics');
                $this->session->set_userdata('sub_menu', 'proxy/index');
                $session = $this->setting_model->getCurrentSession();
                $data['title'] = 'Exam Marks';
                $data['exam_id'] = "";
                $data['class_id'] = "";
                $data['section_id'] = "";
                $exam = $this->exam_model->get();
                $class = $this->class_model->get();
                $section = $this->section_model->get();
                $teacher = $this->staff_model->get_staff();
                $subject = $this->subject_model->get();
                $proxylist = $this->proxy_model->get_list();
                $data['proxylist'] = $proxylist;
                $data['teachers'] = $teacher;
                $data['classlists'] = $class;
                $data['sections'] = $section;
                $data['subjects'] = $subject;
                $userdata = $this->customlib->getUserData();
                $feecategory = $this->feecategory_model->get();
                $data['feecategorylist'] = $feecategory;   
                $this->load->view('layout/header', $data);
                $this->load->view('admin/proxy/proxyteacher_list', $data);
                $this->load->view('layout/footer', $data);
 }



 public function save_proxy()
 {
   
   $days = ($this->input->post('days')!='')?$this->input->post('days'):array();
   $periods = $this->input->post('periods');
   $classes= $this->input->post('classes');
   $sections= $this->input->post('sections');
   $subjects= $this->input->post('subjects');
   $extrateacher= $this->input->post('extrateacher');
   $behalf= $this->input->post('behalf');
   $date= $this->input->post('date');
  
     for ($i=0; $i < count($days) ; $i++) { 
    $data['days'] =  $days[$i] ;
    $data['periods'] =  $periods[$i] ;
    $data['classes'] =  $classes[$i] ;
    $data['sections'] =  $sections[$i] ;
    $data['subjects'] =  $subjects[$i] ;
    $data['extrateacher'] =  $extrateacher[$i] ;
    $data['on_behalf'] =  $behalf ;
    $data['date'] =  $date ;
    $this->db->insert('proxyteachers',$data);
     $message = "Your Lecture has been scheduled, please Check Rearangement Teacher List"; 
     $message1 = $message.'class $classes[$i], section $sections[$i] , period $periods[$i]' ;
     $data = $this->db->query("SELECT device_token,contact_no from staff where id = $extrateacher[$i]")->row();
     $device_token = $data->device_token;
     $phone = $data->contact_no;
     if($device_token !=''){
            $top['to'] = $device_token ;
            $top['data'] = array('data' => array('type' => 'Request', 'title' => 'GyanGanga School Notification', 'is_background' => false, 'message' => $message, 'image' => '', 'payload' => array('id' => 1, 'desc' => 'description'), 'timestamp' => date('Y-m-d G:i:s')));
            $result=   $this->sendPushNotificationTwo($top);
              } 

  if($phone) {
         $data1 = array(
            'is_class' => 1,
            'title' => 'Homework',
            'message' => $message1,
            'send_mail' => 0,
            'send_sms' => 'Notification',
            'user_list' => $phone,
            'class_id' => $classes[$i],
            'section_id' => $sections[$i],
            'session_id' => '',
            'sms_status' => 1,
            'department' =>'',
            'msg_type' =>1,
            'other_number'=>'',
          );
    $msgId = $this->messages_model->add($data1); 
  }



      }
     $this->get_freeteacher();  
 }

public function getprint_data()
{
    $tid  = $this->input->post('tid');

    $date = $this->input->post('date');
if ($tid == 0) {
 $qry = "SELECT  proxyteachers.*, staff.name, staff.surname from proxyteachers join staff on proxyteachers.on_behalf =staff.id  where `date` ='". $date."'";
}else{
    $qry = "SELECT  proxyteachers.*, staff.name, staff.surname from proxyteachers join staff on proxyteachers.on_behalf =staff.id  where on_behalf = $tid and `date` ='". $date."'";
}
   
 $result1 = $this->db->query($qry)->result(); 

 if (!empty($result1)) {
       ?> 
  <div id="printthissection">
<?php  if ($tid > 0) {?>
    <h4>    Teacher Substitution Timetable of  <?php  echo ($result1[0]->name.' '.$result1[0]->surname); ?> </h4>
     <?php }else{
  ?>
    <h4>    Teacher Substitution Timetable for  <?=  $date ?> </h4>

  <?php
     } ?>


       <table class="table table-responsive table-bordered" >
         <thead>
             <th>Day</th>
             <th>Date</th>
             <th>Period</th>
             <th>Class</th>
             <th>Section</th>
             <th>Subject </th>
             <th>Teacher</th>
             <th>Teacher Signature</th>
         </thead>
            <?php  
            foreach ($result1 as $key => $value) {   ?>
              <tr>

                
                <td>    <?php echo $value->days; ?>     </td>
                <td>    <?php echo $value->date; ?>     </td>
                <td>    <?php echo $value->periods; ?>  </td>
                <td>    <?php echo $value->classes; ?>  </td>
                <td>    <?php echo $value->sections; ?> </td>
                <td>    <?php echo $value->subjects; ?> </td>
                <td>    <?php echo $this->proxy_model->get_teachername($value->extrateacher)  ; ?> </td>    
                <td>    </td>          
             </tr>

         <?php } ?>
          </table>
  </div>
        
  <?php  
}
}



 
    function view($id) {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Mark List';
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/proxy/timetableShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/proxy/index');
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Mark';
        $data['id'] = $id;
        $mark = $this->mark_model->get($id);
        $data['mark'] = $mark;
        $this->form_validation->set_rules('name', 'Mark', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/proxy/timetableEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">Employee added successfully</div>');
            redirect('admin/proxy/index');
        }
    }


//push notification DND

public function sendPushNotificationTwo($fields) { 

$FIREBASE_API_KEY_TWO = 'AIzaSyCO04Q-DB0SX5JXrsz3gqj4oFAbv3m8MwQ';

$url = 'https://fcm.googleapis.com/fcm/send'; 
$headers = array(
'Authorization: key=' . $FIREBASE_API_KEY_TWO,
'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);
/*if ($result === FALSE) {
die('Curl failed: ' . curl_error($ch));
}*/

// Close connection
curl_close($ch);

return $result;
}


}

?>