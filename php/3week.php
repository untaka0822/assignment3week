<?php
  session_start();
  require('dbconnect.php');

  if (!isset($_SESSION['login_member_id'])) {
    header('Location: login.php');
    exit();
  }
  
  // ログイン判定
  if (isset($_SESSION['login_member_id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt1 = $dbh->prepare($sql);
  $stmt1->execute($data);
  $members = $stmt1->fetch(PDO::FETCH_ASSOC);


  } else {
    // ログインしていない場合
    header('Location: login.php');
    exit();
  }

  // echo '<pre>';
  // var_dump($members);
  // echo '</pre>';

  // ページング機能
  $page = '';

  // パラメータのページ番号を取得
  if (isset($_REQUEST['page'])) {
      $page = $_REQUEST['page'];
  }

  // パラメータが存在しない場合はページ番号を1とする
  if ($page == '') {
      $page = 1;
  }

  // 1以下のイレギュラーな数値が入ってきた場合はページ番号を1とする
  $page = max($page, 1); // max ()の中に入った一番大きい数を出力 例: echo max(1, 10, 15, 1.9, 12.5) . '<br>'; の場合 15　が出力される

  // データの件数から最大ページ数を計算する
  $sql = 'SELECT COUNT(*) AS `cnt` FROM `diary` WHERE `user_id`=?'; // 復習: tweetsテーブルからデータ全件の件数を数えて取得
  $data = array($_SESSION['login_member_id']);
  $diary_stmt = $dbh->prepare($sql);
  $diary_stmt->execute($data);
  $diarys = $diary_stmt->fetch(PDO::FETCH_ASSOC);

  $max_page = ceil($diarys['cnt'] / 5); // ceil 小数点以下を切り上げ　(php 小数点　関数でggr)
  // ページング機能調整
  $max_page = $max_page + 1;
  // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数とする
  $page = min($page, $max_page); // min 最小値を返す
  $page = max($page, 1); // max 0以下のページの際

  // 1ページに表示する件数分だけデータを取得する
  $page = ceil($page);
  // 1~ , 6~ , 11~のスタートにする
  $start = ($page - 1) * 5;

  // お気に入り!ボタンが押された時
  if (!empty($_POST && $_POST['submit-type'] == 'like')) {
    if ($_POST['like'] == 'like') {
      // いいね！された時の処理
      $sql = 'INSERT INTO `likes` SET `member_id`=?, `diary_id`=?'; // SETの時は , WHEREの時は AND
      $data = array($_SESSION['login_member_id'], $_POST['like_diary_id']);
      $like_stmt = $dbh->prepare($sql);
      $like_stmt->execute($data);
      // header('Location: 3week.php'); あってもいい
      // exit();

    } else {
      //いいね！取り消しされた時の処理
      $sql = 'DELETE FROM `likes` WHERE `member_id`=? AND `diary_id`=?';
      $data = array($_SESSION['login_member_id'], $_POST['like_diary_id']);
      $like_stmt = $dbh->prepare($sql);
      $like_stmt->execute($data);
      // header('Location: 3week.php'); なくてもいい
      // exit();

    }
  }

  // 削除ボタンを押したとき
  if (!empty($_POST && $_POST['submit-type'] == 'delete')) {

    $sql = 'DELETE FROM `diary` WHERE `diary_id`=?';
    $data = array($_REQUEST['diary_id']);
    $re_stmt = $dbh->prepare($sql);
    $re_stmt->execute($data); // 大事！ 削除機能は取得sql文より前に書く 削除 → 取得 → 表示

    }
  // 日記編集ボタンを押したとき
  if (!empty($_POST && $_POST['submit-type'] == 'edit')) {

      $_SESSION['join'] = $_POST;
      header('Location: edit.php');
      exit();
    }

  // ユーザー編集ボタンが押された時
  if (!empty($_POST && $_POST['submit-type'] == 'edit-user')) {

      $_SESSION['join'] = $_POST;
      header('Location: edit_user.php');
      exit();
  }

  // 退会ボタンが押された時
  if (!empty($_POST && $_POST['submit-type'] == 'leave')) {

      $_SESSION['join'] = $_POST;
      header('Location: leave.php');
      exit();
  }

    // ログインしているユーザの日記を全件表示
    // $sql = sprintf('SELECT t.*, m.nick_name, m.picture_path FROM `tweets` t LEFT JOIN `members` m ON t.member_id=m.member_id ORDER BY t.created DESC LIMIT %d, 5', $start); // sprintf 引数に指定した値を指定の形式にフォーマットした文字列を取得 %d 整数値
    $sql = sprintf('SELECT * FROM `diary` WHERE `user_id`=? ORDER BY diary.created DESC LIMIT %d, 5', $start); // 最新順 sprintf()で文字列を使用可能にする
    $data = array($_SESSION['login_member_id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>NexSeed Diary</title>
  <link rel="stylesheet" type="text/css" href="../css/3week.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../resource/lightbox.css" media="screen,tv" />
  <script type="text/javascript" src="../resource/lightbox_plus.js"></script>
</head>

<body>
<div id="a-box">
  <form method="POST" action="">
  <input type="submit" value="退会する" class="btn btn-danger" style="padding: 5px 20px; border-radius: 5px;">
  <input type="hidden" name="member_id" value="<?php echo $_SESSION['login_member_id']; ?>">
  <input type="hidden" name="submit-type" value="leave">
  <a href="3week.php" style="color: yellow; font-size: 40px; margin-left: 365px">NexSeed Diary </a><a class="btn btn-danger" href="logout.php" style="margin-left: 300px;">ログアウト</a>
  </form>
</div>

  <div id="b-box">
    <div class="content1">
      <div class="diarys">
      <!-- ログインしているユーザーの日記を全件表示 -->
      <?php while($diary = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="diary">
              <a href="detail_diary.php?diary_id=<?php echo $diary['diary_id']; ?>" style="font-size: 25px;"><?php echo $diary['title']; ?></a><br>
              <a href="../diary_picture/<?php echo $diary['picture_path']; ?>" rel="lightbox"><img src="../diary_picture/<?php echo $diary['picture_path']; ?>"" class="effectable" style="width: 24%; height: 32%; border-radius: 5px;"></a>
               <!-- hrefの後の?の後を次のページで取得するのが$_REQUEST $_REQUEST $_REQUEST $_REQUEST $_REQUEST-->

              <p class="date"><?php echo $diary['created']; ?></p>
            <form name="form2" method="POST" action="" onsubmit="return submitChk()"> <!-- onsubmitでダイアログの表示 -->
              <input class="btn-xs btn-info" type="submit" name="delete" value="削除" style="margin-bottom: 10px;">
              <input type="hidden" name="diary_id" value="<?php echo $diary['diary_id']; ?>">
              <input type="hidden" name="submit-type" value="delete">
            </form>
            <form name="form3" method="POST" action="">
              <input class="btn-xs btn-success" type="submit" name="edit" value="編集" style="margin-bottom: 10px;">
              <input type="hidden" name="submit-type" value="edit">
              <input type="hidden" name="diary_id" value="<?php echo $diary['diary_id']; ?>">
              <input type="hidden" name="title" value="<?php echo $diary['title']; ?>">
              <input type="hidden" name="contents" value="<?php echo $diary['contents']; ?>">
              <input type="hidden" name="picture_path" value="<?php echo $diary['picture_path']; ?>">
              <input type="hidden" name="created" value="<?php echo $diary['created']; ?>">
            </form>
            <?php
            // お気に入り！をしているかの判定処理
              $sql = 'SELECT * FROM `likes` WHERE `member_id`=? AND `diary_id`=?';
              $data = array($_SESSION['login_member_id'], $diary['diary_id']);
              $is_like_stmt = $dbh->prepare($sql);
              $is_like_stmt->execute($data);

            ?>
            <form name="form1" method="POST" action=""> 
              <?php if($is_like_stmt = $is_like_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <!-- お気に入りデータが存在する(削除ボタン表示) -->
                <input type="hidden" name="like" value="unlike">
                <input type="hidden" name="like_diary_id" value="<?php echo $diary['diary_id']; ?>">
                <input type="hidden" name="submit-type" value="like">
                <input type="submit" value="Return" class="btn-xs btn-danger">
              <?php else: ?>
                <!-- お気に入りデータが存在しない(いいねボタン表示) -->
                <input type="hidden" name="like" value="like">
                <input type="hidden" name="like_diary_id" value="<?php echo $diary['diary_id']; ?>">
                <input type="hidden" name="submit-type" value="like">
                <input type="submit" value="Favorite" class="btn-xs btn-primary">
              <?php endif; ?>
            </form>
        </div>
      <?php  
        // echo '<pre>';
        // var_dump($members);
        // echo '</pre>';
      ?>
      <?php endwhile; ?>

      <ul class="paging">
          <?php
            $word = '';
            if (isset($_GET['search_word'])) {
                $word = '&search_word=' . $_GET['search_word'];
            }
          ?>
          <!-- ページングボタンが押された時に変わる -->
          <div class="col-xs-6 col-lg-offset-2">
          <p style="color: black;"><?php echo $page . 'ページ目'; ?></p>
            <?php if($page > 1): ?>
                <a href="3week.php?page=<?php echo $page - 1; ?><?php echo $word; ?>" class="btn btn-warning">前</a><!-- $_GETを使用 -->
            <?php else: ?>
                <a href="" class="btn btn-default">前</a>
            <?php endif; ?>

            &nbsp;&nbsp;|&nbsp;&nbsp;
            <?php if($page < $max_page - 1): ?>
                <a href="3week.php?page=<?php echo $page + 1; ?><?php echo $word; ?>" class="btn btn-warning">次</a><!-- $_GETを使用 -->
            <?php else: ?>
                <a href="" class="btn btn-default">次</a>
            <?php endif; ?>
          </div>
      </ul>
     </div>
    </div>
  </div>
  <div id="c-box" style="text-align: center">
    <div class="content2">
    <a href="../member_picture/<?php echo $members['picture_path']; ?>" rel="lightbox"><img src="../member_picture/<?php echo $members['picture_path']; ?>" style="width: 100%; height: 72%; border-radius: 5px" class="effectable"></a>
      <?php
          date_default_timezone_set('Asia/Tokyo'); // 時間を日本に設定
          $time = intval(date('H'));
          if (6 <= $time && $time <= 11) { // 06:01～11:00の時間帯のとき ?>
          <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: black; border-radius: 15px;">Goodmorning!</p>
          <?php } elseif (11 <= $time && $time <= 17) { // 11:01〜17:59の時間帯のとき ?>
          <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: black; border-radius: 15px;">Hello!</p>
          <?php } else { // それ以外の時間帯のとき (18:00 〜 05:59の時間帯) ?>
          <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: black; border-radius: 15px;">Good evening!</p>
      <?php } ?>
    <a href="detail_user.php?member_id=<?php echo $members['member_id'];　// $_REQUESTを使用する ?>"><p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: black; border-radius: 15px;"><?php echo $members['nick_name']; ?>さん</p></a>
      <div class="data1"><a class="history" href="#">
      <?php
        // 日記の最新から3個目まで表示
         echo date("Y年m月", mktime( 
          0, // 時
          0, // 分
          0, // 秒
          date("m"),
          date("d"),
          date("Y")
          ));
      ?>の日記
      </a></div><br>
      <div class="data1"><a class="history" href="#">
      <?php
         echo date("Y年m月", mktime(
          0, // 時
          0, // 分
          0, // 秒
          date("m") -1,
          date("d"),
          date("Y")
          ));
      ?>の日記
      </a></div><br>
      <div class="data1"><a class="history" href="#">
      <?php
         echo date("Y年m月", mktime(
          0, // 時
          0, // 分
          0, // 秒
          date("m") -2,
          date("d"),
          date("Y")
          ));
      ?>の日記
      </a></div><br>
    </div>
    <a href="diary.php" class="btn btn-warning">日記を新しく追加する</a>
    <a href="edit_user.php" class="btn btn-success" style="margin-top: 10px;">ユーザー情報を編集する</a>
    
  </div>
  <div id="d-box">
   <h5 style="color: white; font-size: 13px">Copyright @ Hayato.T inc All Rights Reserved</h5>
  </div>
  
 <script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "本当に削除してもいいですか?\n\n削除されない方はキャンセルボタンを押してください");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
 </script>
</body>
</html>