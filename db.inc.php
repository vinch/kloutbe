<?php

$DB_HOST = "your_db_host";  
$DB_NAME = "your_db_name";
$DB_USER = "your_db_user";  
$DB_PASS = "your_db_pass";

$db = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DB_NAME, $db);