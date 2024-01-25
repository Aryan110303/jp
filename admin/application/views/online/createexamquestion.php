<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i>Add Online Exams <small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">        
                 
                       <div class="box-header with-border">     
                        <h3 class="box-title"><?php echo $title; ?></h3>                                  
                       </div>
                       <div class="box-body no-padding">
                                        <form action="<?base_url('OnlineTest/createexamquestions');?>"  method="post" class="" role="form">
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Exam : </label>
                                                                                <select name="exam_id" class="form-control" required onchange="getquestions(this.value)">
                                                                                <option value="">SeLect Exam</option>
                                                                                <?php foreach ($exams as $key => $exam) { ?> 
                                                                                <option value="<?php echo $exam['id'] ; ?>"><?php  echo $exam['examname'] ; ?></option>  
                                                                            <?php } ?>
                                                                                
                                                                                </select>                                                 
                                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Question : </label>
                                                                                <textarea name="question" id="" cols="80" rows="5"></textarea>                                             
                                                                </div>
                                                </div>
                                            
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Option A : </label>
                                                                                <input type="text" name="a" required="required" class="form-control">                                                   
                                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Option B  : </label>
                                                                                <input type="text" name="b" required="required" class="form-control">                                                   
                                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Option C  : </label>
                                                                                <input type="text" name="c" required="required" class="form-control">    
                                                                                                                        
                                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Option D : </label>
                                                                                <input type="text" name="d" required="required" class="form-control">                                                  
                                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Correct Answer : </label>
                                                                                <select name="answer" class="form-control" required >
                                                                                <option value="a">A</option>
                                                                                <option value="b">B</option>
                                                                                <option value="c">C  </option>
                                                                                <option value="d">D  </option>
                                                                                
                                                                                
                                                                                </select>                                                  
                                                                </div>
                                                </div>
                                                <div class="col-sm-4">
                                                                
                                                                                                            
                                                                            <button type="submit" class="btn btn-success" >Add Question</button>                          
                                                                
                                                </div>

                                        </form>
                      </div>
                      <hr>
                     <center>-OR-</center> 
                      <div class="box-body no-padding">
                     <form method="post" id="import_form" enctype="multipart/form-data"  class="" role="form">
                                                  <div class="col-sm-6">
                                                                <div class="form-group">                                                     
                                                                                <label for="examname">Exam : </label>
                                                                                <select name="exam_id" class="form-control" required onchange="getquestions(this.value)">
                                                                                <option value="">SeLect Exam</option>
                                                                                <?php foreach ($exams as $key => $exam) { ?> 
                                                                                <option value="<?php echo $exam['id'] ; ?>"><?php  echo $exam['examname'] ; ?></option>  
                                                                            <?php } ?>
                                                                                
                                                                                </select>                                                 
                                                                </div>
                                                      </div>
                    
                       <div class="col-sm-6">
                            <div class="form-group">                                 
                                  <label>Select Excel File</label>  
                             <input class="filestyle form-control" type='file' name='file' id="file"  />                       
                             </div>
                            </div>
                         
                        <div class="col-sm-6">      
                        <div class="form-group"> 
                             <input type="submit" name="import" value="Import" class="btn btn-info" />
                        </div>
                        </div>
                              
                    </form>
                     </div>

                    <hr>
                    <div class="box-header with-border"> 
                          <h3 class="box-title">Questions List</h3>  
                          <div id="questionlist"> No Question Are in List</div>           
                    </div>

                </div>
            </div>
        </div>       
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
           $('#dob,#admission_date').datepicker({
                       autoclose: true
             });
     })
    </script>
    
    <script>
    function getquestions(value) {
     $.ajax({
      type: 'POST',
      url: "<?php echo base_url('OnlineTest/getquestions/')?>"+value,
      dataType: "text",
      success: function(resultData) {
        $('#questionlist').html(resultData);
       }
});
    }
    </script>
    
    <script>
$(document).ready(function(){

	load_data();

	function load_data()
	{
		$.ajax({
			url:"<?php echo base_url(); ?>OnlineTest/fetch",
			method:"POST",
			success:function(data){
				$('#customer_data').html(data);
			}
		})
	}

	$('#import_form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"<?php echo base_url(); ?>OnlineTest/import_excel",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			success:function(data){
				$('#file').val('');
				load_data();
				alert(data);
			}
		})
	});

});
</script>
