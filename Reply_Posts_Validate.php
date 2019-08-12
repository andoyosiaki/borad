<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);

$img_error = $_FILES['image']['error'];

if($_POST['MAX_FILE_SIZE'] > $_FILES['image']['size'] && $_COOKIE['save'] === null){ //画像サイズの確認
  $image_size = 'sizeture';
}else {
  header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
}

//画像のバリデーションと保存処理とサイズの加工処理
if(isset($_SESSION['id'])){
  if(isset($_FILES['image']['name']) && $_SERVER['REQUEST_METHOD'] === 'POST' && $image_size){

    //拡張子を抽出
    $ext = CutExt_Lower($_FILES['image']['name']);

    if($img_error === 0 && $ext === '.jpg' || $ext === '.png'){
      $uniq_id = GetTweetId($_POST['reply_id']);

      $uniq = $uniq_id['uniq_id'];
      $day = time();
      $img_adress =  $_SESSION['name'].$uniq.$day.$_SESSION['id'].$ext;

      list($baseImage,$width,$hight) = images($_FILES['image']['tmp_name'],R_PROTO_IMG,$img_adress);
      
      // サイズを指定して新しい画像のキャンバスを作成
      $image = imagecreatetruecolor(THUMB_WIDTH, THUMB_HEIGHT);

      if(isset($baseImage)){
        CreatTtumb($image,$baseImage,R_COMPRE_IMG,$width,$hight,$img_adress);
      }else {
        header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
      }

    }else {
      $error = 'extension';//拡張子が.jpg.png以外の場合はエラーを挿入してdbに保存させない
    }
  }

  if(mb_strlen($_POST['re_text']) < 200){
    $true_text = $_POST['re_text'];
  }else {
    header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }


  if(isset($_COOKIE['save']) && $_COOKIE['save'] !==null){
    header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }elseif($_SESSION['id'] && !empty($_POST['re_text']) || empty($error)){ //ログインしてる&テキストが空じゃないor拡張子が.jpgか.pngだった場合
    $statment = $db->prepare('INSERT INTO replay_posts SET reply_id=?,reply_author_id=?,reply_author_name=?,reply_content=?,reply_img=?,re_create_at=NOW()');
    $statment->execute(array(
      $_POST['reply_id'],
      $_POST['reply_author_id'],
      $_POST['reply_author_name'],
      $_POST['re_text'],
      $img_adress
    ));
    PostingRestriction();//クッキー作成

  }elseif ($_SESSION['id'] && $_POST['re_text'] ==='' && $error) { //ログインしてる&テキストが空&拡張子が.jpgか.png以外だった場合
    header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }else {
    header('Location:index.php');exit();
  }

  //返信数の変更
  if($_SESSION['id'] && $_POST['maxpost']){
    $maxpost = GetCount($_POST['maxpost']);
    Update($maxpost['cnt'],$_POST['maxpost']);
    header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }

}else {
  header('Location:index.php');
}
