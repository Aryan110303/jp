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
       padding: 3px;
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
                           <td style="text-align: center"> Session : <?php echo $sessionname ?></td>
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
                        
                        $rollnumber1  =($rollno*1000) ;
                           
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
                        <tr><td  style="font-weight: bold">  Date of Birth : <?php $date = str_replace('/', '-', $student["dob"]);
                                                                            echo date('d-m-Y', strtotime($date));       ?></td></tr>
                       
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

   


   <table width="100%" border="1">
    <tr>
        <td style="width:200px;">
            <table width="100%" >
                <tr><td colspan="2"  style="width:200px;"> SUBJECT </td></tr> 
                <tr><td colspan="2">&nbsp;</td></tr> 
                
            </table>
         </td>
         <td style="width:300px; border: 1px solid ;" >
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
          <tr>
            <td valign="middle" width="25%" rowspan="3" style="text-transform: uppercase; font-weight: bold; background:#f7921e;color: #fff">Subject</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e; color: #fff">TERM I</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e; color: #fff">TERM II  </td>

            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e; color: #fff">GRAND TOTAL</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e; color: #fff">YEARS AVERAGE</td>

          </tr>

          <tr style="background: #f7921e;color: #fff;font-weight: bold;">
            <td valign="middle">PERODIC I (20)</td>
            <td valign="middle">PERODIC II (80)</td>
            <td valign="middle">PERODIC III (20)</td>
            <td valign="middle">FINAL (80)</td>
            <td valign="middle" rowspan="2">Max Marks</td>
            <td valign="middle" rowspan="2">Marks Obt</td>
            <td valign="middle" rowspan="2">Max Marks</td>
            <td valign="middle" rowspan="2">Marks Obt</td>
          </tr>

          <tr style="background: #f7921e;color: #fff;font-weight: bold;">
            <td valign="middle">Marks Obt</td>
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Marks Obt.</td>
          </tr>

           <tr>
            <td valign="middle" style="text-align: left">English</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
          </tr>
          <tr>
            <td valign="middle" style="text-align: left">Hindi</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
          </tr>
           <tr>
            <td valign="middle" style="text-align: left">Accountancy</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
          </tr>
           <tr>
            <td valign="middle" style="text-align: left">Business Studies</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
          </tr>

          <tr>
            <td valign="middle" style="text-align: left">Economics</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
            <td valign="middle">100</td>
            <td valign="middle">50</td>
          </tr>
        </table>
        </td>
         <td style="width:200px; border: 1px solid ;">
             <table width="100%" border="1"  >
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
<?php
//print_r($subject); die;
 foreach ($subject as $key => $value) {?>
<tr style="width:200px;"><td colspan="2" style="width:200px;"><?=  $value ?></td></tr> 
<!-- <tr style="width:200px;"><td colspan="2" style="width:200px;">HINDI</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">MATHS</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SANSKRIT</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">COMPUTER</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SCIENCE</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SOCIAL SCIENCE</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">ENGLISH LANGUAGE</td></tr> -->
                 
                   <?php } ?>
                
            </table>
         </td>
         <td style="width:300px;">
            <table width="100%">
<tbody>
<tr>
<td>
<table border="1">
<tbody>
<?php  
$i=0;



foreach ($periodicI as $value1) { ?>
               
<tr><td><?php echo $value1; ?></td>
</tr>
   <?php
      $i++;
$tot1[] = $value1 ;
     } 

   ?>

</tbody>
</table>
</td>
<td>
<table border="1">
<tbody>
 <?php
 $j=0;
 
 foreach ($periodicII as $value2) { ?>
                 
<tr><td><?php echo $value2; ?></td>
</tr>
   <?php $j++;
$tot2[] = $value2 ;


    } 
    //echo "<pre>";print_r($total1);
   ?>
</tbody>
</table>
</td>
<td>
<table border="1">
<tbody>
 <?php
 $k=0;
 
 foreach ($periodicIII as $value3) { ?>
                 
<tr ><td><?php echo $value3; ?></td>
</tr>
   <?php $k++;
$tot3[] = $value3 ; } 
    //echo "<pre>";print_r($total1);
   ?>
</tbody>
</table>
</td><td>
<table border="1">
<tbody>
 <?php
 $l=0;
 
 foreach ($final_term as $value) { ?>
                 
<tr>
  <td ><?php echo $value; ?></td>
</tr>
   <?php $l++;
$tot[] = $value ; } 
    //echo "<pre>";print_r($total1);
   ?>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
        </td>
         <td style="width:200px;">
             <table width="100%" border="1" >
                  <?php   
                  $tot_avg=array();
                  $obt_full = array_map(function () {
                                                 return array_sum(func_get_args());
                                            }, $tot, $tot1,$tot2,$tot3);
                                        //echo "<pre>";print_r($total);
                  foreach ($obt_full as $key => $value) {
                                     ?>
                   <tr><td>200</td><td><?php echo  $value ;?> </td>
                    <td>100</td><td><?php   echo round((($value)*100)/200 ,1) ; ?></td></tr>
                 <?php  $tot_avg[]=$value; } ?>
            </table>
        </td>

    </tr>   

    
   </table>
    
    
  <table width="100%">
    <tr>
          <td style="width:200px;">
              <table border="1">
                      <tr><td>TOTAL</td></tr>
                      <tr><td>PERCENTAGE</td></tr>
                      <tr><td>ATTENDANCE</td></tr>
              </table>
          </td>
          <td style="width:300px;">
            <table border="1">
                    <tr><td><?php  echo  array_sum($tot1); ?></td><td><?php  echo  array_sum($tot2); ?></td><td><?php  echo  array_sum($tot3); ?></td><td><?php  echo  array_sum($tot); ?></td></tr>
                    <tr>
                      <td><?php  echo  round((array_sum($tot1)*100 )/160 ,1 ); ?>%</td>
                      <td><?php  echo  round((array_sum($tot2)*100)/640 ,1); ?>%</td>
                      <td><?php  echo  round((array_sum($tot3)*100)/160 ,1); ?>%</td>
                      <td><?php  echo  round((array_sum($tot)*100)/640 ,1); ?>%</td>
                    </tr>

                      <tr><td colspan="4" >&nbsp;</td></tr>
              </table>
          </td>
          <td style="width:200px; ">
            <table border="1">
                     <tr><td>1600</td>
                      <td><?php echo  array_sum($tot_avg) ;  ?></td>
                      <td>800</td>
                      <td> <?php echo  array_sum($tot_avg)/2 ;?></td>
                    </tr>
                     <tr rowspan="2">
                         <td colspan="4"> 
                                       <?php  echo  round(((array_sum($tot_avg)/2)*100)/800 ,1);   ?>
                        %  <br>&nbsp;<br></td>

                    </tr>

                      
              </table>
          </td>
    </tr>
  </table>
       
     <div>
     
          &nbsp;
         <p>  Class Teacher's  Remarks  . . . . . . . . . . . . . . . . . . . . . . . .</p>
         <h5> RESULT&nbsp;  - &nbsp;  <?php  
          $i = 0 ;
            $count=0;
                                foreach ($tot_avg as $value) {
                                 $avg=$value/2;
                                    if($avg<33){
                                     $count++;   
                                    $failsub = $i;
                                    }
                                 $i++ ; }
         if($count > 1){ echo "DETAINED";  } 
         elseif($count == 1){ echo " TO BE RETESTED IN  &nbsp;- &nbsp; $subject[$failsub] ";  }
         else{ 
          echo "PASS <br>";  
          echo "PROMOTED TO CLASS &nbsp; - &nbsp;  $promotedTo";
            }
          ?></h5>
     </div>

   <div style="margin-left: 35%"> Grading scale for scholastic areas</div> 
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
    &nbsp;   <br>&nbsp;

    <table width="100%">
       <tr>
       <td style="float: left;">Place : JABALPUR  <br>
            Date : <?php echo date('d-m-Y');?></td>
            <td style="text-align: center; "> <br>&nbsp; <br>Class Teacher's Sign</td>
            <td style="text-align: right"> 
                <img src="<?php echo base_url('uploads/principal_sign.jpg') ?>" width="80px;"> <br>Principal's Sign
             </td>
          
       </tr>
    </table>



</body>
</html>

