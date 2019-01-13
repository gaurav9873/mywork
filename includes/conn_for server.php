<?php 

session_start();


define("HOST",'localhost');
define("USER",'portfolio');
define("PASSWORD",'port123#');
define("DATABASE",'caroye_new');

$conn = mysql_connect(HOST ,USER ,PASSWORD);
mysql_select_db(DATABASE,$conn);

define("SITEPATH","http://localhost/caroye/");
?>