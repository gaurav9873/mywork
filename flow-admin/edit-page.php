<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$page_id = $cFunc->DecryptClientId($_REQUEST['page_id']);

$page_contents = $obj->get_row_by_id('op2mro9899_pages', 'id', intval($page_id));

$page_name = $page_contents[0]['page_name'];
$short_description = stripcslashes($page_contents[0]['short_description']);
$page_content = $page_contents[0]['page_content'];
$pc_image = $page_contents[0]['pc_image'];
$short_note2 = $page_contents[0]['short_note2'];
$sd_image = $page_contents[0]['sd_image'];
$header_banner = $page_contents[0]['header_banner'];
$footer_banner = $page_contents[0]['footer_banner'];

$disabled = (($header_banner <> '') ? 'disabled' : '');
$disabled_buy_now = (($footer_banner <> '') ? 'disabled' : '');

$pc_disabled = (($pc_image <> '') ? 'disabled' : '');
$sd_disabled = (($sd_image <> '') ? 'disabled' : '');

//Get Domain
$db_rows = $obj->fetchAll('op2mro9899_add_domain');

//Domain
$chk_sites = $obj->get_row_by_id('op2mro9899_pages_relation', 'page_id', intval($page_id));

$domain = array();
foreach($chk_sites as $sval){
	array_push($domain, $sval['site_id']);
}



if(isset($_POST['submit'])){
	$p_name = $_POST['page_name'];
	$p_content = stripcslashes($_POST['page_content']);
	$short_note = stripcslashes($_POST['short_note']);
	$pshort_note2 = stripcslashes($_POST['short_note2']);
	$site_ids = $_POST['chk_enable'];
	$site_exists = $domain;
    $chk_siteIds = $site_ids; 
    $result = $cFunc->array_equal( $chk_siteIds, $site_exists);
    
    //Header Banner
	$header_banner = isset($_FILES['header_banner']['name']) ? $_FILES['header_banner']['name'] : '';
	$header_tmp_name = isset($_FILES['header_banner']['tmp_name']) ? $_FILES['header_banner']['tmp_name'] : '';
	
	//Footer Banner
    $footer_banner = isset($_FILES['footer_banner']['name']) ? $_FILES['footer_banner']['name'] : '';
	$footer_tmp_name = isset($_FILES['footer_banner']['tmp_name']) ? $_FILES['footer_banner']['tmp_name'] : '';
	
	//Page Content Image
    $pn_image = isset($_FILES['pn_image']['name']) ? $_FILES['pn_image']['name'] : '';
	$pn_image_tmp_name = isset($_FILES['pn_image']['tmp_name']) ? $_FILES['pn_image']['tmp_name'] : '';
	
	//Short Description Image
    $sn_image = isset($_FILES['sn_image']['name']) ? $_FILES['sn_image']['name'] : '';
	$sn_image_tmp_name = isset($_FILES['sn_image']['tmp_name']) ? $_FILES['sn_image']['tmp_name'] : '';
	
	
	//Header Banner
	if($header_banner!=''){
		$target_dir = "uploads/slider/banner/";
		$target_file = time().rand(290,7689).'.'.@end(explode('.',$header_banner));
		//print_r($target_file); die;
		$up_image = move_uploaded_file($header_tmp_name,$target_dir.$target_file);
		$header_path = $target_dir.$target_file;
		if($up_image){
			$header_args = array('header_banner' => $header_path);
			$update_header_stmt = $obj->update_row('op2mro9899_pages', $header_args,"WHERE id = '$page_id'");
		}
		
	}
	
	
	//Footer Banner
	if($footer_banner!=''){
		$targetDir = "uploads/slider/banner/";
		$targetFile = time().rand(290,7689).'.'.@end(explode('.',$footer_banner));
		$upImage = move_uploaded_file($footer_tmp_name,$targetDir.$targetFile);
		$footer_path = $targetDir.$targetFile;
		if($upImage){
			$footer_args = array('footer_banner' => $footer_path);
			$update_footer_stmt = $obj->update_row('op2mro9899_pages', $footer_args,"WHERE id = '$page_id'");
		}
	}
	
	//Page Content Image Upload
	if($pn_image!=''){
		$ptarget_dirs = "uploads/slider/pageimage/";
		$ptarget_pfile = time().rand(290,7689).'.'.@end(explode('.',$pn_image));
		//print_r($target_file); die;
		$pup_image = move_uploaded_file($pn_image_tmp_name,$ptarget_dirs.$ptarget_pfile);
		$ppath = $ptarget_dirs.$ptarget_pfile;
		if($pup_image){
			$p_args = array('pc_image' => $ppath);
			$pstmt = $obj->update_row('op2mro9899_pages', $p_args,"WHERE id = '$page_id'");
		}
		
	}
	
	
	//Short Description Image Upload
	if($sn_image!=''){
		$sntarget_dir = "uploads/slider/pageimage/";
		$sntarget_file = time().rand(290,7689).'.'.@end(explode('.',$sn_image));
		//print_r($target_file); die;
		$snup_image = move_uploaded_file($sn_image_tmp_name,$sntarget_dir.$sntarget_file);
		$sn_path = $sntarget_dir.$sntarget_file;
		if($snup_image){
			$sn_args = array('sd_image' => $sn_path);
			$sn_stmt = $obj->update_row('op2mro9899_pages', $sn_args,"WHERE id = '$page_id'");
		}
		
	}
    
   
    
	$post_arr = array('page_name' => $p_name, 'page_content' => $p_content, 'short_description' => $short_note, 'short_note2' => $pshort_note2);
	
	$update_stmt = $obj->update_row('op2mro9899_pages', $post_arr, "WHERE id = '$page_id'" );
	if($update_stmt){
			if($result){
			}else{
				$obj->deleteRow('op2mro9899_pages_relation', 'page_id', $page_id);
				foreach($site_ids as $postID){
					$row_arr = array('page_id' => $page_id, 'site_id' => $postID);
					$obj->insert_records('op2mro9899_pages_relation', $row_arr);
				}
			}
		header("location:edit-page.php?page_id=".$_REQUEST['page_id']."&msg=success");
	}
}
?>
<script>
$(document).ready(function(){
	$(".delClass").on('click', function(){
		var imgID = $(".delClass").data('did');
		//alert(imgID); return false;
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgID!=''){
				$.ajax({
					url:'action/page.php',
					type:'POST',
					data:{action:'delete_header_image', img_id:imgID},
					beforeSend:function(){
						$(".ajloader").show();
					},
					complete:function(){
						$(".ajloader").hide();
					},
					success:function(responce){
						//alert(responce);
						window.location.href='';
					}
				});
			}
		}
	});
	
	//Page Content
	$(".pndelClass").on('click', function(){
		var pndid = $(".pndelClass").data('did');
		var checkstrss =  confirm('are you sure you want to remove this?');
		if(checkstrss == true){
			if(pndid!=''){
				$.ajax({
					url:'action/page.php',
					type:'POST',
					data:{action:'delete_pn_image', pnimg_id:pndid},
					beforeSend:function(){
						$(".pnajloader").show();
					},
					complete:function(){
						$(".pnajloader").hide();
					},
					success:function(pnresp){
						//alert(pnresp);
						window.location.href='';
					}
				});
			}
		}
	});
	
	//Short Content
	$(".sndelClass").on('click', function(){
		var snimgIDs = $(".sndelClass").data('did');
		//alert(imgIDs); return false;
		var snchk =  confirm('are you sure you want to remove this?');
		if(snchk == true){
			if(snimgIDs!=''){
				$.ajax({
					url:'action/page.php',
					type:'POST',
					data:{action:'delete_sn_image', sn_img_ids:snimgIDs},
					beforeSend:function(){
						$(".snajloaders").show();
					},
					complete:function(){
						$(".snajloaders").hide();
					},
					success:function(resp){
						//alert(resp);
						window.location.href='';
					}
				});
			}
		}
	});
	
	
	
	
	$(".delClasses").on('click', function(){
		var imgIDs = $(".delClasses").data('did');
		//alert(imgIDs); return false;
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgIDs!=''){
				$.ajax({
					url:'action/page.php',
					type:'POST',
					data:{action:'delete_footer_image', img_ids:imgIDs},
					beforeSend:function(){
						$(".ajloaders").show();
					},
					complete:function(){
						$(".ajloaders").hide();
					},
					success:function(resp){
						//alert(resp);
						window.location.href='';
					}
				});
			}
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
                        <input class="form-control" id="page_name" name="page_name" value="<?php echo $page_name; ?>" placeholder="Enter Page Name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="pageContent">Page Content</label>
                        <textarea class="form-control" name="page_content"><?php echo $page_content; ?></textarea>
                    </div>
                    <?php if($page_id == 1){ ?>
                    <div class="form-group">
                        <label for="exampleInputFile">Page Content Image</label>
                        <input id="exampleInputFile" type="file" name="pn_image" id="pn_image" class="" <?php echo $pc_disabled; ?>>
                         <p class="help-block"><strong>Upload image size 600x264</strong></p>
                         <br />
                        
                        <?php if($pc_image <> ''){ ?>
							<div class="img"><img src="<?php echo $pc_image; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="pndelClass" data-did="<?php echo $cFunc->EncryptClientId($page_id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="pnajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pageContent">Short Note</label>
                        <textarea class="form-control" name="short_note"><?php echo $short_description; ?></textarea>
                    </div>
                    <?php if($page_id == 1){ ?>
                    <div class="form-group">
                        <label for="exampleInputFile">Short Note Image</label>
                        <input id="exampleInputFile" type="file" name="sn_image" id="sn_image" class="" <?php echo $sd_disabled; ?>>
                         <p class="help-block"><strong>Upload image size 600x500</strong></p>
                         <br />
                        
                        <?php  if($sd_image <> ''){ ?>
							<div class="img"><img src="<?php echo $sd_image; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="sndelClass" data-did="<?php echo $cFunc->EncryptClientId($page_id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="snajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <div class="form-group">
                        <label for="pageContent"><?php if($page_id==2){ echo 'Map Iframe'; }else{?>Short Note2<?php } ?></label>
                        <textarea class="form-control" name="short_note2"><?php echo $short_note2; ?></textarea>
                    </div>
                    
                     <div class="collectionOfFOrmGroup">
						<?php
							foreach($db_rows as $db_val){
								$domian_name = $db_val['domain_name'];
								$site_id = $db_val['site_id'];
								$checked = ((in_array($site_id, $domain)) ? 'checked' : '');		
								echo '<div class="form-group">
								<label>'.$domian_name.'</label>
								<input data-no-uniform="true" '.$checked.' name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
								</div>';
							}
						?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Header Banner</label>
                        <input id="exampleInputFile" type="file" name="header_banner" id="header_banner" class="" <?php echo $disabled; ?>>
                         <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                         <br />
                        
                        <?php if($header_banner <> ''){ ?>
							<div class="img"><img src="<?php echo $header_banner; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delClass" data-did="<?php echo $cFunc->EncryptClientId($page_id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                    </div>
                    <?php if($page_id == 2){ ?>
                    <div class="form-group">
                        <label for="exampleInputFile">Footer Banner</label>
                        <input id="exampleInputFile" type="file" name="footer_banner" id="footer_banner" class="" <?php echo $disabled_buy_now; ?>>
                        <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                        <br />
                        
                        <?php if($footer_banner <> ''){ ?>
							<div class="img"><img src="<?php echo $footer_banner; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delClasses" data-did="<?php echo $cFunc->EncryptClientId($page_id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="ajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                    </div>
					<?php } ?>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'page_content' );
	CKEDITOR.replace( 'short_note' );
	CKEDITOR.replace( 'short_note2' );
</script>



<?php include_once 'footer.php'; ?>
