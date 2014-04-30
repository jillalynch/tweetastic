<!doctype html>
<html>
<head>
        <meta charset="utf-8">
        <title>Jill Lynch's Tweetastic Friends</title>
        <link href='http://fonts.googleapis.com/css?family=Londrina+Shadow' rel='stylesheet' type='text/css'>
		<style type="text/css">h1, h2 {font-family: 'Londrina Shadow', cursive; clear: both;} a { color: gray; text-decoration: none; } input { float: left; margin-bottom: 2em } </style>
</head>
<body>
        <h1>Tweetastic Twitter</h1>

<?php

if (isset($_GET['word']) && empty($_GET['word']) === false) {

	$searchWord = filter_var($_GET['word'], FILTER_SANITIZE_STRING);

	//Curl over SSL requires a SSL certificate (cert not in xamp) so line 198 was added to TwitterAPIExchange.php
	require_once 'TwitterAPIExchange.php';

	$settings = (parse_ini_file("tokens.ini"));
	
	$twitter = new TwitterAPIExchange($settings);

	//true, json decode as an php array
	$friendsList = json_decode($twitter->setGetfield('?screen_name=jillscript')->buildOauth('https://api.twitter.com/1.1/friends/list.json', 'GET')->performRequest(), true);

	$totalWordCount = 0;

	foreach ($friendsList['users'] as $friend) {

		$tweets = json_decode($twitter->setGetfield('?screen_name=' . $friend['screen_name'] . '&count=20')->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET')->performRequest(), true);

		foreach ($tweets as $tweet) {

			$wordCount = substr_count($tweet['text'], $searchWord);

			$totalWordCount = $totalWordCount + $wordCount;

		}
	}

    echo "<p>Jill's friends tweeted \"$searchWord\" a total of $totalWordCount times recently.</p>";

} else {

	echo "<p>Enter a word to see if Jill's friends are tweeting about it.</p>";
}

?>

        <form action="index.php" method="GET">
            <p><input type="text" name="word" value="<?php if (isset($_GET['word'])) { print htmlspecialchars($_GET['word']); } ?>"</p>
            <p><input type="submit" name="submit" value="Search" /></p>
        </form>
    </body>
    <footer>
        <h2> 
        	<a href = "mailto:jill.lynch5@gmail.com">jill.lynch5@gmail.com </a> 
        	<a href = "http://www.pinterest.com/bibli0babe/">Pinterest </a> 
        	<a href = "https://twitter.com/jillscript">Twitter</a>
        </h2>
    </footer>
</html>
