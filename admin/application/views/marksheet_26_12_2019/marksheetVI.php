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
                  <td valign="top" style="font-weight: bold; padding-top: 5px ;padding-bottom: 5px">Student's Name: <span style="color: #f7921e"><?php echo $student['name'] ; ?> </span></td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold;  padding-bottom: 5px">Mother's Name: <?php echo $student['mname'] ; ?></td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Father's Name: <?php echo $student['fname'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Date of Birth : <?php echo $student['dob'] ; ?></td>
                </tr>
              </table>
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                 <tr>
                  <td valign="top" style="font-weight: bold; padding-top: 25px ; padding-bottom: 5px">Scholar No.: <?php echo $student['scholar_no'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Roll No: <?php echo $student['rollno'] ; ?></td>
                </tr>
                <tr>
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Class/ Section: <?php echo $student['class'].'/'.$student['section'] ; ?>
                    
                  </td>
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
          <?php
    foreach ($subject as $key => $sub) { ?>
           <tr>
            <td valign="middle" style="text-align: left"><?php echo $sub; ?></td>
            <td valign="middle"><?php echo $periodicI[$sub]['get']; ?></td>           
            <td valign="middle"><?php echo $periodicII[$sub]['get']; ?></td>        
            <td valign="middle"><?php echo $periodicIII[$sub]['get']; ?></td>
            <td valign="middle"><?php echo $final_term[$sub]['get']; ?></td>
            <td valign="middle"></td>
            <td valign="middle"></td>
            <td valign="middle"></td>
            <td valign="middle"></td>
          </tr>
           <?php } ?>
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
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="background: #f7921e;color: #fff;">
              <tr>
                <td>76</td>
                <td>76</td>
                <td>570</td>
                <td>657</td>
                
              </tr>
              <tr>
                <td colspan="" style="text-align: center;">60%</td>
                <td colspan="" style="text-align: center;">60%</td>
                <td colspan="" style="text-align: center;">60%</td>
                <td colspan="" style="text-align: center;">60%</td>
              </tr>
              <tr><td colspan="4" >&nbsp;</td></tr>
            </table>
          </td>
          <td valign="top" style=" padding-left: 6px;" align="right">
            <table width="100%" cellpadding="0" cellspacing="0" class="table" style="background: #f7921e;color: #fff;">
              <tr>
                <td>555</td> 
                <td>5555</td>
                <td>550</td>
                <td>55</td>
              </tr>
              <tr><td colspan="4" style="height: 56px; text-align: center; font-weight: bold;">0%</td></tr>
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
              <td valign="top" style="font-weight: bold; font-size: 16px; text-transform: uppercase;">Class Teacher's Remarks - <span style="background: #f7921e;color:#fff;padding: 4px 8px; border-radius: 2px;border: 1px solid #333;">Very Good</span></td>
             <td valign="top" style="text-transform: uppercase; font-weight: bold; text-align: right;">RESULT - <span style="background:#f7921e;color: #fff;padding: 4px 8px; border-radius: 2px;border: 1px solid #333;">PASS</span></td>
            </tr>
         </table>
      </td>
    </tr>
    <tr><td height="5"></td></tr> 
     <tr>
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

