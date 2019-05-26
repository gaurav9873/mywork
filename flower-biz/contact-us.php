<?php include_once 'header.php'; 

$url = API_PATH.'page-detail/22';
$con = $obj->getCurl($url);
$pages_deatil = $con->pages_deatil[0];
$page_id = $pages_deatil->id;
$page_name = $pages_deatil->page_name;
$page_content = $pages_deatil->page_content;
$short_description = $pages_deatil->short_description;
$footer_banner = $pages_deatil->footer_banner;
$header_banner = $pages_deatil->header_banner;
$shortNote2 = $pages_deatil->short_note2;


$all_testimonial = API_PATH.'all-testimonial';
$curl_vals = $obj->getCurl($all_testimonial);
$all_testimonials = $curl_vals->all_testimonial;
$count = count($all_testimonials);
$bg_class = (($count == 0) ? '' : 'bg');
?>
<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script>
$(document).ready(function (){
	
	 $('#contactfrm').validate({
        rules: {
			user_name: "required",
			user_email:{required: true, email: true},
            user_message: {required: true, minlength:1, maxlength: 500},
        },
        
        messages: {
			user_name: "please enter your name",
            user_email: "Please enter your valid email id",
            user_message: "please enter your query",
        },
        
         submitHandler: function (form) {
			 
				var data =  JSON.stringify($('#contactfrm').serializeObject());
				$.ajax({
					type:'POST',
					contentType: 'application/json',
					url:'action/contact-us.php',
					dataType: "json",
					data: data,
					success: function(data){
						if(data){
							$("#msg").append(data.msg);
							$('#contactfrm')[0].reset();
						}
					}
			});
         
        }
    });
    
    $("#content-slider").lightSlider({
			loop:true,
			keyPress:true,
			slideMargin:40,
			item:1,
			auto:true
		});
    	
});
</script>

<section>
  <div class="BannerBox"> <img src="<?php echo IMG_PATH.$header_banner; ?>" /> </div>
</section>
<div class="clearfix"></div>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-7 col-md-6 col-sm-6">
        <div class="contDetail">
          <h2><?php echo $page_name; ?></h2>
          <address>
			  <?php echo $page_content; ?>
          </address>
          <div class="map">
            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.562648262738!2d-0.7251623842295334!3d51.521239279637385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487662cfbabff1ef%3A0x82cddffceccc82b8!2sFleur+de+Lis+Flowers!5e0!3m2!1sen!2sin!4v1482322165714" width="" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>-->
            <?php echo $shortNote2; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-5 col-md-6 col-sm-6">
        <form name="contactfrm" id="contactfrm" action="" method="post">
          <div class="contDetail">
            <div class="resp" id="msg"></div>
            <h2>Your Query Please</h2>
            <div class="reviewDetail cardMassages shopNow ">
              <div class="form-group">
                <label for="title"><span>*</span>Name :</label>
                <input type="text" class="form-control" name="user_name" id="user_name">
              </div>
              <div class="form-group">
                <label for="title"><span>*</span>Email :</label>
                <input type="email" class="form-control" name="user_email" id="user_email">
              </div>
              <div class="form-group">
                <label for="title">Phone No :</label>
                <input type="text" class="form-control" name="user_phone" id="user_phone">
              </div>
              <div class="form-group">
                <label for="promotionalcode"><span>*</span>Message :</label>
                <textarea class="form-control" name="user_message" id="user_message"></textarea>
              </div>
              <p>[Max Length:500 Charater] Remaining Character: 500 :</p>
              <button type="submit" class="btn btn-col btnDirectionLeft">Submit </button>
              <button type="reset" class="btn btn-col btnDirectionLeft" style="margin-left:20px;">Reset </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<div class="clearfix"></div>
<section class="feedback <?php echo $bg_class; ?>" style="background: url(<?php echo IMG_PATH.$footer_banner; ?>) no-repeat center center;">
  <?php if(!empty($all_testimonials)){ ?>
  <div class="feedbackLists">  
  
  <div id="myCarousel" class="carousel slide" data-ride="carousel">

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <?php 
    $i = 0;
	foreach($all_testimonials as $countobj){
		$title = $countobj->user_title;
		$user_content = $countobj->user_content;
		$active_class = (($i == 0) ? 'active' : '');
		echo '<div class="item '.$active_class.' text-center">
		  <h4> '.$title.'</h4>
		  <blockquote>'.$user_content.'</blockquote>
		</div>';
	$i++; }
	?>
        
  </div>

  <!-- Indicators -->
  <ol class="carousel-indicators">
	<?php
	$counter = 0;
	foreach($all_testimonials as $countobj){
		$active = (($counter == 0) ? 'active' : '');
		echo '<li data-target="#myCarousel" data-slide-to="'.$counter.'" class="'.$active.'"></li>';
	$counter++; }
    ?>
  </ol>
</div>
  
  
  </div>
  <?php } ?>
</section>
<?php include_once 'footer.php'; ?>
