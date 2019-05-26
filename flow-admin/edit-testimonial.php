<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();


$id = $cFunc->DecryptClientId($_REQUEST['id']);
$content = $obj->get_row_by_id('op2mro9899_testimonial', 'id', intval($id));

$user_title = $content[0]['user_title'];
$user_content = $content[0]['user_content'];
$created_date = $content[0]['created_date'];
$created_ip = $content[0]['created_ip'];

if(isset($_POST['user_titles'])){
	$user_titles = $cFunc->xss_clean($_POST['user_titles']);
	$user_contents = addslashes($_POST['user_contents']);
	$post_arr = array('user_title' => $user_titles, 'user_content' => $user_contents);
	$update_stmt = $obj->update_row('op2mro9899_testimonial', $post_arr, "WHERE id = '$id'" );
	if($update_stmt){
		header("location:all-testimonial.php");
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
                        <input class="form-control validate" id="user_title" name="user_titles" value="<?php echo $user_title; ?>" placeholder="Enter Title" type="text">
                        <ul class="error" style="display:none;"><li>Please enter page name</li></ul>
                    </div>
                    <div class="form-group">
                        <label for="pageContent">Content</label>
                        <textarea class="form-control" name="user_contents"><?php echo $user_content ?></textarea>
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
	CKEDITOR.replace( 'user_contents' );
</script>

<!-- content ends -->
</div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
<hr>


<?php include_once 'footer.php'; ?>
