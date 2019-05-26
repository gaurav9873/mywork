<?php include_once 'header.php'; 

//~ $url = 'http://54.191.172.136:82/florist-admin/flowers/api/category-image/1';
//~ $json_decode = $obj->getCurl($url);
//~ $category_images = $json_decode->category_images;

$cat_id = $obj->DecryptClientId($_REQUEST['category_id']);
$childCatApi = API_PATH.'child-cat/'.$cat_id.'';
$getCurl = $obj->getCurl($childCatApi); 
$child_cat = $getCurl->child_cat;

//Category description
$caturi = API_PATH.'cat-desc/'.$cat_id.'';
$get_desc = $obj->getCurl($caturi); 
$cat_detail = $get_desc->cat_desc[0];

$cid = $cat_detail->cat_id;
$c_name = $cat_detail->category_name;
$cat_description = $cat_detail->category_description;
$img_full_path = $cat_detail->full_path;
$img_medium_path = $cat_detail->medium_path;
$img_thumbnail_path = $cat_detail->thumbnail_path;
?>
<div class="clearfix"></div>
<section>
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
<div class="clearfix"></div>
<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12">
				<?php
					/*foreach($category_images as $post_cat){
						$cat_id = $obj->EncryptClientId($post_cat->cat_id);
						$category_name = $post_cat->category_name;
						$parent_category = $post_cat->parent_category;
						$full_path = $post_cat->full_path;
						$medium_path = $post_cat->medium_path;
						$thumbnail_path = $post_cat->thumbnail_path;
						echo '<div class="flow-cate">
						<a href="cat-product.php?cat_id='.$cat_id.'">
						<img src="http://54.191.172.136:82/florist-admin/'.$medium_path.'" class="img-responsive" alt="img"/>
						<span>'.$category_name.'</span>
						</a>
						</div>';
					}*/
					
					foreach($child_cat as $post_val){
						$cat_ids = $obj->EncryptClientId($post_val->cat_id);
						$category_name = $post_val->category_name;
						$category_description = $post_val->category_description;
						$parent_category = $post_val->parent_category;
						$full_path = $post_val->full_path;
						$medium_path = $post_val->medium_path;
						$thumbnail_path = $post_val->medium_path;
						echo '<div class="flow-cate">
						<a href="cat-product.php?cat_id='.$cat_ids.'">
						<img src="'.IMG_PATH.''.$medium_path.'" class="img-responsive" alt="img"/>
						<span>'.$category_name.'</span>
						</a>
						</div>';
					}
					
					
				?>
            </div> 
        </div>
    </div>
</section>            
<?php include_once 'footer.php'; ?>
