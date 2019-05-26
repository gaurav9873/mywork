<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');

if(isset($_POST['submit'])){
	$page_name = $cFunc->xss_clean($_POST['page_name']);
	$page_content = addslashes($_POST['page_content']);
	$short_note = addslashes($_POST['short_note']);
	$created_ip = $cFunc->get_client_ip();
	$date = date("Y-m-d H:i:s");
	$chk_enable = array_filter($_POST['chk_enable']);
	
	
	//Header Banner
	$header_banner = $_FILES["header_banner"]['name'];
	$header_banner_tmp = $_FILES["header_banner"]['tmp_name'];
	//print_r($header_banner); die;
	
	//Footer Banner
	$footer_banner = $_FILES["footer_banner"]['name'];
	$footer_banner_tmp = $_FILES["footer_banner"]['tmp_name'];
	
	//Page Content Image
	$pn_image = $_FILES["pn_image"]['name'];
	$pn_image_tmp = $_FILES["pn_image"]['tmp_name'];
	
	//Short Content Image
	$sn_image = $_FILES["sn_image"]['name'];
	$sn_image_tmp = $_FILES["sn_image"]['tmp_name'];
	
	
	$header_path = '';
	//Header Banner
	if($header_banner!=''){
		$target_dir = "uploads/slider/banner/";
		$target_file = time().rand(290,7689).'.'.@end(explode('.',$header_banner));
		//print_r($target_file); die;
		$up_image = move_uploaded_file($header_banner_tmp,$target_dir.$target_file);
		$header_path = $target_dir.$target_file;
		
	}
	
	$footer_path = '';
	//Footer Banner
	if($footer_banner!=''){
		$targetDir = "uploads/slider/banner/";
		$targetFile = time().rand(290,7689).'.'.@end(explode('.',$footer_banner));
		$upImage = move_uploaded_file($footer_banner_tmp,$targetDir.$targetFile);
		$footer_path = $targetDir.$targetFile;
		
	}
	
	$pn_path = '';
	//Page Content Image Upload
	if($pn_image!=''){
		$pntargetDir = "uploads/slider/banner/";
		$pntargetFile = time().rand(290,7689).'.'.@end(explode('.',$pn_image));
		$pnupImage = move_uploaded_file($pn_image_tmp,$pntargetDir.$pntargetFile);
		$pn_path = $pntargetDir.$pntargetFile;
		
	}
	
	$sn_path = '';
	//Short Content Image Upload
	if($sn_image!=''){
		$sntargetDir = "uploads/slider/banner/";
		$sntargetFile = time().rand(290,7689).'.'.@end(explode('.',$sn_image));
		$snupImage = move_uploaded_file($sn_image_tmp,$sntargetDir.$sntargetFile);
		$sn_path = $sntargetDir.$sntargetFile;
		
	}
	
	
	
		
	$page_arr = array('page_name' => $page_name, 'page_content' => $page_content, 'pc_image' => $pn_path, 'short_description' => $short_note, 'sd_image' => $sn_path,
					  'header_banner' => $header_path, 'footer_banner' => $footer_path, 'created_date' => $date, 'created_ip' => $created_ip);
	$ins_stmt =  $obj->insert_records('op2mro9899_pages', $page_arr);
	if($ins_stmt){
		foreach($chk_enable as $site_val){
			$pArr = array('page_id' => $ins_stmt, 'site_id' => $site_val);
			$page_stmt =  $obj->insert_records('op2mro9899_pages_relation', $pArr);
		}
		
		header("location:all-pages.php?msg=success");
	}
}
?>

<script>
$(document).ready(function(){
	
	$("#pfrm").on('submit', function(evt){
		errFlag = false;
		$(".validate").each(function(){
			if($(this).val()==''){
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').show();
				errFlag = true
			}else{}
			
		});
		
		
		var chk_length = $(".check-tiggle:checked").length
		if(chk_length < 1){
			$(".error").show();
		}
		if(chk_length >0){
			$(".error").hide();
		}
		
		$(".validate").on('keypress change',function(){
			if($(this).val() ==''){
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').show();
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
				$(this).parent().find('.error').hide();
			}
		});
		
		if(errFlag){
			evt.preventDefault();
		}
		
	});
});
</script>

<style>
.collectionOfFOrmGroup .form-group {
    background: #f4f4f4 none repeat scroll 0 0;
    border-bottom: 2px solid #e8e8e8;
    float: left;
    margin: 0 5px;
    padding: 10px 20px;
    width: 49.3%;
}
.collectionOfFOrmGroup {
    float: left;
    margin-bottom: 20px;
    width: 100%;
}
.error{
	color:red;
}
</style>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Add Pages</h2>
            </div>
            <div class="box-content">
                <form name="pfrm" id="pfrm" action="" method="post" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="text">Page Name</label>
                        <input class="form-control validate" id="page_name" name="page_name" placeholder="Enter Page Name" type="text">
                        <ul class="error" style="display:none;"><li>Please enter page name</li></ul>
                    </div>
                    <div class="form-group">
                        <label for="pageContent">Page Content</label>
                        <textarea class="form-control" name="page_content"></textarea>
                    </div>
                    
                     <div class="form-group">
                        <label for="exampleInputFile">Page Content Image</label>
                        <input id="exampleInputFile" type="file" name="pn_image" id="pn_image" class="">
                         <p class="help-block"><strong>Upload image size 600x264</strong></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="pageContent">Short Note</label>
                        <textarea class="form-control" name="short_note"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Short Note Image</label>
                        <input id="exampleInputFile" type="file" name="sn_image" id="sn_image" class="">
                         <p class="help-block"><strong>Upload image size 600x500</strong></p>
                    </div>
                    
                    <div class="collectionOfFOrmGroup">
						<?php
							foreach($db_rows as $db_val){
								$domian_name = $db_val['domain_name'];
								$site_id = $db_val['site_id'];					
								echo '<div class="form-group">
								<label>'.$domian_name.'</label>
								<input data-no-uniform="true" name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
								</div>';
							}
						?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Header Banner</label>
                        <input id="exampleInputFile" type="file" name="header_banner" id="header_banner" class="">
                         <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Footer Banner</label>
                        <input id="exampleInputFile" type="file" name="footer_banner" id="footer_banner" class="">
                        <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                    </div>

                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>
<!--<script src="//cdn.ckeditor.com/4.7.0/full/ckeditor.js"></script>-->
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'page_content' );
	CKEDITOR.replace( 'short_note' );
</script>

<?php include_once 'footer.php'; ?>
