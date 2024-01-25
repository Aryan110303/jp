
 <script type="text/javascript" src="cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" ></script>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> SMS Number Reports <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <style type="text/css">
        .wid{
            width: 200px;
        }
        .modalscroll{overflow-y: hidden; overflow-y: scroll; height: 300px; width: 100%; }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                    </div>
                   
                                    </div>
                
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-info" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> Sms Number's Report </h3>
                                </div>                              
                                <div class="box-body table-responsive" >
                                   <div class="download_label"><?php echo $this->lang->line('student_fees_report'); ?></div>  
                                    <table  class="table table-striped table-bordered table-hover " id="myTable">
                                        <thead>
                                            <tr>
                                                   <th class="text text-left">S. no</th>
                                                   <th class="text text-left">Number</th>
                                                   <th class="text text-left">Student Name</th>
                                                   <th class="text text-left">Class</th>
                                                   <th class="text text-left">Section</th>
                                                   <th class="text text-left">Sent Date-Time</th>
                                                   <th class="text text-left">Delivered Date-Time</th>
                                                   <th class="text text-left">Status</th>
                                                   <th  class="text text-center">View Details</th>
                                            </tr>
                                        </thead>  
                                        <tbody>
                                          
                                        <?php $s = 1;
                                        
                                        foreach ($num as $key => $value) {
                                                $details = $this->Classteacher_model->get_data_by_query("SELECT * from students  where guardian_phone = '$value'  ") ;
                                                if (empty($details)) {
                                                   
                                                $details = $this->Classteacher_model->get_data_by_query("SELECT * from students  where mobileno = '$value'  ") ;
                                                }

                                                $class_details =  $this->Classteacher_model->get_data_by_query("SELECT * from student_session  where student_id = '".$details[0]['id']."'  ") ;

                                         // print_r($details); die;
                                                
                                            ?>
                                             <tr>
                                             <td><?= $s++ ?></td>
                                             <td><?= $value?></td>
                                             <td><?= $details[0]['firstname'].' '.$details[0]['lastname']   ?></td>
                                             <td><?=  $this->Classteacher_model->findfield('classes','id',$class_details[0]['class_id'],'class'); ?></td>
                                             <td><?=  $this->Classteacher_model->findfield('sections','id',$class_details[0]['class_id'],'section'); ?></td>
                                             <td> <?= $num_details[0]['created_at'] ?></td>
                                              <td class="text-center"> <?php if ($num_details[0]['updated_at']  == '0000-00-00 00:00:00') {
                                                 echo '--' ;
                                              } else 
                                               echo $num_details[0]['updated_at'] ; ?></td>
                                              <td> <?php if ($num_details[0]['sms_status'] == '1') {
                                                  echo '<b style="color:green">Sent</b>'  ;
                                              } else {
                                                 echo '<b style="color:red">Pending</b>' ;
                                              }
                                               ?></td>
                                               <td class="text-center"><a href="<?php echo base_url('admin/Smslist/all_message').'/'.$value ; ?>"><i class="fa fa-eye"></i></a></td>
                                              </tr>
                                            <?php
                                        } 


                                       ?>          </tbody> 
                                    </table>
                                </div>                            
                            </div>                       
                        </div>
                    </div>
                
                </section>
            </div>


            <script type="text/javascript" src="cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>

    $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
