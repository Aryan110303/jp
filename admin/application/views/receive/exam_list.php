<div class="content-wrapper" style="min-height: 946px;">
      <section class="content-header">
        <h1>Exam List         
            <small><a href="<?php base_url();?>" >Home</a></small>
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
                          <!--   <div class="col-md-4 text-right">
                                <?php //echo anchor(site_url('receive/create'), 'Create', 'class="btn btn-primary"'); ?>
                            </div> -->
                        </div>
                        <div class="download_label"><?php echo $this->lang->line('exam_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($examlist)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($examlist as $exam) {
                                            ?>

                                            <tr>

                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover" ><?php echo $exam['name'] ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($exam['note'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $exam['note']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('exam', 'can_edit')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/exam/edit/<?php echo $exam['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>receive/exam_classes/<?php echo $exam['id'] ?>"class="btn btn-info btn-xs">
                                                        <?php echo $this->lang->line('view_status'); ?></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
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