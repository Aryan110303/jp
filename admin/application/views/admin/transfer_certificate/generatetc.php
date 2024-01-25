<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>

    </style>
</head>
<body style="margin: 0px;padding: 0px">
<div style=" background: url(<?php echo base_url('backend/images/lk-logo.png') ?>) no-repeat center center;height: 100vh; width: 100%;">
    <div style="font-size: 12px;">
    <div style="margin-bottom:0px;"><img src="<?php echo base_url('backend/images/headimg2.png') ?>" style="height: 80px;width: 100%;"></div>

<h4 style="text-align: center;margin: 0px;">TRANSFER CERTIFICATE</h4>
<P><b>S.NO.</b>&nbsp;&nbsp;&nbsp;0001&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Admission no.</b>&nbsp;&nbsp;&nbsp;<?php echo $result[0]['admission_no']; ?></P>

<p><b>1.&nbsp;&nbsp;&nbsp;Name of Pupil</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span><?php echo $result[0]['firstname']; ?> <?php echo $result[0]['lastname']; ?></span></p>

    <p><b>2.&nbsp;&nbsp;&nbsp;Mother's Name</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo $result[0]['mother_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    3.&nbsp;&nbsp;&nbsp;<b>Father's Name</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo $result[0]['father_name']; ?>
    </p>

    <p><b>4.&nbsp;&nbsp;&nbsp;Date of Birth(in Christian Era)according to Admission & Withdraw Register(in figures)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span><?php echo date('d-m-Y',strtotime($result[0]['dob'])); ?></span></p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(in words)</b>&nbsp;:&nbsp;&nbsp;<?php echo $dobinword ?></p>
    <P><b>5.&nbsp;&nbsp;&nbsp;Nationality</b>&nbsp;:&nbsp;&nbsp;Indian</P>
    <P><b>6.&nbsp;&nbsp;&nbsp;Whether the candidate belongs to Schedule Caste or Schedule Tribe or O.B.C.</b>&nbsp;&nbsp;<?php echo $result[0]['cast']; ?></P>
    <P><b>7.&nbsp;&nbsp;&nbsp;Date of first admission in the school</b>&nbsp;&nbsp;:&nbsp;<?php echo date('d-m-Y',strtotime($result[0]['register_date'])); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Class</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo $result[0]['class']; ?></P>

    <P><b>8.&nbsp;&nbsp;&nbsp;Class in which the pupil last studied (in figures)</b>&nbsp;&nbsp;:&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>(In Words)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA
    </P>

    <p><b>9.&nbsp;&nbsp;&nbsp;School/Board Annual examination&nbsp;&nbsp;&nbsp;</b></p>
    <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Last taken with result</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>10. &nbsp;&nbsp;&nbsp;Whether failed, if so once/twice in the same class</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>11.&nbsp;&nbsp;&nbsp;Subjects studied</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo rtrim($result[0]['subject_name'],',') ?><br>
        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</P>

    <p><b>12.&nbsp;&nbsp;&nbsp;&nbsp;Whether qualified for promotion to the higher class</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If so, to which class (in figure)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(In Words)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>

    <p><b>13.&nbsp;&nbsp;&nbsp;Month upto which the pupil has paid school dues</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>14.&nbsp;&nbsp;&nbsp;Any fee concession availed of, if so, the nature of such concession</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>15.&nbsp;&nbsp;&nbsp;Total number of working days in academic session</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>16.&nbsp;&nbsp;&nbsp;Total number of working days pupil present in the school</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>17.&nbsp;&nbsp;&nbsp;Whether NCC Cadet/Boy/Scout/Girl Guide (details may be given)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>18.&nbsp;&nbsp;&nbsp;Games played or extra curricular activities in which the pupil usually took part</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</p>
    <p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mention achievement level therein)</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p><b>19.&nbsp;&nbsp;&nbsp;General conduct</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;GOOD</p>
    <p><b>20.&nbsp;&nbsp;&nbsp;Date of application for certificate</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo date('d-m-Y',strtotime($result[0]['generateDate'])) ?></p>
    <p><b>21.&nbsp;&nbsp;&nbsp;Date of issue of certificate</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo date('d-m-Y',strtotime($result[0]['generateDate'])) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;.............................</p>
    <p><b>22.&nbsp;&nbsp;&nbsp;Reason for leaving the school</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo $result[0]['leaving_reason'] ?></p>
    <p><b>23.&nbsp;&nbsp;&nbsp;Any other remarks</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;NA</p>
    <p>
        <b>Signature of Class Teacher</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Checked by </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Principal</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </p>
    <p style="text-align: center"><b>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SWETA ASATHI</p>
    <p style="text-align: center"><b>Designation</b>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;OFFICE ASSISTANT</p>
</div>
</div>
</body>
</html>