<?php
include_once 'header.php';

$obj = new Connectdb();
$custom_obj = new CustomFunctions();

$sl_id = $custom_obj->DecryptClientId($_REQUEST['slider_id']);
$get_val = $obj->get_row_by_id('op2mro9899_slider', 'id', $sl_id);

$id = $get_val[0]['id'];
$slider_name = $get_val[0]['slider_name'];
$slider_text = $get_val[0]['slider_text'];
$slider_text1 = $get_val[0]['slider_text1'];
$slider_text2 = $get_val[0]['slider_text2'];
$buynow_text = $get_val[0]['buynow_text'];
$full_path = $get_val[0]['full_path'];
$medium_path = $get_val[0]['medium_path'];
$thumbnail_path = $get_val[0]['thumbnail_path'];
$buy_now_image = $get_val[0]['buy_now_image'];

$disabled = (($thumbnail_path <> '') ? 'disabled' : '');
$disabled_buy_now = (($buy_now_image <> '') ? 'disabled' : '');

$upload_path = './uploads/slider/'.date('Y').'/'.date('m');

$images = array('full' => array('max_width' => 1920, 'max_height' => 600),
				'medium' => array('max_width' => 800, 'max_height' => 700),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);
				
if(isset($_POST['submit'])){
	
	
	$sliderName = $_POST['slider_name'];
	$sliderContent = addslashes($_POST['slider_content']);
	$slider_txt1 = addslashes($_POST['slider_txt1']);
	$slider_txt2 = addslashes($_POST['slider_txt2']);
	$buynow_content = addslashes($_POST['buynow_content']);
	$shop_id = $_SESSION['shop_id'];
	
	$slider_image = isset($_FILES['slider_image']['name']) ? $_FILES['slider_image']['name'] : '';
	$tmp_name = isset($_FILES['slider_image']['tmp_name']) ? $_FILES['slider_image']['tmp_name'] : '';
	
	//Buy Now Image
	$buy_image = isset($_FILES['buy_image']['name']) ? $_FILES['buy_image']['name'] : '';
	$buy_tmp_name = isset($_FILES['buy_image']['tmp_name']) ? $_FILES['buy_image']['tmp_name'] : '';
	
	
	if($slider_image <> ''){
		foreach($images as $key => $val){
			$path = $upload_path."/".$key;
			if($key!='full')
				$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$images[$key]['max_height'],'width'=>$images[$key]['max_width']);

			if(!is_dir($path)){
				mkdir($path, 0777, true);
			}
		}
		
		$source = $upload_path.'/'.'full/';
		$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$slider_image));
		
		$createimagestatus = move_uploaded_file($tmp_name,$source.$fileNameWithExtension);
		if($createimagestatus){
				foreach($destination_path as $key=>$value){
					$custom_obj->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
				}
			}
			
		$cat_arr = array('full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 
						 'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension);
						 
		$update_img_stmt = $obj->update_row('op2mro9899_slider', $cat_arr,"WHERE id = '$sl_id'");
	
	}
	
	
	//Buy Now
	$buy_now_path = '';
	if($buy_image!=''){
		$target_dir = "uploads/slider/buynow/";
		$target_file = time().rand(290,7689).'.'.@end(explode('.',$buy_image));
		$up_image = move_uploaded_file($buy_tmp_name,$target_dir.$target_file);
		$buy_now_path = $target_dir.$target_file;
		if($up_image){
			$arr_args = array('buy_now_image' => $buy_now_path,);
			$update_img_stmt = $obj->update_row('op2mro9899_slider', $arr_args,"WHERE id = '$sl_id'");
		}
	}
	
	//buynow_text
	$args = array('slider_name' => $sliderName, 'slider_text' => $sliderContent, 'slider_text1' => $slider_txt1, 'slider_text2' => $slider_txt2, 'buynow_text' => $buynow_content, 'site_id' => $shop_id);
	$update_stmt = $obj->update_row('op2mro9899_slider', $args,"WHERE id = '$sl_id'");
	if($update_stmt){
		header("location:edit-slider.php?slider_id=".$_REQUEST['slider_id']."&msg=success");
	}
	
}

?>

<script>
$(document).ready(function(){
	$(".delClass").on('click', function(){
		var imgID = $(".delClass").data('did');
		
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgID!=''){
				$.ajax({
					url:'action/slider.php',
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
	
	
	$(".delClasses").on('click', function(){
		var imgIDs = $(".delClasses").data('did');
		
		var checkstr =  confirm('are you sure you want to remove this?');
		if(checkstr == true){
			if(imgIDs!=''){
				$.ajax({
					url:'action/slider.php',
					type:'POST',
					data:{action:'delete_buy_now_image', img_ids:imgIDs},
					beforeSend:function(){
						$(".ajloaders").show();
					},
					complete:function(){
						$(".ajloaders").hide();
					},
					success:function(resp){
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
                <h2><i class="glyphicon glyphicon-edit"></i> Slider Elements</h2>
            </div>
            <div class="box-content">
				
                <form name="slfrm" id="slfrm" class="slfrm" action="" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Add Slider Name</label>
                        <input class="form-control" name="slider_name" id="slider-name" value="<?php echo $slider_name; ?>" placeholder="Slider Name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Slider Content</label>
                        <textarea class="form-control" name="slider_content"><?php echo $slider_text; ?></textarea>
                    </div>
                     <div class="form-group">
                        <label for="exampleInputPassword1">Slider Text1</label>
                        <textarea class="form-control" name="slider_txt1"><?php echo $slider_text1; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Slider Text2</label>
                        <textarea class="form-control" name="slider_txt2"><?php echo $slider_text2; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Buy Now Content</label>
                        <textarea class="form-control" name="buynow_content"><?php echo $buynow_text; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input id="exampleInputFile" type="file" name="slider_image" <?php echo $disabled; ?>>
                        <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                        <br />
                        
                        <?php if($thumbnail_path <> ''){ ?>
							<div class="img"><img src="<?php echo $thumbnail_path; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delClass" data-did="<?php echo $custom_obj->EncryptClientId($id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                        
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Buy Now Image</label>
                        <input id="exampleInputFile" type="file" name="buy_image" id="buy_image" class="" <?php echo $disabled_buy_now; ?>>
                        <p class="help-block"><strong>Upload image size 294x368</strong></p>
                        <br />
                        
                        <?php if($buy_now_image <> ''){ ?>
							<div class="img"><img src="<?php echo $buy_now_image; ?>" height="50" width="50"></div>
							<br />
							<a href="javascript:void(0);" class="delClasses" data-did="<?php echo $custom_obj->EncryptClientId($id); ?>"><span class="label-default label label-danger">Delete</span></a>
							<img class="ajloaders" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                        <?php } ?>
                    </div>
                    <?php if($buy_now_image <> ''){ ?>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Buy Now Image Path</label>
                        <input type="text" name="img_path" value="<?php echo 'https://www.fleurdelisflorist.co.uk/florist-admin/'.$buy_now_image; ?>" readonly>
                    </div>
                    <?php } ?>
                   <button type="submit" name="submit" class="btn btn-default btn-submit" id="btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->
</div>
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'slider_txt1' );
	CKEDITOR.replace( 'slider_txt2' );
	CKEDITOR.replace( 'buynow_content' );
	
</script>

<?php include_once 'footer.php'; ?>