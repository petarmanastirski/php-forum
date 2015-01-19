<?php

define('host','localhost');
define('username','root');
define('password','');
define('db_name','forum');


mysql_connect(host, username, password)or die("cannot connect to server");
mysql_select_db(db_name)or die("cannot select db"); 


?>