<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta  name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />

  <style type="text/css">
  *{margin:0; padding: 0;}
    html { width: 100%; }
      body {margin-left:90px  ; padding:0; width:100%;  -webkit-text-size-adjust:none; -ms-text-size-adjust:none;}

    @font-face {
  font-family:'BRLNSB';
  src: url(<?php echo base_url('icard/BRLNSB.TTF') ;?>);
  font-weight: normal;
  font-style: normal;
}

 @font-face {
  font-family:'Roboto-Bold';
  src: url(<?php echo base_url('icard/Roboto-Bold.ttf'); ?> );
  font-weight: normal;
  font-style: normal;
}

 @font-face {
  font-family:'Roboto-Medium';
  src: url(<?php echo base_url('icard/Roboto-Medium.ttf'); ?> );
  font-weight: normal;
  font-style: normal;
}
  </style>
<?php
$school = $sch_setting[0];
$i = 0;
?>

</head>
<body><div style="position: relative; color: #fff; width: 48%; margin-left: -1%; float: left;">        
  <img src="<?php echo base_url().'icard/8.jpg' ?>" style="position: absolute; z-index: -1; width: 96%; height: 235px;">
<table cellpadding="0" cellspacing="0" width="100%" align="center" style="position: relative; z-index: 1">
  <tr>
      <?php
        foreach ($students as $student) { }
         
            ?>


    <td width="100%" valign="top" align="center">
      <table width="95%" align="center">
        <tr>
          <td valign="top" align="center" style="padding-top: 5px;"><img src="<?php echo base_url().'icard/logo.png'; ?>" width="50" height="64" ></td>
          <td valign="top">
            <table style="width: 100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" style="color: white"><div style="font-size: 30px;-webkit-text-fill-color: white; font-family:'BRLNSB'; text-transform: uppercase;padding-top: 5px; margin:0; -webkit-text-stroke: 1px #000; text-stroke: 1px #000;">Central Academy</div></td>
              </tr>
              <tr> <td><p style="font-family:'Roboto-Bold'; font-weight: bold; text-align: right; padding-left: 5px; padding-right: 15px; padding-top:8px; color: #fff; font-size: 12px;">Sr.Sec.School,Vijay Nagar, Jabalpur (M.P.)</p></td></tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

   <tr>
    <td width="100%" valign="top" align="center">
      <table width="100%" align="center">
        <tr>
          <td valign="top" align="center" style="width:100px; height:90px; overflow:hidden;">  
            <img src="<?php echo base_url().$student->image; ?>"  width="70" height="90" style="border-radius:10px; border:2px solid #e67014; margin-top:10px;margin-left:-8px;">
          </td>
          <td valign="top">
            <table align="center" width="98%" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right; line-height: 11px; padding-right:2px;">Student Name</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 11px"><?php echo strtoupper($student->firstname . " " . $student->lastname); ?></td>
              </tr>
              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right;line-height:11px; padding-right:2px;">Father/Guardian</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 11px"><?php echo $student->father_name; ?></td>
              </tr>

              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right;line-height: 11px; padding-right:2px;">Date of Birth</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 11px"><?php echo date('d-m-Y',strtotime($student->dob)) ; ?></td>
              </tr>

              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right;line-height: 11px; padding-right:2px;">Class</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 11px"><?php echo $student->class . ' - ' . $student->section ; ?></td>
              </tr>

              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right;line-height: 11px; padding-right:2px;">Address</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 11px; padding-right:7px;"><?php 
                
                echo $add = strtolower($student->current_address); 
                //echo word_limiter($add,8);
                ?></td>
              </tr>

              <tr>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium'; text-align: right;line-height: 12px; padding-right:2px;">Mobile No.</td>
                <td valign="top" style="padding-right: 1px; color: #000; padding-left: 3px;line-height: 11px">:</td>
                <td valign="top" style="font-size: 10px; color: #000;font-family:'Roboto-Medium';line-height: 12px"><?php echo $student->mobileno; ?></td>
              </tr>
             
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

      <tr>
        <td width="100%" valign="top" align="center">
          <table width="100%" align="center">
            <tr>
               <td valign="top" align="left" style="font-family:'Roboto-Medium'; text-transform: uppercase; color: #000; font-size: 10px; padding-left: 11px;">Identity card</td>
          
          <td align="right"><img src="<?php echo base_url('icard/sign2.jpg')?>" width="60" height="20" style="margin-top:-5px;"></td>
          <td><p style="font-family:'Roboto-Bold'; text-align: left; color: #000; font-size: 12px; padding-left: 2px; margin-top:-5px;">Principal</p></td>
            </tr>
          </table>
        </td>  
         
      </tr>

   

      </table>
    </td>
  </tr>

   <tr>
    <td width="100%" valign="top" align="center">
      <table width="98%" align="center">
        <tr>
          <td valign="top" align="center"><p style="font-family:'Roboto-Bold'; color: #fff; font-size: 10px; padding-top: 13px;">Having I.D.Card Is Mandatory</p></td>
          <td valign="top">
            <table align="right">
              <tr> <td><p style="font-family:'Roboto-Bold'; text-align: right; padding-top: 10px; padding-right: 15px; color: #fff; font-size: 10px;"><?php echo  $school['current_session']['session']  ;?></p></td></tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>
</div>
</body>
</html>
