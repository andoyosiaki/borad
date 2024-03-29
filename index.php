<?php
session_start();
require_once __DIR__."/core/dbconect.php";
require "function/functions.php";

$statement = $db->query('SELECT * FROM tweets INNER JOIN userinfo on userinfo.user_id=tweets.author_id order by tweets.create_at DESC');

?>
<?php require_once('./head.php'); ?>
<!-- TweetPostSection & Form -->
    <div class="TweetPostSection">
      <?php if(!empty($_SESSION)): ?>
      <div class="TweetPostFormBox">
        <form class="TweetPostForm" action="Posts_Validate.php" method="post" enctype="multipart/form-data">
          <?php if(isset($_COOKIE['save']) && $_COOKIE['save'] === 'post'): ?>
          <p><?php echo INTERVAL ?>秒後に投稿可能になります。</p>
          <?php elseif(empty($_COOKIE['save'])): ?>
          <p>現在投稿可能です。</p>
          <?php endif; ?>
          <textarea class="form-control" name="text" rows="5" placeholder="投稿内容は200文字以下で,画像は2M以下の.jpgか.pngのみUPできます。" id="Textarea"></textarea>
          <div class="form-group mt-1 file">
            <label for="File" id="LabelFile"><i class="far fa-image fa-2x "></i></label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">
            <input type="file" name="image" id="File" name="upfile" id="upfile" accept="image/*">
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
      <?php endif; ?>
    </div>
  </header>
<!-- TweetPostSection & Form -->
<!-- main -->
  <main>
    <?php while($rec = $statement->fetch()): ?>
    <article class="MainArticle">
      <div class="MainIconBox">
        <object><a href="Mypage.php?page=<?php echo h($rec['user_id']); ?>">
          <img src="<?php echo IMAGES_DIR.P_COMPRE_IMG ?><?php echo h($rec['icon']); ?>" alt="" class="MinIcon">
        </object></a>
      </div>
      <div class="MainAuthorPostBox">
        <a href="Reply_Posts.php?page=<?php echo h($rec['tweets_id']);  ?>">
          <div class="MainAuthorName">
            <div class="NameBox">
              <object><a href="Mypage.php?page=<?php echo h($rec['user_id']); ?>"><?php echo h($rec['name']); ?></a></object>
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
            <object><a href="Detail_Image.php?item=<?php echo h($rec['tweets_id']); ?>"><img src="<?php echo IMAGES_DIR.COMPRE_IMG ?><?php echo h($rec['tweet_img']); ?>" class="MainPostImage">
            <div class="ImageCover">
              <div class="ImageCaption">Click</div>
            </div>
            </a></object>
          </div>
          <?php endif; ?>
        </a>
        <div class="TinkerBox">
          <div class="ReplyIconBox">
            <object><a href="Reply_Posts.php?page=<?php echo h($rec['tweets_id']);  ?>"><i class="far fa-comment fa-lg "></i></a></object>
            <?php if($rec['maxpost'] > 0): ?><span class="MaxReplayPost"><?php echo $rec['maxpost']; ?></span><?php endif; ?>
          </div>
          <?php if(isset($_SESSION['id']) && $_SESSION['id'] === $rec['author_id']): ?>
          <div class="DeleteIconBox">
            <i class="far fa-trash-alt" id="<?php echo h($rec['tweets_id']); ?>"></i>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <script>
      $('#<?php echo h($rec['tweets_id']); ?>').click(function(){
          if(!confirm('本当に削除しますか？')){
              return false;
          }else{
              location.href = 'Delete_Post.php?from_main=<?php echo h($rec['tweets_id']); ?>';
          }
      });
    </script>
    <?php endwhile; ?>
  </main>
<!-- main -->
<?php require_once('./footer.php'); ?>
