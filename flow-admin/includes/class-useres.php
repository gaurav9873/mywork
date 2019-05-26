<?php
include 'db.php';
class useres extends ConnectDb{
	
	public $db = null;
	
	public function __construct(){
		$this->db = new ConnectDb();
	}
	
	
	public function add_useres($vals){
		try{
			$lastID  =  $this->db->insert_records('op2mro9899_admin_useres',$vals); 
			return $lastID;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function fetch_useres($tbname){
		try{
			$stmt = $this->db->fetchAll('op2mro9899_admin_useres');
			return $stmt;
		}catch(PDOEXception $e){
			echo $e->getMessage();
			return false;
		}
	}
	
}

?>
