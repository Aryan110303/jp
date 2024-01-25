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
   .table3{border-collapse: collapse;}
  .table3 td{border: 1px solid #333; padding: 2px 0px;}

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
              <img src="<?php echo 'http://softwares.centralacademyjabalpur.com/'.$student['image'] ; ?>" style="height: 120px;width:90 ;border: 2px solid #000; border-radius: 10px;" />
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                <tr>
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Scholar No.: <?php echo $student['scholar_no'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-top: 5px ;padding-bottom: 5px">Student's Name: <span style="color: #f7921e"><?php echo $student['name'] ; ?> </span></td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold;  padding-bottom: 5px">Mother's Name: <?php echo $student['mname'] ; ?></td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Father's Name: <?php echo $student['fname'] ; ?></td>
                </tr>
               
              </table>
            </td>
            <?php 
            $romans = array( 
                            'XI' => 11000,
                            'X' => 10000,
                            'IX' => 9000,
                            'VIII' => 8000,
                            'VII' => 7000,
                            'VI' => 6000,
                            'V' => 5000,
                            'IV' => 4000,
                            'III' => 3000,
                            'II' => 2000,
                            'I' => 1000,
                          );
           
            ?>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                <tr>  
                  <td valign="top" style="font-weight: bold;padding-top: 25px ; padding-bottom: 5px">Roll No: <?php echo  $romans[$student['class']] + $student['rollno']; ?></td>
                 
                </tr>
                <tr>
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Class / Section: <?php echo $student['class'].'/'.$student['section'] ; ?>
                    
                  </td>
                </tr>    
                    <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Date of Birth : <?php echo $student['dob'] ; ?></td>
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
    <tr><td height="10"></td></tr> 
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
          <tr>
            <td valign="middle" width="25%" rowspan="3" style="text-transform: uppercase; font-weight: bold; background:#f7921e;color: #fff">Subjects</td>
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
            <td valign="middle" rowspan="2">Marks Obt.</td>
            <td valign="middle" rowspan="2">Max Marks</td>
            <td valign="middle" rowspan="2">Marks Obt.</td>
          </tr>

          <tr style="background: #f7921e;color: #fff;font-weight: bold;">
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Marks Obt.</td>
            <td valign="middle">Marks Obt.</td>
          </tr>
          <?php
          $chk = 0;
          $chk_count = 0;
          $sub_count = 0;
    foreach ($subject as $key => $sub) { ?>
           <tr>
            <td valign="middle" style="text-align: left"><?php echo $sub; ?></td>
            <td valign="middle"><?php echo $p1[] = $periodicI[$sub]; ?></td>           
            <td valign="middle"><?php echo $p2[] = $periodicII[$sub]; ?></td>        
            <td valign="middle"><?php echo $p3[] = $periodicIII[$sub]; ?></td>
            <td valign="middle"><?php echo $p4[] = $final_term[$sub] ;?></td>
            <td valign="middle"><?php echo $p7[] = 200 ;?></td>
            <td valign="middle"><?php echo $p5[] = $tot =  $periodicI[$sub] + $periodicII[$sub] + $periodicIII[$sub] + $final_term[$sub] ;?></td>
            <td valign="middle"><?php echo $p8[] = 100 ;?></td>
            <td valign="middle"><?php echo $p6[] = $chk = round(($tot / 2 ),2);
              if ($chk < 33) {
                $chk_count++ ;
              }
              ?></td>
          </tr>
    <?php $sub_count++; } ?>
        </table>
    </td>
  </tr>

  <tr><td valign="top" height="5"></td></tr>
  <tr>
    <td valign="top" style="font-weight: bold;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" valign="top" style="width: 25%;" >
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="background: #f7921e;color: #fff; ">
              <tr><td style="font-weight: bold; text-transform: uppercase;">TOTAL</td></tr>
              <tr><td style="font-weight: bold; text-transform: uppercase;">PERCENTAGE</td></tr>
              <tr><td style="font-weight: bold; text-transform: uppercase;">ATTENDANCE</td></tr>
            </table>
          </td>
          <td valign="top" style=" padding-left: 6px; width: 42% ;">
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="background: #f7921e;">
              <tr>
                <td><?php echo array_sum($p1); ?></td>
                <td><?php echo array_sum($p2); ?></td>
                <td><?php echo array_sum($p3); ?></td>
                <td><?php echo array_sum($p4); ?></td>
              </tr>
              <tr>
                <td style="text-align: center;">&nbsp;<?php echo round((array_sum($p1)*100)/($sub_count*20),2) ;?> %</td>
                <td style="text-align: center;">&nbsp;<?php echo round((array_sum($p2)*100)/($sub_count*80),2) ;?> %</td>
                <td style="text-align: center;">&nbsp;<?php echo round((array_sum($p3)*100)/($sub_count*20),2) ;?> %</td>
                <td style="text-align: center;">&nbsp;<?php echo round((array_sum($p4)*100)/($sub_count*80),2) ;?> %</td>
               
              </tr>
              <tr>
                 <td colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <?php echo $student['present_days'] ; ?>  /  <?php echo $student['total_days'] ; ?>
                </td>
             </tr>
            </table>
          </td>
          <td valign="top" style=" padding-left: 6px;" align="right">
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="background: #f7921e;">
              <tr>
                <td><?php echo array_sum($p7); ?></td> 
                <td><?php echo array_sum($p5); ?></td>
                <td><?php echo array_sum($p8); ?></td>
                <td><?php echo array_sum($p6); ?></td>
              </tr>
              <tr>
                <td colspan="4" style="height: 56px; text-align: center; font-weight: bold;"><br> &nbsp;<?php echo $pass= round(((array_sum($p6) * 100 )/array_sum($p8) ),2) ;?> % <br> &nbsp;</td></tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr><td height="5"></td></tr> 

    <tr><td valign="top">
         <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #333; padding: 10px">
           <tr>
              <td valign="top" style="font-weight: bold; font-size: 16px; text-transform: uppercase;">Class Teacher's Remarks - <span >...........</span></td>
             <td valign="top" style="text-transform: uppercase; font-weight: bold; text-align: right;">RESULT - <span ><?php
             if ($chk_count > 0) {
              $pass = 0;
             }
               echo ($pass > 32)?"PASS":"FAIL";?></span></td>
              <td valign="top" style="text-transform: uppercase; font-weight: bold;text-align: right;">PROMOTED TO CLASS -
           <span ><?php  if ($student['class'] == 'VI') {
                        $class =' VII ';
                        }elseif ($student['class'] == 'VII') {
                        $class =' VIII ';
                        }elseif ($student['class'] == 'VIII') {
                        $class =' IX ';
                        }

               echo ($pass > 32)?$class:" ";?></span>
         </td>
            </tr>
         </table>
      </td>
    </tr>
    <tr><td height="5"></td></tr> 
    <!--  <tr>
      <td valign="top">
         <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
           <tr>
              <td style="font-weight: bold;background:#f7921e; color: #fff; text-transform: uppercase;">CO-SCHOLASTIC AREAS<br>
                (On a 5 point grading scale : A-E)</td>
                <td style="font-weight: bold;background:#f7921e; color: #fff; text-transform: uppercase;">GRADE</td>  
                <td style="font-weight: bold;background:#f7921e; color: #fff; text-transform: uppercase;">OverALL Grade</td> 
              </tr>
                <tr>
                  <td>Drawing</td>
                  <td>A</td>
                  <td rowspan="6">E (Needs Improvement)</td>
               </tr>
                <tr>
                  <td>Neatness</td>
                  <td>A+</td>
                </tr>
                <tr>
                  <td>Punctuality</td>
                  <td>A</td>
                </tr>
                <tr>
                  <td>Courteousness</td>
                  <td>A+</td>
                </tr>

                <tr>
                  <td>Obedience</td>
                  <td>A</td>
                </tr>
                <tr>
                  <td>Discipline</td>
                  <td>A+</td>
              </tr>
         </table>
      </td>
    </tr>-->
<tr>
     <td valign="top">
       <table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <?php if (0 && !empty($grade)) {?>
    <td valign="top" width="68%"  style="padding-right: 10px;">
       <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
          <tr> 
            <td style="font-weight: bold;background:#f7921e; color: #fff">Co-Scholastic Area</td>
            <td style="font-weight: bold;background:#f7921e; color: #fff">GRADE</td>            
          </tr>
          <tr>  <td>Drawing</td>   <td><?php echo $grade->drawing ?></td> </tr>
          <tr>  <td>Neatness</td>   <td><?php echo $grade->neatness ?></td> </tr>
          <tr>  <td>Punctuality</td>   <td><?php echo $grade->punctuality ?></td> </tr>
          <tr>  <td>Courteousness</td>   <td><?php echo $grade->courteousness ?></td> </tr>
          <tr>  <td>Obedience</td>   <td><?php echo $grade->obedience ?></td> </tr>
          <tr>  <td>Discipline</td>   <td><?php echo $grade->discipline ?></td> </tr>
       </table>
    </td>
    <?php   } ?>
            <td valign="top" width="30%">
              <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;">

                <tr><td valign="top" colspan="2" style="padding-top: 6px; height: 28px;text-align: center; font-size:12px; font-weight: bold;">Grading scale for co-scholastic areas</td></tr>
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
                  <td valign="top" style="font-size:12px">E(Needs improvemnet)</td>
                </tr>

              </table>
            </td>
               </tr>
             </table>
          </td>
    </tr>

  <tr><td height="5"></td></tr>
   <tr>
    <td valign="">
      <table width="100%" cellpadding="0" cellspacing="0">
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
<tr><td valign="top" height="10"></td></tr> 
</table>
   
</div>
</body>
</html>

