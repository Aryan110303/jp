<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta  name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />
    <title>Central Academy Jabalpur </title> 
  <style type="text/css">
  *{margin:0; padding: 0;}
    html { width: 100%;}
   
  .table{border-collapse: collapse;}
  .table td{border: 1px solid #333; padding: 8px 10px;}

   .table2{border-collapse: collapse;}
  .table2 td{border: 1px solid #333; padding: 8px 0px;}
</style>
</head>
<body>

 <div style="width:1000px; margin:0px auto; position: relative; z-index: 1;font-size: 15px; 
      color: #000; line-height: 20px; font-family: Arial">
 <img src="bobybg.png" style="position: absolute; top:30%; left: 0; right:0; bottom:0; margin-right: auto; margin-left: auto; background-size: 100% 100%; width: 60%;  z-index:-1; opacity: .10;" />
<table cellpadding="0" cellspacing="0" width="100%" align="center">
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0">
         <tr>
          <td width="100%" valign="top" align="center" style="height: 300px;"></td>
        </tr>
       
      </table>
    </td>
  </tr>

   <tr>
      <td valign="top" align="right">
        <table width="100%" align="center" cellpadding="0" cellspacing="0">
           <tr>
              <td valign="top" width="" style=" font-size: 16px;">Affiliation No.: 1030293</td>
              <td valign="top" style="text-align: center; font-size: 16px;" >Session :<?php echo $sessionname ;?></td>
              <td valign="top" style="text-align: right; font-size: 16px;" >School Code No. : 14151</td>
            </tr>
        </table>
      </td>
    </tr>     
  <tr><td height="10"></td></tr>

    <tr>
      <td valign="top">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                <tr>
                  <td valign="top" style="font-weight: bold; padding-top: 20px; padding-bottom: 5px">Scholar No.: 3722</td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Roll No: <?php echo $student['rollno'] ; ?></td>
                </tr>

                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Student's Name: <?php echo $student['name'] ; ?></td>
                </tr>

                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Father's Name: <?php echo $student['name'] ; ?></td>
                </tr>
      

              </table>
            </td>
            <td valign="top">
              <table width="100%" cellpadding="0" cellspacing="0" class="">
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-top: 20px; padding-bottom: 5px">Class/ Section: <?php echo $student['class'].'/'.$student['section'] ; ?></td>
                </tr>
                <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Date of Birth : <?php echo $student['dob'] ; ?></td>
                </tr>
                 <tr>  
                  <td valign="top" style="font-weight: bold;  padding-bottom: 5px">Mother's Name: <?php echo $student['mname'] ; ?></td>
                </tr>
              </table>
            </td>

            <td valign="top" align="right">
              <img src="student.png" style="height: 120px;border: 1px solid #000;padding: 6px;" />
            </td>

          </tr>
        </table>
      </td>
    </tr>

    <tr><td height="20"></td></tr> 

  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
           <tr>
            <td valign="middle" width="25%" rowspan="2" style="text-transform: uppercase; font-weight: bold;">Subject</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;">QUARTERLY</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;">HALF YEARLY</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;">FINAL</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;">GRAND TOTAL</td>
          

          </tr>

          <tr>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt</td>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt</td>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt</td>
            <td valign="middle">Max Marks</td>
            <td valign="middle">Marks Obt</td>
        
          </tr>

          
        <?php 
       
  
        foreach ($subject as $key => $sub) {
        
        ?>
          <tr>

            <td valign="middle" style="text-align: left"><?php echo $sub ;?></td>
            <td valign="middle"><?php echo $quaterly[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $quaterly[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $halfyearly[$sub]['full'] ;?></td>
            <td valign="middle"><?php echo $halfyearly[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $final[$sub]['full'];?></td>
            <td valign="middle"><?php echo $final[$sub]['get'] ;?></td>
            <td valign="middle"><?php echo $fulltotal[] = ($halfyearly[$sub]['full'])+ ($quaterly[$sub]['full']) + ($final[$sub]['full']);?></td>
            <td valign="middle"><?php echo $gettotal[] =  ($halfyearly[$sub]['get'])+($quaterly[$sub]['get']) + ($final[$sub]['get']) ;?></td>
          
          </tr>
         <?php
         
            }
         ?>
         

      </table>
    </td>
  </tr>

  <tr><td valign="top" height="20"></td></tr>

  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" style="width: 30%;">
            <table width="100%" cellpadding="0" cellspacing="0" class="table">
              <tr> 
                <td valign="top" width="" style="text-align: center; font-weight: bold;">Total</td>
                <td valign="top" width="40%" style="text-align: center;"><?php echo array_sum($gettotal).'/'.array_sum($fulltotal) ; ?></td></tr>
            </table>
          </td>
          <td valign="top">
            <table width="100%" cellpadding="0" cellspacing="0" style="width: 200px; margin: 0 auto;"  class="table">
              <tr> 
                <td valign="top" width="" style="text-align: center; font-weight: bold;">Percentage</td>
                <td valign="top" width="30%" style="text-align: center;">%</td></tr>
            </table>
          </td>
          <td valign="top" align="right" style="width: 30%;">
            <table width="100%" cellpadding="0" cellspacing="0"  class="table">
              <tr> 
                <td valign="top" width="" style="text-align: center; font-weight: bold;">Attendance</td>
                <td valign="top" width="35%" style="text-align: center;">87/201</td></tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

   <tr><td height="20"></td></tr> 
   <tr><td valign="top" style="padding-bottom: 15px; font-weight: bold; font-size: 16px;">Class Teacher's Remarks - Very Good</td></tr>
   <tr><td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 5px;">RESULT - PASS</td></tr>
   <tr><td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 5px;">PROMOTED TO CLASS - XII</td></tr>

  
  
  <tr><td height="20"></td></tr> 

  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
       
            <tr><td valign="top" colspan="2" style="padding: 15px; text-align: center; font-size: 16px">Grading scale for scholastic areas</td></tr>
               <tr>
                  <td valign="top" style="font-weight: bold;">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;">GRADE</td>
               </tr>
               <tr>
                  <td valign="top">91-100</td>
                  <td valign="top">A1</td>
               </tr>
               <tr>
                  <td valign="top">81-90</td>
                  <td valign="top">A2</td>
               </tr>
               <tr>
                  <td valign="top">71-80</td>
                  <td valign="top">B1</td>
               </tr>
               <tr>
                  <td valign="top">61-70</td>
                  <td valign="top">B2</td>
               </tr>
               <tr>
                  <td valign="top">51-60</td>
                  <td valign="top">C1</td>
               </tr>
               <tr>
                  <td valign="top">41-50</td>
                  <td valign="top">C2</td>
               </tr>
               <tr>
                  <td valign="top">33-40</td>
                  <td valign="top">D</td>
               </tr>
               <tr>
                  <td valign="top">32 & Below</td>
                  <td valign="top">E(Needs improvemnet)</td>
               </tr>
           
      </table>
    </td>
  </tr>
  


  <tr><td height="40"></td></tr>

   <tr>
    <td valign="">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="bottom" align="left" style="font-weight: bold;">
          Place : JABALPUR<br/>  
          Date : 10-10-2019 </td>
          <td valign="bottom" align="center" style="font-weight: bold;">
            <img src="sign.png" width="94" height="50" />
            <p>Class Teacher's Sign</p></td>
          
          
          <td valign="bottom" align="right" style="font-weight: bold;">
            <img src="sign.png" width="94" height="50" />
            <p>Principal's Sign</p></td>
        </tr>
      </table>
    </td>
  </tr>
 
</table>
   
</div>
</body>
</html>
