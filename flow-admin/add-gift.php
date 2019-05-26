<?php
include 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();


//Get Domain
$db_rows = $obj->fetchAll('op2mro9899_add_domain');

$folder_name = 'gift';
$upload_path = './uploads/'.$folder_name.'/'.date('Y').'/'.date('m');

$arr = array('full' => array('max_width' => 800, 'max_height' => 800),
				'medium' => array('max_width' => 600, 'max_height' => 600),
				'thumbnail' => array('max_width' => 150, 'max_height' => 150),);


if(isset($_POST['submit'])){
	
	
	$gift_img = $_FILES["gift_img"]['name'];
	$tmp_path = $_FILES['gift_img']['tmp_name'];
	
	$destination_path = array();
	foreach($arr as $key=>$val){
		$path = $upload_path."/".$key;
		if($key!='full')
			$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$arr[$key]['max_height'],'width'=>$arr[$key]['max_width']);
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		
	}
	

	$source = $upload_path.'/'.'full/';
	$imgExtension = time().rand(290,7689).'.'.@end(explode('.',$gift_img));
	
	$createimagestatus = move_uploaded_file($tmp_path,$source.$imgExtension);
	if($createimagestatus){
		foreach($destination_path as $key=>$value){
			$cFunc->make_thumb($source.$imgExtension,$value['path'].$imgExtension,$value['width'],$value['heigth']);
		}
		
		
	}
	

	$gift_name = $cFunc->xss_clean($_POST['gift_name']);
	$gift_price = $cFunc->xss_clean($_POST['gift_price']);
	$disccount_price = $cFunc->xss_clean($_POST['disccount_price']);
	$gift_description = addslashes($_POST['gift_description']);
	$short_note = addslashes($_POST['short_note']);
	$created_ip = $cFunc->get_client_ip();
	$date = date("Y-m-d H:i:s");
	$chk_enable = $_POST['chk_enable'];
	$created_ip = $cFunc->get_client_ip();
	$created_date = date("Y-m-d H:i:s");
	$gift_desc = $_POST['gift_desc'];
	$gift_prices = $_POST['gift_prices'];
	
	
	$arr = array('gift_name' => $gift_name, 'regular_price' => $gift_price, 'disccount_price' => $disccount_price, 
				 'description' => $gift_description, 'short_note' => $short_note, 'full_path' => $source.$imgExtension, 
				 'medium_path' => $destination_path['medium']['path'].$imgExtension, 'thumbnail_path' => $destination_path['thumbnail']['path'].$imgExtension,  
				 'created_date' => $date, 'created_ip' => $created_ip);
	$insert_stmt = $obj->insert_records('op2mro9899_gifts', $arr);
	
	if($insert_stmt){
		
		foreach($chk_enable as $postids){
			$site_array = array('gift_id' => $insert_stmt, 'site_id' => $postids);
			$obj->insert_records('op2mro9899_gifts_relation', $site_array);
		}
		
		foreach($gift_desc as $key => $gifts_vals){
			$prices = $gift_prices[$key];
			$array_args = array('gift_cat_id' => $insert_stmt, 'gifts_name' => $gifts_vals, 'gifts_price' => $prices, 'created_date' => $created_date, 'created_ip' => $created_ip);
			$obj->insert_records('op2mro9899_gifts_type', $array_args);
		}
		
		header("location:add-gift.php?msg=success");
	}
	
}
?>

<script type="text/javascript">
$(document).ready(function() {
  	
	$("#gift_img").on('change', function () {

		if (typeof (FileReader) != "undefined") {

			var image_holder = $("#preview");
			image_holder.empty();
			var reader = new FileReader();
			reader.onload = function (e) {
				$("<img />", {
				"src": e.target.result,
				"style": "height:100px;width: 100px;margin:5px 5px 0 0; border-radius:5px;"
				}).appendTo(image_holder);
			}
			image_holder.show();
			reader.readAsDataURL($(this)[0].files[0]);
		}else{
			alert("This browser does not support FileReader.");
		}
	});
	
	$("#frm").on('submit', function(e){

		errorFlag = false;
		$(".validate").each(function(){
			if($(this).val()==''){
				$(this).css('border-color', 'red');
				errorFlag = true;
			}else{
			}
		});
		
		var chk_length = $(".check-tiggle:checked").length
		if(chk_length < 1){
			$(".error").show();
		}
		if(chk_length >0){
			$(".error").hide();
		}

		$(".validate").on('keypress change',function(){
			if($(this).val()==''){
				$(this).css('border-color', 'red');
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
			}

		});

		if(errorFlag){
			e.preventDefault();
		}
	});
	
	var maxField = 25;
	var addButton = $('.add_button');
	var wrapper = $('.field_wrapper');

	var field_count = 1;
	$(addButton).click(function(){
		if(field_count < maxField){
			field_count++;
			var fieldHTML = $('<div><div class="row rvals"><div class="input-group col-md-3"  style="float: left; margin-right: 10px; margin-left:15px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-tint yellow"></i></span><input class="form-control" name="gift_desc[]" value="" placeholder="Gift Descriptions" type="text"></div><div class="input-group col-md-3"  style="float: left; margin-right:10px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-star yellow"></i></span><input class="form-control" name="gift_prices[]" placeholder="Gift prices" type="text"></div><div class="input-group col-md-2"  style="float: left; margin-right:10px; margin-top:18px;"><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="img/remove.png"></a></div></div></div>');
			$(wrapper).append(fieldHTML);
		}
	});
	$(wrapper).on('click', '.remove_button', function(e){
		e.preventDefault();
		$(this).parent().parent('div').remove();
		field_count--;
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
                <h2><i class="glyphicon glyphicon-edit"></i> Gift Elements</h2>
            </div>
            <div class="box-content">
                <form name="frm" id="frm" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="giftName">Gift Name</label>
                        <input class="form-control validate" id="gift_name" name="gift_name" placeholder="Enter Gift Name" type="text">
                    </div>
                     <div class="form-group">
                        <label for="giftName">Price</label>
                        <input class="form-control validate" id="gift_price" name="gift_price" placeholder="Enter Price" type="text">
                    </div>
                    <div class="form-group">
                        <label for="giftName">Disscount Price</label>
                        <input class="form-control" id="disccount_price" name="disccount_price" placeholder="Enter Disscount Price" type="text">
                    </div>
                    <div class="form-group">
                        <label for="description">Dsecription</label>
                        <textarea class="form-control validate" id="gift_description" name="gift_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Short Note</label>
                        <textarea class="form-control" id="short_note" name="short_note"></textarea>
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
					<div class="field_wrapper">
						<div>
							<div class="row">    
								<div class="input-group col-md-3"  style="float: left; margin-right: 10px; margin-left:15px; margin-top:15px;">
								<span class="input-group-addon"><i class="glyphicon glyphicon-tint yellow"></i></span>
								<input class="form-control" value="" name="gift_desc[]" placeholder="Add Descriptions" type="text">
							</div>

							<div class="input-group col-md-3"  style="float: left; margin-right:10px; margin-top:15px;">
								<span class="input-group-addon"><i class="glyphicon glyphicon-star yellow"></i></span>
								<input class="form-control" name="gift_prices[]" placeholder="Gift Prices" type="text">
							</div>

							<div class="input-group col-md-2"  style="float: left; margin-right:10px; margin-top:18px;">
								<a href="javascript:void(0);" class="add_button" title="Add field"><img src="img/Add.png"></a>
								</div>
							</div>
						</div>
					</div><br /><br />
                    <div class="form-group">
                        <label for="exampleInputFile">Upload Gift Image</label>
                        <input id="gift_img" name="gift_img" id="gift_img" type="file">
                        <p class="help-block"><strong>Upload image size 150x150</strong></p>
                        <div id="preview"></div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->
</div>

<?php include_once 'footer.php'; ?>
