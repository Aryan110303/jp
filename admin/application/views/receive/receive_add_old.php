
<head>
    <title>Capture webcam image with php and jquery - ItSolutionStuff.com</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style type="text/css">
        #results { padding:20px; border:1px solid; background:#ccc; }
    </style>
</head>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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

                        <label>Enter Enrollment NO</label>
                        <input type="text" name="enroll" id="enroll" placeholder="Enter Enroll No">
                        <br>  <br>
                        <form action="<?php echo $action ?>" method="post" >

                            <?php echo validation_errors(); ?>
                            <div class="<?php if (form_error('child_id')) echo 'has-error'; ?>">
                                <label>Child Name</label>
                                <input type="text" class="form-control "name="child_id" id="child_id" readonly=""  value="<?php echo $child_id; ?>">
                                <span class="text text-danger"> <?php echo form_error('child_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('child_id')) echo 'has-error'; ?>">
                                <label>Class</label>
                                <input type="text" class="form-control "name="child_id" id="child_id" readonly=""  value="<?php echo $child_id; ?>">
                                <span class="text text-danger"> <?php echo form_error('child_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('child_id')) echo 'has-error'; ?>">
                                <label>Father Name</label>
                                <input type="text" class="form-control "name="child_id" id="child_id" readonly=""  value="<?php echo $child_id; ?>">
                                <span class="text text-danger"> <?php echo form_error('child_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('child_id')) echo 'has-error'; ?>">
                                <label>Mother Name</label>
                                <input type="text" class="form-control "name="child_id" id="child_id" readonly="" value="<?php echo $child_id; ?>">
                                <span class="text text-danger"> <?php echo form_error('child_id'); ?></span>
                            </div>

                            <div class="<?php if (form_error('name')) echo 'has-error'; ?>">
                                <label >Receiver Name</label>
                                <input type="text" class="form-control "name="name" id="name" value="<?php echo $name; ?>">
                                <span class="text text-danger"> <?php echo form_error('name'); ?></span>
                            </div>
                            <div class="<?php if (form_error('relation')) echo 'has-error'; ?>">
                                <label >Relation With Child</label>
                                <input type="text" class="form-control "name="relation" id="name" value="<?php echo $relation; ?>">
                                <span class="text text-danger"> <?php echo form_error('relation'); ?></span>
                            </div>
                            <div class="<?php if (form_error('namobile_noe')) echo 'has-error'; ?>">
                                <label >Mobile No   </label>
                                <input type="text" class="form-control "name="mobile_no" id="name" value="<?php echo $mobile_no; ?>">
                                <span class="text text-danger"> <?php echo form_error('mobile_no'); ?></span>
                            </div>


                            <div class="<?php if (form_error('reason')) echo 'has-error'; ?>">
                                <label >Reason</label>
                                <input type="text" class="form-control "name="reason" id="name" value="<?php echo $reason; ?>">
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
                     


                        <script src="<?php echo base_url('assets/hinglish/jsapi.js') ?>"></script>
                        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script language="JavaScript">
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








</script>
