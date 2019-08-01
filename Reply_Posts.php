<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require(__DIR__.'/function/functions.php');
ini_set('display_errors',1);

define('MAX_FILE_SIZE', 4 * 1024 * 1024); // 1MB


  $statment = $db->prepare('SELECT * FROM userinfo INNER JOIN tweets on userinfo.user_id=tweets.author_id WHERE tweets.tweets_id=?');
  $statment->execute(array(
    $_REQUEST['page']
  ));
  $rec = $statment->fetch();


  $statments = $db->prepare('SELECT * FROM replay_posts JOIN tweets ON replay_posts.reply_id=tweets.tweets_id RIGHT JOIN userinfo ON userinfo.user_id=replay_posts.reply_author_id WHERE replay_posts.reply_id=? AND tweets_id=?');
  $statments->execute(array(
    $_REQUEST['page'],
    $_REQUEST['page']
  ));


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
<!-- Navigation -->
  <nav class="Navigation">
    <div class="NavigationBox">
      <div class="NavigationBrandBox">
        <a href="index.php" class="text-dark">
          <h1 class="NavigationBrandTitle">Twitter Clone</h1>
        </a>
      </div>
      <ul class="NavigationList">
        <li class="NavigationItems" id="Mypage"><a href="Mypage.php?page=<?php echo  $_SESSION['id']; ?>">Mypage</a></li>
        <li class="NavigationItems" id="Login"><a href="login.php">Login</a></li>
        <li class="NavigationItems" id="Logout"><a href="logout.php">Logout</a></li>
        <li class="NavigationItems" id="Register"><a href="Register.php">Register</a></li>
      </ul>
    </div>
  </nav>
  <form class="" action="Reply_Posts_Validate.php" method="post">
    <input type="hidden" name="maxpost" value="<?php echo $_REQUEST['page']; ?>">
  </form>
<!-- Navigation -->
  <main>
    <article class="MainArticle">
      <div class="MainIconBox">
        <a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>">
          <img src="images/Profile_Compre_img/<?php echo $rec['icon']; ?>" alt="" class="MinIcon">
        </a>
      </div>
      <div class="MainAuthorPostBox">
        <div class="MainAuthorName">
          <div class="NameBox">
            <a href="Mypage.php?page=<?php echo $rec['user_id']; ?>"><?php echo h($rec['name']); ?></a>
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
          <a href="Detail_Image.php?item=<?php echo $rec['tweets_id']; ?>"><img src="images/Compre_img/<?php echo $rec['tweet_img']; ?>" class="MainPostImage"></a>
          <?php else: ?>
          <p></p>
          <?php endif; ?>
        </div>
      </div>
    </article>
  </main>

<!-- ReplaySecsion -->
  <main>
    <?php while($recs = $statments->fetch()): ?>
    <article class="MainArticle">
      <div class="MainIconBox">
        <a href="Mypage.php?page=<?php echo  $recs['reply_author_id']; ?>">
          <img src="images/Profile_Compre_img/<?php echo $recs['icon']; ?>" alt="" class="MinIcon">
        </a>
      </div>
      <div class="MainAuthorPostBox">
        <div class="MainAuthorName">
          <div class="NameBox">
            <a href="Mypage.php?page=<?php echo  $recs['reply_author_id']; ?>"><?php echo h($recs['reply_author_name']); ?></a>
          </div>
          <div class="TimeBox">
            <time><?php echo times($recs['re_create_at']); ?></time>
          </div>
        </div>
        <div class="MainPostBox">
          <p><?php  $text = h($recs['reply_content']); echo nl2br($text);?></p>
        </div>
        <div class="MainPostImageBox">

          <?php if($recs['reply_img']): ?>
          <a href="Reply_Detail_Image.php?page=<?php echo $recs['reply_img']; ?>"><img src="images/Reply_Compre_img/<?php echo $recs['reply_img']; ?>" class="MainPostImage"></a>
          <?php else: ?>
          <p></p>
          <?php endif; ?>
        </div>
        <div class="TinkerBox">
          <?php if(isset($_SESSION['id'])  && $_SESSION['id'] === $recs['reply_author_id']): ?>
          <div class="DeleteIconBox">
              <i class="far fa-trash-alt" id='<?php echo $recs['reply_co_id']; ?>'></i>
            <input type="hidden" name="deletepost" value="">
          </div>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <script>
      $('#<?php echo $recs['reply_co_id']; ?>').click(function(){
          if(!confirm('本当に削除しますか？')){
              return false;
          }else{
              location.href = 'Delete_Post.php?Reply=<?php echo $recs['reply_co_id']; ?>';
          }
      });
    </script>
    <?php endwhile; ?>
  </main>
<!-- ReplaySecsion -->
<!-- Insert -->
  <div class="Insert">
    <?php if($_SESSION): ?>
    <div class="TweetPostSection">
      <div class="TweetPostFormBox">
      <form class="TweetPostForm" action="Reply_Posts_Validate.php" method="post" enctype="multipart/form-data">
        <textarea name="re_text" rows="8" cols="80" id="Textarea" placeholder="投稿内容は200文字以下で、画像は2M以下の.jpgか.pngのみUPできます。"></textarea>
        <input type="hidden" name="reply_id" value="<?php echo $_REQUEST['page']; ?>">
        <input type="hidden" name="reply_author_id" value="<?php echo $_SESSION['id']; ?>">
        <input type="hidden" name="reply_author_name" value="<?php echo h($_SESSION['name']); ?>">
        <input type="hidden" name="maxpost" value="<?php echo $_REQUEST['page']; ?>">
        <div class="form-group mt-1 file">
          <label for="File" id="LabelFile"><i class="far fa-image fa-2x "></i></label>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">
          <input type="file" name="image" value="" id="File">
        </div>
        <button type="submit" class="btn btn-lg FormBtn">　送　信　</button>
      </form>
      </div>
    </div>
  <?php elseif(!$_SESSION): ?>
  <div class="TweetPostSection">
  <div class="InductionLoginBox">
    <p>ログインすると投稿可能になります。</p>
    <div class="Induction">
      <a href="login.php"><button type="button" class="btn bg-primary">Login</button></a>
      <a href="Register.php"><button type="button" class="btn bg-warning">Register</button></a>
    </div>
    </div>
  </div>
    </div>
  <?php endif; ?>
  </div>
<!-- Insert -->
</body>
</html>
