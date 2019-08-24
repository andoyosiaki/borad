<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

$img_error = $_FILES['image']['error'];

//アイコンの保存処理
if(isset($_FILES['image']['name'])){
  //新しいアイコンを保存する前に古いアイコンの削除処理を行う
    $user = GetUserId($_SESSION['id']);
  if(isset($user['icon']) && $user['icon'] !=='0.png'){
    DeleteFile_2(P_COMPRE_IMG,P_PROTO_IMG,$user['icon']);
  }

  //投稿されたアイコンのサイズ加工処理と元画像と加工した画像の保存
  $ext = CutExt_Lower($_FILES['image']['name']);
  if($img_error === 0 && $ext === '.jpg' || $ext === '.png'){
    $day = time();
    $img_adress =  $day.$_SESSION['id'].$ext;
    list($baseImage,$width,$hight) = images($_FILES['image']['tmp_name'],P_PROTO_IMG,$img_adress);
    $image = imagecreatetruecolor(ICON_SIZE, ICON_SIZE); // サイズを指定して新しい画像のキャンバスを作成

    // 画像のコピーと伸縮
    CreatTtumb($image,$baseImage,P_COMPRE_IMG,$width,$hight,$img_adress);
  }elseif($_POST['hidden_img']) {
    $img_adress = $_POST['hidden_img'];
  }else {
    $img_adress = '0.png';
  }
}


//テキストの保存処理
if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['id'] && mb_strlen($_POST['intorotext']) < 200  ){ //プロフィールのテキストは200文字以内に指定
  //画像を変更しない場合の処理
 if($_FILES['image']['name'] === ''){
   if($_POST['hidden_img'] === null){
      $img_adress = $_POST['hidden_img'];
   }
 }
$statment = $db->prepare('UPDATE userinfo SET introduction=?,icon=? WHERE user_id=?');
$statment->execute(array(
  $_POST['intorotext'],
  $img_adress,
  $_SESSION['id']
));
header("Location:Mypage.php?page=".$_SESSION['id']);exit();
}else {
 header("Location:Mypage.php?page=".$_SESSION['id']);exit();
}
