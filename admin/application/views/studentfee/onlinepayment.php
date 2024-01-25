<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>                          
                            <?php echo $this->lang->line('search'); ?>
                            <?php echo $this->lang->line('fees'); ?>
                            <?php echo $this->lang->line('payment'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="<?php echo site_url('studentfee/onlineTransactionReport') ?>" method="post" class="form-inline">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>Date From
                                            </label><small class="req"> *</small>
                                            <input  id="datefrom" name="datefrom"  type="text" class="form-control date" />
                                            <span class="text-danger"><?php echo form_error('datefrom');?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Date To
                                            </label><small class="req"> *</small>
                                            <input  id="dateto" name="dateto"  type="text" class="form-control date" />
                                            <span class="text-danger"><?php echo form_error('dateto');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle mmius15"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($transactions)) {
                    ?>
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('payment_id_detail'); ?></h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body table-responsive">


                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr >                                     
                                        <th>Scholar No.</th>
                                        <th>Student Name</th>
                                        <th>Class/Section</th>     
                                        <th>Paytm Transaction ID</th>                                  
                                        <th>Date</th>
                                        <th>Amount</th>                                       
                                        <th>Bank TransactionID</th>
                                        <th>BankName</th>
                                        <th>Payment Status</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $amount = 0;
                                    $discount = 0;
                                    $fine = 0;
                                    $total = 0;
                                    $grd_total = 0;
                                    if (empty($transactions)) {
                                        ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($transactions as $key => $transaction) {
                                            if($transaction->status == "TXN_FAILURE"){
                                                $color = "#ec5837c7";
                                            }
                                            else{
                                                $color = "#45ffbac7";
                                            }
                                            ?>
                                        <tr style="background-color: <?php echo $color;?>">
                                            <td>
                                                <?php echo $transaction->admission_no; ?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->firstname . " " . $transaction->lastname; ?>
                                            </td>
                                            <td>
                                                <?php echo $transaction->class . " (" . $transaction->section . ")"; ?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->txnid; ?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->txndate; ?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->txnamount;?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->banktxnid;?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->bankname;?>
                                            </td> 
                                            <td>
                                                <?php echo $transaction->status;?>
                                            </td> 
                                           
                                        </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <?php
                }
                ?>
            </div>
        </div> 
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
      
        $(".date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>