<?php 
 $userdata= $this->session->userdata('admin');
   
 $uesrid=$userdata['id']; 
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/zoom_addon.css">
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i><?php echo $this->lang->line('live_meeting'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i><?php echo "Google Meet"; ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('live_meeting', 'can_add')) {
                                ?>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> </button>
                            <?php }
                            ?>

                        </div>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>

                        <div class="table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('live_meeting'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('meeting') . ' ' . $this->lang->line('title'); ?></th>
                                    <?php if($uesrid==1){?>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>                                     
                                        <th><?php echo $this->lang->line('teacher'); ?> </th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <!-- <th>Time</th> -->
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    <?php } else {?>
                                        <th><?php echo $this->lang->line('action'); ?></th>
                                    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($conferences)) {   ?>

                                        <?php
                                    } else {
                                        foreach ($conferences as $conference_key => $conference_value) {   ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $conference_value->topic; ?></a>

                                                    <div class="fee_detail_popover displaynone">
                                                        <?php
                                                        if ($conference_value->url == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                             <?php if($uesrid==1){?>
                                                            <p class="text text-info"><a href="<?php echo $conference_value->url; ?>"> Click Here To Redirect </a></p>
                                                             <?php } ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <?php if($uesrid==1){?>

                                                <td class="mailbox-name">
                                                    <?php echo $conference_value->class; ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo  $conference_value->section; ?>
                                                </td>

                                                <td class="mailbox-name">
                                                       <?php echo $conference_value->name.' '. $conference_value->surname; ?>     
                                                   </td>
                                                <td class="mailbox-name">
                                                   <?php echo $conference_value->name.' '. $conference_value->surname; ?>                                                     
                                               </td>

                                               <td class="mailbox-name">
                                                   <?php echo date('d-m-Y',strtotime($conference_value->class_date)); ?>                                                     
                                               </td>

                                               <!-- <td class="mailbox-name">
                                                   <?php //echo $conference_value->class_time; ?>                                                     
                                               </td> -->

                                                <td class="mailbox-date pull-right">
                                                <a data-placement="left" class="btn btn-default btn-xs" onclick="edit_class('<?php echo $conference_value->id ; ?>')"  title="<?php echo $this->lang->line('delete'); ?>"
                                                 >
                                                                <i class="fa fa-edit"></i>
                                                            </a> |
                                                    
                                                            <a data-placement="left" href="<?php echo base_url(); ?>admin/conference/deletegmeet/<?php echo $conference_value->id ; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                          
                                                    
                                                </td>

                                                <?php } else {?>
                                                <td>
                                                    <a href="<?php echo $conference_value->url; ?>" class="btn btn-primary btn-sm">Start Class</a>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-online-timetable">
    <div class="modal-dialog modal-lg">
        <form id="form-addconference" action="<?php echo site_url('admin/conference/gmeet'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('add') . ' ' . "Google Meet Link" ?> </h4>
                </div>
                <div class="modal-body">
                    <div class="row">    
                    <input  type="hidden" name="meetid" id="meetid">
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label for="title"><?php echo $this->lang->line('meeting') . ' ' . $this->lang->line('subject') ?> <small class="req"> *</small></label>
                                <input type="text" class="form-control" id="title" name="topic">
                                <span class="text text-danger" id="title_error"></span>
                            </div>
                             
                    </div>
                    <div class="row">
                                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label for="duration"><?php echo "Google MeetLink "; ?> <small class="req"> *</small></label>
                                        <input type="text" class="form-control" id="url" name="url">
                                        <span class="text text-danger" id="title_error"></span>
                                 </div>
                    </div>
                    <div class="row">                     
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="label15"><?php echo $this->lang->line('staff') . ' ' . $this->lang->line('list') ?> </label>  
                                    <select name="teacher_id" id="teacher_id" class="form-control">
                                    <option value="">Select Teacher</option>
                                        <?php foreach ($staffList as $staff_key => $staff_value) {   ?>
                                            <option value="<?php echo  $staff_value->id ;?>"><?php echo  $staff_value->name.''. $staff_value->surname?></option>
                                        <?php }
                                        ?>
                                </select>
                        </div>  
                    </div>
                      <div class="row">
                         <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="label15clsss"><?php echo $this->lang->line('class');?> </label>   
                           
                                 <select name="class_id" id="class_id" class="form-control class_id">
                                 <option value="">Select Class</option>
                                    <?php foreach ($classes as $classes_key => $class) {   ?>
                                        <option value="<?php echo  $class->id ;?>"><?php echo  $class->class;?></option>
                                       <?php }
                                    ?>
                               </select>
                         </div>    
                      </div>  
                      <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label class="label15section"><?php echo $this->lang->line('sections') ; ?> </label>   
                                  
                                        <select name="section_id" id="section_id" class="form-control section_id">
                                         <option value="0">Select Section</option>
                                         
                                            <?php foreach ($sections as $section_key => $section) {   ?>
                                                <option value="<?php echo  $section->id ;?>"><?php echo  $section->section;?></option>
                                                
                                            <?php }
                                            ?>
                                        </select>
                            </div>     
                      </div>
               
                      <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                   
                                    <label for="date"><?php echo $this->lang->line('meeting') . ' ' . $this->lang->line('date') ?> <small class="req"> *</small></label>
                                    <input id="date" name="date11" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                    <span class="text text-danger" id="date_error"></span>
                            </div>     
                      </div>
               
                      <!-- <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                   
                                    <label class="label15">Time </label>  
                                    <input id="time12" name="time12" placeholder="" type="time" class="form-control"   readonly="readonly"/>
                                    <span class="text text-danger" id="time_error"></span>
                            </div>     
                      </div> -->

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>

                </div>
            </div>
        </form>
    </div>
</div>
<script>
   function edit_class(id)
    {
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/conference/getEditdata") ?>',
            data: {'id': id},
            dataType: "JSON",
            success: function (data) {
                
                $('#modal-online-timetable').modal('show');
              
              $('#meetid').val(data.id);
              $('#title').val(data.title);
              $('#url').val(data.url);
              $('#teacher_id').val(data.teacher_id);
              $('.class_id').css('display','none');
              $('.section_id').css('display','none');
              $('.label15clsss').css('display','none');
              $('.label15section').css('display','none');
              
              $('#date11').val(data.date11);
              $('#time12').val(data.time12);
            }
        });
    }
</script>
<script>
     var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
     $( function() {
    $( "#date" ).datepicker({format: date_format,  autoclose: true});
    
  } );  



</script>
<script type="text/javascript">
    (function ($) {
        "use strict";
        var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY']) ?>';
        $(document).on('change', '.chgstatus_dropdown', function () {
            $(this).parent('form.chgstatus_form').submit();
        });
      

        $("form.chgstatus_form").submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                dataType: "JSON",
                success: function (data)
                {
                    alert(data);
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }

                }
            });


        });
     

        $(document).on('click', 'a.join-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).attr('href');
            $.ajax({
                url: "<?php echo site_url("admin/conference/add_history") ?>",
                type: "POST",
                data: {"id": id},
                dataType: 'json',
                beforeSend: function () {
                },
                success: function (res)
                {
                    if (res.status == 0) {
                    } else if (res.status == 1) {
                        window.open(url, '_blank');
                    }
                },
                error: function (xhr) {
                    alert("Error occured.please try again");
                },
                complete: function () {
                }
            });
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

        $(document).on('change', '#role_id', function (e) {
            $('#staff_id').html("");
            var role_id = $(this).val();
            getEmployeeName(role_id);
        });

        $('#meeting_date').datetimepicker({
            format: datetime_format + " HH:mm",
            showTodayButton: true,
            ignoreReadonly: true
        });

        $('#modal-online-timetable').on('shown.bs.modal', function (e) {
            $("#class_id").prop("selectedIndex", 0);
            $("#section_id").find('option:not(:first)').remove();
            var password = makeid(5);
            $('#password').val("").val(password);
        })

        $("form#form-addconference").submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                    url = $form.attr('action');
            var $button = $form.find("button[type=submit]:focus");
            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize(),
                dataType: "JSON",
                beforeSend: function () {
                    $button.button('loading');
                },
                success: function (data) {
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        $('#modal-online-timetable').modal('hide');
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $button.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $button.button('reset');
                },
                complete: function (data) {
                    $button.button('reset');
                }
            });
        })

        $('#modal-online-timetable').on('hidden.bs.modal', function () {
            $(this).find("input,textarea,select").not("input[type=radio]")
                    .val('')
                    .end();
            $(this).find("input[type=checkbox], input[type=radio]")
                    .prop('checked', false);
            $('input:radio[name="host_video"][value="1"]').prop('checked', true);
            $('input:radio[name="client_video"][value="1"]').prop('checked', true);
        });

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });
    })(jQuery);

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                       
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                       
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function getEmployeeName(role) {
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/staff/getEmployeeByRole",
            data: {'role': role},
            dataType: "JSON",
            beforeSend: function () {
                $('#staff_id').html("");
                $('#staff_id').addClass('dropdownloading');
            },
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value='" + obj.id + "'>" + obj.name + " " + obj.surname + "</option>";
                });
                $('#staff_id').append(div_data);
            },
            complete: function () {
                $('#staff_id').removeClass('dropdownloading');
            }
        });
    }

  
</script>
