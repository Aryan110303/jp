<div class="content-wrapper">  
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> Transfer Certificate Form
		</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="padding: 5px;">
                    <form method="post" action="<?php echo base_url('transfercertificate/create') ?>">
                        <div class="apk-block">
                            <h4 class="form-heading">APPLICATION FOR TRANSFER CERTIFICATE</h4>

                            <div class="ftop" >
                                <p> To,</p>
                                <p>The Principal</p>
                                <p> Little Kingdom Sr. Sec. School</p>
                                <p> Ramnagar, Adhartal, Jabalpur (M.P.)</p>
                                <p>Please furnish me with the Transfer Certificate of my Son/Daughter. The necessary particulars are given below-</p>
                            </div>

                            <div class="row fform">
                            <div class="col-md-6">
                                <input type="hidden" name="studentId" value="<?php echo $result['id'] ?>">
                                <input type="text" class="form-control" value="<?php echo $result['admission_no'] ?>" placeholder="admission no.">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Branch">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" value="<?php echo $result['firstname'] ?> <?php echo $result['lastname'] ?>" placeholder="Full name of student">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" value="<?php echo $result['father_name'] ?>" placeholder="Father’s Name ">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo $result['mother_name'] ?>" class="form-control" placeholder="Mother's Name ">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo date('d-m-Y',strtotime($result['dob'])) ?>" class="form-control" placeholder="Date of Birth (Figures):">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo date('D F Y',strtotime($result['dob'])) //$result['father_name'] ?>" class="form-control" placeholder="Word">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo $result['cast'] ?>" class="form-control" placeholder="Cast (SC/ST/OBC/GEN) :">
                            </div>

                            <div class="col-md-12">
                                <input type="text" value="<?php //echo $result['class'] ?>" class="form-control" placeholder="Class in which admission taken : ">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo $result['class'] ?>" class="form-control" placeholder="Class in which studying :">
                            </div>
                            <div class="col-md-12">
                                <input type="text" value="<?php echo date('d-m-Y') ?>" class="form-control" placeholder="Date of leaving school :">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Reason for leaving :" name="reason">
                            </div>

                            <div class="col-md-7">
                                <input type="text" class="form-control" placeholder="Name & Signature of parents">
                            </div>

                            <div class="col-md-5">
                                <input type="text" value="<?php echo $result['mobileno'] ?>" class="form-control" placeholder="Mobile no.">
                            </div>

                                <h4 class="col-md-12 form-heading">FOR OFFICE USE ONLY</h4>
                                <div class="col-md-12">
                                    <div class="col-md-6 ">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="checkFee" value="">All the fees due have been paid: </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Treasurer :" name="treasure">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-6 ">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="cancelName" value="">Name has been cancelled from the class:  </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="teacher" placeholder="Class Teacher: ">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-6 ">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="bookReturn" value="">All books returned : </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="librarian" placeholder="Librarian :">
                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-6 ">
                                    The T.C. May be issued
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="principal" placeholder="Principal :">
                                </div>

                                </div>

                                <div class="col-md-12 nblock">
                                    <h4 class="col-md-12 form-heading">N.B.</h4>
                                    <p class="nblist">
                                        1.  No transfer certificate is given until the dues to the school have been paid in full.
                                    </p>
                                    <p class="nblist">
                                        2.  Transfer Certificate will be posted to the given address if a stamped envelope is supplied together with this application form.
                                    </p>
                                </div>

                                <h4 class="col-md-12 form-heading">(PARENTS COPY)</h4>

                                <div class="col-md-12">
                                    <input type="text" class="pcfield" value="<?php echo date('d-m-Y') ?>" placeholder=" Application received for the Transfer Certificate on date">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="pcfield" value="<?php echo $result['firstname'] ?> <?php echo $result['lastname'] ?>" placeholder="Name of student">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="text" class="pcfield" placeholder="Parents Sign">
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="pcfield" placeholder="Office Incharge">
                                </div>

                                <div class="col-md-12" id="submitBtnDiv">
                                    <button type="submit" class="btn btn-primary center-block">Submit</button>
                                </div>

                            </div>



                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>    