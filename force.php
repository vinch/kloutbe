<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'config.inc.php';
include_once 'db.inc.php';

if (isset($_GET['u']) && !empty($_GET['u'])) {
	$twitter_screen_name = $_GET['u'];
	
	$query = mysql_query("SELECT * FROM users WHERE twitter_screen_name = '$twitter_screen_name'");
	
	if (mysql_num_rows($query)) {
		$user = mysql_fetch_assoc($query);
		
		$ch = curl_init($api_endpoint.'/identity.json/twitter?key='.$key.'&screenName='.$twitter_screen_name);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		$score = -1;
		$change = 0;

		if ($info['http_code'] != '404') {

			$data_json = json_decode($data, true);
			$result = json_decode(file_get_contents($api_endpoint.'/user.json/'.$data_json['id'].'/score?key=' . $key), true);

			if (isset($result['score'])) {
				$score = $result['score'];
				$change = $score - $user['kscore'];
			}

		}
		
		$now = date('Y-m-d H:i:s');
		$set_sql = sprintf('kscore = \'%f\', kchange=\'%f\', ', $score, $change);
		
		mysql_query("UPDATE users SET $set_sql last_update = '$now' WHERE twitter_screen_name = '$twitter_screen_name'");

		header('Location: .?u='.$twitter_screen_name.'&s='.$score);
	}
	else {
		header('Location: .');
	}
}
else {
	header('Location: .');
}