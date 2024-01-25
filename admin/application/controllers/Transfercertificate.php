<?php  
 /**
  * 
  */
 class Transfercertificate extends Admin_Controller
 {
 	
 	function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');        
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->blood_group = $this->config->item('bloodgroup');
        $this->role;
    }

    function search() {

        if (!$this->rbac->hasPrivilege('transfer_certificate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transfer Certificate');
        $this->session->set_userdata('sub_menu', 'transfercertificate/search');
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
            $this->load->view('admin/transfer_certificate/studentSearch', $data);
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
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfer_certificate/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function tcform($id)
    {
        $data['result'] = $this->student_model->get($id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transfer_certificate/tcform');
        $this->load->view('layout/footer', $data);
        // echo "<pre>"; print_r($data['result']); echo "</pre>";
    }

    public function create()
    {
        // print_r($_POST); exit();
        $id = $this->input->post('studentId');
        $reason = $this->input->post('reason');
        $checkFee = $this->input->post('checkFee');
        $treasure = "NA";
        $cancelName = $this->input->post('cancelName');
        $teacher = "NA";
        $bookReturn = $this->input->post('bookReturn');
        $librarian = "NA";
        $principal = $this->input->post('principal');
        if (isset($checkFee)) {
            $treasure = $this->input->post('treasure');
        }
        if (isset($cancelName)) {
            $teacher = $this->input->post('teacher');
        }
        if (isset($bookReturn)) {
            $librarian = $this->input->post('librarian');
        }

        $data = array(
            'student_id' => $id,
            'leaving_reason' => $reason,
            'fee_paid' => $treasure,
            'canceled_name' => $teacher,
            'books_returned' => $librarian,
            'created_at' => date('Y-m-d'),
            'status' => 0,
        );

        $result = $this->Transfercertificate_model->add($data);
        if (!empty($result)) {
            redirect('transfercertificate/tcgenerate/'.$result);
        }
    }

    public function tcgenerate($tcid)
    {

        $data['result'] = $this->Transfercertificate_model->getTc($tcid);
        // $studentId = $data['result'][0]['sId'];
        // $data['getAttandance'] = $this->Transfercertificate_model->getAttandance($studentId);
        $dob = $data['result'][0]['dob'];
        $day = date('d',strtotime($data['result'][0]['dob']));
        $year = date('Y',strtotime($data['result'][0]['dob']));
        $dayinword = $this->convert_day($day);
        $yearinword = $this->convert_year($year);
        $data['dobinword'] = $dayinword ."&nbsp;". date('F',strtotime($data['result'][0]['dob'])) . "&nbsp;" .$yearinword;
        // echo $data['dobinword'];
        // print_r($number); exit();
        
        // print_r($dobDay); die();
        $this->load->view('admin/transfer_certificate/generatetc',$data);
    }


    function convert_year($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) {
            $result .= $this->convert_year($giga) .  "Million";
        }
        if ($kilo) {
            $result .= (empty($result) ? "" : " ") .$this->convert_year($kilo) . " Thousand";
        }
        if ($hecto) {
            $result .= (empty($result) ? "" : " ") .$this->convert_year($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) {
                $result .= " and ";
            }
            if ($deca < 2) {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) {
            $result = "zero";
        }
        return $result;
    }

    function convert_day($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) {
            $result .= $this->convert_day($giga) .  "Million";
        }
        if ($kilo) {
            $result .= (empty($result) ? "" : " ") .$this->convert_day($kilo) . " Thousand";
        }
        if ($hecto) {
            $result .= (empty($result) ? "" : " ") .$this->convert_day($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) {
                $result .= " and ";
            }
            if ($deca < 2) {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) {
            $result = "zero";
        }
        return $result;
    }

    public function generatecertificate()
    {
        if (!$this->rbac->hasPrivilege('transfer_certificate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transfer Certificate');
        $this->session->set_userdata('sub_menu', 'admin/transfer_certificate/generatecertificate');
        //$this->data['certificateList'] = $this->Generatecertificate_model->certificateList();
        $certificateList = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transfer_certificate/generatecertificate', $data);
        $this->load->view('layout/footer', $data);
    }

    function searchlist() {
        $this->session->set_userdata('top_menu', 'Transfer Certificate');
        $this->session->set_userdata('sub_menu', 'admin/transfer_certificate/generatecertificate');
        //$data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        // $certificateList = $this->Certificate_model->getstudentcertificate();
        // $data['certificateList'] = $certificateList;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfer_certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            // $certificate = $this->input->post('certificate_id');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');

                // $this->form_validation->set_rules('certificate_id', 'Certificate', 'trim|required|xss_clean');




                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    $data['searchby'] = "filter";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    // $certificate = $this->input->post('certificate_id');
                    $resultlist = $this->Transfercertificate_model->getcertificatelist($class,$section);
                    // print_r($certificateResult); die();
                    // $data['certificateResult'] = $certificateResult;
                    // $resultlist = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist'] = $resultlist;
                    $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfer_certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        }
    }
 }
?>