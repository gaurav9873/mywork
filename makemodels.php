<?php 
include('includes/conn.php');

/*$sql="select * from brand";
$reses = mysql_query($sql);
while ($row= mysql_fetch_assoc($reses))
 {
 $id=$row["b_id"];
}*/

error_reporting(0);
$task1=$_POST['task'];

if($task1=="signupmakes")
{

$years=$_REQUEST['years'];
	$years=$_REQUEST['years'];$years=$_REQUEST['years'];$years=$_REQUEST['years'];$years=$_REQUEST['years'];$years=$_REQUEST['years'];
$makes=$_REQUEST['makes'];
$models=$_REQUEST['models'];
 echo $years;
 echo "<br>";
 echo $makes;
 echo "<br>";
 echo $models;

header("location:car-option.php?y=$years&&m=$makes&&md=$models");


}

/*if(!isset($_SESSION['nextstep']) ||  $_SESSION['nextstep']=="")
{
	header("location:index.php");
	exit;
}
if(!isset($_SESSION['task']) ||  $_SESSION['task']!="successsignup")
{
	header("location:sign-up.php");
	exit;
}
*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Car Oye!</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">

function getmakes(myear){
	$.post("getmakes.php", {myear : myear },function(data){
		$("#makes").removeAttr("disabled");
		$("#makes").html(data);
	});	
}

function getmodels(makval){
	var makval = $("#makes").val();
	var yersval = $("#years").val();
	$.post("getmodels.php", {makval : makval , yersval : yersval },function(data){
		if(data.length > 0){
			$("#models").removeAttr("disabled");
			$("#submitimg").removeAttr("disabled");
			$("#submitimg").removeClass("disabled");
			$("#models").html(data);
		}else{
			$("#models").html('');
			$("#models").attr("disabled", true);
			$("#submitimg").attr("disabled", true);
			$("#submitimg").addClass("disabled");
		}
	});	
}
</script>
</head>
<body>
<div id="wrapper">
  <!--help_wfix-->
  <div id="help_wfix"><a href="#"><img src="images/help.png" alt=""  /></a></div>
  <!--help_wfix-->
  <div id="header_main">
    <?php include("header-small.php");?>
  </div>
  
  <div id="container" class="back2">
    <div class="container_inner backnoun">
      <div class="cont-shadow back2">
        <div id="body_cont_in">
        <div class="payment_gateway_inner">
      <div class="gateway_conten">
      <div class="gateway_conten_left"></div>
      <div class="gateway_conten_center">
      <h1>What kind of car are you going to buy?</h1>
      </div>
      <div class="gateway_conten_right">
      </div>
      <div class="clear">
      
      </div>
      </div> 
             <div class="rate_review_inner_cont">
     <div class="gateway_conten1">

<!--<form method="post"  action="signup-action.php" >-->
<form method="post"  action="" name="frm1" >
	<input type="hidden" name="task" value="signupmakes" />
<div class="hefp_decide_inner">
<div class="help_form_box">
  <label>
  <select name="years" id="years" class="help_form_box_form" onchange="getmakes(this.value);" >
    <?php 
		$resy = mysql_query("select * from year");
		while($rowy = mysql_fetch_assoc($resy)){
	?>
    <option value="<?php echo $rowy['year'];?>"><?php echo $rowy['year'];?></option>
	<?php } ?>
    </select>
  </label>
</div>
<div class="choose_form_box">
  <label>
  <select name="makes" id="makes" class="choose_box_form" onchange="getmodels(this.value);" disabled="disabled">
    <option value="">Choose a make</option>
 </select>
  </label>
</div>
<div class="choose_form_box">
  <label>
  <select name="models" id="models" class="choose_box_form" disabled="disabled" >
    <option value="0">Choose a model</option>
  </select>
  </label>
</div>
<div class="countin">
	<input type="image" src="images/continue.jpg" alt="" id="submitimg" disabled="disabled" class="disabled" />
</div>
<div class="clear"></div>
</div>
</form>
<div class="clear"></div>
 </div>     
     </div> <div class="gateway_fotter"></div>  

             </div>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
    <!--footer start here-->
  <?php include("footer.php");?>
  <!--footer end here-->
</div>
</body>
</html>
