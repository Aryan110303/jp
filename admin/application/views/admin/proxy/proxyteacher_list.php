<div class=" content-wrapper" style="min-height: 946px;" id="printhatao">  
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small></h1>
    </section>
    <section class="content " id="printDiv">
        <div class="row">    

            <div class="col-md-12">          
                 <div class="box box-primary " >

                    <div class="box-header with-border">
                        <label>
                          Teacher Substitution Timetable
                        </label>
                     <div class="row">
                        <form action="<?php echo base_url('admin/Proxymanagment/get_freeteacher'); ?>" method="POST">
                              <div class="form-group col-md-4" >
                                  <label for="exampleInputEmail1"><?php echo $this->lang->line('teacher'); ?></label>
                                 <select  name="teacher_id" class="form-control" >
                                      <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                      <?php
//print_r($teachers); die;

                                      foreach ($teachers as $teacher) {
                                          ?>
                                          <option <?php if( set_value('teacher_id') == $teacher['id']) echo "selected"; ; ?>  value="<?php echo $teacher['id'] ?>"><?php echo $teacher['name'] .' '. $teacher['surname'] ;?></option>
                                          <?php   }
                                      ?>
                                  </select>   
                            </div>
                            <div class="form-group col-md-4" >
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label>
                                 <input type="text" id="datepick" name="proxydate" value="<?php echo set_value('proxydate') ; ?> " class="form-control">
                           </div>
                           <div  class="form-group col-md-4 no-print"  >
                            <label for="exampleInputEmail1"><?php echo "Get Free Teacher" ; ?></label>
                            <input type="submit" name="button" value="Get TimeTable" class="btn btn-default form-control"> 
                          </div>      
                     </form>
                  </div>                      
                        <?php
                            if (!empty($resultdata)){
                              $count = count($resultdata) ;
                             ?>

                            <form action="<?php echo base_url('admin/Proxymanagment/save_proxy'); ?>" method="POST">
                            <input type="hidden" name="behalf" value="<?php echo set_value('teacher_id') ; ?>">
                            <input type="hidden" name="date" value="<?php echo set_value('proxydate') ; ?>">
                                <table class="table table-responsive"> 
                                <tr> 
                                      <td><?php echo "Period"; ?></td>
                                      <td><?php echo "Day"; ?></td>
                                      <td><?php echo "Class"; ?></td>
                                      <td><?php echo "Section"; ?></td>
                                      <td><?php echo "Subject"; ?></td>
                                      <td><?php echo "Teacher's List"; ?></td>
                                      
                                </tr> 
                                <?php foreach ($resultdata as $key => $value) { 


                                  //print_r($value); die;?>
                                   <tr> 
                                      <td><?php echo $periods[$value->period_id];
                                        echo ' <input type="hidden" name="periods[]" value="'.$periods[$value->period_id].'">' ;
                                         ?></td>


                                      <td><?php echo $days[$value->days];
                                        echo ' <input type="hidden" name="days[]" value="'.$days[$value->days].'">' ; ?></td>
                                      <td>       
                                        <?php foreach ($classlists as $key => $class) {
                                       if ( ($value->class_id) == $class['id']) {
                                         echo $class['class'];
                                         echo ' <input type="hidden" name="classes[]" value="'.$class['class'].'">' ;
                                       }
                                      } ?>
                                        
                                     </td>
                                      <td><?php foreach ($sections as $key => $section) {
                                       if ( ($value->section_id) == $section['id']) {
                                         echo $section['section'];
                                        echo ' <input type="hidden" name="sections[]" value="'.$section['section'].'">' ;
                                       }
                                      }  ?>
                                        

                                      </td> 

                                       <td><?php foreach ($subjects as $key => $subject) {

                                       if ( ($value->subject_id) == $subject['id']) {
                                         echo $subject['name'].'( '.  $subject['type'].')';
                                         echo ' <input type="hidden" name="subjects[]" value="'.$subject['name'].'">' ;
                                       }
                                      }  ?></td>
                                      <td><select name="extrateacher[]" class="form-control">
                                        <option value=""> Select Teacher</option>
                                        <?php 

                                        $freeteachers = $this->proxy_model->get_teachers($value->period_id,$value->days, set_value('proxydate') );
                                          if (!empty($freeteachers)) {
                                             foreach ($freeteachers as $key => $teacher) {
                                                $periods_count = $this->proxy_model->get_count( $teacher['id'], $value->days);
                                              /*     print_r($periods_count);  die;
                                                 print_r($this->db->last_query()); 
                                                  die;*/
                                               ?>
                                                 <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['name'].''.$teacher['surname'] .'--- ('. $periods_count.' Periods  )' ; ?></option>
                                           <?php } 
                                               }else{
                                             ?>  <option value="">No Teacher Available</option>  <?php
                                           } ?>
                                       
                                      </select> </td>
                                      
                                  </tr>
                              <?php  }  ?>
                                 

                                </table><!-- 
                                 <button onclick="printdiv();" class="btn btn-default no-print">Print </button> -->
                                 <button type="submit" class="btn btn-primary no-print">Save</button>

</form>
        
                </div> 


            </div>  
            <?php
        } else {
          ?>  <div class="">No Reult Found</div> <?php
        }
        ?>

         
        <br>
        <hr>
        <br>
        <div class="no-print">
<div class="row">
  <div class="col-md-4">
    <input type="text" id="datepick2" class="form-control" name="date">
  </div>
   <div class="col-md-2">
   <button onclick="printproxy(0,$('#datepick2').val());" class="btn btn-primary">Print</button>
  </div>
</div>
         
          <hr>
  <h3>
       Teacher Substitution Timetable List
  </h3>
          <table class="table-responsive table">
            <thead>
              <th> Teacher's Name</th>
              <th>On Date</th>
              <th>Day</th>
              <th>Action</th>
            </thead>
            <?php 
          foreach ($proxylist as $key => $value) {    ?>
            <tr>
               <td><?= $value->name.' ' .$value->surname?></td>
               <td><?= $value->date ?></td>
               <td><?= $value->days?></td>
               <td>
                <?php 
                  $behalf = $value->on_behalf;
                   $date = $value->date ; 
                ?>
                  <button onclick="printproxy(<?php echo $behalf ;?>,'<?php  echo $value->date ?>' );" class="btn btn-primary">Print </button>
              </td>
             
            </tr>
          <?php }?>
          </table>
           
        </div>
        
    </section>


  
</div>



<div id="ateekprint">
  
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





     function printdiv() {
      var printdata =  $('#printDiv').html();
      window.print(printdata);
     }


     function printproxy(tid, date) {

  var teacherid = tid;
  var dates  =  date ; 
         $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/Proxymanagment/getprint_data')?>",
                 data: {'tid': teacherid,'date':dates},
                   success: function (msg) 
                   {
                    $('#ateekprint').html(msg);  
                    var abcd =$('#ateekprint').html();
                    $('#printhatao').addClass('no-print');
                    window.print(abcd);
                    $('#ateekprint').html(' ');
                   }
            });
     }
</script>
