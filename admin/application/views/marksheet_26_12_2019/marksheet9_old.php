
<!DOCTYPE html>
<html lang="en">
<head>
    <title> MARKSHEET </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <?php //print_r($student); die; ?>
</head>
<style type="text/css">
   td {
       padding: 5px;
    }
    table{
        border-collapse: collapse;
    }
      table td table{       
        text-align: center;
        width: 100%;
        border-collapse: collapse;
    }
</style>

<body style="font-family: sans-serif;margin: 0px;padding: 0px;color: #000;font-size: 13px" >
<div >
<div style=" background: url(<?php echo base_url('uploads/background-img.png') ?> ) no-repeat center center;height: 100vh; width: 100%;">
    <table cellpadding="0" cellspacing="0" width="100%" >
        <tbody>
        <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0">
         <tr>
          <td width="100%" valign="top" align="center" style="height: 300px;"></td>
        </tr>
       
      </table>
    </td>
  </tr>

        <tr style="font-size:13px;font-weight: bold;line-height: 22px;">
             <td colspan="2">
                  <table width="100%">
                      <tr>
                           <td  style="text-align: left; "> <div>Affiliation No.: 1030293 </div>   </td>
                           <td style="text-align: center"> Session : 2018-19</td>
                           <td style="text-align: right; ">   School Code No. : 14151 </td>
                      </tr>

                  </table>
             </td>
           
           
        
    
        </tr>

        </tbody>
    </table>

<table style="width: 100%">
        <tbody>
            <tr>
                <td style="float: left">
                    <table style=" text-align: left;">

                        <tbody>
                           <?php $rollno = $student['rollno'] ;
                           $romans = array(        
                             
                            'I' => 1000,
                            'II' =>2000,
                             'III' =>3000,
                            'IV' => 4000,
                            'V' => 5000,
                              'VI'=>6000,
                             'VII'=>7000,
                             'VIII'=>8000,
                            'IX' => 9000,
                             'X' => 10000,
                            'XI' => 11000,
                            'XII' => 12000,
                        );
                       
                        $roman = $student['class'] ;  
                        foreach($romans as $key => $value) {
                        
                        if($roman == $key){
                            $promoted = $value +1000 ; 
                            $rollno = $value + $rollno;
                         
                        }
                          if ($promoted == $value) {
                            $promotedTo =  $key ; 
                          }
                       
//echo $value .'<br>';

                        
                        }
                         ?>
                            <tr><td  style="font-weight: bold">  <?php echo "Scholar No." . ": " . $student['scholar_no']; ?></td></tr>
                            <tr><td  style="font-weight: bold"><?php echo "Roll No" . ": " .$rollno ; ?></td></tr>
                            <tr><td  style="font-weight: bold"> <?php echo" Student's Name" . ": " . $student['name']; ?></td></tr>
                            <tr><td  style="font-weight: bold"> <?php echo" Father's Name" . ": " . $student['fname']; ?></td></tr>
                           
                                                    
  
                        </tbody>
                    </table>
                </td>
                <td style="float: right">
                    <table style=" text-align: left;">
                        <tbody>
                        <tr><td  style="font-weight: bold">  </td>
                            <td> </td> 
                            <td rowspan="5" style="width: 100px;text-align: center;border: 1px solid #464646;height: 100px;">
                            <img style="width: 100px; height: 100px; padding: 2px; " src="<?php echo base_url($student['image']) ;?>">
                                </td></tr>
                                 <tr><td  style="font-weight: bold"> <?php echo  "Class/ Section" . ": " . $student['class']."(".$student["section"].")"; ?></td></tr>
                        <tr><td  style="font-weight: bold">  Date of Birth : <?php  $date = str_replace('/', '-', $student["dob"]);
                                                                            echo date('d-m-Y', strtotime($date));       ?></td></tr>
                       
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

   


   <table width="100%" border="1" >
    <tr>
        <td style="width:200px;">
            <table width="100%" >
                <tr ><td colspan="2"  style="width:200px;">SUBJECT</td></tr> 
                <tr><td colspan="2">&nbsp;</td></tr> 
                
            </table>
         </td>
         <td style="width:200px;">
             <table width="100%" border="1"  >
                <tr><td >PERIODIC &nbsp;
                 (20) &nbsp; </td><td >FINAL &nbsp; (80)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr> 
                <tr><td>Max Obt. </td><td>Marks Obt. </td></tr>
            </table>
        </td>
         <td style="width:300px;">
             <table width="100%" border="1"   >
                <tr><td colspan="2">GRAND TOTAL</td><td colspan="2">YEARS AVERAGE</td></tr> 
                <tr><td>Max Marks</td><td>Marks Obt. </td><td>Max Marks</td><td>Marks Obt. </td></tr>
            </table>
        </td>

    </tr>   

    
   </table>
   
    <table width="100%"  >
    <tr>
        <td style="width:200px;">
            <table width="100%" border="1"  >
                <?php //  foreach ($subjects as $key => $value) {
                   ?>
                     <tr style="width:200px;"><td colspan="2" style="width:200px;">ENGLISH</td></tr> 
                     <tr  style="width:200px;"><td colspan="2" style="width:200px;">HINDI</td></tr> 
                     <tr  style="width:200px;"><td colspan="2" style="width:200px;">INFO. TECH.</td></tr> 
                     <tr  style="width:200px;"><td colspan="2" style="width:200px;">MATHS</td></tr> 
                     <tr  style="width:200px;"><td colspan="2" style="width:200px;">SCIENCE</td></tr> 
                     <tr  style="width:200px;"><td colspan="2" style="width:200px;">SOCIAL SCIENCE</td></tr> 
                   <?php //} ?>
                
            </table>
         </td>
         <td style="width:200px;">
          

             <table width="100%" border="1" >
                <tr>                     
                   <td>
                    <?php  echo $all_exam['ENGLISH'] ; ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['ENGLISH'] ; 
                     $totyear['ENGLISH'] =  $all_exam['ENGLISH'] + $final_exam['ENGLISH'] ; 

                    ?>  
                   </td>
                 </tr>
                  <tr>                     
                   <td>
                    <?php  echo $all_exam['HINDI'] ;
                    $totyear['HINDI'] =  $all_exam['HINDI'] + $final_exam['HINDI'] ; 
                    ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['HINDI'] ; ?>  
                   </td>
                 </tr>
                  <tr>                     
                   <td>
                    <?php  echo $all_exam['INFO. TECH.'] ; ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['INFO. TECH.'] ;
                     $totyear['INFO. TECH.'] =  $all_exam['INFO. TECH.'] + $final_exam['INFO. TECH.'] ; 
                      ?>  
                   </td>
                 </tr>
                  <tr>                     
                   <td>
                    <?php  echo $all_exam['MATHS'] ; ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['MATHS'] ;

                     $totyear['MATHS'] =  $all_exam['MATHS'] + $final_exam['MATHS'] ;  ?>  
                   </td>
                 </tr>
                  <tr>                     
                   <td>
                    <?php  echo $all_exam['SCIENCE'] ; ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['SCIENCE'] ;
                    $totyear['SCIENCE'] =  $all_exam['SCIENCE'] + $final_exam['SCIENCE'] ; ?>  
                   </td>
                 </tr>
                  <tr>                     
                   <td>
                    <?php  echo $all_exam['SOCIAL SCIENCE'] ; ?>
                  </td>

                   <td> 
                    <?php  echo $final_exam['SOCIAL SCIENCE'] ; 
 $totyear['SOCIAL SCIENCE'] =  $all_exam['SOCIAL SCIENCE'] + $final_exam['SOCIAL SCIENCE'] ;
                    ?>  
                   </td>
                 </tr>

            </table>
        </td>

         <td style="width:300px;">

             <table width="100%" border="1" >

                                         <?php 
                         $obt = array_map(function () {
                             return array_sum(func_get_args());
                        }, $final_exam, $all_exam);
  
               //  foreach ($subjects as $key => $value) {
                   ?>
                   <tr><td>100</td><td><?php echo  $totyear['ENGLISH'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['ENGLISH']); ?></td>
                  </tr>
                   <tr><td>100</td><td><?php echo  $totyear['HINDI'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['HINDI']) ; ?></td>
                  </tr>
                   <tr><td>100</td><td><?php echo  $totyear['INFO. TECH.'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['INFO. TECH.']) ; ?></td>
                  </tr>
                   <tr><td>100</td><td><?php echo  $totyear['MATHS'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['MATHS']) ; ?></td>
                  </tr>
                   <tr><td>100</td><td><?php echo  $totyear['SCIENCE'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['SCIENCE']) ; ?></td>
                  </tr>
                   <tr><td>100</td><td><?php echo  $totyear['SOCIAL SCIENCE'];?> </td>
                    <td>100</td><td><?php   echo ($totyear['SOCIAL SCIENCE']) ; ?></td>
                  </tr>
                 <?php // } ?>
            </table>
        </td>
   </tr>   
   </table>
  <table width="100%">
    <tr>
          <td style="width:200px;">
              <table border="1">
                      <tr><td>TOTAL &nbsp; &nbsp; &nbsp;</td></tr>
                      <tr><td>PERCENTAGE &nbsp; &nbsp; &nbsp;</td></tr>
                      <tr><td>ATTENDANCE &nbsp; &nbsp; &nbsp;</td></tr>
              </table>
          </td>
          <td style="width:200px;">
            <table border="1">
                    <tr><td><?php  echo  array_sum($all_exam); ?></td><td><?php  echo  array_sum($final_exam); ?></td></tr>
                    <tr><td><?php  echo  round((array_sum($all_exam)*100 )/120 ,1 ); ?> % </td><td><?php  echo  round((array_sum($final_exam)*100)/480 ,1); ?>% </td></tr>
                      <tr><td colspan="2" >&nbsp;</td></tr>
              </table>
          </td>
          <td style="width:300px;">
            <table border="1">
                    <tr><td>600</td><td><?php echo  array_sum($obt) ;  ?></td>
                      <td>600</td><td> <?php echo  array_sum($obt) ;  ?></td>
                    </tr>
                    <tr>
                         <td colspan="4"> 
                                       <?php  echo  round(((array_sum($obt))*100)/600 ,1);   ?>% <br>&nbsp;<br>&nbsp;
                         </td>

                    </tr>
                      
              </table>
          </td>
    </tr>
  </table>
       
     <div>     
       
         <p>  Class Teacher's  Remarks  . . . . . . . . . . . . . . . . . . . . . . . .</p>

         <h5> RESULT  - <?php //print_r($totyear);
         $res = 0 ;
         foreach ($totyear as $key=>$value) {
         if ($value < 33) {
         $res++ ;
          $failsub = $key;
         }

         }  

if ($res > 1 ) {
 echo "DETAINED";
}
 elseif($res == 1){ echo " TO BE RETESTED IN  &nbsp; - &nbsp; $failsub";  }
else{
    echo "PASS <br>";
   echo "PROMOTED TO CLASS &nbsp; - &nbsp;  $promotedTo";
}   ?> 
</h5>
        
     </div>

     <p style="margin-left: 30%;"><b>Grading scale for scholastic areas </b> </p>
<table width="100%" >
       <tr>
           <td style="float: left;">&nbsp; </td>
            <td style="text-align: center; "> 
             <table width="300px ;" border="1" >
                 <tr><td  style="padding:0px !important;"><b>MARKS RANGE</b></td><td style="padding:0px !important;"><b>GRADE</b></td></tr>
                <tr><td  style="padding:0px !important;">91-100</td><td style="padding:0px !important;">A1</td></tr>
                <tr><td style="padding:0px !important;">81-90</td><td style="padding:0px !important;">A2</td></tr>
                <tr><td style="padding:0px !important;">71-80</td><td style="padding:0px !important;">B1</td></tr>
                <tr><td style="padding:0px !important;">61-70</td><td style="padding:0px !important;">B2</td></tr>
                <tr><td style="padding:0px !important;">51-60</td><td style="padding:0px !important;">C1</td></tr>
                <tr><td style="padding:0px !important;">41-50</td><td style="padding:0px !important;">C2</td></tr>
                <tr><td style="padding:0px !important;">33-40</td><td style="padding:0px !important;">D</td></tr>
                <tr><td style="padding:0px !important;">32 & Below</td><td style="padding:0px !important;"> E(Needs improvemnet)</td></tr>
            </table> </td>
            <td style="float: right;"> 
                &nbsp;
             </td>
          
       </tr>
    </table>  
    &nbsp;<br>
    <table width="100%">
       <tr>
           <td style="float: left;">Place : JABALPUR  <br>
            Date : <?php echo date('d-m-Y');?></td>
            <td style="text-align: center; "> &nbsp;<br>&nbsp;<br>  Class Teacher's Sign</td>
            <td style="text-align:right"> 
            <img src="<?php echo base_url('uploads/principal_sign.jpg') ?>" width="80px;"> <br>Principal's Sign
             </td>
          
       </tr>
    </table>



</body>
</html>

