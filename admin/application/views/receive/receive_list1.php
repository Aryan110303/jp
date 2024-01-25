<div class="content-wrapper" style="min-height: 946px;">
      <section class="content-header">
        <h1>
            Receiver List          
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
                                <?php echo anchor(site_url('receive/create'), 'Create', 'class="btn btn-primary"'); ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped" id="mytable">
                            <thead>
                                <tr>
                                    <th>Child Name</th>
                                    <th>Receiver Name</th>
                                    <th>Relation With Child</th>
                                    <th>Mobile No</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($rec)) {

                                    foreach ($rec as $value) {
                                        ?>

                                        <tr>
                                    <!--<td><?php echo ++$start; ?></td>-->
                                            <td><?php echo $value->child_id;?></td>
                                            <td><?php echo $value->name;?></td>
                                            <td><?php echo $value->relation;?></td>
                                            <td><?php echo $value->mobile_no;?></td>
                                             <td><?php echo $value->reason;?></td>
                                            <td><img src="<?php echo $value->image?>" height="100" width="100"></td>
                                            <td style="text-align:center" width="200px">

                                                <?php
                                                echo anchor(site_url('receive/read/' . $value->id), '<i class="fa fa-eye" aria-hidden="true"></i>');
                                                echo ' | ';
                                                echo anchor(site_url('receive/update/' . $value->id), '<i class="fa fa-pencil-square-o"></i>');
                                                echo ' | ';
                                                echo anchor(site_url('receive/delete/' . $value->id), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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