<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i>Online Exams <small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3> <hr>
  <br>                                   <a href="<?php echo  base_url('OnlineTest/createexam'); ?>"  class="btn btn-success"><b>Create Exam</b></a>   
                                         <a href="<?php echo  base_url('OnlineTest/createexamquestions'); ?>"  class="btn btn-success"><b>Add Questions</b></a>
                       </div>
                       <div class="download_label"><?php echo $title; ?></div>
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                             
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                               <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Exam</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Max Attempt</th>
                                    <th>Date</th>
                                </tr> 
                               </thead>

                               <tbody id="result">
                               
                                   <?php $sno = 1;  foreach ($exams as $exam) { ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $exam['examname']; ?></td>
                                    <td><?php echo $exam['class']; ?></td>
                                    <td><?php echo $exam['subject'] . "(" . $exam['topic'] . ")"; ?></td>
                                    <td><?php echo $exam['max_attempt']; ?></td>
                                    <td><?php echo ' From - '.$exam['startDate'] .' , To - '.$exam['endDate']; ?></td>
                                </tr> 
                                <?php }  ?>
                            </tbody>
                        </table>
                        
                    </div>
                    <!-- <div class="box-body no-padding"> -->
                        <?php
                      
                        // foreach ($exams as $exam) {
                            ?>
                            <!-- <div class="row carousel-row">
                                <div class="col-xs-8 col-xs-offset-2 slide-row">
                                    
                                    <div class=" "> -->
                                        <!-- <h4><?php //echo $exam['examname'] ;?></h4>
                                        <h4><?php //echo $exam['class'] ;?></h4>
                                        <h4><?php //echo $exam['subject'] . "(" . $exam['topic'] . ")" ?></h4> -->
                                        <!-- <address>                                            -->
                                            <b><?php //echo "Max Attempt"; ?>: </b><?php //echo $exam['max_attempt'] ; ?>  <br>                                   
                                            <b><?php //echo "Date"; ?>: </b>  <?php //echo ' From -'.$exam['startDate'] .' - To '.$exam['endDate'] ;?><br>
                                            <!-- <a href="<?php //echo base_url('OnlineTest/deleterecord/').$exam['id']; ?>"  class="btn btn-primary"><b> Delete Exam  </b></a>                                                                                        -->
                                       <!-- </address>  -->
                                    <!-- </div>
                                   
                                </div>
                            </div> -->
                            <?php
                             
                        // }
                        ?>
                    </div>
                    
                </div>
            </div>
        </div>       
    </section>
</div>
