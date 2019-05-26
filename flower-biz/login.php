<?php include_once 'header.php'; 

$dbobj = new ConnectDb();
$dblogin = new Login();

if(empty($_SESSION['cart']['products']['Standard']) AND empty($_SESSION['cart']['products']['Large'])){
	$root_url = 'my-account';
}else{
	$root_url = 'checkout-delivery';
}

$msg = '';
if(isset($_POST['submit']) == 'submit'){
	unset($_POST['submit']);

	$user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
	$user_password = $_POST['user_password'];
	$enc_password = hash('sha256', $user_password);
	$login_customer = $dblogin->doLogin($user_email, $enc_password, SITE_ID);
	
	$doLogin = json_decode($login_customer);
	if($doLogin->status == 'login'){
		header("location:$root_url");
	}else if($doLogin->status == 'update_password'){
		$msg = 'New site has been introduced.Please check your mail box and re-set the password to login to the new site.';
	}else if($doLogin->status == 'notfound'){
		$msg = 'This email address could not be found in our system';
	}else{
		$msg = 'If you have forgotten your password, please reset your password here: <a href="javascript:void(0);" class="" id="pwdLink">Click here</a>';
	}
}

$key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
$token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
$jwthelper = JWT::encode($token, $key);
?>

<script>
$(document).ready(function(){
	
	var auth = '<?php echo $jwthelper; ?>';
	$("#loginfrm").on('submit',function(e){
		var errorFlag = false;
		$(".required").each(function(){
			if($(this).val() == ''){
				errorFlag = true;
				$(this).css('border-color', 'red');
				$(this).parent().find('.errmsg').show();
				$(".loginfrm").effect("shake", { times:1 });
			}else{
				if(this.id == 'user_email'){
					var chkMail = $("#user_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						$(this).parent().find('.errmsg').show();
						errorFlag = true;
					}
				}
			}
		});


		$(".required").on('keypress change',function(){
			if($(this).val() ==''){
				$(this).css('border-color', 'red');
				$(this).parent().find('.error').show();
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
				$(this).parent().find('.errmsg').hide();
				if(this.id == 'user_email'){
					var chkMail = $("#user_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						$(this).parent().find('.errmsg').show();
						errorFlag = true;
					}
				}
			}
		});

		if(errorFlag){
			e.preventDefault();
		}


	});
	
	
	$("#pwdLink").on('click', function(){
		$("#forgetpwd").show();
		$("#loginsec").hide();
	});
	
	$("#lgfrmlink").on('click', function(){
		$("#forgetpwd").hide();
		$("#loginsec").show();
	});
	
	$("#forgetPass").on('click', function(){
		var emailtxt = $(".emailtxt").val();
		if(emailtxt!=''){
			if(isValidEmailAddress(emailtxt) == false){
				alert("Please enter valid email");
				return false;
			}else{
				var form_array = JSON.stringify($('#fpwd').serializeObject());
				$.ajax({
					url:'action/forget-pass',
					type:'post',
					contentType: 'application/json',
					dataType: "json",
					data:form_array,
					cache:false,
					beforeSend:function(xhr){
						$(".waitwheel").show();
					},
					complete:function(){
						$(".waitwheel").hide();
					},
					success:function(responce){
						if(responce.status == 'true'){
							$('.sumsg').empty().html(responce.msg).css('color', 'green').show();
						}else{
							$('.sumsg').empty().html(responce.msg).css('color', 'red').show();
						}
						$('#fpwd')[0].reset();
						setTimeout(function(){ $('.sumsg').fadeOut();},3000);
					}
				});
			}
		}
		
	});
	
});

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
	return pattern.test(emailAddress);
}
</script>

<section>
	<div class="container-fluid">
    	<div class="row">
    		<div class="col-lg-12">
                <h2>Login</h2>
            </div>
        </div> 
        <div class="row">
        	<div class="col-lg-6 col-md-6 col-sm-6">
            	<div class="sec-login" style="margin:35px 0 0;">
                	<strong>New to Flowerbiz</strong>
                    <p>Create an online account: It’s easy and you’ll gain following important advantages:</p>                    
                     <ul>
                        <li>Create an address book to shop faster</li>
                        <li>Track your orders</li>
                        <li>Receive special email offers</li>
                        <li>and much more.....</li>
                    </ul>
                </div>	
                <a href="registration" class="btn btn-col2 btnDirectionLeft" style="margin-top:13px;">Register</a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
            
            	<div class="sec-login lg-form" id="loginsec">
                	<strong>Registered Member</strong>
                    <p>Access your address book and billing preferences for faster checkout.</p>
                    <div class="err" style="color:red;font-size:11pt;"><?php echo $msg; ?></div>
                    <form name="loginfrm" id="loginfrm" class="loginfrm" action="" method="post">
						<div class="form-group">
							<label class="">Email Address:</label>
							<input class="form-control required" id="user_email" name="user_email" type="text">
							<ul class="errmsg" style="display:none;"><li>Please enter valid email.</li></ul>
						</div>
						<div class="form-group pwdSec">
							<label class="">Password:</label>
							<input class="form-control required" id="user_password" name="user_password" type="password">
							<ul class="errmsg" style="display:none;"><li>Please enter your password.</li></ul>
						</div>
						<button type="submit" name="submit" value="submit" class="btn btn-col btnDirection">Login</button>
						<a href="javascript:void(0);" class="pwdLink" id="pwdLink">Forgot your password?</a>
                 	 </form>               
                </div>
                
                <div class="sec-login lg-form" id="forgetpwd" style="display:none;">
					<form name="fpwd" id="fpwd" action="" method="post">
						<strong>Forgot your password?</strong>
						<p>Simply Enter the Email address you used to sign up, we'll email to you your UserName and Password. </p>
						<div class="form-group deviceform">
							<label class="">Enter Email Address:</label>
							<input class="form-control emailtxt" name="forget_email" id="emailtxt" type="email">
							<div class="waitwheel" style="display:none; color:red;">Please Wait...</div>

						</div>
						<div class="clearfix"></div>
                        <div class="sumsg" style="display:none;"></div>
						<button type="button" class="btn btn-col btnDirection" id="forgetPass">Submit</button>
                    </form>
                 	<a href="javascript:void(0);" class="pwdLink forgetlogin" id="lgfrmlink">Login</a>                   
                </div>
                
                
            </div>
        </div>       
    </div>
</section>            

<?php include_once 'footer.php'; ?>
