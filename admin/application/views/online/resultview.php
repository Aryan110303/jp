
<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i>Add Online Exams <small></small></h1>
    </section>
    <script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">        
                 
                       <div class="box-header with-border">     
                        <h3 class="box-title"><?php echo $title; ?></h3>                                  
                       </div>


                       <div class="box-body no-padding">
                       <form action="<?base_url('OnlineTest/result');?>"  method="post" class="" role="form">
                             <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Select Exam : </label>
                                                            <select name="class_id" class="form-control" required  >
                                                            <option value="">SeLect Exam</option>
                                                            <?php foreach ($exams as $key => $exam) { ?> 
                                                             <option value="<?php echo $exam['id'] ; ?>"><?php  echo $exam['examname'] ; ?></option>  
                                                           <?php } ?>                                                              
                                                            </select>   
                                                                                                   
                                            </div>
                                           <button type="submit" class="btn btn-primary btn-sm pull-right checkbox-toggle">  <i class="fa fa-search"></i> Search</button> 
                              </div>
                            

                    </form>
                        </div>
                    
                </div>
            </div>
        </div>  
        <div class="col-md-12">             
                <div class="box box-primary">        
                 
                       <div class="box-header with-border">     
                        <h3 class="box-title"><?php echo $title; ?></h3>                                  
                       </div> 
                       <div class="download_label"><?php echo $title; ?></div>
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                             
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                               <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Student Name</th>
                                    <th>Max Marks</th>
                                    <th>Obtained Marks</th>
                                </tr> 
                               </thead>

                               <tbody id="result">
                               <?if($students!=''){?>
                                   <?php $sno = 1; foreach ($students as $key => $student) { ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo str_replace(',','',$stdnames[$student->student_id]); ?></td>
                                    <td><?php echo $max_marks[$student->student_id]; ?></td>
                                    <td><?php echo $obtained_marks[$student->student_id]; ?></td>
                                </tr> 
                                <?php } }  ?>
                            </tbody>
                        </table>
                        
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


function YouTubeGetID(url) {
    var ID = '';
    url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
    if (url[2] !== undefined) {
        ID = url[2].split(/[^0-9a-z_\-]/i);
        ID = ID[0];
    } else {
        ID = url;
    }
    return ID;
}
 

 

function InsertHTML(content_html) {
    // Get the editor instance that we want to interact with.
    var editor = CKEDITOR.instances.editor1;


    // Check the active editing mode.
    if (editor.mode == 'wysiwyg')
    {
        editor.insertHtml(content_html);
    } else
        alert('You must be in WYSIWYG mode!');
}


$(document).on('keyup change', '#title', function () {
    var str = $(this).val();

    var url = string_to_slug(str);
    $('#url').val(url);
});

function string_to_slug(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to = "aaaaeeeeiiiioooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

    return str;
}


function getresult(value) {
     $.ajax({
       type: 'POST',
       url: "<?php echo base_url('OnlineTest/fetchResult/')?>"+value,
       dataType: "text",
       success: function(resultData) {
        $('#result').html(resultData);
        }
    });
    
    }

</script>
