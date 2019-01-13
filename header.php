<script type="text/javascript">
	$(
 		function moveDown(){
		$('.login a').click(function (){
   			//if($('.mon-bot-txt:visible')){$('.mon-bot-txt').toggle()}else{return};
			if($('#login-menu:visible')){$('#login-menu').slideToggle()}else{return};
  			return false;
		});
	}
	);
</script>
<div class="header_inner">
      <div class="header_wfix">
        <div class="logo_lp"><a href="index.php"><img src="images/caroye_logo.png" alt="logo" width="280" height="77" border="0" /></a></div>
        <div class="logo_rp">
          <div class="link_top">
            <ul>
              <li class="margin0">Your City: <a href="#">Delhi NCR</a></li>
              <li>Other Cities: <a href="#">Mumbai</a><a href="#">Bangalore</a> </li>
              <li class="margin0"><a href="#"><img src="images/reter-a-friend_icon.png" alt=""  /></a></li>
            </ul>
          </div>
          <div id="socal_wfix">
            <div class="login"><a href="#">Login</a>
            <div id="login-menu" <?php if($_SESSION['loginmsg']!="") {    ?> style="display:block" <?php } ?> >
			<?php if($_SESSION['loginmsg']!="") {?>
				<span style="color:red"><?php echo $_SESSION['loginmsg']; echo $_SESSION['loginmsg']="";?></span>
			<?php }?>
			<form method="POST" action="buyerlogin.php" class="">
				<div style="margin: 0px; padding: 5px 0px;" class="caroye-form-field-last">
					<label for="login" class="caroye-form-label">Email</label>
					<span class="caroye-form-req">(required)</span><br>
					<input type="text" tabindex="1" autofocus="" required="" class="caroye-form-input-plain" style="width: 235px;" id="login" name="email" gtbfieldid="1">
					<div class="caroye-form-error"></div>
					<div class="clear"></div>
				</div>
				<div style="margin: 0px; padding: 5px 0px;" class="caroye-form-field-last">
					<label for="password" class="caroye-form-label">Password</label>
					<span class="caroye-form-req">(required)</span><br>
					<input type="password" tabindex="2" required="" class="caroye-form-input-plain" style="width: 235px;" id="password" name="password"><br>
					<div class="caroye-form-error"></div>
					<div class="clear"></div>
				</div>
				<div style="margin-top: 5px;" class="fb-form-field-last flotrgt">
					<input type="hidden" value="login" name="task">
					<input type="image" value="Login" src="images/login_btn.png">
				</div>
				<div style="margin: 5px 0px; padding-right: 2px;" class="fgot">
					<a href="sign-up.php">Register!</a>
					<br>
					<a href="forgotpassword.php">Forgot Password?</a>				</div>
                <div class="clear"></div>
		</form>
		<div id="connect-buttons">
			Or Connect with: <br>
			<a class="facebook-login" id="login-facebook-connect" href="#"><img alt="Facebook Connect" src="images/facebook-connect.gif" class="facebook-login"></a> &nbsp;
			<a href="#"><img alt="Twitter Signin" style="height: 21px;" src="images/twitter-connect.png"></a>		</div>
	</div>
            </div>
             <div class="signup"><a href="sign-up.php">Sign up</a></div>
          </div>
        </div>
      </div>
    </div>