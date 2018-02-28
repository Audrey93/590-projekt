<?php
session_start();
require 'autoload.php';
require 'src/db_conn.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'YxJfXOzBfIjBIDrneoPiRenp9'); 
define('CONSUMER_SECRET', 'NeKSONKupcgSVtaFlYogsUhm9NiPSmMwOYaZXTlu7kAH2irVz1'); 
define('OAUTH_CALLBACK', 'http://127.0.0.1/591-projekt/callback.php');

  $access_token = $_SESSION['access_token'];
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  $connection->setTimeouts(10, 15);
  $user = $connection->get("account/verify_credentials");
  $num_new_folower = $user->followers_count;

function addFolowers($connection,$conn)
{
  $new_folower = $connection->get('followers/list');

  foreach ($new_folower->users as $new) 
  {
    $id = $new->id;
    $name = $new->name;
    $sql ="INSERT INTO user (ID, Name, Folowing) VALUES ('$id','$name',1)";
    $conn->exec($sql);
  }
}

function addNewFolower($connection,$conn)
{
  $new_folower = $connection->get('followers/list');

  foreach ($new_folower->users as $new)
  {
    $id = $new->id;
    $name = $new->name;
    $sql ="SELECT * FROM user WHERE ID = '$id'";
    $result = $conn->prepare($sql);
    $result->execute();
    $find = $result->rowCount();
    if($find)
      $sql_1 = "UPDATE user SET Folowing = 1 WHERE ID = ".$id;
    else 
      $sql_1 ="INSERT INTO user (ID, Name, Folowing) VALUES ('$id','$name',1)";
      $conn->exec($sql_1);
  }
}

function removeFolower($connection,$conn,$num_new_folower)
{
  $sql ="UPDATE user SET Folowing = 0 ";
  $conn->exec($sql);
  $ids = $connection->get('followers/ids');
  $ids_arrays = array_chunk($ids->ids, 100);
  for($x=0; $x<$num_new_folower;$x++)
  {
    $sql_1 ="UPDATE user SET Folowing = 1 WHERE ID = ".$ids_arrays[0][$x]."";
    $conn->exec($sql_1);
  }
}

function printFolowers($connection,$conn)
{
  
  $sql = "SELECT Name FROM user WHERE  Folowing=1";
  $folower = $conn->prepare($sql);
  $folower->execute();
  $result = $folower->fetchAll();
  if($result)
  {
    echo '<div class="Iam" align="center"><p>'.'Your follower:'.'</p>'; 
    echo '<b><div class="innerIam">'; 
    foreach($result as $v) 
    { 
      echo $v['Name'].'<br>';
    }
    echo '</div></b></div>'; 
  }
}

function printUnfolowers($connection,$conn)
{
  
  $sql = "SELECT Name FROM user WHERE  Folowing=0";
  $folower = $conn->prepare($sql);
  $folower->execute();
  $result = $folower->fetchAll();
  if($result)
  {
    echo '<div class="Iam"><p>'.'Your unfollower:'.'</p>'; 
    echo '<b><div class="innerIam">'; 
    foreach($result as $v) 
    { 
      echo $v['Name'].'<br>';
    }
    echo '</div></b></div>'; 
  } 
}



$sql_1 = "SELECT COUNT(ID) FROM user";
  $result_1 = $conn->prepare($sql_1);
  $result_1->execute();
  $empty = $result_1->fetchColumn();

  $sql = "SELECT COUNT(ID) FROM user WHERE Folowing=1";
  $result = $conn->prepare($sql);
  $result->execute();
  $num_old_folower = $result->fetchColumn();
  if(!$empty)
    addFolowers($connection,$conn);
  elseif($num_new_folower>$num_old_folower)
    addNewFolower($connection,$conn);
  elseif($num_new_folower<$num_old_folower)
    removeFolower($connection,$conn,$num_new_folower);

 printFolowers($connection,$conn);
 printUnfolowers($connection,$conn);

?>