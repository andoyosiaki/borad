<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";
ini_set('display_errors',1);

// if($_COOKIE['name'] !=''){
//   $_POST['name'] = $_COOKIE['name'];
//   $_POST['password'] = $_COOKIE['password'];
//   $_POST['save'] = 'on';
// }


// if(!$_SESSION){
  $errors['errors'] = NULL;
  //$_POSTの情報がdbにあったら$_SESSIONにデータ挿入
  if(!empty($_POST)){
    if(!$_POST['name'] !=='' && !$_POST['password'] !=='' ){
      $statment = $db->prepare('SELECT * FROM userinfo WHERE name=? AND password=?');
      $statment->execute(array(
        $_POST['name'],
        sha1($_POST['password'])
      ));
      $rec = $statment->fetch();

      if($rec){
        $_SESSION['id'] = $rec['user_id'];
        $_SESSION['name'] = $rec['name'];
        $_SESSION['icon'] = $rec['icon'];
        $_SESSION['time'] = time();
        header('Location:index.php');exit();
        echo "成功";
      }else {
          $errors['errors'] = 'miss1';
      }
    }else {
        $errors['errors'] = 'miss2';
    }
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>twiiter clone</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
 <script type="text/javascript" src="inview/jquery.inview.min.js"></script>
 <link rel="stylesheet" href="animate/animate.min.css">
 <link href="css/style.css" rel="stylesheet">
 <script src="js/main.js"></script>
</head>
  <body>
    <header>
<!-- Navigation -->
      <nav class="Navigation">
        <div class="NavigationBox">
          <div class="NavigationBrandBox">
            <a href="index.php" class="text-dark">
            <h1 class="NavigationBrandTitle">Twitter Clone</h1>
            </a>
          </div>
          <ul class="NavigationList">
            <li class="NavigationItems" id="Mypage"><a href="Mypage.php?page=<?php echo $_SESSION['id']; ?>">Mypage</a></li>
            <li class="NavigationItems" id="Login"><a href="login.php">Login</a></li>
            <li class="NavigationItems" id="Logout"><a href="logout.php">Logout</a></li>
            <li class="NavigationItems" id="Register"><a href="Register.php">Register</a></li>
          </ul>
        </div>
      </nav>
   </header>
<!-- Navigation -->
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
          <?php if($errors['errors'] === 'miss1'): ?>
            <p>入力に誤りがあります</p>
          <?php endif; ?>
          <?php if($errors['errors'] === 'miss2'): ?>
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
</body>
<footer>

</footer>
