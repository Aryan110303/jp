<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
  <div class="row">  
    <div class="col-md-12">
    <section class="content-header" style="padding-right: 0;">
        <h1>
            <i class="fa fa-user-plus"></i> Student Marksheet<small><?php echo $this->lang->line('student1'); ?></small></h1>
       
    </section>
</div>

<div>
<a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
  <aside class="studentsidebar">
       <div class="stutop" id="">
    <!-- Create the tabs -->
     <div class="studentsidetopfixed">
    <p class="classtap"><?php echo $student["class"]; ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
    <ul class="nav nav-justified studenttaps">
        <?php foreach ($class_section as $skey => $svalue) {   
         ?>
    <li <?php if($student["section_id"] == $svalue["section_id"]){ echo "class='active'" ; } ?> ><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]); ?></a></li>
   <?php } ?>
    </ul>
</div>
    <!-- Tab panes -->
    <div class="tab-content">
      <?php foreach ($class_section as $skey => $snvalue) {
            ?>
      <div class="tab-pane <?php if($student["section_id"] == $snvalue["section_id"]){ echo "active" ; } ?>" id="section<?php echo $snvalue["section_id"]; ?>">
        <?php foreach ($studentlistbysection as $stkey => $stvalue) {
            if($stvalue['section_id'] == $snvalue["section_id"]){
                ?>
        <div class="studentname">
            <a class="" href="<?php echo base_url()."student/view/".$stvalue["id"] ?>">
        <div class="icon"><img src="<?php echo base_url().$stvalue["image"]; ?>" alt="User Image"></div>
          <div class="student-tittle"><?php echo $stvalue["firstname"]." ".$stvalue["lastname"]; ?></div></a>
        </div>
            <?php
            }
        } ?>
   </div>
   <?php } ?>
     
      <!-- /.tab-pane -->
    </div>
</div>
  </aside>
</div>  
  <!-- /.control-sidebar -->
</div>

    <section class="content">
        <div class="row">
           
            <div class="col-md-9">

                <div class="nav-tabs-custom">
                   
                    <div class="tab-content">
                       
                           

                         
                      
                                        
                        <div class="tab-pane active" id="exam">
                            <div class=""> 
<?php
if (empty($examSchedule)) {
    ?>
                                    <div class="alert alert-danger">
                                        No Exam Found.
                                    </div>
    <?php
} else {
    foreach ($examSchedule as $key => $value) {
        ?>
                                        <h4 class="pagetitleh"><?php echo $value['exam_name']; ?></h4>
                                        <?php
                                        if (empty($value['exam_result'])) {
                                            ?>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_result_prepare'); ?></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="table-responsive borgray around10">  
                                                <div class="download_label"><?php echo $this->lang->line('exam_marks_report'); ?>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover example">
                                                    <thead>
                                                        <tr>
                                                            <th>
            <?php echo $this->lang->line('subject'); ?>
                                                            </th>
                                                            <th>
            <?php echo $this->lang->line('full_marks'); ?>
                                                            </th>
                                                            <th>
            <?php echo $this->lang->line('passing_marks'); ?>
                                                            </th>
                                                            <th>
            <?php echo $this->lang->line('obtain_marks'); ?>
                                                            </th>
                                                            <th class="text text-right">
            <?php echo $this->lang->line('result'); ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
            <?php
            $obtain_marks = 0;
            $total_marks = 0;
            $result = "Pass";
            $exam_results_array = $value['exam_result'];

            $s = 0;
            foreach ($exam_results_array as $result_k => $result_v) {
                $total_marks = $total_marks + $result_v['full_marks'];
                ?>
                                                            <tr>
                                                                <td>  <?php
                                            echo $result_v['exam_name'] . " (" . substr($result_v['exam_type'], 0, 2) . ".) ";
                                            ?></td>
                                                                <td><?php echo $result_v['full_marks']; ?></td>
                                                                <td><?php echo $result_v['passing_marks']; ?></td>
                                                                <td>
                <?php
                if ($result_v['attendence'] == "pre") {
                    echo $get_marks_student = $result_v['get_marks'];
                    $passing_marks_student = $result_v['passing_marks'];
                    if ($result == "Pass") {
                        if ($get_marks_student < $passing_marks_student) {
                            $result = "Fail";
                        }
                    }
                    $obtain_marks = (int) $obtain_marks + (int) $result_v['get_marks'];
                } else {
                    $result = "Fail";
                    echo ($result_v['attendence']);
                }
                ?>
                                                                </td>
                                                                <td class="text text-center">
                <?php
                if ($result_v['attendence'] == "pre") {
                    $passing_marks_student = $result_v['passing_marks'];

                    if ($get_marks_student < $passing_marks_student) {
                        echo "<span class='label pull-right bg-red'>" . $this->lang->line('fail') . "</span>";
                    } else {
                        echo "<span class='label pull-right bg-green'>Pass</span>";
                    }
                } else {
                    echo "<span class='label pull-right bg-red'>" . $this->lang->line('fail') . "</span>";
                    $s++;
                }
                ?>
                                                                </td>
                                                            </tr>
                <?php
                if ($s == count($exam_results_array)) {
                    $obtain_marks = 0;
                }
            }
            ?>
  
              <tr class="hide">
              
                                                <td><?php echo $this->lang->line('exam') . ": " . $value['exam_name']; ?></td>
                                                            <td>
            <?php
            if ($result == "Pass") {
                ?>
                                                                    <b class='text text-success'><?php echo $this->lang->line('result') . ": " . $result; ?></b>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <b class='text text-danger'><?php echo $this->lang->line('result') . ": " . $result; ?></b>
                                                                    <?php
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                echo $this->lang->line('grand_total') . ": " . $obtain_marks . "/" . $total_marks;
                                                                ;
                                                                ?></td>
                                                            <td><?php
                                                                $foo = ($obtain_marks * 100) / $total_marks;
                                                                echo $this->lang->line('percentage') . ": " . number_format((float) $foo, 2, '.', '')."%";
                                                                ?></td>
                                                            <td><?php
                                                                if (!empty($gradeList)) {
                                                                    foreach ($gradeList as $key => $value) {
                                                                        if ($foo >= $value['mark_from'] && $foo <= $value['mark_upto']) {
                                                                            ?>
                                                                            <?php echo $this->lang->line('grade') . " : " . $value['name']; ?>
                                                                            <?php
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                ?></td>

                                                        </tr>

<tr>
    <td><?php echo $this->lang->line('name') . ": " . $student['firstname']." ".$student['lastname']; ?></td>
    <td><?php echo $this->lang->line('roll_no') . ": " . $student['roll_no']; ?></td>
    <td><?php echo $this->lang->line('admission_no') . ": " . $student['admission_no']; ?></td>
    <td><?php echo $this->lang->line('class') . ": " . $student['class']."(".$student["section"].")"; ?></td><td></td>
</tr>
                                                    </tbody>
                                                </table>
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <div class="bgtgray">
            <?php
            $foo = "";
            ?>    

                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('result'); ?>:
                                                                    <span class="description-text">
            <?php
            if ($result == "Pass") {
                ?>
                                                                            <b class='text text-success'><?php echo $result; ?></b>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <b class='text text-danger'><?php echo $result; ?></b>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </h5>
                                                            </div>                                             
                                                        </div>                                     
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?>:
                                                                    <span class="description-text"><?php echo $obtain_marks . "/" . $total_marks; ?></span>

                                                                </h5>
                                                            </div>                                               
                                                        </div> 
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('percentage'); ?>:
                                                                    <span class="description-text"><?php
                                                            $foo = ($obtain_marks * 100) / $total_marks;
                                                            echo number_format((float) $foo, 2, '.', '')."%";
                                                                        ?>
                                                                    </span>
                                                                </h5>
                                                            </div>                                           
                                                        </div>                                          

                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header">
                                                                    <span class="description-text"><?php
                                                            if (!empty($gradeList)) {
                                                                foreach ($gradeList as $key => $value) {
                                                                    if ($foo >= $value['mark_from'] && $foo <= $value['mark_upto']) {
                                                                                    ?>
                                                                                    <?php echo $this->lang->line('grade') . ": " . $value['name']; ?>
                                                                                    <?php
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                        ?></span>
                                                                </h5>
                                                            </div>                                               
                                                        </div>                                         
                                                    </div>
                                                </div>
                                            </div>
        <?php }
        ?>
                                        <?php
                                    }
                                }
                                ?>
                            </div>                      
                        </div></div>
                </div>
                <center><a href="<?php echo base_url('Marksheet/printmarksheet/').$student["id"]  ; ?> "><button class="btn btn-primary">Print Marksheet</button></a></center>
            </div>

    </section>
</div>  
<script type="text/javascript">



    function disable(id,status,role){
        if(confirm("Are you sure you want to disable this record.")){
              var student_id = '<?php echo $student["id"] ?>';
            $.ajax({
            type: "post",
            url: base_url + "student/getUserLoginDetails",
            data: {'student_id': student_id},
            dataType: "json",
            success: function (response) {
           
                    var userid = response.id ;

                 
              
                      changeStatus(userid,'no','student'); 

            }
        });
          
        }else{
            return false ;
        }

    }

    function enable(id,status,role){
        if(confirm("Are you sure you want to enable this record.")){
              var student_id = '<?php echo $student["id"] ?>';
            $.ajax({
            type: "post",
            url: base_url + "student/getUserLoginDetails",
            data: {'student_id': student_id},
            dataType: "json",
            success: function (response) {
             
                    var userid = response.id ;

                  
              
                      changeStatus(userid,'yes','student'); 

            }
        });
          
        }else{
            return false ;
        }

    }

    function changeStatus(rowid, status='no', role='student') {

      //  alert(rowid);
        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/users/changeStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });

    $(document).on('click', '.schedule_modal', function () {
        $('.modal-title_logindetail').html("");
        $('.modal-title_logindetail').html("<?php echo $this->lang->line('login_details'); ?>");
        var base_url = '<?php echo base_url() ?>';
        var student_id = '<?php echo $student["id"] ?>';
        var student_first_name = '<?php echo $student["firstname"] ?>';
        var student_last_name = '<?php echo $student["lastname"] ?>';
        $.ajax({
            type: "post",
            url: base_url + "student/getlogindetail",
            data: {'student_id': student_id},
            dataType: "json",
            success: function (response) {
                var data = "";
                data += '<div class="col-md-12">';
                data += '<div class="table-responsive">';
                data += '<p class="lead text text-center">' + student_first_name + ' ' + student_last_name + '</p>';
                data += '<table class="table table-hover">';
                data += '<thead>';
                data += '<tr>';
                data += '<th>' + "<?php echo $this->lang->line('user_type'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('username'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('password'); ?>" + '</th>';
                data += '</tr>';
                data += '</thead>';
                data += '<tbody>';
                $.each(response, function (i, obj)
                {
                    data += '<tr>';
                    data += '<td><b>' + firstToUpperCase(obj.role) + '</b></td>';
                     data += '<input type=hidden name=userid id=userid value='+obj.id+'>';
                    data += '<td class="text text-center">' + obj.username + '</td> ';
                    data += '<td class="text text-center">' + obj.password + '</td> ';
                    data += '</tr>';
                });
                data += '</tbody>';
                data += '</table>';
                data += '<b class="lead text text-danger" style="font-size:14px;"> ' + "<?php echo $this->lang->line('login_url'); ?>" + ': ' + base_url + 'site/userlogin</b>';
                data += '</div>  ';
                data += '</div>  ';
                $('.modal-body_logindetail').html(data);
                $("#scheduleModal").modal('show');
            }
        });
    });

    function firstToUpperCase(str) {
        return str.substr(0, 1).toUpperCase() + str.substr(1);
    }
</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
  var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $("#timeline_date").datepicker({
              format: date_format,
            autoclose: true

        });
    });
</script>