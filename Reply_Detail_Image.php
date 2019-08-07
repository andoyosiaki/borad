<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);



  $statment = $db->prepare('SELECT * FROM replay_posts WHERE reply_img=?');
  $statment->execute(array(
    $_REQUEST['page']
  ));
  $image= $statment->fetch();


 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo h($image['reply_img']); ?></title>
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
      <img src="<?php echo IMAGES_DIR.R_PROTO_IMG ?><?php echo h($image['reply_img']); ?>" alt="">
    </div>
  </body>
</html>
