<?php
namespace App\Http\Controllers;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');


use Illuminate\Http\Request;
use App\Principalapi;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class PrincipalapiController extends Controller
{
	 public $current_session = 0;
	  function __construct() {
		DB::enableQueryLog();
        $this->current_session = DB::table('sch_settings')->select('session_id')->first()->session_id;
    }

 public function login(Request $requestserver)
  {  
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {        
        $base='http://jpacademymeerut.com/admin/uploads/staff_images/';
        $request  = json_decode($abc) ;
        $result = DB::select("SELECT staff.*,CONCAT('$base',image) as image FROM staff JOIN staff_roles on staff_roles.staff_id = staff.id where contact_no ='".$request->phone."' and pass_code = '".$request->password."' and staff_roles.role_id = 7 ");
       if(!empty($result))
                {
                    $outputarr['msg'] = "login Successfully";
                    $outputarr['result'] = $result ;
                    $outputarr['status'] = '1';
                }  else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = " Invalid Users";
                    }
    }else
        {
        $outputarr['status'] = '0';
        $outputarr['msg'] = "Post data Not found";
        }
  print json_encode($outputarr);
 }

public function schoolurls()
{
    $result = DB::select("SELECT * from school_urls where is_active = 1 and module = 5 order by sequence asc");
                      $outputarr['result'] = $result;                       
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';              
          print json_encode($outputarr);
}


 public function TeacherList()
   {
    $result = DB::select(" SELECT staff.*,staff_designation.designation from staff left join staff_designation on staff.designation = staff_designation.id where staff.designation > 6 ");
      $outputarr['result'] = $result;                       
      $outputarr['msg'] = "success result found";  
      $outputarr['status'] = '1';  
      print json_encode($outputarr);
    }
      
public function classList()
  {
    $total_student =0;
    $total_present =0;
    $total_absent  =0;
    $result = DB::select("SELECT classes.* from classes where 1 ");  
  //  print_r($result); die;
   if(!empty($result))
        {
          foreach ($result as $results) 
              {                           
              $results->student_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                      //->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.is_active','=','yes')
                                                      ->count();          

							$results->boys_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Male')
                                                      ->orWhere('students.gender','like','MALE')
                                                      ->where('students.is_active','=','yes')
                                                      ->count();

							$results->boys_present = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Male')
                                                      ->orWhere('students.gender','like','MALE')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',1)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->boys_absent = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Male')
                                                      ->orWhere('students.gender','like','MALE')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',4)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->girls_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Female')
                                                      ->orWhere('students.gender','like','FEMALE')
                                                       ->where('students.is_active','=','yes')
                                                      ->count();
							$results->girls_present = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Female')
                                                      ->orWhere('students.gender','like','FEMALE')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',1)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->girls_absent = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->orWhere('students.gender','like','Female')
                                                      ->orWhere('students.gender','like','FEMALE')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',4)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count(); 

                                                       $total_student = $results->student_count;
                                                       $total_present+= $results->boys_present+$results->girls_present;
                                                       $total_absent += $results->boys_absent+$results->girls_absent;
                                                }

                        $calculation = new \stdClass(); 
                        $calculation->total_student=$total_student;
                        $calculation->total_present=0;//$total_present;
                        $calculation->total_absent=0;//$total_absent;
                        $calculation->total_pending=0;//$total_student-($total_present+$total_absent);

                        $data = new \stdClass(); 
                        $data->calculation=$calculation;
                        $data->class=$result;
                      $outputarr['result'] = $data;                       
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = " Invalid Users";
                   
                  }
          print json_encode($outputarr);
  }
     
public function sectionList(Request $requestserver)
  {
    $total_student=0;
    $total_present=0;
    $total_absent=0;
    $abc = file_get_contents("php://input");
      if (!empty($abc))
      {
        $request  = json_decode($abc) ;
        $result = DB::select(" SELECT sections.*,class_sections.* from sections join class_sections on class_sections.section_id = sections.id where class_sections.class_id  = $request->id ");
        if(!empty($result))
                {     
                  foreach ($result as $results) 
                     { 
                          $results->student_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                       ->where('student_session.session_id','=',$this->current_session)
                                                       ->where('students.is_active','=','yes')
                                                      ->count();
                         $results->boys_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                       ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Male')
                                                      ->where('students.is_active','=','yes')
                                                      ->count();

							          $results->boys_present = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                    ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Male')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',1)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->boys_absent = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                       ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Male')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',4)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->girls_count = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Female')
                                                       ->where('students.is_active','=','yes')
                                                      ->count();
							$results->girls_present = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Female')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',1)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count();
							$results->girls_absent = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('student_attendences', 'student_attendences.student_session_id', '=', 'student_session.id')
                                                      ->select('student_session.id')
                                                      ->where('student_session.class_id','=',$results->class_id)
                                                      ->where('student_session.section_id','=',$results->section_id)
                                                      ->where('student_session.session_id','=',$this->current_session)
                                                      ->where('students.gender','=','Female')
                                                       ->where('students.is_active','=','yes')
                                                      ->where('student_attendences.attendence_type_id','=',4)
                                                      ->where('student_attendences.date','=',date('Y-m-d'))
                                                      ->count(); 

                                                       $total_student+=$results->student_count;
                                                       $total_present+=$results->boys_present+$results->girls_present;
                                                       $total_absent+=$results->boys_absent+$results->girls_absent;
                         
                        }
                        $calculation = new \stdClass(); 
                        $calculation->total_student=$total_student;
                        $calculation->total_present=$total_present;
                        $calculation->total_absent=$total_absent;
                        $calculation->total_pending=$total_student-($total_present+$total_absent);

                         $data = new \stdClass(); 
                          $data->calculation=$calculation;
                          $data->class=$result;

                      $outputarr['result'] = $data;                     
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = " No Result Found";
                    }
            }else
                {
                $outputarr['status'] = '0';
                $outputarr['msg'] = "Post data Not found";
                }
          print json_encode($outputarr);
    }


public function classes()
{
   $result = DB::select("SELECT  classes.* from classes where 1  ");
   if (!empty($result)) {
                        $outputarr['result'] = $result;
                        $outputarr['status'] = '1';
                        $outputarr['msg'] = " Data Found";  
   }else{
      $outputarr['status'] = '0';
      $outputarr['msg'] = "No Data Found";  
   }
     print json_encode($outputarr);
}

public function sections(Request $requestserver)
{
    $abc = file_get_contents("php://input");
      if (!empty($abc))
      {
        $request  = json_decode($abc) ;
           $result = DB::select(" SELECT sections.*,class_sections.* from sections join class_sections on class_sections.section_id = sections.id where class_sections.class_id  = $request->class_id ");
                 if (!empty($result)) {
                                      $outputarr['result'] = $result;
                                      $outputarr['status'] = '1';
                                      $outputarr['msg'] = " Data Found";  
                 }else{
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = "No Data Found";  
                 }
       }else{
           $outputarr['status'] = '0';
           $outputarr['msg'] = "No Data Found";  
       }
         print json_encode($outputarr);
}

public function appstatusclassSectionwise(Request $requestserver)
  {
    $total_student =0;
    $abc = file_get_contents("php://input");
      if (!empty($abc))
      {
         
        $request  = json_decode($abc) ;
         $section_id = $request->section_id ;
         $class_id = $request->class_id ;
       if ($section_id == 0){
        
           $studentList = DB::select("SELECT students.app_seen_date,students.gender,students.admission_no,students.guardian_phone,students.firstname,students.lastname from students join student_session on students.id = student_session.student_id where students.is_active = 'yes' and student_session.session_id = '".$this->current_session."' and student_session.class_id = '".$class_id."' ");
           
        }else{
         $studentList  = DB::select("SELECT students.app_seen_date,students.gender,students.admission_no,students.guardian_phone,students.firstname,students.lastname from students join student_session on students.id = student_session.student_id where students.is_active = 'yes' and student_session.session_id = '".$this->current_session."' and student_session.class_id = '".$class_id."' and student_session.section_id = '".$section_id."'"); 
              
        }
        if(!empty($studentList))
                { 
                        $outputarr['result'] = $studentList;
                        $outputarr['status'] = '1';
                        $outputarr['msg'] = " Data Found";                             
                } else{
                        $outputarr['status'] = '0';
                        $outputarr['msg'] = " No Data Found";                               
                       }
             }else{
                 $outputarr['status'] = '0';
                 $outputarr['msg'] = " Post Data Not Found"; 
                  }
          print json_encode($outputarr);
  }

public function StudentAttendance(Request $requestserver)
  {
    //  $abc = $requestserver->input();
    $total_student=0;
    $total_present=0;
    $total_absent=0;
    $abc = file_get_contents("php://input");
      if (!empty($abc))
      {

          $base  = url('/');
          $request  = json_decode($abc) ;
          $class_id  = $request->class_id;  
          $section_id  = $request->section_id;  
          $date  = $request->date;
          $result = DB::select("SELECT students.gender,student_attendences.id as pid,student_attendences.attendence_type_id, students.firstname, 
           students.lastname, CONCAT('".$base."','/',students.image) as image,student_attendences.remark, attendence_type.type from student_attendences 
            join student_session on student_attendences.student_session_id = student_session.id 
            join students on student_session.student_id = students.id 
            join attendence_type on student_attendences.attendence_type_id = attendence_type.id  
            where student_session.class_id = $class_id and student_session.section_id = $section_id and student_attendences.date = '".$date."' and students.is_active = 'yes' ");

          if(!empty($result))
                {

                  foreach ($result as $val) 
                        { 
                          if($val->attendence_type_id==1)
                          $total_present++;
                          elseif($val->attendence_type_id==4)
                          $total_absent++;
                        }


                   $calculation = new \stdClass(); 

                        $calculation->total_student=$total_student=count($result);
                        $calculation->total_present=$total_present;
                        $calculation->total_absent=$total_absent;
                        $calculation->total_pending=$total_student-($total_present+$total_absent);

                         $data = new \stdClass(); 
                          $data->calculation=$calculation;
                          $data->class=$result;


                      $outputarr['result'] = $data;                      
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = " No Result Found on this Date";
                    }
            }else
                {
                $outputarr['status'] = '0';
                $outputarr['msg'] = "Post data Not found";
                }
          print json_encode($outputarr);
    }



public function TeacherAttendance(Request $requestserver)
  {
    
     // $abc = $requestserver->input();
     $total=0;
    $total_present=0;
    $total_absent=0;
     $abc = file_get_contents("php://input");
      if (!empty($abc))
      {        
          //$request  = json_decode($abc['data']) ;
          $request  = json_decode($abc) ;
          $date  = $request->date; 
         
        
          $result = DB::select("SELECT staff_attendance.*,staff.name, staff.gender,staff.surname,staff_attendance_type.type from staff_attendance join staff on staff.id = staff_attendance.staff_id join staff_attendance_type on staff_attendance.staff_attendance_type_id = staff_attendance_type.id  where  staff_attendance.date = '".$date."'  ");


        if(!empty($result))
                {

                   foreach ($result as $val) 
                        { 
                          if($val->staff_attendance_type_id==1)
                          $total_present++;
                          elseif($val->staff_attendance_type_id==3)
                          $total_absent++;
                        }



                      $calculation = new \stdClass(); 

                        $calculation->total_teacher=$total_student=count($result);
                        $calculation->total_present=$total_present;
                        $calculation->total_absent=$total_absent;
                        $calculation->total_pending=$total_student-($total_present+$total_absent);

                         $data = new \stdClass(); 
                          $data->calculation=$calculation;
                          $data->teacher=$result;


                      $outputarr['result'] = $data;                        
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = "No Result Found on this Date";
                    }
            }else
                {
                $outputarr['status'] = '0';
                $outputarr['msg'] = "Post data Not found";
                }
          print json_encode($outputarr);
    }


public function absentteachers(Request $requestserver)
  {
     // $abc = $requestserver->input();
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {        
      $request  = json_decode($abc) ;
        //  $request  = json_decode($abc['data']) ;
          $date  =$request->date;
          $result = DB::select("SELECT staff_attendance.*,staff.name, staff.gender,staff.surname,staff_attendance_type.type from staff_attendance join staff on staff.id = staff_attendance.staff_id join staff_attendance_type on staff_attendance.staff_attendance_type_id = staff_attendance_type.id  where  staff_attendance.date = '".$date."' and staff_attendance_type_id = 3");
        if(!empty($result))
                {
                      $outputarr['result'] = $result;                       
                      $outputarr['msg'] = "success result found";  
                      $outputarr['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = "No Result Found on this Date";
                    }
            }else
                {
                $outputarr['status'] = '0';
                $outputarr['msg'] = "Post data Not found";
                }
          print json_encode($outputarr);
    }


public function TeacherProxy(Request $requestserver)
  {
      ///$abc = $requestserver->input();
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
          $request  = json_decode($abc) ;
          $teacher_id  =$request->teacher_id;
          $date  =$request->date;
          $result = DB::select("SELECT proxyteachers.*, staff.name,staff.gender,staff.surname from proxyteachers left join staff on staff.id = proxyteachers.extrateacher where proxyteachers.date = '".$date."' and on_behalf  = $teacher_id ");
        if(!empty($result))
                {
                      $data['result'] = $result;                       
                      $data['msg'] = "success result found";  
                      $data['status'] = '1';
                } else
                    {
                    $outputarr['status'] = '0';
                    $outputarr['msg'] = " No Result Found on this Date";
                    }
            }else
                {
                $outputarr['status'] = '0';
                $outputarr['msg'] = "Post data Not found";
                }
          print json_encode($outputarr);
    }
    
//------------------------------new Dashboard api's-------------------------------------

public function totalFees(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id; 
        
        $students = DB::select("SELECT students.*,student_session.promoted , student_session.id as student_session_id ,classes.class, sections.section from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where  student_session. session_id = $session_id and students.is_active = 'yes' ");
       $a = 0 ;
       $amount = 0 ;
     
       $paidamount = 0 ;
      
         foreach ($students as $key => $value){     
        $totalfees = '' ;
        $id=$value->student_session_id;
        $totalfees = SELF::get_student_fees($value->student_session_id);
                foreach ($totalfees as $key => $totalfee){
  	                foreach ($totalfee->fees as $key => $fee) {
                        if ($value->promoted == 1) {
                             $amount +=  $fee->amount ;
                         }else{
                               $amount +=  $fee->amount_II ;
                             }
        	              }              
      	            }
      	       } ///students foreach
          $fesss['totalfee'] = $amount ;
          $fesss['totalpaid'] =  $paidamount ;
          $fesss['totalremain'] =  $amount - $paidamount ;
		      $outputarr['result'] = $fesss; 
	        $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";	 
            
	   }else{
	        $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
          // $outputarr['msg']=$request->session_id;  
	       }  
	   print json_encode($outputarr);
	}
     
 public function todayFees()
     {
       // $todaydate = date('Y-m-d');
       // $fees = DB::select("SELECT fee_deposite_receipt.*,fee_groups_feetype.institute from fee_deposite_receipt 
       //  join fee_groups_feetype on fee_groups_feetype.id = fee_deposite_receipt.fee_groups_feetype_id ");
       // $amountpaid1 = 0 ;
       // $amountdiscount1 = 0 ;
       // $amountfine1 = 0 ;
       // $amountpaid2 = 0 ;
       // $amountdiscount2 = 0 ;
       // $amountfine2 = 0 ;
       // foreach ($fees as $key => $fee) {
       //      $amount_details = json_decode($fee->amount_detail);
       //     if ($amount_details->date  == $todaydate) {
       //         if($fee->institute == 1){
       //            $amountfine1 += $amount_details->amount_fine ;
       //            $amountdiscount1 += $amount_details->amount_discount ;
       //            $amountpaid1 += $amount_details->amount ;
       //         }else{
       //           $amountfine2 += $amount_details->amount_fine ;
       //           $amountdiscount2 += $amount_details->amount_discount ;
       //           $amountpaid2 += $amount_details->amount ;
       //         }             
       //     } 
       //  } 
        
         if(1 || ($fees > 0)){
         $data1['today_collection'] = 0 ;
         $data1['today_discount'] = 0 ;
         $data1['today_fine'] = 0 ;
         
         // $data['name'] = "Central Academy Jabalpur";
        /*   $data['data'] = $data1;
          $datafinal[] = $data ; */
          $outputarr['result'] = $data1;
          $outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
         else{
	        $outputarr['status'] = '0';
	        $outputarr['msg'] = "data Not found";
	       }  
	      print json_encode($outputarr);

     }


  public function transportfees(Request $requestserver)
     {
     	  $abc = file_get_contents("php://input");
			      if (!empty($abc))
			      {       
			        $request  = json_decode($abc) ;
			        $session_id  =$request->session_id;  
			        $todaydate = date('Y-m-d');
        			$transportamountpaid = 0 ;
        			$amountdiscount = 0 ;
        			$amountfine = 0;
              // $transportamountpaid = DB::table('transport_fee_deposite')
              //   ->select(DB::raw('SUM(transport_fees) as total_paid_transport_fees'))->where('session_id',$session_id)
              //   ->get()->first()->total_paid_transport_fees;

 	            // $totaltransportfees = DB::table('student_session')
              //   ->select(DB::raw('SUM(transport_fees) as total_transport_fees'))->join('students', 'students.id', '=', 'student_session.student_id')->where('session_id',$session_id)->where('students.is_active','yes')
              //   ->where('session_id',$session_id)->get()->first()->total_transport_fees;
    if(1 || $totaltransportfees > 0){   
         //$totalpending = 	$totaltransportfees - $transportamountpaid ;      
         $data['totaltranportFee'] = 0;//$totaltransportfees ;
         $data['paidtransportFee'] = 0;//$transportamountpaid ;
         $data['pendingtransportFee'] = 0;//$totalpending;

          $outputarr['result'] = $data;
          $outputarr['status'] = '0';
	      $outputarr['msg'] = "Result found";
         }
     else{
	        $outputarr['status'] = '0';
	        $outputarr['msg'] = "Result Not found";
	       }
	      }else{
	      	 $outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	      } 
	    print json_encode($outputarr);

     }

public function todaytransportfees(Request $requestserver)
     {      
      $todaydate = date('Y-m-d');
      $transportamountpaid = 0 ;
      $amountdiscount = 0 ;
      $amountfine = 0 ;

  
     // $transportamountpaid = DB::table('transport_fee_deposite')
     //            ->select(DB::raw('SUM(transport_fees) as total_paid_transport_fees'))->where('date',$todaydate )->get()->first()->total_paid_transport_fees;
              if ($transportamountpaid === null) {
               $transportamountpaid = 0 ;
              }
         $data['todaycollection'] = $transportamountpaid ;
         $data['todaydiscount'] = 0;
         $data['todayfine'] = 0;

          $outputarr['result'] = $data;
          $outputarr['status'] = 0;
         $outputarr['msg'] = "Result found";
            
     print json_encode($outputarr);

     }

     public function appstatuscount()
     {
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id; 
        $section_id  =$request->section_id; 
        $class_id  =$request->class_id; 
        if (($class_id > 0) && ($section_id > 0) ) {      
          $downloadstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw(" students.device_token != '' and students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."' and student_session.section_id = '".$section_id."' ")->count();
          $notstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("(students.device_token = '' or students.app_seen_date = '' ) and students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."' and student_session.section_id = '".$section_id."'")->count();
          $totalstudent = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."' and student_session.section_id = '".$section_id."' " )->count();
        }elseif (($class_id > 0) &&( $section_id == 0)) {  
          $downloadstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw(" students.device_token != '' and students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."' ")->count();
          $notstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("(students.device_token  = '' or students.app_seen_date = '' ) and students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."'   ")->count();
          $totalstudent = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("students.is_active = 'yes' and student_session.session_id = '".$session_id."' and student_session.class_id = '".$class_id."'  " )->count();
        }else{
          $downloadstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw(" students.device_token != '' and students.is_active = 'yes' and student_session.session_id = '".$session_id."' ")->count();
          $notstatus = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("(students.device_token  = '' or students.app_seen_date = '' ) and students.is_active = 'yes' and student_session.session_id = '".$session_id."'  ")->count();
          $totalstudent = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->whereRaw("students.is_active = 'yes' and student_session.session_id = '".$session_id."' " )->count();
        }
        $data['name'] = 'Total Student';
        $data['count'] =  $totalstudent ; 
        $status[] = $data ;
        $data['name'] = 'Total App Installed';
        $data['count'] = $downloadstatus ;
        $status[] = $data ;
        $data['name'] = 'Student (Not Installed)';
        $data['count'] = $notstatus ;
        $status[] = $data ;
        $outputarr['result'] = $status;
        $outputarr['status'] = '1';
        $outputarr['msg'] = "result found";
      }else{ 
        $outputarr['status'] = '0';
        $outputarr['msg'] = "result Not found";
      }
      print json_encode($outputarr);
    }

 public function enquriy(Request $requestserver)
     {
     	$abc = file_get_contents("php://input");
      if (!empty($abc))
       {        
         /*   $request  = json_decode($abc) ;
           $session_id = $request->session_id;  
        	 $pending = SELF::get_enquiry($session_id,0);
        	 $reject = SELF::get_enquiry($session_id,1);
        	 $complete = SELF::get_enquiry($session_id,2);  */        
           $enquirya['pending'] = 0;//$pending ;
           $enquirya['reject'] = 0;//$reject ;
           $enquirya['complete'] = 0;//$complete;
           $outputarr['result'] = $enquirya;
           $outputarr['status'] = '1';
  	      $outputarr['msg'] = "result found";
        }else{
			    $outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	       }  
	     print json_encode($outputarr);
  }

  public function enquriyDetails(Request $requestserver)
     {
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request = json_decode($abc) ;
        $session_id = $request->session_id;  
        $page = $request->page; 
        // $pending = SELF::get_enquirydetails($session_id,0,$page);
        // $reject = SELF::get_enquirydetails($session_id,1,$page);
        // $complete = SELF::get_enquirydetails($session_id,2,$page);            
         $enquirya['pending'] = 0;//$pending ;
         $enquirya['reject'] = 0;//$reject ;
         $enquirya['complete'] =0;// $complete;
         $outputarr['result'] = $enquirya;
         $outputarr['status'] = '1';
         $outputarr['msg'] = "result found";
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
       print json_encode($outputarr);
  }

public function staffAttendancenew()
{
	 $todaydate = date('Y-m-d');
     $absentstaff = DB::table('staff_attendance')->where('staff_attendance.date',$todaydate)->count();
     $totatlstaff = DB::table('staff')->where('staff.is_active',1)->count();
     $presentstaff= $totatlstaff- $absentstaff ;

         $staffAttendance['totalstaff'] = $totatlstaff ;
         $staffAttendance['presentstaff'] = $presentstaff ;
         $staffAttendance['absentstaff'] =  $absentstaff  ; 
         if (!empty($totatlstaff)) {
         	$outputarr['result'] = $staffAttendance;
         	$outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
         else{
			$outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	       }  
	     print json_encode($outputarr);
}

public function studentAttendancenew()
{
	 $todaydate = date('Y-m-d');
     $absentstudent = DB::table('student_attendences')->where('student_attendences.date',$todaydate)->where('student_attendences.attendence_type_id','!=','1')->count();
         
      $totatlstudent = DB::table('students')->join('student_session', 'students.id', '=', 'student_session.student_id')->where('students.is_active','yes')->where('student_session.session_id',$this->current_session)->count();
          $presentstudent= $totatlstudent - $absentstudent ;

         $stuAttendance['totalstudent'] = $totatlstudent ;
         $stuAttendance['presentstudent'] = $presentstudent ;
         $stuAttendance['absentstudent'] = $absentstudent ; 
         if (!empty($totatlstudent)) {
         	$outputarr['result'] = $stuAttendance;
         	$outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
         else{
			$outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	       }  
	     print json_encode($outputarr);
}


public function teacherstimetable(Request $requestserver)
{
	 $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $teacher_id  =$request->teacher_id;       
         
       $teacherstimetable = DB::select("SELECT teacher_timetable.*,staff.name,staff.surname,classes.class,sections.section,subjects.name as subject from teacher_timetable join staff on staff.id = teacher_timetable.teacher_id join classes on classes.id = teacher_timetable.class_id join 
            sections on sections.id = teacher_timetable.section_id join subjects on subjects.id = teacher_timetable.subject_id where teacher_id =$teacher_id");

        $timetable['teacherstimetable'] = $teacherstimetable ; 
         if (!empty($timetable)) 
         {
         	$outputarr['result'] = $timetable;
         	$outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
         else{
			$outputarr['status'] = '0';
	        $outputarr['msg'] = "Data Not found";
	       }  
	   }else {
	      	$outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	   }

	     print json_encode($outputarr);
}


public function events()
{
	 $todaydate = date('Y-m-d h:i:s');            
      $eventlist = DB::select("SELECT * from events where start_date > '$todaydate'") ;
         if (!empty($eventlist))
         {
         	$outputarr['result'] = $eventlist;
         	$outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
    else{
			    $outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	      }  
	     print json_encode($outputarr);
}


public function appointmentsCount()
{
		 $todaydate = date('Y-m-d');
       $totalpendingappointment = DB::table('appointments')->where('appointments.meet_date',$todaydate)->where('appointments.status',0)->count(); 

       $totalapprovedappointment = DB::table('appointments')->where('appointments.meet_date',$todaydate)->where('appointments.status',1)->count();

       $totalrejectedappointment = DB::table('appointments')->where('appointments.meet_date',$todaydate)->where('appointments.status',2)->count();    
          
       $appointments['totalpending'] = $totalpendingappointment ;
       $appointments['approved'] = $totalapprovedappointment ;
       $appointments['rejected'] = $totalrejectedappointment ; 
         if (!empty($appointments)) {
         	$outputarr['result'] = $appointments;
         	$outputarr['status'] = '1';
	        $outputarr['msg'] = "Result found";
         }
         else{
			    $outputarr['status'] = '0';
	        $outputarr['msg'] = "Post data Not found";
	       }  
        
	     print json_encode($outputarr);
}


public function appointments()
{
     $todaydate = date('Y-m-d');
         $appointments['appointments_pending'] = DB::select("SELECT * from appointments where status = 0");            
         $appointments['appointments_reject'] = DB::select("SELECT * from appointments where status = 1");            
         $appointments['appointments_complete'] = DB::select("SELECT * from appointments where status = 2");            

         if (!empty($appointments)) {
          $outputarr['result'] = $appointments;
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";
         }
         else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
        
       print json_encode($outputarr);
}

public function workload()
{
     $todayday = date('w');
         //print_r($todayday); die;
        $teaching_staff = DB::select("SELECT count(staff.id) as teacherscount from staff join staff_roles on staff.id = staff_roles.staff_id where staff_roles.role_id = 2"); 
        $teaching_staff = $teaching_staff[0]->teacherscount;    
          $lecture = 8 ; 
          
          $totallecture = $lecture*$teaching_staff;    

 $todaysLecture = DB::select("SELECT count(teacher_timetable.id) as teacher_timetablecount from teacher_timetable where teacher_timetable.days =$todayday and class_id != 0  and section_id != 0"); 
   $todaysLecture = $todaysLecture[0]->teacher_timetablecount;
 
  $work['workload'] = round(( (100 * $todaysLecture) /$totallecture),2);

         if (!empty($work)) {
          $outputarr['result'] = $work;
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";
         }
         else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
        
       print json_encode($outputarr);
}


  public function feesDetailByCLassSection(Request $requestserver)
   {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id;    
        $class_id  =$request->class_id;    
        $section_id  =$request->section_id;  

        $students = DB::select("SELECT students.*,student_session.promoted , student_session.id as student_session_id ,classes.class, sections.section from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where student_session. session_id = $session_id and students.is_active = 'yes' and student_session.class_id = $class_id and  student_session.section_id = $section_id");
       $a = 0 ;      
       $mainamount = 0 ;
       $mainpaidamount = 0 ;
       foreach ($students as $key => $value){  
             $value->total_fees = 0 ;
             $amount = 0 ;
             $paidamount = 0 ;
            
          //$totalfees = SELF::get_student_fees($value->student_session_id);
       
          // foreach ($totalfees as $key => $totalfee) {

          //      foreach ($totalfee->fees as $key => $fee) {
          //         if ($value->promoted == 1) {
          //          $amount +=  $fee->amount ;                   
          //         }else{
          //          $amount +=  $fee->amount_II ;                 
          //         }
          //        $value->total_fees = $amount ;
                
          //       } 
          //         $mainamount += $amount;
          //       foreach ($totalfee->receipt as $receipts) {
          //                $paidamount += $receipts->amount_detail->amount;                                
          //               }
          //                 $value->total_paidfees = $paidamount ;
          //                 $mainpaidamount += $paidamount;     
          //    }
         }
          $res['feesDetail'] = $students ;
          $res['totalAmount'] = 0;// $mainamount ;
          $res['totalpaidAmount'] = 0;// $mainpaidamount  ;
          $res['totalUnpaidAmount'] = 0;// $mainamount - $mainpaidamount  ;

          $outputarr['result'] = $res ;
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";
     
     }else{
            $outputarr['status'] = '0';
            $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }


public function totalClassFees(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id;    
        $class_id  =$request->class_id;    
        $students = DB::select("SELECT students.*,student_session.promoted , student_session.id as student_session_id ,classes.class, sections.section from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where  student_session. session_id = $session_id and students.is_active = 'yes' and student_session.class_id = $class_id  ");
       $a = 0 ;
       $amount = 0 ;
       $paidamount = 0 ;
       // foreach ($students as $key => $value){     
       //    $totalfees = '' ;
       //   $totalfees = SELF::get_student_fees($value->student_session_id);
       //       foreach ($totalfees as $key => $totalfee) {
       //         foreach ($totalfee->fees as $key => $fee) {
       //            if ($value->promoted == 1) {
       //             $amount +=  $fee->amount ;
       //            }else{
       //             $amount +=  $fee->amount_II ;
       //            }
       //          } 
       //           foreach ($totalfee->receipt as $key => $receiptfee) {
       //              if ($receiptfee->amount_detail->amount >  0) {
       //                   $paidamount += $receiptfee->amount_detail->amount;
       //               }
       //          } 
       //      }
       //   }
         $fesss['totalfee'] = 0 ;//$amount ;
         $fesss['totalpaid'] =  0 ;//$paidamount ;
         $fesss['totalremain'] = 0 ;//$amount - $paidamount ;
          $outputarr['result'] = $fesss; 
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }
    


public function daily_activities(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id = $request->session_id;    
        $class_id  =$request->class_id; 
        $date  =$request->date; 

        $result = DB::select("SELECT students.*,student_session.promoted , student_session.id as student_session_id ,classes.class, sections.section from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where  student_session. session_id = $session_id and students.is_active = 'yes' and student_session.class_id = $class_id  ");
     
          $outputarr['result'] = $result; 
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }

  public function vehicles_list()
  {   
    $result = DB::select("SELECT vehicles.* from vehicles");     
      $outputarr['result'] = $result; 
      $outputarr['status'] = '1';
      $outputarr['msg'] = "Result found";    
     print json_encode($outputarr);
  }
    
public function transportStudentList(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id = $request->session_id;    
        $vehicle_id  =$request->vehicle_id; 
        $result = DB::select("SELECT students.*,vehicles.vehicle_no,student_session.promoted , student_session.id as student_session_id ,classes.class, sections.section from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id 
        join vehicles on student_session.vehicle_id = vehicles.id 
        where students.is_active = 'yes' and vehicles.id = $vehicle_id ");     
          $outputarr['result'] = $result; 
          $outputarr['status'] = '1';
          $outputarr['msg'] = "Result found";     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }

  public function get_enquiry($session_id, $type)
  {  
  	if ($type == 0) {
       return $result = DB::table('enquiry')->whereRaw("session_id = $session_id and (status = 3 or status=$type)")->count();
  	}elseif($type == 1) {
  		return $result = DB::table('enquiry')->whereRaw("session_id = $session_id and (status = 4 or status=$type) ")->count();
  	}else{
  	   return $result = DB::table('enquiry')->whereRaw("session_id= $session_id and  status =$type")->count();
  	 }
  }

 public function get_enquirydetails($session_id, $type, $page=0)
  { 
    $limit = 200 ; 
    $offset = $page* $limit ;   
    if ($type == 0) {
       return $result = DB::table('enquiry')->whereRaw("session_id = $session_id and (status = 3 or status=$type)")->offset($offset)->limit($limit)->get();
    }elseif($type == 1) {
      return $result = DB::table('enquiry')->whereRaw("session_id = $session_id and (status = 4 or status=$type) ")->offset($offset)->limit($limit)->get();
    }else{
       return $result = DB::table('enquiry')->whereRaw("session_id= $session_id and  status =$type")->offset($offset)->limit($limit)->get();
     }
  }


  function get_student_fees($student_session_id)
   {
       $result =DB::select("SELECT `student_fees_master`.*,fee_groups.name FROM `student_fees_master` INNER JOIN fee_session_groups 
       on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  
       WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`" );     
          if (!empty($result)) {
              foreach ($result as $result_key => $result_value){
                  $fee_session_group_id = $result_value->fee_session_group_id;
                  $student_fees_master_id = $result_value->id;
                  $result_value->fees= SELF::getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);
                  $result_value->receipt = SELF::getreciept($student_fees_master_id); 
                }                                  
              }
          return $result;
   }

public function getDueFeeByFeeSessionGroup($fee_session_groups_id, $student_fees_master_id) {
        $result = DB::select("SELECT student_fees_master.*,fee_groups_feetype.id as `fee_groups_feetype_id`,fee_groups_feetype.amount,fee_groups_feetype.shows,
        fee_groups_feetype.amount_II,fee_groups_feetype.due_date,fee_groups_feetype.fee_groups_id,student_fees_deposite.receipt_no,fee_groups.name,
        fee_groups_feetype.feetype_id,feetype.code as type ,feetype.type as code,feetype.description as type2,feetype.fine_apply,
        FROM `student_fees_master`
        INNER JOIN fee_session_groups on fee_session_groups.id = student_fees_master.fee_session_group_id 
        INNER JOIN fee_groups_feetype on fee_groups_feetype.fee_session_group_id = fee_session_groups.id 
        INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id
        INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id 
        WHERE student_fees_master.fee_session_group_id =".$fee_session_groups_id." AND student_fees_master.id=" . $student_fees_master_id . " 
        order by fee_groups_feetype.due_date asc");       
             return $result;
    }


 public function getreciept($student_fees_master_id)
     {     
       $result =DB::select(" SELECT fee_deposite_receipt.*,fee_groups_feetype.institute from fee_deposite_receipt 
        join fee_groups_feetype on fee_groups_feetype.id = fee_deposite_receipt.fee_groups_feetype_id
        where student_fees_master_id =  $student_fees_master_id ");
        foreach ($result as $key => $value) {
        $value->amount_detail = json_decode($value->amount_detail);
        }
        return $result ;
     }

     
     public function teachersActivities()
     {
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {  
        $request  = json_decode($abc) ;
        $date = $request->date; 
         if (!empty($date)) {
           $base = 'http://softwares.centralacademyjabalpur.com/';
      $teachers =DB::select("SELECT staff.id, staff.name,CONCAT('$base',staff.image) as image, staff.surname from staff join staff_roles on staff.id = staff_roles.staff_id where staff_roles.role_id = 2 ");
    /*  print_r($teachers);
      die;*/
      if (!empty($teachers)) {
        $date = $date.' 00:00:00';
        $dateused = date('Y-m-d').' 00:00:00';      
         foreach ($teachers as $key => $teacher){
             $teacher->notice_count = DB::table('messages')->SELECT('messages.id')->whereRaw("messages.title like '%Complaint%' and messages.teacher_id = '$teacher->id' and messages.created_at > '$date' ")->count();

             $teachernotice_used = DB::table('messages')->SELECT('messages.id')->whereRaw("messages.title like '%Complaint%' and messages.teacher_id = '$teacher->id' and messages.created_at > '$dateused' ")->count();
             if ($teachernotice_used > 0) {
               $teacher->notice_used = 'Yes' ;
               $teacher->today_notice_send= $teachernotice_used ;
             }else{
               $teacher->notice_used = 'No' ;
               $teacher->today_notice_send= $teachernotice_used ;
             }

             $teacher->homework_count = DB::table('messages')->SELECT('messages.id')->whereRaw("messages.title like '%Homework%' and messages.teacher_id = '$teacher->id' and messages.created_at > '$date' ")->count(); 

             $teacherhomework_used = DB::table('messages')->SELECT('messages.id')->whereRaw("messages.title like '%Homework%' and messages.teacher_id = '$teacher->id' and messages.created_at > '$dateused' ")->count(); 
               
                 if ($teacherhomework_used > 0) {
                 $teacher->homework_used = 'Yes';
                 $teacher->today_homework_send= $teacherhomework_used;
               }else{
                 $teacher->homework_used = 'No';
                 $teacher->today_homework_send= $teacherhomework_used;
                 }   
             }

                $outputarr['result'] = $teachers;
                $outputarr['status'] = '1';
                $outputarr['msg'] = "Result found";
          }
          else
          {
              $outputarr['status'] = '0';
              $outputarr['msg'] = "Result Not found";
          } 
          }else{
             $outputarr['status'] = '0';
            $outputarr['msg'] = "Request Not found";
          } 
      }
        else{
            $outputarr['status'] = '0';
            $outputarr['msg'] = "Postdata Not found";
         }    
         print json_encode($outputarr);
     }


public function totalTransportFees(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id;    
        $students = DB::select("SELECT students.*,student_session.promoted, student_session.id as student_session_id ,classes.class, sections.section, student_session.transport_fees from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where student_session. session_id = $session_id and students.is_active = 'yes'");
       $a = 0 ;
       $amount = 0 ;
       $paidamount = 0 ;
       foreach ($students as $key => $value){               
           $amount  += $value->transport_fees ;           
           $paidfees = DB::select("SELECT SUM(transport_fees) as paidtransportFee from transport_fee_deposite where student_session_id = '".$value->student_session_id."' ") ;
           $value->paidamount = $paidfees[0]->paidtransportFee ;
           $paidamount += $paidfees[0]->paidtransportFee ; 
         }
         $fesss['classList'] = DB::select("SELECT * from classes where 1");
         $fesss['totalfee'] = $amount ;
         $fesss['totalpaid'] = $paidamount;
         $fesss['totalremain'] =$amount - $paidamount ;
         $outputarr['result'] = $fesss; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Result found";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }
    


public function totaltransportClassFees(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id;    
        $class_id  =$request->class_id;    
        $students = DB::select("SELECT students.*,student_session.promoted, student_session.id as student_session_id ,classes.class, sections.section, student_session.transport_fees from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where student_session. session_id = $session_id and students.is_active = 'yes' and student_session.class_id = $class_id  ");
       $a = 0 ;
       $amount = 0 ;
       $paidamount = 0 ;
       foreach ($students as $key => $value){               
           $amount  += $value->transport_fees ;           
           $paidfees = DB::select("SELECT SUM(transport_fees) as paidtransportFee from transport_fee_deposite where student_session_id = '".$value->student_session_id."' ") ;
           $value->paidamount = $paidfees[0]->paidtransportFee ;
           $paidamount += $paidfees[0]->paidtransportFee ; 
         }
         $fesss['sectionList'] = DB::select("SELECT sections.* from class_sections join sections on class_sections.section_id = sections.id where class_id = $class_id");
         $fesss['totalfee'] = $amount ;
         $fesss['totalfee'] = $amount ;
         $fesss['totalpaid'] = $paidamount;
         $fesss['totalremain'] =$amount - $paidamount ;
         $outputarr['result'] = $fesss; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Result found";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }
    


public function totaltransportClassSectionFees(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;
        $session_id  =$request->session_id;    
        $class_id  =$request->class_id;    
        $section_id  =$request->section_id;    
        $students = DB::select("SELECT students.*,student_session.promoted, student_session.id as student_session_id ,classes.class, sections.section, student_session.transport_fees from students 
        join student_session on students.id = student_session.student_id 
        join classes on student_session.class_id = classes.id  
        join sections on student_session.section_id = sections.id where student_session. session_id = $session_id and students.is_active = 'yes' and student_session.class_id = $class_id and student_session.section_id = $section_id  ");
       $a = 0 ;
       $amount = 0 ;
       $paidamount = 0 ;
       foreach ($students as $key => $value){               
           $amount  += $value->transport_fees ;           
           $paidfees = DB::select("SELECT SUM(transport_fees) as paidtransportFee from transport_fee_deposite where student_session_id = '".$value->student_session_id."' ") ;
           $value->paidamount = $paidfees[0]->paidtransportFee ;
           $paidamount += $paidfees[0]->paidtransportFee ; 
           if ($value->transport_fees > 0 ) {
              $studentList[] = $value;
           }          
         }        
         $fesss['StudentList'] = $studentList ;
         $fesss['totalfee'] = $amount ;
         $fesss['totalpaid'] = $paidamount;
         $fesss['totalremain'] =$amount - $paidamount ;
         $outputarr['result'] = $fesss; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Result found";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }

    public function complaintList()
    {
      $complaintList = DB::select("SELECT parent_teacher_message.*, students.firstname, students.lastname from parent_teacher_message join students on students.id = parent_teacher_message.student_id ");
      if (!empty($complaintList)) {        
        $outputarr['result'] = $complaintList;
        $outputarr['status'] = '1';
        $outputarr['msg'] = "Result found";     
        }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
    }

public function principalseen(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc);      
        $complain_id  =$request->complain_id;    
        $students = DB::table('parent_teacher_message ')
                   ->where('id', $complain_id)
                   ->update(['principal_seen' => 1]);        
         $complaintList = DB::select("SELECT parent_teacher_message.*, students.firstname, students.lastname from parent_teacher_message join students on students.id = parent_teacher_message.student_id ");
         $outputarr['result'] = $complaintList ; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Updated Successfully";
     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }

public function updatecomplainstatus(Request $requestserver)
  {             
      $abc = file_get_contents("php://input");
      if (!empty($abc))
      {       
        $request  = json_decode($abc) ;      
        $complain_id  =$request->complain_id;    
        $students = DB::table('parent_teacher_message ')
                    ->where('id', $complain_id)
                    ->update(['status' => 1,'principal_seen' => 1]);        
         $complaintList = DB::select("SELECT parent_teacher_message.*, students.firstname, students.lastname from parent_teacher_message join students on students.id = parent_teacher_message.student_id ");
         $outputarr['result'] = $complaintList ; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Updated Successfully";     
     }else{
          $outputarr['status'] = '0';
          $outputarr['msg'] = "Post data Not found";
         }  
     print json_encode($outputarr);
  }


/* new api listed */
  


public function videoListAll()
{
 $result = DB::select("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,tvideo.view_count,
 tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
 from tvideo 
 left join topics on topics.id = tvideo.topic 
 left join subjects on subjects.id = tvideo.subject_id ");
 if(!empty($result))
         {
           foreach ($result as $key => $video) {
             $video->like = DB::select("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ");
             if($video->video_type)
             $video->url =  "https://s3-ap-south-1.amazonaws.com/schooleye-bucket/ggis/upload/videos/".$video->url;
          }
         $outputarr['result'] = $result;                       
         $outputarr['msg'] = "success result found";  
         $outputarr['status'] = '1';
   } else
       {
       $outputarr['status'] = '0';
       $outputarr['msg'] = " no videos list found";
      
     }
     print json_encode($outputarr);
}
 

public function SubjectList(Request $requestserver)
{             
    $abc = file_get_contents("php://input");
    if (!empty($abc))
    {       
      $request  = json_decode($abc) ;      
      $class  =$request->class;
      $section  =$request->section;       
      $session =  $this->current_session;  
      
      $subjects = DB::SELECT("SELECT subjects.* from subjects left join teacher_subjects on teacher_subjects.subject_id = subjects.id
                               left join class_sections on teacher_subjects.class_section_id = class_sections.id
                               where class_sections.class_id = '$class' and class_sections.section_id = '$section' and teacher_subjects.session_id = '$session'");  
                               if(!empty($subjects)){ 
                                 $outputarr['result'] = $subjects ; 
                                 $outputarr['status'] = '1';
                                 $outputarr['msg'] = "Subject List Found";  }
                               else{
                                 $outputarr['status'] = '0';
                                 $outputarr['msg'] = "Subjects Not found";
                               }  
       
   }else{
        $outputarr['status'] = '0';
        $outputarr['msg'] = "Post data Not found";
       }  
   print json_encode($outputarr);
}


public function TopicList(Request $requestserver)
{             
    $abc = file_get_contents("php://input");
    if (!empty($abc))
    {       
      $request  = json_decode($abc) ;      
      $subject_id  =$request->subject_id;
      $subjects = DB::SELECT("SELECT * from topics where subject_id= '$subject_id'");    
      $outputarr['result'] = $subjects ; 
      $outputarr['status'] = '1';
      $outputarr['msg'] = "Topic  List Found";     
   }else{
        $outputarr['status'] = '0';
        $outputarr['msg'] = "Post data Not found";
       }  
   print json_encode($outputarr);
}

public function ClassRoomList(Request $requestserver)
{             
    $date =  date('d-m-Y');
   
      $classRoom = DB::SELECT("SELECT * from classroom where date = '$date'");    
      $outputarr['result'] = $classRoom ; 
      $outputarr['status'] = '1';
      $outputarr['msg'] = "Class-List Found";     
  
   print json_encode($outputarr);
   
}


/*public function searchvideo(Request $requestserver)
{             
    $abc = file_get_contents("php://input");
    if (!empty($abc))
    {       
      $request  = json_decode($abc) ;      
      $subject_id  =$request->subject_id;
      $class_id  =$request->class_id;
      $topic_id  =$request->topic_id;
      $result = DB::SELECT("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,tvideo.view_count,
      tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
      from tvideo 
      left join topics on topics.id = tvideo.topic 
      left join subjects on subjects.id = tvideo.subject_id  where class_id= '$class_id' and topic= '$topic_id' and tvideo.subject_id= '$subject_id'");    
        if(!empty($result))
        {
             foreach ($result as $key => $video) {
               $video->like = DB::select("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ");
               if($video->video_type)
               $video->url =  "https://s3-ap-south-1.amazonaws.com/schooleye-bucket/caj/upload/videos/".$video->url;
                 }
         $outputarr['result'] = $result ; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Videos  List Found";     
         }
         else{
           $outputarr['status'] = '0';
           $outputarr['msg'] = "Data Not found";
         }
   }
   else{
        $outputarr['status'] = '0';
        $outputarr['msg'] = "Post data Not found";
      }  
      print json_encode($outputarr);
 }*/

 /*public function VideoDetails(Request $requestserver)
 {             
     $abc = file_get_contents("php://input");
     if (!empty($abc))
     {       
       $url = "http://kmps.edu.in/admin/";            
       $request  = json_decode($abc) ;      
       $video_id  =$request->video_id;
       $messages = DB::SELECT("SELECT videomessage.*,CONCAT(students.firstname,' ',students.lastname) as student_name,CONCAT('$url',students.image) as student_image,
       CONCAT(staff.name,' ',staff.surname) as teacher_name, CONCAT('$url',staff.image) as teacher_image  from videomessage
        left join students on students.id = videomessage.student_id  
        left join staff on staff.id = videomessage.teacher_id  
       where videomessage.video_id = '$video_id'");
       
       
       $videos = DB::SELECT("SELECT tvideo.id,CONCAT('https://s3-ap-south-1.amazonaws.com/schooleye-bucket/caj/upload/videos/','tvideo.url') as url ,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,
       tvideo.view_count,tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
       from tvideo left join topics on topics.id = tvideo.topic left join subjects on subjects.id = tvideo.subject_id 
       where tvideo.id= '$video_id'");   
           $videoid = $videos[0]->id;
        
       $videoslike = DB::SELECT("SELECT count(videolike.id) as likecount from videolike where video_id = '$videoid'");       

       
       
       $a['comments'] =$messages ;
       $a['videos'] = $videos ; 
       $a['likes'] = $videoslike ; 
       $outputarr['result'] = $a;
       $outputarr['status'] = '1';
       $outputarr['msg'] = "Topic  List Found";     
    }else{
         $outputarr['status'] = '0';
         $outputarr['msg'] = "Post data Not found";
        }  
    print json_encode($outputarr);
 }
*/
/* public function videoByTeacher(Request $requestserver)
{             
    $abc = file_get_contents("php://input");
    if (!empty($abc))
    {       
      $request  = json_decode($abc) ;      
      $teacher_id  =$request->teacher_id;     
      $result = DB::SELECT("SELECT tvideo.id,tvideo.url,tvideo.subject_id,tvideo.topic,tvideo.teacher_id,tvideo.tag,tvideo.name ,tvideo.status,tvideo.view_count,
      tvideo.created_at, tvideo.video_type,tvideo.class_id,topics.name as topic_name,subjects.name as subject_name 
      from tvideo 
      left join topics on topics.id = tvideo.topic 
      left join subjects on subjects.id = tvideo.subject_id  where teacher_id= '$teacher_id'");    
        if(!empty($result))
        {
             foreach ($result as $key => $video) {
               $video->like = DB::select("SELECT count(videolike.id) as likecount from videolike where video_id = '$video->id' ");
               if($video->video_type)
               $video->url =  "https://s3-ap-south-1.amazonaws.com/schooleye-bucket/caj/upload/videos/".$video->url;
                 }
         $outputarr['result'] = $result ; 
         $outputarr['status'] = '1';
         $outputarr['msg'] = "Videos  List Found";     
         }
         else{
           $outputarr['status'] = '0';
           $outputarr['msg'] = "Data Not found";
         }
   }
   else{
        $outputarr['status'] = '0';
        $outputarr['msg'] = "Post data Not found";
      }  
      print json_encode($outputarr);
 }*/

   /* public function attendencedone($teacherid)
    {
      $result =DB::select("SELECT fee_deposite_receipt.*,fee_groups_feetype.institute from fee_deposite_receipt 
       join fee_groups_feetype on fee_groups_feetype.id = fee_deposite_receipt.fee_groups_feetype_id
       where student_fees_master_id =  $student_fees_master_id ");
      
       return $result ;
    }*/

  

  
    /* public function createchatRoom(Request $requestserver)
     {             
         $abc = file_get_contents("php://input");
         if (!empty($abc))
         {       
           $request  = json_decode($abc) ;      
           $title  =$request->title;
           $title  =$title.date('Y-m-d h:i:s');
           $chiper = md5($title);
           $url = "https://meet.helpeye.in/".$chiper;
           $data['title'] =  $title ;    
           $data['link'] =$url;        
           $data['classroom_id'] =0;        
           $data['classroom_active'] =1;        
   
              $classcreated = DB::table('classroomdata')->insertGetId( $data);             
              $senddata1 = array("title"=>$title , "url"=>$url);
              $burl = "http://schooleye.org/schooleye/mobileapi/Android_api/insertRoomcurl";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $burl);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $senddata1);
 
             $result = curl_exec($ch);
 
              $outputarr['url'] = $url ; 
              $outputarr['status'] = '1';
              $outputarr['msg'] = "Room Created  ";     
              }
              else{
                 $outputarr['status'] = '0';
                 $outputarr['msg'] = "Failed to create";
                }         
           print json_encode($outputarr);
      }*/


    /*/  public function createTeacherRoom(Request $requestserver)
      {             
          $abc = file_get_contents("php://input");
          if (!empty($abc))
          {       
            $request  = json_decode($abc) ;      
            $title  =$request->title;
            $teachersList  =$request->teachersList;
           // print_r($request); die;
          //  file_put_contents('bacs.txt',$teachersList);
            $title = $title.date('Y-m-d h:i:s');
            $chiper = md5($title);
            $url = "https://meet.helpeye.in/".$chiper;
            $data['title'] =  $title ;    
            $data['link'] =$url;        
            $data['classroom_id'] =0;        
            $data['classroom_active'] =1;              
            $teach_id = json_encode($teachersList )   ;
            $senddata['teacher_ids'] = $teach_id;
            $senddata['url'] = $url ;
            $senddata['title'] =  $title ; 
            $senddata['chiper'] =  $chiper ; 
             $classcreated = DB::table('classroomdata')->insertGetId( $data);       
               
               $burl = "http://softwares.centralacademyjabalpur.com/mobileapp/Android_api/createteacherRoomprincipal";              
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL, $burl);
                   curl_setopt($ch, CURLOPT_POST, true);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                   curl_setopt($ch, CURLOPT_POSTFIELDS, $senddata);
                  $result = curl_exec($ch);
                  //print_r($result); die;
               $outputarr['url'] = $url ; 
               $outputarr['status'] = '1';
               $outputarr['msg'] = "Teachers Room Created  ";     
               }
               else{
                  $outputarr['status'] = '0';
                  $outputarr['msg'] = "Failed to create";
                 }         
            print json_encode($outputarr);
       }*/

 /*public function createClassRoom(Request $requestserver)
    {             
     $abc = file_get_contents("php://input");
     if (!empty($abc))
     {       
       $request  = json_decode($abc);      
       $senddata['session_id']  =$request->session_id;     
       $senddata['class']  =$request->class_id;     
       $senddata['section']  =$request->section_id;
       $senddata['teacher_id']  =$request->teacher_id;
       $senddata['title']  =$request->title;
    
         $url = "http://softwares.centralacademyjabalpur.com/mobileapp/Android_api/createRoomprincipal";
   
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_POST, true);
         //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         // Disabling SSL Certificate support temporarly
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $senddata);
         // Execute post
         $result = curl_exec($ch);
      if($result){
        echo $result;
      }
      else{
            $outputarr['status'] = '0';
            $outputarr['msg'] = "Data Not found";
            print json_encode($outputarr);
            }
    } // main if 
    else{
         $outputarr['status'] = '0';
         $outputarr['msg'] = "Post data Not found";
         print json_encode($outputarr);
         }  
      
}*/


    //----------------------End class----------------------///
}
