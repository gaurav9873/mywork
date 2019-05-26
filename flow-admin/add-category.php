<?php
include_once('header.php');

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');
$all_categories = $obj->get_allCategories();
$cat = $obj->fetchCategoryTree();



$upload_path = './uploads/'.date('Y').'/'.date('m');
$home_path = './uploads/home/'.date('Y').'/'.date('m');

/*$images = array('full' => array('max_width' => 1980, 'max_height' => 1200),
				'medium' => array('max_width' => 922, 'max_height' => 468),
				'thumbnail' => array('max_width' => 500, 'max_height' => 469),);
*/

$images = array('full' => array('max_width' => 922, 'max_height' => 468),
				'medium' => array('max_width' => 500, 'max_height' => 600),
				'thumbnail' => array('max_width' => 445, 'max_height' => 469),);

				
$home_images = array('full' => array('max_width' => 1980, 'max_height' => 1200),
				'medium' => array('max_width' => 922, 'max_height' => 468),
				'thumbnail' => array('max_width' => 445, 'max_height' => 469),);

if(isset($_POST['category_name'])){

	$category_name = $cFunc->xss_clean($_POST['category_name']);
	$cat_desc = addslashes($_POST['cat_desc']);
	$parent_id = intval($_POST['parent_id']);
	//$sids = $_POST['chk_enable'];
	$domain_id = isset( $_POST["chk_enable"] ) ? $_POST["chk_enable"] : "0" ;
	
	
	if($_FILES['home_category_image']['name']!=''){
		//Home Category Image
		$home_category_image = $_FILES["home_category_image"]['name'];
		$tmp_names = $_FILES["home_category_image"]['tmp_name'];
		
		foreach($home_images as $keys => $vals){
			$paths = $home_path."/".$keys;
			if($keys!='full')
				$destination_paths[$keys] =  array('path'=>$home_path.'/'.$keys.'/','heigth'=>$images[$keys]['max_height'],'width'=>$images[$keys]['max_width']);

			if(!is_dir($paths)){
				mkdir($paths, 0777, true);
			}
		}
		
		$sources = $home_path.'/'.'full/';
		$fileNameWithExtensions = time().rand(290,768957).'.'.@end(explode('.',$home_category_image));
		$createimagestatuss = move_uploaded_file($tmp_names,$sources.$fileNameWithExtensions);
		if($createimagestatuss){
				foreach($destination_paths as $k=>$v){
					$cFunc->make_thumb($sources.$fileNameWithExtensions,$v['path'].$fileNameWithExtensions,$v['width'],$v['heigth']);
				}
			}
			
		//End Home Category Image
	}
	
	$cat_array = array('category_name' => $category_name, 'category_description' => $cat_desc, 'parent_category' => $parent_id, 'full_path' => $sources.$fileNameWithExtensions, 
						'medium_path' => $destination_paths['medium']['path'].$fileNameWithExtensions, 'thumbnail_path' => $destination_paths['thumbnail']['path'].$fileNameWithExtensions);
	$cat_stmt = $obj->insert_records('op2mro9899_category', $cat_array);

	if($domain_id!=0){
		foreach($domain_id as $post_ids){
			$ret_array = array('category_id' => $cat_stmt, 'site_id' => $post_ids);
			$obj->insert_records('op2mro9899_category_relation', $ret_array);
		}
	}
	

	$category_images = $_FILES["cat_img"]['name'];
	$siteIDS = $_POST['siteIDS'];
	$tmpArray = array_combine($_POST['siteIDS'],$_FILES['cat_img']['tmp_name']);
	$imageArray = array_combine($_POST['siteIDS'],$_FILES['cat_img']['name']);

	$desired_width = $desired_h = 113;
	$all_images = array();
	$destination_path = array();
	foreach($images as $key => $val){
		$path = $upload_path."/".$key;
		if($key!='full')
			$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$images[$key]['max_height'],'width'=>$images[$key]['max_width']);

		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}

	}


	foreach($imageArray as $siteIds=>$imageName){
		$source = $upload_path.'/'.'full/';
		$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$imageName));
		$img_array = array('full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'site_id' => $siteIds, 'cat_id' => $cat_stmt);
		$file_parts = pathinfo($source.$fileNameWithExtension);
		if($file_parts['extension']){
			$obj->insert_records('op2mro9899_category_image', $img_array);
		}
		$createimagestatus = move_uploaded_file($tmpArray[$siteIds],$source.$fileNameWithExtension);
		if($createimagestatus){
			foreach($destination_path as $key=>$value){
				$cFunc->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
			}
		}

	}
	
	header("location:all-categories.php");
	
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
	
	$("#cfrm").on('submit', function(e){
		
		var errFlag = false;
		
		$(".required").each(function(){
			if($(this).val() == ''){ 
				$(this).css('border-color', 'red');
				errFlag = true;
			}else{
			}
		});
		
		var chk_length = $(".check-tiggle:checked").length;
		if(chk_length < 1){
			$(".error").show();
		}
		if(chk_length >0){
			$(".error").hide();
		}
		
		$(".required").on('keypress change',function(){
			if($(this).val() ==''){
				$(this).css('border-color', 'red');
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
			}
		});
		
		if(errFlag){
			e.preventDefault();
		}
	});
	
	
	/*$(".chk_availability").on('change', function(){
		var chk_val = $(".chk_availability").val();
		if(chk_val){
			$.ajax({
				url:'action/category.php',
				type:'POST',
				data:{
					action:'chkCatName',
					cat_name:chk_val
				},
				cache:false,
				beforeSend:function(){
				},
				complete:function(){
				},
				success:function(responce){
					var data = $.parseJSON(responce);
					var msg = data.msg;
					if(msg == 'Category already exist.'){
						$(".avalibility").html(msg);
						$("#btn").prop('disabled', true);
					}
					
					if(msg == 'Category Added'){
						$(".avalibility").html('');
						$("#btn").prop('disabled', false);
					}
				}
			});
		}
		
	});*/
	
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
                        <input class="form-control chk_availability required" id="category_name" name="category_name" placeholder="Add Category Name" type="text">
                        <div class="avalibility" style="color:red;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category Description</label>
                       <textarea class="form-control validate" id="cat_desc" name="cat_desc"></textarea>
                        <div class="avalibility" style="color:red;"></div>
                    </div>
                    
                    
                    <div class="form-group">
						<label for="password">Parent Category</label>
							<select name="parent_id" id="parent_id" class="form-control">
								<option value="">Select Parent Category</option>
								<?php
									foreach($cat as $cat_view){
										$cat_id = $cat_view['id'];
										$category_name = $cat_view['name'];
										echo '<option value="'.$cat_id.'">'.$category_name.'</option>';
									}
								?>
							</select>
					</div>  
					
        		<div class="collectionOfFOrmGroup">
					<?php
						foreach($db_rows as $db_img){
							
							$site_name = $db_img['domain_name'];
						    $site_ids = $db_img['site_id'];
						    
							echo '<div class="form-group">
							<label for="exampleInputFile">'.$site_name.'</label>
							<input id="exampleInputFile" type="file" name="cat_img[]">
							<input type="hidden" name="siteIDS[]" value="'.$site_ids.'">
							<p class="help-block"><strong>Upload image size 800x751</strong></p>
							</div>';
						}
                
 
				
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
						<label for="exampleInputFile">Add Image For Home Category</label>
						<input id="exampleInputFile" type="file" name="home_category_image">
						<p class="help-block"><strong>Upload image size 922x468</strong></p>
					</div>
                    
                    <button type="submit" class="btn btn-default btn-submit" id="btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'cat_desc' );
</script>

<?php include_once('footer.php'); ?>
