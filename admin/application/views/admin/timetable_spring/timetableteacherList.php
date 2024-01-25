<style type="text/css">
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
<?php //echo"<pre>";
//print_r($result_array1); die; ?>
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

                     <div class="row">
                <form action="<?php echo base_url('admin/timetable/gettimetable_by_teacher'); ?>" method="POST">
                              <div class="form-group col-md-4" >
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('teacher'); ?></label>
                           <select  name="teacher_id" class="form-control" >
                                <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($teachers as $teacher) {
                                    ?>
                                    <option value="<?php echo $teacher['id'] ?>"><?php echo $teacher['name'].' '.$teacher['surname'] ;?></option>
                                    <?php   }
                                ?>
                            </select>   
                           </div>
                           <div  class="form-group col-md-4" >
                            <label for="exampleInputEmail1"><?php echo "Get List" ; ?></label>
                            <input type="submit" name="button" value="Get TimeTable" class="btn btn-default form-control"> 
                          </div>      
                        </form>
                                     
                     </div>                      
                        <?php
                            if (!empty($result_array1)) {
                                ?>
                    <div class="box box-info" id="timetable">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> Teacher Timetable <br><br><br>
                             <?php                           

                               echo $result_array1[0]->tname.' '.$result_array1[0]->surname;
                               ?>
                             </h3>
                        </div>
                        <div class="box-body">
                            <div class="row print" >
                                <div class="col-md-12">
                                    <div class="col-md-offset-4 col-md-4">
                                        <center><b><?php echo $this->lang->line('class'); ?>: </b> <span class="cls"></span></center> 
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (!empty($result_array1)) {
                                ?>
                                <div class="table-responsive">
                                    <div class="download_label">Teacher's Timetable<br><br>
                             <?php                           

                               echo $result_array1[0]->tname.' '.$result_array1[0]->surname;
                               ?>
                                      
                                    </div>
                                    <table class="table table-bordered example">
                                        <thead>
                                            <tr>
                                              <th>Days</th>
                                              <th>P1</th>
                                              <th>P2</th>
                                              <th>P3</th>
                                              <th>P4</th>
                                              <th>P5</th>
                                              <th>P6</th>
                                              <th>P7</th>
                                              <th>P8</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                            $sno = 1 ;
                                            // foreach ($result_array1 as $key => $timetable) {
                                                ?>
                                                  <tr>
                                                    
                                                      <td>Monday</td>
                                                      <?php 
                                                      $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                        if ($count == 8) {
                                                         break;
                                                        }
                                                          ?>

                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php

                                                    $count++;  
                                                  }  ?>
                                                  </tr>

                                                   <tr>
                                                    
                                                      <td>Tuesday</td>                                                     
                                                       
                                             <?php $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                        if ($count < 8 ) { 
                                                          $count++ ;
                                                           continue;
                                                        }if ($count == 16) {
                                                            break;
                                                        }
                                                          ?>

                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php
                                                    $count++ ;  }  ?>
                                                  </tr>
                                                   <tr>
                                                    
                                                      <td>Wednesday</td>                                                                                      
                                                     <?php $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                          if ($count < 16 ) { 
                                                            $count++ ;
                                                            continue;
                                                        }if ($count == 24) {
                                                            break;
                                                        }
                                                          ?>
                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php
                                                    $count++ ;  }  ?>
                                                  </tr>
                                                  <tr>                                                    
                                                  <td>Thrusday</td>                                                     
                                                       
                                             <?php $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                         if ($count < 24) {
                                                          $count++ ;
                                                           continue;
                                                        }if ($count == 32) {
                                                            break;
                                                        }
                                                          ?>

                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php
                                                    $count++ ;  }  ?>
                                                  </tr>
                                                   <tr>
                                                    
                                                      <td>Friday</td>                                                     
                                                       
                                             <?php $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                         if ($count < 32 ) { 
                                                          $count++ ;
                                                          continue;
                                                        }if ($count == 40) {
                                                            break;
                                                        }
                                                          ?>

                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php
                                                    $count++ ;  }  ?>
                                                  </tr>
                                                   <tr>
                                                    
                                                      <td>Saturday</td>                                                     
                                                       
                                             <?php $count = 0 ; 
                                                       foreach ($result_array1 as $key => $value) {
                                                        if ($count < 40 ) { 
                                                          $count++ ;
                                                          continue;
                                                        }if ($count == 48) {
                                                            break;
                                                        }
                                                          ?>

                                                       <td><?php echo $value->subname.'<br> ';  echo $value->class.' '.$value->section ; ?></td>
                                                       <?php
                                                    $count++ ;  }  ?>
                                                  </tr>

                                             <?php
                                            
                                            // }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div> 
            </div>  
            <?php
        } else {
          ?>  <div class="">No Reult Found</div> <?php
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
<!-- 
<script>
   $('#teacher_id').on('change', function() {
    var tid = this.value;
 //alert(tid);
     $.ajax({
          type:"POST",
           url:"<?php echo base_url('admin/timetable/gettimetable_by_teacher/');?>"+tid,
          success: function(msg){
           
        }
      });
 });
    
            /**/
 

</script> -->