<?php
session_start();

require 'autoload.php';
require 'src/db_conn.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'YxJfXOzBfIjBIDrneoPiRenp9'); 
define('CONSUMER_SECRET', 'NeKSONKupcgSVtaFlYogsUhm9NiPSmMwOYaZXTlu7kAH2irVz1'); 
define('OAUTH_CALLBACK', 'http://127.0.0.1/591-projekt/callback.php');


if(isset($_SESSION['access_token']))
{
  $access_token = $_SESSION['access_token'];
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  $connection->setTimeouts(10, 15);
  $user = $connection->get("account/verify_credentials");
  

  $screen_name = $user->screen_name; 
  $message = '';

 
}
else header('Location: index.php');
 
if(isset($_POST['search'])) 
{
    
  $username = $_POST['username'];
    
  $sql = "SELECT * FROM user WHERE Name='$username' AND Folowing=1";
  $find = $conn->prepare($sql);
  $find->execute();
  $result = $find->fetchAll();
  if($result)
    $message = $username.' is following you!';
  else
    $message = $username.' is not following you!' ;

}

?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $user->screen_name; ?></title>
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
        font-size:12px;
        padding: 10px 10px 10px 10px;
        border: solid #8c1f7c 3px;
        text-decoration: none;
        background-color:#2d2d2d;: 
        }
      .button:hover {
        text-decoration: none;
      }

  </style>
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
 <script type="text/javascript"> var auto_refresh = setInterval( function() { $('#loadtweets').load('refresh_db.php').fadeIn("slow"); }, 10000); 
 </script> 
</head>
<body>
  <div align="center">
    <div class="Iam">
      <?php echo '<div "><img  style="border-radius: 50%" src="'.$user->profile_image_url.'"/>'.'<br>'. $screen_name. '<br></div>';?>
    </div>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div style="padding: 12px 20px;">
        <p style="padding: 0px 20px;font: normal 40px/50px Montserrat ,sans-serif;color: #999;margin-right: 10px; " >
          Search your follower:
        </p>
        <input style="padding: 10px 20px;margin-right: 10px;" type="text" name="username" />
        <input  name="search" class="button" type="submit" value="SEARCH">
        <p style="padding: 0px 20px;font: normal 20px/30px Montserrat ,sans-serif;color: #999;margin-right: 10px; " >
          <?php echo $message;?>
        </p>
        <div id="loadtweets" align="center"></div>
      </div>
    </form>
  </div>
</body>
</html>