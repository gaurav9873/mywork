<?php include_once 'header.php'; 
$dbobj = new ConnectDb();

$site_id = SITE_ID;
$req_id = $obj->DecryptClientId($_REQUEST['cat_id']);
$cat_id = intval($req_id);
$url = API_PATH."category_products/$site_id/$cat_id";
$json_data = $obj->getCurl($url);
$category_products = $json_data->category_products;

//Category description
$caturi = API_PATH.'cat-desc/'.$req_id.'';
$get_desc = $obj->getCurl($caturi); 
$cat_detail = isset($get_desc->cat_desc[0]) ? $get_desc->cat_desc[0] : '';
$c_name = isset($cat_detail->category_name) ? $cat_detail->category_name : '';

$cat_products_data = API_PATH.'cat-product/'.$site_id.'/'.$cat_id.'';
$json_products_data = $obj->getCurl($cat_products_data);

/*foreach($json_products_data->cat_products as $product_vals){
	$cat_product_image = API_PATH.'cat-product-image/'.$product_vals->pid.'';
	$image_data = $obj->getCurl($cat_product_image);
	echo $image_data->product_image[0]->medium_path;
}*/

$cat_names = $dbobj->get_category_name_by_id($req_id);
$canmes = json_decode($cat_names);
$catNames = $canmes[0]->category_name;
?>
<br />
<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12"><h2><?php echo $catNames; ?></h2></div> 
        </div>
        <div class="row">
    		
			<?php
			if(empty($category_products)){ echo '<div class="col-lg-12" style="text-align:center;"><h3>Sorry!! Product not found in this category</h3></div>'; }else{
				$html = '';
				foreach($json_products_data->cat_products as $post_product){ //print_r($post_product);
					
					$pid = $obj->EncryptClientId($post_product->pid);
					$product_name = $post_product->product_name;
					$cat_id = $post_product->cat_id;
					$regular_price = $post_product->regular_price;
					$product_prices = $post_product->price;
					//$chk_reg_price = (($product_prices == '0.00') ? $regular_price : $product_prices);
					$chk_reg_price = (($product_prices == '0.00') ? $regular_price : (($product_prices == '') ? $regular_price : $product_prices));
					$disscount_price = $post_product->disscount_price;
					$final_price = (($disscount_price <> '') ? $disscount_price : $chk_reg_price);
					
					$cat_product_image = API_PATH.'cat-product-image/'.$post_product->pid.'';
					$image_data = $obj->getCurl($cat_product_image);
					$medium_path = $image_data->product_image[0]->medium_path;
					
					
						
					$html = '<div class="col-lg-3 col-md-3 col-sm-3">';
					$html .= '<div class="bridal-cat gridHitNew">';
					$html .= '<a href="product-detail?product_id='.$pid.'">';
					$html .= '<img src="'.IMG_PATH.$medium_path.'" class="img-responsive" alt="img"/>';
					$html .= '</a>';
					$html .= '<h3>'.$product_name.'</h3>';
					$html .= '<span>';
					$html .= '£ '.$final_price.'';
					if($disscount_price <> ''){
						$html .= '<del>£'.$product_prices.'</del>';
					}
					$html .= '</span>';
					$html .= '</div>';
					$html .= '</div>'; 
					
					echo $html;
				}
			}
			?>
        </div>
    </div>
</section>            

<?php include_once 'footer.php'; ?>
