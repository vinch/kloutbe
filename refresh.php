<?php

include_once 'model.php';

$offset = 0;
do {
	// Get a batch of user details
	$users = Model::getUsers($offset);

	foreach ($users as $user) {
		Model::refreshScore($user);

		// Avoid Klout API limitations - max 10/req second
		sleep(0.2);
	}

	// Prepare for next batch
	$offset += count($users);

} while (!empty($users));