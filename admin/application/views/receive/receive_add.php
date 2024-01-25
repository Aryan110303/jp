<div class="content-wrapper">
    <!-- Content Header (Page header) -->


<style type="text/css">
#results { padding:10px; border:1px solid; background:#ccc; }
</style>

    <section class="content-header">
        <h1>
            Receiver Form    <?php echo $button ?>          
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- ******************/master header end ****************** --> 
                        <label>Enter Admisssion No.</label>
                        <input type="text" class="form-control" name="enroll" id="enroll" placeholder="Enter Enroll No">

                        <br>  <br>
                        <form action="<?php echo $action ?>" method="post" >
                              <input type="hidden" class="form-control "name="student_id" id="student_id"   >
                              <input type="hidden" class="form-control "name="parent_id" id="parent_id"   >
                              <input type="hidden" class="form-control "name="student_image" id="student_image"   >
                              <input type="hidden" class="form-control "name="guardian_image" id="guardian_image"   >
                            <?php echo validation_errors(); ?>
                            <div class="<?php if (form_error('student_name')) echo 'has-error'; ?>">
                                <label>Student Name</label>
                                <input type="text" class="form-control "name="student_name" id="student_name" readonly="" >
                                <span class="text text-danger"> <?php echo form_error('student_name'); ?></span>
                            </div>

                            <div class="<?php if (form_error('class_id')) echo 'has-error'; ?>">
                                <label>Class</label>

                                <input type="text" class="form-control "name="class_id" id="class_id" readonly=""  >
                                <span class="text text-danger"> <?php echo form_error('class_id'); ?></span>
                            </div>
                               <div class="<?php if (form_error('section_id')) echo 'has-error'; ?>">
                                <label>Section</label>
                                <input type="text" class="form-control "name="section_id" id="section_id" readonly=""  >
                                <span class="text text-danger"> <?php echo form_error('section_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('father_id')) echo 'has-error'; ?>">
                                <label>Father Name</label>
                                <input type="text" class="form-control "name="father_id" id="father_id" readonly=""  >
                                <span class="text text-danger"> <?php echo form_error('father_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('mother_id')) echo 'has-error'; ?>">
                                <label>Mother Name</label>
                                <input type="text" class="form-control "name="mother_id" id="mother_id" readonly="" >
                                <span class="text text-danger"> <?php echo form_error('mother_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('receiver_name')) echo 'has-error'; ?>">
                                <label >Receiver Name</label>
                                <input type="text" class="form-control " required="required" name="receiver_name" id="receiver_name">
                                <span class="text text-danger"> <?php echo form_error('receiver_name'); ?></span>
                            </div>
                            <div class="<?php if (form_error('relation')) echo 'has-error'; ?>">
                                <label >Relation With Child</label>
                                <input type="text" class="form-control " required="required"  name="relation" id="relation" value="<?php echo $relation; ?>">
                                <span class="text text-danger"> <?php echo form_error('relation'); ?></span>
                            </div>
                            <div class="<?php if (form_error('namobile_noe')) echo 'has-error'; ?>">
                                <label >Mobile No   </label>
                                <input type="text" class="form-control "name="mobile_no" required="required" id="mobile_no" value="<?php echo $mobile_no; ?>">
                                <span class="text text-danger"> <?php echo form_error('mobile_no'); ?></span>
                            </div>


                            <div class="<?php if (form_error('reason')) echo 'has-error'; ?>">
                                <label >Reason</label>
                                <input type="text" class="form-control "name="reason" required="required" id="name" value="<?php echo $reason; ?>">
                                <span class="text text-danger"> <?php echo form_error('reason'); ?></span>
                            </div>
                            <!--************************************************************************************************************************-->                         
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="my_camera"></div>
                                    <br/>
                                    <input type=button value="Take Snapshot" onClick="take_snapshot()">
                                    <input type="hidden" name="image" class="image-tag">
                                </div>

                                <div class="col-md-6">
                                    <div id="results">Your captured image will appear here...</div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <br/>
                                    <input type="hidden" name='id' value="<?php echo $id ?>">


                                </div>
                            </div>


                            <!--*****************************************************************************************************************************-->                       
                            <div class="form-group">
                                <input type="submit"  class="btn btn-primary " name="btn1" id="btn" value="<?php echo $button ?>" onclick="return  Validate()"/> 
                                <a href="<?php echo base_url('receive') ?>"class="btn btn-primary">Cancel</a>
                            </div>

                        </form>
                        <!--********************************************************************************************-->                       
                     
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> -->

<!-- 
<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script> -->
                       

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    Webcam.set({
        width: 490,
        height: 390,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

    function take_snapshot() {
//        alert('hello');
        Webcam.snap(function (data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
        });
    }
</script>
<script>
    $("#enroll").keyup(function(){
    var number = $('#enroll').val();


        $.ajax({
          type: "POST",
           url: '<?php echo base_url('Receive/getdetails'); ?>',
           data: {'number':number},  
          success: function(data) {          
           if(data) {
           var result = $.parseJSON(data) ;
            $('#student_name').val(result.firstname+''+result.lastname);
             $('#class_id').val(result.class);
               $('#section_id').val(result.section);
            $('#mother_id').val(result.mother_name);
            $('#father_id').val(result.father_name);
            $('#student_id').val(result.id);
            $('#parent_id').val(result.parent_id);
            $('#student_image').val(result.image);
            $('#guardian_image').val(result.guardian_pic);
            
         }
      },
          
});
   
});
</script>



