<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
ini_set('display_errors',1);

define('MAX_FILE_SIZE', 4 * 1024 * 1024); // 1MB


//画像のバリデーションと保存処理とサイズの加工処理
if(isset($_SESSION['id'])){
  if(isset($_FILES['image']['name']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $ext = substr($_FILES['image']['name'],-4);
    if($ext === '.jpg' || $ext === '.png'){
      $day = time();
      $img_adress =  $_POST['reply_author_name'].$day.$_SESSION['id'].$ext;
      move_uploaded_file($_FILES['image']['tmp_name'],'images/Reply_Proto_img/'."$img_adress");


      list($width, $hight,$info) = getimagesize('images/Reply_Proto_img/'."$img_adress"); // 元の画像名を指定してサイズを取得
      switch($info){
      case 2:
      $baseImage = imagecreatefromjpeg("images/Reply_Proto_img/"."$img_adress");
      break;
      case 3:
      $baseImage = imagecreatefrompng("images/Reply_Proto_img/"."$img_adress");
      break;
      }

      $image = imagecreatetruecolor(200, 140); // サイズを指定して新しい画像のキャンバスを作成

      if(isset($baseImage)){
        imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 200, 140, $width, $hight);
        imagejpeg($image , 'images/Reply_Compre_img/'."$img_adress");
      }else {
        header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
      }
    }else {
      $error = 'extension';//拡張子が.jpg.png以外の場合はエラーを挿入してdbに保存させない
    }
  }



  if($_SESSION['id'] && !empty($_POST['re_text']) || empty($error)){ //ログインしてる&テキストが空じゃないor拡張子が.jpgか.pngだった場合
    $statment = $db->prepare('INSERT INTO replay_posts SET reply_id=?,reply_author_id=?,reply_author_name=?,reply_content=?,reply_img=?,re_create_at=NOW()');
    $statment->execute(array(
      $_POST['reply_id'],
      $_POST['reply_author_id'],
      $_POST['reply_author_name'],
      $_POST['re_text'],
      $img_adress
    ));
  }elseif ($_SESSION['id'] && $_POST['re_text'] ==='' && $error) { //ログインしてる&テキストが空&拡張子が.jpgか.png以外だった場合
     header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }else {
     header('Location:index.php');exit();
  }

  if($_SESSION['id'] && $_POST['maxpost']){
    $max = $db->prepare('SELECT COUNT(*) as cnt FROM replay_posts WHERE reply_id=?');
    $max->execute(array(
      $_POST['maxpost']
    ));
    $maxpost = $max->fetch();
  }

  //返信の投稿数
  if($maxpost['cnt']){
    $max = $db->prepare('UPDATE tweets SET maxpost=? WHERE tweets_id=?');
    $max->execute(array(
      $maxpost['cnt'],
      $_POST['maxpost']
    ));
     header('Location:Reply_Posts.php?page='.$_POST['reply_id']);exit();
  }
}else {
  header('Location:index.php');
}
