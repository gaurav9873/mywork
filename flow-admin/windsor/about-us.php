<?php include_once 'header.php'; 

$url = API_PATH.'page-detail/1';
$con = $obj->getCurl($url);
$pages_deatil = $con->pages_deatil[0];
$page_id = $pages_deatil->id;
$page_name = $pages_deatil->page_name;
$page_content = $pages_deatil->page_content;
$short_description = $pages_deatil->short_description;
?>
<section>
	<div class="container-fluid">
    	<div class="BannerBox"> <img src="images/banner3.jpg" /> </div>
    </div>
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
                <div class="aboutSec">
                    <div class="aboutContent">
                        <?php echo $short_description; ?>
                </div>
                
                <div class="bot-msg">You will be assured of a warm welcome by all the members of our team when you visit.</div>
            </div>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
