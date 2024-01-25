


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Receiver Form              
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?php echo $action ?>" method="post" >
                            <div class="col-md-3">
                                <input class="form-control" type="text" placeholder="Decription here" name="desc"> 
                            </div>
                             <div class="col-md-3">
                                <input class="form-control" type="text" name="int_date" placeholder="Select Interview date" id="int_date"> 
                            </div>    
                             <div class="col-md-3">
                                <input class="form-control" type="number" min="1" name="int_count" placeholder="Count of Student" > 
                            </div>    
                             <div class="col-md-3">
                                <input type="submit" value="ADD" class="btn btn-primary"> 
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
                            $(document).ready(function () {
                                $("#int_date").datepicker({ 
                                    format: 'dd-mm-yyyy'
                                    
                                     });
                                
                            });
                        </script>
    