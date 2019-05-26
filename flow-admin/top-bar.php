<?php
$dbobj = new ConnectDb();
$active_domain = $dbobj->get_active_domain();

if(isset($_POST['domain_id'])){
	$_SESSION['shop_id'] = $_POST['domain_id'];
}
?>
<div class="navbar navbar-default" role="navigation">
<div class="navbar-inner">
	<button type="button" class="navbar-toggle pull-left animated flip">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="index.php"> <img alt="Charisma Logo" src="img/logo-01.png" class="hidden-xs"/>
		<span>Admin</span></a>

	<!-- user dropdown starts -->
	<div class="btn-group pull-right">
		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> <?php echo $_SESSION['user_name']; ?></span>
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="divider"></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	<div class="input-group input-group-lg domain">
	<div class="styled-select green rounded">
	<form action="" method="POST">
		<select name="domain_id" id="domain_id" class="" onchange="this.form.submit()">
			<?php
			foreach($active_domain as $domain_value){
				$site_id = $domain_value['site_id'];
				$domain_name = $domain_value['domain_name'];
				$selected = (($_SESSION['shop_id'] == $site_id) ? 'selected' : '');
				echo '<option value="'.$site_id.'" '.$selected.'>'.$domain_name.'</option>';
			}
			?>
		</select>
	</form>
	</div>
	</div>
</div>
</div>
