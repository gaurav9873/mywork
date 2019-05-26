<?php include_once 'header.php'; 


$msg = '';
if(isset($_POST['submit']) == 'submit'){
	unset($_POST['submit']);
	$post_data = $_POST;
	$url = 'http://54.191.172.136:82/florist-admin/flowers/api/login';
	$req = $obj->httpPost($url, $post_data);
	if($req->status == 'true'){
		$res = $req->data;
		$_SESSION['user_id'] = $res->user_id;
		$_SESSION['email'] = $res->emailID;
		$_SESSION['first_name'] = $res->fname;
		$_SESSION['last_name'] = $res->lname;
		$_SESSION['key'] = $res->user_key;
		header("location:my-account.php");
	}else{
		$msg = 'invalid credentials';
	}
}
?>

<script>
$(document).ready(function(){
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
            	<div class="sec-login">
                	<strong>New to Fleur de lis</strong>
                    <p>Create an online account: It’s easy and you’ll gain following important advantages:</p>                    
                     <ul>
                        <li>Create an address book to shop faster</li>
                        <li>Track your orders</li>
                        <li>Receive special email offers</li>
                        <li>and much more.....</li>
                    </ul>
                </div>	
                <a href="registration.php" class="btn btn-col2 btnDirectionLeft">Register</a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
            
            	<div class="sec-login lg-form">
                	<strong>Register Member</strong>
                    <p>Access your address book and billing preferences for faster checkout.</p>
                    <div class="err" style="color:red;"><?php echo $msg; ?></div>
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
						<a href="javascript:void(0);" class="pwdLink">Forgot your password?</a>
                 	 </form>               
                </div>
            </div>
        </div>       
    </div>
</section>            

<?php include_once 'footer.php'; ?>
