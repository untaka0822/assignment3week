<?php
	session_start();
  require('dbconnect.php');

  if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
  }

  $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  $login_member = $stmt->fetch(PDO::FETCH_ASSOC);

  echo '<br>';
  echo '<br>';
  echo '<br>';
  echo '<br>';
  // echo '<pre>';
  // var_dump($login_member);
  // echo '</pre>';

  // 退会するボタンが押された時
  if (!empty($_POST)) {

    $sql = 'DELETE FROM `members` WHERE `member_id`=?';
    $data = array($_SESSION['login_member_id']);
    $stmt1 = $dbh->prepare($sql);
    $stmt1->execute($data);

    $sql = 'DELETE FROM `diary` WHERE `user_id`=?';
    $data = array($_SESSION['login_member_id']);
    $stmt2 = $dbh->prepare($sql);
    $stmt2->execute($data);

    $sql = 'DELETE FROM `likes` WHERE `member_id`=?';  
    $data = array($_SESSION['login_member_id']);
    $like_stmt = $dbh->prepare($sql);
    $like_stmt->execute($data);

    session_destroy(); // $_SESSIONの情報を削除
      
    // COOKIE情報も削除
    setcookie('email', '', time() - 3000);
    setcookie('password', '', time() - 3000);

    header('Location: thanks_delete.php');
    exit();


  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>

  <meta charset="utf-8">
  <title>退会画面</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/leave.css">

</head>
<body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div id="all-box">
    <div id="a-box">
      <img src="../member_picture/<?php echo $login_member['picture_path']; ?>" style="width: 27%; height: 36%;">
    </div>
    <div id="b-box">
      <p>会員番号 : <?php echo $login_member['member_id']; ?></p>
      <p>会員名 : <?php echo $login_member['nick_name']; ?>様</p>
      <p>メールアドレス : <?php echo $login_member['email']; ?></p>
      <form method="POST" action="" onsubmit="return submitChk()">
        <input type="hidden" name="member_id" value="<?php echo $_SESSION['login_member_id']; ?>">
        <input type="hidden" name="like_tweet_id" value="<?php echo $like_tweet_id; ?>">
        <input type="submit" value="退会する" class="btn btn-default">
        <a href="3week.php" class="btn btn-info">トップへ戻る</a>
      </form>
    </div>
  </div>
  <script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "本当に退会しますか?\n\n退会されない方はキャンセルボタンを押してください");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
 </script>
</body>
</html>