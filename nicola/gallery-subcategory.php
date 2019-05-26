<?php 
include_once 'header.php'; 

$cat_name = $obj->xss_clean($_REQUEST['cat_name']);
$gallery_id = $obj->DecryptClientId($_GET['gallery_id']);

$url = API_PATH.'sub-category/'.$gallery_id;
$sub_cat = $obj->getCurl($url);

/*$sub_cat_img = API_PATH.'sub-cat-image/19';
$img = $obj->getCurl($sub_cat_img);
*/
?>
<section class="galleryPage">	
	<div class="container">
    	<div class="row marginTop20">
        	<div class="col-sm-12 text-center"><h2><?php echo $cat_name; ?></h2></div>
        </div>
    	<div class="row marginTop20 text-center tt">         	  		
			<?php
				foreach($sub_cat->sub_cat as $val){
					$id = $val->id;
					$gallery_name = $val->gallery_name;
					$category_id = $val->category_id;
					$sub_cat_img = API_PATH.'sub-cat-image/'.$id.'';
					$img = $obj->getCurl($sub_cat_img);
					$img_path = $img->sub_cat_img[0]->medium_path;
			echo '<div class="col-sm-4">
			<div class="gallery">        	               	
			<a href="inner-gallery.php?gallery_id='.$obj->EncryptClientId($id).'&cat_name='.$gallery_name.'">
				<img src="'.IMG_PATH.$img_path.'" alt=""></a>
			<div class="cat-name">'.$gallery_name.'</div>
			</div>
			</div>';
			} ?>
			
			
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
