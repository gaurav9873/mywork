<?php include_once 'header.php'; 

$parent_cat = API_PATH.'site-category/2';
$parent_data = $obj->getCurl($parent_cat);
$parent_categories = $parent_data->parent_categories;


$slider_api = API_PATH.'slider/'.SITE_ID.'';
$slider_data = $obj->getCurl($slider_api);
$post_data = $slider_data->slider;
krsort($post_data);

//Content
$page_con_api = API_PATH.'home-content/'.SITE_ID.'';
$page_data = $obj->getCurl($page_con_api);
$page_content = $page_data->page_content[0];
$page_name = $page_content->page_name;
$page_content = $page_content->page_content;
?>
  
<div class="wide-container">
  <div id="slides">
    <ul class="slides-container">
      
      <?php 
		$numItems = count($post_data);
		$i = 0;
		foreach($post_data as $post_slider){ //print_r($post_slider);
			$slider_id = $post_slider->id;
			$slider_name = $post_slider->slider_name;
			$slider_text = $post_slider->slider_text;
			//$slider_text2 = $post_slider->slider_text2;
			$buynow_texts = $post_slider->buynow_text;
			$full_path = $post_slider->full_path;
			$medium_path = $post_slider->medium_path;
			$thumbnail_path = $post_slider->medium_path;
			$class = ((++$i === $numItems) ? 'forth' : '');
			
			$txt1_content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $post_slider->slider_text1);
			$slider_text1 = preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $txt1_content);
			
			$txt2_content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $post_slider->slider_text2);
			$slider_text2 = preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $txt2_content);
			
			$buy_content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $post_slider->buynow_text);
			$buynow_text = preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $buy_content);
      ?>
      
		<li>
			<div class="container <?php //echo $class; ?>">
				<hgroup>
					<h1 class="wow fadeInUp" data-wow-delay="1.3s"><?php echo $slider_text1; ?></h1>
					<h2 class="wow fadeInDown" data-wow-delay="1.3s"><?php echo $slider_text2; ?></h2>
					<?php //echo $slider_text; ?>
				</hgroup>
				<?php if($buynow_text <> ''){ ?>
				<div class="bannerAdd text-center wow slideInLeft" data-wow-delay="0.5s"> 
					<?php echo $buynow_text;?>
				</div>
				<?php } ?>
			</div>
			<img src="<?php echo IMG_PATH.$full_path; ?>" width="1920" height="600" alt="Cinelli">
		</li>
        
        <?php } ?>
        
    </ul>
    <nav class="slides-navigation"> <a href="#" class="next">Next</a> <a href="#" class="prev">Previous</a> </nav>
  </div>
</div>

  
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
					$count_child_category = API_PATH.'child-category/'.$post_val->cat_id.'';
					$get_curl = $obj->getCurl($count_child_category);
					$count_cat = count($get_curl->child_categories);
					$product_page = (($count_cat == 0) ? 'category-products' : 'categories');
        	?>
      <div class="<?php echo $class; ?>">
        <div class="f-categories"> <a href="<?php echo $product_page; ?>?category_id=<?php echo $cat_id; ?>"> 
        <img src="<?php echo IMG_PATH.$img; ?>" alt="img" class="img-responsive" /> <span><?php echo $category_name; ?> </span> </a> </div>
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
        <?php echo $page_content; ?> </div>
    </div>
  </div>
</section>
<?php include_once 'footer.php'; ?>
