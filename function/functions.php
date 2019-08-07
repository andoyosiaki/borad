<?php
define('IMAGES_DIR','images/'); //画像フォルダ
define('COMPRE_IMG','Compre_img/'); //投稿加工画像
define('PROTO_IMG','Proto_img/'); //投稿元画像
define('P_COMPRE_IMG','Profile_Compre_img/'); //プロフィール加工画像
define('P_PROTO_IMG','Profile_Proto_img/'); //プロフィール元画像
define('R_COMPRE_IMG','Reply_Compre_img/'); //返信加工画像
define('R_PROTO_IMG','Reply_Proto_img/'); //返信元画像

define('MAX_FILE_SIZE', 4 * 1024 * 1024); // 1MB


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

function uniqidname(){

}


 ?>
