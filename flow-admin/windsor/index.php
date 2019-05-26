<?php include_once 'header.php'; 

$parent_cat = API_PATH.'site-category/1';
$parent_data = $obj->getCurl($parent_cat);
$parent_categories = $parent_data->parent_categories;

$slider_api = API_PATH.'slider';
$slider_data = $obj->getCurl($slider_api);
$post_data = $slider_data->slider;

//Content
$page_con_api = API_PATH.'home-content';
$page_data = $obj->getCurl($page_con_api);
$page_content = $page_data->page_content[0];
$page_name = $page_content->page_name;
$page_content = $page_content->page_content;
?>
<section>
	<div class="container-fluid">
   	<div class="row">
        	<div class="banner">                 
               <div id="bannerscollection_zoominout_opportune">
                    <div class="myloader"></div>
                    <ul class="bannerscollection_zoominout_list">
                        <li data-initialZoom="0.77" data-finalZoom="1" data-horizontalPosition="left" data-verticalPosition="center" data-bottom-thumb="<?php echo IMG_PATH.$post_data[0]->full_path; ?>" data-text-id="#bannerscollection_zoominout_photoText1" data-autoPlay="10">
                        <img src="<?php echo IMG_PATH.$post_data[0]->full_path; ?>" alt="" width="2500" height="782" /></li>
                        
                        <li data-initialZoom="0.77" data-finalZoom="1" data-horizontalPosition="left" data-verticalPosition="top" data-bottom-thumb="<?php echo IMG_PATH.$post_data[1]->full_path; ?>" data-text-id="#bannerscollection_zoominout_photoText2" data-link="gallery.html"><!-- data-target="_blank"-->
                        <img src="<?php echo IMG_PATH.$post_data[1]->full_path; ?>" alt="" width="2500" height="782" /></li>    
                        
                        <li data-horizontalPosition="left" data-verticalPosition="top" data-initialZoom="1" data-finalZoom="0.77" data-bottom-thumb="<?php echo IMG_PATH.$post_data[2]->full_path; ?>" data-text-id="#bannerscollection_zoominout_photoText4">
                        <img src="<?php echo IMG_PATH.$post_data[2]->full_path; ?>" alt="" width="2500" height="782"  /></li>
                        
                        <li data-horizontalPosition="center" data-verticalPosition="center" data-initialZoom="1" data-finalZoom="0.77" data-duration="15" data-bottom-thumb="<?php echo IMG_PATH.$post_data[3]->full_path; ?>" data-text-id="#bannerscollection_zoominout_photoText5">
                        <img src="<?php echo IMG_PATH.$post_data[3]->full_path; ?>" alt="" width="2500" height="782" /></li>
                       
                    </ul>
                                     
					<?php
						//Slider Text
						
						foreach($post_data as $postVal){
							$content = html_entity_decode($postVal->slider_text);
							echo  stripslashes($content); 
						}
					?>                   
               </div>                   
            </div>
        </div>
    </div>
</section>


<div class="clearfix"></div>
<section class="bg-body">
	<div class="container-fluid">
    	
    	<div class="row">
        	
        	<?php
				$count = 0;
				foreach($parent_categories as $post_val){
					$cat_id = $obj->EncryptClientId($post_val->cat_id);
					$category_name = $post_val->category_name;
					$parent_category = $post_val->parent_category;
					$full_path = $post_val->full_path;
					$medium_path = $post_val->medium_path;
					$thumbnail_path = $post_val->thumbnail_path;
					$img = (($count == 0) ? $medium_path : $thumbnail_path);
					$class = (($count == 0) ? 'col-lg-6 col-md-6 col-sm-6' : 'col-lg-3 col-md-3 col-sm-3');
        	?>
        	
        	<div class="<?php echo $class; ?>">
            	<div class="f-categories">
                	<a href="categories.php?category_id=<?php echo $cat_id; ?>">
                        <img src="http://54.191.172.136:82/florist-admin/<?php echo $img; ?>" alt="img" />
                        <span><?php echo $category_name; ?> </span>
                    </a>
                </div>
            </div>
            <?php $count++;} ?>
            
        </div>
        
    </div>
</section>
<div class="clearfix"></div>
<section class="bg-body">
	<div class="container-fluid">
    	<div class="row">
        	<div class="col-lg-12">
            	<h1><?php echo $page_name; ?></h1>
                <?php echo $page_content; ?>
            </div>
        </div>
    </div>
</section>
<?php include_once 'footer.php'; ?>
