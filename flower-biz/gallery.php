<?php 
include_once 'header.php'; 

$url = API_PATH.'gallery-image/'.SITE_ID;
$cat_img = $obj->getCurl($url);
$cat_images = $cat_img->cat_images;


/*$path = 'http://54.191.172.136:82/florist-admin/uploads/gallery/2017/01/thumbnail/14835960804715.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
echo $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
*/
?>
<section class="galleryPage">	
	<div class="container">
    	<div class="row marginTop20">
        	<div class="col-sm-12 text-center"><h2>Gallery</h2></div>
        </div>
    	<div class="row marginTop20 text-center tt">         	  		
			<?php
				foreach($cat_images as $value){
					$id = $obj->EncryptClientId($value->id);
					$category_name = $value->category_name;
					$medium_path = $value->medium_path;
					$category_name = $value->category_name;
					
					echo '<div class="col-sm-4">
					<div class="gallery">        	               	
					<a href="gallery-subcategory.php?gallery_id='.$id.'&cat_name='.$category_name.'"><img src="'.IMG_PATH.$medium_path.'" alt="" /></a>
					<div class="cat-name">'.$category_name.'</div>
					</div>
					</div>';   
				}
			?>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
