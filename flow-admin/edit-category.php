<?php
include_once 'header.php';

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');

$cat = $obj->fetchCategoryTree();

$cat_ids = $cFunc->DecryptClientId($_REQUEST['cat_id']);

$get_cat = $obj->get_row_by_id('op2mro9899_category', 'cat_id', intval($cat_ids));

$parent_category_id = $get_cat[0]['parent_category'];
$category_name = $get_cat[0]['category_name'];
$category_des = $get_cat[0]['category_description'];
$th_path = $get_cat[0]['thumbnail_path'];

//Disable Button
$disabled_home_cat_img = (($th_path <>'') ? 'disabled' : '');

$domain = $obj->get_row_by_id('op2mro9899_category_relation', 'category_id', intval($cat_ids));


$cat_img = $obj->get_row_by_id('op2mro9899_category_image', 'cat_id', intval($cat_ids));


$chk_img = $obj->check_exist_value('op2mro9899_category_image', 'cat_id', intval($cat_ids));



//Domain chkBox
$site = array();
foreach($domain as $val){
	$id = array_push($site, $val['site_id']);
}

//Category Image
$domainArray = array();
$thumb_path = array();
foreach($cat_img as $img_val){
	$siteIDS = array_push($domainArray, $img_val['site_id']);
	$tpath = array_push($thumb_path, $img_val['thumbnail_path']);
}

$combine = array_combine($domainArray, $thumb_path);



if(isset($_POST['category_name'])){
	
	
	$category_name = $cFunc->xss_clean($_POST['category_name']);
	$category_description = addslashes($_POST['cat_desc']);
	$parent_id = intval($_POST['parent_id']);
	$siteIDS = $_POST['siteIDS'];
	$thumb_path = $_POST['thumb_path'];
	$full_path = $_POST['full_path'];
	$medium_path = $_POST['medium_path'];
    $enable_domain = $_POST['chk_enable'];
    
    //check previous enabled domain
    $chk_val = $cFunc->array_equal($enable_domain, $site);
	
	
	
	//create folder and upload images
	$upload_path = './uploads/'.date('Y').'/'.date('m');
	$home_path = './uploads/home/'.date('Y').'/'.date('m');

	/*$images = array('full' => array('max_width' => 1980, 'max_height' => 1200),
	'medium' => array('max_width' => 922, 'max_height' => 468),
	'thumbnail' => array('max_width' => 445, 'max_height' => 469),);
	*/
	
	$images = array('full' => array('max_width' => 922, 'max_height' => 468),
					'medium' => array('max_width' => 500, 'max_height' => 600),
					'thumbnail' => array('max_width' => 445, 'max_height' => 469),);
	
	$home_images = array('full' => array('max_width' => 1980, 'max_height' => 1200),
						'medium' => array('max_width' => 922, 'max_height' => 468),
						'thumbnail' => array('max_width' => 445, 'max_height' => 469),);
	
	
	//Update Home Category Image
	
		$home_category_image = isset($_FILES['home_category_image']['name']) ? $_FILES['home_category_image']['name'] : '';
	    $tmp_names = isset($_FILES['home_category_image']['tmp_name']) ? $_FILES['home_category_image']['tmp_name'] : '';
	    if($home_category_image <> ''){
			foreach($home_images as $keys => $vals){ 
				$paths = $home_path."/".$keys;
				if($keys!='full')
					$destination_paths[$keys] =  array('path'=>$home_path.'/'.$keys.'/','heigth'=>$home_images[$keys]['max_height'],'width'=>$home_images[$keys]['max_width']);
				if(!is_dir($paths)){
					mkdir($paths, 0777, true);
				}
			}
			
			
			
			$sources = $home_path.'/'.'full/';
			$fileNameWithExtensions = time().rand(290,768957).'.'.@end(explode('.',$home_category_image));
			$createimagestatus = move_uploaded_file($tmp_names,$sources.$fileNameWithExtensions);
			if($createimagestatus){
				foreach($destination_paths as $k=>$v){
					$cFunc->make_thumb($sources.$fileNameWithExtensions,$v['path'].$fileNameWithExtensions,$v['width'],$v['heigth']);
				}
			}

			$cat_arr = array('full_path' => $sources.$fileNameWithExtensions, 'medium_path' => $destination_paths['medium']['path'].$fileNameWithExtensions, 
							 'thumbnail_path' => $destination_paths['thumbnail']['path'].$fileNameWithExtensions);

			$update_img_stmt = $obj->update_row('op2mro9899_category', $cat_arr,"WHERE cat_id = '$cat_ids'");
			
			
		}
	
	//End Home Category Image
	

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

	$fileNameRequest = array();
	$fileTmpRequest = array_filter(array_combine($_POST['siteIDS'],$_FILES['cat_img']['tmp_name']));
	$fileNameRequest = array_filter(array_combine($_POST['siteIDS'],$_FILES['cat_img']['name']));
	$previosFullFiles = array_filter(array_combine($_POST['siteIDS'],$_POST['full_path']));
	$previosThumbFiles = array_filter(array_combine($_POST['siteIDS'],$_POST['thumb_path']));
	$previosmediumFiles = array_filter(array_combine($_POST['siteIDS'],$_POST['medium_path']));
	

	if(!empty($fileNameRequest)){ 
		foreach($fileNameRequest as $siteIds=>$imageName){
			$source = $upload_path.'/'.'full/';
			$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$imageName));


			$createimagestatus = move_uploaded_file($fileTmpRequest[$siteIds],$source.$fileNameWithExtension);

			if($createimagestatus){
				foreach($destination_path as $key=>$value){
					$cFunc->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
				}
			}

			
			if($previosFullFiles[$siteIds]!=''){
					
				$prevFiletoDeleteFull = unlink($previosFullFiles[$siteIds]);
				$prevFiletoDeleteThumb = unlink($previosThumbFiles[$siteIds]);
				$prevFiletoDeleteMedium = unlink($previosmediumFiles[$siteIds]);
                
               
                
				$img_array = array('full_path' =>$destination_path['full']['path'].$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'site_id' => $siteIds, 'cat_id' => $cat_ids);
				$up_img = $obj->update_row('op2mro9899_category_image', $img_array,"WHERE cat_id = '$cat_ids' AND site_id = '$siteIds'");
				//print_r($img_array); die;
				
			}else{

				$img_arrays = array('full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'site_id' => $siteIds, 'cat_id' => $cat_ids);
				
				$file_parts = pathinfo($source.$fileNameWithExtension);
				if($file_parts['extension']){
					$obj->insert_records('op2mro9899_category_image', $img_arrays);
				}
			}


		}

	}

	//update domain value
	if($chk_val){}else{
		foreach($domain as $delID){
			$obj->deleteRow('op2mro9899_category_relation', 'id', $delID['id']);
		}
		
		foreach($enable_domain as $site_val){
			$ret_array = array('category_id' => $cat_ids, 'site_id' => $site_val);
			$obj->insert_records('op2mro9899_category_relation', $ret_array);
		}
	}
	
	$cat_data = array('category_name' => $category_name, 'category_description' => $category_description, 'parent_category' =>  $parent_id);
	$q = $obj->update_row('op2mro9899_category', $cat_data,"WHERE cat_id = '$cat_ids'");
	
	//header('location:edit-category.php?cat_id='.$_REQUEST['cat_id'].'&msg=sucess');
	header('location:all-categories.php');
	
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
	$(".delImg").on('click', function(){
		var sid = $(this).find('.label').attr('data-site');
		var cid = $(this).find('.label').attr('data-cat');
		var imgSrc = $(this).next().next().find('.catImg').attr('src');
		var imgPath = $(this).parent().find('.timg').val();
		
		var loader = $(this).find(".ajloader");
		if(imgSrc!=''){
			$.ajax({
				url:'action/category.php',
				type:'POST',
				cache:false,
				data:{
					action:'delete_image',
					siteID:sid,
					catID:cid,
					img:imgPath
				},
				beforeSend: function() {	
					$(loader).show();
				},
				complete: function(){
					$(loader).hide();
				},
				success: function(json){
					window.location.href='';
					/*var Data = $.parseJSON(json);
					var html = template(Data[0]);
					$("#site-data").prepend(html);
					$("#add-alert").show();
					$('#add-alert').delay(5000).fadeOut('slow');
					$("#sfrm")[0].reset();*/
				}
			});
		}
	});
	
	
	$(".delImageClass").on('click', function(){
		var imgID = $(".delImageClass").data('did');
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgID!=''){
				$.ajax({
					url:'action/category.php',
					type:'POST',
					data:{action:'delete_home_image', img_id:imgID},
					beforeSend:function(){
						$(".ajloaders").show();
					},
					complete:function(){
						$(".ajloaders").hide();
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
                <h2><i class="glyphicon glyphicon-edit"></i> Edit Category Elements</h2>
            </div>
            <div class="box-content">
                <form name="cfrm" id="cfrm" action="" method="post" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category Name</label>
                        <input class="form-control chk_availability required" id="category_name" name="category_name" value="<?php echo $category_name; ?>" placeholder="Add Category Name" type="text">
                        <div class="avalibility"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category Description</label>
                       <textarea class="form-control validate" id="cat_desc" name="cat_desc"><?php echo $category_des; ?></textarea>
                        <div class="avalibility" style="color:red;"></div>
                    </div>
                    
                    
                    <?php if($parent_category_id != 0){ ?>
                    
                    <div class="form-group">
						<label for="password">Parent Category</label>
							<select name="parent_id" id="parent_id" class="form-control">
								<option value="">Select Parent Category</option>
								<?php
									foreach($cat as $cat_view){
										$cat_id = $cat_view['id'];
										$category_name = $cat_view['name'];
										$parent = $cat_view['parent'];
										$chkparent = (($parent == 0) ? $cat_id : $parent);
										$selected = (($cat_id == $cat_ids) ? 'selected' : '');
										echo '<option '.$selected.' value="'.$chkparent.'">'.$category_name.'</option>';
									}
								?>
							</select>
					</div>  
					<?php } ?>
        		<div class="collectionOfFOrmGroup">
					<?php
						foreach($db_rows as $db_img){
							
							$site_name = $db_img['domain_name'];
						    $site_ids = $db_img['site_id'];
						    $thumb_img = ((array_key_exists($site_ids, $combine)) ? $combine[$site_ids] : '');
							$full_img = str_replace('/thumbnail/', '/full/', $thumb_img);
			            	$medium_img = str_replace('/thumbnail/', '/medium/', $thumb_img);
			            	$disabled = (($thumb_img <> '') ? '' : '');
							echo '<div class="form-group">
							<input type="hidden" class="timg" name="thumb_path[]" value="'.$thumb_img.'">
							<input type="hidden" class="fimg" name="full_path[]" value="'.$full_img.'">
							<input type="hidden" class="mimg" name="medium_path[]" value="'.$medium_img.'">
							<a href="javascript:void(0);" class="delImg">
							<span data-cat="'.$cat_ids.'" data-site="'.$site_ids.'" class="label-default label label-danger">Delete</span>
							<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
							</a>
							<div class="form-block">
							<label for="exampleInputFile">'.$site_name.'</label>
							<input id="exampleInputFile" '.$disabled.' type="file" class="resimg" value="" name="cat_img[]">
							<p class="help-block"><strong>Upload image size 800x751</strong></p>
							<input type="hidden"  name="siteIDS[]" value="'.$site_ids.'"></div>
							<div class="flower-block">
							<img class="catImg" src="'.$thumb_img.'">
							</div>
							</div>';
						}
                
 
				
					foreach($db_rows as $db_val){
						
						$domian_name = $db_val['domain_name'];
						$site_id = $db_val['site_id'];	
						$checked = ((in_array($site_id, $site, true)) ? 'checked' : 'false');
									
						echo '<div class="form-group">
						<label>'.$domian_name.'</label>
						<input data-no-uniform="true" '.$checked.' name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
						</div>';
					}
					?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
                    
                     <div class="form-group">
						<label for="exampleInputFile">Add Image For Home Category</label>
						<input id="exampleInputFile" <?php echo $disabled_home_cat_img; ?> type="file" name="home_category_image">
						<p class="help-block"><strong>Upload image size 922x468</strong></p>
						<?php if($th_path <> ''){ ?>
							<div class="img"><img src="<?php echo $th_path; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delImageClass" data-did="<?php echo $cat_ids; ?>">
							<span class="label-default label label-danger">Delete</span></a>
							<img class="ajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
						<?php } ?>
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
<?php include_once 'footer.php'; ?>
