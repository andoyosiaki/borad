<?php
session_start();
require_once __DIR__."/core/dbconect.php";

if(isset($_SESSION['join'])){
  $statement = $db->prepare('INSERT INTO userinfo SET name=?,password=?,created=NOW(),icon=?');
  $statement->execute(array(
    $_SESSION['join']['name'],
    sha1($_SESSION['join']['password']),
    $_SESSION['join']['icon']
  ));
  header('Location:login.php');exit();
}else {
  header('Location:index.php');exit();
}
