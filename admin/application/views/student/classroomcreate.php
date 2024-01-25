<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('classes'); ?> <small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3>
                            
                       </div>
                    <div class="box-body no-padding">
                        
                         <form role="form" action="<?php echo base_url('student/createRoomAdmin') ?>" method="post" class="">
                        
                         <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-6">
                                            <div class="form-group"> 
                                                <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small> 
                                                <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($classlist as $class) {
                                                        ?>
                                                        <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>  
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small> 
                                                <select  id="section_id" name="section" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="101">All Section</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small> 
                                               <input type="text" name="title" class="form-control">
                                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small> 
                                               <input type="text" name="description" class="form-control">
                                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Start Date</label><small class="req"> *</small> 
                                               <input type="text" id="datepicker" name="startdate" class="form-control">
                                                <span class="text-danger"><?php echo form_error('startdate'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Start Time</label><small class="req"> *</small> 
                                               <input type="text" id="time1" name="starttime" class="form-control" placeholder="09:15">
                                                <span class="text-danger"><?php echo form_error('starttime'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>End Time</label><small class="req"> *</small> 
                                               <input type="text" id="time2" name="endTime" class="form-control" placeholder="10:15">
                                                <span class="text-danger"><?php echo form_error('endTime'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Time To Join</label><small class="req"> *</small> 
                                                <select  id="exceedTimeValue" name="exceedTimeValue" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="5">5 Minutes</option>
                                                    <option value="10">10 Minutes</option>
                                                    <option value="15">15 Minutes</option>
                                                    <option value="20">20 Minutes</option>
                                                    <option value="0">Always</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('exceedTimeValue'); ?></span>
                                            </div>   
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="Create" value="Create" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> Create</button>
                                            </div>
                                        </div>
                                </div>  
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>       
    </section>
</div>
<script type="text/javascript">
                                                            function getSectionByClass(class_id, section_id) {
                                                                if (class_id != "" && section_id != "") {
                                                                    $('#section_id').html("");
                                                                    var base_url = '<?php echo base_url() ?>';
                                                                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                                                                    $.ajax({
                                                                        type: "GET",
                                                                        url: base_url + "sections/getByClass",
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
                                                                            div_data += "<option value=101> All Section</option>";
                                                                            $('#section_id').append(div_data);
                                                                        }
                                                                    });
                                                                }
                                                            }
                                                            $(document).ready(function () {
                                                                var class_id = $('#class_id').val();
                                                                var section_id = '<?php echo set_value('section_id') ?>';
                                                                getSectionByClass(class_id, section_id);
                                                                $(document).on('change', '#class_id', function (e) {
                                                                    $('#section_id').html("");
                                                                    var class_id = $(this).val();
                                                                    var base_url = '<?php echo base_url() ?>';
                                                                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                                                                    $.ajax({
                                                                        type: "GET",
                                                                        url: base_url + "sections/getByClass",
                                                                        data: {'class_id': class_id},
                                                                        dataType: "json",
                                                                        success: function (data) {
                                                                            $.each(data, function (i, obj)
                                                                            {
                                                                                div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";

                                                                            });
                                                                            div_data += "<option value=101> All Section</option>";
                                                                            $('#section_id').append(div_data);
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script> <script>
                                                                        $( function() {
                                                                            $( "#datepicker" ).datepicker();
                                                                        } );
                                                                        </script>