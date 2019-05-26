<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');

$cat_id = $customObj->DecryptClientId($_GET['cat_id']);
$get_row = $obj->get_row_by_id('op2mro9899_gallery_category', 'id', intval($cat_id));

$id = $get_row[0]['id'];
$category_name = $get_row[0]['category_name'];
$full_path = $get_row[0]['full_path'];
$medium_path = $get_row[0]['medium_path'];
$thumbnail_path = $get_row[0]['thumbnail_path'];

//Disable browse button
$disabled = (($thumbnail_path <> '') ? 'disabled' : '');

$get_rows = $obj->get_row_by_id('op2mro9899_gallery_category_relation', 'gallery_cat_id', intval($cat_id));
$site_arr = array();
foreach($get_rows as $row_id){
	array_push($site_arr, $row_id['site_id']);
}

$upload_path = './uploads/gallery/'.date('Y').'/'.date('m');

$images = array('full' => array('max_width' => 800, 'max_height' => 800),
				'medium' => array('max_width' => 606, 'max_height' => 487),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);

if(isset($_POST['submit'])){
	
	
	$gallery_image = isset($_FILES['gallery_image']['name']) ? $_FILES['gallery_image']['name'] : '';
	$tmp_name = isset($_FILES['gallery_image']['tmp_name']) ? $_FILES['gallery_image']['tmp_name'] : '';
	
	if($gallery_image <> ''){
		foreach($images as $key => $val){
			$path = $upload_path."/".$key;
			if($key!='full')
				$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$images[$key]['max_height'],'width'=>$images[$key]['max_width']);

			if(!is_dir($path)){
				mkdir($path, 0777, true);
			}
		}
		
		$source = $upload_path.'/'.'full/';
		$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$gallery_image));
		$createimagestatus = move_uploaded_file($tmp_name,$source.$fileNameWithExtension);
		if($createimagestatus){
				foreach($destination_path as $key=>$value){
					$customObj->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
				}
			}
			
		$cat_arr = array('full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 
						 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension);
						 
		$update_img_stmt = $obj->update_row('op2mro9899_gallery_category', $cat_arr,"WHERE id = '$cat_id'");
	
	}
	
	$cat_name = $_POST['category_name'];
	$checked_domain = $_POST['chk_enable'];
	
	$arr = array('category_name' => $cat_name);
	$update_stmt = $obj->update_row('op2mro9899_gallery_category', $arr,"WHERE id = '$cat_id'");
	if($update_stmt){
		$result = $customObj->array_equal( $checked_domain, $site_arr);
		if($result){}else{
			$obj->deleteRow('op2mro9899_gallery_category_relation', 'gallery_cat_id', intval($cat_id));
			foreach($checked_domain as $domain_id){
				 $row_arr = array('gallery_cat_id' => intval($cat_id), 'site_id' => $domain_id);
				 $obj->insert_records('op2mro9899_gallery_category_relation', $row_arr);
			}
		}
		header("location:all-gallery-category.php");
	}
	
}

?>

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
</style>

<script>
$(document).ready(function(){
	$(".delClass").on('click', function(){
		var imgID = $(".delClass").data('did');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgID!=''){
				$.ajax({
					url:'action/gallery-category.php',
					type:'POST',
					data:{action:'delete_image', img_id:imgID},
					beforeSend:function(){
						$(".ajloader").show();
					},
					complete:function(){
						$(".ajloader").hide();
					},
					success:function(responce){
						window.location.href='';
					}
				});
			}
		}
	});
});
</script>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Category Elements</h2>
            </div>
            <div class="box-content">
				
				<form name="cfrm" id="cfrm" action="" method="post" role="form" enctype="multipart/form-data">
					<div class="form-group">
                        <label for="exampleInputEmail1">Category Name</label>
                        <input class="form-control chk_availability required" id="category_name" name="category_name" value="<?php echo $category_name; ?>" placeholder="Add Category Name" type="text">
                        <div class="avalibility" style="color:red;"></div>
                    </div>
                    <div class="collectionOfFOrmGroup">
						<?php
							foreach($db_rows as $db_val){
								$domian_name = $db_val['domain_name'];
								$site_id = $db_val['site_id'];
								$sel = ((in_array($site_id, $site_arr)) ? 'checked' : '');		
								echo '<div class="form-group">
								<label>'.$domian_name.'</label>
								<input data-no-uniform="true" name="chk_enable[]" value="'.$site_id.'" type="checkbox" '.$sel.' class="iphone-toggle check-tiggle">
								</div>';
							}
						?>
					</div>
					
					<div class="form-group">
						<label for="exampleInputFile">Add Image</label>
						<input id="exampleInputFile" <?php echo $disabled; ?> type="file" name="gallery_image"><br />
						<p class="help-block"><strong>Upload image size 252x203</strong></p>
						<?php if($thumbnail_path <> ''){ ?>
							<div class="img"><img src="<?php echo $thumbnail_path; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delClass" data-did="<?php echo $customObj->EncryptClientId($id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
					</div>
					<div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
					<button type="submit" name="submit" class="btn btn-default btn-submit" id="btn">Submit</button>
				</form>
				
		</div>
        </div>
    </div>
    <!--/span-->

</div>


<?php include_once 'footer.php'; ?>
