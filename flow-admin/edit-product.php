<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');
$cat_tree = $obj->fetchCategoryTree();

$product_id = $cFunc->DecryptClientId($_REQUEST['pid']);

//Product price
$products_price = $obj->get_row_by_id('op2mro9899_products_price', 'product_id', intval($product_id));


//Product site relation
$site_relation = $obj->get_row_by_id('op2mro9899_product_site_relation', 'pid', intval($product_id));

$site_array = array();
foreach($site_relation as $postIDs){
	$id = array_push($site_array, $postIDs['site_id']);
}

$data = $obj->get_row_by_id('op2mro9899_products', 'pid', intval($product_id));
$product_name = $cFunc->xss_clean($data[0]['product_name']);
$description = $data[0]['description'];
$short_description = $data[0]['short_description'];
$regular_price = $cFunc->xss_clean($data[0]['regular_price']);
$largePrice = $cFunc->xss_clean($data[0]['large_price']);
$disscount_price = $cFunc->xss_clean($data[0]['disscount_price']);
$product_code = $cFunc->xss_clean($data[0]['product_code']);


//category
$cat_data = $obj->get_row_by_id('op2mro9899_products_relation', 'pid', intval($product_id));

$catArr = array();
foreach($cat_data as $cat_val){
	$cat_id = $cat_val['cat_id'];
	array_push($catArr, $cat_id);
}

$p_img = $obj->get_row_by_id('op2mro9899_products_image', 'pid', intval($product_id), 'image_order');


$upload_path = './uploads/products/'.date('Y').'/'.date('m');

$chk_pcode = $obj->chk_product_code($product_id);


if(isset($_POST['submit'])){
	

	$pro_name = addslashes($_POST['product_name']);
	$product_desc = addslashes($_POST['product_desc']);
	$product_sdesc = addslashes($_POST['product_sdesc']);
	$product_price = $cFunc->xss_clean($_POST['product_price']);
	$large_price = $cFunc->xss_clean($_POST['large_price']);
	$dis_price = $cFunc->xss_clean($_POST['dis_price']);
	$chk_enable = isset($_POST['chk_enable']) ? $_POST['chk_enable'] : '';

	
	$products_prices = $_POST['products_prices'];
	$siteids = $_POST['siteids'];
	$product_codes = 'FRESHBOU-'.$cFunc->generateProductCode().rand(0,10);
	
	if(!empty($products_price)){
		//update
		foreach($products_prices as $key => $pricevals){
			$siteids_val = $siteids[$key];
			$priceids = $products_price[$key]['id'];
			$row_arrs = array('product_id' => $product_id, 'price' => $pricevals, 'site_id' => $siteids_val);
			$update_stmt = $obj->update_row('op2mro9899_products_price', $row_arrs,"WHERE id = '$priceids'");
		}
	}else{
		//Insert if data empty in database
		foreach($products_prices as $keyvals => $pricevalues){
			$siteid_vals = $siteids[$keyvals];
			$price_args = array('product_id' => $product_id, 'price' => $pricevalues, 'site_id' => $siteid_vals);
			$obj->insert_records('op2mro9899_products_price', $price_args);
		}
		
	}
	
	if($chk_pcode[0]->product_code == NULL){
		$code_args = array('product_code' => $product_codes.$product_id);
		$obj->update_row('op2mro9899_products', $code_args, "WHERE pid = '$product_id'");
	}
	
	
	$chk_val = $cFunc->array_equal($chk_enable, $site_array);
	
	
	$cat_ids = $_POST['cat_ids'];
	$category_exists = $catArr;
    $chk_catIds = $cat_ids; 
    $result = $cFunc->array_equal( $chk_catIds, $category_exists);
    
    $full_path = isset($_POST['full_path']) ? $_POST['full_path'] : '';
    $medium_path = isset($_POST['medium_path']) ? $_POST['medium_path'] : '';
    $thumbnail_path = isset($_POST['thumbnail_path']) ? $_POST['thumbnail_path'] : '';
    
    $imgTmpArr = $_FILES['product_img']['tmp_name'];
	$imgNameArr = array_filter($_FILES['product_img']['name']);

	//print_r( $_FILES['product_img']); die;
   
	$row_arr = array('product_name' => $pro_name, 'description' => $product_desc, 'short_description' => $product_sdesc, 'regular_price' => $product_price, 'large_price' => $large_price, 'disscount_price' => $dis_price);
	$update_stmt = $obj->update_row('op2mro9899_products', $row_arr,"WHERE pid = '$product_id'");
	if($update_stmt){
		if ($result) {
			}else{
				$obj->deleteRow('op2mro9899_products_relation', 'pid', $product_id);
				foreach($cat_ids as $cids){
					$row_arr = array('pid' => $product_id, 'cat_id' => $cids);
					$obj->insert_records('op2mro9899_products_relation', $row_arr);
				}
		}
		
		
		if($chk_val){}else{
			$obj->deleteRow('op2mro9899_product_site_relation', 'pid', $product_id);
			foreach($chk_enable as $post_ids){
				$relation_arr = array('pid' => $product_id, 'site_id' => $post_ids);
				$obj->insert_records('op2mro9899_product_site_relation', $relation_arr);
			}
		}
		
		
		if(!empty($imgNameArr)){
			$img_size = $cFunc->createFolder();

			foreach($imgNameArr as $imgKey=>$imgName){
				$source = $upload_path.'/'.'full/';

				$imgExtension = time().rand(290,7689).'.'.@end(explode('.',$imgName));

				$img_array = array('full_path' => $source.$imgExtension, 'medium_path' => $img_size['medium']['path'].$imgExtension, 'thumbnail_path' => $img_size['thumbnail']['path'].$imgExtension, 'pid' => $product_id);
				//print_r($img_array); die;
				$img_stmt = $obj->insert_records('op2mro9899_products_image', $img_array);
				
				$createimagestatus = move_uploaded_file($imgTmpArr[$imgKey],$source.$imgExtension);
				if($createimagestatus){
					foreach($img_size as $key=>$imgSize){
						$cFunc->make_thumb($source.$imgExtension,$imgSize['path'].$imgExtension,$imgSize['width'],$imgSize['heigth']);
					}
				}
			}
	   }
		header("location:edit-product.php?pid=".$_REQUEST['pid']."&msg=success");
	}
		
}
?>

<style>
 .imgClass {
    display: inline-block;
    width:80px;
    text-align:center;
    margin-top:10px;
}
.imgClass img{border-radius:5px;}

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

<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<script>
$(document).ready(function(){
	
	$( ".sortable" ).sortable();
	$(document).on('click','#save-reorder',function(evt){
		evt.preventDefault();
		var list = new Array();
		$('.sortable').find('.imgClass').each(function(){
		var id=$(this).attr('data-ids');    
		list.push(id);
		});
		var data=JSON.stringify(list);
		$.ajax({
			url:'action/product.php',
			type:'POST',
			data: {action:'imageorder',data:data},
			cache:false,
			beforeSend:function(){
				$(".loader").show();
			},
			complete:function(){
				$(".loader").hide();
			},
			success:function(resp){
				if(resp){
					$(".alert-success").show();
				}
			}
		});

	});
	
	$(".delete").on('click', function(){
		var imgId = $(this).attr('data-id');
		var checkstr =  confirm('are you sure you want to remove this?');
		var loader = $(this).siblings(".ajloader");

		if(checkstr == true){
			if(imgId!=''){
				
				$.ajax({
					url:'action/product.php',
					type:'POST',
					cache:false,
					data:{
						action:'delete_image',
						img_id:imgId,
					},
					beforeSend: function() {	
						$(loader).show();
					},
					complete: function(){
						$(loader).hide();
					},
					success: function(json){
						//alert(json); return false;
						window.location.href='';
						
					}
				});
			}
		}
	});
	
	
	 $("#product_img").change(function (e) {
		var numFiles = $("input:file")[0].files.length;
		var numFilespre = $(".delete").length;
		//alert(numFilespre); return false;
		var numFilesTotal = numFiles + numFilespre;
		//console.log(numFilesTotal);
		if(numFilesTotal > 3){
			alert('you have cross the limit');
			$("#product_img").val('');
	    }else{
			
			var files = e.originalEvent.target.files;
			for (var i=0, len=files.length; i<len; i++){
				var n = files[i].name,
				    s = files[i].size,
				    t = files[i].type;
				if (s > 7340032) {
				  alert('Please deselect this file: "' + n + '," it\'s larger than the maximum filesize allowed. Sorry!');
				  $("#product_img").val('');
				}
			}
			
			 if (typeof (FileReader) != "undefined") {
				var dvPreview = $("#dvPreviewinner");
				dvPreview.html("");
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|)$/;
				$($(this)[0].files).each(function () {
					var file = $(this);
					if (regex.test(file[0].name.toLowerCase())) {
						var reader = new FileReader();
						reader.onload = function (e) {
							var img = $("<img />");
							img.attr("style", "height:100px;width: 100px;margin:5px 5px 0 0; border-radius:5px;");
							img.attr("src", e.target.result);
							dvPreview.append(img);
						}
						reader.readAsDataURL(file[0]);
					} else {
						alert(file[0].name + " is not a valid image file.");
						dvPreview.html("");
						return false;
					}
				});
			} else {
				alert("This browser does not support HTML5 FileReader.");
			}
		 
	    }
     });
     
     $(".close").on('click', function(){
		$(".alert-success").hide();
	 });
	 
	 $("#pricechk").on('change',function(){
		 var chk = $(this).is(':checked');
		 if(chk == true){
			var product_prices = $("#product_prices").val();
			$(".inputval").each(function( index ) {
				$(".inputval").val(product_prices);
			});
		}
	 });
	
});
</script>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Add Products</h2>
            </div>
            <div class="box-content">
                <form name="pfrm" id="pfrm" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product">Product Name</label>
                        <input class="form-control validate" id="product_name" name="product_name" value="<?php echo $product_name; ?>" placeholder="Enter product name" type="text">
                        <ul class="error" style="display:none;"><li>Please enter product name</li></ul>
                    </div>
                    
                    <div class="form-group">
						<label for="description">Description</label>
						<textarea class="form-control validate" id="product_desc" name="product_desc"><?php echo $description; ?></textarea>
						<ul class="error" style="display:none;"><li>Please enter description</li></ul>
                    </div>
                    
                     <div class="form-group">
						<label for="description">Short Description</label>
						<textarea class="form-control validate" id="product_sdesc" name="product_sdesc"><?php echo $short_description; ?></textarea>
						<ul class="error" style="display:none;"><li>Please enter short description</li></ul>
                    </div>
                    
					<div class="form-group">
						<label>Check for all price</label>
						<input name="chkprice" value="0" type="checkbox" id="pricechk" class="">
					</div>
                    
                    
                     <?php 
						$jk = 1;
						foreach($db_rows as $keys => $price_val){
							$domain_name = $price_val['domain_name'];
							$site_ids = $price_val['site_id'];
							$prices = isset($products_price[$keys]['price']) ? $products_price[$keys]['price'] : '0.00';
							//$prices = (($priceids <> '') ? $priceids : '0.00');
							$chk_class = (($jk == 1) ? '' : 'inputval');
                     ?>
                     <div class="form-group">
                        <label for="price"><?php echo $domain_name; ?> Price</label>
                        <input type="hidden" name="siteids[]" value="<?php echo $site_ids; ?>">
                        <input class="form-control <?php echo $chk_class; ?>" id="product_prices" name="products_prices[]" value="<?php echo $prices; ?>" placeholder="Enter Standard Price" type="text">
                    </div>
                     <?php $jk++; } ?>
                     
                     <div class="form-group">
                        <label for="price">Standard Regular Price</label>
                        <input class="form-control validate" id="product_price" name="product_price" value="<?php echo $regular_price; ?>" placeholder="Enter Standard Price" type="text">
                        <ul class="error" style="display:none;"><li>Please enter price</li></ul>
                    </div>
                    
                     <div class="form-group">
                        <label for="disscountPrice">Standard Discount Price</label>
                        <input class="form-control" id="dis_price" name="dis_price" value="<?php echo $disscount_price; ?>" placeholder="Enter Disscount Price" type="text">
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="price">Large Price</label>
                        <input class="form-control validate" id="large_price" name="large_price" value="<?php echo $largePrice; ?>" placeholder="Enter Large Price" type="text">
                        <ul class="error" style="display:none;"><li>Please enter price</li></ul>
                    </div>
                    
                   
                    <div class="control-group">
                    <label class="control-label" for="selectError1">Add Category</label>

                    <div class="controls">
                        <select id="selectError1" name="cat_ids[]" multiple class="form-control validate" data-rel="chosen" >
                            <?php
								foreach($cat_tree as $cat_val){
									$cat_ids = $cat_val['id'];
									$parent_id = $cat_val['parent'];
									$cat_name = $cat_val['name'];
									$selected = ((in_array($cat_ids, $catArr)) ? 'selected' : '');
									
									echo '<option '.$selected.' value="'.$cat_ids.'">'.$cat_name.'</option>';
								}
                            ?>
                        </select>
                        <ul class="error" style="display:none;"><li>Please select categories</li></ul>
                    </div>
                </div>
                
                <br /><br />
                
                 <div class="collectionOfFOrmGroup">
						<?php
						foreach($db_rows as $db_val){
							$domian_name = $db_val['domain_name'];
							$site_id = $db_val['site_id'];					
							$checked = ((in_array($site_id, $site_array, true)) ? 'checked' : 'false');
							echo '<div class="form-group">
							<label>'.$domian_name.'</label>
							<input data-no-uniform="true" '.$checked.' name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
							</div>';
						}
						?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
              
                
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input type="file"  multiple="multiple" name="product_img[]" id="product_img" accept="image/*">
                        <p class="help-block"><strong>Upload image size 500x600</strong></p>
                        <ul class="error" style="display:none;"><li>Please upload your image.</li></ul><br />
                        <button class="btn btn-default" id="save-reorder">Save Order</button><br />
                        <img src="img/ajax-loaders/ajax-loader-1.gif" class="loader" title="Please wait" style="display:none;">
                        <div id="dvPreview" class="sortable">
						<?php
						  foreach($p_img as $imgSrc){
							  echo '<input type="hidden" name="full_path[]" value="'.$imgSrc['full_path'].'">
							        <input type="hidden" name="medium_path[]" value="'.$imgSrc['medium_path'].'">
							        <input type="hidden" name="thumbnail_path[]" value="'.$imgSrc['thumbnail_path'].'">';
							  echo '<div class="imgClass" data-ids="'.$imgSrc['img_id'].'"><img src="'.$imgSrc['thumbnail_path'].'" width="80" height="80">
								    <a href="javascript:void(0);" class="delete" data-id="'.$imgSrc['img_id'].'"><span class="label-default label label-danger">Delete</span></a>
								    <img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
								    </div>';
						  }
						?>
						</div>
						<div id="dvPreviewinner"></div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>
                <br />
				<div class="alert alert-success" style="display:none;">
					<button type="button" class="close">×</button>
						Order saved successfully.
				</div>
            </div>
        </div>
    </div>
    <!--/span-->

</div>
<script src="ckeditor/ckeditor.js"></script>
<script> 
	CKEDITOR.replace( 'product_desc' );
	CKEDITOR.replace( 'product_sdesc' );
</script>

<?php include_once 'footer.php'; ?>
