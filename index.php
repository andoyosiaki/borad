<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require "function/functions.php";

define('MAX_FILE_SIZE', 4 * 1024 * 1024); // 1MB
ini_set('display_errors',1);



$statment = $db->query('SELECT * FROM tweets INNER JOIN userinfo on userinfo.user_id=tweets.author_id order by tweets.tweets_id DESC');



var_dump($_SERVER['REQUEST_URI']);

 ?>
 <!DOCTYPE html>
 <html lang="ja">
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>twiiterもどき(仮)</title>
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
          <h1 class="NavigationBrandTitle">twiiterもどき(仮)</h1>
        </div>
        <ul class="NavigationList">
          <li class="NavigationItems" id="Mypage"><a href="Mypage.php?page=<?php echo $_SESSION['id']; ?>">Mypage</a></li>
          <li class="NavigationItems" id="Login"><a href="login.php">Login</a></li>
          <li class="NavigationItems" id="Logout"><a href="logout.php">Logout</a></li>
          <li class="NavigationItems" id="Register"><a href="Register.php">Register</a></li>
        </ul>
      </div>
    </nav>
<!-- Navigation -->
<!-- TweetPostSection & Form -->
    <div class="TweetPostSection">
      <?php if(!empty($_SESSION)): ?>
      <div class="TweetPostFormBox">
        <form class="TweetPostForm" action="Posts_Validate.php" method="post" enctype="multipart/form-data">
          <textarea class="form-control" name="text" rows="5" placeholder="投稿内容は200文字以下で,画像は2M以下の.jpgか.pngのみUPできます。" id="Textarea"></textarea>
          <div class="form-group mt-1 file">
            <label for="File" id="LabelFile"><i class="far fa-image fa-2x "></i></label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">
            <input type="file" name="image" id="File">
          </div>
          <button type="submit" class="btn btn-lg FormBtn">　送　信　</button>
        </form>
      </div>
    <?php elseif(empty($_SESSION)): ?>
      <div class="InductionLoginBox">
        <p>ログインすると投稿可能になります。</p>
        <div class="Induction">
          <a href="login.php"><button type="button" class="btn bg-primary">Login</button></a>
          <a href="Register.php"><button type="button" class="btn bg-warning">Register</button></a>
        </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
<!-- TweetPostSection & Form -->
  </header>
<!-- main -->
  <main>
    <?php while($rec = $statment->fetch()): ?>
    <article class="MainArticle">
      <div class="MainIconBox">
        <object><a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>">
          <img src="images/Profile_Compre_img/<?php echo $rec['icon']; ?>" alt="" class="MinIcon">
        </object></a>
      </div>
      <div class="MainAuthorPostBox">
        <a href="Reply_Posts.php?page=<?php echo $rec['tweets_id'] ;  ?>">
          <div class="MainAuthorName">
            <div class="NameBox">
              <object><a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>"><?php echo h($rec['name']); ?></a></object>
            </div>
            <div class="TimeBox">
              <time><?php echo times($rec['created']); ?></time>
            </div>
          </div>
          <div class="MainPostBox">
            <p><?php echo newline($rec['content']); ?></p>
          </div>
          <div class="MainPostImageBox">
            <?php if($rec['tweet_img']): ?>
            <object><a href="Detail_Image.php?item=<?php echo $rec['tweets_id']; ?>"><img src="images/Compre_img/<?php echo $rec['tweet_img']; ?>" class="MainPostImage"></a></object>
            <?php else: ?>
            <p></p>
            <?php endif; ?>
          </div>
        </a>
        <div class="TinkerBox">
          <div class="ReplyIconBox">
            <object><a href="Reply_Posts.php?page=<?php echo $rec['tweets_id'] ;  ?>"><i class="far fa-comment fa-lg "></i></a></object>
            <?php if($rec['maxpost'] > 0): ?><span class="MaxReplayPost"><?php echo $rec['maxpost']; ?></span><?php endif; ?>
          </div>
          <?php if($_SESSION && $_SESSION['id'] === $rec['author_id']): ?>
          <div class="DeleteIconBox">
            <i class="far fa-trash-alt" id="<?php echo $rec['tweets_id']; ?>"></i>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <script>
      $('#<?php echo $rec['tweets_id']; ?>').click(function(){
          if(!confirm('本当に削除しますか？')){
              return false;
          }else{
              location.href = 'Delete_Post.php?page=<?php echo $rec['tweets_id']; ?>';
          }
      });
    </script>
    <?php endwhile; ?>
  </main>
<!-- main -->
 </body>
</html>
