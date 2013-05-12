<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'config.inc.php';
include_once 'db.inc.php';

$query = mysql_query("SELECT twitter_screen_name, kscore, kchange FROM users ORDER BY last_update ASC LIMIT 0, 100"); // only 100 at a time

$usernames = array();
$scores = array();
$results = array();

while ($user = mysql_fetch_assoc($query)) {
	$usernames[] = $user['twitter_screen_name'];
	
	// Array with additonal information which will be used the calculate the change
	$scores[$user['twitter_screen_name']] = array(
		'score' => $user['kscore'],
		'change' => $user['kchange']
	);
}

$now = date('Y-m-d H:i:s');

foreach ($usernames as $username) {
	$result = json_decode(file_get_contents($api_endpoint.'/identity.json/twitter?key='.$key.'&screenName='.$username), true);

    // Check if there is a result for this user
    if(isset($result['id'])) {
        // Get the user Klout id
        $kid = $result['id'];

        $result = json_decode(file_get_contents($api_endpoint.'/user.json/'.$kid.'/score?key=' . $key), true);

        // Get the Klout query result
        $score = $result['score'];

        // Avoid Klout API limitations - max 10/req second
        sleep(0.5);

		// Get the user's previous score
		$old_score = $scores[$username]['score'];
		
		// Calculate the change if the score changed
		if(!empty($old_score) && $score != $old_score) {
			// Calculate the different between the old and the new score
			$change = $score - $old_score;
		}
		else {
			// Put the old change back
			$change = $scores[$username]['change'];
		}
	}
	else {
		$score = -1;
		$change = 0;
	}
	
	// Compose the fields that need to be updated
	$set_sql = sprintf('kscore = \'%f\', kchange=\'%f\', ', $score, $change);
	
	// Update the user
	mysql_query("UPDATE users SET $set_sql last_update = '$now' WHERE twitter_screen_name = '$username'");
}