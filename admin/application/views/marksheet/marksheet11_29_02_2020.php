<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta  name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />
    <title>Central Academy Jabalpur</title> 
  <style type="text/css">
  *{margin:0; padding: 0;}
    html { width: 100%;}
    body{font-family: Arial;margin:0; padding: 0;}
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
              <td valign="top" style="text-align: center; font-size: 16px;" ><b>Session:</b> <?php echo
              $sessionname;?></td>
              <td valign="top" style="text-align: right; font-size: 16px;" ><b>School Code No.:</b> 50262</td>
            </tr>
        </table>
      </td>
    </tr>
    <tr><td valign="top" height="5"></td></tr>
    <tr><td valign="top" bgcolor="1" height="1"></td></tr>
    <tr><td valign="top" height="5"></td></tr>   
    <tr>
      <td valign="top">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
 
             <td valign="top" align="left" width="140">
              <img src="<?php echo 'http://softwares.centralacademyjabalpur.com/'.$student['image'] ; ?>" style="height: 120px; width:90px; border: 2px solid #000; border-radius: 10px;" />
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                <tr>
                  <td valign="top" style="font-weight: bold; padding-top: 20px; padding-bottom: 5px">Scholar No.: <?php echo $student['scholar_no'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Student's Name: <span style="color: #f7921e"><?php echo $student['name'] ; ?> </span></td>
                </tr>                
                 <tr>  
                  <td valign="top" style="font-weight: bold;  padding-bottom: 5px">Mother's Name: <?php echo $student['mname'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Father's Name: <?php echo $student['fname'] ; ?></td>
                </tr>

              </table>
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-top: 20px">Date of Birth : <?php echo $student['dob'] ; ?></td>
                </tr>
                <tr>
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px; padding-bottom: 5px">Class/ Section: <?php echo $student['class'].'/'.$student['section'] ; ?></td>
                </tr>               
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Roll No: <?php echo $student['rollno'] ; ?></td>
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
            <td valign="middle" width="25%" rowspan="2" style="text-transform: uppercase; font-weight: bold ;background:#f7921e;color: #fff">Subject</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">QUARTERLY</td>
            <td valign="middle" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">&nbsp;</td>
            <td valign="middle" colspan="3" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">HALF YEARLY</td>
            <td valign="middle" colspan="3" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">ANNUAL</td>
          </tr>
          <tr>
           
            <td valign="middle" >Max </td>   
            <td valign="middle" > Obt</td>   
            <td valign="middle" >Max</td>               
            <td valign="middle" >Theory</td>
            <td valign="middle" >Prac</td>   
            <td valign="middle" >Total</td>           
            <td valign="middle" >Theory</td>
            <td valign="middle" >Prac</td> 
            <td valign="middle" >Total</td>
          <!--   <td valign="middle" colspan="2">Theory<br>Max | Obt</td>              
            <td valign="middle" colspan="2">Prac<br>Max | Obt</td>        -->       
          </tr>
        <?php foreach ($subject as $key => $sub) { ?>
          <tr>
            <td valign="middle" style="text-align: left"><?php echo $sub ;?></td>             
            <td valign="middle"><?php echo $quaterlyfull[] = $quaterly[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $quaterlyget[] = $quaterly[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $halfyearlypracfull[] = $halfyearlyprac[$sub]['full'] + $halfyearly[$sub]['full'] ;?></td>         
            <td valign="middle"><?php echo $halfyearlyget[] =  $halfyearly[$sub]['get'] ;?></td>
               <td valign="middle"><?php echo $halfyearlypracget[] = $halfyearlyprac[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $halfyearly[$sub]['get'] +$halfyearlyprac[$sub]['get']  ;?></td>
            <?php  $finalpracfull[] = $finalprac[$sub]['full'] + $final[$sub]['full']; ;?>
                   
            <td valign="middle"><?php echo $finalget[] = $final[$sub]['get'] ;?></td>    
              <td valign="middle"><?php echo $finalpracget[] = $finalprac[$sub]['get'] ; ?></td>  
             <td valign="middle"><?php echo  $final[$sub]['get'] + $finalprac[$sub]['get'];?></td>        
           <?php  $fulltotal[] = ($finalprac[$sub]['full']);
                  $gettotal[] =  ($final[$sub]['get'] + $finalprac[$sub]['get'] ) ; ?>
           </tr>
         <?php } ?>
            <tr style="background: #f7921e;color: #fff;" >
               <td valign="top" style="text-align: center;color: #fff; font-weight: bold;">Total   </td>            
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($quaterlyfull) ;  ?> </td>
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($quaterlyget) ;  ?> </td>
                <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($halfyearlypracfull) ;  ?> </td>
                  
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($halfyearlyget) ;  ?> </td>
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($halfyearlypracget) ;  ?> </td> 
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($halfyearlyget) + array_sum($halfyearlypracget) ;  ?> </td>
           
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo $totget = array_sum($finalget) ;  ?> </td>    
                 <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php echo array_sum($finalpracget) ;  ?> </td>         
              <td valign="top" style="text-align: center;color:#fff; font-weight: bold;"><?php  array_sum($finalget) +array_sum($finalpracget)  ;  ?> </td>             
            </tr>
            <tr style="background: #f7921e;color: #fff;" >                
              <td valign="top"  width="" style="text-align: left;color: #fff; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Percentage : </td>
              <td  colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;  </td>   
               
               <td  colspan="7" style="text-align:right; color: #fff;font-weight: bold; padding-right: 20px;">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $pass = round((($totget* 100 )/ $totfull),2) ;?>  %  </td>               
            </tr>
            <tr style="background: #f7921e;color: #fff;" >
                 <td valign="top" style="text-align: left; color: #fff;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Attendance : </td>
                 <td valign="top" colspan="9"  width="" style="text-align: right; padding-right: 20px; color: #fff;font-weight: bold;">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; /  </td>
            </tr>
         </table>
    </td>
  </tr>
  <tr><td valign="top" height="10"></td></tr>
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #333; padding: 10px">
        <tr>
          <td valign="top" style="font-weight: bold; font-size: 16px; text-transform: uppercase;">Class Teacher's Remarks - 
            <span>...................</span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;">RESULT - 
            <span> <?php echo ($pass > 32)?"PASS":"FAIL";?></span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;text-align: right;">PROMOTED TO CLASS -
           <span ><?php echo ($pass > 32)?"XII th":"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";?></span>
         </td>
        </tr>
     </table>
    </td>
  </tr>

  
  
  <tr><td height="5"></td></tr> 
 
  <tr>

<td valign="top">
       <table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <?php if (!empty($grade)) {?>
    <td valign="top" width="68%"  style="padding-right: 10px;">
       <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
          <tr> 
            <td style="font-weight: bold;background:#f7921e; color: #fff">Grade In Subject Of Internal Assessment</td>
            <td style="font-weight: bold;background:#f7921e; color: #fff">GRADE</td>            
          </tr>
          <tr>  <td>GENERAL STUDIES</td>   <td><?php echo $grade->drawing ?></td> </tr>
          <tr>  <td>WORK EXPERIENCE</td>   <td><?php echo $grade->neatness ?></td> </tr>
          <tr>  <td>PHYSICAL EDUCATION</td>   <td><?php echo $grade->punctuality ?></td> </tr>
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
            <!-- <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">

                <tr><td valign="top" colspan="2" style="padding: 8px; text-align: center; font-size:18px; font-weight: bold;">Grading scale for scholastic areas</td></tr>
                <tr>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff">GRADE</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">91-100</td>
                  <td valign="top"  style="font-size:12px">A1</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">81-90</td>
                  <td valign="top" style="font-size:12px">A2</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">71-80</td>
                  <td valign="top" style="font-size:12px">B1</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">61-70</td>
                  <td valign="top" style="font-size:12px">B2</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">51-60</td>
                  <td valign="top" style="font-size:12px">C1</td>
                </tr>
                <tr>
                  <td valign="top" style="font-size:12px">41-50</td>
                  <td valign="top" style="font-size:12px">C2</td>
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
            </td> -->
  </tr>
  


  <tr><td height="20"></td></tr>
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
