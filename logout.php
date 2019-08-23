<?php
session_start();


if(isset($_SESSION['id'])){
  if(ini_get("session.use_cookies") && $_SESSION){
    $params = session_get_cookie_params();
    setcookie(session_name(),'',time()-4200,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
  }

  session_destroy();

  setcookie('name','',time()-3600);
  setcookie('password','',time()-3600);
  $logount = 'ログアウトしました。';
}else {
  header('Location:index.php');
}

 ?>

<?php require_once('./head.php'); ?>

    </header>
    <body>
      <div class="InsertFormSection">
        <div class="InsertFormBox text-center">
          <p><?php if(isset($logount)) {echo $logount; }?></p>
        </div>
      </div>

<?php require_once('./footer.php'); ?>
