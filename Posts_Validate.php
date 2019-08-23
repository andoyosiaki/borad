<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";


$img_error = $_FILES['image']['error'];

if($_POST['MAX_FILE_SIZE'] > $_FILES['image']['size'] && $_COOKIE['save'] === null){ //画像サイズの確認
  $image_size = 'sizeture';
}else {
  header('Location:index.php');exit();
}

if(isset($_SESSION['id']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_FILES['image']['name']) && $image_size){

  //拡張子を抽出
  $ext = CutExt_Lower($_FILES['image']['name']);
    if($img_error === 0 && $ext === '.jpg' || $ext === '.png'){
      $img_adress = CreateImagePath($ReplyId,$ext);
      //ファイルの拡張子チェックと保存
      list($baseImage,$width,$hight) = images($_FILES['image']['tmp_name'],PROTO_IMG,$img_adress);
      //サイズを指定して新しい画像のキャンバスを作成
      $image = imagecreatetruecolor(THUMB_WIDTH, THUMB_HEIGHT);
      //サムネイルの作成
      CreatTtumb($image,$baseImage,COMPRE_IMG,$width,$hight,$img_adress);
    }else {
     $img_adress = 0;
     $error = 'extension';//拡張子が.jpg.png以外の場合はエラーを挿入してdbに保存させない
    }

  }else {
    $img_adress = 0;
  }

    //ログインしてる&POSTでアクセス&テキストが空じゃないor拡張子が.jpgか.pngの場合
    if(isset($_COOKIE['save']) && $_COOKIE['save'] !==null || mb_strlen($_POST['text']) > 200){
      header('Location:index.php');exit();
    }elseif($_SESSION['id'] && !empty($_POST['text']) && $_COOKIE['save'] === null || empty($error)){
      $uniq = md5(uniqid(rand(),true));
      $statment = $db->prepare('INSERT INTO tweets SET author_id=?,uniq_id=?,content=?,tweet_img=?,create_at=NOW()');
      $statment->execute(array(
      $_SESSION['id'],
      $uniq,
      $_POST['text'],
      $img_adress
    ));
    //クッキー作成
    PostingRestriction();

    header('Location:index.php');exit();
    //ログインしてる&POSTでアクセス&テキストが空&拡張子が.jpgか.png以外の場合
  }elseif ($_SESSION['id'] && $true_text ==='' && $error){
     header('Location:index.php');exit();
  }else {
    header('Location:index.php');exit();
  }
}else {
  header('Location:index.php');exit();
}
