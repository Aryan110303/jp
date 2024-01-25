<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receive extends Admin_Controller {

    function __construct() {
        parent::__construct();
          $this->load->model('Examschedule_model');
        $this->load->model('Receive_model');
           $this->load->model('Student_model');
        
        
        $this->load->library('form_validation');
       // $this->check_auth();
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'Download Center');
        $this->session->set_userdata('sub_menu', 'download/gatepass');
        $data = array(

            'rec' => $this->Receive_model->get_all(),
        );

         $this->load->view('layout/header', $data);
        $this->load->view('receive/receive_list', $data);
        $this->load->view('layout/footer', $data);        
    }

    public function create() {

        $data = array();
        $this->form_validation->set_rules('image', 'reciver Image', 'trim|required');
//        $this->form_validation->set_rules('customer_mobileno', 'Mobile Number', 'trim|required|callback_code_check');

        if ($this->form_validation->run() == FALSE) {

            $data = array(
                'button' => 'Create ',
                'action' => site_url('receive/create'),
                'id' => set_value('id'),
                'child_id' => set_value('child_id'),
                'name' => set_value('name'),
                'mobile_no' => set_value('mobile_no'),
                'relation' => set_value('relation'),
                'image' => set_value('image'),
                 'reason' => set_value('reason'),
            );
//            ******************************************************************
         
            
//          ************************************************************************  
           
          $this->load->view('layout/header', $data);
        $this->load->view('receive/receive_add', $data);
        $this->load->view('layout/footer', $data);
   } else {
            $data = array(
                'parent_id' => $this->input->post('parent_id'),
                'mobile_no' => $this->input->post('mobile_no'),
                'relation' => $this->input->post('relation'),
                'reason' => $this->input->post('reason'),
                'student_id' => $this->input->post('student_id'),
                'class' => $this->input->post('class_id'),
                'section' => $this->input->post('section_id'),
                'receiver_name' => $this->input->post('receiver_name'),
                'father_name' => $this->input->post('father_id'),
                'mother_name' => $this->input->post('mother_id'),
                'student_name' => $this->input->post('student_name'),
                 'guardian_image' => $this->input->post('guardian_image'),
                 'student_image' => $this->input->post('student_image'),                
                 'datetime' => date('d/m/y H:i:s')
                


            );
            
               
    $img = $_POST['image'];
    $folderPath = "uploads/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64); 
    $data['image']=$folderPath.$fileName;
//    print_r($fileName);
//       die;     
            
            
            
            $id = $this->Receive_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url("receive"));
        }
        //redirect(base_url()."index.php/musician/update/".$insert_id);
    }

    public function get_student($code) {
        $res = $this->Receive_model->get_stu_details($code);
        $data = array();

        $this->load->view('layout/header', $data);
        $this->load->view('receive/receive_add', $data);
        $this->load->view('layout/footer', $data);   
       
    }

    public function update($id) {

        $data = array();


        $this->form_validation->set_rules('child_id', 'child  Name', 'trim|required');
//        $this->form_validation->set_rules('customer_mobileno', 'Item code', 'trim|required|callback_code_check');

        if ($this->form_validation->run() == FALSE) {

            $res = $this->Receive_model->get_by_id($id);
            if ($res) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('receive/update/' . $id),
                    'id' => set_value('id', $res->id),
                    'child_id' => set_value('child_id', $res->child_id),
                    'name' => set_value('name', $res->name),
                    'mobile_no' => set_value('mobile_no', $res->mobile_no),
                    'relation' => set_value('relation', $res->relation),
                     'reason' => set_value('reason', $res->reason),
                    'image' => set_value('image', $res->image),
                );
          $this->load->view('layout/header', $data);
          $this->load->view('receive/receive_add', $data);
          $this->load->view('layout/footer', $data);
               
            }
        } else {

            $data = array(
                'id' => $this->input->post('id'),
                'child_id' => $this->input->post('child_id'),
                'name' => $this->input->post('name'),
                'mobile_no' => $this->input->post('mobile_no'),
                'relation' => $this->input->post('relation'),
                 'reason' => $this->input->post('reason'),
                'photo' => $this->input->post('photo'),
            );

            $result = $this->Receive_model->update($data);

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
            // exit();

            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('receive'));
        }
        //redirect(base_url()."index.php/musician/update/".$insert_id);
    }

    public function read($id) {
        $data = array(
            'res' => $this->Receive_model->get_by_id($id)
        );
//         print_r($data);
//         die;
          $this->load->view('layout/header', $data);
        $this->load->view('receive/print_receive', $data);
        $this->load->view('layout/footer', $data);
        
    }

    public function delete($id) {
        $res = $this->Receive_model->get_by_id($id);
        // print_r($res);
        // die;
        if ($res) {
            $this->Receive_model->delete($id);
            $this->session->set_flashdata('message', 'record delete successful');
            redirect(base_url("receive"));
        } else {
            $this->session->set_flashdata('message', 'record not found');
            redirect(base_url("receive"));
        }
    }

    public function check_auth() {
        $login_type = $this->session->userdata('type');
        if (!$login_type == '1')
            redirect(base_url('Login/'));
        return;
    }

    public function getdetails(){
     $number = $this->input->post('number');
     $result = $this->Receive_model->getdetails($number);
     //print_r($result); die;
     echo json_encode($result);
    }
    

/* admint card starts from here */
 public function admitcard(){
        $this->session->set_userdata('top_menu', 'Download Center');
        $this->session->set_userdata('sub_menu', 'download/admitcard');
        $exam_result = $this->exam_model->get();
        $data['examlist'] = $exam_result;
        $this->load->view('layout/header', $data);
        $this->load->view('receive/exam_list', $data);
        $this->load->view('layout/footer', $data);
 }

 



    public function exam_classes($id) {
        if (!$this->rbac->hasPrivilege('exam', 'can_view')) {
            access_denied();
        }
      
        $data['title'] = 'list of  Alloted';
        $exam = $this->exam_model->get($id);
        $data['exam'] = $exam;
        $classsectionList = $this->examschedule_model->getclassandsectionbyexam($id);
       // print_r($classsectionList); die;
        $array = array();
        foreach ($classsectionList as $key => $value) {
            $s = array();
            $exam_id = $value['exam_id'];
            $class_id = $value['class_id'];
            $section_id = $value['section_id'];
            $result_prepare = $this->examresult_model->checkexamresultpreparebyexam($exam_id, $class_id, $section_id);
            $s['exam_id'] = $exam_id;
            $s['class_id'] = $class_id;
            $s['section_id'] = $section_id;
            $s['class'] = $value['class'];
            $s['section'] = $value['section'];
            if ($result_prepare) {
                $s['result_prepare'] = "yes";
            } else {
                $s['result_prepare'] = "no";
            }
            $array[] = $s;
        }
        $data['classsectionList'] = $array;
        $this->load->view('layout/header');
        $this->load->view('receive/examclasslist', $data);
        $this->load->view('layout/footer');
    }
 public function studentdetails(){
       $id =  $this->input->post('id'); 

      $data = $this->Examschedule_model->getstudentdetails($id);
     // print_r($data);die;
      echo json_encode($data);
 }

 public function studentadmitcard($exam,$class,$section){
      $data['exam'] = $exam ;
      $data['class'] = $class ;
      $data['section'] = $section ;
      $result = $this->Examschedule_model->getDetailbyClsandSection($class,$section,$exam);
     // print_r($this->db->last_query());die;
      $student = $this->Student_model->searchByClassSection($class,$section);
       $html = '' ;

       $romans = array(        
        'XII' => 12,
        'XI' => 11,
        'X' => 10,
        'IX' => 9,
        'VIII'=>8,
        'VII'=>7,
        'VI'=>6,
        'V' => 5,
        'IV' => 4,
        'III' =>3,
        'II' =>2,
        'I' => 1,
    );
    $rol = 0;
    $roman = $student[0]['class'] ;  
    foreach($romans as $key => $value) {

  if($roman == $key){
     $rol = $value;

  }

    }
   
    $rollnumber1  =($rol*1000) ;
    
 

      foreach($student as $stu){
        $rollnumber = '';
        $rollnumber  = $rollnumber1+$stu['roll_no'] ;

        $path= $rollnumber.'-'.$stu['firstname'].' '.$stu['lastname']; 
        $PNG_TEMP_DIR = FCPATH.DIRECTORY_SEPARATOR.'/qrcode/'.DIRECTORY_SEPARATOR;
        $PNG_WEB_DIR = 'qrcode/';
        require_once( __DIR__.'/../third_party/qrcode/qrcode/qrlib.php' );

        $errorCorrectionLevel = 'S'; 
        $matrixPointSize = 6;
        $datum=$path;
        $filename = $PNG_TEMP_DIR.md5($datum.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($datum, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
        //echo $PNG_WEB_DIR.basename($filename);
        $map_qrimg=$PNG_WEB_DIR.basename($filename);

        $html.= '<div style="font-size:0px; margin-top:15px; min-height:500px  
       "  >
        
          <div style=" background-image: url("http://softwares.centralacademyjabalpur.com/uploads/background-img.png") ;backgroun-size:100% 100%; background-repeat: no-repeat ; z-index:99; background-position:center ; opacity:0.7; height:100%; width: 100%;">' ;
        $html.='<div style="text-align: center; border-bottom: 1px solid #000;">
        <div><img src="'.base_url('uploads/logo2.jpg').'" alt="logo" style="width:50px; height:50px;"></div>
        <div style="margin: 5px 0px;font-size: 20px;font-weight: 600;color: #043368;">CENTRAL ACADEMY SR. SEC. SCHOOL</div>
        <div style="font-size: 13px;"> MATHURA VIHAR, VIJAY NAGAR , JABALPUR (M.P.)</div>
        <div style="margin: 5px 0px;font-size: 13px;font-weight: 600;color: #043368;"> ADMIT CARD </div>
    </div>';
    $html.='<div style="padding: 5px 10px;">
    <div style="text-align: center;font-size:12px;font-weight: bold;margin:2px 0px;"> PERSONAL INFORMATION</div>
                        
    <table style="width: 100%; " cellpadding="0">
        <tbody>
        <tr>
        <td style="width:44%">
                <table style="text-transform: uppercase; font-size:15px!important;">
                    <tbody>
                          
                    <tr><td style="font-weight: bold"> Schlor No. &nbsp;</td><td>'.$stu['admission_no'].'</td></tr>
                    <tr><td style="font-weight: bold"> Roll No. &nbsp;</td><td>'.$rollnumber.'</td></tr>
                    <tr><td style="font-weight: bold"> Name &nbsp;</td><td>'.$stu['firstname'].' '.$stu['lastname'].'</td></tr>
                    <tr><td style="font-weight: bold">Father`s  Name &nbsp;</td><td>'.$stu['father_name'].' </td></tr>
                    <tr><td style="font-weight: bold"> Class  : &nbsp; </td><td> '.$stu['class'].'</td></tr>
                    <tr><td style="font-weight: bold">  Section:&nbsp; </td><td>'.$stu['section'].' </td></tr>

              
                    </tbody>
                </table>
            </td>
            <td style="width:30%">
            <img  src="'. base_url($map_qrimg) .'" width="150px;" height="150px;">
            </td>

            <td style=" width:20%">
           
                <table >
                    <tbody>
                    <tr>
                        <td style="width: 60px;text-align: center;border: 1px solid #464646;height: 40px;"> <img width="90px"  height="110px"  src="'.base_url().$stu['image'].'" alt="student pic" ></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
<br><br>
<div class="row">  
<table cellpadding="5" style="border-collapse: collapse;text-align: center;width:100% ; font-size:12px; "  border="1"  >
        <thead>
            <tr><th class="text-center" >
                Sno
               </th>
               <th class="text-center">
                Subject
               </th>
               <th class="text-center">
                Date
               </th>
        </tr>
        </thead>
        <tbody>';


      
        if (!empty($result)) {
            $i = 1 ;
                     foreach ($result as $key => $value) {
                        $html.=' <tr><td>'.$i++ .'</td><td>'.$value['name'].'</td><td>'.$value['date_of_exam'].'</td></tr>';
                     }
                     } 

        $html.=' </tbody></table><div style="font-size: 12px;margin-top:50px;bottom: 50px;right: 10px"> EXAMINATION CONTROLLER’S SIGN <span style="margin-left:60%;"> PRINCIPAL’S SIGN</span>
          </div></div></div></div></div></div>';

    
    }



 echo $html ; die;
      $pdfFilePath = "output_pdf_name.pdf";
      //load mPDF library
      $this->load->library('m_pdf');
      $this->m_pdf->pdf->SetDisplayMode('real');
      $this->m_pdf->pdf->use_kwt = true;
     //generate the PDF from the given html
      $this->m_pdf->pdf->WriteHTML('<style>@page {
        background: url(http://softwares.centralacademyjabalpur.com/uploads/background-img.png) no-repeat 0 0;
        background-image-resize: 6;
         }</style>' . $html);
      //$this->m_pdf->pdf->WriteHTML($html);
      //download it.
      $this->m_pdf->pdf->Output($pdfFilePath,'D');  
  //print_r($data['student']); die;
        // $this->load->view('layout/header');
        // $this->load->view('receive/studentlistadmitcard', $data);
        // $this->load->view('layout/footer');

 }
/*admit card end's here */



/*axams result start from here */

 public function result(){
          $id = 1006 ;
             $student = $this->student_model->get($id);
             $gradeList = $this->grade_model->get();            
             $studentSession = $this->student_model->getStudentSession($id);
             $examList = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
           
             $data['examSchedule'] = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student['id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student['id']);
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
 }


 
/*-----interview date management start-----*/
function interviewdate($value='')
{  
   $data['rec'] = $this->Receive_model->interview_data();
   $this->load->view('layout/header');
   $this->load->view('receive/interview_list', $data);
   $this->load->view('layout/footer');
}


function add_interview($value='')
{  
   $data['action']  = 'create_interview';
   $this->load->view('layout/header');
   $this->load->view('receive/addinterview_list', $data);
   $this->load->view('layout/footer');
} 

function create_interview($value='')
{  
 $data['description']=$this->input->post('desc');
 $data['int_date']= date('Y-m-d',strtotime($this->input->post('int_date')));
 $data['count']=$this->input->post('int_count');
 $this->db->insert('interview_date', $data);
$id = $this->db->insert_id();
if ($id > 0 ) {
     $this->session->set_flashdata('message', 'Add Record Success');
           redirect(base_url('receive/interviewdate'));
}else{
    $this->session->set_flashdata('message', 'Failed to Add Data');
           redirect(base_url('receive/interviewdate'));

}

}


public function deleteinterview($id) 
   {        
      $this ->db-> where('id', $id);
      $this ->db-> delete('interview_date');
     $this->session->set_flashdata('message', 'record delete successful');
      redirect(base_url("receive/interviewdate"));         
      }


      function assigned_studentlist($value)
      {  
         $data['rec'] = $this->Receive_model->assigned_studentlist($value);  
         $this->load->view('layout/header');
         $this->load->view('receive/assigned_interview_list', $data);
         $this->load->view('layout/footer');
      }
     
     
       public function updateresult($val,$id,$date)
        {
          $this->Receive_model->updaterecord($val,$id);
           redirect(base_url("receive/assigned_studentlist/").$date);  
        } 
     






/*-----------ends here-------------------*/









/* result ends here */
}


