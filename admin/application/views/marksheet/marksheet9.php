<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta  name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />
    <title>Central Academy Jabalpur</title> 
  <style type="text/css">
  *{margin:0; padding: 0;}
    html { width: 100%;}
    body{font-family: Arial;margin:0; padding: 0;background:#f7921e;}
  .table{border-collapse: collapse;}
  .table td{border: 1px solid #333; padding: 8px 10px;}
  .table2{border-collapse: collapse;}
  .table2 td{border: 1px solid #333; padding: 8px 0px;}
    .table3{border-collapse: collapse; font-size: 12px;}
  .table3 td{border: 1px solid #333; padding: 4px 0px;font-size: 12px;}
</style>
</head>
<body>
 <div style="width:1000px; margin:0px auto; position: relative; z-index: 1;font-size: 15px; 
      color: #000; line-height: 20px; font-family: Arial; border: 16px solid #f7921e;padding: 3px;background: #feefd8;">

<table cellpadding="0" cellspacing="0" width="100%" align="center" style="background-image:<?php echo base_url().'/uploads/logo.png' ;?>">
  <tr><td valign="top" height="5"></td></tr>
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0">
         <tr>
          <td valign="top" align="left"><img src="<?php echo base_url().'/uploads/logo.png' ;?>" width="90" height="110" /></td>
           <td valign="top" style="font-weight: bolder;font-size: 32px;text-align: center; padding-top: 30px; padding-bottom: 5px; line-height: normal;">Central Academy Senior Secondary School <span style="font-weight: lighter;  font-size: 18px; " > (10+2)</span></td>
          <td valign="top" align="right" >
             <table>
              <tr><td valign="top" height="20"></td></tr>
              <tr> <td style="border:2px solid #000; height:80px; width:58px; "> &nbsp;</td> </tr>
             </table>
         </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  <td valign="top" style="text-align: center; font-size: 14px;padding-bottom: 2px;">Mathura Vihar, Vijay Nagar, Jabalpur - 482002 (M.P.)  <span style="padding-left: 10px;">Phone:</span> 0761-4928811</td>
  </tr>
  <tr><td valign="top" height="10"></td></tr>
  <tr><td valign="top" bgcolor="1" height="1"></td></tr>
  <tr><td valign="top" height="10"></td></tr> 
  <tr>
      <td valign="top" align="right">
        <table width="100%" align="center" cellpadding="0" cellspacing="0">
           <tr>
              <td valign="top" width="" style=" text-align: left; font-size: 16px;"><b>Affiliation No.:</b> 1030293</td>
              <td valign="top" style="text-align: center; font-size: 16px;" ><b>Session :</b> <?php echo
              $sessionname;?></td>
              <td valign="top" style="text-align: right; font-size: 16px;" ><b>School Code :</b> 50262</td>
            </tr>
        </table>
      </td>
    </tr>
    <tr><td valign="top" height="10"></td></tr>
    <tr><td valign="top" bgcolor="1" height="1"></td></tr>
    <tr><td valign="top" height="10"></td></tr>   
    <tr>
      <td valign="top">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
 
             <td valign="top" align="left" >
              <img src="<?php echo 'http://softwares.centralacademyjabalpur.com/'.$student['image'] ; ?>" style="height: 120px;width:90px ;border: 2px solid #000; border-radius: 10px;" />
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">

                 <tr>
                  <td valign="top" style="font-weight: bold; padding-top: 25px ; padding-bottom: 5px">Scholar No.: <?php echo $student['scholar_no'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-top: 5px ;padding-bottom: 5px">Student's Name: <span style="color: #f7921e"><?php echo $student['name'] ; ?> </span>
                  </td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold;  padding-bottom: 5px">Mother's Name: <?php echo $student['mname'] ; ?>                    
                  </td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Father's Name: <?php echo $student['fname'] ; ?>                    
                  </td>
                </tr>
              
              </table>
            </td>

          
<td valign="top">
  <table width="100%" cellpadding="0" cellspacing="0" class="">
      <tr>  
      <td valign="top" style="font-weight: bold; padding-bottom: 5px; padding-top: 25px ;">    Roll No: <?php echo 9000 + $student['rollno'] ; ?>               
      </td>
    </tr>
    <tr>
      <td valign="top" style="font-weight: bold; padding-bottom: 5px">Class/ Section: <?php echo $student['class'].'/'.$student['section'] ; ?>
        
      </td>
    </tr>
    <tr>  
      <td valign="top" style="font-weight: bold; padding-bottom: 5px">Date of Birth : <?php echo $student['dob'] ; ?> </td>
    </tr>    
  </table>
</td>
 <td>
    <img src="<?php echo base_url().$student['qrimage'] ; ?>" style="height: 80px;width:80px ;border: 2px solid #000; " />
 </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td height="25"></td></tr> 
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
           <tr>
            <td valign="middle" width="25%" rowspan="2" style="text-transform: uppercase; font-weight: bold ;background:#f7921e;color: #fff">Subjects</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">Periodic</td>
  
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">FINAL</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">GRAND TOTAL</td>
              <td valign="middle"  style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">OVERALL</td>
          </tr>
          <tr>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt.</td>           
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt.</td>  
            <td valign="middle">GRADE</td>              
          </tr>
        <?php  
        
        $count = 0;
             foreach ($subjects as $key => $sub) {   
             $grad = 0;     
        ?>
          <tr>
            <td valign="middle" style="text-align: left"><?php echo $sub ;?></td>
            <td valign="middle"><?php echo $allexamfull[] = 20 ; //$all_exam[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $allexamget[] =  $all_exam[$sub]; // $all_exam[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $finalfull[] = 80 ; //$final_exam[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $finalget[] =  $final_exam[$sub] ;?></td>
            <td valign="middle"><?php echo $fulltotal[] = 100 ;//$all_exam[$sub]['full'] + $final_exam[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $gettotal[] = $grad = $all_exam[$sub] + $final_exam[$sub] ;
             //$all_exam[$sub]['get'] + $final_exam[$sub]['get'] ;?></td>           
            <td valign="middle"><?php $grad = round($grad); 
            if($grad > 90) {
                                             echo "A+";             
                                              } elseif($grad > 80 && $grad < 91 ) {
                                             echo "A";             
                                              }elseif($grad > 70 && $grad < 81 ) {
                                             echo "B+";             
                                              }elseif($grad > 60 && $grad < 71 ) {
                                             echo "B";             
                                              }elseif($grad > 50 && $grad < 61 ) {
                                             echo "C+";             
                                              }elseif($grad > 40 && $grad < 51 ) {
                                             echo "C";             
                                              }elseif($grad > 32 && $grad < 41 ) {
                                             echo "D";             
                                              }else {
                                                echo "E";     
                                                $count++;        
                                              }  ?>
            </td>           
         </tr>
         <?php
           }
         ?>
         <tr>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;">Total</td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $allexamfullnum = array_sum($allexamfull); ?></td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $allexamgetnum = array_sum($allexamget); ?></td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $finalfullnum = array_sum($finalfull); ?></td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $finalgetnum = array_sum($finalget); ?></td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $fulltotalnum =array_sum($fulltotal); ?></td>
           <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;"><?php echo $gettotalnum= array_sum($gettotal); ?></td>     
            <td style="text-align: center; font-weight: bold;background: #f7921e;color: #fff;">&nbsp;</td>         
         </tr>
         <tr style="background: #f7921e;color: #fff;"> 
            <td valign="top"  style="text-align: center; font-weight: bold;">Percentage  </td>
            <td valign="top" colspan="2" style="text-align: center; font-weight: bold; color: #fff;">  <?php echo  round((($allexamgetnum * 100) / $allexamfullnum),2) ;?>  %</td>

              <td valign="top" colspan="2" style="text-align: center; font-weight: bold; color: #fff;">  <?php echo  round((($finalgetnum * 100) / $finalfullnum),2) ;?> %</td>
                <td valign="top" colspan="3" style="text-align: center; font-weight: bold; color: #fff;">  <?php echo $pass = round((($gettotalnum * 100) / $fulltotalnum),2) ;?>  %</td>

                  
         </tr>
          <tr style="background: #f7921e;color: #fff;"> 
            <td valign="top" style="text-align: center; font-weight: bold;">Attendance </td>
            <td valign="top" colspan="7"  width="" style="text-align: left; padding-right: 20px; color: #fff;font-weight: bold;"> <?php echo $student['present_days'] ; ?>  /  <?php echo $student['total_days'] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </td>
        </tr>
         </table>
    </td>
  </tr>

 <tr><td height="5"></td></tr> 

   <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #333; padding: 10px">
        <tr>
          <td valign="top" style="font-weight: bold; font-size:16px; text-transform: uppercase;">Class Teacher's Remarks - 
             <span> ............... </span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;">RESULT - 
             <span> <?php
              if ($count > 0) {
                 $pass = $count;
               } 
              echo ($pass > 32)?"PASS":"FAIL";?> </span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;text-align: right;">PROMOTED TO CLASS -
           <span> <?php echo ($pass > 32)?" X ":" ";?> </span>
         </td>
        </tr>
     </table>
    </td>
  </tr>
  <tr><td height="5"></td></tr>   
 <tr>
   <td valign="top">
       <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;">
       <tr> 
        <td style="font-weight: bold;background:#f7921e; color: #fff">Co-Scholastic Area</td>
        <td style="font-weight: bold;background:#f7921e; color: #fff">GRADE</td>            
       </tr>
          <tr>  <td>Work Education(Pro-vocational Education)</td><td><?php echo $grade->neatness ;?></td>   </tr>
          <tr>  <td>Art Education</td><td><?php echo $grade->drawing; ?></td>     </tr>
          <tr>  <td>Health & Physical Education</td><td><?php echo $grade->punctuality;?></td> </tr>
          <tr>  <td >Discipline [On a 5 point (A-E) Grading Scale]</td> <td>-</td></tr>
       </table>
    </td>
 </tr>
 <tr><td height="5"></td></tr>  
 <tr>
   <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;">

                <tr><td valign="top" colspan="2" style="padding-top: 2px;text-align: center; font-size:12px; font-weight: bold;">Grading scale for Scholastic areas</td></tr>
                <tr>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ;padding: 6px;">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ; padding: 6px;">GRADE</td>
                </tr>
                <tr>
                  <td valign="top">91-100</td>
                  <td valign="top">A+</td>
                </tr>
                <tr>
                  <td valign="top">81-90</td>
                  <td valign="top">A</td>
                </tr>
                <tr>
                  <td valign="top">71-80</td>
                  <td valign="top">B+</td>
                </tr>
                <tr>
                  <td valign="top">61-70</td>
                  <td valign="top">B</td>
                </tr>
                <tr>
                  <td valign="top">51-60</td>
                  <td valign="top">C+</td>
                </tr>
                <tr>
                  <td valign="top">41-50</td>
                  <td valign="top">C</td>
                </tr>
                <tr>
                  <td valign="top">33-40</td>
                  <td valign="top">D</td>
                </tr>
                <tr>
                  <td valign="top">32 & Below</td>
                  <td valign="top">E(Needs improvement)</td>
                </tr>
              </table>
            </td>
 </tr>
  <!--<tr>
<td valign="top">
  <table width="100%" cellpadding="0" cellspacing="0">
   <tr>
    <?php if (!empty($grade)) { ?>
     <td valign="top" width="68%"  style="padding-right: 10px;">
       <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
       <tr> 
        <td style="font-weight: bold;background:#f7921e; color: #fff">Co-Scholastic Area</td>
        <td style="font-weight: bold;background:#f7921e; color: #fff">GRADE</td>            
       </tr>
          <tr>  <td>Work Education(Pro-vocational Education)</td><td><?php echo $grade->neatness ;?></td>   </tr>
          <tr>  <td>Art Education</td><td><?php echo $grade->drawing; ?></td>     </tr>
          <tr>  <td>Health & Physical Education</td><td><?php echo $grade->punctuality;?></td> </tr>
          <tr>  <td colspan="2">Discipline [On a 5 point (A-E) Grading Scale]</td> </tr>
       </table>
    </td>
    <?php   } ?>
            <td valign="top" width="30%">
              <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;">

                <tr><td valign="top" colspan="2" style="padding-top: 6px; height: 28px;text-align: center; font-size:12px; font-weight: bold;">Grading scale for Scholastic areas</td></tr>
                <tr>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ;padding: 6px;">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ; padding: 6px;">GRADE</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">91-100</td>
                  <td valign="top"  style="font-size:12px">A+</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">81-90</td>
                  <td valign="top" style="font-size:12px">A</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">71-80</td>
                  <td valign="top" style="font-size:12px">B+</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">61-70</td>
                  <td valign="top" style="font-size:12px">B</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">51-60</td>
                  <td valign="top" style="font-size:12px">C+</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">41-50</td>
                  <td valign="top" style="font-size:12px">C</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">33-40</td>
                  <td valign="top" style="font-size:12px">D</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">32 & Below</td>
                  <td valign="top" style="font-size:12px">E(Needs improvement)</td>
                </tr>

              </table>
            </td>
               </tr>
             </table>
          </td>
    </tr>
  
 -->

  <tr><td height="5"></td></tr>
   <tr>
    <td valign="">
      <table width="100%" cellpadding="0" cellspacing="0" >
        <tr>
          <td valign="bottom" align="left" style="font-weight: bold;">
          Place : JABALPUR<br/>  
          Date : <?php echo date('d-M-Y')?> </td>
          <td valign="bottom" align="center" style="font-weight: bold;">
            <p>Class Teacher's Sign</p></td>
          <td valign="bottom" align="right" style="font-weight: bold;">
            <img src="<?php echo base_url('uploads/sign.png'); ?>" width="94" height="50" />
            <p>Principal's Sign</p></td>
        </tr>
      </table>
    </td>
  </tr>
        <tr><td valign="top" height="10"></td></tr>
        <tr><td valign="top" bgcolor="1" height="1"></td></tr>
        <tr><td valign="top" height="10"></td></tr>
   <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0">
         <tr>
          <td valign="top" style="text-align: left; font-size: 14px;padding-bottom: 5px;">Website: www.centralacademyjabalpur.com</td>
          <td valign="top" style="text-align: right; font-size: 14px;padding-bottom: 5px;">Email: management@centralacademyjabalpur.com</td>
        </tr>
      </table>
    </td>
  </tr>
<tr><td valign="top" height="5"></td></tr> 
</table>
   
</div>
</body>
</html>