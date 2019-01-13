<?php 
include("includes/conn.php");

if($_POST['task']=='login')
{	
	$email = trim($_POST["email"]);
	$password = $_POST['password'] ;
	$_SESSION['loginmsg'] = "";
	 
	
	#$sqlres =  mysql_query("select * from users  where email='".mysql_escape_string($email)."' and password='".mysql_escape_string($password)."' and user_type='1'") ;
	$sqlres =  mysql_query("select * from users  where email='$email' and password='$password' and user_type='1'") ;
	
		if(mysql_num_rows($sqlres) > 0){
			
			$data = mysql_fetch_assoc($sqlres);
				
			if($data['status'] == 0){
				$_SESSION['loginmsg'] = "Inactive account";	
				header("location:index.php");
				exit;		
			} else {
				
				$_SESSION['email'] = $data['email'] ;
				$_SESSION['buyer'] = $data['id'] ;
				
				header("location:buyeraccount.php");
				exit;
			}
						
		}else{
						
			$_SESSION['loginmsg']= " hai user does not exist";
			
			header("location:index.php");
			exit;
		}
 }

?>