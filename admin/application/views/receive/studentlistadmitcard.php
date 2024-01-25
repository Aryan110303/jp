<div class="content-wrapper" style="min-height: 946px;">
      <section class="content-header">
        <h1>Exam List       
            <small><a href="<?php base_url();?>" >Home</a></small>
        </h1>
    </section>

    <style type="text/css">
        input {
              border: 0;
              }
            @media print {
              .no-print{
                 display: none;
                }}
    </style>
    <!-- Main content -->
 <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- ******************/master header end ****************** -->
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4 text-center">
                                <div style="margin-top: 4px"  id="message">
                                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                </div>
                            </div>
                          <!--   <div class="col-md-4 text-right">
                                <?php //echo anchor(site_url('receive/create'), 'Create', 'class="btn btn-primary"'); ?>
                            </div> -->
                           </div>
                        <div class="download_label"><?php echo $this->lang->line('exam_list'); ?></div>
                           <!--  <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Name</th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                
                                    if (!empty($student)) {
                                       foreach ($student as $key => $value) { ?>
                                         <tr>
                                            <td><?php echo $value['firstname'].'  '. $value['lastname']?></td> 
                                           <td>
                                             <a href="#myModal">  Admit card</a>
                                           </td>                                         </tr>   
                                    <?php } }?>
                                </tbody>
                            </table> -->

                         <div class="row no-print"> 
                            <div class="col-md-3">
                           <label class="form-group" >Select Student Name</label> 
                           <select class="form-control" name="student" id="student">
                            <option value="0">Select Student </option>
                             <?php foreach ($student as $key => $value) {
                               
                                ?>
                                <option value="<?php echo $value['id'] ;?>"><?php echo $value['firstname'].' ' .$value['firstname'] ;  ?></option>

                            <?php }?>
                            </select>
                            </div>
                         </div>
 <br><br>
                         <div class="row">
                            <div class="col-md-8">
                                
<div style=" background: url(<?php echo base_url('uploads/background-img.png')?>) no-repeat center center;height: 100%; width: 100%;">
    <div style=";text-align: center;border-bottom: 1px solid #000">
        <div><img src="<?php echo base_url('uploads/logo2.jpg')?>" alt="logo" style="width:80px"></div>
        <div style="margin: 8px 0px;font-size: 20px;font-weight: 600;color: #043368;">CENTRAL ACADEMY SR. SEC. SCHOOL</div>
        <div style="font-size: 17px;"> MATHURA VIHAR, VIJAY NAGAR <br> JABALPUR (M.P.)</div>
        <div style="margin: 8px 0px;font-size: 20px;font-weight: 600;color: #043368;"> ADMIT CARD </div>
    </div>


    <div style="padding: 10px 12px">
        <div style="text-align: center;font-size:20px;font-weight: bold;margin: 15px 0px;"> PERSONAL INFORMATION</div>
                            
        <table style="width: 100%">
            <tbody>
            <tr>
                <td>
                    <table style="text-transform: uppercase">
                        <tbody>
                     
                        <tr><td style="font-weight: bold"> Schlor No. &nbsp;</td><td> <input readonly="true" type="text" name="scholarno" id="scholarno"> </td></tr>
                        <tr><td style="font-weight: bold"> Roll No. &nbsp;</td><td> <input readonly="true" type="text" name="roll" id="roll">   </td></tr>
                        <tr><td style="font-weight: bold"> Name &nbsp;</td><td> <input readonly="true" type="text" name="name" id="name">   </td></tr>
                        <tr><td style="font-weight: bold">Mother's  Name &nbsp;</td><td>  <input readonly="true" type="text" name="mothername" id="mothername"> </td></tr>
                        <tr><td style="font-weight: bold"> Class  : &nbsp; </td><td>  <input readonly="true" type="text" name="class" id="class">  </td></tr>
                        <tr><td style="font-weight: bold">  Section:&nbsp; </td><td> <input readonly="true" type="text" name="Section" id="section">   </td></tr>

                  
                        </tbody>
                    </table>
                </td>

                <td style="float: right">
                    <table>
                        <tbody>
                        <tr>
                            <td style="width: 100px;text-align: center;border: 1px solid #464646;height: 130px;"> <img id="image" width="90px"  height="120px" alt="" ></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    <br><br>
<div class="row">  
    <table cellpadding="8" style="border-collapse: collapse;text-align: center;width:100%"border="1" >
            <thead>
                <tr><th class="text-center" >
                    Sno
                   </th>
                   <th class="text-center">
                    Subject
                   </th>
                   <th class="text-center">
                    Date
                   </th>
            </tr>
            </thead>
            <tbody>

           <?php if (!empty($result)) {
    $i = 1 ;
             foreach ($result as $key => $value) { ?>
              <tr>
                <td><?php echo $i++ ; ?></td>
                  <td><?php echo $value['name']?></td>
                   <td><?php echo $value['date_of_exam']?></td>
              </tr>
         <?php   }
           }  ?>

            
            </tbody>


        </table>
 <div style="font-size: 12px;margin-top:30px;bottom: 25px;right: 10px"> EXAMINATION CONTROLLER’S SIGN</div></div>
       



    </div>
   <button class="no-print btn btn-primary" onclick="window.print();">Print</button>
</div>

                            </div>
                             
                         </div>

                        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
                        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
                        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#mytable").dataTable();
                            });
                        </script>
                        <!-- ******************/master footer ****************** -->
                    </div>
                </div>
            </div>
    </section>
</div>

<script>

    $("#student" ).change(function() {
      var id = $('#student').val()  ;
  

      $.ajax({
      type: 'POST',
      url: "<?php echo base_url('receive/studentdetails'); ?> ",
      dataType : "JSON",
        data : {id:id},
      success: function(resultData) 
      { 
       //  alert(resultData);
     //   var obj = JSON.parse(resultData) ;
      //  alert(resultData);
            $('#roll').val(resultData.roll_no);
            $('#mothername').val(resultData.mother_name);
              var  firstname = resultData.firstname;
              var lastname = resultData.lastname;
              $('#name').val(firstname +' '+ lastname) ;
             $('#section').val(resultData.section);
             $('#class').val(resultData.class);
             $("#image").attr("src","<?php echo base_url()?>"+ resultData.image);
      }

    });
});

</script>