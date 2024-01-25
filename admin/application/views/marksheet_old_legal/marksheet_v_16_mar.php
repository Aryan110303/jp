<!DOCTYPE html>
<html lang="en">
<head>
    <title> MARKSHEET </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <?php //print_r($student); die; ?>
</head>
<style type="text/css">
 table td table td {
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
    .boldfont{
           font-weight:bolder;
    }
</style>

<body style="font-family: sans-serif;margin: 0px;padding: 0px;color: #000;font-size: 13px" >
<div >
<div style=" background: url(<?php echo base_url('uploads/background-img.png') ?> ) no-repeat center center;height: 100vh; width: 100%;">
    <table cellpadding="0" cellspacing="0" width="100%" style="padding: 0;">
        <tbody>
        <tr>
            <td style="width: 14% !important;" ><img src="<?php echo base_url('uploads/logo2.jpg')?>" alt="Central Academy" style="height: 120px ; width:100px;"></td>
            <td style="text-align: center;text-transform: uppercase" >
                <div style="color: #ef3035;font-weight: bolder;font-size: 22px;" >Central Academy Senior Secondary School  </div>
                <div style="font-size: 12px;font-weight: bolder;">
                     Kanchan Vihar, Vijay Nagar, Jabalpur(M.P.), Phone : 0761-4928811 <br>
                   MANAGEMENT@CENTRALACADEMYJABALPUR.COM,<br>  www.centralacademyjabalpur.com <br>
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
                            $rollno = $student['rollno'] ;
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
        <td style="width:200px;" rowspan="3">
            <table width="100%" class="boldfont">
                <tr ><td colspan="2"  style="width:200px;">SUBJECT</td></tr> 

                
            </table>
         </td>
         <td style="width:300px; border: 1px solid;">
             <table width="100%" class="boldfont" >
                <tr>
                  <td colspan="2" >QUATERLY   &nbsp;</td>
                  <td colspan="2">HALF YEARLY </td>
                  <td colspan="2" >FINAL &nbsp; </td>

                </tr> 
                <tr>
                  <td>Max Marks </td><td> Max Obt. </td>
                  <td>Max Marks</td><td> Max Obt.</td>
                  <td>Max Marks</td><td> Max Obt.</td>
                </tr>
            </table>
        </td>
         <td style="width:200px; border: 1px solid;" >
             <table width="100%"  class="boldfont"  >
                <tr><td colspan="2">GRAND TOTAL</td><td colspan="2">YRS. AVG.</td></tr> 
                <tr><td>Max Marks</td><td>Max Obt. </td><td>Max Marks</td><td>Max Obt. </td></tr>
            </table>
        </td>

    </tr>   

    
   </table>
   
    <table width="100%"  >
    <tr>
        <td style="width:200px;">
            <table width="100%" border="1" class="boldfont" >
                <?php 
                   foreach ($final as $key2 => $value) {
                      ?>   
                     <tr style="width:200px;"><td colspan="2" style="width:200px;"><?php echo $key2 ; ?></td></tr> 
                    
                   <?php }?>
                
            </table>
         </td>



         <td style="width:300px;">  
                    <table width="100%">
      
<tr>
  <td >
      <table width="100%" border="1" >
                <?php foreach ($quaterly as $key => $value) {
                 ?>  
                  <tr>
                    <td> <?= ($value['full'])?$value['full']:'-' ?></td>
                    <td> <?= ($value['get'])?$value['get']:'-' ?></td>
                  </tr>
               <?php  

               $tot_qtr_full[]= $value['full'] ;
               $tot_qtr_get[]= $value['get'] ;


             } ?>
                 
             </table>
  </td>
<td >
   <table width="100%" border="1">
                <?php foreach ($halfyearly as $key => $value1) {
              ?>  
                  <tr>
                    <td> <?= $value1['full'] ?></td>
                    <td> <?= $value1['get'] ?></td>
                  </tr>
               <?php  
  $tot_half_full[]= $value1['full'] ;

   $tot_half_get[]= $value1['get'] ;
             } ?>
                 
             </table>
</td>
<td >
   <table width="100%" border="1">
                <?php foreach ($final as $key => $value2) {

                     ?>  
                  <tr>
                    <td> <?=$value2['full'] ?></td>
                    <td> <?=$value2['get'] ?></td>
                  </tr>
               <?php   
              
 $tot_final_full[]= $value2['full'] ;

   $tot_final_get[]= $value2['get'] ;
             }?>
                 
             </table>
</td>
</tr>

  </table>
                           
        </td>


         <td style="width:200px;">
            <table width="100%">
               <tr> 

                    <td>
                       <table width="100%">
                                    <?php 

                                                             
                                             $obt_full = array_map(function () {
                                                 return array_sum(func_get_args());
                                            }, $tot_qtr_full, $tot_half_full,$tot_final_full);
                                            // print_r($obt_full); 
                                               $obt_get = array_map(function () {
                                                 return array_sum(func_get_args());
                                            }, $tot_qtr_get, $tot_half_get,$tot_final_get);
                                            //  print_r($obt_get); 
                                    ?>

                                      <tr>
                                        <td> 
                                           <table border="1">
                                            
                                               <?php foreach ($obt_full as $key => $val1) {
                                                                          
                                         ?>
                                          <tr>

                                        <td> <?= $val1 ?></td> </tr>
                                        <?php  } ?>
                                           
                                           </table>
                                        </td>

                                        <td>
                                           <table border="1">
                                            
                                               <?php foreach ($obt_get as $key => $val2) {
                                                                                                
                                         ?><tr>
                                        <td> <?= $val2 ?></td> </tr>
                                        <?php  }?>
                                            
                                           </table>
                                        </td>

                                      </tr>
                                  
                                     
                                 </table>
                    </td>
                    <td>
                        <table width="100%" >
                                    <?php 

                                                             
                                             $obt_full = array_map(function () {
                                                 return array_sum(func_get_args());
                                            }, $tot_qtr_full, $tot_half_full,$tot_final_full);
                                            // print_r($obt_full); 
                                               $obt_get = array_map(function () {
                                                 return array_sum(func_get_args());
                                            }, $tot_qtr_get, $tot_half_get,$tot_final_get);
                                            //  print_r($obt_get); 
                                    ?>

                                      <tr>
                                        <td> 
                                           <table border="1">
                                            
                                               <?php foreach ($obt_full as $key => $val1) {
                                                                                             
                                         ?>
                                          <tr>

                                        <td> <?= round($val1/3,1) ?></td> </tr>
                                        <?php  } ?>
                                           
                                           </table>
                                        </td>

                                        <td>
                                           <table border="1">
                                            
                                               <?php foreach ($obt_get as $key => $val2) {
                                                                                               
                                         ?><tr>
                                        <td> <?= round($val2/3, 1) ?></td> </tr>
                                        <?php  }?>
                                            
                                           </table>
                                        </td>

                                      </tr>
                                  
                                     
                                 </table>
                    </td>
                     
               </tr>


            </table>
             
        </td>

    </tr>   

    
   </table>
  <table width="100%">
    <tr>
          <td style="width:200px;">
              <table border="1" class="boldfont">
                      <tr><td>TOTAL</td></tr>
                      <tr><td>PERCENTAGE</td></tr>
                      <tr><td>ATTENDANCE</td></tr>
              </table>
          </td>
          <td style="width:300px;">
            <table border="1" class="boldfont">
                    <tr>
                      <td><?php  echo  array_sum($tot_qtr_full); ?></td><td><?php  echo  array_sum($tot_qtr_get); ?></td>
                      <td><?php  echo  array_sum($tot_half_full); ?></td><td><?php  echo  array_sum($tot_half_get); ?></td>
                      <td><?php  echo  array_sum($tot_final_full); ?></td><td><?php  echo  array_sum($tot_final_get); ?></td>
                    </tr>
                    <tr>

                    
                      <td colspan="2"><?php echo  round((array_sum($tot_qtr_get)*100 )/array_sum($tot_qtr_full) ,2 ); ?>  %</td>
                      <td colspan="2"><?php echo  round((array_sum($tot_half_get)*100 )/array_sum($tot_half_full) ,2 ); ?>  %</td>
                      <td colspan="2"><?php echo  round((array_sum($tot_final_get)*100 )/array_sum($tot_final_full) ,2 ); ?>  %</td>

                    </tr>
                      <tr>

                        <td colspan="6" >&nbsp;</td>

                      </tr>
              </table>
          </td>
          <td style="width:200px;">
            <table border="1" class="boldfont">
                    <tr><td><?php  echo  array_sum($obt_full ) ;  ?></td> <td><?php  echo  array_sum($obt_get ) ;  ?></td><td><?php  echo  round(array_sum($obt_full )/3,2) ;  ?></td><td> <?php echo  round(array_sum($obt_get)/3 ,2);  ?></td></tr>
                    <tr  >
                         <td colspan="4"  style="height: 50px;"> 
                                       <?php  echo  round(((array_sum($obt_get))*100)/array_sum($obt_full),2);   ?> %
                         </td>
                    </tr>
                      
              </table>
          </td>
    </tr>
  </table>
       
     <div>

         <p>  Class Teacher's  Remarks  . . . . . . . . . . . . . . . . . . . . . . . .</p>
         <h5> RESULT  -  </h5>
     </div>

   
<table width="100%" >
       <tr>
           <td style="float: left;">&nbsp; </td>
            <td style="text-align: center; "> 
             <table width="300px ;" border="1" >
                <tr><td  style="padding:0px !important;">CO-SCHOLASTIC AREAS <br>
                (On a point grading scale : A-E)</td><td style="padding:0px !important;"><b>GRADE</b></td>  <td> OverALL Grade</td> </tr>
                <tr><td  style="padding:0px !important;">Drawing</td><td style="padding:0px !important;"><?php echo  $grade->drawing ?></td> <td rowspan="6"></td></tr>
                <tr><td style="padding:0px !important;">Neatness</td><td style="padding:0px !important;"><?php echo  $grade->neatness ?></td></tr>
                <tr><td style="padding:0px !important;">Punctuality</td><td style="padding:0px !important;"><?php  echo $grade->punctuality ?></td></tr>
                <tr><td style="padding:0px !important;">Courteosness</td><td style="padding:0px !important;"><?php  echo $grade->courteousness ?></td></tr>
                <tr><td style="padding:0px !important;">Obedience</td><td style="padding:0px !important;"><?php  echo $grade->obedience ?></td></tr>
                <tr><td style="padding:0px !important;">Dicsipline</td><td style="padding:0px !important;"><?php  echo $grade->discipline ?></td></tr>
                
            </table> </td>
               <td style="float: right;"> 
                &nbsp;
             </td>
          
       </tr>
    </table>    
&nbsp;<br>&nbsp;<br>
&nbsp;<br>&nbsp;

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

