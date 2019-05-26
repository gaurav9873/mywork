<?php
include_once 'db.php';
class Login{
	
	public function __construct(){
		$db = ConnectDb::getInstance();
		$conn = $db->getConnection();
		return $this->conn = $conn;
	}

	
	public function doLogin($user_email,$user_password){
		
		$sql = "SELECT id, user_name, user_email, user_password, user_level, domain_id, user_status FROM op2mro9899_admin_useres
				WHERE user_email = :user_email AND user_password = :user_password AND user_status = 'Active'";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(array(':user_email'=>$user_email, ':user_password'=>$user_password));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0){
				if($user_password == $userRow['user_password']){	
					$_SESSION['user_id'] = $userRow['id'];
					$_SESSION['user_level'] = $userRow['user_level'];
					$_SESSION['user_name'] = $userRow['user_name'];
					$_SESSION['user_email'] = $userRow['user_email'];
					$_SESSION['site_id'] =  $userRow['domain_id'];
					return $_SESSION['user_level'];
				}else{
					return false;
				}
			}
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
}
?>
