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
                         <!--   <a href="<?php echo  base_url('student/classroomdeleteall'); ?>"  class="btn btn-danger"><b>Remove All Rooms </b></a>
                          -->  
                           <a href="<?php echo  base_url('student/createclassroom'); ?>"  class="btn btn-success"><b>Create Room</b></a>
                       </div>
                    <div class="box-body no-padding">
                        <?php
                      
                        foreach ($classrooms as $classroom) {
                            ?>
                            <div class="row carousel-row">
                                <div class="col-xs-8 col-xs-offset-2 slide-row">
                                    
                                    <div class="slide-content">
                                        <h4><?php echo $classroom['title'] . "(" . $classroom['description'] . ")" ?></h4>
                                        <address>
                                           
                                            <b><?php echo "Status"; ?>: </b><?php echo ($classroom['classroom_active']==0)?"ROOM IS INACTIVE":"ROOM IS ACTIVE" ?>                                     
                                            <b><?php echo "Period"; ?>: </b>  <?php echo $classroom['lecture_id'] ?><br>
                                            <?php
                                            
                                            if ($classroom['end_classAt']){ ?>
                                                <strong>
                                                    CLass Room End At <?php echo date('d-m-Y h:i:s',strtotime($classroom['end_classAt'])) ; ?>
                                                </strong>
                                            <?php }else{
                                            if (empty($classroom['classroomlink'])) {?>
                                                        <a href="http://jpacademymeerut.com/admin/mobileapp/Android_api/startClassRoomadmin/<?php echo $classroom['id'] ?>"   class="btn btn-success"><b><?php echo "Start Class"; ?>  </b></a>
                                                        <?php   }else{?>
                                                        <a href="<?php echo $classroom['classroomlink']; ?>"   class="btn btn-success"><b><?php echo "Re-Join Now"; ?>  </b></a>
                                                        <a href="http://jpacademymeerut.com/admin/mobileapp/Android_api/endClassRoomteacheradmin/<?php echo $classroom['teacher_id'].'/'.$classroom['id'] ?>"  class="btn btn-primary"><b><?php echo "End CLass Room"; ?>  </b></a>
                                           <?php  } }?>
                                               <!-- <a href="<?php echo base_url('student/classroomdelete/').$classroom['id']; ?>" target="_blank" class="btn btn-danger"><b><?php echo "Remove "; ?> </b></a>
                                                                               -->
                                                                               </address> 
                                    </div>
                                   
                                </div>
                            </div>
                            <?php
                             
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
        </div>       
    </section>
</div>
