<!doctype html>
<html>
<head>
	<title>Unfolow</title>
	<link rel="stylesheet" type="text/css" href="css/style_1.css">
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<style>
		.button {
  			-webkit-border-radius: 1;
  			-moz-border-radius: 1;
  			border-radius: 1px;
  			font-family: Arial;
  			text-align: center;
  			display: inline-block;
  			color: #8a0f8a;
  			font-size: 20px;
  			padding: 20px;
  			border: solid #8c1f7c 3px;
  			text-decoration: none;
  			margin-top: 150px;
				}
			.button:hover {
  			text-decoration: none;
			}
	</style>
</head>
<body style="margin-top:100px ">
	<div class="Iam" align="center">
		<p>Aplication</p>
		<b>
  		<div class="innerIam">
   			Who followed Me?<br /> 
   			Who Unfollowed Me?<br />
   			Search your folowers<br />
   			It's easy!<br />
   			Press button
    	</div>
		</b>
	</div>

<?php

session_start();
require 'src/db_conn.php';
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'YxJfXOzBfIjBIDrneoPiRenp9'); 
define('CONSUMER_SECRET', 'NeKSONKupcgSVtaFlYogsUhm9NiPSmMwOYaZXTlu7kAH2irVz1'); 
define('OAUTH_CALLBACK', 'http://127.0.0.1/591-projekt/callback.php');

if (!isset($_SESSION['access_token'])) 
{
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$connection->setTimeouts(10, 15);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	
	echo'<div text-align: center">
			<a href="'.$url.'" class="button">Sign up for Twitter</a>
		</div>';
}
else header('Location: text.php');
?>
</body>
