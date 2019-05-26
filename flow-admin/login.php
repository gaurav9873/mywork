<?php
$no_visible_elements = true;
include('header.php'); 

$obj = new Login();
$customObj = new CustomFunctions();
$dbobj = new ConnectDb();
$msg = '';

if(isset($_POST['submit'])){
	
	$user_email = $_POST['user_email'];
	$user_password = $_POST['user_password'];
	$enc_password = hash('sha256', $user_password);
	$domain_id = intval($_POST['domain_id']);
	$doLogin = $obj->doLogin($user_email, $enc_password);
	if($doLogin){
		$_SESSION['shop_id'] = $domain_id;
		header("location:upcoming-orders.php");
	}else{
		$msg = 'user email/password not matched';
	}
}

$active_domain = $dbobj->get_active_domain();
?>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.11.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function(){

	$("#login-frm").on('submit',function(e){
		var errorFlag = false;
		
		$(".required").each(function(){
			if($(this).val() == ''){
				errorFlag = true;
				$(this).css('border-color', 'red');
				//$(this).parent().find('.error').show();
				$(".loginFrm").effect("shake", { times:2 }, 200);
			}else{
				if(this.id == 'user_email'){
					var chkMail = $("#user_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						errorFlag = true;
					}
				}
			}
		});

		$(".required").on('keypress change', function(){
			if($(this).val() == ''){
				$(this).css('border-color', 'red');
				errorFlag = true;
			}

			if($(this).val()!=''){
				$(this).css('border-color', '');
				if(this.id == 'user_email'){
					var chkMail = $("#user_email").val();
					if(isValidEmailAddress(chkMail)==false){
						$(this).css('border-color', 'red');
						errorFlag = true;
					}
				}
			}

		});

		if(errorFlag){
			e.preventDefault();
		}
	});
	
	$("#user_email").on('keypress change', function() {
		var email_vals = $("#user_email").val();
		if(isValidEmailAddress(email_vals)==true){
			$(".domain").show();
			$(".semi-square").addClass('bounceInRight animated');
		}
		if(email_vals ==''){
			$(".domain").hide();
		}
	});
	
});

function isValidEmailAddress(emailAddress){
	var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
	return pattern.test(emailAddress);
}

</script>
<link href="animate/animate.min.css" rel="stylesheet">
<link href="animate/animate.css" rel="stylesheet">
<link href="animate/bounceInRight.css" rel="stylesheet">
<link href="animate/bounceOutLeft.css" rel="stylesheet">
<div class="container">
   <div class="row">
        <div class="col-md-12 center login-header">
            <h2>Welcome to Admin</h2>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row loginFrm">
        <div class="well col-md-5 center login-box">
			<?php if($msg){ ?>
				<div class="alert alert-danger">
					<strong>OOPS!!!</strong> <?php echo $msg; ?>
				</div>
			<?php }else{ ?>
            <div class="alert alert-info"> Please login with your Useremail and Password.</div>
            <?php } ?>
            <form class="form-horizontal" name="login-frm" id="login-frm" action="" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input type="text" class="form-control required" name="user_email" id="user_email" placeholder="Useremail">
                        <div class="error" style="color:red;display:none;">Please enter your valid email</div>
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input type="password" class="form-control required" name="user_password" id="user_password" placeholder="Userpassword">
                        <div class="error" style="color:red;display:none;">Please enter your password</div>
                    </div>
                    <div class="clearfix"></div>
					<br /><br />
                   <div class="input-group input-group-lg domain" style="display:none;">
						<div class="styled-select blue semi-square">
						<select name="domain_id" id="domain_id" class="required">
							<option value="">Select domain</option>
							<?php
								foreach($active_domain as $domain_value){
									$site_id = $domain_value['site_id'];
									$domain_name = $domain_value['domain_name'];
									echo '<option value="'.$site_id.'">'.$domain_name.'</option>';
								}
							?>
						</select>
						</div>
                    </div>
                    <div class="clearfix"></div>

                    <p class="center col-md-5">
                        <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    </p>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
    </div>
<?php //require('footer.php'); ?>
