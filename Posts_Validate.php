<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);


if($_POST['MAX_FILE_SIZE'] > $_FILES['image']['size']){ //画像サイズの確認
  $image_size = 'sizetrue';
}else {
  header('Location:index.php');exit();
}

$statments = $db->prepare('SELECT * FROM userinfo where user_id=?');
$statments->execute(array($_SESSION['id']));
$rec = $statments->fetch();

$img_error = $_FILES['image']['error'];

  if(isset($_FILES['image']['name']) && $_SERVER['REQUEST_METHOD'] === 'POST' && $image_size){
    $ext = substr($_FILES['image']['name'],-4);

    if($ext === '.jpg' || $ext === '.png' && $img_error === 0){
      $day = time();
      $img_adress =  $rec['name'].$day.$_SESSION['id'].$ext;
      move_uploaded_file($_FILES['image']['tmp_name'],IMAGES_DIR.PROTO_IMG.$img_adress);


      list($width, $hight,$info) = getimagesize(IMAGES_DIR.PROTO_IMG.$img_adress); // 元の画像名を指定してサイズを取得

      switch($info){
      case 2:
      $baseImage = imagecreatefromjpeg(IMAGES_DIR.PROTO_IMG.$img_adress);
      break;
      case 3:
      $baseImage = imagecreatefrompng(IMAGES_DIR.PROTO_IMG.$img_adress);
      break;
      }
      $image = imagecreatetruecolor(200, 140); // サイズを指定して新しい画像のキャンバスを作成


       if(isset($baseImage)){ //アップロードされた画像ファイルが偽装ファイルじゃないか確認(不完全)
       imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 200, 140, $width, $hight);
       imagejpeg($image,IMAGES_DIR.COMPRE_IMG.$img_adress);
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
  if($_SESSION['id'] && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($true_text) || empty($error)){
    $uniq = md5(uniqid(rand(),true));
    $statment = $db->prepare('INSERT INTO tweets SET author_id=?,uniq_id=?,content=?,tweet_img=?,create_at=NOW()');
    $statment->execute(array(
      $_SESSION['id'],
      $uniq,
      $_POST['text'],
      $img_adress
    ));
    header('Location:index.php');exit();
    //ログインしてる&POSTでアクセス&テキストが空&拡張子が.jpgか.png以外の場合
  }elseif ($_SESSION['id'] && $_SERVER['REQUEST_METHOD'] === 'POST' && $true_text ==='' && $error){
     header('Location:index.php');exit();
  }else {
    header('Location:index.php');exit();
  }
