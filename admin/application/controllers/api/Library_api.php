  <?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  //defined('BASEPATH') OR exit('No direct script access allowed');

  class Library_api extends CI_Controller {
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


   public function BookList()
  { 
      $outputarr['listbook'] = $this->book_model->listbook();
          $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Data Found";
      echo json_encode($outputarr);
  }


    public function MemberList()
  {
        $memberList = $this->librarymember_model->get();
        $outputarr['memberList'] = $memberList;
        $outputarr['Status'] = 1; 
        $outputarr['Msg'] = "Data Found";
        echo json_encode($outputarr); 
  }


   public function EditBook(){
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {  
                            $data = array(
                                          'id' => $request->id,
                                          'book_title' => $request->book_title,
                                          'book_no' => $request->book_no,
                                          'isbn_no' => $request->isbn_no,
                                          'subject' => $request->subject,
                                          'rack_no' => $request->rack_no,
                                          'publish' => $request->publish,
                                          'author' => $request->author,
                                          'qty' => $request->qty,
                                          'perunitcost' => $request->perunitcost,
                                          'postdate' =>  $request->postdate,
                                          'description' => $request->description
                                     );
                                  $this->book_model->addbooks($data);
                                  $outputarr['Status'] = 1; 
                                  $outputarr['Msg'] = "Data Updated Successfully";
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


public function AddBook()
  {
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {              
                     $data = array(
                                'book_title' => $request->book_title,
                                'book_no' => $request->book_no,
                                'isbn_no' => $request->isbn_no,
                                'subject' => $request->subject,
                                'rack_no' => $request->rack_no,
                                'publish' => $request->publish,
                                'author' => $request->author,
                                'qty' => $request->qty,
                                'perunitcost' => $request->perunitcost,
                                'postdate' =>  $request->postdate,
                                'description' =>  $request->description
                            );
                                  $this->book_model->addbooks($data);
                                  $outputarr['Status'] = 1; 
                                  $outputarr['Msg'] = "Data Added Successfully";
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
 
  public function DetailsByMemberId()
  {
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {

               $scholarno = $request->scholarno ;
                                  $outputarr['Status'] = 1; 
                                  $outputarr['Msg'] = "Data Added Successfully";
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


public function returnbook()
{ 
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) {
                         $id = $request->book_id ;
                         $Tdate = date('Y-m-d');
                         $book_detail = $this->db->query("SELECT * from book_issues where id = $id")->row();
                          $latefee = 0;
                           $lastdate = $book_detail->return_date;
                          $date1 =  new DateTime( $Tdate);
                          $date2 =new DateTime($lastdate);
                          $interval = $date1->diff($date2);
                           $intday = $interval->days;
                          if ($intday <=10 ) {
                            $latefee = $intday* 1 ; 
                          }elseif ($intday >10 && $intday<=15  ) {

                              $latefee = 10 ; 
                              $intday  = $intday -10 ;
                              $latefee += $intday* 2 ; 

                          }else{
                             $latefee = 10 ; 
                             $intday  = $intday -10 ;
                             $latefee += 5*2 ;
                             $intday  = $intday - 5 ;
                             $latefee += $intday* 5 ; 
                          }
                         $data = array(
                                    'id' => $id,
                                    'is_returned' => 1
                                );

                                  $this->bookissue_model->update($data);
                                  $outputarr['Status'] = 1; 
                                  $outputarr['Latefees'] = $latefee; 
                                  $outputarr['Msg'] = "Book returned Successfully";
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



public function issuebook()
{  
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
            if (!empty($request)) { 
               $book_id = $request->book_id ;
                $date = date('Y-m-d');
                $date = strtotime($date);
               $return_date = strtotime("+7 day", $date);
               $bookno = $request->bookno ;
               $session_id = $request->session_id ;
          $check = $this->Isavailable($book_id);
          if ($check) {
             $member_id =$request->member_id;
                      $data = array(
                          'book_id' => $book_id,
                          'session_id' => $session_id,
                          'return_date' => date('Y-m-d', $return_date),
                          'issue_date' => date('Y-m-d'),
                          'member_id' => $member_id,
                      );
                      $this->bookissue_model->add($data);
   $studentphone = $this->db->query("SELECT guardian_phone from students where id = $member_id")->row();
if (!empty($studentphone)) {
  $studentphone->guardian_phone ; 
  $msg_data=array(
          'title'=>'Book Issued',
          'message'=>"Dear Parent Book No $bookno has been issued return date is $return_date .",
          'session_id'=> $this->setting_model->getCurrentSession(),
          'user_list'=> $studentphone ,
          'student_session_id'=>0,
          'sms_status'=>1
        );
        $this->Messages_model->add($msg_data);
}
   
   

                    $outputarr['Status'] = 1; 
                    $outputarr['Msg'] = "Book Issued Successfully";
          }else{
                     $outputarr['Status'] = 0; 
                    $outputarr['Msg'] = "This Book is Not Available on Rack";
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

public function Isavailable($value='')
{
 $book_detail =  $this->db->query("SELECT * from books where id = $value")->row();
 $allot_book_count = $this->db->query("SELECT count(id) as bookcount from book_issues where book_id = $value and is_returned = 0")->row()->bookcount;
 if ($book_detail->qty > $allot_book_count) {
    return 1 ; 
 }else{
  return 0 ;
 }


}

public function testdate($value='')
{
  $date = date('Y-m-d');
$date = strtotime($date);
$date = strtotime("+7 day", $date);
echo date('Y-m-d', $date);
}

public function addallStudentAsMember()
{
  $student = $this->db->query("SELECT * from students where 1")->result();
  //print_r($student); die;
  foreach ($student as $key => $value) 
     {    
        $data['library_card_no']= $value->admission_no ;
        
        $b = $this->librarymanagement_model->check_data_exists($data);                                
        if (!empty($b)) {  }
          else{
             $library_card_no= $value->admission_no ;
             $student =  $value->id ;  
             $data = array(
                  'member_type' => 'student',
                  'member_id' => $student,
                  'is_active' =>  'yes',
                  'library_card_no' => $library_card_no
                   );
              $inserted_id = $this->librarymanagement_model->add($data);
           }

     } 
              $outputarr['Status'] = 1; 
              $outputarr['Msg'] = "Member Added successfully";
               echo json_encode($outputarr);
}

public function issuereturnbook()
   {  
    $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
          if (!empty($request)) {
                               $id = $request->member_id ; 
                              /*--------------------------------------*/
                                $memberList = $this->librarymember_model->getByMemberID($id);
                                $outputarr['memberdetails'] = $memberList;
                                $issued_books = $this->bookissue_model->getMemberBooks($id);
                                $outputarr['issued_books'] = $issued_books;
                                $bookList = $this->book_model->get();
                                $outputarr['bookList'] = $bookList;
                               /*--------------------------------------*/
                                $outputarr['Status'] = 1; 
                                $outputarr['Msg'] = "Data found";
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
  
 public function addStudentAsMember()
    {
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
                      if (!empty($request)) {                               
                                $library_card_no = $request->library_card_no;
                                $data['library_card_no']= $library_card_no ;
                                $b = $this->librarymanagement_model->check_data_exists($data);                                
                                if (!empty($b)) {
                                	$outputarr['Status'] = 0; 
                                    $outputarr['Msg'] = "Library Card Number Already Exist";
                                    }else{
                                		 $student =  $request->student_id ;  
                                     $data = array(
                                          'member_type' => 'student',
                                          'member_id' => $student,
                                          'library_card_no' => $library_card_no
                                           );
                                      $inserted_id = $this->librarymanagement_model->add($data);
                                      $outputarr['Status'] = 1; 
                                      $outputarr['Msg'] = "Member Added successfully";
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


 public function addTeacherAsMember()
    {
      $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
                      if (!empty($request)) {                               
                                $library_card_no = $request->library_card_no;
                                $data['library_card_no']=$library_card_no ;
                                $b = $this->librarymanagement_model->check_data_exists($data);
                                if (!empty($b)) {
                                       $outputarr['Status'] = 0; 
                                       $outputarr['Msg'] = "Library Card Number Already Exist";
                                      }
                                       else{
                                       	$staff =  $request->staff_id ;  
                                      $data = array(
                                          'member_type' => 'teacher',
                                          'member_id' => $staff,
                                          'library_card_no' => $library_card_no
                                      );

                                      $inserted_id = $this->librarymanagement_model->add($data);
                                      $outputarr['Status'] = 1; 
                                      $outputarr['Msg'] = "Member Added successfully";
                               
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


  public function SurrenderCard()
  {
     $postdata = file_get_contents("php://input");
     if ($postdata != '')
     {            
       $request = json_decode($postdata);
                      if (!empty($request)) {
                                $member_id = $request->member_id ; 
                                 $this->librarymember_model->surrender($member_id);
                                $outputarr['Status'] = 1; 
                                $outputarr['Msg'] = "Membership surrender successfully";
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


 public function teacherList()
 {  
 	$teachers = $this->db->query("SELECT staff.*,staff.designation as designation_id,staff_designation.designation as designation ,CONCAT('http://softwares.centralacademyjabalpur.com/uploads/staff_images/',image ) as image from staff left join staff_designation on staff_designation.id = staff.designation  ")->result();
 	foreach ($teachers as $key => $value) {
 		$lib = $this->db->query("SELECT * from libarary_members where member_id = '".$value->id."' and member_type = 'teacher' ")->row();
 		if (!empty($lib)) {
 			$value->Is_member = 1 ; 
 		}else{
 			$value->Is_member = 0 ; 
 		}
 	}
      $arr['result'] =  $teachers;
      $arr['Status'] = 1;
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


  public function pendancyReport()
  {
    $postdata = file_get_contents("php://input");
     if ($postdata != ''){     
         $request = json_decode($postdata);
          $month = $request->month ; 
               if ($month) { 
	                 $data1 = $this->db->query("SELECT books.book_title, book_issues.*,book_issues.member_id as book_issues_id, libarary_members.member_id, students.firstname,students.lastname
	                 	 from book_issues 
	                 	 join libarary_members on libarary_members.id = book_issues.member_id 
	                 	 join students on students.id = libarary_members.member_id 
	                 	 join books on books.id = book_issues.book_id
	                 	 where MONTH(return_date) = $month and book_issues.is_returned = '0' and libarary_members.member_type = 'student' ")->result();

	                  $data2 = $this->db->query("SELECT books.book_title, book_issues.*,book_issues.member_id as book_issues_id, libarary_members.member_id,staff.name as firstname ,staff.surname as lastname
	                 	 from book_issues 
	                 	 join libarary_members on libarary_members.id = book_issues.member_id 
	                 	 join staff on staff.id = libarary_members.member_id 
	                 	 join books on books.id = book_issues.book_id
	                 	 where MONTH(return_date) = $month and book_issues.is_returned = '0' and libarary_members.member_type = 'teacher' ")->result();
  											
  						//echo $this->db->last_query();die;					
  											foreach ($data2 as $key => $value) {
  											     $data1[] = $value ;
  											}
	                // echo $this->db->last_query();die;
                 if (!empty($data1)) {
                     $outputarr['data'] = $data1; 
                     $outputarr['Status'] = 1; 
                     $outputarr['Msg'] = "Data  Found";
                 }else{
                    $outputarr['Status'] = 0; 
                    $outputarr['Msg'] = "Data Not Found";
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
  //--------------------------------End Class-------------------------------------------//
     } ?>

