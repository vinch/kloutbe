<?php

date_default_timezone_set(timezone_name_from_abbr('CET'));

include_once 'klout.php';
include_once 'db.inc.php';

/**
 * DB wrapper
 */
class DB {
	/**
	 * Insert a new user based on his/her Twitter username.
	 *
	 * @param string $twitter_screen_name
	 * @return array
	 */
	public static function insert($twitter_screen_name) {
		$status = 'error';

		$blacklist = explode("\n", file_get_contents('blacklist.txt'));

		if (!in_array($twitter_screen_name, $blacklist)) {

			$klout_id = Klout::getId('twitter', $twitter_screen_name);

			if ($klout_id !== false) {

				$kscore = Klout::getScore($klout_id);

				if ($kscore !== false) {

					$query1 = mysql_query("SELECT * FROM users WHERE twitter_screen_name = '$twitter_screen_name'");

					if (mysql_num_rows($query1) == 0) {

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
}