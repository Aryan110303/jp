<div class="content-wrapper" style="min-height: 946px;">
      <section class="content-header">
        <h1>
            Interview Scheduler      
            <small></small>
        </h1>
    </section>
    <!-- Main content -->
 <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- ******************/master header end ****************** -->
                      <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4 text-center">
                                <div style="margin-top: 4px"  id="message">
                                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php echo anchor(site_url('receive/add_interview'), 'Create', 'class="btn btn-primary"'); ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped" id="mytable">
                            <thead>
                                <tr><th>S no.</th>
                                    <th>Description</th>
                                    <th>Date</th>                                    
                                    <th>Number Of Student For Interview</th>                                     
                                    <th>Assigned Count</th>                                     
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($rec)) {
                                                $start = 0 ;
                                    foreach ($rec as $value) {
                                        ?>

                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td><?php echo $value->description;?></td>
                                            <td><?php echo $value->int_date;?></td>
                                            <td><?php echo $value->count;?></td>
                                            <td>
                                               <a href="<?php echo base_url("receive/assigned_studentlist/").$value->int_date ; ?>" > <b>
                                                   
                                               <?php   $vl = $this->Receive_model->assigned_data($value->int_date);
                                                            if ($vl) {
                                                                echo $vl ;
                                                            }else echo '0' ;
                                                     ?></b>
                                                     </a> 

                                                 </td>
                                            <td >  
                                                <?php
                                             
                                              
                                             /*   echo anchor(site_url('receive/update/' . $value->id), '<b><i class="fa fa-pencil-square-o"></b></i>');*/
                                              
                                                echo anchor(site_url('receive/deleteinterview/' . $value->id), '<b><i style="color:red;" class="fa fa-trash-o" aria-hidden="true"></i></b>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            }
                                            ?> 
                                        </td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>
                        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
                        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
                        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#mytable").dataTable();
                            });
                        </script>
                        <!-- ******************/master footer ****************** -->
                    </div>
                </div>
            </div>
    </section>
</div>