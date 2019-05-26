<?php
ini_set('memory_limit', '256M');
ob_start();
date_default_timezone_set('Europe/London');
// Singleton to connect db.
class ConnectDb {
	
  // Hold the class instance.
  private static $instance = null;
  private $conn;
  
  private $host = 'localhost';
  private $user = 'root';
  private $pass = '';
  private $name = 'fdl_live';
   
  // The db connection is established in the private constructor.
  public function __construct(){
    $this->conn = new PDO("mysql:host={$this->host};
    dbname={$this->name}", $this->user,$this->pass,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    //return $this->conn; 
  }
  

	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new ConnectDb();
		}
		return self::$instance;
	}

	public function getConnection(){
		return $this->conn;
	}
  
  
  public function insert_records($table,$parameters){
		
		$columns = implode("`,`",array_keys($parameters));
		$values = implode("','",$parameters);
		try
		{   
			$stmt = $this->conn->prepare("INSERT INTO `".$table."` (`".$columns."`) VALUES ('".$values."')");
			$stmt->execute();
			return $this->conn->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function insertRecords($table,$data){
		
		if(!empty($data) && is_array($data)){
			$columns = '';
			$values  = '';
			$i = 0;
			//$data['created_date'] = date("Y-m-d H:i:s");
			//$data['created_ip'] = getUserIP();
			
			$columnString = implode(',', array_keys($data));
			$valueString = ":".implode(',:', array_keys($data));
			
			$sql = "INSERT INTO $table ($columnString) VALUES ($valueString)";
			
			
			$query = $this->conn->prepare($sql);
			
			foreach($data as $key=>$val){
				$query->bindValue(':'.$key, $val);
			}
			$insert = $query->execute();
			if($insert){
				$data = $this->conn->lastInsertId();
			return $data;
			} else {
				return false;
			}
		} else {
			throw new Exception('data variable not found');
			return false;
		}
	}
	
	
	public function fetchAll($tb_name){

		try{
			$sql_stmt = $this->conn->prepare("SELECT * FROM $tb_name WHERE TRUE ORDER BY site_id DESC");
			$sql_stmt->execute();
			$domain_data = $sql_stmt->fetchAll(PDO::FETCH_ASSOC);
			return $domain_data;
			
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		
	}
	
	public function get_active_domain(){
		$sql = "SELECT site_id, domain_name, status FROM op2mro9899_add_domain WHERE status = 1 ORDER BY site_id DESC";
		try{
			$dbstmt = $this->conn->prepare($sql);
			$dbstmt->execute();
			$active_domain = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
			return $active_domain;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
		
	public function get_allCategories(){
		try{
			$stmt = $this->conn->prepare("SELECT cat_id, category_name FROM op2mro9899_category WHERE TRUE");
			$stmt->execute();
			$cat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $cat_list;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	

	
	public function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '') {

		try{
			if (!is_array($user_tree_array))
			$user_tree_array = array();
			$sql = "SELECT `cat_id`, `category_name`, `parent_category` FROM `op2mro9899_category` WHERE TRUE AND `parent_category` = $parent ORDER BY cat_id ASC";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($row as $tree_arr){
				$user_tree_array[] = array("id" => $tree_arr->cat_id, 'parent' => $tree_arr->parent_category, "name" => $spacing . $tree_arr->category_name);
				$user_tree_array = $this->fetchCategoryTree($tree_arr->cat_id, $spacing . '&nbsp;&nbsp;&nbsp;&nbsp;', $user_tree_array);
			}
			return $user_tree_array;
			
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function child_category_byID($parent_cat_id){
		$sql = "SELECT * FROM op2mro9899_category WHERE parent_category = :parent_cat_id ORDER BY cat_order ASC";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindparam("parent_cat_id", $parent_cat_id);
			$stmt->execute();
			$child_cat = $stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($child_cat);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function fetchAll_user($tbname){
		try{
			$sql_stmt = $this->conn->prepare("SELECT * FROM $tbname WHERE TRUE ORDER BY id DESC");
			$sql_stmt->execute();
			$domain_data = $sql_stmt->fetchAll(PDO::FETCH_ASSOC);
			return $domain_data;
			
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function fetchAllRecord($tbname, $column_name, $shop_id = null){
		if(null === $shop_id){
			$qstr = 'TRUE';
		}else{
			$qstr = "site_id = $shop_id";
		}
		try{
			$stmt = $this->conn->prepare("SELECT * FROM $tbname WHERE $qstr ORDER BY $column_name DESC");
			$stmt->execute();
			$all_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $all_records;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function fetchByOrder($tbname, $colname){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM $tbname WHERE TRUE ORDER BY $colname ASC");
			$stmt->execute();
			$allData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $allData;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}

	public function get_row_by_id($tbname, $column_name, $id, $orderby = null){
			if(null === $orderby){
				$qstr = '';
			}else{
				$qstr = "ORDER BY $orderby ASC";
			}
		try{
			$stmt = $this->conn->prepare("SELECT * FROM $tbname WHERE $column_name = '".$id."' $qstr");
			$stmt->execute(array(":$column_name" => $id));
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $rows;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function deleteRow($tbname, $column_name, $id){
		try{
			$delete_stmt = $this->conn->prepare("DELETE FROM $tbname WHERE $column_name = '".$id."'");
			$delete_stmt->bindparam(":$column_name", $id);
			$delete_stmt->execute();
			return true;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function delteRows($tbname, $col_name, $id){
		try{
			$del_stmt = "DELETE FROM $tbname WHERE $col_name IN($id)";
			$con_stmt = $tis->conn->prepare($del_stmt);
			$con_stmt->execute();
			return true;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function deleteImage($site_id, $cat_id){
		try{
			$stmt = $this->conn->prepare("DELETE FROM op2mro9899_category_image WHERE site_id = $site_id AND cat_id = $cat_id");
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	
	public function update_row($table_name, $form_data, $where_clause=''){
		try{
			$whereSQL = '';
			if(!empty($where_clause)){
				if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE'){
					$whereSQL = " WHERE ".$where_clause;
				}else{
					$whereSQL = " ".trim($where_clause);
				}
			}
			$sql = "UPDATE `".$table_name."` SET ";
			
			$sets = array();
			foreach($form_data as $column => $value){
				$sets[]="".$column." = '".$value."'";
			}
			
			$sql .= implode(', ', $sets);
			$sql.= $whereSQL;
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			return true;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	
	}
	
	public function check_exist_value($tbname, $column_name, $value){
		try{
			
			$stmt = $this->conn->prepare("SELECT count(*) FROM $tbname WHERE $column_name = '".$value."'");
			$stmt->execute(array(":$column_name" => $value));
			$rows = $stmt->fetchColumn();
			return $rows;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function getManagername(){
		try{
			$stmt = "SELECT u.id, u.user_name, u.user_email, u.user_level, u.domain_id, u.user_status, 
					 u.created_date, u.created_ip, d.site_id, d.domain_name
					 FROM op2mro9899_admin_useres u LEFT JOIN op2mro9899_add_domain d
					 ON u.domain_id = d.site_id ORDER BY u.id DESC";
			$req = $this->conn->prepare($stmt);
			$req->execute();
			$cols = $req->fetchAll(PDO::FETCH_ASSOC);
			return $cols;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function get_site_name_byID($id){
		try{

			$sql_stmt = "SELECT u.id, u.user_name, u.user_email, u.user_level, u.domain_id, u.user_status,
			u.created_date, u.created_ip, d.site_id, d.domain_name FROM op2mro9899_admin_useres u
			LEFT JOIN op2mro9899_add_domain d
			ON u.domain_id = d.site_id WHERE u.id= '".$id."' ORDER BY u.id DESC";
			$res = $this->conn->prepare($sql_stmt);
			$res->execute();
			$row = $res->fetchAll(PDO::FETCH_ASSOC);
			return $row;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function listcategories(){
		try{
			$sql_stmt = $this->conn->prepare("SELECT c.cat_id, c.category_name, CASE WHEN c.parent_category=0 THEN 'Parent' else '' end AS cat_name, 
											  cr.category_id, GROUP_CONCAT(cr.site_id) as site_id, GROUP_CONCAT(s.domain_name) as domain_name 
											  FROM op2mro9899_category AS c LEFT JOIN op2mro9899_category_relation as cr ON 
											  c.cat_id = cr.category_id INNER JOIN op2mro9899_add_domain as s ON cr.site_id = s.site_id 
											  GROUP BY c.category_name");
			$sql_stmt->execute();
			$table = $sql_stmt->fetchAll(PDO::FETCH_ASSOC);
			return $table;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function treeView($parent_id = 0, $space = '', $treeView = ''){
		try{
			if (!is_array($treeView))
			$treeView = array();
					  
			$stmt = "SELECT c.cat_id, c.category_name, c.parent_category, c.active, c.menu, c.cat_order, c.gift_status,
			         GROUP_CONCAT(cr.site_id) as sid, GROUP_CONCAT(d.domain_name) as sname, d.site_id FROM `op2mro9899_category` AS c 
					 LEFT JOIN op2mro9899_category_relation AS cr ON c.cat_id = cr.category_id
			         LEFT JOIN op2mro9899_add_domain as d ON
			         cr.site_id = d.site_id WHERE TRUE AND `parent_category` = $parent_id
			         GROUP BY c.category_name ORDER BY  c.cat_order ASC, c.cat_order ASC";
			$req = $this->conn->prepare($stmt);
			$req->execute();
			$row = $req->fetchAll(PDO::FETCH_OBJ);
			//print_r($row);
			foreach($row as $tree_arr){
				$treeView[] = array("id" => $tree_arr->cat_id, 'pid' => $tree_arr->parent_category, 
									'active_val' => $tree_arr->active, 'menu' => $tree_arr->menu, "sname" => $tree_arr->sname, 
									"sid" => $tree_arr->sid,  "name" => $space . $tree_arr->category_name, "gift_status" => $tree_arr->gift_status);
				$treeView = $this->treeView($tree_arr->cat_id, $space . '&nbsp;&nbsp;&nbsp;&nbsp;', $treeView);
			}
			return $treeView;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		
	}
	
	public function getDomainNmaeByID($site_id){
		try{
			$stmt = "SELECT site_id, domain_name FROM op2mro9899_add_domain WHERE site_id = $site_id";
			$res = $this->conn->prepare($stmt);
			$res->execute();
			$dname = $res->fetchAll(PDO::FETCH_ASSOC);
			return $dname[0]['domain_name'];
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function getCategoryName($cat_id){
		try{
			$sql = "SELECT cat_id, category_name FROM op2mro9899_category WHERE cat_id = $cat_id";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(isset($row[0])){
				return $row[0]['category_name'];
			}else{
				return '';
			}
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function get_parent_category($condition = null){
		if($condition == '1'){
			$queryStr = 'ORDER BY cat_order ASC';
		}else{
			$queryStr='';
		}
		$sql = "SELECT * FROM op2mro9899_category WHERE parent_category = 0 $queryStr";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$parent_row = $stmt->fetchAll(PDO::FETCH_OBJ);
			return  $parent_row;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function parent_category_order(){
		$sql = "SELECT * FROM `op2mro9899_category` WHERE `parent_category`=0 ORDER BY `cat_order` ASC LIMIT 0,1";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$cat_order = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $cat_order;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function active_on_home_category(){
		$sql = "SELECT * FROM op2mro9899_category WHERE parent_category = 0 AND active = 1";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$vals = $stmt->fetchAll(PDO::FETCH_OBJ);
			return  $vals;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function getGalleryCategory(){
		try{
			$sql = "SELECT id, category_name FROM op2mro9899_gallery_category WHERE TRUE";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $row;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function getGalleryCtegoryName($cat_id){
		try{
			$sql = "SELECT id, category_name FROM op2mro9899_gallery_category WHERE id = $cat_id";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $row[0]['category_name'];
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function get_gallery_category_by_domain($domain_id){
		
		$sql = "SELECT c.id, c.category_name, c.status, c.order, r.gallery_cat_id, r.site_id, r.gal_order, r.status
				FROM op2mro9899_gallery_category as c 
				INNER JOIN op2mro9899_gallery_category_relation as r ON c.id = r.gallery_cat_id 
				WHERE c.status = 1 AND r.site_id = :domain_id ORDER BY r.gal_order ASC";
		
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam("domain_id", $domain_id);
			$stmt->execute();
			$row_val = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $row_val;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		
	}
	
	public function get_record_orderby($tbname, $field_name){
		$sql = "SELECT * FROM $tbname WHERE TRUE ORDER BY $field_name ASC";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$row_vals = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $row_vals;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function allOrders($shop_id, $start_offset = null, $end_offset = null){
		
		if (null === $start_offset && null === $end_offset){
			$query_str = '';
		}else{
			$query_str = "LIMIT $start_offset, $end_offset";
		}
		
		$sql = "SELECT d.id, d.user_name, d.email_address, d.order_id, d.user_id, d.ordered_date, d.delivery_date, d.created_date, d.site_id,
				p.payment_id, p.item_number, p.payment_status, p.order_status, p.order_id, p.user_id, p.created_date, p.mc_gross, p.print_status, 
				o.id, COUNT(1) as `total_quantity`, o.order_id, o.created_date 
				FROM `op2mro9899_delivery_address` d 
				INNER JOIN `op2mro9899_payments` p ON d.order_id = p.order_id 
				INNER JOIN `op2mro9899_ordered_product` o ON d.order_id = o.order_id WHERE d.site_id= $shop_id
				GROUP BY o.order_id ORDER BY d.id DESC $query_str";
		try{
			$order_stmt = $this->conn->prepare($sql);
			$order_stmt->execute();
			$all_orders = $order_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($all_orders);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function order_by_date_range($shop_id, $todate, $fromdate, $offset1 = null, $offset2 = null){
		
		if(null === $offset1 && null === $offset2){
			$qstr = '';
		}else{
			$qstr = "LIMIT $offset1, $offset2";
		}
		
		$sql = "SELECT d.id, d.user_name, d.email_address, d.order_id, d.user_id, d.ordered_date, d.delivery_date, d.created_date, d.site_id,
				p.payment_id, p.item_number, p.payment_status, p.order_status, p.order_id, p.user_id, p.created_date, p.mc_gross, p.print_status, 
				o.id, COUNT(1) as `total_quantity`, o.order_id, o.created_date 
				FROM `op2mro9899_delivery_address` d 
				INNER JOIN `op2mro9899_payments` p ON d.order_id = p.order_id 
				INNER JOIN `op2mro9899_ordered_product` o ON d.order_id = o.order_id
				WHERE d.site_id=$shop_id d.ordered_date BETWEEN '$todate' AND '$fromdate'
				GROUP BY o.order_id ORDER BY d.id DESC $qstr";
		
		try{
			$stmts = $this->conn->prepare($sql);
			$stmts->execute();
			$data = $stmts->fetchAll(PDO::FETCH_OBJ);
			return json_encode($data);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function serach_ordered_product($shop_id, $search_key){
		 $search_sql = "SELECT d.id, d.user_name, d.email_address, d.order_id, d.user_id, d.ordered_date, d.delivery_date, d.created_date, p.payment_id, d.site_id,
						p.item_number, p.payment_status, p.order_status, p.order_id, p.user_id, p.created_date, p.mc_gross, p.print_status, 
						o.id, COUNT(1) as `total_quantity`, o.order_id, o.created_date FROM `op2mro9899_delivery_address` d 
						INNER JOIN `op2mro9899_payments` p ON d.order_id = p.order_id 
						INNER JOIN `op2mro9899_ordered_product` o ON d.order_id = o.order_id 
						WHERE d.site_id LIKE '%$shop_id%' AND (d.email_address LIKE '%$search_key%' OR d.order_id LIKE '%$search_key%' OR p.mc_gross LIKE '%$search_key%' OR
						p.order_status LIKE '%$search_key%' OR d.user_name LIKE '%$search_key%')
						GROUP BY o.order_id ORDER BY d.id DESC";
		
		try{
			$sstmts = $this->conn->prepare($search_sql);
			$sstmts->execute();
			$search_data = $sstmts->fetchAll(PDO::FETCH_OBJ);
			return json_encode($search_data);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function today_orders($site_id){
		$today_date = date("Y-m-d");
		$sql ="SELECT d.id, d.user_name, d.email_address, d.order_id, d.user_id, d.ordered_date, d.delivery_date, d.created_date, 
				p.payment_id, p.item_number, p.payment_status, p.order_status, p.order_id, p.user_id, p.site_id, p.created_date, p.mc_gross, p.print_status,
				o.id, COUNT(1) as `total_quantity`, o.order_id, o.created_date 
				FROM `op2mro9899_delivery_address` d 
				INNER JOIN `op2mro9899_payments` p ON d.order_id = p.order_id 
				INNER JOIN `op2mro9899_ordered_product` o ON d.order_id = o.order_id 
				WHERE d.delivery_date = '$today_date' AND p.order_status = 'due' AND p.site_id = '$site_id'
				GROUP BY o.order_id ORDER BY d.delivery_date ASC";
	 try{
			$order_stmt = $this->conn->prepare($sql);
			$order_stmt->execute();
			$today_order = $order_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($today_order);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}	
	}
	
	
	public function tomorrow_order($site_id){
		$d = strtotime("tomorrow");
		$tomorrow_date =  date("Y-m-d", $d);
		$sql ="SELECT d.id, d.user_name, d.email_address, d.order_id, d.user_id, d.ordered_date, d.delivery_date, d.created_date, 
				p.payment_id, p.item_number, p.payment_status, p.order_status, p.order_id, p.user_id, p.site_id, p.created_date, p.mc_gross, p.print_status,
				o.id, COUNT(1) as `total_quantity`, o.order_id, o.created_date 
				FROM `op2mro9899_delivery_address` d 
				INNER JOIN `op2mro9899_payments` p ON d.order_id = p.order_id 
				INNER JOIN `op2mro9899_ordered_product` o ON d.order_id = o.order_id 
				WHERE d.delivery_date = '$tomorrow_date' AND p.order_status = 'due' AND p.site_id = '$site_id'
				GROUP BY o.order_id ORDER BY d.delivery_date ASC";
		
		 try{
			$order_statement = $this->conn->prepare($sql);
			$order_statement->execute();
			$tomorrow_orders = $order_statement->fetchAll(PDO::FETCH_OBJ);
			return json_encode($tomorrow_orders);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}	
		
		
	}
	
	public function user_billing_address($user_id, $site_id){
		$sql_stmt = "SELECT * FROM op2mro9899_customers_billing_address WHERE user_id = :user_id AND site_id = :site_id AND default_address = 1";
		try{
			$billing_stmt = $this->conn->prepare($sql_stmt);
			$billing_stmt->bindParam("user_id", $user_id);
			$billing_stmt->bindParam("site_id", $site_id);
			$billing_stmt->execute();
			$billing_address = $billing_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($billing_address);			
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function user_billing_address_custID($cust_id, $site_id){
		$sql_stmt = "SELECT * FROM op2mro9899_customers_billing_address WHERE customer_id = :cust_id AND site_id = :site_id AND default_address = 1";
		try{
			$billing_stmt = $this->conn->prepare($sql_stmt);
			$billing_stmt->bindParam("cust_id", $cust_id);
			$billing_stmt->bindParam("site_id", $site_id);
			$billing_stmt->execute();
			$billing_address_custid = $billing_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($billing_address_custid);			
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	
	public function invoice_order_detail($order_id){
		$sql = "SELECT * FROM op2mro9899_ordered_product WHERE order_id = :order_id";
		try{
			$invoice_stmt = $this->conn->prepare($sql);
			$invoice_stmt->bindparam("order_id", $order_id);
			$invoice_stmt->execute();
			$invoice_order = $invoice_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($invoice_order);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function user_payment_detail($oid){
		$sql = "SELECT * FROM op2mro9899_payments WHERE order_id = :oid";
		try{
			$oid_stmt = $this->conn->prepare($sql);
			$oid_stmt->bindparam("oid", $oid);
			$oid_stmt->execute();
			$p_detail = $oid_stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($p_detail);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function all_products($start,$end){
		$sql = "SELECT * FROM op2mro9899_products WHERE TRUE ORDER BY pid DESC LIMIT $start,$end";
		try{
			$stmt = $this->conn->prepare($sql);
			//$stmt->bindparam("start",$start);
			//$stmt->bindparam("end",$end);
			$stmt->execute();
			$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $all_products;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function search_product($search_keyword){
		$sql = "SELECT * FROM `op2mro9899_products` WHERE product_name LIKE '%$search_keyword%' 
				OR regular_price LIKE '%$search_keyword%'
				OR product_code LIKE '%$search_keyword%'";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$search_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $search_data;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function count_row($tbname){
		$sql = "SELECT count(*) FROM `$tbname` WHERE TRUE";
		try{ 
			$result = $this->conn->prepare($sql);
			$result->bindparam("tbname", $tbname);
			$result->execute(); 
			$number_of_rows = $result->fetchColumn(); 
			return $number_of_rows;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function count_cat_product($cid){
		$sql = "SELECT COUNT(*) FROM `op2mro9899_products_relation` WHERE `cat_id` = $cid";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$cat_rows = $stmt->fetchColumn();
			return $cat_rows;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function get_product_by_cat_id($shop_id, $cat_id, $start_offset, $end_offset){
		
		/*SELECT p.pid, p.product_name, p.description, p.short_description, p.regular_price, p.large_price, p.disscount_price, p.product_code, p1.id, p1.pid, p1.product_order, p1.cat_id, p2.site_id
		FROM op2mro9899_products p
		INNER JOIN op2mro9899_products_relation p1 ON p.pid = p1.pid
		INNER JOIN op2mro9899_product_site_relation p2 ON p.pid = p2.pid
		WHERE p1.cat_id =8
		AND p2.site_id =1
		ORDER BY p1.product_order ASC
		LIMIT 0 , 30*/
		
		$sql = "SELECT p.pid, p.product_name, p.description, p.short_description, p.regular_price, p.large_price, p.disscount_price, p.product_code, 
				p1.id, p1.pid, p1.product_order, p1.cat_id, p2.site_id FROM op2mro9899_products p 
				INNER JOIN op2mro9899_products_relation p1 ON p.pid = p1.pid 
				INNER JOIN op2mro9899_product_site_relation p2 ON p.pid = p2.pid
				WHERE p1.cat_id = :cat_id AND p2.site_id = :shop_id
				ORDER BY p1.product_order ASC LIMIT $start_offset, $end_offset";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindparam("cat_id", $cat_id);
			$stmt->bindparam("shop_id", $shop_id);
			$stmt->execute();
			$cat_product = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $cat_product;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	  
	  
	public function search_customers($shop_id, $serach_string){
		$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE site_id = $shop_id AND default_address = 1 AND (user_first_name LIKE '%$serach_string%' 
				OR user_last_name LIKE '%$serach_string%' OR user_phone LIKE '%$serach_string%' 
				OR user_emailid LIKE '%$serach_string%' OR customer_id LIKE '%$serach_string%')";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$search_cust = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $search_cust;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function fetchAllRecords($tbname, $column_name, $start_offset, $end_offset){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM $tbname WHERE TRUE ORDER BY $column_name DESC LIMIT $start_offset, $end_offset");
			$stmt->execute();
			$all_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $all_records;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function search_by_date($tbname, $column_name, $sdate, $edate, $order_column, $offset1, $offset2){
		$sql = "SELECT * FROM `$tbname` WHERE `$column_name` BETWEEN '$sdate' AND '$edate' ORDER BY `$order_column` DESC LIMIT $offset1, $offset2";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$all_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $all_rows;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function customer_lists($shop_id, $offset1, $offset2){
		$sql ="SELECT * FROM op2mro9899_customers_billing_address WHERE default_address =1 AND site_id = '$shop_id' ORDER BY id DESC LIMIT $offset1, $offset2";
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$all_cust = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $all_cust;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function count_customer_lists($shop_id){
		$sql = "SELECT COUNT(*) FROM op2mro9899_customers_billing_address WHERE default_address = 1 AND site_id='$shop_id'";
		try{
			$cstmt = $this->conn->prepare($sql);
			$cstmt->execute();
			$count_list = $cstmt->fetchColumn();
			return $count_list;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function customer_date_search($shop_id, $todate, $fromdate, $limit1, $limit2){
		$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE default_address = 1 AND site_id = '$shop_id' AND reg_date BETWEEN '$todate' AND '$fromdate' 
				ORDER BY id DESC LIMIT $limit1, $limit2";
		try{
			$statement = $this->conn->prepare($sql);
			$statement->execute();
			$date_filter = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $date_filter;
		}catch(PDOException $e){
			
		}
	}
	
	public function countCustomerData($shop_id, $sdate, $edate){
		$sql = "SELECT COUNT(*) FROM op2mro9899_customers_billing_address WHERE default_address = 1 AND site_id = '$shop_id' AND reg_date BETWEEN '$sdate' AND '$edate' ORDER BY id DESC";
		try{
			$stmts = $this->conn->prepare($sql);
			$stmts->execute();
			$countData = $stmts->fetchColumn();
			return $countData;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function export_csv_byDate_range($sdate, $edate){
		$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE default_address = 1 AND reg_date BETWEEN '$sdate' AND '$edate' ORDER BY id DESC";
		try{
			$statement = $this->conn->prepare($sql);
			$statement->execute();
			$date_filter = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $date_filter;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function export_customer_csv(){
		$sql = "SELECT * FROM op2mro9899_customers_billing_address WHERE default_address = 1 ORDER BY id DESC";
		try{
			$csv_stmt = $this->conn->query($sql);
			$csv_stmt->execute();
			$csv_con = $csv_stmt->fetchAll(PDO::FETCH_ASSOC);
			return $csv_con;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function duplicate_product_list(){
		$sql = "SELECT pid, product_name FROM op2mro9899_products WHERE product_name IN (
			SELECT product_name FROM op2mro9899_products GROUP BY product_name  HAVING COUNT(pid) > 1) ORDER BY product_name ASC";
		try{
			$dbh_stmt = $this->conn->query($sql);
			$dbh_stmt->execute();
			$dup_data = $dbh_stmt->fetchAll(PDO::FETCH_ASSOC);
			return $dup_data;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function delivery_email_by_orderID($uorder_id){
		$sql = "SELECT id, email_address, order_id FROM op2mro9899_delivery_address WHERE order_id = :uorder_id";
		try{
			$dbs = $this->conn->prepare($sql);
			$dbs->bindparam('uorder_id', $uorder_id);
			$dbs->execute();
			$demial = $dbs->fetchAll(PDO::FETCH_ASSOC);
			return $demial;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function get_ptoduct_id($product_name){
		$sql = "SELECT pid, product_name FROM op2mro9899_products WHERE product_name = :product_name";
		try{
			$dbs = $this->conn->prepare($sql);
			$dbs->bindparam('product_name', $product_name);
			$dbs->execute();
			$pid = $dbs->fetchAll(PDO::FETCH_ASSOC);
			$product_id = isset($pid[0]['pid']) ? $pid[0]['pid'] : '';
			return $product_id;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	  
	 
	public function unassign_product($shop_id, $offset, $limit){
		//$sql = "SELECT a . * FROM `op2mro9899_products` a NATURAL LEFT JOIN `op2mro9899_products_relation` b WHERE b.pid IS NULL LIMIT $offset, $limit";
		//echo $sql = "SELECT a. * FROM `op2mro9899_products` a NATURAL LEFT JOIN `op2mro9899_product_site_relation` b NATURAL LEFT JOIN `op2mro9899_products_relation` c WHERE b.site_id =$shop_id AND c.pid IS NULL LIMIT $offset, $limit";
		$sql = "SELECT a. * FROM `op2mro9899_products` a LEFT JOIN `op2mro9899_product_site_relation` b ON a.pid = b.pid
				LEFT JOIN `op2mro9899_products_relation` c ON a.pid = c.pid
				WHERE b.site_id =$shop_id AND c.pid IS NULL LIMIT $offset, $limit";
		try{
			$dbh = $this->conn->query($sql);
			$dbh->execute();
			$unassign_products = $dbh->fetchAll(PDO::FETCH_ASSOC);
			return $unassign_products;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function unassign_site(){
		$sql = "SELECT a.pid as product_id, a.product_name, a.product_code, b.pid, b.site_id FROM `op2mro9899_products` a LEFT JOIN `op2mro9899_product_site_relation` b ON a.pid = b.pid WHERE b.pid IS NULL";
		try{
			$dbstmt = $this->conn->query($sql);
			$dbstmt->execute();
			$unassign_site_product = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
			return $unassign_site_product;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function get_page_by_domian_id($shop_id){
		$sql = "SELECT t.id, t.page_name, t.short_description, t.status, t.show_home_page, t.created_date, t.created_ip, t1.page_id, t1.site_id FROM op2mro9899_pages t
				INNER JOIN op2mro9899_pages_relation t1 ON t.id = t1.page_id WHERE t1.site_id = $shop_id";
		try{
			$dbstmt = $this->conn->query($sql);
			$dbstmt->execute();
			$pages = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
			return $pages;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	public function chk_product_code($product_id){
		$sql = "SELECT pid, product_code FROM op2mro9899_products WHERE pid = :product_id";
		try{
			$code_stmt = $this->conn->prepare($sql);
			$code_stmt->bindparam('product_id', $product_id);
			$code_stmt->execute();
			$productcode = $code_stmt->fetchAll(PDO::FETCH_OBJ);
			return $productcode;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}

		
	public function nicola_orders($offset1 = null, $offset2 = null){
		
		if(null === $offset1 && null === $offset2){
			$qstr = '';
		}else{
			$qstr = "LIMIT $offset1, $offset2";
		}
		$sql = "SELECT a.orderID, a.datetime, a.ip, a.customerID, a.title, a.forename, a.surname, a.address1, a.address2, a.town, a.county,a.country, a.postcode,
				a.telephone, a.fax, a.email, a.company, a.deliveryCompany, a.deliveryName, a.deliveryAddress1, a.deliveryAddress2, a.deliveryTown, a.deliveryCounty,
				a.deliveryCountry, a.deliveryPostcode, a.deliveryTelephone, a.ccName, a.ccNumber, a.ccExpiryDate, a.ccType, a.ccStartDate, a.ccIssue, a.ccCVV, a.currencyID,
				a.goodsTotal, a.shippingTotal, a.taxTotal, a.discountTotal, a.giftCertTotal, a.status, a.shippingMethod, a.paymentID, a.paymentName, a.paymentDate,
				a.authInfo, a.terms, a.shippingID, a.randID, a.orderPrinted, a.orderNotes, a.paymentNameNative, a.shippingMethodNative, a.languageID, a.giftCertOrder,
				a.referURL, a.accTypeID, a.affiliateID, a.offerCode, a.e_delivery_date, b.orderID, b.lineID, b.extraFieldID, b.extraFieldName, b.extraFieldTitle,
				b.exvalID, b.content, b.contentNative, c.lineID, c.orderID, c.productID, c.code, c.name, c.qty, c.weight, c.price, c.nameNative, c.taxamount, c.isDigital, c.digitalFile, c.digitalReg, c.downloadID, c.ooprice,
				c.ootaxamount, c.supplierID, c.suppliercode
				FROM jss_orders_headers a  
				INNER JOIN jss_orders_extrafields b ON a.orderID = b.orderID 
				INNER JOIN jss_orders_lines c ON a.orderID = c.orderID
				ORDER BY a.orderID DESC $qstr";
		 try{
			 $dbs_stmt = $this->conn->query($sql);
			 $dbs_stmt->execute();
			 $nic_orders = $dbs_stmt->fetchAll(PDO::FETCH_OBJ);
			 return $nic_orders;
		 }catch(PDOException $e){
			 echo $e->getMessage();
			 return false;
		 }
	}
	
	public function nicola_order_detail($oid){
		$sql = "SELECT a.orderID, a.datetime, a.ip, a.customerID, a.title, a.forename, a.surname, a.address1, a.address2, a.town, a.county,a.country, a.postcode,
				a.telephone, a.fax, a.email, a.company, a.deliveryCompany, a.deliveryName, a.deliveryAddress1, a.deliveryAddress2, a.deliveryTown, a.deliveryCounty,
				a.deliveryCountry, a.deliveryPostcode, a.deliveryTelephone, a.ccName, a.ccNumber, a.ccExpiryDate, a.ccType, a.ccStartDate, a.ccIssue, a.ccCVV, a.currencyID,
				a.goodsTotal, a.shippingTotal, a.taxTotal, a.discountTotal, a.giftCertTotal, a.status, a.shippingMethod, a.paymentID, a.paymentName, a.paymentDate,
				a.authInfo, a.terms, a.shippingID, a.randID, a.orderPrinted, a.orderNotes, a.paymentNameNative, a.shippingMethodNative, a.languageID, a.giftCertOrder,
				a.referURL, a.accTypeID, a.affiliateID, a.offerCode, a.e_delivery_date, b.orderID, b.lineID, b.extraFieldID, b.extraFieldName, b.extraFieldTitle,
				b.exvalID, b.content, b.contentNative, c.lineID, c.orderID, c.productID, c.code, c.name, c.qty, c.weight, c.price, c.nameNative, c.taxamount, c.isDigital, c.digitalFile, c.digitalReg, c.downloadID, c.ooprice,
				c.ootaxamount, c.supplierID, c.suppliercode
				FROM jss_orders_headers a  
				INNER JOIN jss_orders_extrafields b ON a.orderID = b.orderID 
				INNER JOIN jss_orders_lines c ON a.orderID = c.orderID
				WHERE a.orderID = :oid";
		 try{
			 $dbs_stmt = $this->conn->prepare($sql);
			 $dbs_stmt->bindparam('oid', $oid);
			 $dbs_stmt->execute();
			 $nic_order_detail = $dbs_stmt->fetchAll(PDO::FETCH_OBJ);
			 return $nic_order_detail;
		 }catch(PDOException $e){
			 echo $e->getMessage();
			 return false;
		 }
	}

    public function unprocessed_orders($site_id){
        $sql = "SELECT id, product, gift, delivery_address, order_id, delivery_charges, discount, site_id, user_id, customer_id, order_date, order_process, delivery_date 
  				FROM op2mro9899_tmp_order WHERE site_id = :site_id AND order_date != '0000-00-00' ORDER BY id DESC";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam('site_id', $site_id);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $orders;
        }catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    public function check_order_exist($orderid, $siteid){
        $sql = "SELECT t.order_id, t.site_id, t.customer_id, t.ordered_date, t.user_name, o.order_id, o.user_id, o.site_id, p.item_number, p.user_id, p.site_id, p.order_id
				FROM op2mro9899_delivery_address t INNER JOIN op2mro9899_ordered_product as o ON t.order_id = o.order_id
				INNER JOIN op2mro9899_payments as p ON t.order_id = p.order_id WHERE t.order_id = :orderid AND t.site_id = :siteid";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam('orderid', $orderid);
            $stmt->bindParam('siteid', $siteid);
            $stmt->execute();
            $chk_record = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $chk_record;
        }catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
	
		
	 
}

?>
