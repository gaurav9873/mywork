<?php
include_once 'header.php';

$dbobj = new ConnectDb();

$site_id = SITE_ID;
$cat_id = $obj->DecryptClientId($_REQUEST['category_id']);
$childCatApi = API_PATH.'child-cat/'.$site_id.'/'.$cat_id.'';
$getCurl = $obj->getCurl($childCatApi); 
$child_cat = $getCurl->child_cat;

//Category description
$caturi = API_PATH.'cat-desc/'.$site_id.'/'.$cat_id.'';
$get_desc = $obj->getCurl($caturi); 
$cat_detail = $get_desc->cat_desc[0];

$cid = $cat_detail->cat_id;
$c_name = $cat_detail->category_name;
$cat_description = $cat_detail->category_description;
$img_full_path = $cat_detail->full_path;
$img_medium_path = $cat_detail->medium_path;
$img_thumbnail_path = $cat_detail->thumbnail_path;


$req_id = $obj->DecryptClientId($_REQUEST['category_id']);
$cat_ids = intval($req_id);
/*$url = API_PATH."category_products/$site_id/$cat_ids";
$json_data = $obj->getCurl($url);
$category_products = $json_data->category_products;*/

$cat_products_data = API_PATH.'cat-product/'.$site_id.'/'.$cat_id.'';
$json_products_data = $obj->getCurl($cat_products_data);

$cat_names = $dbobj->get_category_name_by_id($cat_id);
$canmes = json_decode($cat_names);
$catNames = $canmes[0]->category_name;
?>

<?php if($img_medium_path <> ''){ ?>
<div class="clearfix"></div>
<section class="responsPnone">
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-5 col-md-5 col-sm-5">
            	<aside class="img-full-width">
                	<img src="<?php echo IMG_PATH.$img_medium_path; ?>" class="img-responsive" alt="Flower" />
                </aside>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7">
            	<aside class="right-side">
                	<h2><?php echo $c_name; ?></h2>
                	<?php echo $cat_description; ?>
                </aside>
            </div>
    	</div>
    </div>
</section>
<?php } ?>
<div class="clearfix"></div>
<br /><br />

<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12"><h2><?php echo strip_tags($catNames); ?></h2></div> 
        </div>
        <div class="row">
    		
			<?php
			if(empty($json_products_data)){ echo '<div class="col-lg-12" style="text-align:center;"><h3>Sorry!! Product not found in this category</h3></div>'; }else{
				$html = '';
				foreach($json_products_data->cat_products as $post_product){ //print_r($post_product);
					
					$pid = $obj->EncryptClientId($post_product->pid);
					$product_name = $post_product->product_name;
					$cat_id = $post_product->cat_id;
					//$medium_path = $post_product->medium_path;
					$description = $post_product->description;
					$short_description = $post_product->short_description;
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
