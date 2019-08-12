<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);

$img_error = $_FILES['image']['error'];

if($_POST['MAX_FILE_SIZE'] > $_FILES['image']['size'] && $_COOKIE['save'] === null){ //画像サイズの確認
  $image_size = 'sizeture';
}else {
  header('Location:index.php');exit();
}

if(isset($_SESSION['id'])){
  if(isset($_FILES['image']['name']) && $_SERVER['REQUEST_METHOD'] === 'POST' && $image_size){
    
    //拡張子を抽出
    $ext = CutExt_Lower($_FILES['image']['name']);

    if($img_error === 0 && $ext === '.jpg' || $ext === '.png'){
      $rec = GetUserId($_SESSION['id']);
      $day = time();
      $img_adress =  $rec['name'].$day.$_SESSION['id'].$ext;

      //ファイルの拡張子チェックと保存
      list($baseImage,$width,$hight) = images($_FILES['image']['tmp_name'],PROTO_IMG,$img_adress);

      // サイズを指定して新しい画像のキャンバスを作成
      $image = imagecreatetruecolor(THUMB_WIDTH, THUMB_HEIGHT);

     if(isset($baseImage)){
       CreatTtumb($image,$baseImage,COMPRE_IMG,$width,$hight,$img_adress);
     }else {
       header('Location:index.php');exit();
     }

    }else {
     $img_adress = 0;
     $error = 'extension';
    }
  }else {
    $img_adress = 0;
  }

  if(mb_strlen($_POST['text']) < 200){
    $true_text = $_POST['text'];
  }else {
    header('Location:index.php');exit();
  }



    //ログインしてる&POSTでアクセス&テキストが空じゃないor拡張子が.jpgか.pngの場合
    if(isset($_COOKIE['save']) && $_COOKIE['save'] !==null){
      header('Location:index.php');exit();
    }elseif($_SESSION['id'] && $_SERVER['REQUEST_METHOD'] === 'POST' && $_COOKIE['save'] === null && !empty($true_text) || empty($error)){
    $uniq = md5(uniqid(rand(),true));
    $statment = $db->prepare('INSERT INTO tweets SET author_id=?,uniq_id=?,content=?,tweet_img=?,create_at=NOW()');
    $statment->execute(array(
      $_SESSION['id'],
      $uniq,
      $_POST['text'],
      $img_adress
    ));

    PostingRestriction();//クッキー作成

    header('Location:index.php');exit();
    //ログインしてる&POSTでアクセス&テキストが空&拡張子が.jpgか.png以外の場合
  }elseif ($_SESSION['id'] && $_SERVER['REQUEST_METHOD'] === 'POST' && $true_text ==='' && $error){
     header('Location:index.php');exit();
  }else {
    header('Location:index.php');exit();
  }
}else {
  header('Location:index.php');exit();
}
