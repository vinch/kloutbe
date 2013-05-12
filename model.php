<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'klout.php';
include_once 'db.inc.php';

/**
 * DB wrapper
 */
class Model {
	/**
	 * Insert a new user based on his/her Twitter username.
	 *
	 * @param string $twitter_screen_name
	 * @return array
	 */
	public static function insert($twitter_screen_name) {
		$status = 'error';

		$blacklist = explode("\n", file_get_contents('blacklist.txt'));

		if (!in_array(strtolower($twitter_screen_name), $blacklist)) {

			$klout_id = Klout::getId('twitter', $twitter_screen_name);

			if ($klout_id !== false) {

				$score = Klout::getScore($klout_id);

				if ($score !== false) {

					$kscore = $score['score'];
					$kchange = $score['scoreDelta']['weekChange'];

					$query1 = mysql_query("SELECT * FROM users WHERE twitter_screen_name = '$twitter_screen_name'");

					if (mysql_num_rows($query1) == 0) {

						$now = date('Y-m-d H:i:s');

						$query2 = mysql_query("INSERT INTO users VALUES('', '$twitter_screen_name', '$klout_id', '$kscore', '$kchange', '$now')");

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

	/**
	 * Retrieve a specific user's details from DB
	 *
	 * @param string $id The Klout id
	 * @return array|false The user's details from DB, or false on failure
	 */
	public static function getUser($id) {
		$query = mysql_query("SELECT * FROM users WHERE kid = '$id'");
		while ($user = mysql_fetch_assoc($query)) {
			return $user;
		}

		return false;
	}

	/**
	 * Retrieve all users' details from DB
	 *
	 * @param int[optional] $offset
	 * @param int[optional] $limit
	 * @return resource
	 */
	public static function getUsers($offset = 0, $limit = 100) {
		$offset = (int) $offset;
		$limit = (int) $limit;

		$users = array();

		$query = mysql_query("SELECT * FROM users ORDER BY last_update ASC LIMIT $offset, $limit");
		while ($user = mysql_fetch_assoc($query)) {
			$users[] = $user;
		}

		return $users;
	}

	/**
	 * Refresh a user's Klout score.
	 *
	 * @param array $user
	 * @return float New score
	 */
	public static function refreshScore($user) {
		// Get fresh Klout score
		$score = Klout::getScore($user['kid']);

		// Make sure we did retrieve a response score
		if($score !== false) {
			$kchange = $score['scoreDelta']['weekChange'];
			$kscore = $score['score'];
		}
		else {
			// No response = invalid account
			$kchange = 0;
			$kscore = -1;
		}

		// Compose the fields that need to be updated
		$set_sql = sprintf('kscore = \'%f\', kchange=\'%f\', ', $kscore, $kchange);

		// Update the user
		$now = date('Y-m-d H:i:s');
		$klout_id = $user['kid'];
		mysql_query("UPDATE users SET $set_sql last_update = '$now' WHERE kid = '$klout_id'");

		return $score;
	}
}
