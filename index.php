<!doctype html>
<html>
<head>
	<title>Unfolow</title>
	 <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
session_start();
require 'src/db_conn.php';
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'YxJfXOzBfIjBIDrneoPiRenp9'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'NeKSONKupcgSVtaFlYogsUhm9NiPSmMwOYaZXTlu7kAH2irVz1'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'http://127.0.0.1/591-projekt/callback.php'); // your app callback URL
if (!isset($_SESSION['access_token'])) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	echo '<a href="'.$url.'" class="button">Sign in with twitter</a>';
} else {
	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$user = $connection->get("account/verify_credentials");
	//var_dump( $user);
	echo "Dobrodosli ". $user->screen_name. "<br>";
	$followers_count = $user->followers_count;
	$screen_name = $user->screen_name;
	$result = $connection->get("https://api.twitter.com/1.1/followers/list.json?screen_name=$screen_name&count=100");
	$ids = $connection->get('followers/ids');
	$newFollowers = array();
	$ids_arrays = array_chunk($ids->ids, 100);

	foreach($ids_arrays as $implode) {
  $results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
  
  foreach($results as $profile) {
  	
  $sql = "INSERT IGNORE INTO user (ID, Name, Folowing) VALUES ('$profile->id','$profile->screen_name',1)";
  $conn->exec($sql);
  }
}
}
?>
</body>
