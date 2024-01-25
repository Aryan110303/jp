<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->


<!--- Add Template Modal Start -->
<div class="modal fade" id="myModaladd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> Add Template</h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formadd" method="post" class="ptt10" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Name</label>
                                        <input type="text" name="addtempName" id="addtempName" class="form-control" placeholder="Enter Termplate Name">
                                    </div> 
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Message</label>
                                        <!-- <input type="text" name="addtempMsg" id="addtempMsg" class="form-control" placeholder="Enter Termplate Message"> -->
                                        <textarea name="addtempMsg" id="addtempMsg" class="form-control" placeholder="Enter Termplate Message" rows="5"></textarea>
                                    </div> 
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Status</label>
                                        <select class="form-control" id="addtempStatus" name="addtempStatus">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="0">De-active</option>
                                        </select>
                                    </div> 
                                </div>

                            </div><!--./row-->    

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">

                <div class="pull-right paddA10">

                    <button onclick="addTemplate()" type="button" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    <!-- <input type="submit" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" /> -->
                </div>
            </div>
        </div>
        </form>
    </div>
</div>    
</div>
<!-- Add Template Modal End -->

<!--- Edit template Model Start -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> Edit Template</h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formedit" method="post" class="ptt10" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="tempId" id="tempId">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Name</label>
                                        <input type="text" name="tempName" id="tempName" class="form-control">
                                    </div> 
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Message</label>
                                        <input type="text" name="tempMsg" id="tempMsg" class="form-control">
                                    </div> 
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Template Status</label>
                                        <select class="form-control" id="tempStatus" name="tempStatus">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="0">De-active</option>
                                        </select>
                                    </div> 
                                </div>

                            </div><!--./row-->    

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">

                <div class="pull-right paddA10">

                    <button onclick="editTemplate()" type="button" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    <!-- <input type="submit" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" /> -->
                </div>
            </div>
        </div>
        </form>
    </div>
</div>    
</div>
<!-- Edit template Model End -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> Templates</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Sms List</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModaladd" ><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">


                            <div class="download_label"><?php echo $this->lang->line('book_list'); ?></div>
                            <table id="" class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Template Title</th>
                                        <th>Status</th>
                                        <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($smslist as $sms) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $sms->template_name ?></a>
                                            </td>
                                            <td class="mailbox-name"> <?php echo $sms->template_msg ?></td>
                                            <?php if ($sms->template_status == 1) {
                                                $status = "Active";
                                            }else{
                                                $status = "Not Active";
                                            } ?>
                                            <td class="mailbox-name"> <?php echo $status ?></td>
                                            
                                            <td class="mailbox-date no-print text text-right">
                                                <?php if ($this->rbac->hasPrivilege('templates', 'can_edit')) { ?> 
                                                    <a onclick="getRecord(<?php echo $sms->template_id; ?>)" class="btn btn-default btn-xs" data-toggle="modal" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing" data-original-title="View">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php }if ($this->rbac->hasPrivilege('templates', 'can_delete')) { ?> 
                                                    <a href="<?php echo base_url(); ?>admin/smslist/delete/<?php echo $sms->template_id ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_template(<?php echo $sms->template_id; ?>)">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <!-- general form elements disabled -->
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#postdate').datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>

<script type="text/javascript">
    function getRecord(id) {
        $.ajax({
            url: "<?php echo site_url("admin/smslist/getTemplateMsg/") ?>" + id,
            type: "POST",
            dataType: 'json',

            success: function (res)
            {
                $('#myModaledit').modal('show');
                $("#tempId").val(res.template_id);
                $("#tempName").val(res.template_name);
                $("#tempMsg").val(res.template_msg);
                $("#tempStatus").val(res.template_status);
            }
        });

    }

    function editTemplate()
    {
 
       // ajax adding data to database
          $.ajax({
            url : "<?php echo site_url("admin/smslist/updateTemplate/1") ?>",
            type: "POST",
            data: $('#formedit').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
               $('#myModaledit').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }

    function addTemplate() {
        
        $('#myModaladd').modal('show');
        // ajax adding data to database
          $.ajax({
            url : "<?php echo site_url("admin/smslist/addTemplate/1") ?>",
            type: "POST",
            data: $('#formadd').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
              $('#myModaladd').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }


    function delete_template(id)
    {
      if(confirm('Are you sure delete this data?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('admin/smslist/deleteTemplate/')?>"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
              
               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
 
      }
  }
</script>


<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }


    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });


    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>