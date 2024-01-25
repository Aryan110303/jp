<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> Complaint
        </h1>
    </section>   
    <section class="content">
   
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Complaint List</h3>
                        <?php if ($this->rbac->hasPrivilege('homework', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="addhomework()" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                             <button type="button" onclick="addhomework()" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#abmod"><i class="fa fa-plus"></i> Add Multiple
                                   </button>
                            </div>
                       
                        <?php } ?>
                    </div>   

                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th >Numbers</th>
                                        <th>Complaint Date</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
<?php foreach ($homeworklist as $key => $homework) {
    ?>
                                        <tr>
                                           
                                            <td><?php echo $homework["message"] ?></td>
                                            <td><?php echo $homework["user_list"] ?></td>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(),strtotime($homework['created_at'])); ?></td>
                                        
                                        </tr>
<?php } ?>

                                </tbody>      
                            </table> 

                        </div>           
                    </div>
                </div>
            </div>
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> Add Complaint</h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formadd" method="post" class="ptt10" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('class') ?></label><small class="req"> *</small> 
                                        <select class="form-control" name="class_id" id="class_id" onchange="getSection(this.value, 'section_id')">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                  <?php foreach ($classlist as $key => $value) {
                                                             ?>
                                                <option value="<?php echo $value["id"] ?>"><?php echo $value["class"] ?></option> 
                                                <?php } ?>

                                        </select>
                                        <span id="name_add_error" class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('section') ?></label><small class="req"> *</small>
                                        <select class="form-control" name="section_id" id="section_id" onchange="getStudent(this.value, 'class_id', 'student_list')">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>

                                        </select>
                                        <span id="name_add_error" class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
       
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd">Select Template</label>
                                        <select class="form-control" name="template" id="template" onchange="getTemplate(this.value)">
                                            <option>Select Template</option>
                                            <?php
                                               foreach ($templateList as $smsList) {
                                                     echo "<option value=".$smsList->template_id.">" .$smsList->template_name. "</option>";
                                                 }  
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd">Select Department</label>
                                        <div class="wellscroll">
                                            <ul class="list-group send_list">
                                   
                                              
                                                    <div class="checkbox">
              
                                                    <ul class="treeview">
                                                    <?php foreach ($departmentList as $depList) { ?>
                                                       <li  ><?php echo $depList->department_name; ?>
                                                            <ul class="treeview-menu">
                                                            <?php $staff = $this->classteacher_model->get_data_by_query("SELECT * FROM staff where id ='".$depList->id."'  ORDER BY department DESC"); 
                                                                 foreach($staff as $staf){  ?>
                                                                    
                                                                <li><input type="checkbox" name="department[]" value="<?= $staf['id']  ?>" > <b><?=  $staf['name'].''.$staf['surname'] ?></b></li>
                                                               <?php  } ?>
                                                              
                                                           </ul>
                                                       
                                                       </li>
                                                       <?php } ?>
                                                     </ul> 
                                                     </div>
                                             
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd">Select student</label>
                                        <div class="wellscroll">
                                            <ul class="list-group send_list">
                                                <li id="student_list">
                                                    <span id="name_add_error" class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12" id="student_list">
                                    
                                <span id="name_add_error" class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>
                                        <textarea name="description" id="textarea-compose" class="form-control" rows="6"></textarea>
                                    </div> 
                                </div>

                            </div><!--./row-->    

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">

                <div class="pull-right paddA10">

                    <input type="submit" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" />
                </div>

            </div>
            </form>
        </div>
    </div>    
</div>
        
<!--- -->
<div class="modal fade" id="abmod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> Add Complaint for Multiple Classes </h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formaddmultiple" method="post" class="ptt10" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('class') ?></label><small class="req"> *</small> 

                                    <div class="col-md-12">

                                                <div class="form-group">
                                                   
                                                   <div class="wellscroll">
                                            <ul class="list-group send_list">
                                                 <?php
                                                $class = $this->classteacher_model->get_data_by_query("SELECT * from student_all_numbers GROUP BY class_id");  
                                                        foreach ($class as $classes) {
                                                            ?>

                                                            <div class="checkbox">
                                                                <label><input type="checkbox" name="class_id[]" value="<?php echo $classes['class_id']; ?>"> <b><?php echo $this->classteacher_model->findfield('classes','id', $classes['class_id'],'class') ;; ?></b></label>
                                                            </div>


                                                            <?php
                                                        }
                                                        ?>
                                            </ul>
                                        </div>
                                                </div>       
                                            </div>
                                         </div>

                                </div>
                               
                                





                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd">Select Template</label>
                                        <select class="form-control" name="template" id="template" onchange="getTemplate2(this.value)">
                                            <option>Select Template</option>
                                            <?php
                                               foreach ($templateList as $smsList) {
                                                     echo "<option value=".$smsList->template_id.">" .$smsList->template_name. "</option>";
                                                 }  
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="col-sm-12" id="student_list">
                                <span id="name_add_error" class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>
                                        <textarea name="description" id="textarea-compose2" class="form-control" rows="6"></textarea>
                                    </div> 
                                </div>






                            </div><!--./row-->    

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">

                <div class="pull-right paddA10">

                    <input type="submit" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" />
                </div>

            </div>
            </form>
        </div>
    </div>    
</div>

<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('edit_homework'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formedit" method="post" class="ptt10" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('class') ?></label><small class="req"> *</small>
                                        <select class="form-control" name="class_id" id="classid" onchange="getSection(this.value, 'sectionid')">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($classlist as $key => $value) {
                                                                     ?>
                                                <option value="<?php echo $value["id"] ?>"><?php echo $value["class"] ?></option>

                                            <?php } ?>

                                        </select>
                                        <span id="name_add_error" class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('section') ?></label><small class="req"> *</small>
                                        <select class="form-control" name="section_id" id="sectionid" onchange="getSubject(this.value, 'classid', 'subjectid')">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>

                                        </select>
                                        <span id="name_add_error" class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('subject') ?></label><small class="req"> </small>
                                        <select class="form-control" name="subject_id" id="subjectid">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>


                                        </select>
                                        <span id="name_add_error" class="text-danger"><?php echo form_error('subject_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('homework_date'); ?></label>
                                        <input type="text" id="homeworkdate" name="homework_date" class="form-control" value="" readonly="">
                                        <span id="date_add_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('submission_date'); ?></label>
                                        <input type="text" id="submitdate" name="submit_date" class="form-control" value="" readonly="">
                                        <input type="hidden" name="homeworkid" id="homeworkid">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file" id="file" name="userfile" class="form-control filestyle" value="">

                                        <input type="hidden" name="document" id="document">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>
                                        <textarea name="description" id="desc-textarea" class="form-control"></textarea>
                                    </div> 
                                </div>

                            </div><!--./row-->    

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">

                <div class="pull-right paddA10">

                    <input type="submit" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" />
                </div>
            </div>
        </div>
        </form>
    </div>
</div>    
</div>
<!-- -->

<div class="modal fade" id="evaluation" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('evaluate_homework'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="evaluation_details">
            </div>
        </div>
    </div>
</div>
<!-- -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#homework_date,#submit_date,#homeworkdate,#submitdate').datepicker({
            format: date_format,
            autoclose: true
        });

        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });

    });

</script>
<script>
    $(function () {

        $("#compose-textarea,#desc-textarea").wysihtml5();
    });
</script>
<script type="text/javascript">

	 function getStudent(section_id, classid, htmlid) {
        $('#' + htmlid).html("");

        var class_id = $('#' + classid).val();

        var base_url = '<?php echo base_url() ?>';
        var div_data = '';
        $.ajax({
            type: "POST",
            url: base_url + "complaint/getStudentByClassandSection",
            data: {'class_id': class_id, 'section_id': section_id},
            dataType: "json",
            success: function (data) {
					 console.log(data);
                $.each(data, function (i, obj)
                {
					
					if(obj.guardian_phone!=''){	
                    div_data += "<label class='checkbox-inline'><input type='checkbox' name='guardian_phone[]' value='" + obj.guardian_phone + "'/>" + obj.firstname+" "+obj.lastname + "</label></br>";
					}
                });

                $('#' + htmlid).append(div_data);
            }
        });
    }
    ;


    function getSubject(section_id, classid, htmlid) {
        $('#' + htmlid).html("");

        var class_id = $('#' + classid).val();

        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/teacher/getSubjctByClassandSection",
            data: {'class_id': class_id, 'section_id': section_id},
            dataType: "json",
            success: function (data) {

                $.each(data, function (i, obj)
                {


                    div_data += "<option value=" + obj.subject_id + ">" + obj.name + " (" + obj.type + ")" + "</option>";
                });

                $('#' + htmlid).append(div_data);
            }
        });
    }
    ;

    function getSection(class_id, htmlid) {
        $('#' + htmlid).html("");
        // var class_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var url = "<?php
$userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2)) {
    echo "getClassTeacherSection";
} else {
    echo "getByClass";
}
?>";
        $.ajax({
            type: "GET",
            url: base_url + "sections/" + url,
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });

                $('#' + htmlid).append(div_data);
            }
        });
    }
    ;

    $(document).ready(function (e) {
        getSectionByClass("<?php echo $class_id ?>", "<?php echo $section_id ?>", 'secid');
        getSubjectByClassandSection("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_id ?>", 'subid');
    });

    $(document).ready(function (e) {
        $("#formadd").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("complaint/create") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                {

                    if (res.status == "fail") {

                        var message = "";
                        $.each(res.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);

                    } else {

                        successMsg(res.message);

                        window.location.reload(true);
                    }
                }
            });
        }));

 $("#formaddmultiple").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("complaint/create_multiple") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                { 
                    if (res.status == "fail") { 
                        var message = "";
                        $.each(res.error, function (index, value) { 
                            message += value;
                        });
                        errorMsg(message); 
                    } else { 
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));   
        $("#formedit").on('submit', (function (e) {

            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("homework/edit") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                {

                    if (res.status == "fail") {

                        var message = "";
                        $.each(res.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);

                    } else {

                        successMsg(res.message);

                        window.location.reload(true);
                    }
                }
            });
        }));

    });


    function getRecord(id) {


        var random = Math.random();
        $('#classid').val(null).trigger('change');
        $.ajax({
            url: "<?php echo site_url("homework/getRecord/") ?>" + id + "?r=" + random,
            type: "POST",
            dataType: 'json',

            success: function (res)
            {

                getSelectClass(res.class_id);
                getSectionByClass(res.class_id, res.section_id, 'sectionid');
                getSubjectByClassandSection(res.class_id, res.section_id, res.subject_id, 'subjectid');
                $("#homeworkdate").val(new Date(res.homework_date).toString("MM/dd/yyyy"));
                $("#submitdate").val(new Date(res.submit_date).toString("MM/dd/yyyy"));
                $("#desc-textarea").text(res.description);
                $('iframe').contents().find('.wysihtml5-editor').html(res.description);
                // $('select[id="classid"] option[value="' + res.class_id + '"]').attr("selected", true);
                $("#homeworkid").val(res.id);
                $("#document").val(res.document);
            }
        });

    }

    function getSelectClass(class_id) {
        $('#classid').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "homework/getClass",
            //data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {

                    var sel = "";
                    if (class_id == obj.id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.id + " " + sel + ">" + obj.class + "</option>";
                });
                $('#classid').append(div_data);

            }});
    }

    function getSectionByClass(class_id, section_id, htmlid) {
        if (class_id != "" && section_id != "") {
            $('#' + htmlid).html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
$userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2)) {
    echo "getClassTeacherSection";
} else {
    echo "getByClass";
}
?>";
            $.ajax({
                type: "GET",
                url: base_url + "sections/" + url,
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#' + htmlid).append(div_data);
                }
            });
        }
    }

    function getSubjectByClassandSection(class_id, section_id, subject_id, htmlid) {
        console.log("rrrr");
        if (class_id != "" && section_id != "" && subject_id != "") {
            $('#' + htmlid).html("");
            //  var class_id = $('#class_id').val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "POST",
                url: base_url + "admin/teacher/getSubjctByClassandSection",
                data: {'class_id': class_id, 'section_id': section_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subject_id == obj.subject_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_id + " " + sel + ">" + obj.name + " (" + obj.type + ")" + "</option>";
                    });

                    $('#' + htmlid).append(div_data);
                }
            });
        }
    }

    function evaluation(id) {
        $('#evaluation_details').html("");
        $.ajax({
            url: '<?php echo base_url(); ?>homework/evaluation/' + id,
            success: function (data) {
                $('#evaluation_details').html(data);
                // $.ajax({
                //     url: '<?php echo base_url(); ?>homework/getRecord/' + id,
                //     success: function (data) {
                //         $('#timeline').html(data);
                //     },
                //     error: function () {
                //         alert("Fail")
                //     }
                // });
            },
            error: function () {
                alert("Fail")
            }
        });
    }

    function addhomework() {

        $('iframe').contents().find('.wysihtml5-editor').html("");
    }

    function getTemplate(id) {
        // body...
        $("#textarea-compose").text('');
        $.ajax({
            url: "<?php echo site_url("homework/getTemplateMsg/") ?>" + id,
            type: "POST",
            dataType: 'json',

            success: function (res)
            {
                // alert(res.template_msg)
                if (res) {
                    $("#textarea-compose").text(res.template_msg);
                    // $('iframe').contents().find('.wysihtml5-editor').html(res.template_msg);
                }else{
                    $("#textarea-compose").text('');
                    // $('iframe').contents().find('.wysihtml5-editor').html('');
                }
            }
        });
    }
    function getTemplate2(id) {
        // body...
        $("#textarea-compose").text('');
        $.ajax({
            url: "<?php echo site_url("homework/getTemplateMsg/") ?>" + id,
            type: "POST",
            dataType: 'json',

            success: function (res)
            {
                // alert(res.template_msg)
                if (res) {
                    $("#textarea-compose2").text(res.template_msg);
                    // $('iframe').contents().find('.wysihtml5-editor').html(res.template_msg);
                }else{
                    $("#textarea-compose2").text('');
                    // $('iframe').contents().find('.wysihtml5-editor').html('');
                }
            }
        });
    }
</script>