<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

$errors = NULL;
//$_POSTの情報がdbにあったら$_SESSIONにデータ挿入
if(!empty($_POST)){
  if(!$_POST['name'] !=='' && !$_POST['password'] !=='' ){
    $statement = $db->prepare('SELECT * FROM userinfo WHERE name=? AND password=?');
    $statement->execute(array(
      $_POST['name'],
      sha1($_POST['password'])
    ));
    $rec = $statement->fetch();

    if($rec){
      $_SESSION['id'] = $rec['user_id'];
      $_SESSION['name'] = $rec['name'];
      $_SESSION['icon'] = $rec['icon'];
      $_SESSION['time'] = time();
      header('Location:index.php');exit();
    }else {
        $errors = 'miss1';
    }
  }else {
      $errors = 'miss2';
  }
}

?>
<?php require_once('./head.php'); ?>
  <div class="InsertFormSection">
    <div class="InsertFormBox">
      <h2>ログイン画面</h2>
      <form class="" action="" method="post">
        <div class="form-group">
          <label for="exampleInputEmail1">アカウント名</label>
          <?php if(isset($_SESSION['join']['name'])): ?>
          <input type="text" name="name" value="<?php echo $_SESSION['join']['name']; ?>" placeholder="アカウント名" class="form-control" id="exampleInputEmail1">
          <?php else: ?>
          <input type="text" name="name" value="" placeholder="アカウント名" class="form-control" id="exampleInputEmail1">
          <?php endif; ?>
          <?php if($errors === 'miss1'): ?>
          <p>入力に誤りがあります</p>
          <?php endif; ?>
          <?php if($errors === 'miss2'): ?>
          <p>入力に誤りがあります</p>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">パスワード</label>
          <?php if(isset($_SESSION['join']['password'])): ?>
          <input type="password" name="password" value="<?php echo $_SESSION['join']['password']; ?>" placeholder="パスワード" class="form-control" id="exampleInputPassword1">
          <?php else: ?>
          <input type="password" name="password" value="" placeholder="パスワード" class="form-control" id="exampleInputPassword1">
          <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">送信する</button>
      </form>
    </div>
  </div>
<?php require_once('./footer.php'); ?>
