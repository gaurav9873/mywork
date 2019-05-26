<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();


if(isset($_POST['user_title'])){
	$user_title = $customObj->xss_clean($_POST['user_title']);
	$user_content = addslashes($_POST['user_content']);
	$created_ip = $customObj->get_client_ip();
	$date = date("Y-m-d H:i:s");
	
	$testimonial_args = array('user_title' => $user_title, 'user_content' => $user_content, 'created_date' => $date, 'created_ip' => $created_ip);
	$ins_stmt =  $obj->insert_records('op2mro9899_testimonial', $testimonial_args);
	if($ins_stmt){
		header("location:add-testimonial.php?msg=success");
	}
}

?>


<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Add Testimonial</h2>
            </div>
            <div class="box-content">
                <form name="pfrm" id="pfrm" action="" method="post" role="form">
                    <div class="form-group">
                        <label for="text">Title</label>
                        <input class="form-control validate" id="user_title" name="user_title" placeholder="Enter Title" type="text">
                        <ul class="error" style="display:none;"><li>Please enter page name</li></ul>
                    </div>
                    <div class="form-group">
                        <label for="pageContent">Content</label>
                        <textarea class="form-control" name="user_content"></textarea>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'user_content' );
</script>

<!-- content ends -->
</div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
<hr>


<?php include_once 'footer.php'; ?>
