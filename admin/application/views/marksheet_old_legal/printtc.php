<!DOCTYPE html>
<html lang="en">
<head>

    <title>Transfer Certificate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body style="color: #413e9a;margin: 0px;padding: 0px;display: inline-block;width: 21cm;height: 29.7cm;">
<div style=" background: url(<?php echo base_url('uploads/background-img.png') ?> ) no-repeat center center;height: 100vh; width: 100%;">
<table cellpadding="0" cellspacing="0" width="100%" style="display: inline-block">
    <tbody>
    <tr>
        <td><img src="<?php echo base_url('uploads/logo2.jpg')?>" alt="Central Academy" style="height: 100px"></td>
        <td style="width: 100%;text-align: center;">
            <div style="font-weight:bold;font-size: 33px;text-transform: uppercase">Central Academy </div>
            <div style="font-size: 15px;margin: 5px 0px;font-weight: 600">Senior Sec. School, Vijay Nagar, Jabalpur  </div>
            <div style="font-size: 15px;margin: 3px 0px;font-weight: 600"> (Affiliated to CBSE, New Delhi) </div>
            <div style="font-size: 22px;font-weight:600"> Transfer Certificate </div>
        </td>
    </tr>
    </tbody>
</table>



<table style="width: 100%">
    <tbody>
    <tr style="font-size:15px;font-weight: 600;line-height: 22px;">
        <td> <div> Affiliation No.: 1030293 </div>  </td>
        <td style="float: right">  School Code No. : 14151 </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -5px;">Book No.  &nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>

        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -5px;">SI. No. &nbsp;. </td>
                </tr>
                </tbody></table>
        </td>

        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -5px;">Application No. &nbsp; </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--FORM STARTS HERE-->

<!--1-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">1.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Name of Pupil  &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $student['firstname'] ."  ". $student['lastname']; ?></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--2-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">2.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Mother's Name&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $student['mother_name'] ; ?></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--3-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">3.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Father' Name&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $student['father_name'] ; ?> </td>
                     <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Guardian's Name&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $student['guardian_name'] ; ?> </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--4-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">4.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Nationality &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Indian </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--5-->
<table  cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">5.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Whether the candidate belongs to Schedule Caste or Schedule Tribe  &nbsp;:</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--6-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">6.</td>
        <td>   <table width="100%">
            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Date of First Admission in the school with class &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $student['admission_date']. "  " . $student['class']; ?>  </td>
            </tr>
            </tbody>
        </table></td>
    </tr>
    </tbody>
</table>

<!--7-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
        <tr>
        <td style="width: 20px;text-align: right">7.</td>
        <td>
            <table width="100%">
            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Date of Birth(In Christian Era)According to admission register (in figure) &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $student['dob']; ?>  </td>
            </tr>
            </tbody>
        </table>

        </td>
    </tr>
        <tr>
            <td style="width: 20px;text-align: right"> </td>
            <td>
                <table width="100%">
                    <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                        <td style="background: #fff;display: inline-block;margin-bottom: -1px;">(In words) &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $student['dob'] ; ?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

    </tbody>
</table>

<!--8-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right"> 8.</td>
        <td> <table width="100%">
            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Class in which the pupil last studied (In figure) &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $student['class']; ?></td>
            </tr>
            </tbody>
        </table> </td>
    </tr>
    </tbody>
</table>

<!--9-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right"> 9.</td>
        <td><table width="100%">
            <tbody><tr style="display:block;margin: 5px 0px;">
                <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> School/Board's Annual Examination last taken with result's  &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CBSE, NEW DELHI</td>
            </tr>
            </tbody>
        </table> </td>
    </tr>
    </tbody>
</table>

<!--10-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right"> 10.</td>
        <td><table width="100%">
            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Whether failed, if so once/twice in the same class&nbsp;:</td>
            </tr>
            </tbody>
        </table></td>
    </tr>
    </tbody>
</table>

<!--11-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;padding-top: 8px">11.</td>
        <td>
            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td>
                        <table width="100%">
                            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                                <td style="background: #fff;display: inline-block;margin-bottom: -5px;">Subjects Studies &nbsp; 1. &nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                                <td style="background: #fff;display: inline-block;margin-bottom: -5px;">2. &nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                                <td style="background: #fff;display: inline-block;margin-bottom: -5px;">3. &nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                                <td style="background: #fff;display: inline-block;margin-bottom: -5px;">4. &nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                                <td style="background: #fff;display: inline-block;margin-bottom: -5px;">5. &nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--12-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
        <tr>
            <td style="width: 20px;text-align: right">12.</td>
            <td>
                <table width="100%">
                    <tbody>
                    <tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                        <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Whether qualified  for promotion to the higher class &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
        <td style="width: 20px;text-align: right"> </td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> if so, to which class(in figures) &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--13-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right"> 13.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Month upto which the (pupil has paid) School dues/paid &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
             </table>
        </td>
    </tr>
    </tbody>
</table>

<!--14-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  14.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Total number of working days &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--15-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  15.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Total number of working days presents &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--16-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  16.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin-bottom: 15px">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Whether NCC Cadet/Boy Scout/Girl Guide (details may be given) &nbsp;:</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right"> </td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">   &nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--17-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  17.</td>
        <td>Game played or extra-curricular activities in which the pupil usually took part&nbsp;</td>
    </tr>

    <tr>
        <td style="width: 20px;text-align: right"> </td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">  (mention achivement level therein) &nbsp;:</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--18-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  18.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">General Conduct &nbsp;:</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--19-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  19.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Date of application for certificate &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--20-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  20.</td>
        <td>
            <table width="100%">
                <tbody><tr style="display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;">Date of issue of certificate &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date('d-m-Y'); ?> </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--21-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  21.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Reason for leaving the school  &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--22-->
<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="width: 20px;text-align: right">  21.</td>
        <td>
            <table width="100%">
                <tbody><tr style="border-bottom: 1px dotted #05059a;display:block;margin: 5px 0px;">
                    <td style="background: #fff;display: inline-block;margin-bottom: -1px;"> Any other remarks  &nbsp;:</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<!--SIGNATURE-->
<table style="width: 100%;font-size: 18px;font-weight: bold;margin-top: 20px" >
    <tbody>
        <tr>
            <td> Signature of class teacher</td>
            <td>Checked by (State full name & designation) </td>
            <td> Pricipal Seal</td>
        </tr>
    </tbody>
</table>
</div>

</body>
</html>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>
    $( document ).ready(function() {
      window.print();
    });
    </script>