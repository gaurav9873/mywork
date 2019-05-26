<?php
include 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

echo $gift_id = $cFunc->DecryptClientId($_REQUEST['gift_id']);

//Get Domain
$db_rows = $obj->fetchAll('op2mro9899_add_domain');

$row = $obj->get_row_by_id('op2mro9899_gifts', 'id', $gift_id);

$id = $row[0]['id'];
$gift_name = $row[0]['gift_name'];
$regular_price = $row[0]['regular_price'];
$disccount_price = $row[0]['disccount_price'];
$description = $row[0]['description'];
$short_note = $row[0]['short_note'];
$full_path = $row[0]['full_path'];
$medium_path = $row[0]['medium_path'];
$thumbnail_path = $row[0]['thumbnail_path'];
$gift_order = $row[0]['gift_order'];

//disable browse button
$disabled = (($thumbnail_path <> '') ? 'disabled' : '');

$rows = $obj->get_row_by_id('op2mro9899_gifts_relation', 'gift_id', $gift_id);
$array = array();
foreach($rows as $val){
	array_push($array, $val['site_id']);
}


$gifs_attrib = $obj->get_row_by_id('op2mro9899_gifts_type', 'gift_cat_id', $gift_id);
$att = array();
foreach($gifs_attrib as $attvlas){
	array_push($att, $attvlas['gifts_price']);
}

//Upload Image
$folder_name = 'gift';
$upload_path = './uploads/'.$folder_name.'/'.date('Y').'/'.date('m');

$arr = array('full' => array('max_width' => 800, 'max_height' => 800),
				'medium' => array('max_width' => 600, 'max_height' => 600),
				'thumbnail' => array('max_width' => 150, 'max_height' => 150),);

if(isset($_POST['submit'])){
	
	
	
	$giftName = $cFunc->xss_clean($_POST['gift_name']);
	$giftPrice = $cFunc->xss_clean($_POST['gift_price']);
	$disccountPrice = $cFunc->xss_clean($_POST['disccount_price']);
	$giftDescription = addslashes($_POST['gift_description']);
	$shortNote = addslashes($_POST['short_note']);
	$chk_enable = $_POST['chk_enable'];
	$created_ip = $cFunc->get_client_ip();
	$created_date = date("Y-m-d H:i:s");
	$gift_desc = $_POST['gift_desc'];
	$gift_prices = $_POST['gift_prices'];
	
	$img_name = isset($_FILES['gift_img']['name']) ? $_FILES['gift_img']['name'] : '';
	$tmp_path = isset($_FILES['gift_img']['tmp_name']) ? $_FILES['gift_img']['tmp_name'] : '';
	
	
	if($img_name){
	
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
		$imgExtension = time().rand(290,7689).'.'.@end(explode('.',$img_name));
		
		$createimagestatus = move_uploaded_file($tmp_path,$source.$imgExtension);
		if($createimagestatus){
			foreach($destination_path as $key=>$value){
				$cFunc->make_thumb($source.$imgExtension,$value['path'].$imgExtension,$value['width'],$value['heigth']);
			}	
		}
		
		//Update Image
		$img_arr = array('full_path' => $source.$imgExtension, 'medium_path' => $destination_path['medium']['path'].$imgExtension, 
						 'thumbnail_path' => $destination_path['thumbnail']['path'].$imgExtension);
		
		$update_img_stmt = $obj->update_row('op2mro9899_gifts', $img_arr,"WHERE id = '$gift_id'");
	}
	
	
	
	$row_arr = array('gift_name' => $giftName, 'regular_price' => $giftPrice, 'disccount_price' => $disccountPrice, 
					 'description' => $giftDescription, 'short_note' => $shortNote);
	$update_stmt = $obj->update_row('op2mro9899_gifts', $row_arr,"WHERE id = '$gift_id'");
	if($update_stmt){
		 $result = $cFunc->array_equal( $chk_enable, $array);
		 if($result){}else{
			 $obj->deleteRow('op2mro9899_gifts_relation', 'gift_id', $gift_id);
			 foreach($chk_enable as $domain_id){
					$row_arr = array('gift_id' => $gift_id, 'site_id' => $domain_id);
					$obj->insert_records('op2mro9899_gifts_relation', $row_arr);
			 }
		}
		
	}
	
	$comp = $cFunc->array_equal( $gift_prices, $att);
	if($comp){ }else{
		$obj->deleteRow('op2mro9899_gifts_type', 'gift_cat_id', $gift_id);
		foreach($gift_desc as $key => $gifts_vals){
			$prices = $gift_prices[$key];
			$array_args = array('gift_cat_id' => $gift_id, 'gifts_name' => $gifts_vals, 'gifts_price' => $prices, 'created_date' => $created_date, 'created_ip' => $created_ip);
			$obj->insert_records('op2mro9899_gifts_type', $array_args);
		}
	}
	header("location:all-gifts.php");
	
}

?>

<script>
	$(document).ready(function(){
		//$(".gift_img").prop('disabled', true);

		$(".delClass").on('click', function(){
			var did = $(this).attr('data-did');
			var checkstr =  confirm('are you sure you want to remove this?');

			if(checkstr == true){
				if(did!=''){
					$.ajax({
						url:'action/gift.php',
						type:'POST',
						data:{action:'deleteImage', imgID:did},
						cache:false,
						beforeSend:function(){
							$(".ajloader").show();
						},
						complete:function(){
							$(".ajloader").hide();
						},
						success:function(data){
							$(".gift_img").prop('disabled', false);
							alert(data);
						}
					});
				}
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
                        <input class="form-control validate" id="gift_name" name="gift_name" value="<?php echo $gift_name; ?>" placeholder="Enter Gift Name" type="text">
                    </div>
                     <div class="form-group">
                        <label for="giftName">Price</label>
                        <input class="form-control validate" id="gift_price" name="gift_price" value="<?php echo $regular_price; ?>" placeholder="Enter Price" type="text">
                    </div>
                    <div class="form-group">
                        <label for="giftName">Disscount Price</label>
                        <input class="form-control" id="disccount_price" name="disccount_price" value="<?php echo $disccount_price; ?>" placeholder="Enter Disscount Price" type="text">
                    </div>
                    <div class="form-group">
                        <label for="description">Dsecription</label>
                        <textarea class="form-control validate" id="gift_description" name="gift_description"><?php echo $description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Short Note</label>
                        <textarea class="form-control" id="short_note" name="short_note"><?php echo $short_note; ?></textarea>
                    </div>
                    
                    <div class="collectionOfFOrmGroup">
						<?php
							foreach($db_rows as $db_val){
								$domian_name = $db_val['domain_name'];
								$site_id = $db_val['site_id'];
								$checked = ((in_array($site_id, $array)) ? 'checked' : '');
								echo '<div class="form-group">
								<label>'.$domian_name.'</label>
								<input data-no-uniform="true" '.$checked.' name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
								</div>';
							}
						?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
                     <div class="field_wrapper">
						<?php if(empty($gifs_attrib)){ ?>
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
						<?php } ?>
						<?php
							$i = 1;
							foreach($gifs_attrib as $giftValues){
								$gifts_names = $giftValues['gifts_name'];
								$gifts_price = $giftValues['gifts_price'];
								
								$img = (($i == 1) ? 'Add.png' : 'remove.png');
								$btn_class = (($i == 1) ? 'add_button' : 'remove_button');
								$btn_title = (($i == 1) ? 'Add field' : 'Remove field');
								echo '<div>
									<div class="row rvals">
											<div class="input-group col-md-3" style="float: left; margin-right: 10px; margin-left:15px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-tint yellow"></i></span>
											<input class="form-control" name="gift_desc[]" value="'.$gifts_names.'" placeholder="Gift Descriptions" type="text">
										</div>
										<div class="input-group col-md-3" style="float: left; margin-right:10px; margin-top:15px;"><span class="input-group-addon"><i class="glyphicon glyphicon-star yellow"></i></span>
											<input class="form-control" name="gift_prices[]" value="'.$gifts_price.'" placeholder="Gift prices" type="text">
										</div>
										
										<div class="input-group col-md-2" style="float: left; margin-right:10px; margin-top:18px;">
											<a href="javascript:void(0);" class="'.$btn_class.'" title="'.$btn_title.'"><img src="img/'.$img.'"></a>
										</div>
									</div>
								</div>';
							$i++; }
						?>
						 
                    </div><br /><br />
                    <div class="form-group">
                        <label for="exampleInputFile">Upload Gift Image</label>
                        <input id="gift_img" class="gift_img" <?php echo $disabled; ?> name="gift_img" id="gift_img" type="file">
                        <p class="help-block"><strong>Upload image size 150x150</strong></p>
                        <div id="preview"></div>
                        <br />
                        <img src="<?php echo $thumbnail_path; ?>" width="80" height="80">
                        <br />
                        <a href="javascript:void(0);" class="delClass" data-did="<?php echo $id; ?>"><span class="label-default label label-danger">Delete</span></a>
                        <img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->
</div>

<?php include_once 'footer.php'; ?>
