<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
}

	if($_POST['task']=="add_manufacture"){
			
			$err = false ;
			
			
		
			
			$year = $_POST['year'] ;
			$manu_name = $_POST['manu_name'] ;
			
			
		
			if($manu_name==""){
				$err = true ;
				$_SESSION['regmsg'] .= "Enter Name in the text  box.<br />";
			}
		
			
			/*$sqlres =  mysql_query("select email from  users where email='".$email."'  and  user_type='".$usertype."' ") ;
			if(mysql_num_rows($sqlres) > 0)
			{
				$err = true ;
				$_SESSION['regmsg'] .= "email address already exist.";
			 
			}*/
			if($err)
			{
				//header("location:addusers.php");
			} else 
			{
			
			$sqlq="INSERT INTO `makes_model`(`year`,`make_model_text`,`parent_id`)VALUES('$year','$manu_name','0')";
			
				mysql_query($sqlq);
				
				header("location:manufacture.php?msg=Data Has Been Added Successully");
	exit;
			}
	
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
 
	function chkreg(){
		//alert($("input[name=cpassword]").val())
		var fullname = $("input[name=manu_name]").val();
		
		$("#errormsg").html("");
		$("#errormsg").fadeIn();
		
		if(fullname=="" || fullname=='Your Name'){
		   $("#errormsg").html("Enter Manufacture Name");
		   $("input[name=fullname]").focus();
			return false;
		}
		
		
		
		
		
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
                          <td align="left" valign="middle" class="middle_bg_blu"><h4>Add New Manufacture</h4></td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                      <tr>
                      <td height="12"  
					  <div style="color:red; margin-left:300px;"><?php echo $_SESSION['regmsg'] ; $_SESSION['regmsg']=""; ?></div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      <form name="adddealer"  method="post" onsubmit="return chkreg();" >
						 <input type="hidden" name="task" value="add_manufacture" />						
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
					  
		 <tr>
   <td align="left" valign="top" class="page-tile"><div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px; display:none;"></div>
		<?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg']!="") { ?>
		<div id="errormsg" style="color:#FF0000; font-size:11px; line-height:20px;"><?php echo $_SESSION['errmsg'] ; echo $_SESSION['errmsg']="";?></div>
		<?php } ?></td>
     <td align="left" valign="top" class="tr-color"> 
      
      </td>
  </tr>
  
  
                       
  <tr>
   <td align="left" valign="top" class="page-tile"><strong>Year</strong></td>
     <td align="left" valign="top" class="tr-color"> 
       <select name="year" class="input-type">
		   <?php 
	   $selyear=mysql_query("select * from year  order by year desc");
	   while($rowyear=mysql_fetch_array($selyear))
	   {	   
	   ?>
	    <option value="<?php echo $rowyear['year']; ?>"><?php echo $rowyear['year']; ?></option>
		<?php } ?>
	   
       </select>
      </td>
  </tr>
 
  <tr>
    <td width="20%" align="left" valign="top" class="page-tile tr-color"><strong>Name</strong></td>
    <td width="80%" align="left" valign="top"  class="tr-color"><input name="manu_name" type="text" value="" class="input-type"/></td>
  </tr>
  
   
  
  
 
  
   <tr>
    <td align="left" valign="middle" class="page-tile">&nbsp;</td>
    <td align="left" valign="middle">
    <input type="submit" name="update" value="Add New Manufacture" class="inputBtn" />
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
