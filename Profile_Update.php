<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";

ini_set('display_errors',1);

$img_error = $_FILES['image']['error'];

if(isset($_FILES['image']['name'])){
  $ext = substr($_FILES['image']['name'],-4);


  if($ext === '.jpg' || $ext === '.png' && $img_error === 0){
    $day = time();
    $img_adress =  $day.$_SESSION['id'].$ext;
    move_uploaded_file($_FILES['image']['tmp_name'],'images/Profile_Proto_img/'."$img_adress");


    $info = substr('images/Profile_Proto_img/'."$img_adress",-4);
    list($width, $hight) = getimagesize('images/Profile_Proto_img/'."$img_adress"); // 元の画像名を指定してサイズを取得

    switch($info){
    case '.jpg':
    $baseImage = imagecreatefromjpeg("images/Profile_Proto_img/"."$img_adress");
    break;
    case '.png':
    $baseImage = imagecreatefrompng("images/Profile_Proto_img/"."$img_adress");
    break;
    }

    $image = imagecreatetruecolor(100, 100); // サイズを指定して新しい画像のキャンバスを作成

    // 画像のコピーと伸縮
    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 100, 100, $width, $hight);
    imagejpeg($image , 'images/Profile_Compre_img/'."$img_adress");
  }elseif($_POST['hidden_img']) {
    $img_adress = $_POST['hidden_img'];
  }else {
    $img_adress = '0.png';
  }
}

// $length =  mb_strlen($_POST['intorotext']);
 if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['id'] && mb_strlen($_POST['intorotext']) < 200  ){
    //画像を変更しない場合の処理
   if($_FILES['image']['name'] === ''){
     if($_POST['hidden_img'] === null){
       $_POST['hidden_img'] = '0.png';
        $img_adress = $_POST['hidden_img'];
     }

   }
var_dump($img_adress);
var_dump($_FILES['image']['name']);
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
