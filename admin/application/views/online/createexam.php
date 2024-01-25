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
                       <form action="<?base_url('OnlineTest/createexam');?>"  method="post" class="" role="form">
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Exam Name : </label>
                                                            <input type="text" name="examname" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Class : </label>
                                                            <select name="class_id" class="form-control" required >
                                                            <option value="">SeLect Class</option>
                                                            <?php foreach ($classlist as $key => $class) { ?> 
                                                             <option value="<?php echo $class['id'] ; ?>"><?php  echo $class['class'] ; ?></option>  
                                                           <?php } ?>
                                                              
                                                            </select>                                                 
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Subject : </label>
                                                            <input type="text" name="subject" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Topic : </label>
                                                            <input type="text" name="topic" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Max Attempt : </label>
                                                            <input type="number" min='1' name="max_attempt" value="1" required="required" class="form-control">   
                                                                                                       
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">StartDate : </label>
                                                            <input type="text" id="dob"  name="startDate" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">EndDate : </label>
                                                            <input type="text"  id="admission_date" name="endDate" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                                            <label for="examname">Total Time : <small>In Minute</small></label>
                                                            <input type="number" min=0   id="time" name="time" required="required" class="form-control">                                                   
                                            </div>
                              </div>
                              <div class="col-sm-4">
                                            <div class="form-group">                                                     
                                        <label for="examname"> Exam Note </label>
                                         <textarea id="editor1" name="note" placeholder="" type="text" class="form-control" ><?php echo set_value('note'); ?></textarea>                                           
                                            </div>
                              </div>
                              <div class="col-sm-4">                                    
                                     <button type="submit" class="btn btn-success" >Add Exam</button>                          
                             </div>

                    </form>
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

$(document).ready(function () {
    var popup_target = 'media_images';
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

    $('.date').datepicker({
        //  format: "dd-mm-yyyy",
        format: date_format,
        autoclose: true
    });

    CKEDITOR.replace('editor1',
            {
             allowedContent: true
            });
  
   

   




 

    
});

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
</script>
