<?php
session_start(); // $_SESSIONの使用条件
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

// 各入力値を保持する変数を用意
$title = '';
$contents     = '';

// エラー格納用の配列を用意
$errors = array();

// 『確認画面へ』ボタンが押されたとき
if (!empty($_POST)) {

    $title = $_POST['title'];
    $contents = $_POST['contents'];
        // タイトルのフォームが空のため、画面にエラーを出力
    if ($title == '') {  
        $errors['title'] = 'blank'; // blank部分はどんな文字列でも良い
    }

    if ($contents == '') {
        $errors['contents'] = 'blank';
    }
    
      header('Location: check_diary.php');
      exit();
}
    // header('Location: check_diary.php');
    // exit();

?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NexSeed Diary 新しい日記</title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->

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
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-twitter-square"></i> NexSeed Diary</span></a>
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

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>日記の登録</legend>
        <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data"><!-- enctypeがないと$_FILESが作成されない-->
          <!-- ニックネーム -->
          <div class="form-group">
            <label class="col-sm-4 control-label">日記のタイトル</label>
            <div class="col-sm-8">
              <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" placeholder="例： Harry Potter">
              <?php if(isset($errors['title']) && $errors['title'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                日記のタイトルを入力してください
              </p>
               <?php endif; ?>
            </div>
          </div>
          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">日記の内容</label>
            <div class="col-sm-8">
              <input type="contents" name="contents" class="form-control" value="<?php echo $contents; ?>" placeholder="例： 今日はクディッチの試合だった"> 
              <?php if(isset($errors['contents']) && $errors['contents'] == 'blank'): ?> <!-- コロン構文 -->
                <p style="color: red; font-size: 10px; margin-top: 2px;">日記の内容を入力してください</p>
              <?php endif; ?>
            </div>
          </div>

          <input type="submit" class="btn btn-default" value="確認画面へ">

        </form>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>



  
