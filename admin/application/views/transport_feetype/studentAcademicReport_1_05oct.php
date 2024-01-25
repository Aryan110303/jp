<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> Fees collection reports <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('transportfee/student_transport_fee') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Search By Date :</label><small class="req"></small> 
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control"  value="<?php echo set_value('dob'); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 23px;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>   </div>

                            </div>
                        </div>
                        <div class="box-footer">

                    </form>
                </div>
                <?php
                if (isset($student_due_fee)) {
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-info" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> Transport Fees report </h3>
                                </div>                              
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php echo $this->lang->line('student_fees_report'); ?></div>    
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th class="text text-left"><?php echo $this->lang->line('student_name'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('father_name'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('roll_no'); ?></th>
                                                <th class="text text-right"><?php echo 'Class '; ?></th>
                                                <th class="text-right"><?php echo 'Vehicle'; ?></th>
                                                <th class="text text-right"><?php echo 'Date'; ?></th>
                                                <th class="text text-right"><?php echo 'Submit By'; ?></th>
                                               
                                              
                                                <th class="text text-right"><?php echo $this->lang->line('total_fees'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                            </tr>
                                        </thead>  
                                        <tbody>
                                            
                                                <?php
                                                $total_amount=0;
                                                
                                                foreach ($student_due_fee as $value) { 
                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $value->firstname . ' ' . $value->lastname; ?> </td>
                                                                <td><?php echo $value->father_name; ?> </td>
                                                                <td><?php echo $value->admission_no; ?> </td>
                                                                <td><?php echo $value->roll_no; ?> </td>
                                                                <td class="text text-right"><?php echo $value->class . ' (' . $value->section.')'; ?> </td>
                                                                <td class="text text-right"><?php echo $value->vehicle_no ?> </td>
                                                                <td class="text text-right"><?php echo $value->date; ?> </td>
                                                                <td class="text text-right"><?php echo $value->submit_by; ?> </td>
                                                                <td class="text text-right"><?php echo $value->amount; ?> </td>
                                                    </tr>                        
                                                <?php 
                                                
                                             $total_amount+=$value->amount;
                                                }
                                                
                                                ?>

                                            
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" class="text-danger text-center">
                                                        <span class="input input-danger"><?php echo $this->lang->line('no_record_found'); ?></span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <?php
                                        }
                                        ?>
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
                                                <?php echo $this->lang->line('grand_total'); ?>
                                            </td>
                                            <td class="text text-right">
                                                <?php echo ($currency_symbol . number_format($total_amount, 2, '.', '') ); ?>
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
//                }
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


