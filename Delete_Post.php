<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";

ini_set('display_errors',1);

  //メインページからの削除要請
if(isset($_REQUEST['page'])){
  $page = $_REQUEST['page'];
  if($page){
    if(isset($_SESSION['id'])){
      $statment = $db->prepare('SELECT * FROM tweets WHERE author_id=?');
      $statment->execute(array($_SESSION['id']));
      $user = $statment->fetch();
    }else {
      header('Location:index.php');exit();
    }
      //画像ファイルの削除
    if(isset($user['tweet_img']) && $user['tweet_img'] !==0){
      $file = 'images/Proto_img/'.$user['tweet_img'];
      $files = 'images/Compre_img/'.$user['tweet_img'];
      unlink($file);
      unlink($files);
    }

    if($_SESSION['id'] === $user['author_id']){
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
    if(isset($_SESSION['id'])){
      $statment = $db->prepare('SELECT * FROM replay_posts WHERE reply_co_id=?');
      $statment->execute(array($reply));
      $user = $statment->fetch();
    }else {
      header('Location:index.php');exit();
    }

    //画像ファイルの削除
  if(isset($user['reply_img']) && $user['reply_img'] !==null){
    $file = 'images/Reply_Proto_img/'.$user['reply_img'];
    $files = 'images/Reply_Compre_img/'.$user['reply_img'];
    unlink($file);
    unlink($files);
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

  if(isset($_SESSION['id'])){
    $statment = $db->prepare('SELECT * FROM userinfo WHERE user_id=?');
    $statment->execute(array($acount));
    $user = $statment->fetch();
    var_dump($user['name']);
      // アイコンの削除処理
    if(isset($user['icon']) && $user['icon'] !=='0.png'){
      if($user['icon'] !=='0.png'){
        $file = 'images/Profile_Proto_img/'.$user['icon'];
      }
      $files = 'images/Profile_Compre_img/'.$user['icon'];
      if(isset($files) && isset($file)){
        unlink($file);
        unlink($files);
      }
    }
    var_dump($user['name']);
    echo  $file1 = 'images/Compre_img/'.$user['name'];
    echo   $file2 = 'images/Proto_img/'.$user['name'];
    echo   $file3 = 'images/Reply_Compre_img/'.$user['name'];
    echo   $file4 = 'images/Reply_Proto_img/'.$user['name'];
      foreach(glob('images/Compre_img/'.$user['name'].'*') as $file1){
      unlink($file1);
      }
      foreach(glob('images/Proto_img/'.$user['name'].'*') as $file2){
      unlink($file2);

      }
      foreach(glob('images/Reply_Compre_img/'.$user['name'].'*') as $file3){
      unlink($file3);
      }
      foreach(glob('images/Reply_Proto_img/'.$user['name'].'*') as $file4){
      unlink($file4);
      }


    if($acount){
      $delete = $db->prepare('DELETE  FROM userinfo where user_id=?'); //ユーザー情報を削除
      $delete->execute(array($acount));

      $deletea = $db->prepare('DELETE  FROM tweets where author_id=?'); //ツイートを投稿していたら全て削除。ツイートしてなかったら何も処理しない。
        if(!empty($deletea)){
          $deletea->execute(array($acount));
        }

      $deleteb = $db->prepare('DELETE  FROM replay_posts where reply_author_id=?'); //返信ツイートをしていたら削除
      $deleteb->execute(array($acount));
    }
    session_destroy();
    header('Location:index.php');exit();
  }
}
