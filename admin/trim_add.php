<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}


//echo $getrandno;

	if($_POST['task']=="addbrand"){
			
		$err = false ;
			$_SESSION['regmsg'] = ""; 
		//$upload=$_FILES[upload][name];
		//$upload_tmp=$_FILES[upload][tmp_name];
		//$uploadsize=$_FILES[upload][size];
		//$uploadtype=$_FILES[upload][type];
		
			
		
			$years=$_POST['years'];
		   $companyname = $_POST['companyname'] ;
			$model = $_POST['models'];
			$trim = $_POST['trim'] ;
			$msrp = $_POST['msrp'] ;
			$invoice = $_POST['invoice'];
			$mpgcity = $_POST['mpgcity'] ;
			$mpghwy = $_POST['mpghwy'];
		
			
		/*
			if($brandname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Brand Name in the text  box.<br />";
			}
			if($companyname==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Company Name.<br />";
			}*/
			
			/*$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='".$usertype."' ") ;
			if(mysql_num_rows($sqlres) > 0)
			{
				$err = true ;
				$_SESSION['regmsg'] .= "email address already exist.";
			 
			}*/
			/*if($err)
			{
				//header("location:addusers.php");
			}
			 else
			 {*/
			
			$sqlq="INSERT INTO `trim_manage`(`years`,`makes`,`model`,`trim_name` ,
			`msrp` ,
			`invoice` ,
			`mpg_city` ,
			`mpg_hwy`
			)
			VALUES ('$years', '$companyname','$model', '$trim', '$msrp', '$invoice', '$mpgcity', '$mpghwy'
			)";

			
			//$sqlq="INSERT INTO `makes_model`(`year`,`make_model_text`,`parent_id`)VALUES('$year','$model_name','$manu_name')";
			
				mysql_query($sqlq);
				
			header("location:trimview.php?msg=Data Has Been Added Successully");
	exit;
			
			/*$getrandno=rand(0000,1234);
			$upload1=$getrandno.$upload;
			
			 	$sqlq  = "insert into brand set b_name='".$brandname."',year='".$years."', b_company='".$companyname."', model='".$model."', performance='".$performance."', saftey='".$saftey."', comfort='".$comfort."', status='".$status."', colour='".$colour."' , accessories='".$accessories."',image='".$upload1."', b_details='".$details."'"; 
				
				mysql_query($sqlq);
				move_uploaded_file($_FILES[upload][tmp_name],"carimage/".$upload1);
				$_SESSION['regmsg'] = "new brand  added successfully.";
				header("location:brand.php?msg=Brand Has Been Added Successully");
	exit;*/
			}
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">

function getmakes(myear){
	$.post("getmakes.php", {myear : myear },function(data){
		$("#companyname").removeAttr("disabled");
		$("#companyname").html(data);
	});	
}

function getmodels(makval){
	var makval = $("#companyname").val();
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
<?php
	require('includes/header.php');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="22" valign="top" class="right_top_l_bg"><img src="images/right_top_l.gif" alt="" width="22" height="236" /></td>
                  <td align="left" valign="top" class="right_top_top_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="19"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="24" align="left" valign="top"><img src="images/left_rouud_blu.jpg" alt="" width="24" height="35" /></td>
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Add New Trim</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12">  
					  <div style="color:red; margin-left:300px;"><?php // echo $_SESSION['regmsg'] ; $_SESSION['regmsg']=""; ?></div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="addbrand"  method="post" onsubmit="chkreg();" >
						 <input type="hidden" name="task" value="addbrand" />						
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
					  
					  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Year</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <select name="years" id="years" class="input-type" onchange="getmakes(this.value);" >
    <?php 
		$resy = mysql_query("select * from year order by year desc");
		while($rowy = mysql_fetch_assoc($resy)){
	?>
    <option value="<?php echo $rowy['year'];?>"><?php echo $rowy['year'];?></option>
	<?php } ?>
    </select>
      </td>
  </tr>
  
  
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Manufacture</strong></td>
     <td align="left" valign="top" class="tr-color"> 
      <div class="choose_form_box">
  <label>
  <select name="companyname" id="companyname" class="input-type" onchange="getmodels(this.value);" disabled="disabled">
    <option value="">Choose a make</option>
 </select>
  </label>
</div>
      </td>
  </tr>
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Choose models</strong></td>
     <td align="left" valign="top" class="tr-color"> 
     <div class="choose_form_box">
  <label>
  <select name="models" id="models" class="input-type" disabled="disabled" >
    <option value="0">Choose a model</option>
  </select>
  </label>
</div>
      </td>
  </tr>
                       
 
 
  <!--<tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Comapny Name</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="companyname" type="text" value="<?php //$_POST['companyname']; ?>" class="input-type"/></td>
  </tr>-->
  
   <!--<tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Model</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="model" type="text" value="<?php //$_POST['model']; ?>" class="input-type"/></td>
  </tr>-->
  
   <tr>
   <td align="left" valign="top" class="page-tile"><strong>Trim Name</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <input name="trim" type="text" value="" class="input-type"/>
      </td>
  </tr>
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>MSRP</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="msrp" type="text" value="" class="input-type"/></td>
  </tr>
  
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Invoice</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="invoice" type="text" value="" class="input-type"/></td>
  </tr>
  
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>MLG City</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="mpgcity" type="text" value="" class="input-type"/></td>
  </tr>
  
   <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>MLG Hwy</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="mpghwy" type="text" value="" class="input-type"/></td>
  </tr>
  
  
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Add New Trim" class="inputBtn" />
    </td>
   </tr>
</table>
</form>
</td>
                    </tr>
                    
                  </table></td>
                  <td width="22" valign="top" class="right_top_r_bg"><img src="images/right_top_r.gif" alt="" width="22" height="236" /></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><img src="images/right_bot_l.gif" alt="" width="22" height="42" /></td>
                  <td valign="top" class="right_bot_bot_bg">&nbsp;</td>
                  <td valign="top"><img src="images/right_bot_r.gif" alt="" width="22" height="43" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
<?php
require('includes/footer.php');
?>
</body>
</html>
