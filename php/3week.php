<?php
  session_start();
  require('dbconnect.php');
  
  if (isset($_SESSION['login_member_id']) && $_SESSION['time']+ 3600 > time()) {
  $_SESSION['time'] = time();
  $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt1 = $dbh->prepare($sql);
  $stmt1->execute($data);
  // $login_user = $stmt1->fetch(PDO::FETCH_ASSOC);

  } else {
    // ログインしていない場合
    header('Location: login.php');
    exit();
  }

  // if (!empty($_POST['tweet'])) {
  //   if ($_POST['tweet'] != '') {
  //     // DBへの登録処理
  //     $sql = 'INSERT INTO `diary` SET `diary_id`=?,
  //                                      `user_id`=?,
  //                                      `title`=?,
  //                                      `contents`=?,
  //                                      `created`=NOW()';
  //     $data = array($_POST['diary_id'], $_SESSION['login_member_id'], $_POST['title'], $_POST['contents']);
  //     $stmt = $dbh->prepare($sql);
  //     $stmt->execute($data);

  //     header('Location: top.php');
  //     exit();
  //   }
  // }

  $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  $members = $stmt->fetch(PDO::FETCH_ASSOC);

  // echo '<pre>';
  // var_dump($members);
  // echo '</pre>';

  $sql = 'SELECT * FROM `diary` WHERE `user_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);

  if (!empty($_POST)) {

    $sql = 'DELETE FROM `diary` WHERE `diary_id`=?';
    $data = array($_REQUEST['diary_id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    }

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
  <h2 style="color: yellow; font-size: 40px; margin-right: -400px">NexSeed Diary <a class="btn btn-primary" href="logout.php" style="margin-left: 300px;">ログアウト</a>
  </h2>
</div>

  <div id="b-box">
    <div class="content1">
      <div class="diarys">
      <?php while($diary = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="diary">
            <a href="#"><?php echo $diary['title']; ?></a><br>
            <p class="date"><?php echo $diary['created']; ?></p>
            <form name="form3" method="POST" action="" onsubmit="return submitChk()">
              <input class="btn-xs btn-info" type="submit" name="delete" value="削除" style="margin-bottom: 10px;">
              <input type="hidden" name="diary_id" value="<?php echo $diary['diary_id']; ?>">
            </form>
        </div>
      <?php endwhile; ?>
      <!-- ページング機能必要か？？？ -->
      </div>
    </div>
  </div>

  <div id="c-box">
    <div class="content2">
    <img src="../member_picture/<?php echo $members['picture_path']; ?>" style="width: 100%;
  height: 64%;">
      <?php
          date_default_timezone_set('Asia/Tokyo');
          $time = intval(date('H'));
          if (6 <= $time && $time <= 11) { // 06:01～11:00の時間帯のとき ?>
          <p style="font-size: 20px; margin-top: 25px;">おはようございます、<?php echo $members['nick_name']; ?>さん</p>
          <?php } elseif (11 <= $time && $time <= 17) { // 11:01〜17:59の時間帯のとき ?>
          <p style="font-size: 20px; margin-top: 25px;">こんにちわ、<?php echo $members['nick_name']; ?>さん</p>
          <?php } else { // それ以外の時間帯のとき (18:00 〜 05:59の時間帯) ?>
          <p style="font-size: 20px; margin-top: 25px;">こんばんわ、<?php echo $members['nick_name']; ?>さん</p>
      <?php } ?>
      <p class="data1"><a class="history" href="">2016年10月の日記</a></p><br>
      <p class="data2"><a class="history" href="">2016年09月の日記</a></p><br>
      <p class="data2"><a class="history" href="">2016年08月の日記</a></p>
    </div>
    <a href="add.php">日記を新しく追加する</a> 
  </div>
  <div id="d-box">
   <h5 style="color: white; font-size: 13px">Copyright @ NexSeed inc All Rights Reserved</h5>
  </div>
</div>
<script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "削除してもよろしいですか？\n\n削除したくない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>
</body>
</html>