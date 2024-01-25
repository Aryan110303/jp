<!DOCTYPE html>
<html lang="en">
<head>
    <title> MARKSHEET </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <?php //print_r($student); die; ?>
</head>
<body style="font-family: sans-serif;margin: 0px;padding: 0px;color: #000;font-size: 13px" >
<div class="wrapper" style="display: inline-block;">
<div style=" background: url(<?php echo base_url('uploads/background-img.png') ?> ) no-repeat center center;height: 100vh; width: 100%;">
    <table cellpadding="0" cellspacing="0" width="100%" style="display: inline-block">
        <tbody>
        <tr>
            <td ><img src="<?php echo base_url('uploads/logo2.jpg')?>" alt="Central Academy" style="height: 100px"></td>
            <td style="text-align: center;text-transform: uppercase">
                <div style="color: #ef3035;font-weight: 600;font-size: 22px;">Central Academy Senior Secondary School  </div>
                <div style="font-size: 12px;font-weight: bold;">
                    Kanchan Vihar, Vijay Nagar, Jabalpur(M.P.), Phone : 0761-4928811 <br>
                 Email : principal@centralacademyjabalpur.com, Website : www.centralacademyjabalpur.com <br>
                (Affiliated to CBSE, New Delhi)
                </div>
            </td>
        </tr>

        <tr style="font-size:13px;font-weight: 600;line-height: 22px;">
            <td > Affiliation No.: 1030293 </td>
            <td style="float: right">  School Code No. : 14151 </td>
        </tr>

        </tbody>
    </table>

    <div style="text-align: center;font-size: 15px;color: green;font-weight: bold;"> SESSION : <?php $sess = $this->Receive_model->get_sessionbyid($student["student_session_id"]) ; echo $sess->session ; ?> </div>

    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <table>

                        <tbody>
                           
                           
                            <tr><td  style="font-weight: bold"><?php echo $this->lang->line('roll_no') . ": " . $student['roll_no']; ?></td></tr>
                            <tr><td  style="font-weight: bold"> <?php echo $this->lang->line('name') . ": " . $student['firstname']." ".$student['lastname']; ?></td></tr>
                            <tr><td  style="font-weight: bold">  <?php echo $this->lang->line('admission_no') . ": " . $student['admission_no']; ?></td></tr>
                            <tr><td  style="font-weight: bold"> <?php echo $this->lang->line('class') ."/ Section" . ": " . $student['class']."(".$student["section"].")"; ?></td></tr>
                            <tr>
  
                        </tbody>
                    </table>
                </td>
                <td style="float: right">
                    <table>
                        <tbody>
                        <tr><td  style="font-weight: bold">  </td><td> </td> <td rowspan="5" style="width: 100px;text-align: center;border: 1px solid #464646;height: 100px;">
                           <img style="width: 90px; height: 100px; padding: 2px; " src="<?php echo base_url($student['image']) ;?>">
                                </td></tr>
                        <tr><td  style="font-weight: bold">  Date of Birth :</td><td><?php echo $student["samagra_id"]?></td></tr>
                        <tr><td  style="font-weight: bold">   SSSM ID:</td><td> <?php echo $student["samagra_id"]?></td></tr>
                        <tr><td  style="font-weight: bold"> Aadhar  :</td><td><?php echo $student["adhar_no"]?> </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
<?php
if (empty($examSchedule)) {
    ?>  <div class="alert alert-danger">
                                        No Exam Found.
                                    </div>
    <?php
} else { 

?>

<table style="text-align: center">  
 <tbody> <tr>

    <td>  <div style="margin-bottom: 18px;"> - </div>
         <b style="margin-bottom: 5px;"> Subjects</b> 
         <table>
    <?php  

     $exam_results_array = $examSchedule[0]['exam_result']; 
    foreach ($exam_results_array as $key => $val) {
         ?>
    <tr>  

          <td>  <?php  echo $val['exam_name'] . " (" . substr($val['exam_type'], 0, 2) . ".) ";   ?></td>
    </tr>
<?php }?>
</table></td>
    <?php
   foreach ($examSchedule as $key => $value) { ?>     



          <td>
             <h3><?php echo strtoupper($value['exam_name']); ?></h3> 
             <table class="table table-striped table-bordered table-hover example">
                                                    <thead>
                                                        <tr>
                                                           <th>
          
            <?php  echo $this->lang->line('full_marks'); ?>
                                                            </th>
                                                            <th>
            <?php  echo $this->lang->line('passing_marks'); ?>
                                                            </th>
                                                            <th>
            <?php echo $this->lang->line('obtain_marks'); ?>
                                                            </th>
                                                            <th class="text text-right">
            <?php echo $this->lang->line('result'); ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
            <?php
            $obtain_marks = 0;
            $total_marks = 0;
            $result = "Pass";
            $exam_results_array = $value['exam_result'];

            $s = 0;
            foreach ($exam_results_array as $result_k => $result_v) {
                $total_marks = $total_marks + $result_v['full_marks'];
                ?>
                                                            <tr>
                                                               
                                                                <td><?php echo $result_v['full_marks']; ?></td>
                                                                <td><?php echo $result_v['passing_marks']; ?></td>
                                                                <td>
                <?php
                if ($result_v['attendence'] == "pre") {
                    echo $get_marks_student = $result_v['get_marks'];
                    $passing_marks_student = $result_v['passing_marks'];
                    if ($result == "Pass") {
                        if ($get_marks_student < $passing_marks_student) {
                            $result = "Fail";
                        }
                    }
                    $obtain_marks = (int) $obtain_marks + (int) $result_v['get_marks'];
                } else {
                    $result = "Fail";
                    echo ($result_v['attendence']);
                }
                ?>
                                                                </td>
                                                                <td class="text text-center">
                <?php
                if ($result_v['attendence'] == "pre") {
                    $passing_marks_student = $result_v['passing_marks'];

                    if ($get_marks_student < $passing_marks_student) {
                        echo "<span class='label pull-right bg-red'>" . $this->lang->line('fail') . "</span>";
                    } else {
                        echo "<span class='label pull-right bg-green'>Pass</span>";
                    }
                } else {
                    echo "<span class='label pull-right bg-red'>" . $this->lang->line('fail') . "</span>";
                    $s++;
                }
                ?>
                                                                </td>
                                                            </tr>
                <?php
                if ($s == count($exam_results_array)) {
                    $obtain_marks = 0;
                }
            }
            ?>
  
              <tr class="hide">
              
                                               
                                                            <td>
            <?php
            if ($result == "Pass") {
                ?>
                                                                    <b class='text text-success'><?php echo $this->lang->line('result') . ": " . $result; ?></b>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <b class='text text-danger'><?php echo $this->lang->line('result') . ": " . $result; ?></b>
                                                                    <?php
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                echo $this->lang->line('grand_total') . ": " . $obtain_marks . "/" . $total_marks;
                                                                ;
                                                                ?></td>
                                                            <td><?php
                                                                $foo = ($obtain_marks * 100) / $total_marks;
                                                                echo $this->lang->line('percentage') . ": " . number_format((float) $foo, 2, '.', '')."%";
                                                                ?></td>
                                                            <td><?php
                                                                if (!empty($gradeList)) {
                                                                    foreach ($gradeList as $key => $value) {
                                                                        if ($foo >= $value['mark_from'] && $foo <= $value['mark_upto']) {
                                                                            ?>
                                                                            <?php echo $this->lang->line('grade') . " : " . $value['name']; ?>
                                                                            <?php
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                ?></td>

                                                        </tr>


                                                    </tbody>
                                                </table>
         </td>
   <?php }?>
      </tr>
</tbody>
 </table>

 <?php  } 
        ?> 


    <table style=" border-collapse: collapse;width: 100%" border="1">
        <tbody>
          

        </tbody>
    </table>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td>
                <table width="50%">
                    <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                        <td style="background: #fff;display: inline-block;margin-bottom: -5px;">Class Teacher's Remarks &nbsp;</td>
                    </tr>
                    </tbody></table>
            </td>
            <td>

            </td>
            <td>
                <table width="100%">
                    <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                        <td style="background: #fff;display: inline-block;margin-bottom: -5px;"> Attendance &nbsp; </td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="margin: 0px;padding: 0px"> <h4><b> </b></h4> </div>

    <table style="width: 100%">
        <tbody>
            <tr>
                <td>Place : <br> Date..............</td>

                <td style="text-align: center">Signature of class teacher </td>

                <td style="float: right"> Signature of Principal</td>
            </tr>
        </tbody>
    </table>
</div>
</div>


</body>
</html>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

<script>
    $( document ).ready(function() {  
      window.print();
});

</script>