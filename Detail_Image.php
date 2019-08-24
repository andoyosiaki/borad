<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

if(isset($_REQUEST['item'])){
  $image = GetTweetId($_REQUEST['item']);
}elseif(isset($_REQUEST['page'])){
  $sql = 'SELECT * FROM replay_posts WHERE reply_img=?';
  $image= Select($sql,$_REQUEST['page']);
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php if(isset($image['tweet_img'])){ echo h($image['tweet_img']);} elseif (isset($image['reply_img'])){echo h($image['reply_img']);} ?></title>
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
    <div class="wrap">
      <div class="img_box">
        <?php if(isset($image['tweet_img'])): ?>
        <img src="<?php echo IMAGES_DIR.PROTO_IMG ?><?php echo h($image['tweet_img']); ?>" alt="">
        <?php elseif(isset($image['reply_img'])): ?>
        <img src="<?php echo IMAGES_DIR.R_PROTO_IMG ?><?php echo h($image['reply_img']); ?>" alt="">
        <?php endif; ?>
      </div>
    </div>
  </body>
</html>
