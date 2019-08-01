<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";

ini_set('display_errors',1);

  //メインページからの削除要請
if(isset($_REQUEST['page'])){
  $page = $_REQUEST['page'];
  if($page){
    if( is_numeric($page) === true && $_SESSION['id']){
      $statment = $db->prepare('SELECT * FROM tweets WHERE author_id=?');
      $statment->execute(array($_SESSION['id']));
      $user = $statment->fetch();
    }else {
      header('Location:index.php');exit();
    }

    if($_SESSION['id']===$user['author_id']){
      if($page === $user['tweets_id'] && $user['maxpost'] > 0){
      $delete = $db->prepare('DELETE tweets,replay_posts FROM tweets,replay_posts WHERE tweets.tweets_id=? and replay_posts.reply_id=?');
      $delete->execute(array(
        $page,
        $page
      ));
      header('Location:index.php');exit();
        // 返信が付いていない投稿を削除する場合の処理
      }else {
          $delete = $db->prepare('DELETE FROM tweets WHERE tweets_id=?');
          $delete->execute(array($page));
          header('Location:index.php');exit();
      }
    }else {
      header('Location:index.php');exit();
    }
  }
}

  //返信ページからの削除要請
if(isset($_REQUEST['Reply'])){
  $reply = $_REQUEST['Reply'];
  if($reply){
    if( is_numeric($reply) === true && $_SESSION['id']){
      $statment = $db->prepare('SELECT * FROM replay_posts WHERE reply_co_id=?');
      $statment->execute(array($reply));
      $user = $statment->fetch();
    }else {
      header('Location:index.php');exit();
    }

    if($_SESSION['id']===$user['reply_author_id']){
      if($reply === $user['reply_co_id']){
        $delete = $db->prepare('DELETE  FROM replay_posts WHERE reply_co_id=?');
        $delete->execute(array(
          $reply
        ));
          //最大返信数も変更させるための処理
        $max = $db->prepare('SELECT COUNT(*) as cnt FROM replay_posts WHERE reply_id=?');
        $max->execute(array(
          $user['reply_id']
        ));
        $maxpost = $max->fetch();

        if($maxpost['cnt'] === 0){
          $maxpost = null;
        }
        if($maxpost['cnt'] || $maxpost){
          $max = $db->prepare('UPDATE tweets SET maxpost=? WHERE tweets_id=?');
          $max->execute(array(
            $maxpost['cnt'],
            $user['reply_id']
          ));
        }
        header('Location:Reply_Posts.php?page='.$user['reply_id']);exit();
      }
    }else {
      header('Location:index.php');exit();
    }
  }
}

  //退会申請
if(isset($_REQUEST['acount'])){
  $acount = $_REQUEST['acount'];
  if($acount){
    if(is_numeric($acount) === true && $_SESSION['id']){
      $statment = $db->prepare('SELECT * FROM userinfo,tweets,replay_posts WHERE userinfo.user_id=? and tweets.author_id=? and replay_posts.reply_author_id=?');
      $statment->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
      $user = $statment->fetch();
    }
    
    if($user['user_id']===NUll && $user['author_id'] === null && $user['reply_author_id'] === null){
      $delete = $db->prepare('DELETE  FROM userinfo WHERE user_id=?');
      $delete->execute(array($_SESSION['id']));
      session_destroy();
      // header('Location:index.php');exit();
      echo "userinfoだけ消した";
    }elseif(isset($user['user_id'],$user['author_id'],$user['reply_author_id'])){
      $delete = $db->prepare('DELETE userinfo,tweets,replay_posts FROM userinfo,tweets,replay_posts WHERE userinfo.user_id=? and tweets.author_id=? and replay_posts.reply_author_id=?');
      $delete->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
      session_destroy();
      echo "全消し";
    }
  }
}
