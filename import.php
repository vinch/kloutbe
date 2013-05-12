<?php

include_once 'model.php';

$users = explode("\n", file_get_contents('list.txt'));

// fetch these users' Klout data & insert into db
foreach ($users as $user) {
	insert($user);

	// Avoid Klout API limitations - max 10/req second
	sleep(0.5);
}