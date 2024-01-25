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
  .table td{border: 1px solid #333; padding: 5px 10px;}
  .table2{border-collapse: collapse;}
  .table2 td{border: 1px solid #333; padding: 5px 0px;}

  .table3{border-collapse: collapse;}
  .table3 td{border: 1px solid #333; padding: 5px 0px; font-size: 10px}
  hr {background-color:#333; height: 1px; display: block;  margin-left: -6px; margin-right: -6px; }
</style>
</head>
<body>

 <div style="width:100%; margin:0px auto; position: relative; z-index: 1;
      color: #000; font-family: Arial; border: 16px solid #f7921e;padding: 3px;background: #feefd8;">

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
              <tr><td valign="top" height="10"></td></tr>
              <tr> <td valign="top"  style="border:2px solid #000; height:80px; width:58px; "> &nbsp;</td> </tr>
             </table>
         </td>
        </tr>
      </table>
    </td>
  </tr>

   <tr>
    <td valign="top" style="text-align: center; font-size: 14px;padding-bottom: 2px;">Mathura Vihar, Vijay Nagar, Jabalpur - 482002 (M.P.)  <span style="padding-left: 10px;">Phone:</span> 0761-4928811</td>
  </tr>
   
  <tr><td valign="top" height="5"></td></tr>
  <tr><td valign="top" bgcolor="1" height="1"></td></tr>
  <tr><td valign="top" height="5"></td></tr> 
   <tr>
      <td valign="top" align="right">
        <table width="100%" align="center" cellpadding="0" cellspacing="0">
           <tr>
              <td valign="top" width="" style=" text-align: left; font-size: 16px;"><b>Affiliation No. :</b> 1030293</td>
              <td valign="top" style="text-align: center; font-size: 16px;" ><b>Session :</b> <?php echo
              $sessionname;?></td>
              <td valign="top" style="text-align: right; font-size: 16px;" ><b>School Code :</b> 50262</td>
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
              <img src="<?php echo 'http://softwares.centralacademyjabalpur.com/'.$student['image'] ; ?>" style="height: 120px; width:100px;border: 2px solid #000; border-radius: 10px;" />
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
                  <td valign="top" style="font-weight: bold; padding-top: 20px">Roll No: <?php echo  $romans[$student['class']] + $student['rollno']; ?></td>
                </tr>
                <tr>
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px; padding-bottom: 5px">Class / Section: <?php echo $student['class'].'/'.$student['section'] ; ?></td>
                </tr>               
                 <tr>  
                  <td valign="top" style="font-weight: bold; padding-bottom: 5px">Date of Birth : <?php echo $student['dob'] ; ?></td>
                </tr>
                
              </table>
             </td>
              <td valign="middle" align="right" style="padding-right: 3px;">
               <img src="<?php echo base_url().$student['qrimage'] ; ?>" style="height: 80px;width:80px ;border: 2px solid #000; " />
            </td>
           

          </tr>
        </table>
      </td>
    </tr>

    <tr><td height="5"></td></tr> 

  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;">
           <tr>
            <td valign="middle" width="25%" rowspan="2" style="text-transform: uppercase; font-weight: bold ;background:#f7921e;color: #fff">Subjects</td>
            <td valign="middle" colspan="3" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">QUARTERLY</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">HALF YEARLY</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">ANNUAL</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">GRAND TOTAL</td>
            <td valign="middle" colspan="2" style="text-transform: uppercase; font-weight: bold;background:#f7921e;color: #fff">YEAR'S AVG</td>
          </tr>
          <tr>     
            <td valign="middle"> </td>       
            <td valign="middle">Max </td>   
            <td valign="middle"> Obt.</td> 

             <td valign="middle">Max </td>   
            <td valign="middle"> Obt.</td> 

             <td valign="middle">Max </td>   
            <td valign="middle"> Obt.</td> 

             <td valign="middle" >Max </td>   
            <td valign="middle"> Obt.</td>   

             <td valign="middle">Max </td>   
            <td valign="middle"> Obt.</td>                   
                          
          </tr>
        <?php 
       
        $count= 0;

        foreach ($subject as $key => $sub) { 
           $c= 0;?>
          <tr>
            <td valign="middle" style="text-align: left"><?php echo $sub ;?></td> 
             <td valign="middle"> Theory 
              <hr/> Practical</td>            
            <td valign="middle"><?php echo $quaterlyfull[] = $quaterly[$sub]['full'] ;?>
               <hr/>
              0
            </td>
            <td valign="middle"><?php echo $quaterlyget[] = $quaterly[$sub]['get'] ;?>
               <hr/>
              0
            </td> 

            <td valign="middle"><?php echo $halfyearlyfull[] =  $halfyearly[$sub]['full'] ;?>
                 <hr/>
                <?php echo $halfyearlypracfull[] = ($halfyearlyprac[$sub.'(Prac)']['full']>0)?$halfyearlyprac[$sub.'(Prac)']['full']:0 ;?>
            </td>         
            <td valign="middle"><?php echo $halfyearlyget[] =  $halfyearly[$sub]['get'] ;?>
              <hr/>
              <?php echo $halfyearlypracget[] =  ($halfyearlyprac[$sub.'(Prac)']['get']>0)?$halfyearlyprac[$sub.'(Prac)']['get']:0 ;?>
              <?php  $halfyearlytotal[] = $halfyearly[$sub]['get'] + $halfyearlyprac[$sub.'(Prac)']['get']  ;?>
            </td>
        
         
            <td valign="middle">
              <?php echo $finalfull[] = $final[$sub]['full']; ?> 
                <hr />
              <?php echo $finalpracfull[] =($finalprac[$sub.'(Prac)']['full']>0)?$finalprac[$sub.'(Prac)']['full']:0;?>            
            </td> 

            <td valign="middle">
              <?php echo $finalget[] = $final[$sub]['get'] ;?>
              <hr/>
              <?php echo $finalpracget[] = ($finalprac[$sub.'(Prac)']['get']>0)?$finalprac[$sub.'(Prac)']['get']:0 ; ?>
              <?php $finaltotal[] =  $final[$sub]['get'] +  $finalprac[$sub.'(Prac)']['get'];?>
            </td>  

            <td valign="middle">
             <?php echo $grandtotal[] =  $final[$sub]['full'] + $halfyearly[$sub]['full'] + $quaterly[$sub]['full'] ;?>
            <hr/>
            <?php echo $grandtotalprac[] = $finalprac[$sub.'(Prac)']['full'] + $halfyearlyprac[$sub.'(Prac)']['full']  ;?>              
            </td> 

            <td valign="middle"><?php echo $grandtotalget[] = $final[$sub]['get'] + $halfyearly[$sub]['get'] + $quaterly[$sub]['get'] ;?>              
              <hr/>
              <?php echo $grandtotalgetprac[] = $finalprac[$sub.'(Prac)']['get'] + $halfyearlyprac[$sub.'(Prac)']['get']  ;?>
            </td> 
        
            <td valign="middle"><?php echo $avgtotal[] = 100 ; ?>
              <hr/>
              <?php echo $avgtotalprac[] = (($finalprac[$sub.'(Prac)']['full'])>0)?100:0 ;?>
            </td>  

            <td valign="middle"><?php echo $avgtotalget[] = $c = round( (($final[$sub]['get'] + $halfyearly[$sub]['get'] + $quaterly[$sub]['get'] )*100)/($final[$sub]['full'] + $halfyearly[$sub]['full'] + $quaterly[$sub]['full']),2); 
            if ($c < 33) {
              $count++;
            }
             ?>
              <hr/>
              <?php echo $avgtotalgetprac[] = (($finalprac[$sub.'(Prac)']['full'])>0)?round( (($finalprac[$sub.'(Prac)']['get'] + $halfyearlyprac[$sub.'(Prac)']['get'])*100)/($finalprac[$sub.'(Prac)']['full'] + $halfyearlyprac[$sub.'(Prac)']['full']),2):0 ;?>
            </td>    
          </tr>
         <?php } ?>
            <tr style="background: #f7921e;" >
              <td valign="top" style="text-align: center;color: #fff; font-weight: bold;">Total   </td> 
              <td valign="top" style="text-align: center;color: #fff; font-weight: bold;"></td> 

              <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php echo $qf =  array_sum($quaterlyfull) ;  ?> </td>

              <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php echo  $qg =array_sum($quaterlyget) ;  ?> </td>

              <td valign="top" style="text-align: center;  font-weight: bold;">
                  <?php  $hf1 = array_sum($halfyearlyfull);
                         $hf2 = array_sum($halfyearlypracfull) ;
                           echo  $hf1 +$hf2;   ?> </td>
                  
              <td valign="top" style="text-align: center; font-weight: bold;">
                <?php echo array_sum($halfyearlyget) + array_sum($halfyearlypracget) ;
                 $hg =array_sum($halfyearlytotal)  ;  ?></td>

              <td valign="top" style="text-align: center; font-weight: bold;">
                <?php       $ff1 =array_sum($finalfull); 
                            $ff2 = array_sum($finalpracfull); 
                            echo $ff1+ $ff2;?> </td>   

              <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php echo array_sum($finalget) + array_sum($finalpracget);
                           $totfinalfull= $fg =array_sum($finaltotal);  ?> </td>         

             <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php  $totgrandfull= array_sum($grandtotal);
                       $totgrandfullp= array_sum($grandtotalprac); 
                       echo $totgrandfull +  $totgrandfullp ;?> </td>         

             <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php    $totgrandget = array_sum($grandtotalget);
                         $totgrandgetp = array_sum($grandtotalgetprac); 
                         echo $totgrandget+ $totgrandgetp ;?> </td>           

             <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php  $totfulla= array_sum($avgtotal); 
                     $totfullap= array_sum($avgtotalprac);
                     echo $totfulla + $totfullap;  ?> </td>         

             <td valign="top" style="text-align: center;  font-weight: bold;">
                <?php $totgeta = array_sum($avgtotalget); 
                      $totgetap = array_sum($avgtotalgetprac);  
                      echo $totgeta +$totgetap; ?> </td>
          </tr>


       <tr style="background: #f7921e; ">                
              <td valign="top"  width="" style="text-align: center; font-weight: bold;color: #fff;">Percentage : </td>
              <td style="text-align:right; font-weight: bold; padding-right: 20px;">&nbsp;</td> 
              <td  colspan="2" style="text-align:right;font-weight: bold; padding-right: 20px;"><?php echo round((($qg /$qf )*100),2) ?> %</td>   
               
               <td  colspan="2" style="text-align:right;font-weight: bold; padding-right: 20px;"><?php echo round((($hg /($hf2 + $hf1) )*100),2) ?> % </td>    

                <td  colspan="2" style="text-align:right; font-weight: bold; padding-right: 20px;">   <?php echo round((($fg / ($ff2 + $ff1) )*100),2) ?> % </td>

                 <td  colspan="2" style="text-align:right; font-weight: bold; padding-right: 20px;"> <?php echo round((( ($totgrandget + $totgrandgetp)* 100 )/($totgrandfull + $totgrandfullp)),2) ;?> %   </td> 

                <td  colspan="2" style="text-align:right; font-weight: bold; padding-right: 20px;"> <?php echo $pass = round((( ($totgeta +$totgetap)* 100 )/ ($totfulla + $totfullap)),2) ;?> %   </td>                  
         </tr>
        <tr style="background: #f7921e;" >
             <td valign="top" style="text-align: center; font-weight: bold;color: #fff;">
              Attendance : </td>
             <td valign="top" colspan="11"  width="" style="text-align: right; padding-right: 20px; font-weight: bold;"> <?php echo $student['present_days'] ; ?>  /  <?php echo $student['total_days'] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </td>
        </tr> 
         </table>
    </td>
  </tr>

  
  <tr><td valign="top" height="5"></td></tr>
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #333; padding: 10px">
        <tr>
          <td valign="top" style="font-weight: bold; text-transform: uppercase;">Class Teacher's Remarks - 
            <span>...................</span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;">RESULT - 
            <span> <?php 
           if ($count > 0 ) {
             $pass = 0 ;
           } echo ($pass > 32)?"PASS":"FAIL";?></span>
          </td>
          <td valign="top" style="text-transform: uppercase; font-weight: bold;text-align: right;">PROMOTED TO CLASS -
           <span ><?php echo ($pass > 32)?"XII":"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";?></span>
         </td>
        </tr>
     </table>
    </td>
  </tr>

  
  
  <tr><td height="5"></td></tr> 
 
  <tr>
     <td valign="top" >
       <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;font-size:16px; ">
          <tr> 
            <td style="font-weight: bold;background:#f7921e; color: #fff">Grade In Subject Of Internal Assessment</td>
            <td style="font-weight: bold; background:#f7921e; color: #fff">GRADE</td>            
          </tr>
          <tr>  <td>GENERAL STUDIES</td>   <td><?php echo $grade->drawing ?></td> </tr>
          <tr>  <td>WORK EXPERIENCE</td>   <td><?php echo $grade->neatness ?></td> </tr>
          <tr>  <td>PHYSICAL EDUCATION</td>   <td><?php echo $grade->punctuality ?></td> </tr>
           </table>
    </td>
  </tr>
  <tr><td height="5"></td></tr> 
  <tr>
     <td valign="top" >
              <table width="100%" cellpadding="0" cellspacing="0" class="table3" style="text-align: center;">

                <tr><td valign="top" colspan="2" style="padding-top: 6px; height: 28px;text-align: center; font-size:12px; font-weight: bold;">Grading scale for co-scholastic areas</td></tr>
                <tr>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ;">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ; ">GRADE</td>
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
                  <td valign="top">E(Needs improvemnet)</td>
                </tr>

              </table>
            </td>
  </tr>
  <!--<tr>

     <td valign="top">
       <table width="100%" cellpadding="0" cellspacing="0">
     <tr>
    <?php if (!empty($grade)) {?>
    <td valign="top" width="68%"  style="padding-right: 10px;">
       <table width="100%" cellpadding="0" cellspacing="0" class="table" style="text-align: center;font-size:16px; ">
          <tr> 
            <td style="font-weight: bold;background:#f7921e; color: #fff">Grade In Subject Of Internal Assessment</td>
            <td style="font-weight: bold; background:#f7921e; color: #fff">GRADE</td>            
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
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ;">MARKS RANGE</td>
                  <td valign="top" style="font-weight: bold;background:#f7921e; color: #fff ; ">GRADE</td>
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
                  <td valign="top">E(Needs improvemnet)</td>
                </tr>

              </table>
            </td>
               </tr>
             </table>
          </td>  
  </tr> -->
  
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
        <tr><td valign="top" height="5"></td></tr>
        <tr><td valign="top" bgcolor="1" height="1"></td></tr>
        <tr><td valign="top" height="5"></td></tr>
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
