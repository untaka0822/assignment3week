<?php
session_start();
require('dbconnect.php');
// index.phpで入力された内容を受取り表示する

// $_SESSION['join']の判定
if (!isset($_SESSION['join'])) { 
    header('Location: login.php');
    exit();
}

// ログイン判定プログラム
if (isset($_SESSION['login_member_id']) && $_SESSION['time']+ 3600 > time()) {
    $_SESSION['time'] = time();
    $sql = 'SELECT * FROM `members` WHERE `member_id`=? ';
    $data = array($_SESSION['login_member_id']);
    $stmt1 = $dbh->prepare($sql);
    $stmt1->execute($data);
    $login_member = $stmt1->fetch(PDO::FETCH_ASSOC);

  } else {
    // ログインしていない場合
    header('Location: login.php');
    exit();
}

// echo '<pre>';
// var_dump($login_user);
// echo '</pre>';

// 完了ボタンを押したとき
if (!empty($_POST)) {
    $title = $_SESSION['join']['title'];
    $contents = $_SESSION['join']['contents'];
    $picture_path = $_SESSION['join']['picture_path'];

  try { 
        // DBへの登録処理
        $sql = 'INSERT INTO `diary` SET `diary_id`=?, `user_id`=?, `title`=?, `contents`=?, `picture_path`=?, `created`=NOW()';
        $data = array($diary_id, $login_member['member_id'], $title, $contents, $picture_path);
        $stmt2 = $dbh->prepare($sql);
        $stmt2->execute($data);

        // unset = SESSIONの情報を削除
        unset($_SESSION['join']);
        
        // 3week.phpへ遷移される
        header('Location: 3week.php');
        exit();
        // エラー時に表示
      } catch(PDOException $e){
              // 例外が発生した場合の処理
        echo 'SQL文実行時のエラー: ' . $e->getMessage();
        exit();
      }
}

?>

<br>
<br>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>NexSeed Diary 新規日記確認</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
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
              <a class="navbar-brand" href="3week.php"><span class="strong-title"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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

 <div class="container" style="text-align: center">
  <div class="row">
    <div style="font-size: 20px;">
      <p> 日記のタイトル : <?php echo $_SESSION['join']['title']; ?></p>
    </div>
    <div style="font-size: 20px;">
      <p> 日記の内容 : <?php echo $_SESSION['join']['contents']; ?></p>
    </div>
    <div style="font-size: 20px;">
      日記の写真 <br>
       <img src="../diary_picture/<?php echo $_SESSION['join']['picture_path']; ?>" style="width: 24%; height: 32%; border-radius: 5px;">
    </div>
    <br>
    <form method="POST" action="">
      <a href="diary.php" class="btn btn-default">戻る</a>
      <input type="hidden" name="hoge" value="fuga"> <!-- 値を表示せずにDBに保存するときはhidden -->
      <input type="submit" value="完了" class="btn btn-default">
    </form>
  </div>
 </div>
</body>
</html>
