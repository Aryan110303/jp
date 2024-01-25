
 <style type="text/css" src="  <script type="text/javascript" src="cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" ></style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> All SMS List <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
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
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> Sms List </h3>
                                </div>                              
                                <div class="box-body table-responsive" >
                                   <div class="download_label"><?php echo $this->lang->line('student_fees_report'); ?></div>  
                                    <table  class="table table-striped table-bordered table-hover " id="myTable">
                                        <thead>
                                            <tr>
                                                   <th class="text text-left">S. no</th>
                                                    <th class="text text-left">Sent Date</th>
                                                    <th class="text text-left">Message</th>
                                                    <th class="text text-left">Status</th>
                                                   
                                            </tr>
                                        </thead>  
                                        <tbody>
                                          
                                        <?php $s = 1;
                                        
                                        foreach ($msg as $key => $value) {
                                             

                                         // print_r($details); die;
                                                
                                            ?>
                                             <tr>
                                             <td><?= $s++ ?></td>
                                              <td><?= $value['message']?></td>
                                             <td><?= $value['created_at']?></td>
                                              <td><?php if ($value['sms_status'] == 1) {
                                               echo "Delivered" ;
                                              } 
                                              else echo  "Pending" ;?></td>
                                            
                                              </tr>
                                            <?php
                                        } 

                                        foreach ($msg1 as $val) {
                                              
                                            ?>
                                             <tr>
                                             <td><?= $s++ ?></td>
                                              <td><?= $val['message']?></td>
                                             <td><?= $val['created_at']?></td>
                                              <td><?php if ($val['sms_status'] == 1) {
                                               echo "Delivered" ;
                                              } 
                                              else echo  "Pending" ;?></td>
                                            
                                              </tr>
                                            <?php
                                        }
                                       ?>  
                                               </tbody> 
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
