<?php

include_once 'db.inc.php';

$users = explode("\n", file_get_contents('list.txt'));

foreach ($users as $user) {
	mysql_query("INSERT INTO users VALUES('', '$user', '1', '')");
}