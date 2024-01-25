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
            <td style="width: 14% !important;" ><img src="<?php echo base_url('uploads/logo2.jpg')?>" alt="Central Academy" style="height: 120px ; width:100px;"></td>
            <td style="text-align: center;text-transform: uppercase" >
                <div style="color: #ef3035;font-weight: bold;font-size: 22px;">Central Academy Senior Secondary School  </div>
                <div style="font-size: 12px;font-weight: bold;">
                     Kanchan Vihar, Vijay Nagar, Jabalpur(M.P.), Phone : 0761-4928811 <br>
                   MANAGEMENT@CENTRALACADEMYJABALPUR.COM,  www.centralacademyjabalpur.com <br>
                      (Affiliated to CBSE, New Delhi)
                </div>
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
                            'XII' => 12000,
                            'XI' => 11000,
                            'X' => 1000,
                            'IX' => 9000,
                            'VIII'=>8000,
                            'VII'=>7000,
                            'VI'=>6000,
                            'V' => 5000,
                            'IV' => 4000,
                            'III' =>3000,
                            'II' =>2000,
                            'I' => 1000,
                        );
                       
                        $roman = $student['class'] ;  
                        foreach($romans as $key => $value) {
                        
                        if($roman == $key){
                            $rollno = $value + $rollno;
                        
                        }
                        
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
                        <tr><td  style="font-weight: bold">  Date of Birth : <?php echo date('d-m-Y', strtotime($student["dob"])) ;      ?></td></tr>
                       
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

   


   <table width="100%" border="1"  >
    <tr>
        <td style="width:200px;">
            <table width="100%">
                <tr ><td colspan="2"  style="width:200px;">SUBJECT</td></tr> 
                <tr><td colspan="2">&nbsp;</td></tr> 
                
            </table>
         </td>
         <td style="width:300px; border: 1px solid ;">
             <table width="100%"  >
                <tr><td >PREODIC &nbsp;
                  &nbsp; </td><td >FINAL &nbsp; </td></tr> 
                <tr><td>Max Obt. </td><td>Max Obt. </td></tr>
            </table>
        </td>
         <td style="width:200px; border: 1px solid ;">
             <table width="100%"  >
                <tr><td colspan="2">GRAND TOTAL</td><td colspan="2">YEARS AVERAGE</td></tr> 
                <tr><td>Max Marks</td><td>Max Obt. </td><td>Max Marks</td><td>Max Obt. </td></tr>
            </table>
        </td>

    </tr>   

    
   </table>
   
    <table width="100%"  >
    <tr>
        <td style="width:200px;">
            <table width="100%" border="1"  >
                <?php // foreach ($subjects as $key => $value) {
                   ?>
<tr style="width:200px;"><td colspan="2" style="width:200px;">ENGLISH LITERATURE</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">HINDI</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">MATHS</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SANSKRIT</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">COMPUTER</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SCIENCE</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">SOCIAL SCIENCE</td></tr> 
<tr style="width:200px;"><td colspan="2" style="width:200px;">ENGLISH LANGUAGE</td></tr>
                 
                   <?php //} ?>
                
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
foreach ($periodic as $value) { ?>
               
<tr style="width:150px;"><td colspan="2" style="width:150px;"><?php echo $total[$i]=$value[1]; ?></td>
</tr>
   <?php $i++; } 

   ?>

</tbody>
</table>
</td>
<td>
<table border="1">
<tbody>
 <?php
 $j=0;
 
 foreach ($final_term as $value1) { ?>
                 
<tr style="width:150px;"><td colspan="2" style="width:150px;"><?php echo $total1[$j]=$value1[1]; ?></td>
</tr>
   <?php $j++; } 
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
                                         $obt = $total;
                        array_walk($obt, function (&$val, $key, $foo) {
                           $val += $foo[$key];
                        }, $total1);
                                        //echo "<pre>";print_r($total);
                   ?>
                   <tr><td>200</td><td><?php echo  $obt[0];?> </td><td>100</td><td><?php   echo ($obt[0])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[1];?> </td><td>100</td><td><?php   echo ($obt[1])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[2];?> </td><td>100</td><td><?php   echo ($obt[2])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[3];?> </td><td>100</td><td><?php   echo ($obt[3])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[4];?> </td><td>100</td><td><?php   echo ($obt[4])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[5];?> </td><td>100</td><td><?php   echo ($obt[5])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[6];?> </td><td>100</td><td><?php   echo ($obt[6])/2 ; ?></td></tr>
                   <tr><td>200</td><td><?php echo  $obt[7];?> </td><td>100</td><td><?php   echo ($obt[7])/2 ; ?></td></tr>
                 <?php // } ?>
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
                    <tr><td><?php  echo  array_sum($total); ?></td><td><?php  echo  array_sum($total1); ?></td></tr>
                    <tr><td><?php  echo  round((array_sum($total)*100 )/800 ,2 ); ?></td><td><?php  echo  round((array_sum($total1)*100)/800 ,2); ?></td></tr>
                      <tr><td colspan="2" >&nbsp;</td></tr>
              </table>
          </td>
          <td style="width:200px;">
            <table border="1">
                    <tr><td>1600</td><td><?php echo  array_sum($obt) ;  ?></td><td>800</td><td> <?php echo  array_sum($obt)/2 ;  ?></td></tr>
                    <tr rowspan="2" >
                         <td colspan="4"> 
                                       <?php  echo  round(((array_sum($obt)/2)*100)/800 ,2);   ?>
                         </td>
                    </tr>
                      
              </table>
          </td>
    </tr>
  </table>
       
     <div>
     
          &nbsp;
         <p>  Class Teacher's  Remarks  . . . . . . . . . . . . . . . . . . . . . . . .</p>
         <h5> RESULT   <?php 
         $count=0;
                                foreach ($obt as $value) {
                                    $avg=$value/2;
                                    if($avg<33){
                                     $count++;   
                                    }
                                }
         if($count > 1){ echo "FAIL";  }else{ echo "PASS"; }
          ?></h5>
     </div>

   <div style="margin-left: 45%">  Instructions </div> 
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
    &nbsp;   <br>&nbsp;<br>&nbsp;

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

