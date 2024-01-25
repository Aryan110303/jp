<?php  
 /**
  * 
  */
 class Smslist extends Admin_Controller
 {
 	
 	function __construct()
 	{
 	parent::__construct();

        $this->load->model("homework_model");
        $this->load->model("SmsModel");
        $this->load->model("Messages_model");
        $this->load->model("staff_model");
        $this->load->model("Classteacher_model");
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->role;
 	}

 	public function index()
 	{
 	if (!$this->rbac->hasPrivilege('templates', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
        $data['title'] = 'Add Template';
        $data['title_list'] = 'Template Details';
        $smslist = $this->SmsModel->get();
        $data['smslist'] = $smslist;
        $this->load->view('layout/header');
 		$this->load->view('admin/sms/getallsms',$data);
 		$this->load->view('layout/footer');
 	}

 	public function addTemplate()
 	{
    	$tempName = $this->input->post('addtempName');
    	$tempMsg = $this->input->post('addtempMsg');
    	$tempStatus = $this->input->post('addtempStatus');
    	$insertData = array(
    		'template_name' => $tempName,
    		'template_msg' => $tempMsg,
    		'template_status' => $tempStatus,
    	);

    	$insert = $this->SmsModel->addTemplateModel($insertData);
    	echo json_encode(array("status" => TRUE));
 	}

 	public function getTemplateMsg($id)
    {
        if (!$this->rbac->hasPrivilege('templates', 'can_view')) {
            access_denied();
        }
        $result = $this->SmsModel->getTemplateMsgModel($id);
        $data["result"] = $result;

        echo json_encode($result);
    }

    public function updateTemplate()
    {
    	if (!$this->rbac->hasPrivilege('templates', 'can_edit')) {
            access_denied();
        }
    	// print_r($_POST); exit();
    	$tempId = $this->input->post('tempId');
    	$tempName = $this->input->post('tempName');
    	$tempMsg = $this->input->post('tempMsg');
    	$tempStatus = $this->input->post('tempStatus');
    	$updateData = array(
    		'template_name' => $tempName,
    		'template_msg' => $tempMsg,
    		'template_status' => $tempStatus,
    	);

    	$this->SmsModel->updateTemplateModel($tempId,$updateData);
    	echo json_encode(array("status" => TRUE));

    }

    public function deleteTemplate($id)
    {
         $this->SmsModel->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
	
	public function delete($id)
    {
		$this->Messages_model->remove($id);
		$this->homework_model->deleteBYmsgId($id);
		redirect("admin/smslist/get_reports");
        //echo json_encode(array("status" => TRUE));
    }
	

    function test(){
        echo "test";
    }

    public function get_message_report()
    {
        // if (!$this->rbac->hasPrivilege('templates', 'can_view')) {
        //     access_denied();
        // }
         $result = $this->Messages_model->get_all_messages();
         $data["result"] = $result;
       
        $this->load->view('layout/header');
        $this->load->view('admin/sms/get_report',$data);
        $this->load->view('layout/footer');
    }


    public function msgreport($id)
    {
    // $id = $this->uri->segment(4);
     $details = $this->Classteacher_model->get_data_by_query1("SELECT * from messages where id='$id' ") ;    
     if ($details['type'] == '2') {
       $number = $details['user_list'] ;
         //print_r($number);
         $number = explode(',',$number) ;
     }
     else{
         $class_id =   $details['class_id']; 
         $section_id =  $details['section_id'];  
         $session_id =  $details['session_id'];

         $number = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);
         $number=explode(',',$number['numbers']) ;
//        print_r($number);die;
     }
//        $number['num'] = $number  ;
        $data = array(
            'num' => $number,
            'class_id' => $details['class_id'],
            'section_id' => $details['section_id'],
        );
        //print_r($details); die;
        $data['num_details'] = $details ;
        // print_r($details);die;
        $this->load->view('layout/header');
        $this->load->view('admin/sms/get_sms_number',$data);
        $this->load->view('layout/footer');
       
    }
     public function delete_msgreport($id){
           $this->db->where('id',$id);
         if($this->db->delete('messages')){
           $this->session->set_flashdata('message', 'Deleted Successfully.');
             redirect('admin/smslist/get_reports');
         }else{
            $this->session- a('message', 'Sorry Try Again later');
            redirect('admin/smslist/get_reports');
         }

     }

      public function all_message()
              
      {
        $abcd=  $this->uri->segment(4);
        $abcd= str_replace('%20','',$abcd);    
        $number = explode(',', $abcd) ;
        $number = $number[0]; 
        $n = $number;
     if (!empty($number)) {

        $msg2 = '';
           $all_number = $this->Classteacher_model->get_data_by_query("SELECT * from student_all_numbers where numbers LIKE '%".$number."%'  ");
     
            foreach ($all_number as $value) {
            $msg2[] = $this->Classteacher_model->get_data_by_query("SELECT * from messages where class_id = '".$value['class_id']."' and section_id = '".$value['section_id']."' and session_id ='".$value['session_id']."'  ") ;
      
            }
               $msg['msg1'] = $msg2[0];
        
                $msg['msg']  = $this->Classteacher_model->get_data_by_query("SELECT * FROM messages  WHERE `user_list` LIKE '%".$number."%' ");

    if(empty($msg['msg'])  && empty($msg['msg1'])){
       
        $number = $number[1];
                  
           $all_number = $this->Classteacher_model->get_data_by_query("SELECT * from student_all_numbers where numbers LIKE '%".$number."%'  ");
           
                  foreach ($all_number as $value) {
                  $msg2[] = $this->Classteacher_model->get_data_by_query("SELECT * from messages where class_id = '".$value['class_id']."' and section_id = '".$value['section_id']."' and session_id ='".$value['session_id']."'  ") ;
            
                  }
                     $msg['msg1'] = $msg2[0];                  
                      $msg['msg']  = $this->Classteacher_model->get_data_by_query("SELECT * FROM messages  WHERE `user_list` LIKE '%".$number."%' ");
                         }
                $msg['name'] = $this->Classteacher_model->get_data_by_query1("SELECT * from students where mobileno like '%$n%' or guardian_phone like '%$n%'");
             //    print_r($msg['name']); die;
        $this->load->view('layout/header');
        $this->load->view('admin/sms/view_all',$msg);
        $this->load->view('layout/footer');
        }
         else{
        
            redirect("admin/smslist/get_reports");
         }
       
    }

     public function search_reports(){    
//         echo 'lulululululul';die;
        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
             $this->load->view('admin/sms/search_report',$data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text, $carray);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }
             $this->load->view('layout/header',$data);
             $this->load->view('admin/sms/search_report',$data);
             $this->load->view('layout/footer',$data);
        }
        
  }



   

 }
?>