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
        $this->load->model("classteacher_model");
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


    public function msgreport()
    {
     $id = $this->uri->segment(4);
     $details = $this->classteacher_model->get_data_by_query("SELECT * from messages where id='$id' ") ;
     if ($details[0]['type'] == '2') {
       $number = $details[0]['user_list'] ;
         //print_r($number);
         $number = explode(',',$number) ;
     }
     else{
         $class_id =   $details[0]['class_id']; 
         $section_id =  $details[0]['section_id'];  
         $session_id =  $details[0]['session_id'];

        $number = $this->messages_model->get_all_student_no($class_id, $section_id, $session_id);
         $number=explode(',',$number['numbers']) ;
       // print_r($number);
     }
        $number['num'] = $number  ;
        //print_r($number); die;
        $this->load->view('layout/header');
        $this->load->view('admin/sms/get_sms_number',$number);
        $this->load->view('layout/footer');
       
    }

 }
?>