<?php
define('IMAGES_DIR','images/'); //画像フォルダ
define('COMPRE_IMG','Compre_img/'); //投稿加工画像
define('PROTO_IMG','Proto_img/'); //投稿元画像
define('P_COMPRE_IMG','Profile_Compre_img/'); //プロフィール加工画像
define('P_PROTO_IMG','Profile_Proto_img/'); //プロフィール元画像
define('R_COMPRE_IMG','Reply_Compre_img/'); //返信加工画像
define('R_PROTO_IMG','Reply_Proto_img/'); //返信元画像

define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

//サムネイルサイズ
define('THUMB_WIDTH',200);
define('THUMB_HEIGHT',140);

//アイコンサイズ
define('ICON_SIZE',100);

function GetDB(){
  try {
    $db = new PDO('mysql:dbname=twitter;host=localhost;charset=utf8','root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
    return $db;
}

function GetUserId($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'SELECT * FROM userinfo WHERE user_id=?';
  $stetment = $dbh->prepare($sql);
  $stetment->execute($datas);
  return  $stetment->fetch();
}

function DeleteUserId($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'DELETE FROM userinfo where user_id=?';
  $delete = $dbh->prepare($sql);
  return  $delete->execute($datas);
}

function GetTweetId($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'SELECT * FROM tweets WHERE tweets_id=?';
  $stetment = $dbh->prepare($sql);
  $stetment->execute($datas);
  return  $stetment->fetch();
}

function DeleteTweet($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'DELETE  FROM tweets WHERE tweets_id=?';
  $stetment = $dbh->prepare($sql);
  return $stetment->execute($datas);
}

function SelectReply($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'SELECT * FROM replay_posts WHERE reply_co_id=?';
  $statment = $dbh->prepare($sql);
  $statment->execute($datas);
  return $statment->fetch();
}

function DeleteReply($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'DELETE  FROM replay_posts WHERE reply_co_id=?';
  $delete = $dbh->prepare($sql);
  return $delete->execute($datas);
}

function DeleteReplyPost($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'DELETE FROM replay_posts where reply_author_id=?';
  $deletes = $dbh->prepare($sql);
  return  $deletes->execute($datas);
}

function GetDistinctReply($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'SELECT DISTINCT reply_id FROM replay_posts WHERE reply_author_id=?';
  $stetment = $dbh->prepare($sql);
  $stetment->execute($datas);
  return $stetment->fetch();
}

function DeleteTweet_V($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'DELETE FROM tweets where author_id=?';
  $statment = $dbh->prepare($sql);
  if(!empty($statment)){
    return  $statment->execute($datas);
  }
}

function Maxpost($sql,$data){
  $datas = array($data);
  $dbh = getDB();
  $max = $dbh->prepare($sql);
  $max->execute($datas);
  return $max->fetch();
}

function GetCount($data){
  $datas = array($data);
  $dbh = getDB();
  $sql = 'SELECT COUNT(*) as cnt FROM replay_posts WHERE reply_id=?';
  $statment = $dbh->prepare($sql);
  $statment->execute($datas);
  return $statment->fetch();
}

function Update($key,$value){
  $dbh = getDB();
  $update = $dbh->prepare('UPDATE tweets SET maxpost=? WHERE tweets_id=?');
  return $update->execute(array($key,$value));
}

function Select($sql,$data){
  $datas = array($data);
  $dbh = GetDB();
  $statment = $dbh->prepare($sql);
  $statment->execute($datas);
  return $statment->fetch();
}

function Deletes($sql,$data){
  $datas = array($data);
  $dbh = GetDB();
  $delete = $dbh->prepare($sql);
  return $delete->execute($datas);
}



//image
function images($data,$dir,$pass){
  move_uploaded_file($data,IMAGES_DIR.$dir.$pass);
  list($width, $hight,$info) = getimagesize(IMAGES_DIR.$dir.$pass); // 元の画像名を指定してサイズを取得
  switch($info){
  case 2:
  $base = imagecreatefromjpeg(IMAGES_DIR.$dir.$pass);
  break;
  case 3:
  $base = imagecreatefrompng(IMAGES_DIR.$dir.$pass);
  break;
  }
  return array($base,$width,$hight);
}

function CreatTtumb($img,$base,$dir,$w,$h,$adress){
  if($dir ==='Compre_img/' || $dir === 'Reply_Compre_img/'){
    imagecopyresampled($img,$base, 0, 0, 0, 0,THUMB_WIDTH,THUMB_HEIGHT, $w, $h);
    return imagejpeg($img,IMAGES_DIR.$dir.$adress);
  }elseif($dir === 'Profile_Compre_img/'){
    imagecopyresampled($img,$base, 0, 0, 0, 0,ICON_SIZE,ICON_SIZE, $w, $h);
    return imagejpeg($img,IMAGES_DIR.$dir.$adress);
  }
}

function CutExt_Lower($data){
   return strtolower(substr($data,-4));
}

function DeleteFile_2($dir1,$dir2,$data){
  $file1 = IMAGES_DIR.$dir1.$data;
  $file2 = IMAGES_DIR.$dir2.$data;
  unlink($file1);
  unlink($file2);
}

function CreateImagePath($data,$extension){
  if(isset($data)){
    $uniq_id = GetTweetId($data);
    $uniq = $uniq_id['uniq_id'];
    $day = time();
    return  $img =  $_SESSION['name'].$uniq.$day.$_SESSION['id'].$extension;
  }elseif(is_null($data)) {
    $rec = GetUserId($_SESSION['id']);
    $day = time();
    return  $img =  $rec['name'].$day.$_SESSION['id'].$extension;
  }
}



function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'utf-8');
}

//datatimeの加工
function times($time){
return  substr($time,0,-3);
}

//テキストの改行処理
function newline($text){
  $texts = h($text);
  return nl2br($texts);
}

function deletefileforeach($filepass){
  foreach(glob($filepass.'*') as $filepass){
    unlink($filepass);
  }
}

function PostingRestriction(){
  $key = 'save';
  $value = 'post';
  $time = time()+1;
  setcookie($key,$value,$time);
}

 ?>
