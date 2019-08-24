<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

$sql = 'SELECT * FROM userinfo INNER JOIN tweets on userinfo.user_id=tweets.author_id WHERE tweets.tweets_id=?';
$rec = Select($sql,$_REQUEST['page']);

$statments = $db->prepare('SELECT * FROM replay_posts JOIN tweets ON replay_posts.reply_id=tweets.tweets_id RIGHT JOIN userinfo ON userinfo.user_id=replay_posts.reply_author_id WHERE replay_posts.reply_id=? AND tweets_id=?');
$statments->execute(array(
  $_REQUEST['page'],
  $_REQUEST['page']
));

?>
<?php require_once('./head.php'); ?>
  <main>
    <article class="MainArticle">
      <div class="MainIconBox">
        <a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>">
          <img src="<?php echo IMAGES_DIR.P_COMPRE_IMG ?><?php echo $rec['icon']; ?>" alt="" class="MinIcon">
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
          <p class="MainPost"><?php echo newline($rec['content']); ?></p>
        </div>
        <?php if($rec['tweet_img']): ?>
        <div class="MainPostImageBox">
          <object><a href="Detail_Image.php?item=<?php echo $rec['tweets_id']; ?>"><img src="<?php echo IMAGES_DIR.COMPRE_IMG ?><?php echo $rec['tweet_img']; ?>" class="MainPostImage">
          <div class="ImageCover">
            <div class="ImageCaption">Click</div>
          </div>
          </a></object>
        </div>
        <?php endif; ?>
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
          <p class="MainPost"><?php  $text = h($recs['reply_content']); echo nl2br($text);?></p>
        </div>
        <?php if($recs['reply_img']): ?>
        <div class="MainPostImageBox">
          <object><a href="Detail_Image.php?page=<?php echo $recs['reply_img']; ?>"><img src="images/Reply_Compre_img/<?php echo $recs['reply_img']; ?>" class="MainPostImage">
          <div class="ImageCover">
            <div class="ImageCaption">Click</div>
          </div>
          </a></object>
        </div>
        <?php endif; ?>
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
        <?php if(isset($_COOKIE['save']) && $_COOKIE['save'] === 'post'): ?>
        <p><?php echo INTERVAL; ?>秒後に投稿可能になります。</p>
        <?php elseif(empty($_COOKIE['save'])): ?>
        <p>現在投稿可能です。</p>
        <?php endif; ?>
        <textarea name="re_text" rows="8" cols="80" class="TweetPostFormText" id="Textarea" placeholder="投稿内容は200文字以下で、画像は2M以下の.jpgか.pngのみUPできます。"></textarea>
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
    <?php endif; ?>
  </div>
<!-- Insert -->
<?php require_once('./footer.php'); ?>
