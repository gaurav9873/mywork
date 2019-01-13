<?php 

session_start();
error_reporting(0);


define("HOST",'localhost');
define("USER",'root');
define("PASSWORD",'');
define("DATABASE",'caroye_new');

$conn = mysql_connect(HOST ,USER ,PASSWORD)or die("not connect from user");
mysql_select_db(DATABASE,$conn) or die("not connect from database");

define("SITEPATH","http://localhost/caroye/");

?>