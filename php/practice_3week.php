<?php
  session_start();
  require('dbconnect.php');

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
  // var_dump($_SESSION['login_member_id']);
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

  //1以下のイレギュラーな数値が入ってきた場合はページ番号を1とする
  $page = max($page, 1); // max()の中に入った一番大きい数を出力 例: echo max(1, 10, 15, 1.9, 12.5) . '<br>'; の場合 15 が出力される

  // データの件数から最大ページ数を計算する
  $sql = 'SELECT COUNT(*) AS `cnt` FROM `diary` WHERE `user_id`=?';
  // tweetテーブルからデータ全件の件数を数えて取得
  $data = array($_SESSION['login_member_id']);
  $diary_stmt = $dbh->prepare($sql);
  $diary_stmt->execute($data);
  $diarys = $diary_stmt->fetch(PDO::FETCH_ASSOC);

  $max_page - ceil($diarys['cnt'] / 5); // ceil 小数点 以下を切り上げ
  // ページング機能調整
  $max_page = $max_page + 1;
  // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数とする
  $page = min($page, $max_page); // min 最小値を返す
  $page = max($page, 1); // max 0以下のページの際

  // 1ページに表示する件数文だけデータを取得する
  $page = ceil($page);
  // 1~, 6~, 11~ スタートにする
  $start = ($page - 1) * 5;
  // 削除ボタンを押した時
  if (!empty($_POST && $_POST['submit-type'] == 'delete')) {
    
    $sql = 'DELETE FROM `diary` WHERE `diary_id`=?';
    $data = array($_REQUEST['diary_id']);
    $re_stmt = $dbh->prepare($sql);
    $re_stmt->execute($data); // 大事！ 削除機能は取得sql文より前に書く 削除 → 取得 → 表示
  }
  // 編集ボタンを押した時
  if (!empty($_POST && $_POST['submit-type'] == 'edit')) {
      $_SESSION['join'] = $_POST;
      header('Location: edit.php');
      exit();
  }

  // ログインしているユーザーの日記を全件表示
  // 引数に指定した値を指定の形式にフォーマットした文字列を取得 % 整数値を使用可能にする d 10進数(0~9)
  $sql = sprintf('SELECT * FROM `diary` WHERE `used_id`=? ORDER BY diary.created DESC LIMIT %d, 5', $start); // 最新順
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
</head>
<body>
<div id="a-box">
  <a href="3week.php" style="color: yellow; font-size: 40px; margin-left: 375px">NexSeed Diary </a><a class="btn btn-danger" href="logout.php" style="margin-left: 300px;">ログアウト</a>
</div>

<div id="b-box">
  <div class="content1">
    <div class="diarys">
    <!-- ログインしているユーザーの日記を全件表示 -->
      <?php while($diary = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="diary">
          <a href="detail_diary.php"><?php echo $diary['title']; ?></a><br>
          <p class="date"><?php echo $diary['created']; ?></p>
        <form name="form2" method="POST" action="" onsubmit="return submitChk()"> <!-- onsubmitでダイアログの表示 -->
          <input class="btn-xs btn-info" type="submit" name="delete" value="削除" style="margin-bottom: 10px;">
          <input type="hidden" name="diary_id" value="<?php echo $diary['diary_id']; ?>">
          <input type="hidden" name="submit-type" value="delete">
        </form>

        <form name="form3" method="POST" action="">
          <input class="btn-xs btn-success" type="submit" name="delete" value="編集" style="margin-bottom: 10px;">
          <input type="hidden" name="submit-type" value="edit">
          <input type="hidden" name="diary_id" value="<?php echo $diary['diary_id']; ?>">
          <input type="hidden" name="title" value="<?php echo $diary['title']; ?>"> 
          <input type="hidden" name="contents" value="<?php echo $diary['contents']; ?>">
          <input type="hidden" name="created" value="<?php echo $diary['created']; ?>">
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
          <p style="color: white;"><?php echo $page . 'ページ目' . ' / ' . $max_page . 'ページ'; ?></p>
            <?php if ($page > 1): ?>
              <button style="background-color: yellow"><a href="3week.php?page=<?php echo $page - 1; ?><?php echo $word; ?>">前</a></button>
            <?php else: ?>
              <button>前</button>
            <?php endif; ?>

            &nbsp;&nbsp;|&nbsp;&nbsp;
            <?php if ($page < $max_page - 1): ?>
              <button style="background-color: yellow;"><a href="3week.php?page=<?php echo $page + 1; ?><?php echo $word; ?>">次</a></button>
            <?php else: ?>
              <button>次</button>
            <?php endif; ?>
        </div>
      </ul>
    </div>
  </div>
</div>

<div id="c-box" style="text-align: center">
  <div class="content2">
    <img src="../member_picture/<?php echo $members['picture_path']; ?>" style="width: 100%; height: 72%; border-radius: 5px;">
    <?php
      date_default_timezone_set('Asia/Tokyo');
      $time = intval(date('H'));
      if (6 <= $time && $time <= 11) { // 06:01~11:00の時間帯の時 ?>
        <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: deepskyblue; border-radius: 15px;">Good morning! <?php echo $members['nick_name']; ?></p>
        <?php } elseif (11 <= $time && $time <= 17) { // 11:01~18:00の時間帯の時 ?>
        <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: deepskyblue; border-radius: 15px;">Hello! <?php echo $members['nick_name']; ?></p>
        <?php } else { // それ以外の時間帯の時 ?>
        <p style="font-size: 20px; margin-top: 25px; text-align: center; background-color: white; color: deepskyblue; border-radius: 15px;">Good evening! <?php echo $members['nick_name']; ?></p>
      <?php } ?>
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
  </div>
  <a href="diary.php" class="btn btn-warning">日記を新しく追加する</a>
 </div>
 <div id="d-box">
  <h5 style="color: white; font-size: 13px">Copyright @ NexSeed inc All Rights Reserved</h5>
 </div>

 <script>

  /*
    * 確認ダイアログの返り値によりフォーム送信
  */
  function submitChk () {
    /* 確認ダイアログ表示 */
    var flag = confirm ("本当に削除してもよろしいですか？\n\n削除されない方はキャンセルボタンを押してください");
    /* send flg が TRUEなら送信、FALSEなら送信しない */
    return  flag;
  }

 </script>
</body>
</html>