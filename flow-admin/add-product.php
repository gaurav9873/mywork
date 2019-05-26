<?php
include_once 'header.php';

$obj = new Connectdb();
$cFunc = new CustomFunctions();

$db_rows = $obj->fetchAll('op2mro9899_add_domain');


$cat_tree = $obj->fetchCategoryTree();

$folder_path = $cFunc->createFolder();

$upload_path = './uploads/products/'.date('Y').'/'.date('m');

if(isset($_POST['submit'])){
	
	$product_name = addslashes($_POST['product_name']);
	$product_desc = addslashes($_POST['product_desc']);
	$product_sdesc = addslashes($_POST['product_sdesc']);
	$product_price = $cFunc->xss_clean($_POST['product_price']);
	$large_price = $cFunc->xss_clean($_POST['large_price']);
	$dis_price = $cFunc->xss_clean($_POST['dis_price']);
	$created_ip = $cFunc->get_client_ip();
	$date = date("Y-m-d H:i:s");
	$cat_ids = $_POST['cat_ids'];
	$chk_enable = $_POST['chk_enable'];
	$product_code = 'FRESHBOU-'.$cFunc->generateProductCode().rand(0,700);
	
	$imgTmpArr = $_FILES['product_img']['tmp_name'];
	$imgNameArr = $_FILES['product_img']['name'];
	
	$parray = array('product_name' => $product_name, 'description' => $product_desc, 'short_description' => $product_sdesc, 'regular_price' => $product_price, 'large_price' => $large_price, 'disscount_price' => $dis_price, 'product_code' => $product_code, 'created_date' => $date, 'created_ip' => $created_ip);
	$pid_stmt =  $obj->insert_records('op2mro9899_products', $parray);
	
	
	if($pid_stmt){
		$img_size = $cFunc->createFolder();
		foreach($cat_ids as $pid_val){
			$pr_array = array('pid' => $pid_stmt, 'cat_id' => $pid_val);
			$pr_stmt = $obj->insert_records('op2mro9899_products_relation', $pr_array);
		}
		
		
		foreach($chk_enable as $post_ids){
			$relation_arr = array('pid' => $pid_stmt, 'site_id' => $post_ids);
			$obj->insert_records('op2mro9899_product_site_relation', $relation_arr);
		}
		

		foreach($imgNameArr as $imgKey=>$imgName){
			$source = $upload_path.'/'.'full/';
			$imgExtension = time().rand(290,7689).'.'.@end(explode('.',$imgName));

			$img_array = array('full_path' => $source.$imgExtension, 'medium_path' => $img_size['medium']['path'].$imgExtension, 'thumbnail_path' => $img_size['thumbnail']['path'].$imgExtension, 'pid' => $pid_stmt);
			$img_stmt = $obj->insert_records('op2mro9899_products_image', $img_array);
			
			$createimagestatus = move_uploaded_file($imgTmpArr[$imgKey],$source.$imgExtension);
			if($createimagestatus){
				foreach($img_size as $key=>$imgSize){
					$cFunc->make_thumb($source.$imgExtension,$imgSize['path'].$imgExtension,$imgSize['width'],$imgSize['heigth']);
				}
			}
		}
		header("location:products-list.php");
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
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
	
$(document).ready(function(){
    $("#product_img").on('change', function (e) {
		var numFiles = $("input:file")[0].files.length;
		
		if(numFiles > 3){
			alert('You have exceeded the limit');
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
				var dvPreview = $("#dvPreview");
				dvPreview.html("");
				//var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
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
    
    
	$("#pfrm").on('submit',function(e){
		var errorFlag = false;
		$(".validate").each(function(){
			if($(this).val() == ''){ 
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').css('color', 'red').show();
				errorFlag = true;
			}else{
			}
		});

		$(".validate").on('keypress change',function(){
			if($(this).val() ==''){
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').show();
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
				$(this).parent().find('.error').hide();
			}
		});
		if(errorFlag){
			e.preventDefault();
		}
	});
	
	
	$('.proprice').on('change',function(){
		var val = $('.proprice').val();
		console.log(val);
		$('.proprice').text( val !== '' ? val : '(empty)' );
	});
	$('.proprice').number( true, 2 );
	
    
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
                        <input class="form-control validate" id="product_name" name="product_name" placeholder="Enter product name" type="text">
                        <ul class="error" style="display:none;"><li>Please enter product name</li></ul>
                    </div>
                    
                    <div class="form-group">
						<label for="description">Description</label>
						<textarea class="form-control" id="product_desc" name="product_desc"></textarea>
						<ul class="error" style="display:none;"><li>Please enter description</li></ul>
                    </div>
                    
                     <div class="form-group">
						<label for="description">Short Description</label>
						<textarea class="form-control" id="product_sdesc" name="product_sdesc"></textarea>
						<ul class="error" style="display:none;"><li>Please enter short description</li></ul>
                    </div>
                    
                     <div class="form-group">
                        <label for="price">Standard Regular Price</label>
                        <input class="form-control validate proprice" id="product_price" name="product_price" placeholder="Enter Price" type="text">
                        <ul class="error" style="display:none;"><li>Please enter price</li></ul>
                    </div>
                    
                    <div class="form-group">
                        <label for="disscountPrice">Standard Discount Price</label>
                        <input class="form-control proprice" id="dis_price" name="dis_price" placeholder="Enter Disscount Price" type="text">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Large Price</label>
                        <input class="form-control validate proprice" id="large_price" name="large_price" placeholder="Enter Price" type="text">
                        <ul class="error" style="display:none;"><li>Please enter large price</li></ul>
                    </div>
                    
                    <div class="control-group">
                    <label class="control-label" for="selectError1">Add Category</label>

                    <div class="controls">
                        <select id="selectError1" name="cat_ids[]" multiple class="form-control validate" data-rel="chosen" >
							<!--<option selected value="">Please select category</option>-->
                            <?php
								foreach($cat_tree as $cat_val){
									$cat_id = $cat_val['id'];
									$parent_id = $cat_val['parent'];
									$cat_name = $cat_val['name'];
									echo '<option value="'.$cat_id.'">'.$cat_name.'</option>';
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

							echo '<div class="form-group">
							<label>'.$domian_name.'</label>
							<input data-no-uniform="true" name="chk_enable[]" value="'.$site_id.'" type="checkbox" class="iphone-toggle check-tiggle">
							</div>';
						}
						?>
                    </div>
                    <div class="error" style="color:red; display:none;">Please select at least one checkbox.</div>
              
                
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input type="file"  multiple="multiple" name="product_img[]" id="product_img" accept="image/*">
                        <p class="help-block"><strong>Upload image size 500x600</strong></p>
                        <ul class="error" style="display:none;"><li>Please upload your image.</li></ul>
                        <div id="dvPreview"></div>

                        <p class="help-block"></p>
                    </div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

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

<?php include 'footer.php'; ?>
