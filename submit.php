<?php

session_start();

include_once 'model.php';

$status = 'error';

if (isset($_POST['twitter_screen_name']) && isset($_POST['token']) && !empty($_POST['twitter_screen_name']) && !empty($_POST['token'])) {
	
	$twitter_screen_name = $_POST['twitter_screen_name'];
	$token = $_POST['token'];
	
	if (preg_match('/^[A-Za-z0-9_]+$/', $twitter_screen_name)) {
		
		if ($token == $_SESSION['token']) {

			// fetch this user's Klout data & insert into db
			list($status, $message) = Model::insert($twitter_screen_name);
		}
		else {
			$message = 'Invalid token!';
		}
	}
	else {
		$message = 'This Twitter screen name is invalid!';
	}
}
else {
	$message = 'Please provide a Twitter screen name!';
}

echo json_encode(array('status' => $status, 'message' => $message));
