<?php
include_once 'header.php';

//$app->get('/search-product/:search_string', 'search_product');
//$app->run('/pro-img/:pid', 'search_pimage');

$search_keyword = $obj->xss_clean($_REQUEST['sq']);
$search_url = API_PATH."search-product/$search_keyword";
$json_data = $obj->getCurl($search_url);
?>

<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12"><h2><?php echo ucwords($search_keyword); ?></h2></div> 
        </div>
        <div class="row">
    		
    		<?php 
				if(!empty($json_data->serach_items)){
					
				foreach($json_data->serach_items as $val){
					$product_id = $val->pid;
					$product_name = $val->product_name;
					$regular_price = $val->regular_price;
					$large_price = $val->large_price;
					$disscount_price = $val->disscount_price;
					$product_code = $val->product_code;
					$final_price = (($disscount_price <> '') ? $disscount_price : $regular_price);
					
					//product image
					$img_uri = API_PATH."pro-img/$product_id";
					$json_data_img = $obj->getCurl($img_uri);
					$product_image_path = $json_data_img->pimage[0]->medium_path;
    		?>
    		
    		<div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="product-detail.php?product_id=<?php echo $obj->EncryptClientId($product_id); ?>">
                        <img src="<?php echo IMG_PATH.$product_image_path; ?>" class="img-responsive" alt="img"/>
                        <h3><?php echo $product_name; ?></h3>
                        <span>£<?php echo $final_price; if($disscount_price <> ''){?> 
                            <del>£<?php echo $regular_price; ?></del>
                        <?php } ?>
                        </span>
                    </a>
                </div>
            </div> 
            
            <?php }  }else{ echo "<h2>OOPS! product not found</h2>"; }?>
            
            <!--<div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
            	<div class="bridal-cat">
                	<a href="shoppingPage.html">
                        <img src="images/cat1.jpg" class="img-responsive" alt="img"/>
                        <h3>Blush Rose*</h3>
                        <span>£100.00 <del>£124.95</del></span>
                    </a>
                </div>
            </div>-->
        </div>
    </div>
</section>  

<?php include_once 'footer.php'; ?>
