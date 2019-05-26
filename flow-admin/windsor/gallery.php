<?php 
include_once 'header.php'; 

$url = API_PATH.'gallery-image/'.SITE_ID;
$cat_img = $obj->getCurl($url);
$cat_images = $cat_img->cat_images;

/*$path = 'http://54.191.172.136:82/florist-admin/uploads/gallery/2017/01/thumbnail/14836995123249.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
*/
?>
<section>	
	<div class="container">
    	<div class="row">         	  		
			<?php
				foreach($cat_images as $value){
					$id = $obj->EncryptClientId($value->id);
					$category_name = $value->category_name;
					$medium_path = $value->medium_path;
					
					echo '<div class="col-lg-6 gallery">            	               	
					<a href="inner-gallery.php?gallery_id='.$id.'"><img src="'.IMG_PATH.$medium_path.'" alt="" /></a>
					</div>';   
				}
			?>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
