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

      public function all_message($abcd)
      {
        
        $number = $abcd;

     if (!empty($number)) {

          
        $msg['name'] = $this->Classteacher_model->get_data_by_query("SELECT firstname,lastname from students where mobileno LIKE '%".$number."%' or guardian_phone  LIKE '%".$number."%' ");
           $all_number = $this->Classteacher_model->get_data_by_query("SELECT * from student_all_numbers where numbers LIKE '%".$number."%'  ");
     /*      print_r($all_number); die;*/
  foreach ($all_number as $value) {
       $msg2[] = $this->Classteacher_model->get_data_by_query("SELECT * from messages where class_id = '".$value['class_id']."' and section_id = '".$value['section_id']."' and session_id ='".$value['session_id']."'  ") ;
      
 }
               $msg['msg1'] = $msg2[0];
  
            //  $number = '9425391656';
                $msg['msg']  = $this->Classteacher_model->get_data_by_query("SELECT * FROM messages  WHERE `user_list` LIKE '%".$number."%' ");

                
                 //  foreach ($all_number2 as $key => $value) {
                 //  $user_list2 = $value['numbers'] ;
                 //     $num_array2= explode(',', $user_list2);
                 // }

        
        $this->load->view('layout/header');
        $this->load->view('admin/sms/view_all',$msg);
        $this->load->view('layout/footer');
       

        } else{
        
             redirect($this->get_message_report()) ;
         }
       
    }


   

 }
?>