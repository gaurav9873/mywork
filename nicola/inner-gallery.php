<?php 
include_once 'header.php'; 

$gallery_id = $obj->DecryptClientId($_GET['gallery_id']);
$cat_name = $obj->xss_clean($_REQUEST['cat_name']);

$api = API_PATH.'all-gallery/'.$gallery_id;
$json = $obj->getCurl($api);
$all_images = $json->all_images;
?>

<section>
  <div class="container">
    <div class="row marginTop20">
      <div class="col-sm-12 text-center">
        <h2><?php echo $cat_name; ?></h2>
      </div>
    </div>
    <div class="row pgallery">
      <div class="sl-spinner" style="display:none;"></div>
      <div class="sl-overlay1" style="display:none;"></div>
         
		<?php
			foreach($all_images as $img_path){
				$full_path = $img_path->full_path;
				$medium_path = $img_path->medium_path;
				$thumbnail_path = $img_path->thumbnail_path;
				echo '<div class="col-lg-2 col-sm-4 col-md-3 GalleryThumb">
				<div class="toureBox tourGallery">
				<figure><a class="lightbox" href="'.IMG_PATH.$full_path.'">
				     <img width="800" height="600" alt="" src="'.IMG_PATH.$medium_path.'"> </a> </figure>
				</div>
				</div>';
				
			}
		?>
    </div>
  </div>
</section>
<?php include_once 'footer.php'; ?>
