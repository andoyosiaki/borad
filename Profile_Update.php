<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";

ini_set('display_errors',1);

$img_error = $_FILES['image']['error'];

if(isset($_FILES['image']['name'])){

  //新しいアイコンを保存する前に古いアイコンの削除処理を行う
  $statment = $db->prepare('SELECT * from userinfo where user_id=?');
  $statment->execute(array($_SESSION['id']));
  $user = $statment->fetch();
  if(isset($user['icon']) && $user['icon'] !=='0.png'){
  $file = IMAGES_DIR.P_COMPRE_IMG.$user['icon'];
  $files = IMAGES_DIR.P_PROTO_IMG.$user['icon'];
  }
  if(isset($file) && isset($files)){
    unlink($file);
    unlink($files);
  }

  //投稿されたアイコンのサイズ加工処理と元画像と加工した画像の保存
  $ext = substr($_FILES['image']['name'],-4);
  if($ext === '.jpg' || $ext === '.png' && $img_error === 0){
    $day = time();
    $img_adress =  $day.$_SESSION['id'].$ext;
    move_uploaded_file($_FILES['image']['tmp_name'],IMAGES_DIR.P_PROTO_IMG.$img_adress);


    list($width, $hight,$info) = getimagesize(IMAGES_DIR.P_PROTO_IMG.$img_adress); // 元の画像名を指定してサイズを取得

    switch($info){
    case 2:
    $baseImage = imagecreatefromjpeg(IMAGES_DIR.P_PROTO_IMG.$img_adress);
    break;
    case 3:
    $baseImage = imagecreatefrompng(IMAGES_DIR.P_PROTO_IMG.$img_adress);
    break;
    }

    $image = imagecreatetruecolor(100, 100); // サイズを指定して新しい画像のキャンバスを作成

    // 画像のコピーと伸縮
    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 100, 100, $width, $hight);
    imagejpeg($image,IMAGES_DIR.P_COMPRE_IMG.$img_adress);
  }elseif($_POST['hidden_img']) {
    $img_adress = $_POST['hidden_img'];
  }else {
    $img_adress = '0.png';
  }
}


 if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['id'] && mb_strlen($_POST['intorotext']) < 200  ){ //プロフィールのテキストは200文字以内に指定
    //画像を変更しない場合の処理
   if($_FILES['image']['name'] === ''){
     if($_POST['hidden_img'] === null){
        $img_adress = $_POST['hidden_img'];
     }
   }
  $statment = $db->prepare('UPDATE userinfo SET intoroduction=?,icon=? WHERE user_id=?');
  $statment->execute(array(
    $_POST['intorotext'],
    $img_adress,
    $_SESSION['id']
  ));
  header("Location:Mypage.php?page=".$_SESSION['id']);exit();
 }else {
   header("Location:Mypage.php?page=".$_SESSION['id']);exit();
 }
