<?php include_once 'header.php'; 

$page_content = API_PATH.'page-detail/21';
$page_data = $obj->getCurl($page_content);
$pages_deatil = $page_data->pages_deatil[0];
$page_contents = $pages_deatil->page_content;
$page_name = $pages_deatil->page_name;
$header_banner = $pages_deatil->header_banner;
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
							<?php echo $page_contents; ?>
                     </div>
                </div>
                
            </div>
    	</div>
    </div>
</section>
<?php include_once 'footer.php'; ?>
