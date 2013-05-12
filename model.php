<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'config.inc.php';
include_once 'db.inc.php';

/**
 * Insert a new user based on his/her Twitter username.
 *
 * @param string $twitter_screen_name
 */
function insert($twitter_screen_name) {
	global $api_endpoint, $key;

	$status = 'error';

	$blacklist = explode("\n", file_get_contents('blacklist.txt'));

	if (!in_array($twitter_screen_name, $blacklist)) {

		/**
		 * Fetch the Klout id for the given Twitter name. Example:
		 * {
		 *     "id":"1254747",
		 *     "network":"ks"
		 * }
		 *
		 * @see http://klout.com/s/developers/v2#intro
		 */
		$ch = curl_init($api_endpoint.'/identity.json/twitter?key='.$key.'&screenName='.$twitter_screen_name);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		if ($info['http_code'] != '404') {

			$data_json = json_decode($data, true);
			$klout_id = $data_json['id'];

			/**
			 * Fetch the Klout score for this Klout id. Example:
			 * {
			 *     "score":39.02721402922333,
			 *     "scoreDelta":
			 *     {
			 *         "dayChange":0.0033099139938457256,
			 *         "weekChange":-0.6880237945155727,
			 *         "monthChange":-1.5063959529033113
			 *     },
			 *     "bucket":"30-39"
			 * }
			 *
			 * @see http://klout.com/s/developers/v2#intro
			 */
			$ch = curl_init($api_endpoint.'/user.json/'.$klout_id.'/score?key='.$key);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			$info = curl_getinfo($ch);

			if ($info['http_code'] != '404') {

				$query1 = mysql_query("SELECT * FROM users WHERE twitter_screen_name = '$twitter_screen_name'");

				if (mysql_num_rows($query1) == 0) {
					$data_json = json_decode($data, true);

					$kscore = $data_json['score'];

					$now = date('Y-m-d H:i:s');

					$query2 = mysql_query("INSERT INTO users VALUES('', '$twitter_screen_name', '$klout_id', '$kscore', '', '$now')");

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
				$message = 'Could not get score for this user!';
			}
		}
		else {
			$message = 'This Twitter screen name doesn\'t exist!';
		}
	}
	else {
		$message = 'This Twitter screen name is not allowed!';
	}

	return array($status, $message);
}