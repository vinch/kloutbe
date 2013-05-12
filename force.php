<?php

include_once 'model.php';

if (isset($_GET['u']) && !empty($_GET['u'])) {
	$klout_id = $_GET['u'];

	$user = Model::getUser($klout_id);

	if ($user !== false) {
		$score = Model::refreshScore($user);

		header('Location: .?u='.$user['twitter_screen_name'].'&s='.$score);
	}
	else {
		header('Location: .');
	}
}
else {
	header('Location: .');
}
