<?php
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
 ?>
