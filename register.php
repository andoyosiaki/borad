<?php
session_start();
require_once __DIR__."/core/dbconect.php";

if(!empty($_POST)){
    //アカウント名空判定
  if($_POST['name'] === ''){
    $error['name'] = 'blank';
  }
    //アカウント名半角英数判定
  if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['name'])){
    $error['name'] = 'alph_chara_name';
  }
    //アカウント名10文字以下判定
  if(strlen($_POST['name']) >10){
    $error['name'] = 'name_length';
  }
    //パスワード空判定
  if($_POST['password'] === ''){
    $error['password'] = 'blank';
  }
    //パスワード半角英数判定
  if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])){
    $error['password'] = 'alph_chara_pass';
  }
    //パスワード4文字以上判定
  if(strlen($_POST['password']) < 4){
    $error['password']='length';
  }
    //アカウント名ダブり判定
  if(empty($error)){
    $statment = $db->prepare('SELECT COUNT(*) AS cnt FROM userinfo WHERE name=?');
    $statment->execute(array($_POST['name']));
    $member = $statment->fetch();
    if($member['cnt'] > 0){
      $error['name'] = 'duplicate';
    }
  }

    //$error空判定
  if(empty($error)){
    $_SESSION['join'] = $_POST;
    header('Location:next.php');exit();
  }
}


 ?>

<?php require_once('./head.php'); ?>

  <div class="InsertFormSection">
    <div class="InsertFormBox">
      <h2>会員登録画面</h2>
      <form class="" action="" method="post">
        <div class="form-group">
          <label for="exampleInputEmail1">アカウント名</label>
          <input type="text" name="name" value="" placeholder="アカウント名" class="form-control" id="exampleInputEmail1">
          <?php if($error['name'] === 'blank'): ?>
          <p class="attension">入力が空です</p>
          <?php endif; ?>
          <?php if($error['name'] === 'alph_chara_name'): ?>
          <p class="attension">半角英数字</p>
          <?php endif; ?>
          <?php if($error['name'] === 'name_length'): ?>
          <p class="attension">10文字以下</p>
          <?php endif; ?>
          <?php if($error['name'] === 'duplicate'): ?>
          <p class="attension">このアカウント名は使えません</p>
          <?php endif; ?>
          <small class="text-muted">アカウント名は半角英数文字の10文字以下でお願いします。</small>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">パスワード</label>
          <input type="password" name="password" value="" placeholder="パスワード" class="form-control" id="exampleInputPassword1">
          <?php if($error['password'] === 'blank'): ?>
          <p class="attension">入力が空です</p>
          <?php endif; ?>
          <?php if($error['password'] === 'alph_chara_pass'): ?>
          <p class="attension">半角英数字</p>
          <?php endif; ?>
          <?php if($error['password'] === 'name_length'): ?>
          <p class="attension">４文字以上</p>
          <?php endif; ?>
          <small class="text-muted">パスワードは半角英数字で４文字以上でお願いします。</small>
        </div>
        <input type="hidden" name="icon" value="0.png">
        <button type="submit" class="btn btn-primary">送信する</button>
      </form>
    </div>
  </div>

<?php require_once('./footer.php'); ?>
