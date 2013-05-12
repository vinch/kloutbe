<?php

// contains $api_endpoint & $key
require_once 'config.inc.php';

/**
 * Read data from Klout
 *
 * @see http://klout.com/s/developers/v2#intro
 */
class Klout {
	/**
	 * Fetch the Klout id for the given Twitter name. Example:
	 * {
	 *     "id":"1254747",
	 *     "network":"ks"
	 * }
	 *
	 * @param string $service Valid services: twitter, klout, gp (Google+)
	 * @param string $account Username on said service
	 * @return string|false Klout id associated to given account, or false on failure
	 */
	public static function getId($service, $account) {
		global $api_endpoint, $key;

		$ch = curl_init($api_endpoint.'/identity.json/'.$service.'?key='.$key.'&screenName='.$account);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		if ($info['http_code'] != '404') {

			$data_json = json_decode($data, true);
			return $data_json['id'];
		}

		return false;
	}

	/**
	 * Fetch the Klout score for a Klout id. Example:
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
	 * @param string $id The Klout id of the user to fetch the score for
	 * @return float|false The Klout score, or false on failure
	 */
	public static function getScore($id) {
		global $api_endpoint, $key;

		$ch = curl_init($api_endpoint.'/user.json/'.$id.'/score?key='.$key);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		if ($info['http_code'] != '404') {

			$data_json = json_decode($data, true);
			return $data_json['score'];

		}

		return false;
	}
}