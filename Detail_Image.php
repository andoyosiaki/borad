<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);


if($_REQUEST['item'] && $_SESSION['id']){
  $statment = $db->prepare('SELECT * FROM tweets WHERE tweets_id=?');
  $statment->execute(array(
    $_REQUEST['item']
  ));
  $image= $statment->fetch();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo h($image['tweet_img']); ?></title>
  </head>
  <body>
    <style media="screen">
        .img_box{
          max-width: 1000px;
          height: auto;
          margin: 0 auto;
        }
        img{
          width: 100%;
          height: auto;
        }
    </style>
    <div class="img_box">
      <img src="images/Proto_img/<?php echo h($image['tweet_img']); ?>" alt="">
    </div>
  </body>
</html>
