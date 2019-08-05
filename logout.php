<?php

ini_set('display_errors',1);
session_start();

$_SESSION = array();
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
            <li class="NavigationItems" id="Mypage"><a href="Mypage.php?page=<?php if(isset($_SESSION['id'])){ echo $_SESSION['id'];} ?>">Mypage</a></li>
             <li class="NavigationItems" id="Login"><a href="login.php">Login</a></li>
             <li class="NavigationItems" id="Logout"><a href="logout.php">Logout</a></li>
             <li class="NavigationItems" id="Register"><a href="Register.php">Register</a></li>
           </ul>
         </div>
       </nav>
    </header>
    <body>
      <div class="InsertFormSection">
        <div class="InsertFormBox text-center">
          <p><?php echo $logount; ?></p>
        </div>
      </div>
    </body>
