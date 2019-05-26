<?php
require_once('header.php');

$obj = new ConnectDb();
$cFunc = new CustomFunctions();

$all_domain = $obj->fetchAll('op2mro9899_add_domain');

$id = $cFunc->xss_clean(base64_decode($_REQUEST['user_id']));

$res = $obj->get_row_by_id('op2mro9899_admin_useres', 'id', intval($id));

if(isset($_POST['user_name']) && ($_POST['user_email']) && ($_POST['user_password'])){
	
	$name = $cFunc->xss_clean($_POST['user_name']);
	$email = $cFunc->xss_clean($_POST['user_email']);
	//$password = $cFunc->getSaltedHash($_POST['user_password']);
	$password = trim($_POST['user_password']);
	$enc_password = hash('sha256', $password);
	$level = $cFunc->xss_clean($_POST['user_level']);
	$domainId = $cFunc->xss_clean($_POST['domain_id']);
	
	$data = array('user_name' => $name, 'user_email' => $email, 'user_password' => $enc_password, 'user_level' => $level, 'domain_id' => $domainId);
	$q = $obj->update_row('op2mro9899_admin_useres', $data,"WHERE id = '$id'");
	if($q == true){
		 header("Location: add-useres.php");
		 exit;
	}
}


foreach($res as $val){
	//print_r($val);
}

$user_name = $val['user_name'];
$user_email = $val['user_email'];
$user_password = $val['user_password'];
$user_level = $val['user_level'];
$domain_id = $val['domain_id'];
$user_status = $val['user_status'];
?>


<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i>Edit user</h2>
            </div>
            
            <div class="box-content">
				<div class="alert alert-info" style="display:none;">Domain added successfully</div>
					<form name="user-frm" id="user-frm" action="" role="form" method="post">
						<div class="form-group">
							<label for="name">User Name</label>
							<input class="form-control validate" id="user_name" name="user_name" value="<?php echo $user_name; ?>"  placeholder="Enter Site User Name" type="text">
							<ul class="err" style="display:none;"><li>Please enter user name.</li></ul>
						</div>
						
						<div class="form-group">
							<label for="email">Email address</label>
							<input class="form-control validate" id="user_email" name="user_email" value="<?php echo $user_email; ?>" placeholder="Enter email" type="email">
							<ul class="err" style="display:none;"><li>Please enter valid email address.</li></ul>
						</div>
						
						<div class="form-group">
							<label for="password">Password</label>
							<input class="form-control validate" id="user_password" name="user_password" value="<?php echo $user_password;?>" placeholder="Password" type="password">
							<ul class="err" style="display:none;"><li>Please enter password.</li></ul>
						</div>
						
						<div class="form-group">
							<select name="user_level" id="user_level" class="form-control validate">
								<option value="">Select User Level</option>
								<option <?php echo ($user_level == '1') ? 'selected' : ''; ?> value="1">Super Admin</option>
								<option <?php echo ($user_level == '2') ? 'selected' : ''; ?> value="2">Manager</option>
							</select>
							<ul class="err" style="display:none;"><li>Please select user level.</li></ul>
						</div>
						
						<div class="form-group">
							<select name="domain_id" id="domain_id" class="form-control">
								<option value="">Select Shop Name</option>
								<?php 
									foreach($all_domain as $site_name){
										$domain_name = $site_name['domain_name'];
										$site_id = $site_name['site_id'];
										$selected = ($domain_id == $site_id) ? 'selected' : '';
										echo '<option '.$selected.' value="'.$site_id.'">'.$domain_name.'</option>';
									}
								?>
							</select>
						</div>
						
						<button type="submit" class="btn btn-default" >Submit</button>
						<ul class="ajax-loaders" style="display:none;"><li><img src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif"></li></ul>
					</form>
            </div>
        </div>
    </div>
    <!--/span-->
</div>


<?php require_once('footer.php'); ?>
