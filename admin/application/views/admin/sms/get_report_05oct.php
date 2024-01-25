<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> SMS Send reports <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> SMS Status</h3>
                        <h5>Remaining SMS for Send : <?php echo $this->Messages_model->sms_count(); ?> </h5>
					<a href="<?php echo base_url('sendsmslink');?>" class="btn btn-success btn-xs">Send</a>
                    </div>
                   
                                    </div>
                <?php
                if (isset($result)) {
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-info" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> Sms reports </h3>
                                </div>                              
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php echo $this->lang->line('student_fees_report'); ?></div>    
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th class="text text-left">Title</th>
                                                <th class="text text-left">Message</th>
                                                <th class="text text-left">Date</th>
                                                <th class="text text-right">Status</th>
                                              <!--   <th class="text-right"><?php echo 'Vehicle'; ?></th>
                                                <th class="text text-right"><?php echo 'Date'; ?></th>
                                                <th class="text text-right"><?php echo 'Submit By'; ?></th> -->
                                                

                                            </tr>
                                        </thead>  
                                        <tbody>
                                            
                                                <?php
                                                $total_amount=0;
                                                
                                                foreach ($result as $value) { 
                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $value->title; ?> </td>
                                                                <td><?php echo $value->message; ?> </td>
                                                                 <td><?php echo $value->created_at; ?> </td>
                                                                <td><?php  if($value->sms_status==1){echo '<b style="color: green;font-weight: bold;">Sent</b>';}else{ echo '<b style="color: red;font-weight: bold;">Pending</b>'; } ?> </td>
                                                               <!--  <td><?php echo $value->roll_no; ?> </td>
                                                                <td class="text text-right"><?php echo $value->class . ' (' . $value->section.')'; ?> </td>
                                                                <td class="text text-right"><?php echo $value->vehicle_no ?> </td>
                                                                <td class="text text-right"><?php echo $value->date; ?> </td>
                                                                <td class="text text-right"><?php echo $value->submit_by; ?> </td>
                                                                <td class="text text-right"><?php echo $value->amount; ?> </td> -->
                                                    </tr>   <?php                     
                                               
                                                }
                                                ?>

                                            
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" class="text-danger text-center">
                                                        <span class="input input-danger"><?php echo $this->lang->line('no_record_found'); ?></span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            
                                        <tr  class="box box-solid total-bg">
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td class="text text-right">
                                               
                                            </td>
                                            <td class="text text-right">
                                            </td>
                                            <td class="text text-right">
                                            </td>
                                            <td class="text text-right">
                                            </td>
                                            
                                            <td class="text text-right">
                                               
                                            </td>
                                            <td class="text text-right">
                                               
                                            </td> 

                                            <td class="text text-right">
                                              
                                            </td>
                                        </tr>

                                        </tbody> 
                                    </table>
                                </div>                            
                            </div>                       
                        </div>
                    </div>
                    <?php
                }
                ?>
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
                                $('#section_id').append(div_data);
                            }
                        });
                    }
                }
                $(document).ready(function () {
                var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
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

                                $('#section_id').append(div_data);
                            }
                        });
                    });
                    $(document).on('change', '#section_id', function (e) {
                        getStudentsByClassAndSection();
                    });
                    var class_id = $('#class_id').val();
                    var section_id = '<?php echo set_value('section_id') ?>';
                    getSectionByClass(class_id, section_id);
                    
                     $('#dob').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
                });
                function getStudentsByClassAndSection() {
                    $('#student_id').html("");
                    var class_id = $('#class_id').val();
                    var section_id = $('#section_id').val();
                    var base_url = '<?php echo base_url() ?>';
                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                    $.ajax({
                        type: "GET",
                        url: base_url + "student/getByClassAndSection",
                        data: {'class_id': class_id, 'section_id': section_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, obj)
                            {
                                div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                            });
                            $('#student_id').append(div_data);
                        }
                    });
                }

                $(document).ready(function () {
                    $("ul.type_dropdown input[type=checkbox]").each(function () {
                        $(this).change(function () {
                            var line = "";
                            $("ul.type_dropdown input[type=checkbox]").each(function () {
                                if ($(this).is(":checked")) {
                                    line += $("+ span", this).text() + ";";
                                }
                            });
                            $("input.form-control").val(line);
                        });
                    });
                });
                $(document).ready(function () {
                    $.extend($.fn.dataTable.defaults, {
                        ordering: false,
                        paging: false,
                        bSort: false,
                        info: false
                    });
                });
            </script>


