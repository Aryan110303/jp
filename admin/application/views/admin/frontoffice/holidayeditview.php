<div class="content-wrapper" style="min-height: 348px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?> </section>
    <section class="content">       
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('visitor_book', 'can_add') || $this->rbac->hasPrivilege('visitor_book', 'can_edit')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit'); ?> Holidays</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/holidays/edit/' . $holiday_data['id']) ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >
                            <div class="box-body">
                                <?php echo $this->session->flashdata('msg') ?>
                               
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('name'); ?></label> <small class="req"> *</small> 
                                    <input type="text" class="form-control" value="<?php echo set_value('name', $holiday_data['name']); ?>" name="name">
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input type="text" id="date" class="form-control" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($holiday_data['date']))); ?>"  name="date" readonly="">
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                
                                
                             
                            </div><!-- /.box-body -->


                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('visitor_book', 'can_add') || $this->rbac->hasPrivilege('visitor_book', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Holidays <?php echo $this->lang->line('list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                   
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                      
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($holiday_list)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($holiday_list as $key => $value) {
                                            //print_r($value);
                                            ?>
                                            <tr>
                                            
                                                <td class="mailbox-name"><?php echo $value['name']; ?></td>
                                             
                                                <td class="mailbox-name"> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                    
                                                <td>
                                                    <a href="<?php echo base_url(); ?>admin/holidays/edit/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                
                                                        <a href="<?php echo base_url(); ?>admin/holidays/delete/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                   <?php } ?>
                                                </td>


                                            </tr>
                                            <?php
                                
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script type="text/javascript">

                                                $(function () {

                                                    $(".timepicker").timepicker({
                                                        // showInputs: false,
                                                        // defaultTime: false,
                                                        // explicitMode: false,
                                                        // minuteStep: 1
                                                    });
                                                });
                                                $(document).ready(function () {
                                                    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

                                                    $('#date').datepicker({
                                                        //  format: "dd-mm-yyyy",
                                                        format: date_format,
                                                        autoclose: true
                                                    });



                                                });

                                                function getRecord(id) {
                                                    // alert(id);
                                                    $.ajax({
                                                        url: '<?php echo base_url(); ?>admin/visitors/details/' + id,
                                                        success: function (result) {
                                                            //alert(result);
                                                            $('#getdetails').html(result);
                                                        }


                                                    });

                                                }


</script>
