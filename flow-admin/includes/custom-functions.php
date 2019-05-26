<?php

class CustomFunctions{
	
	public function getSaltedHash($password) {
		$salt= uniqid(mt_rand(), true);
		$options=['salt'=>$salt, 'cost'=>10];
		$mypassword=$password;
		$cryptpwd=crypt($mypassword,'$2y$12$'.$salt.'$');
		return $cryptpwd;
	}


	public function safe_encode($string) {
		return strtr(base64_encode($string), '+/=', '*^#-_-');
	}

	public function safe_decode($string) {
		return base64_decode(strtr($string, '*^#-_-', '+/='));
	}

	public function EncryptClientId($id){
	return substr(md5($id) , 0, 8) . dechex($id);
	}

	public function DecryptClientId($id){
		$md5_8 = substr($id, 0, 8);
		$real_id = hexdec(substr($id, 8));
		return ($md5_8 == substr(md5($real_id) , 0, 8)) ? $real_id : 0;
	}

	public function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

    public function getCurl($url){
        $jwthelper = new JWT();
        $key = "pjuJ-Xp;/t0y<.X:#06.]7&M[YWLly.sOa0h|t!{yRnG,B!RF`r}CfNQ{)#w*f";
        $token = array("user_id"=> '8302e8318c2ed9cc976c54f45dfcebd3');
        $jwthelper = JWT::encode($token, $key);
        $curl = curl_init();
        $headers = array(
            'Authorization: '.$jwthelper,
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        //print_r($result);
        curl_close($curl);
        return json_decode($result);
    }
	
	public function genrate_apikey(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
			.substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12)
			.chr(125);// "}"
			return $uuid;
		}
	}
	
	public function generate_key($len = 16){
		$data = openssl_random_pseudo_bytes($len);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
		}
	
	public function randomPassword(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	

	public function xss_clean($data)
	{
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);
			return $data;
	}

	public function get_client_ip()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}


	public function make_thumb($src, $dest, $desired_width,$desired_h=null) {
		$extension = @strtolower(end(explode('.',$src)));
		if (in_array($extension, array('jpg','jpeg', 'png', 'gif'))) {
			switch ($extension) {
				case 'jpg':
					$source_image = imagecreatefromjpeg($src);
				break;
				case 'jpeg':
					$source_image = imagecreatefromjpeg($src);
				break;
				case 'png':
					$source_image = imagecreatefrompng($src);                  
				break;
				case 'gif':
					$source_image = imagecreatefromgif($src);
				break;
				default:
					$source_image = imagecreatefromjpeg($src);
			}
		}

		$width = imagesx($source_image);
		$height = imagesy($source_image);
		/* find the "desired height" of this thumbnail, relative to the desired width  */


		$desired_height = ($desired_h==null)? floor($height * ($desired_width / $width)):$desired_h;


		//$desired_height = $desired_h;
		//$desired_height = floor($height * ($desired_width / $width));
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		/* create the physical thumbnail image to its destination */
		switch ($extension) {
			case 'jpg':
				imagejpeg($virtual_image, $dest, 0777, true);
			break;
			case 'jpeg':
				imagejpeg($virtual_image, $dest, 0777, true);
			break;
			case 'png':
				imagepng($virtual_image, $dest, 0777, true);              
			break;
			case 'gif':
				imagegif($virtual_image, $dest, 0777, true); 
			break;
			default:
				imagejpeg($virtual_image, $dest, 0777, true);
		}
		//~ if($is_mobile){
		//~ $this->image_fix_orientation($src,$dest);
		//~ }
	}


	public function image_fix_orientation($image, $filename){
		$exif = exif_read_data($image);
		if (!empty($exif['Orientation'])) {
			$image = imagecreatefromjpeg($filename);
			switch ($exif['Orientation']) {
				case 3:
					$image = imagerotate($image, 180, 0);
				break;

				case 6:
					$image = imagerotate($image, -90, 0);
				break;

				case 8:
					$image = imagerotate($image, 90, 0);
				break;
			}

			imagejpeg($image, $filename, 90);
		}
	}

	public function home_base_url(){   
		$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
		$tmpURL = dirname(__FILE__);
		$tmpURL = str_replace(chr(92),'/',$tmpURL);
		$tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
		$tmpURL = ltrim($tmpURL,'/');
		$tmpURL = rtrim($tmpURL, '/');
		if (strpos($tmpURL,'/')){
			$tmpURL = explode('/',$tmpURL);
			$tmpURL = $tmpURL[0];
		}
		if ($tmpURL !== $_SERVER['HTTP_HOST'])
			$base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL.'/';
		else
			$base_url .= $tmpURL.'/';
		return $base_url;
	}


	public function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	public function createFolder(){
		try{
			$upload_path = './uploads/products/'.date('Y').'/'.date('m');
			$array = array('full' => array('max_width' => 1920, 'max_height' => 1080),
				'medium' => array('max_width' => 500, 'max_height' => 600),
				'thumbnail' => array('max_width' => 150, 'max_height' => 175),);
			foreach($array as $key => $val){
				$path = $upload_path."/".$key;
				if($key!='full')
					$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$array[$key]['max_height'],'width'=>$array[$key]['max_width']);
				if(!is_dir($path)){
					mkdir($path, 0777, true);
				}
				
			}
			return $destination_path; 
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	
	
	public function generateProductCode($length = 5) {
		    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

	}
	
	
	public function array_equal($a, $b, $strict=false) {
		if (count($a) !== count($b)) {
			return false;
		}
		sort($a);
		sort($b);
		return ($strict && $a === $b) || $a == $b;
	}
	
	
	
	public function convert_number_to_words($number) {

		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'fourty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
			break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
			break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
			break;
			default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= $this->convert_number_to_words($remainder);
			}
			break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}
	
  
}
