
<div class="content-wrapper">
<section class="content-header">
        <h1>
            Receiver Read         
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content ">
        <div class="row">
            <div class="col-xs-12">
                        <div class="box box-primary ">
                    <!-- /.box-header -->
                    <div class="box-body" >
                
                 
                        <!-- ******************/master header end ****************** -->
                        

<!-- <body style="border: 1px solid #868686;padding: 18px 3px; width: 21cm;height: 29.7cm;font-family: sans-serif;margin: 0px;color: #000;font-size: 13px;" > -->

    <div style="display: inline-block;width: 100%" >
            <div style="text-align: center;">
                <div><img src="<?php echo base_url('uploads/logo2.jpg') ;?>" alt="logo" style="width:80px"></div>
                <div style="margin: 8px 0px;font-size: 20px;font-weight: 600;color: #043368;">CENTRAL ACADEMY SCHOOL (10+2)</div>
                <div style="font-size: 17px;"> MATHURA VIHAR, VIJAY NAGAR <br> JABALPUR (M.P.)</div>
                <div > GATE PASS </div>
            </div>

            <!--PHOTO-->
            <table style="margin: 12px 0px;width: 100%" class="table table-responsive">
                <tbody>
                    <tr>
                       <td><div class="row">
                          <span style="width: 50%; text-align: center; float: left;" >
                              
                            <img width="90px ;" height="110px;"  src="<?php if($res->student_image) echo base_url($res->student_image) ;
                         else echo base_url('uploads/fatheravtar.png') ; ?>"  alt="receiver pic">  
                           </span> 

                           <span style="width: 50%; text-align: center ; float: left">                                 

                   <img   width="90px ;" height="110px;" src="<?php if($res->image) echo base_url($res->image) ;
                                                               else  echo base_url('uploads/studentavtar.png') ;?>"  alt="student pic">  
                            </span>
                          
                      
                            
                          
                       </div>
                   </td>
                    </tr>
                </tbody>
            </table>

     
<div class="row" >
                          
      <div style=" text-transform: uppercase; ">
     <ul style="list-style: none; padding-top: 20px;padding-left: 20px; margin-left: 30%;  ">
    
 <!--    <li style="margin:4px; " ><b>Schlor No. -- </b> demo</li> -->
     <li style="margin:4px; "><b>Class -- </b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $res->class;?></li>
   <li style="margin:4px; "><b>Section -- </b>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $res->section;?></li>
   <li style="margin:4px;"><b>Student's Name -- </b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $res->student_name;?></li>
   <li style="margin:4px;"><b>Father's  Name -- </b>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $res->father_name;?></li>
   <li style="margin:4px;"><b>Mother's Name -- </b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $res->mother_name;?> </li>
   <li style="margin:4px;"><b>Relation with warden -- </b>  &nbsp; &nbsp;<?php echo $res->relation;?> </li>
   <li style="margin:4px;"><b>REASON -- </b> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $res->reason;?> </li>
   <li style="margin:4px;"><b>DATE & TIME -- </b>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $res->datetime;?></li>


</ul> 
  </div>              

<!-- <table>
       <tr ><td style="font-weight: bold"> Schlor No. &nbsp;</td><td>  </td></tr>
              <tr><td  style="font-weight: bold">Class</td><td><?php echo $res->class;?></td></tr>
              <tr><td  style="font-weight: bold">Section</td><td></td></tr>
              <tr><td style="font-weight: bold"> Student's Name &nbsp;</td><td><?php echo $res->student_name;?>  </td></tr> 
            <tr><td style="font-weight: bold">Father's  Name &nbsp;</td><td><?php echo $res->father_name;?> </td></tr>
            <tr><td style="font-weight: bold">Mother's Name &nbsp;</td><td><?php echo $res->mother_name;?> </td></tr>
            <tr><td style="font-weight: bold">Relation with warden &nbsp;</td><td><?php echo $res->relation;?>  </td></tr>
            <tr><td style="font-weight: bold"> REASON : &nbsp; </td><td><?php echo $res->reason;?>  </td></tr>
            <tr><td style="font-weight: bold">   DATE & TIME :&nbsp; </td><td>   <?php echo $res->datetime;?></td></tr>
                          
</table> -->
       </div>
 <div style="padding-top: 100px; padding-left: 40px;">EMPLOYEE’S  SIGNPARENT/GUARDIAN'S SIGN</div>
          
    </div>

                        
                 
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<script>
    $(document).ready(function(){
   
  window.print();
});

</script>