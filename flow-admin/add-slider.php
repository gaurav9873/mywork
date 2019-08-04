<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$upload_path = './uploads/slider/'.date('Y').'/'.date('m');

$images = array('full' => array('max_width' => 1920, 'max_height' => 600),
				'medium' => array('max_width' => 800, 'max_height' => 700),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);

//if(empty($_POST['slider_name']) && empty($_POST['slider_content'])){
if(isset($_POST['slider_name'])){
	
	$slider_name = $_POST['slider_name'];
	$slider_content = addslashes($_POST['slider_content']);
	$slider_txt1 = addslashes($_POST['slider_txt1']);
	$slider_txt2 = addslashes($_POST['slider_txt2']);
	$buynow_content = addslashes($_POST['buynow_content']);
	$created_ip = $customObj->get_client_ip();
	$created_date = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$shop_id = $_SESSION['shop_id'];
	
	$slider_image = $_FILES["slider_image"]['name'];
	$tmp_name = $_FILES["slider_image"]['tmp_name'];
	

	
	//Buy Now Image
	$buy_image = $_FILES["buy_image"]['name'];
	$buy_image_tmp = $_FILES["buy_image"]['tmp_name'];
	
	
	
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
				$customObj->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
			}
		}
		
		
	$buy_now_path = '';
	//Buy Now
	if($buy_image!=''){
		$target_dir = "uploads/slider/buynow/";
		$target_file = time().rand(290,7689).'.'.@end(explode('.',$buy_image));
		$up_image = move_uploaded_file($buy_image_tmp,$target_dir.$target_file);
		$buy_now_path = $target_dir.$target_file;
		
	}
		
		
	$slider_arr = array('slider_name' => $slider_name, 
					    'slider_text' => $slider_content,
					    'slider_text1' => $slider_txt1,
					    'slider_text2' => $slider_txt2,
					    'buynow_text' => $buynow_content,
					    'full_path' => $source.$fileNameWithExtension, 
					    'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 
					    'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension,
					    'buy_now_image' => $buy_now_path,
					    'site_id' => $shop_id,
					    'date' => $date,
					    'created_date' => $created_date, 
					    'created_ip' => $created_ip);
	$ins_stmt = $obj->insertRecords('op2mro9899_slider', $slider_arr);
	if($ins_stmt){
		header("location:all-slider.php?msg=success");
	}
	
}
?>
<style>
.error{
	color:red;
}
</style>
<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script>
$(document).ready(function(){
	
	$('#slfrm').validate({
        rules: {
			slider_name: "required",
			//slider_image:{required: true, accept: "image/jpeg, image/jpg"},
        },
        
        messages: {
			slider_name: "please enter slider name",
            //slider_image:"Please upload slider image"
        },
        
         submitHandler: function (form) {
			 form.submit();
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
                        <input class="form-control required" name="slider_name" id="slider-name" placeholder="Slider Name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Slider Content</label>
                        <textarea class="form-control required" name="slider_content"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Slider Text1</label>
                        <textarea class="form-control" name="slider_txt1"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Slider Text2</label>
                        <textarea class="form-control" name="slider_txt2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Buy Now Content</label>
                        <textarea class="form-control" name="buynow_content"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input id="exampleInputFile" type="file" name="slider_image" id="slider_image" class="">
                         <p class="help-block"><strong>Upload image size 1920x600</strong></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputFile">Buy Now Image</label>
                        <input id="exampleInputFile" type="file" name="buy_image" id="buy_image" class="">
                        <p class="help-block"><strong>Upload image size 294x368</strong></p>
                    </div>
                    
                    <button type="submit" value="submitval" class="btn btn-default">Submit</button>
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