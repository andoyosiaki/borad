<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>画像掲示板</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
 <link href="css/style.css" rel="stylesheet">
 <script src="js/main.js"></script>
</head>
<body>
 <header>
<!-- Navigation -->
   <nav class="Navigation">
     <div class="NavigationBox">
       <div class="NavigationBrandBox">
         <h1 class="NavigationBrandTitle"><a href="index.php">画像掲示板</a></h1>
       </div>
       <ul class="NavigationList">
           <li class="NavigationItems" id="Mypage"><a href="Mypage.php?page=<?php if(isset($_SESSION['id'])){ echo $_SESSION['id'];} ?>">Mypage</a></li>
         <li class="NavigationItems" id="Login"><a href="login.php">Login</a></li>
         <li class="NavigationItems" id="Logout"><a href="logout.php">Logout</a></li>
         <li class="NavigationItems" id="Register"><a href="Register.php">Register</a></li>
       </ul>
     </div>
   </nav>
<!-- Navigation -->
