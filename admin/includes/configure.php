<?php
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD','');
define('DB_DATABASE', 'caroye_new');
define('USE_PCONNECT', 'true');
$con = mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD);
mysql_select_db(DB_DATABASE,$con);
?>