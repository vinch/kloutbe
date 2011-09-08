<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'config.inc.php';
include_once 'db.inc.php';

$query = mysql_query("SELECT twitter_screen_name, kscore, kchange FROM users ORDER BY last_update ASC LIMIT 0, 100"); // only 50 at a time

$users = array(
	'usernames' => array(),
	'scores' => array()
);
$results = array();

while ($user = mysql_fetch_assoc($query)) {
	$users['usernames'][] = $user['twitter_screen_name'];
	
	// Array with additonal information which will be used the calculate the change
	$users['scores'][$user['twitter_screen_name']] = array(
		'score' => $user['kscore'],
		'change' => $user['kchange']
	);
}

$users_chunked = array_chunk($users['usernames'], 5);

foreach ($users_chunked as $chunk) {
	$result = json_decode(file_get_contents($api_endpoint.'?key='.$key.'&users='.urlencode(implode(',', $chunk))), true);
	
	foreach ($result['users'] as $item) {
		$results[$item['twitter_screen_name']] = $item;
	}
	
	// Avoid Klout API limitations - max 10/req second
	sleep(0.5);
}

$now = date('Y-m-d H:i:s');

// Run trough the users to update them
foreach ($users['usernames'] as $username) {
	// Check if there is a result for this user
	if(isset($results[$username])) {
		// Get the user's previous score
		$old_score = $users['scores'][$username]['score'];
		
		// Get the Klout query result
		$result = $results[$username];
		
		// Calculate the change if the score changed
		if(!empty($old_score) && $result['kscore'] != $old_score) {
			// Calculate the different between the old and the new score
			$change = $result['kscore'] - $old_score;
		}
		else {
			// Put the old change back
			$change = $users['scores'][$username]['change'];
		}
		
		// Compose the fields that need to be updated
		$set_sql = sprintf('kscore = \'%f\', kchange=\'%f\', ', $result['kscore'], $change);
	}
	
	// Update the user
	mysql_query("UPDATE users SET $set_sql last_update = '$now' WHERE twitter_screen_name = '$username'");
}