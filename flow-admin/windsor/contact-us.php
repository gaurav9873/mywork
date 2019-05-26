<?php include_once 'header.php'; ?>
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
	<div class="BannerBox"> <img src="images/contact-us.png" /> </div>
</section>
<div class="clearfix"></div>
<section>
	<div class="container-fluid">
    	<div class="row">    		
            <div class="col-lg-7 col-md-6 col-sm-6">
            	<div class="contDetail">
                	<h2>Contact</h2>
                	<address>
                    	<ul>
                    		<li><i class="fa fa-home"></i>Fleur De Lis
                       		28 King Street, Maidenhead, Berkshire
                        	SL6 1EF, UK</li>
                        
                        	<li><i class="fa fa-envelope"></i>nfo@fleurdelisflorist.co.uk</li>
                        
                        	<li><i class="fa fa-phone"></i>01628 675566
                        	<span>(If you are calling from outside the UK, dial +44 1628 675566)</span></li>                        
                        </ul>
                        <span>(closed UK Public holidays)</span>
                    </address>
                    <div class="map">
                    	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.562648262738!2d-0.7251623842295334!3d51.521239279637385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487662cfbabff1ef%3A0x82cddffceccc82b8!2sFleur+de+Lis+Flowers!5e0!3m2!1sen!2sin!4v1482322165714" width="" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
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
<section class="feedback">
	<div class="feedbackLists">  
    	<div class="item">   	
            <ul id="content-slider" class="content-slider">
                <li>
                  <div class="productItem">
                    <h4> This is what some of our customers say</h4>
                    <blockquote>I have ordered flowers from Fleur de Lis many times and have been consistently thrilled with the products; beautiful fresh flowers, excellent professional service, great value for my money and delighted clients, family and friends! Thank you so much</blockquote>
                  </div>             
                </li>  
                <li>
                  <div class="productItem">
                    <h4> This is what some of our customers say</h4>
                    <blockquote>I have ordered flowers from Fleur de Lis many times and have been consistently thrilled with the products; beautiful fresh flowers, excellent professional service, great value for my money and delighted clients, family and friends! Thank you so much</blockquote>
                  </div>             
                </li>
                <li>
                  <div class="productItem">
                    <h4> This is what some of our customers say</h4>
                    <blockquote>I have ordered flowers from Fleur de Lis many times and have been consistently thrilled with the products; beautiful fresh flowers, excellent professional service, great value for my money and delighted clients, family and friends! Thank you so much</blockquote>
                  </div>             
                </li>                          
            </ul>         
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
