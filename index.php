<?php

include_once 'db.inc.php';

$query = mysql_query("SELECT * FROM users ORDER BY kscore DESC, twitter_screen_name");

$users = array();

$i = 0;
$prev_score = 0;
$prev_pos = 0;

while ($user = mysql_fetch_assoc($query)) {
	if ($prev_score == $user['kscore']) {
		$user['pos'] = $prev_pos;
	}
	else {
		$user['pos'] = $i+1;
		$prev_score = $user['kscore'];
		$prev_pos = $user['pos'];
	}
	$users[] = $user;
	$i++;
}

?>
<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Belgians on Klout</title>
	<link rel="stylesheet" type="text/css" media="screen" href="_css/main.css" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 900px)" href="_css/900.css" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 650px)" href="_css/650.css" />
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

<aside>

	<div id="logo"><img src="_img/klout_be.png" alt="Belgians on Klout"></div>
	
	<div>
		<p>Ranking of Belgian influencers on <a href="http://www.klout.com" target="_blank">Klout</a>. Inspired by <a href="http://datanews.rnews.be/fr/ict/actualite/blog/qui-devez-vous-suivre-dans-la-twittosphere-belge/article-1195010830793.htm" target="_blank">Data News</a> and based on <a href="https://twitter.com/Marievh/belgessurtwitter" target="_blank">this Twitter list</a>. Updated very frequently.</p>
		<p class="note">Made with &hearts; by <a href="http://twitter.com/vinch01" target="_blank">Vincent Battaglia</a> during Wimbledon 2011 men's final. This experiment is <a href="http://github.com/vinch/kloutbe" target="_blank">forkable on GitHub</a>.</p>
	</div>

</aside>

<table cellspacing="0" cellpadding="0">
	<?php foreach ($users as $key => $user) : ?>
		<tr<?php if ($key%2 == 0) echo ' class="even"' ?>>
			<td class="pos"><strong><?php echo $user['pos'] ?></strong></td>
			<td class="name"><a href="http://twitter.com/<?php echo $user['twitter_screen_name'] ?>" target="_blank"><?php echo $user['twitter_screen_name'] ?></a></td>
			<td class="score"><strong><?php echo number_format($user['kscore'], 2) ?></strong></td>
			<td class="klout"><a href="http://klout.com/<?php echo $user['twitter_screen_name'] ?>" target="_blank"><img src="_img/icon.png" /></a></td>
		</tr>
	<?php endforeach; ?>
</table>

</body>
</html>