<?php 
include_once 'header.php'; 

$cat_menu = API_PATH.'pcat-smap';
$jdata = $obj->getCurl($cat_menu);

?>

<section>
  <div class="container">
    <div class="row paginat">
      <h1>Sitemap</h1>
      <div class="col-lg-6">
        <?php
		foreach($jdata->site_map as $cat_val){
			$cat_ids = $obj->EncryptClientId($cat_val->cat_id);
			$category_name = $cat_val->category_name;
			
			$child_cat = API_PATH.'child-smap/'.$cat_val->cat_id.'';
			$jobj = $obj->getCurl($child_cat);
		?>
        <div class="title"><?php echo $category_name; ?></div>
        <?php if(!empty($jobj)){ ?>
        <ul>
          <?php 
			foreach($jobj->child_site_map as $childData){
				$child_cat_id = $obj->EncryptClientId($childData->cat_id);
				$child_category_name = $childData->category_name;
			?>
          <li><a href="cat-product.php?cat_id=<?php echo $child_cat_id; ?>"><?php echo $child_category_name; ?></a></li>
          <?php } ?>
        </ul>
        <?php } } ?>
      </div>
      <!--<div class="col-lg-6">
        <div class="title">Gift Flowers</div>
        <ul>
        <li><a href="cat-product.php?cat_id=eccbc87e3">Birthday</a></li>
        <li><a href="cat-product.php?cat_id=e4da3b7f5">New Baby</a></li>
        <li><a href="cat-product.php?cat_id=8f14e45f7">Anniversary</a></li>
        <li><a href="cat-product.php?cat_id=c9f0f8958">Congratulations</a></li>
        <li><a href="cat-product.php?cat_id=45c48cce9">Thankyou</a></li>
        <li><a href="cat-product.php?cat_id=9bf31c7ff">New Arrivals</a></li>
      </ul>
      </div>-->
    </div>
  </div>
</section>
<?php include_once 'footer.php'; ?>
