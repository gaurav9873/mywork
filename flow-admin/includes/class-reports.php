<?php
include_once 'db.php';
class Userreports{
	
	public function __construct(){
		$db = ConnectDb::getInstance();
		$conn = $db->getConnection();
		return $this->conn = $conn;
	}


	public function start_months(){
		$html = '';
		$st_date = $_REQUEST['st_date'];
		$sel_month = explode('-', $st_date);
		$months = $sel_month[1];
		for($month =1;$month<=12;$month++){
			$num_padded = sprintf("%02d", $month);
			$sel = (($num_padded == $months) ? 'selected' : ($num_padded == '01') ? 'selected' : '');
			$month_name = date("M", strtotime("$month/12/10"));
			$html .= '<option value="'.$num_padded.'" '.$sel.'>'.$month_name.'</option>';
		}
		return $html;
	}
	
	public function start_day(){
		$sdayHtml = '';
		$current_date = date("d");
		$date_vals = $_REQUEST['st_date'];
		$sel_dates = explode('-', $date_vals);
		$req_date = $sel_dates[2];
		for($days = 1; $days<=31; $days++){
			$days_val = sprintf("%02d", $days);
			$sel_date = (($days_val == $req_date) ? 'selected' : ($days_val == '01') ? 'selected' : '');
			$sdayHtml .= '<option value="'.$days_val.'" '.$sel_date.'>'.$days_val.'</option>';
		}
		return $sdayHtml;
	}
	
	public function start_year(){
		$syear_html = '';
		$year_vals = $_REQUEST['st_date'];
		$sel_years = explode('-', $year_vals);
		$req_year = $sel_years[0];
		
		$startDate = '2005';
		$endDate = date('Y');
		$years = range($startDate,$endDate);
		$current_year = date("Y");
		foreach($years as $year){
			$selected = (($year == $req_year) ? 'selected' : ($year == '2005') ? 'selected' : '');
			$syear_html .= '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
		}
		return $syear_html;
	}

	
	public function end_month(){
		$emonth_html = '';
		$current_month = date('m', strtotime('-1 month'));
		$tdate = $_REQUEST['tdate'];
		$tmonth = explode('-', $tdate);
		$tmonths = $tmonth[1];
		
		for($month =1;$month<=12;$month++){
			$num_padded = sprintf("%02d", $month);
			if(isset($_REQUEST['tdate'])){
				$sel = (($num_padded == $tmonths) ? 'selected' : '');
			}else{
				$sel = (($num_padded == $current_month) ? 'selected' : '');
			}
			$month_name = date("M", strtotime("$month/12/10"));
			$emonth_html .= '<option value="'.$num_padded.'" '.$sel.'>'.$month_name.'</option>';
		}
		return $emonth_html;
	}
	
	
	public function end_day(){
		$tday = $_REQUEST['tdate'];
		$tday_explode = explode('-', $tday);
		$tday_sel = $tday_explode[2];
		$eday_html = '';
		$current_date = date("d");
		for($days = 1; $days<=31; $days++){
			$days_val = sprintf("%02d", $days);
			if(isset($_REQUEST['tdate'])){
				$sel_date = (($days_val == $tday_sel) ? 'selected' : '');
			}else{
				$sel_date = (($current_date == $days_val) ? 'selected' : '');
			}
			$eday_html .= '<option value="'.$days_val.'" '.$sel_date.'>'.$days_val.'</option>';
		}
		return $eday_html;
	}
	
	public function end_year(){
		$tyear = $_REQUEST['tdate'];
		$tyear_exp = explode('-', $tyear);
		$tyear_sel = $tyear_exp[0];
		$eyear_html = '';
		$startDate = '2005';
		$endDate = date('Y');
		$years = range($startDate,$endDate);
		$current_year = date("Y");
		foreach($years as $year){
			if(isset($_REQUEST['tdate'])){
				$selected = (($year == $tyear_sel) ? 'selected' : '');
			}else{
				$selected = (($current_year == $year) ? 'selected' : '');
			}
			echo '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
		}
	}
	
	public function filter_limit(){
		$flimit = $_REQUEST['flimit'];
		$number_html = '';
		$page_number = array('25', '50', '100', '200', '500', 'All');
		foreach($page_number as $numbers){
			$selectd_page = (($numbers == $flimit) ? 'selected' : '');
			$number_html .= '<option value="'.$numbers.'" '.$selectd_page.'>'.$numbers.'</option>';
		}
		return $number_html;
	}
	
	public function filter_type(){
		
		$ftype = $_REQUEST['ftype'];
		$yearly = (($ftype == 'yearly') ? 'selected' : '');
		$monthly = (($ftype == 'monthly') ? 'selected' : '');
		$days = (($ftype == 'days') ? 'selected' : '');
		
		$fltype = '<option value="yearly" '.$yearly.'>Yearly</option>
		<option value="monthly" '.$monthly.'>Monthly</option>
		<option value="days" '.$days.'>Days</option>';
		
		return $fltype;
	}
	
	public function report_type(){
		$rtype = $_REQUEST['rtype'];
		$ordered_products = (($rtype == 'ordered_products') ? 'selected' : '');
		$product_popularity = (($rtype == 'product_popularity') ? 'selected' : '');
		$total_orders = (($rtype == 'total_orders') ? 'selected' : '');
		$new_customer = (($rtype == 'new_customer') ? 'selected' : '');
		$rtype = '<option value="ordered_products" '.$ordered_products.'>Ordered Products</option>
				  <option value="product_popularity" '.$product_popularity.'>Product popularity</option>
				  <option value="total_orders" '.$total_orders.'>Total Orders</option>
				  <option value="new_customer" '.$new_customer.'>New customer</option>';
		 
		return $rtype;
	}
	
	
	
	
		public function array_columns(array $input, $columnKey, $indexKey = null) {
			$array = array();
			foreach ($input as $value) {
				if ( !array_key_exists($columnKey, $value)) {
					trigger_error("Key \"$columnKey\" does not exist in array");
					return false;
				}
				if (is_null($indexKey)) {
					$array[] = $value->$columnKey;
				}
				else {
					if ( !array_key_exists($indexKey, $value)) {
						trigger_error("Key \"$indexKey\" does not exist in array");
						return false;
					}
					if ( ! is_scalar($value[$indexKey])) {
						trigger_error("Key \"$indexKey\" does not contain scalar value");
						return false;
					}
					$array[$value[$indexKey]] = $value[$columnKey];
				}
			}
			return $array;
		}
	
	
	

	public function get_customer_by_year($filte_type, $start_date, $end_date,  $site_id, $flimit){
		
		$group_type = (($filte_type == 'monthly') ? "MONTH" : (($filte_type == 'yearly') ? "YEAR" : (($filte_type == 'days') ? "days" : '')));
		$chk_limit = (($flimit == 'All') ? '99999999' : $flimit);
		if($group_type == 'days'){
			$sql = "SELECT count(*) as total, reg_date as regdate FROM `op2mro9899_customers_login` 
					WHERE `reg_date` BETWEEN :start_date AND :end_date AND site_id = :site_id GROUP BY reg_date ORDER BY id DESC LIMIT 0, $chk_limit";
		}else{
			 $sql = "SELECT count(*) as total, $group_type(reg_date) as regdate FROM `op2mro9899_customers_login` 
				WHERE `reg_date` between :start_date and :end_date AND site_id = :site_id GROUP BY $group_type(reg_date) ORDER BY id DESC LIMIT 0, $chk_limit";
		}
		
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindparam("start_date", $start_date);
			$stmt->bindparam("end_date", $end_date);
			$stmt->bindparam("site_id", $site_id);
			$stmt->execute();
			//$stmt->debugDumpParams();
			$cust_record = $stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($cust_record);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	
	
	
	public function total_order_by_year($filter_type, $sdate, $edate,  $site_id, $flimit){

			$chk_group_type = (($filter_type == 'monthly') ? "MONTH" : (($filter_type == 'yearly') ? "YEAR" : (($filter_type == 'days') ? "days" : '')));
			$chk_limits = (($flimit == 'All') ? '99999999' : $flimit);
			if($chk_group_type == 'days'){
				$sql = "SELECT count(*) as totalorder, ordered_date as orderdate FROM `op2mro9899_ordered_product` 
						WHERE `ordered_date` between :sdate AND :edate AND site_id = :site_id GROUP BY ordered_date LIMIT 0, $flimit";
			}else{
				$sql = "SELECT count(*) as totalorder, $chk_group_type(ordered_date) as orderdate FROM `op2mro9899_ordered_product` 
						WHERE `ordered_date` BETWEEN :sdate AND :edate AND site_id = :site_id GROUP BY $chk_group_type(ordered_date) LIMIT 0, $flimit";
		   }
		   try{
				$stmts = $this->conn->prepare($sql);
				$stmts->bindparam("sdate", $sdate);
				$stmts->bindparam("edate", $edate);
				$stmts->bindparam("site_id", $site_id);
				$stmts->execute();
				$total_order_records = $stmts->fetchAll(PDO::FETCH_OBJ);
				return json_encode($total_order_records);
		   }catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		   
	}
	
	public function ordered_products($filter_type, $s_date, $e_date,  $site_id, $limit){
		$chk_group_type = (($filter_type == 'monthly') ? "MONTH" : (($filter_type == 'yearly') ? "YEAR" : (($filter_type == 'days') ? "days" : '')));
		$chk_limits = (($limit == 'All') ? '99999999' : $limit);
		if($filter_type == 'days'){
			$sql = "SELECT COUNT(*) AS qty, ordered_date as orddate, product_name, product_code, site_id FROM op2mro9899_ordered_product 
					WHERE ordered_date BETWEEN :s_date AND :e_date AND site_id = :site_id GROUP BY ordered_date DESC LIMIT 0, $chk_limits";
		}else{
		
		$sql = "SELECT $chk_group_type(ordered_date) AS orddate, COUNT(product_qty) AS qty, product_name, product_code, site_id FROM op2mro9899_ordered_product 
				WHERE ordered_date BETWEEN :s_date AND :e_date AND site_id = :site_id GROUP BY product_name ORDER BY orddate DESC LIMIT 0, $chk_limits";
		}
		try{
			$stmts = $this->conn->prepare($sql);
			$stmts->bindparam("s_date", $s_date);
			$stmts->bindparam("e_date", $e_date);
			$stmts->bindparam("site_id", $site_id);
			$stmts->execute();
			$ord_reports = $stmts->fetchAll(PDO::FETCH_OBJ);
			return json_encode($ord_reports);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	public function popular_products($filterType, $startDates, $endDates,  $shopID, $limitRecord){
		$chk_limits = (($limitRecord == 'All') ? '99999999' : $limitRecord);
		if($filterType == 'yearly'){
			$sql = "SELECT YEAR(ordered_date) as orderdate, product_code, product_name, SUM(product_qty) AS totalQuantity FROM op2mro9899_ordered_product 
					WHERE ordered_date BETWEEN :startDates AND :endDates AND site_id = :shopID GROUP BY product_name HAVING SUM(product_qty) ORDER BY id DESC LIMIT 0, $chk_limits";
		}else if($filterType == 'monthly'){
			 $sql = "SELECT MONTH(ordered_date) as orderdate, product_code, product_name, SUM(product_qty) AS totalQuantity FROM op2mro9899_ordered_product 
					WHERE ordered_date BETWEEN :startDates AND :endDates AND site_id = :shopID GROUP BY MONTH(ordered_date) HAVING SUM(product_qty) ORDER BY id DESC LIMIT 0, $chk_limits";
		}else{
			$sql = "SELECT ordered_date as orderdate, product_code, product_name, SUM(product_qty) AS totalQuantity FROM op2mro9899_ordered_product 
					WHERE ordered_date BETWEEN :startDates AND :endDates AND site_id = :shopID GROUP BY ordered_date LIMIT 0, $chk_limits";
		}
		
		
		try{
			$stmts = $this->conn->prepare($sql);
			$stmts->bindparam("startDates", $startDates);
			$stmts->bindparam("endDates", $endDates);
			$stmts->bindparam("shopID", $shopID);
			$stmts->execute();
			$popular_products_reports = $stmts->fetchAll(PDO::FETCH_OBJ);
			return json_encode($popular_products_reports);
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		
	}
	
	
}
?>
