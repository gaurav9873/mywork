<?php
include_once 'header.php';

$api_uri = API_PATH.'all-childs';
$child_con = $obj->getCurl($api_uri);

$key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
$token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
$jwthelper = JWT::encode($token, $key);
?>
<link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<script>
$(document).ready(function(){
	
	var rootUrl = '<?php echo API_PATH; ?>';
	var auth = '<?php echo $jwthelper; ?>';
	$(".submit-query").on('click', function(){
		var form_array = JSON.stringify($('#search_q').serializeObject());
		var search_keyword = $(".user_keyword").val();
		var price_range = $(".price_range").val();
		var parent_category = $(".parent_category").val();
		var sub_category = $(".sub_category").val();
		if(form_array){
			$.ajax({
				url:rootUrl+'advance-search',
				type:'POST',
				contentType: 'application/json',
				dataType: "json",
				data:form_array,
				cache:false,
				beforeSend:function(xhr){
					$(".wait").show();
					xhr.setRequestHeader('Authorization', auth);
				},
				complete:function(){
					$(".wait").hide();
				},
				success:function(req){
					var plength = req.length;
					if(plength==0){
						$(".emptyval").show();
					}else{
						$(".emptyval").hide();
						var html = '';
						$.each(req, function(i, item) {
							html += template(item);					
						});
						$("#pdata").empty().append(html);
					}
				}
			});
		}
	});
	
		
	
	function template(obj) {
		var html = '';
		var final_price = (obj.disscount_price!='') ? obj.disscount_price : obj.price;
		html +='<div class="col-lg-3 col-md-3 col-sm-3">';
		html +='<div class="bridal-cat">';
		html +='<a href="product-detail?product_id='+obj.pid+'">';
		html +='<img src="<?php echo IMG_PATH ?>'+obj.img+'" class="img-responsive" alt="img">';
		html +='</a>';
		html +='<h3>'+obj.product_name+'</h3>';
		html += '<span>';
		html += '£ '+final_price+'';
		if(obj.disscount_price!=''){
			html += '<del>£'+obj.price+'</del>';
		}
		html += '</span>';
		html +='</div>';
		html +='</div>';
		return html;
	}
	
	
	
});
</script>

<section>
	<div class="container-fluid">
        <div class="row">
        	<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            	<h2>Product Search</h2>
            	<form name="search_q" id="search_q" class="search_q" action="" method="post">
					<input type="hidden" name="site_id" value="<?php echo SITE_ID; ?>" />
					<div class="form-group">
						<label for="user_keyword">Keyword Search :</label>
						<input class="form-control user_keyword product-key" name="user_keyword" id="user_keyword" type="text">
					</div>	
					<div class="form-group">
						<label for="parent_category">Search In :</label>
						<select name="parent_category" id="parent_category" class="form-control parent_category product-key">
							<option value="">--All Categories--</option>
							<?php
							   foreach($child_con->chil_cat as $postval){
								   $cat_id = $obj->EncryptClientId($postval->cat_id);
								   $category_name = $postval->category_name;
								echo '<option value="'.$cat_id.'">'.$category_name.'</option>';
							   }
							?>
						</select>
					</div>
					
					
					<div class="form-group">
						<label for="price_range">Standard Price Range :</label>
						<select class="form-control price_range product-key" name="price_range" id="price_range">
							<option value="">--All Price Range --</option>
							<option value="0-10" data-search-type="range">Less than £10</option>
							<option value="10-20" data-search-type="range">£10 - £20</option>
							<option value="20-30" data-search-type="range">£20 - £30</option>
							<option value="30-40" data-search-type="range">£30 - £40</option>
							<option value="40-50" data-search-type="range">£40 - £50</option>
							<option value="50" data-search-type="greater">More than £50</option>
						</select>
					</div>
					<div class="wait" style="color:red; display:none;">Please wait..</div>
					<button type="button" class="btn btn-col2 btnDirectionLeft submit-query">Search</button>
				</div>
            
            </form>
        </div>       
    </div>
    
    <div class="container-fluid">
		<div class="emptyval" style="color:red; display:none">OOPS!! Product not found.</div>
    	<div class="row" id="pdata"></div>
    </div>
    
</section>

<?php include_once 'footer.php'; ?>
