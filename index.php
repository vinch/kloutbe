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
	<title>Belgians on Klout</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="Ranking of Belgian influencers on Klout">
	<link rel="stylesheet" type="text/css" media="screen" href="_css/main.css" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 900px)" href="_css/900.css" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 650px)" href="_css/650.css" />
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-192063-4']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

<aside>

	<div id="logo"><img src="_img/klout_be.png" alt="Belgians on Klout"></div>
	
	<div id="description">
		<p>Ranking of Belgian influencers on <a href="http://www.klout.com" target="_blank">Klout</a>. Inspired by <a href="http://datanews.rnews.be/fr/ict/actualite/blog/qui-devez-vous-suivre-dans-la-twittosphere-belge/article-1195010830793.htm" target="_blank">Data News</a> and based on <a href="https://twitter.com/Marievh/belgessurtwitter" target="_blank">this Twitter list</a>. Updated very frequently.</p>
		<p class="note">Made with &hearts; by <a href="http://twitter.com/vinch01" target="_blank">Vincent Battaglia</a> during Wimbledon 2011 men's final. This experiment is <a href="http://github.com/vinch/kloutbe" target="_blank">forkable on GitHub</a>.</p>
	</div>
	
	<div id="share">
		<div class="share">
			<iframe src="http://www.facebook.com/plugins/like.php?app_id=158701270868874&amp;href=http%3A%2F%2Fv1n.ch%2Fklout.be%2F&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=60" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:60px;" allowTransparency="true"></iframe>
		</div>
		<div class="share">
			<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://v1n.ch/klout.be/" data-count="vertical" data-via="vinch01">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		</div>
		<div class="share">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<g:plusone size="tall" href="http://v1n.ch/klout.be/"></g:plusone>
		</div>
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