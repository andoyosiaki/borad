<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

/*
 ①[投稿の削除]
 ②[返信の削除]
 ③[退会ユーザー情報の削除]
 */

//①[投稿の削除] メインページ(index.php)からの削除要請
if(isset($_REQUEST['from_main']) && isset($_SESSION['id'])){
  $main = $_REQUEST['from_main'];
  //削除目標の投稿情報を取得
  $post = GetTweetId($main);
    //削除目標の投稿に画像も投稿されていたら画像フォルダから画像を削除
    if($post['tweet_img']){
      DeleteFile_2(PROTO_IMG,COMPRE_IMG,$post['tweet_img']);
    }
    //削除目標の投稿に、もし画像付きの返信が投稿されていた場合は一緒に画像ファイルも削除
    if(isset($post['maxpost']) && $post['maxpost'] !==0){
      $sql = 'SELECT * FROM replay_posts WHERE reply_id=?';
      $reply = Select($sql,$post['tweets_id']);
      if(isset($reply['reply_img']) && $reply['reply_img'] !==null){
        $AuthorName = $reply['reply_author_name'];
        $UniqueId = $post['uniq_id'];
        $R_CompreImage = IMAGES_DIR.R_COMPRE_IMG.$AuthorName.$UniqueId;
        $R_ProtoImage = IMAGES_DIR.R_PROTO_IMG.$AuthorName.$UniqueId;
        foreach(glob(IMAGES_DIR.R_COMPRE_IMG.'*'.$UniqueId.'*') as $R_CompreImage){
          unlink($R_CompreImage);
        }
        foreach(glob(IMAGES_DIR.R_PROTO_IMG.'*'.$UniqueId.'*') as $R_ProtoImage){
          unlink($R_ProtoImage);
        }
      }
    }

  //db削除
  if($_SESSION['id'] === $post['author_id']){
    if($main === $post['tweets_id'] && $post['maxpost'] > 0){
      //投稿の削除
      DeleteTweet($main);
      //投稿に付いてる返信たちも削除
      $sql ='DELETE  FROM replay_posts WHERE reply_id=?';
      Deletes($sql,$main);

      header('Location:index.php');exit();
      // 返信が付いていない投稿を削除する場合の処理
    }else {
      //投稿の削除
      DeleteTweet($main);
      header('Location:index.php');exit();
    }
  }else {
    header('Location:index.php');exit();
  }
}

//②[返信の削除] 返信ページからの削除要請
if(isset($_REQUEST['Reply']) && isset($_SESSION['id'])){
  $reply = $_REQUEST['Reply'];
  //削除する投稿の情報を取得
  $user = SelectReply($reply);
  //画像ファイルの削除
  if(isset($user['reply_img']) && $user['reply_img'] !==null){
    DeleteFile_2(R_PROTO_IMG,R_COMPRE_IMG,$user['reply_img']);
  }

  //db削除
  if($_SESSION['id'] === $user['reply_author_id'] && $reply === $user['reply_co_id']){
    //投稿の削除
    DeleteReply($reply);
    //返信元の投稿に付いてる返信数を取得
    $maxpost = GetCount($user['reply_id']);
    //返信元の投稿数の変更処置
    if($maxpost['cnt'] !==1){
      Update($maxpost['cnt'],$user['reply_id']);
    }
    header('Location:Reply_Posts.php?page='.$user['reply_id']);exit();
  }else{
    header('Location:index.php');exit();
  }
}

 //③[退会ユーザー情報の削除]
if(isset($_REQUEST['acount']) && isset($_SESSION['id'])){
  $acount = $_REQUEST['acount'];
  //退会ユーザーの情報を取得
  $user = GetUserId($acount);
  //アイコンの削除処理。0.pngはデフォルトのアイコンなので削除しない
  if(isset($user['icon']) && $user['icon'] !=='0.png'){
    $file = IMAGES_DIR.P_PROTO_IMG.$user['icon'];
  }
  $files = IMAGES_DIR.P_COMPRE_IMG.$user['icon'];
  if(isset($files) && isset($file)){
    unlink($file);
    unlink($files);
  }

  //退会ユーザーが投稿した全ての画像ファイルの削除
  $CompreImage = IMAGES_DIR.COMPRE_IMG.$user['name'];
  $ProtoImage = IMAGES_DIR.PROTO_IMG.$user['name'];
  $R_CompreImage = IMAGES_DIR.R_COMPRE_IMG.$user['name'];
  $R_ProtoImage = IMAGES_DIR.R_PROTO_IMG.$user['name'];
  $files = array($CompreImage,$ProtoImage,$R_CompreImage,$R_ProtoImage);
  foreach ($files as $file) {
    deletefileforeach($file);
  }

  //db削除
  if($acount){
    //投稿に対して返信をしているか確認
    $distinctreply = GetDistinctReply($acount);
    //投稿に対して返信をしているなら返信数も変更させる
    if(isset($distinctreply['reply_id']) && $distinctreply['reply_id'] !==null){
      //退会処理をする前に退会ユーザーのidを使って、どこの投稿に返信しているか確認
      $statement = $db->prepare('SELECT DISTINCT reply_id FROM replay_posts WHERE reply_author_id=?');
      $statement->execute(array($acount));
      //ユーザーデータの削除
      DeleteUserId($acount);
      DeleteReplyPost($acount);
      DeleteTweet_V($acount);

      //退会ユーザーの情報を全て削除してから、現在の投稿数を改めて取得
      while ($user = $statement->fetch()) {
        $statements = $db->prepare('SELECT DISTINCT reply_id FROM replay_posts WHERE reply_id=?');
        $statements->execute(array($user['reply_id']));
        $userpost = $statements->fetch();
        if($userpost === false){
          $zero = 0;
          //削除に伴う投稿数の更新
          Update($zero,$user['reply_id']);
        }elseif($userpost !==false){
         //最大投稿数を取得
         $maxpost = GetCount($userpost['reply_id']);
         //削除に伴う投稿数の更新
         Update($maxpost['cnt'],$userpost['reply_id']);
        }
      }
    }elseif($distinctreply['reply_id'] === null) {
      //ユーザーデータの削除
      DeleteUserId($acount);
    }
  }
  session_destroy();
  header('Location:index.php');exit();
}
