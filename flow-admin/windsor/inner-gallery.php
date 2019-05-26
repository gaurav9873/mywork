<?php 
include_once 'header.php'; 

$gallery_id = $obj->DecryptClientId($_GET['gallery_id']);

$api = API_PATH.'all-gallery/'.$gallery_id;
$json = $obj->getCurl($api);
$all_images = $json->all_images;
?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="demo">
          <div class="item">
            <div class="clearfix prodctView">
              <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
					<?php
						foreach($all_images as $img_path){
							$full_path = $img_path->full_path;
							$medium_path = $img_path->medium_path;
							$thumbnail_path = $img_path->thumbnail_path;
							echo '<li data-thumb="'.IMG_PATH.$thumbnail_path.'"> <img src="'.IMG_PATH.$full_path.'" alt="" /> </li>';
						}
					?>
              </ul>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>
</section>

<?php include_once 'footer.php'; ?>
