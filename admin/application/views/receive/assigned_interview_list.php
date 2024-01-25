<div class="content-wrapper" style="min-height: 946px;">
  <section class="content-header">
    <h1>
     Assigned Student List
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
                                <th>Reciept No</th>
                                <th>Registration No</th>
                                <th>Name</th>
                                <th>Father Name</th>                                    
                                <th>Class</th>                                     
                                <th>Entrance Exam Date</th>         
                                <th>Result</th>
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
                                        <td><?php echo $value->reciept_number;?></td>
                                        <td><?php echo $value->registration_form_no;?></td>
                                        <td><?php echo $value->appli_name;?></td>
                                        <td><?php echo $value->father_name;?></td>
                                        <td><?php echo $value->class;?></td>
                                        <td><?php echo $value->ent_date;?></td>
                                        <td>
                                          <?php
                                          if ($value->result == 2) {                                        
                                             echo '<strong style = "color:green"> Passed</strong>';
                                                 }
                                          elseif($value->result == 1){
                                                 echo '<strong style = "color:red"> Failed</strong>';

                                          }else{
                                             echo anchor(site_url('receive/updateresult/1/' .$value->id.'/'.$value->ent_date), '<p><i style="color:red;" aria-hidden="true"> Fail</i></p>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');   
                                            
                                              echo anchor(site_url('receive/updateresult/2/' .$value->id.'/'.$value->ent_date), '<p><i style="color:Green;"  aria-hidden="true">Pass</i></p>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');  
                                          }
                                              ?>
                                          
                                          
                                          </td>
                                    
                                         </tr>
                        <?php }   }?>
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