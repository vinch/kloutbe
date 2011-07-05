<?php

session_start();

if (isset($_POST['twitter_screen_name']) && isset($_POST['token']) && !empty($_POST['twitter_screen_name']) && !empty($_POST['token'])) {
	$twitter_screen_name = $_POST['twitter_screen_name'];
	$token = $_POST['token'];
	
	if ($token == $_SESSION['token']) {
		
	}
	else {
		
	}
}
else {
	
}