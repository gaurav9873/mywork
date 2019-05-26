<?php include_once 'header.php'; 

$url = API_PATH.'page-detail/23';
$con = $obj->getCurl($url);
$pages_deatil = $con->pages_deatil[0];
$page_id = $pages_deatil->id;
$page_name = $pages_deatil->page_name;
$page_content = $pages_deatil->page_content;
$short_description = $pages_deatil->short_description;
$header_banner = $pages_deatil->header_banner;
$shortNote2 = $pages_deatil->short_note2;
$pc_image = $pages_deatil->pc_image;
$sd_image = $pages_deatil->sd_image;
?>
<section>
   <div class="BannerBox"> <img src="<?php echo IMG_PATH.$header_banner; ?>" /> </div>
</section>
<div class="clearfix"></div>
<section>
	<div class="container-fluid">
    	<div class="row">    		
            <div class="col-lg-12">
            	<div class="aboutSec">
                    <div class="aboutContent">
                       <?php echo $page_content; ?>
                     </div>
                    <?php if($pc_image <> ''){ ?>
                     <aside class="aboutImg">
                        <img src="<?php echo IMG_PATH.$pc_image; ?>" class="img-responsive" alt="Flower">
                    </aside>
                    <?php } ?>
                </div>
                
                <div class="aboutSec">
                    <div class="aboutContent">
                        <?php echo $short_description; ?>
                     </div>
                    <?php if($sd_image <> ''){ ?>
                     <aside class="aboutImg">
                        <img src="<?php echo IMG_PATH.$sd_image; ?>" class="img-responsive" alt="Flower">
                    </aside>
                    <?php } ?>
                </div>
                
                <div class="bot-msg"><?php echo $shortNote2; ?></div>
            </div>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
