<?php

class Complaint extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model("homework_model");
        $this->load->model("staff_model");
        $this->load->model("classteacher_model");
	$this->load->model("Complaint2_model");
	$this->load->model("Department_model");
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->role;
    }

    public function index() {
       /* if (!$this->rbac->hasPrivilege('complaint', 'can_view')) {
            access_denied();
        }
*/
        $this->session->set_userdata('top_menu', 'Homework');
        $this->session->set_userdata('sub_menu', 'complaint');
        $data["title"] = "Create Complaint";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['subject_id'] = "";
        $homeworklist = $this->Complaint2_model->getall_message();

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {

            $class_id = $this->input->post("class_id");
            $section_id = $this->input->post("section_id");
            $subject_id = $this->input->post("subject_id");
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['subject_id'] = $subject_id;
            $homeworklist = $this->homework_model->search_homework($class_id, $section_id, $subject_id);
        }
        $data["homeworklist"] = $homeworklist;
		// $data['departmentList']=$this->Department_model->get_only_3();
       /* foreach ($data["homeworklist"] as $key => $value) {
            $report = $this->homework_model->getEvaluationReport($value["id"]);

            $data["homeworklist"][$key]["report"] = $report;
            $create_data = $this->staff_model->get($value["created_by"]);
            $eval_data = $this->staff_model->get($value["evaluated_by"]);
            $created_by = $create_data["name"] . " " . $create_data["surname"];
            $evaluated_by = $eval_data["name"] . " " . $create_data["surname"];
            $data["homeworklist"][$key]["created_by"] = $created_by;
            $data["homeworklist"][$key]["evaluated_by"] = $evaluated_by;
        }*/
        $data['templateList'] = $this->homework_model->getTemplateList();
        $this->load->view("layout/header", $data);
        $this->load->view("complaint/complaintlist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function create() {
        if (!$this->rbac->hasPrivilege('homework', 'can_add')) {
            access_denied();
        }
        $data["title"] = "Create Homework";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $userdata = $this->customlib->getUserData();
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_phone[]', 'Students', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');


        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'class_id' => form_error('class_id'),
                'section_id' => form_error('section_id'),
                //'subject_id' => form_error('subject_id'),
                'guardian_phone[]' => form_error('guardian_phone[]'),
                'description' => form_error('description'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {


            $data = array('class_id' => $this->input->post("class_id"),
                'section_id' => $this->input->post("section_id"),
                /*''subject_id' => $this->input->post("subject_id"),
                homework_date' => date("Y-m-d", strtotime($this->input->post("homework_date"))),
                'submit_date' => date("Y-m-d", strtotime($this->input->post("submit_date"))),
                'staff_id' => $userdata["id"],
                'subject_id' => $this->input->post("subject_id"),*/
                'description' => $this->input->post("description"),
                'create_date' => date("Y-m-d"),
                'created_by' => $userdata["id"],
                'evaluated_by' => ''
            );

//*********************************************************************************
			$departmentlisting = $this->input->post("department");
			$dep_list='';
			$other_number='';
			$arr=array();
			if (!empty($departmentlisting)) { foreach ($departmentlisting as $deplisting_key => $deplisting_value) {
                        if($deplisting_value)
                            {
                             $deparr[]=$deplisting_value;
                                $dep_list = implode(',',$deparr); 
                            }
                       
                    }}
					
					if(!empty($deparr)){
					foreach($deparr as $dep_id){
					$staffList=$this->staff_model->getBYdeparment($dep_id);
					if(!empty($staffList)){
					foreach($staffList as $staff_info){
					$other_number.=','.$staff_info->contact_no;
					}}
					
					}
					
					}
            $section= $this->input->post("section_id");
            $class_id=$this->input->post("class_id");
            $message=$this->input->post("description");
            $user_array = array();
            // foreach ($section as $section_key => $section_value) {
                $userlisting = $this->input->post("guardian_phone");
                 // print_r($userlisting); exit();
                if (!empty($userlisting)) { foreach ($userlisting as $userlisting_key => $userlisting_value) {
                        if($userlisting_value)
                            {
                             $arr[]=$userlisting_value;
                                $user_array = implode(',',$arr); 
                            }
                       
                    }
                    $data1 = array(
                            'is_class' => 1,
                            'title' => 'Complaint',
                            'message' => $message,
                            'send_mail' => 0,
                            'send_sms' => 'sms',
                            'user_list' => $user_array,
                            'sms_status' => 0,
							'type'=>2,
							'department' =>$dep_list,
							'other_number'=>$other_number
                        );
                        $msgId = $this->messages_model->add($data1);}
            // }
            // $data1 = array(
            //     'is_class' => 1,
            //     'title' => 'Homework',
            //     'message' => $message,
            //     'send_mail' => 0,
            //     'send_sms' => 'sms',
            //     'user_list' => json_encode($user_array),
            //     'sms_status' => 0,
            // );
            // $this->messages_model->add($data1);
            /*if (!empty($user_array)) {
               // if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {

                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], strip_tags($message));
                        }
                    }
               // }
            }*/

//*********************************************************************************
           
            $msg = "Complaint Created Successfully";
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }
	

     public function create_multiple() {

        if (!$this->rbac->hasPrivilege('homework', 'can_add')) {
            access_denied();
        }
        $data["title"] = "Create Homework Multiple";  
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $userdata = $this->customlib->getUserData();
        $session = $this->setting_model->getCurrentSession();
      //$this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'class_id' => form_error('class_id'),              
                'description' => form_error('description'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array('class_id' => $this->input->post("class_id"),               
                'description' => $this->input->post("description"),
                'create_date' => date("Y-m-d"),
                'created_by' => $userdata["id"],
                'evaluated_by' => ''
            );
//*********************************************************************************
            $class_id=$this->input->post("class_id");
            //print_r($class_id);
            $message=$this->input->post("description");
            $user_array = array();
            // foreach ($section as $section_key => $section_value) {
            foreach ($class_id as $value) {
                $userlisting = $this->classteacher_model->get_data_by_query("SELECT * from student_all_numbers where class_id = '" . $value . "' AND session_id='" . $session . "'");
//              print_r($userlisting);die;
                foreach ($userlisting as $msg_value) {

                    $data1 = array(
                        'is_class' => 1,
                        'title' => 'All class section',
                        'message' => $message,
                        'send_mail' => 0,
                        'send_sms' => 'sms',
                        'user_list' => 0,
                        'class_id' => $msg_value['class_id'],
                        'section_id' => $msg_value['section_id'],
                        'session_id' => $msg_value['session_id'],
                        'sms_status' => 0,
                        'department' => 0,
                        'msg_type' => 0,
                        'other_number' => 0
                    );
                    $msgId = $this->messages_model->add($data1);
                }
            }

            $msg = "Message Created Successfully";
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }
    
	function getStudentByClassandSection() {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        //$data = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
		
		$data = $this->student_model->searchByClassSection($class_id, $section_id);
        echo json_encode($data);
    }

	
	

    public function getRecord($id) {
        if (!$this->rbac->hasPrivilege('homework', 'can_edit')) {
            access_denied();
        }
        $result = $this->homework_model->get($id);
        $data["result"] = $result;

        echo json_encode($result);
    }

    public function edit() {

        if (!$this->rbac->hasPrivilege('homework', 'can_edit')) {
            access_denied();
        }
        $id = $this->input->post("homeworkid");
        $data["title"] = "Edit Homework";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $result = $this->homework_model->get($id);
        $data["result"] = $result;
        $data['class_id'] = $result["class_id"];
        $data['section_id'] = $result["section_id"];
        $data['subject_id'] = $result["subject_id"];
        $data["id"] = $id;
        $userdata = $this->customlib->getUserData();
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('homework_date', 'Homework Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');


        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'class_id' => form_error('class_id'),
                'section_id' => form_error('section_id'),
                'subject_id' => form_error('subject_id'),
                'homework_date' => form_error('homework_date'),
                'description' => form_error('description'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                $uploaddir = './uploads/homework/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["userfile"]["name"]);
                $document = basename($_FILES['userfile']['name']);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploaddir . $img_name);
            } else {

                $document = $this->input->post("document");
            }
            $data = array('id' => $id,
                'class_id' => $this->input->post("class_id"),
                'section_id' => $this->input->post("section_id"),
                'subject_id' => $this->input->post("subject_id"),
                'homework_date' => date("Y-m-d", strtotime($this->input->post("homework_date"))),
                'submit_date' => date("Y-m-d", strtotime($this->input->post("submit_date"))),
                'staff_id' => $userdata["id"],
                'subject_id' => $this->input->post("subject_id"),
                'description' => $this->input->post("description"),
                'create_date' => date("Y-m-d"),
                'document' => $document
            );

            $this->homework_model->add($data);
            $msg = "Homework Updated Successfully";
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('homework', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->homework_model->delete($id);
            redirect("homework");
        }
    }

    public function download($id, $doc) {
        $this->load->helper('download');
        $name = $this->uri->segment(4);
        $ext = explode(".", $name);
        $filepath = "./uploads/homework/" . $id . "." . $ext[1];
        $data = file_get_contents($filepath);
        force_download($name, $data);
    }

    public function evaluation($id) {
        if (!$this->rbac->hasPrivilege('homework_evaluation', 'can_view')) {
            access_denied();
        }
        $data["title"] = "Homework Evaluation";
        $data["created_by"] = "";
        $data["evaluated_by"] = "";

        $result = $this->homework_model->getRecord($id);
        $class_id = $result["class_id"];
        $section_id = $result["section_id"];
        $studentlist = $this->homework_model->getStudents($class_id, $section_id);
        $data["studentlist"] = $studentlist;
        $data["result"] = $result;
        $report = $this->homework_model->getEvaluationReport($id);
        $data["report"] = $report;

        if (!empty($result)) {

            $create_data = $this->staff_model->get($result["created_by"]);
            $eval_data = $this->staff_model->get($result["evaluated_by"]);
            $created_by = $create_data["name"] . " " . $create_data["surname"];
            $evaluated_by = $eval_data["name"] . " " . $create_data["surname"];
            $data["created_by"] = $created_by;
            $data["evaluated_by"] = $evaluated_by;
        }


        $this->load->view("homework/evaluation_modal", $data);
    }

    public function add_evaluation() {
        if (!$this->rbac->hasPrivilege('homework_evaluation', 'can_add')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
        $this->form_validation->set_rules('evaluation_date', 'Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('evaluation_student_list[]', 'Students', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'evaluation_date' => form_error('evaluation_date'),
                'evaluation_student_list[]' => form_error('evaluation_student_list[]'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $students = $this->input->post("evaluation_student_list");

            $prev_students = $this->input->post("evalid");
            if (!empty($prev_students)) {
                $this->homework_model->delete_evaluation($prev_students);
            }

            foreach ($students as $key => $value) {

                $data = array('homework_id' => $this->input->post("homework_id"),
                    'student_id' => $value,
                    'date' => date("Y-m-d", strtotime($this->input->post("evaluation_date"))),
                    'status' => 'Complete'
                );

                $this->homework_model->addEvaluation($data);
            }

            $homework_data = array('id' => $this->input->post("homework_id"), 'evaluated_by' => $userdata["id"]);

            $this->homework_model->add($homework_data);
            $msg = "Homework Evaluation completed Successfully.";
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function evaluation_report() {
        if (!$this->rbac->hasPrivilege('homework_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Homework');
        $this->session->set_userdata('sub_menu', 'homework/evaluation_report');
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['subject_id'] = "";

        $result = $this->homework_model->searchHomeworkEvaluation($class_id = '', $section_id = '', $subject_id = '');
        $data["resultlist"] = $result;

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {

            $class_id = $this->input->post("class_id");
            $section_id = $this->input->post("section_id");
            $subject_id = $this->input->post("subject_id");

            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['subject_id'] = $subject_id;

            $result = $this->homework_model->searchHomeworkEvaluation($class_id, $section_id, $subject_id);
            $data["resultlist"] = $result;
            $data["title"] = "Evaluation Report";
        }
        foreach ($result as $key => $value) {

            $report[] = $this->count_percentage($value["id"], $value["class_id"], $value["section_id"]);
            $data["resultlist"][$key]["report"] = $report;
        }


        $this->load->view("layout/header");
        $this->load->view("homework/homework_evaluation", $data);
        $this->load->view("layout/footer");
    }

    function getreport($id = 1) {

        $result = $this->homework_model->getEvaluationReport($id);
        if (!empty($result)) {
            $data["result"] = $result;
            $class_id = $result[0]["class_id"];
            $section_id = $result[0]["section_id"];
            $create_data = $this->staff_model->get($result[0]["created_by"]);
            $eval_data = $this->staff_model->get($result[0]["evaluated_by"]);
            $created_by = $create_data["name"] . " " . $create_data["surname"];
            $evaluated_by = $eval_data["name"] . " " . $create_data["surname"];
            $data["created_by"] = $created_by;
            $data["evaluated_by"] = $evaluated_by;
            $studentlist = $this->homework_model->getStudents($class_id, $section_id);
            $data["studentlist"] = $studentlist;


            $this->load->view("homework/evaluation_report", $data);
        } else {
            echo "<div class='row'><div class='col-md-12'><br/><div class='alert alert-info'>No Record Found</div></div></div>";
        }
    }

    function count_percentage($id, $class_id, $section_id) {

        $count_students = $this->homework_model->count_students($class_id, $section_id);
        $count_evalstudents = $this->homework_model->count_evalstudents($id, $class_id, $section_id);
        $total_students = $count_students;
        $total_evalstudents = $count_evalstudents;
        $count_percentage = ($total_evalstudents / $total_students) * 100;
        $data["total"] = $total_students;
        $data["completed"] = $total_evalstudents;
        $data["percentage"] = round($count_percentage, 2);
        return $data;
    }

    public function getClass() {

        $class = $this->class_model->get();

        echo json_encode($class);
    }
    public function getTemplateMsg($id)
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_edit')) {
            access_denied();
        }
        $result = $this->homework_model->getTemplateMsgModel($id);
        $data["result"] = $result;

        echo json_encode($result);
    }

}

?>