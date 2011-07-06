<?php

session_start();

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'config.inc.php';
include_once 'db.inc.php';

$status = 'error';

if (isset($_POST['twitter_screen_name']) && isset($_POST['token']) && !empty($_POST['twitter_screen_name']) && !empty($_POST['token'])) {
	
	$twitter_screen_name = $_POST['twitter_screen_name'];
	$token = $_POST['token'];
	
	if (preg_match('/^[A-Za-z0-9_]+$/', $twitter_screen_name)) {
		
		if ($token == $_SESSION['token']) {

			$ch = curl_init($api_endpoint.'?key='.$key.'&users='.$twitter_screen_name);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			$info = curl_getinfo($ch);

			if ($info['http_code'] != '404') {

				$query1 = mysql_query("SELECT * FROM users WHERE twitter_screen_name = '$twitter_screen_name'");

				if (mysql_num_rows($query1) == 0) {
					$data_json = json_decode($data, true);
					$kscore = $data_json['users'][0]['kscore'];

					$now = date('Y-m-d H:i:s');

					$query2 = mysql_query("INSERT INTO users VALUES('', '$twitter_screen_name', '$kscore', '$now')");

					if ($query2) {
						$status = 'ok';
						$message = $twitter_screen_name.' has been successfully added to the ranking with a Klout score of '.$kscore.'!';
					}
					else {
						$message = 'Error while accessing the database!';
					}
				}
				else {
					$message = 'This Twitter screen name is already ranked!';
				}
			}
			else {
				$message = 'This Twitter screen name doesn\'t exist!';
			}
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