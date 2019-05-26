<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');
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

<?php

$upload_path = './uploads/gallery/'.date('Y').'/'.date('m');

$images = array('full' => array('max_width' => 800, 'max_height' => 800),
				'medium' => array('max_width' => 606, 'max_height' => 487),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);

if(isset($_POST['category_name'])){

	$category_name = $customObj->xss_clean($_POST['category_name']);
	$chk_enable = isset($_POST['chk_enable']) ? $_POST['chk_enable'] : '';
	$created_ip = $customObj->get_client_ip();
	$date = date("Y-m-d H:i:s");
	
	$category_images = $_FILES["gallery_image"]['name'];
	$tmp_name = $_FILES["gallery_image"]['tmp_name'];
	
	foreach($images as $key => $val){
		$path = $upload_path."/".$key;
		if($key!='full')
			$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$images[$key]['max_height'],'width'=>$images[$key]['max_width']);

		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
	}
	
	$source = $upload_path.'/'.'full/';
	$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$category_images));
	$createimagestatus = move_uploaded_file($tmp_name,$source.$fileNameWithExtension);
	if($createimagestatus){
			foreach($destination_path as $key=>$value){
				$customObj->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
			}
		}
	
	$cat_arr = array('category_name' => $category_name, 'full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 
					 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'created_date' => $date, 'created_ip' => $created_ip);
	$ins_stmt = $obj->insert_records('op2mro9899_gallery_category', $cat_arr);
	if($ins_stmt){
		foreach($chk_enable as $domain_ids){
			$res = array('gallery_cat_id' => $ins_stmt, 'site_id' => $domain_ids);
			$req = $obj->insert_records('op2mro9899_gallery_category_relation', $res);
		}
		
		header("location:add-gallery-category.php?msg=success");
		
	}
	
}

?>

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
                        <input class="form-control chk_availability required" id="category_name" name="category_name" placeholder="Add Category Name" type="text">
                        <div class="avalibility" style="color:red;"></div>
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
					
					<div class="form-group">
						<label for="exampleInputFile">Add Image</label>
						<input id="exampleInputFile" type="file" name="gallery_image">
						<p class="help-block"><strong>Upload image size 252x203</strong></p>
					</div>
					<div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
					<button type="submit" class="btn btn-default btn-submit" id="btn">Submit</button>
				</form>
				
		</div>
        </div>
    </div>
    <!--/span-->

</div>

<?php include_once 'footer.php'; ?>
