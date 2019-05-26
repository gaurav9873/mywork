<?php

include 'db.php';
class addDomain{
	public $db = null;
	
	public function __construct(){
		$this->db = new ConnectDb();
	}
	
	
	public function insert_domain($params){
		
		try{
			$returnId  =  $this->db->insert_records('op2mro9899_add_domain',$params); 
			return $returnId;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function getAllDomain(){
		try{
			$req = $this->db->fetchAll('op2mro9899_add_domain');
			return $req;
		}catch(PDOEXception $e){
			echo $e->getMessage();
			return false;
		}
	}
	
}

?>
