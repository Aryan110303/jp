
<div class="content-wrapper">
<section class="content-header">
        <h1>
            Receiver Read         
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- ******************/master header end ****************** -->
                      
                        
                        <table class="table table-striped">
                            <tr><td>Student Name</td><td><?php echo $res->student_name;?></td></tr>
                             <tr><td>Class</td><td><?php echo $res->class;?></td></tr>
                              <tr><td>Section</td><td><?php echo $res->section;?></td></tr>
                               <tr><td>Mother's Name </td><td><?php echo $res->mother_name;?></td></tr>
                                <tr><td>Father's Name</td><td><?php echo $res->father_name;?></td></tr>
                             <tr><td>Receiver Name</td><td><?php echo $res->receiver_name;?></td></tr>
                              <tr><td>Relation With Child</td><td><?php echo $res->relation;?></td></tr>
                               <tr><td>Mobile No</td><td><?php echo $res->mobile_no;?></td></tr>
                               <tr><td>Reason</td><td><?php echo $res->reason;?></td></tr>
                         
                               <tr><td>Image</td><td><img src="<?php echo base_url($res->image) ;?>" height="100" width="100"></td></tr> 
                              <!--<tr><td>Status</td><td><?php if($res->status==1){echo 'Active';}else{echo 'Inactive';}?></td></tr>-->
                        </table>
                        
                        <?php echo anchor(base_url('receive'),'Cancel' ,'class="btn btn-primary"');?>
                          <!-- ******************/master footer ****************** -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
