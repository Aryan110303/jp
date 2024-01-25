<?php //echo '<pre>';print_r($timatable);?><style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    .print, .print *
    {
        display: none;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">       
            <div class="col-md-12">          
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                   
                    </div> 
                            
                    
                     <form action="<?php echo site_url('admin/timetable/edit_teachertt') ?>"  method="post" accept-charset="utf-8" name="add">

                       
                        <div class="box-body"> 
                            <div class="form-group col-md-4" >
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('teacher'); ?></label>
                                       <select  id="teacher_id" name="teacher_id" class="form-control" >
                                            <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($teachers as $teacher) {
                                                ?>
                                                <option value="<?php echo $teacher['id'] ?>" <?php if ($timatable[0]['teacher_id']==$teacher['id'] ) echo 'selected'; ?>><?php echo $teacher['name'].' '.$teacher['surname'] ;?></option>
                                                <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                      
                                    </div>
                           

                              <br>
                            <?php echo $this->customlib->getCSRF(); 
                                $period=array(1,2,3,4,5,6,7,8);

                                $days=array('m'=>'Monday','t'=>'Tuesday','w'=>'WednesDay','th'=>'Thrusday','f'=>'Friday','s'=>'Saturday');

                            ?>
<table class=" table table-responsive ">
     <thead>
                                         <th>Days</th>
                                         <th>P1</th>
                                         <th>P2</th>
                                         <th>P3</th>
                                         <th>P4</th>
                                         <th>P5</th>
                                         <th>P6</th>
                                         <th>P7</th>
                                         <th>P8</th>
    </thead>
    <tbody>
        <tr>
            <td><strong> Timing</strong></td>
            <td><input type="time" name="timefrom1" value="<?php echo $timatable[0]['timefrom'] ; ?>">
                <input type="time" name="timeto1" value="<?php echo $timatable[0]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom2" value="<?php echo $timatable[1]['timefrom'] ; ?>">
                <input type="time" name="timeto2" value="<?php echo $timatable[1]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom3" value="<?php echo $timatable[2]['timefrom'] ; ?>">
                <input type="time" name="timeto3" value="<?php echo $timatable[2]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom4" value="<?php echo $timatable[3]['timefrom'] ; ?>">
                <input type="time" name="timeto4" value="<?php echo $timatable[3]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom5" value="<?php echo $timatable[4]['timefrom'] ; ?>">
                <input type="time" name="timeto5" value="<?php echo $timatable[4]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom6" value="<?php echo $timatable[5]['timefrom'] ; ?>">
                <input type="time" name="timeto6" value="<?php echo $timatable[5]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom7" value="<?php echo $timatable[6]['timefrom'] ; ?>">
                <input type="time" name="timeto7" value="<?php echo $timatable[6]['timeto'] ; ?>"></td>
            <td><input type="time" name="timefrom8" value="<?php echo $timatable[7]['timefrom'] ; ?>">
                <input type="time" name="timeto8" value="<?php echo $timatable[7]['timeto'] ; ?>"></td>
           
        </tr>
    <?php 
	$x=0;	
	foreach($days as $days_key=>$days_value) {  ?>
    <tr>
        <td><?php echo $days_value;?></td>
<?php foreach($period as $period_key=> $period_value) {?>
         <td>
            <!-- Class  -->
            <div class="row">
                                                <div class="form-group col-md-2" >
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                                   <select autofocus="" style="width:80px;" id="class_id_<?php echo $timatable[$x]['id'] ?>" name="class_id_<?php echo $timatable[$x]['id'] ?>" class="form-control" >
                                                        <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($classlists as $class) {
                                                            ?>
                                                            <option value="<?php echo $class['id'] ?>" <?php if ($timatable[$x]['class_id']==$class['id'])echo 'selected'; ?>><?php echo $class['class'] ?></option>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                    </select>
                                      
                                                 </div>
            </div>
            <!-- section  -->
            <div class="row">

                                                    <div class="form-group col-md-2">
                                                    <label for="exampleInputEmail1">Section</label>

                                                    <select style="width:80px;" id="section_id_<?php echo $timatable[$x]['id'] ?>" name="section_id_<?php echo $timatable[$x]['id'] ?>" class="form-control" >
                                                    <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($sections as $section) {
                                                        ?>
                                                        <option value="<?php echo $section['id'] ?>" <?php if ($timatable[$x]['section_id']==$section['id'])echo 'selected'; ?>><?php echo $section['section'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                    </select>
                                              
                                                    </div>
            </div>
            <!-- subject  -->
            <div class="row">
                                                   <div class="form-group col-md-2">
                                                    <label for="exampleInputEmail1">Subject</label>
                                                    <select style="width:80px;" id="subject_id_<?php echo $timatable[$x]['id'] ?>" name="subject_id_<?php echo $timatable[$x]['id'] ?>" class="form-control" >
                                                          <option value="0"><?php echo $this->lang->line('select'); ?></option> <?php
                                                        foreach ($subjects as $subject) {
                                                            ?>
                                                            <option value="<?php echo $subject['id'] ?>" <?php if ($timatable[$x]['subject_id']==$subject['id'])echo 'selected'; ?>><?php echo $subject['name'] ?></option>
                                                            <?php                                               
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="ids[]" value="<?php echo $timatable[$x]['id'] ?>"/>
                                                  
                                                   <input type="hidden" name="days[]" value="<?php echo $timatable[$x]['days'] ?>"/>
                                                   <input type="hidden" name="periods[]" value="<?php echo $timatable[$x]['period_id'] ?>"/>
                                                    </div>
            </div>
         </td>    
 <?php $x++; }
 ?>
 </tr><?php  } ?>

                                       
                                   </tbody>
                              </table>    


                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('add'); ?></button>
                        </div>
                    </form> 
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

                    $('#section_id').append(div_data);
                }
            });
        }
    }
    $(document).ready(function () {
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
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#feecategory_id', function (e) {
            $('#feetype_id').html("");
            var feecategory_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "feemaster/getByFeecategory",
                data: {'feecategory_id': feecategory_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.type + "</option>";
                    });

                    $('#feetype_id').append(div_data);
                }
            });
        });
    });

    $(document).on('change', '#section_id', function (e) {
        $("form#schedule-form").submit();
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        var cls = $("#class_id option:selected").text();
        var sec = $("#section_id option:selected").text();
        $('.cls').html(cls + '(' + sec + ')');
        Popup(jQuery(elem).html());
    }

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
</script>