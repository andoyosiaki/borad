<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
ini_set('display_errors',1);



if($_POST['MAX_FILE_SIZE'] > $_FILES['image']['size']){ //画像サイズの確認
  $image_size = 'sizetrue';
}else {
  header('Location:index.php');exit();
}

$statments = $db->query('SELECT * FROM tweets INNER JOIN userinfo on userinfo.user_id=tweets.author_id order by tweets.tweets_id DESC');
$rec = $statments->fetch();

$img_error = $_FILES['image']['error'];

  if(isset($_FILES['image']['name']) && $_SERVER['REQUEST_METHOD'] === 'POST' && $image_size){
    $ext = substr($_FILES['image']['name'],-4);

    if($ext === '.jpg' || $ext === '.png' && $img_error === 0){
      $day = time();
      $img_adress =  $day.$_SESSION['id'].$ext;
      move_uploaded_file($_FILES['image']['tmp_name'],'images/Proto_img/'."$img_adress");


      list($width, $hight,$info) = getimagesize('images/Proto_img/'."$img_adress"); // 元の画像名を指定してサイズを取得

      switch($info){
      case 2:
      $baseImage = imagecreatefromjpeg("images/Proto_img/"."$img_adress");
      break;
      case 3:
      $baseImage = imagecreatefrompng("images/Proto_img/"."$img_adress");
      break;
      }
      $image = imagecreatetruecolor(200, 140); // サイズを指定して新しい画像のキャンバスを作成


       if(isset($baseImage)){ //アップロードされた画像ファイルが偽装ファイルじゃないか確認(不完全)
       imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 200, 140, $width, $hight);
       imagejpeg($image , 'images/Compre_img/'."$img_adress");
       }else {
         header('Location:index.php');exit();
       }
    }else {
     $img_adress = 0;
     $error = 'extension';
     var_dump($error);
    }
  }else {
    $img_adress = 0;
    var_dump($img_adress);
  }

  if(mb_strlen($_POST['text']) < 200){
   $true_text = $_POST['text'];
  }else {
     header('Location:index.php');exit();
  }

    //ログインしてる&POSTでアクセス&テキストが空じゃないor拡張子が.jpgか.pngの場合
  if($_SESSION['id'] && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($true_text) || empty($error)){
    $statment = $db->prepare('INSERT INTO tweets SET author_id=?,content=?,tweet_img=?,create_at=NOW()');
    $statment->execute(array(
      $_SESSION['id'],
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
