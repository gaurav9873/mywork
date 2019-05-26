<?php include_once 'header.php'; 

$req_id = $obj->DecryptClientId($_REQUEST['cat_id']);
$cat_id = intval($req_id);
$url = API_PATH."category_products/1/$cat_id";
$json_data = $obj->getCurl($url);
$category_products = $json_data->category_products;

//Category description
$caturi = API_PATH.'cat-desc/'.$req_id.'';
$get_desc = $obj->getCurl($caturi); 
$cat_detail = isset($get_desc->cat_desc[0]) ? $get_desc->cat_desc[0] : '';
$c_name = isset($cat_detail->category_name) ? $cat_detail->category_name : '';
?>

<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12"><h2><?php echo $c_name; ?></h2></div> 
        </div>
        <div class="row">
    		
			<?php
			$html = '';
			foreach($category_products as $post_product){
				
				$pid = $obj->EncryptClientId($post_product->pid);
				$product_name = $post_product->product_name;
				$cat_id = $post_product->cat_id;
				$medium_path = $post_product->medium_path;
				$description = $post_product->description;
				$short_description = $post_product->short_description;
				$regular_price = $post_product->regular_price;
				$disscount_price = $post_product->disscount_price;
				$final_price = (($disscount_price <> '') ? $disscount_price : $regular_price);
					
				$html = '<div class="col-lg-3 col-md-3 col-sm-3">';
				$html .= '<div class="bridal-cat">';
				$html .= '<a href="product-detail.php?product_id='.$pid.'">';
				$html .= '<img src="'.IMG_PATH.$medium_path.'" class="img-responsive" alt="img"/>';
				$html .= '<h3>'.$product_name.'</h3>';
				$html .= '<span>';
				$html .= '£ '.$final_price.'';
				if($disscount_price <> ''){
					$html .= '<del>£'.$regular_price.'</del>';
				}
				$html .= '</span>';
				$html .= '</a>';
				$html .= '</div>';
				$html .= '</div>'; 
				
				echo $html;
			}
			?>
        </div>
    </div>
</section>            

<?php include_once 'footer.php'; ?>
