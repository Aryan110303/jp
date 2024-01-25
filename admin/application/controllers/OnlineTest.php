 <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OnlineTest extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->load->model("onlinetest_model");
        $this->load->model('transport_custom_model');
        $this->blood_group = $this->config->item('bloodgroup');
        $this->role;
        $this->load->library('excel');
        $this->session_id = $this->setting_model->getCurrentSession();
    }
  

    function index() {
        $this->session->set_userdata('top_menu', 'Online Exam');
        $this->session->set_userdata('sub_menu', 'OnlineTest');
        $data['title'] = 'Online Exam';
//        $date = date('Y-m-d');
        $exams = $this->onlinetest_model->get();
        $data['exams'] = $exams;        
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('online/examslist', $data);
        $this->load->view('layout/footer', $data);
     }

     public function createexam()
     {
        $this->session->set_userdata('top_menu', 'Online Exam');
        $this->session->set_userdata('sub_menu', 'OnlineTest/createexam');
       if($_POST){
        $exam['examname'] = $this->input->post('examname');
        $exam['class_id'] = $this->input->post('class_id');
        $exam['subject'] = $this->input->post('subject');
        $exam['topic'] = $this->input->post('topic');
        $exam['max_attempt'] = $this->input->post('max_attempt');
        $exam['startDate'] = date('Y-m-d',strtotime($this->input->post('startDate')));
        $exam['endDate'] =  date('Y-m-d',strtotime($this->input->post('endDate')));
        $exam['note'] = $this->input->post('note');
        $exam['time'] = $this->input->post('time');
        $this->db->insert('onlineexams',$exam);
        $id = $this->db->insert_id();
        if($id){
            $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> OnlineExam Created Successfully');
        }
        redirect('OnlineTest/index');
    }else{
        $exams = $this->onlinetest_model->get();
        $data['exams'] = $exams;        
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['title'] = 'Add Exam';
        $this->load->view('layout/header', $data);
        $this->load->view('online/createexam', $data);
        $this->load->view('layout/footer', $data);
    }
  }
  public function createexamquestions()
     {
        $this->session->set_userdata('top_menu', 'Online Exam');
        $this->session->set_userdata('sub_menu', 'OnlineTest/createexamquestions');
       if($_POST){
        $exam['exam_id'] = $this->input->post('exam_id');
        $exam['question'] = $this->input->post('question');
        $exam['a'] = $this->input->post('a');
        $exam['b'] = $this->input->post('b');
        $exam['c'] = $this->input->post('c');
        $exam['d'] = $this->input->post('d');
        $exam['answer'] = $this->input->post('answer');
        $this->db->insert('examquestions',$exam);
        $id = $this->db->insert_id();

        //print_r($this->db->last_query()); die;
        if($id){
            $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Question Added Successfully');
        }
        redirect('OnlineTest/createexamquestions');
    }else{
        $date = date('Y-m-d');
        $exams = $this->onlinetest_model->get($date);
        $data['exams'] = $exams;        
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['title'] = 'Add Questions';
        $this->load->view('layout/header', $data);
        $this->load->view('online/createexamquestion', $data);
        $this->load->view('layout/footer', $data);
    }
  }

     public function deleterecord($id)
     {
         $this->db->where('id',$id);
         $this->db->delete('onlineexams');
         redirect('OnlineTest/index');
     }


     public function getquestions($value)
     {
         $allques = $this->db->query("SELECT * from examquestions where exam_id  = $value order by id desc")->result();
         if(!empty($allques)){ 
           ?>
            <div id="questionlist">
                        <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th> Sno. </th>                                          
                                                        <th> Question</th>
                                                        <th> Opt. A</th>
                                                        <th> Opt. B</th>
                                                        <th> Opt. C</th>
                                                        <th> Opt. D</th>
                                                        <th> Answer </th>
                                                        <th> Action </th>                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $sno = 1 ; 
                                                foreach ($allques  as $key => $allque) { ?>
                                                 <tr>
                                                    <td> <?php echo $sno++ ;?></td>
                                                    <td> <?php echo $allque->question ;?></td>
                                                    <td> <?php echo $allque->a ;?></td>
                                                    <td> <?php echo $allque->b ;?></td>
                                                    <td> <?php echo $allque->c ;?></td>
                                                    <td> <?php echo $allque->d ;?></td>
                                                    <td> <?php echo $allque->answer ;?></td>
                                                    <td>  + </td>                                                   
                                                   
                                                 </tr>

                                                <?php } ?> 
                                                </tbody>
                                                </table> 
            </div>       
          </div>
        <?php }

        

     }




     
	function import_excel()
	{
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$exam_id = $_POST["exam_id"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=2; $row<=$highestRow; $row++)
				{
					//$exam_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$question = ($worksheet->getCellByColumnAndRow(1, $row)->getValue() instanceof PHPExcel_RichText)? $worksheet->getCellByColumnAndRow(1, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$a = ($worksheet->getCellByColumnAndRow(2, $row)->getValue() instanceof PHPExcel_RichText) ? $worksheet->getCellByColumnAndRow(2, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$b = ($worksheet->getCellByColumnAndRow(3, $row)->getValue() instanceof PHPExcel_RichText) ? $worksheet->getCellByColumnAndRow(3, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$c = ($worksheet->getCellByColumnAndRow(4, $row)->getValue() instanceof PHPExcel_RichText) ? $worksheet->getCellByColumnAndRow(4, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$d = ($worksheet->getCellByColumnAndRow(5, $row)->getValue() instanceof PHPExcel_RichText) ? $worksheet->getCellByColumnAndRow(5, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$answer = ($worksheet->getCellByColumnAndRow(6, $row)->getValue() instanceof PHPExcel_RichText) ? $worksheet->getCellByColumnAndRow(6, $row)->getValue()->getPlainText() : $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$data[] = array(
						'exam_id'		=>	$exam_id,
						'question'		=>	$question,
						'a'				=>	$a,
						'b'				=>	$b,
						'c'				=>	$c,
						'd'				=>	$d,
						'answer'		=>	strtolower($answer),
					);
				}
            }
          //  print_r($data); die;
			$this->onlinetest_model->insertexceldata($data);
			echo 'Data Imported successfully';
		}	
    }
    
    function fetch()
	{
		$data = $this->onlinetest_model->selectall_questions();
		$output = ' <h3 align="center">Total Data - '.$data->num_rows().'</h3>
		           <table class="table table-striped table-bordered">
                            <tr>
                                <th>Exam ID</th>
                                <th>Question</th>
                                <th>Option A</th>
                                <th>Option B</th>
                                <th>Option C </th>
                                <th>Option D</th>
                                <th>Answer</th>
                            </tr> ';
		foreach($data->result() as $row)
		{
			$output .= '  <tr>
                <td>'.$row->exam_id.'</td>
				<td>'.$row->question.'</td>
				<td>'.$row->a.'</td>
				<td>'.$row->b.'</td>
				<td>'.$row->c.'</td>
				<td>'.$row->d.'</td>
				<td>'.$row->answer.'</td>
			</tr> ';
		}
		$output .= '</table>';
		echo $output;
    }
    

    public function result()
    {
        $data['students']='';
        $exams = $this->onlinetest_model->get();
        $data['exams'] = $exams;        
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['title'] = 'View Result';
        if ($this->input->server('REQUEST_METHOD') == "GET") {
        $this->load->view('layout/header', $data);
        $this->load->view('online/resultview', $data);
        $this->load->view('layout/footer', $data);
        }else{
           $value=$_POST['class_id'];
            $data['students'] = $this->db->query("SELECT onlineexamsresult.student_id from onlineexamsresult where exam_id = $value group by student_id ")->result();
      
                                $sno = 1;
                                $stdname='';
                    foreach ($data['students'] as $key => $student) {
                       
                        $stname = $this->db->query("SELECT CONCAT(firstname,lastname) as sname from students where id= $student->student_id ")->result();
                        $stdname = $stname[0]->sname.',';
                    $results =  $this->db->query("SELECT onlineexamsresult.* from onlineexamsresult where exam_id = $value and  student_id =  $student->student_id")->result();
                    $obtained_marks = 0 ;
                    $max_marks = 0 ;
                    foreach ($results as $key => $result) {
                    $obtained_marks += $result->obt_mark;
                    $max_marks += $result->max_marks;

                    }
                    $obtained_markss[$student->student_id]=$obtained_marks;
                    $max_markss[$student->student_id]=$max_marks;
                    $stdnames[$student->student_id]=$stdname;
                  

                 }

                    $data['obtained_marks']=$obtained_markss;
                    $data['max_marks']=$max_markss;
                    $data['stdnames']=$stdnames;

                    $this->load->view('layout/header', $data);
                    $this->load->view('online/resultview', $data);
                    $this->load->view('layout/footer', $data);
        }
    }

   


public function fetchResult($value)
    {
        $students = $this->db->query("SELECT onlineexamsresult.student_id from onlineexamsresult where exam_id = $value group by student_id ")->result();
        
                            $sno = 1;
     foreach ($students as $key => $student) {
        $results =  $this->db->query("SELECT onlineexamsresult.* from onlineexamsresult where exam_id = $value and  student_id =  $student->student_id")->result();
        $obtained_marks = 0 ;
        $max_marks = 0 ;
        foreach ($results as $key => $result) {
            $obtained_marks += $result->obt_mark;
            $max_marks += $result->max_marks;
            
        } ?>
			   <tr>
                <td><?php echo $sno++; ?></td>
                <td><?php echo $student->student_id; ?></td>
				<td><?php echo $max_marks; ?></td>
				<td><?php echo $obtained_marks; ?></td>
            </tr> 
           
	<?php	}
		
	
          
    }
//********************************************* end class
}

?>