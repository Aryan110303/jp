  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  class Timetable_api extends CI_Controller {
    public function __construct() {
      parent::__construct(); 
      $this->load->model('Api_model');
      $this->load->model('Proxy_model');
      $this->load->model('Book_model');
      $this->load->model('Messages_model');
      $this->load->model('Studentfeemaster_model');
      $this->load->library('Enc_lib'); 
      $this->load->library('form_validation');      
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
       $qry = "SELECT * from sessions where id = $session";
       $sess= $this->db->query($qry)->row();
       $outputarr['currentSession'] = $sess ;
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


public function ClassList($value='')
  {
    $class = $this->class_model->get();
    $data['classlist'] = $class;
    $data['Status'] = 1;
    echo json_encode($data);
  }

  
  public function Sectionbyclassid()
  {
    $postdata = file_get_contents("php://input");
      //$postdata =  $this->input->post('data');    
    if ($postdata != '')
    {  
     $request = json_decode($postdata);
     if(!empty($request))
     {  
      $class_id = $request->class_id  ; 


      $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
      sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class_id."' order by sections.section ";
      $data['sections'] = $this->db->query($qry)->result_array();
      $data['Status'] =  1 ; 
    

         } else{
          $data['msg'] = 'no section found' ; 
          $data['Status'] = 0 ; 
            }
        }
        else{
          $data['msg'] = 'no section found' ; 
          $data['Status'] = 0 ; 
        }
   echo json_encode($data);

  }


   public function SubjectList()
  { 
         $outputarr['subjectList'] = $this->subject_model->get();
         $outputarr['Status'] = 1; 
         $outputarr['Msg'] = "Data Found";
         echo json_encode($outputarr);
  } 

    public function ExamList()
  {      
        $outputarr['examList'] = $this->exam_model->get();
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Data Found";
        echo json_encode($outputarr); 
  }

  public function Update_timetable()
  {
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) { 
                             $class_id = $request->class_id ;      
                             $section_id = $request->section_id ;      
                             $session_id = $request->session_id ;      
                             $exam_id = $request->exam_id ;      
                             $subject = $request->subjects ;                             
                            // $timetable_array = $request->timetable ;                           
                              foreach ($subject as $key => $timetable_value)
                              {
                                if ($timetable_value->id) {
                                    $updatedata = array( 
                                  "session_id"=>$timetable_value->session_id, 
                                  "class_id"=>$timetable_value->class_id, 
                                  "section_id"=>$timetable_value->section_id, 
                                  "date"=>$timetable_value->date, 
                                  "exam_id"=>$timetable_value->exam_id, 
                                  "subject_id"=>$timetable_value->subject_id, 
                                  "updated_at"=>date('Y-m-d h:i:s')
                                    );

                                  $this->db->where('exam_timetable.id',$timetable_value->id);
                                  $this->db->update('exam_timetable',$updatedata);
                                  $outputarr['Status'] = 1; 
                                  $outputarr['Msg'] = "Data Updated Successfully";

                                   }else{
                                     $insertdata = array( 
                                                "session_id"=>$session_id,
                                                "class_id"=>$class_id, 
                                                "section_id"=>$section_id, 
                                                "exam_id"=>$exam_id, 
                                                "date"=>$timetable_value->date,                                     
                                                "subject_id"=>$timetable_value->subject_id, 
                                          );
                                    $this->db->insert('exam_timetable',$insertdata);
                                    $outputarr['Status'] = 1; 
                                    $outputarr['Msg'] = "Data Added Successfully";
                                  }
                                 
 $outputarr['Msga'][] = $this->db->last_query();

                              }                                                               
                        }
                        else{
                              $outputarr['Status'] = 0; 
                              $outputarr['Msg'] = "Request Data Not Found";
                            }
                 }
           else{
                 $outputarr['Status'] = 0; 
                 $outputarr['Msg'] = "Post Data Not Found";
               }     

      echo json_encode($outputarr);
  }

  public function Add_timetable()
  {
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {                          
                             $class_id = $request->class_id ;      
                             $section_id = $request->section_id ;      
                             $session_id = $request->session_id ;      
                             $exam_id = $request->exam_id ;      
                             $subject = $request->subjects ;      
                             if ($section_id != 0) {
                             
                              foreach ($subject as $key => $timetable_value)
                                      {  
                                         $insertdata = array( 
                                                "session_id"=>$session_id,
                                                "class_id"=>$class_id, 
                                                "section_id"=>$section_id, 
                                                "exam_id"=>$exam_id, 
                                                "date"=>$timetable_value->date,                                     
                                                "subject_id"=>$timetable_value->subject_id, 
                                            );
                                      $this->db->insert('exam_timetable',$insertdata);
                                      $outputarr['Status'] = 1; 
                                      $outputarr['Msg'] = "Data Added Successfully";
                                    }   
                            }else{
                               $qry = "SELECT sections.section, sections.id FROM `class_sections` JOIN
                                 sections on class_sections.section_id=sections.id WHERE class_sections.class_id= '".$class_id."' order by sections.section ";
                             $sections = $this->db->query($qry)->result();
                             foreach ($sections as $key => $section) {
                               
                               foreach ($subject as $key => $timetable_value) {  
                                         $insertdata = array( 
                                                "session_id"=>$session_id,
                                                "class_id"=>$class_id, 
                                                "section_id"=>$section->id, 
                                                "exam_id"=>$exam_id, 
                                                "date"=>$timetable_value->date,                                     
                                                "subject_id"=>$timetable_value->subject_id, 
                                          );
                                    $this->db->insert('exam_timetable',$insertdata);
                                    $outputarr['Status'] = 1; 
                                    $outputarr['Msg'] = "Data Added Successfully";
                                    //$outputarr['Msg1'] = $this->db->last_query();
                                                               
                               }   
                             }
                            }                                                            
                        }
                        else{
                              $outputarr['Status'] = 0; 
                              $outputarr['Msg'] = "Request Data Not Found";
                            }
                 }
                 else{
                       $outputarr['Status'] = 0; 
                       $outputarr['Msg'] = "Post Data Not Found";
                     }     
      echo json_encode($outputarr);
  }

  
  public function View_timetable()
  {
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {                             
                                                       
                               $class_id = $request->class_id ;  
                               $section_id = $request->section_id ;                         
                               $exam_id = $request->exam_id ;                         
                               $session_id = $request->session_id ;

                              if ($request->section_id != 0) {
                                 $result = $this->db->query("SELECT exam_timetable.*, classes.class, sections.section,exams.name as examname,subjects.name  as subjectname
                                from exam_timetable 
                                join classes on exam_timetable.class_id = classes.id 
                                join sections on exam_timetable.section_id = sections.id 
                                join subjects on exam_timetable.subject_id = subjects.id 
                                join exams on exam_timetable.exam_id = exams.id 
                                where exam_id = $exam_id and  class_id = $class_id and section_id = $section_id and session_id = $session_id order by exam_timetable.date ")->result();
                                  if (!empty($result)) {
                                   $outputarr['data'] = $result;
                                   $outputarr['students'] = $this->Api_model->get_student_ByclassSection($class_id, $section_id, $session_id);
                                     $outputarr['Status'] = 1; 
                                    $outputarr['Msg'] = "Time Table Data Found";
                                       } else{
                                      $outputarr['Status'] = 0; 
                                      $outputarr['Msg'] = "Time Table Data Not Found";
                                      } 
                              }else{
                               $result = $this->db->query("SELECT exam_timetable.*, classes.class, sections.section,exams.name as examname,subjects.name  as subjectname
                                from exam_timetable 
                                join classes on exam_timetable.class_id = classes.id 
                                join sections on exam_timetable.section_id = sections.id 
                                join subjects on exam_timetable.subject_id = subjects.id 
                                join exams on exam_timetable.exam_id = exams.id 
                                where exam_id = $exam_id and  class_id = $class_id  and session_id = $session_id group by subject_id order by exam_timetable.date ")->result();
                                        if (!empty($result)) {
                                         $outputarr['data'] = $result;
                                         $outputarr['students'] = $this->Api_model->get_student_Byclass($class_id, $session_id);
                                                          $outputarr['Status'] = 1; 
                                        $outputarr['Msg'] = "Time Table Data Found";
                                       } else{
                                      $outputarr['Status'] = 0; 
                                      $outputarr['Msg'] = "Time Table Data Not Found";
                                      }   
                                   }

                                                                
                              }
                              else{
                                $outputarr['Status'] = 0; 
                                $outputarr['Msg'] = "Request Data Not Found";
                              }
                       }else{
                         $outputarr['Status'] = 0; 
                         $outputarr['Msg'] = "Post Data Not Found";
                       }     
      echo json_encode($outputarr);
  }
  

  public function SessionList()
  {
    $arr['session_result'] = $this->session_model->get();
    $arr['Status'] = 1;
    $arr['Msg'] = "Data Found";
    echo json_encode($arr);
  }


public function studentSearch()
{
  $postdata = file_get_contents("php://input");
    if ($postdata != ''){     
      $request = json_decode($postdata);
      $text = $request->text ;
      $session_id = $request->session_id ;
      
        $result = $this->Api_model->get_search_result2($text,$session_id);

        foreach ($result as $key => $value) {
                	$lib = $this->db->query("SELECT * from libarary_members where member_id = '".$value->id."' and member_type = 'student' ")->row();
				 		if (!empty($lib)) {
				 			$value->Is_member = 1 ; 
				 		}else{
				 			$value->Is_member = 0 ; 
				 		}
                }
               //  print_r($result); die;
        $house_list = $this->Api_model->get_house();
   
      if (!empty($result))
      {
       $outputarr['Result'] = $result ;
       $outputarr['house_list'] = $house_list ;
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

  public function All_student() {
    $postdata = file_get_contents("php://input");
       // $postdata =  $this->input->post('data'); 
    if ($postdata != ''){     
      ///$outputarr['da'] = $postdata ; 

      $request = json_decode($postdata);
      $session_id = $request->session_id ;     

      if ($session_id) {      
        $data = $this->Api_model->get_all_student($session_id); 
        foreach ($data as $key => $value) {
                	$lib = $this->db->query("SELECT * from libarary_members where member_id = '".$value->id."' and member_type = 'student' ")->row();
				 		if (!empty($lib)) {
				 			$value->Is_member = 1 ; 
				 		}else{
				 			$value->Is_member = 0 ; 
				 		}
                }        
        if (!empty($data)) {
         $outputarr['Status'] = 1; 
         $outputarr['Msg'] = " Student data Available";
         $outputarr['Result'] = $data ;
       } else {
         $outputarr['Status'] = 0; 
         $outputarr['Msg'] = "No Student Available";
       }
     }else{
       $outputarr['Status'] = 0; 
       $outputarr['Msg'] = "Please Select Session First";
     }
   }else{
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Post Data Not Found";
   }     
    echo json_encode($outputarr);
  }

public function delete_row()
{
  $postdata = file_get_contents("php://input");
    if ($postdata != '')
    {     
           $request = json_decode($postdata);
      if (!empty($request)) {       
        $row_id = $request->row_id ;     
            $this->db->where('id',$row_id);
            $this->db->delete('exam_timetable');
            $outputarr['Status'] = 1; 
            $outputarr['Msg'] = "Row deleted Successfully";
          }else{
             $outputarr['Status'] = 0; 
             $outputarr['Msg'] = "Request Data Not Found";
          }
   }else{
     $outputarr['Status'] = 0; 
     $outputarr['Msg'] = "Post Data Not Found";
   }
   echo json_encode($outputarr);

}
  //--------------------------------End Class-------------------------------------------//
   } ?>

