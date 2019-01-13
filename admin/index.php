<?php
require('includes/application_top.php');

if($_SESSION['admin_id']==""){
		header("location:login.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Caroye.com :: Admin</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="ddaccordion.js"></script>
<style type="text/css">
<!--
a:link, a:visited, a:active{ color:#424242; text-decoration:none;}
a:hover{color:#424242; text-decoration:underline;}
.dash-box {
	display: inline;
	margin: 0px;
	padding: 0px;
	float: left;
	height: auto;
	width: 285px;
	padding-right:15px;
	padding-bottom:20px;
}
.dash-box h5 {
 font-family:Verdana, Arial, Helvetica, sans-serif;
 color:#FFFFFF;
 font-size:11px; font-weight:bold; padding:0px;
 margin:0px; 
}
.dash-box h5 span {
padding-left:5px; padding-right:5px; 
 
}
.top-bg-dash-b{
	background-image: url(images/top-bg-dash-b.png);
	background-repeat: repeat-x;
	background-position: left top;
	height:26px;

}
.bottom-bg-dash-b{
	background-image: url(images/bottom-bg-dash-b.png);
	background-repeat: repeat-x;
	background-position: left top;
	height:8px;

}
.right-bg-dash-b{
	background-image: url(images/right-bg-dash-b.png);
	background-repeat: repeat-y;
	background-position: left top;


}
.left-bg-dash-b{
	background-image: url(images/left-bg-dash-b.png);
	background-repeat: repeat-y;
	background-position: left top;
	border-left:solid 1px #f1f1f1;


}
.dash-text{
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:11px;
color:#000000;


}

#dash-icon-container{ width:500px; position:relative;}
#dash-icon{ width:30px; float:left; display:inline;}
#dash-text{ width:200px; float:left; display:inline;}
#dash-text h4{ line-height:23px;}
-->
</style>

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
                          <td align="left" valign="middle" class="middle_bg_blu">
                          <div id="dash-icon-container">
                          		<div id="dash-icon"><img src="images/dashbord-icon.png" border="0" /></div>
                          		<div id="dash-text"><h4>Dashboard</h4></div>
                          </div>
                          </td>
                          <td width="31" align="right" valign="top"><img src="images/right_rouud_blu.jpg" alt="" width="31" height="35" /></td>
                        </tr>
                      </table></td>
                    </tr>
                     <tr>
                      <td height="12"></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">
                      
                      <div class="dash-box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/top-left-r.png" alt="" width="8" height="25" /></td>
    <td width="96%" class="top-bg-dash-b"><h5><span><img src="images/catalog-icon.png" alt="" /></span>CMS</h5></td>
    <td width="2%" align="left" valign="top"><img src="images/top-right-round.png" alt="" width="8" height="26" /></td>
  </tr>
  <tr>
    <td width="2%" align="left" valign="top" class="left-bg-dash-b"></td>
    <td align="left" valign="top" class="dash-text">
    	<ul>
       <?php
		$sql = "SELECT * FROM website_pages";
		$req = tep_db_query($sql);
		while($res = tep_db_fetch_array($req)){
		echo '<li><font color="#424242"><a href="website_pages.php?page_id='.$res['page_id'].'">'.$res['page_title'].'</a></font></li>';
		}
	  
      ?>
      </ul>
      </td>
    <td width="2%" align="left" valign="top" class="right-bg-dash-b"></td>
  </tr>
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/bottom-left-r.png" alt="" width="8" height="8" /></td>
    <td align="left" valign="top" class="bottom-bg-dash-b"></td>
    <td align="left" valign="top"><img src="images/bottom-right-r.png" alt="" width="8" height="8" /></td>
  </tr>
</table>
</div>

<div class="dash-box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/top-left-r.png" alt="" width="8" height="25" /></td>
    <td width="96%" class="top-bg-dash-b"><h5><span><img src="images/catalog-icon.png" alt="" /></span>Users</h5></td>
    <td width="2%" align="left" valign="top"><img src="images/top-right-round.png" alt="" width="8" height="26" /></td>
  </tr>
  <tr>
    <td width="2%" align="left" valign="top" class="left-bg-dash-b"></td>
    <td align="left" valign="top" class="dash-text">
    <ul>
       <?php
		/*$sql2 = "SELECT * FROM services";
		$req2 = tep_db_query($sql2);
		while($res2 = tep_db_fetch_array($req2)){
		echo '<li><font color="#424242"><a href="services_update.php?id='.$res2['id'].'">'.$res2['title'].'</a></font></li>';
		}
	  */
      ?>
      </ul>
    </td>
    <td width="2%" align="left" valign="top" class="right-bg-dash-b"></td>
  </tr>
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/bottom-left-r.png" alt="" width="8" height="8" /></td>
    <td align="left" valign="top" class="bottom-bg-dash-b"></td>
    <td align="left" valign="top"><img src="images/bottom-right-r.png" alt="" width="8" height="8" /></td>
  </tr>
</table>
</div>

<div class="dash-box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/top-left-r.png" alt="" width="8" height="25" /></td>
    <td width="96%" class="top-bg-dash-b"><h5><span><img src="images/catalog-icon.png" alt="" /></span>Products</h5></td>
    <td width="2%" align="left" valign="top"><img src="images/top-right-round.png" alt="" width="8" height="26" /></td>
  </tr>
  <tr>
    <td width="2%" align="left" valign="top" class="left-bg-dash-b"></td>
    <td align="left" valign="top" class="dash-text">
    <ul>
       <?php
		/*$sql3 = "SELECT * FROM products";
		$req3 = tep_db_query($sql3);
		while($res3 = tep_db_fetch_array($req3)){
		echo '<li><font color="#424242"><a href="product_update.php?id='.$res3['id'].'">'.$res3['title'].'</a></font></li>';
		}*/
		?>
      </ul>
    </td>
    <td width="2%" align="left" valign="top" class="right-bg-dash-b"></td>
  </tr>
  <tr>
    <td width="2%" align="right" valign="top"><img src="images/bottom-left-r.png" alt="" width="8" height="8" /></td>
    <td align="left" valign="top" class="bottom-bg-dash-b"></td>
    <td align="left" valign="top"><img src="images/bottom-right-r.png" alt="" width="8" height="8" /></td>
  </tr>
</table>
</div>
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
