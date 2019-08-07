<?php
session_start();
require_once(__DIR__.'/core/dbconect.php');
require(__DIR__.'/function/functions.php');
ini_set('display_errors',1);

//非ログインユーザーのアクセス除外
if(isset($_SESSION['id'])){
  $statment = $db->prepare('SELECT * FROM userinfo WHERE user_id=?');
  $statment->execute(array(
    $_REQUEST['page']
  ));
  $rec = $statment->fetch();

    //存在しないユーザーページにアクセスしない処理
  if($_REQUEST['page'] === $rec['user_id']){
    $statments = $db->prepare('SELECT * FROM tweets INNER JOIN userinfo on userinfo.user_id=tweets.author_id WHERE author_id=? order by tweets.create_at DESC');
    $statments->execute(array(
      $_REQUEST['page']
    ));
  }else {
  header('Location:index.php');exit();
  }
}else {
  header('Location:index.php');exit();
}

 ?>
<?php require_once('./head.php'); ?>

<!-- ProfileSection -->
<div class="ProfileSectionWrapper">
        <div class="ProfileSection">
          <div class="ProfileBox">
            <div class="ProfileInnerBox">
              <div class="UserIconBox">
                <?php if($rec['icon'] === null): ?>
                <p class="m-1"><img src="<?php echo IMAGES_DIR.P_COMPRE_IMG ?>.'0.png'" class="UserIcon"></p>
                <?php else: ?>
                <p class="m-1"><img src="<?php echo IMAGES_DIR.P_COMPRE_IMG ?><?php echo $rec['icon']; ?>" class="UserIcon"></p>
                <?php endif; ?>
                <p class="UserName"><?php echo $rec['name']; ?></p>
              </div>
              <div class="S-IntoroductionBox">
                <?php if($rec['intoroduction'] === null): ?>
                <p>「プロフィールを編集する」から自己紹介文とアイコンを作成してください。</p>
                <?php endif; ?>
                <p><?php echo $rec['intoroduction']; ?></p>
              </div>
            </div>
            <?php if($_SESSION['id'] === $_REQUEST['page']): ?>
            <div class="ProfileEditTriggerBox">
              <button type="button" class="btn  bg-warning btn-outline-dark ProfileEditTrigger">プロフィールを編集する</button>
            </div>
            <div class="ProfileEditBox">
              <form class="" action="Profile_Update.php" method="post" enctype="multipart/form-data">
                <textarea name="intorotext" rows="3" cols="70" placeholder="自己紹介"><?php echo $rec['intoroduction'] ?></textarea>
                <div class="form-group mt-1 file">
                  <label for="File" id="LabelFile"><i class="far fa-smile fa-2x"></i></label>
                  <input type="file" name="image" id="File">
                  <input type="hidden" name="hidden_img" value="<?php echo $rec['icon']; ?>">
                </div>
                <button type="submit" class="btn btn-lg FormBtn">　送　信　</button>
              </form>
              <div class="DeleteBox">
                <a  id="delete">退会する</a>
              </div>
            </div>
          </div>
          <script>
            $('#delete').click(function(){
                if(!confirm('本当に削除しますか？')){
                    return false;
                }else{
                    location.href = 'Delete_Post.php?acount=<?php echo $rec['user_id']; ?>';
                }
            });
          </script>
            <?php endif; ?>
        </div>
</div>
<!-- ProfileSection -->
<!-- UserPostssection -->
<main>
  <?php while($rec = $statments->fetch()): ?>
  <article class="MainArticle">
    <div class="MainIconBox">
      <a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>">
        <img src="<?php echo IMAGES_DIR.P_COMPRE_IMG ?><?php echo $rec['icon']; ?>" alt="" class="MinIcon">
      </a>
      <div class="NameBox">
        <a href="Mypage.php?page=<?php echo  $rec['user_id']; ?>"><?php echo h($rec['name']); ?></a>
      </div>
    </div>
    <div class="MainAuthorPostBox">
      <div class="MainAuthorName">
        <div class="TimeBox">
          <time><?php echo times($rec['create_at']); ?></time>
        </div>
      </div>
      <div class="MainPostBox">
        <p><?php echo newline($rec['content']); ?></p>
      </div>
      <?php if($rec['tweet_img']): ?>
        <div class="MainPostImageBox">
          <object><a href="Detail_Image.php?item=<?php echo $rec['tweets_id']; ?>"><img src="<?php echo IMAGES_DIR.COMPRE_IMG ?><?php echo $rec['tweet_img']; ?>" class="MainPostImage">
          <div class="ImageCover">
            <div class="caption">Click</div>
          </div>
          </a></object>
        </div>
      <?php endif; ?>
      <div class="TinkerBox">
        <div class="ReplyIconBox">
          <a href="Reply_Posts.php?page=<?php echo $rec['tweets_id'] ;  ?>"><i class="far fa-comment fa-lg "></i></a>
          <?php if($rec['maxpost'] > 0): ?><span class="MaxReplayPost"><?php echo $rec['maxpost']; ?></span><?php endif; ?>
        </div>
        <?php if($_SESSION['id'] === $rec['author_id']): ?>
        <div class="DeleteIconBox">
            <i class="far fa-trash-alt" id="<?php echo $rec['tweets_id']; ?>"></i>
        </div>
        <?php endif; ?>
      </div>
      </p>
    </div>
    <script>
      $('#<?php echo $rec['tweets_id']; ?>').click(function(){
          if(!confirm('本当に削除しますか？')){
              return false;
          }else{
              location.href = 'Delete_Post.php?page=<?php echo $rec['tweets_id']; ?>';
          }
      });
    </script>
  </article>
  <?php endwhile; ?>
</main>
   </body>
 </html>
