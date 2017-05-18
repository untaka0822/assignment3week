<?php
session_start();
require('dbconnect.php');

// 各入力値を保持する変数を用意
$title = '';
$contents = '';

// エラー格納用の配列を用意
$errors = array();

// 確認画面へボタンが押されたとき
if (!empty($_POST)) {

    $title = $_POST['title'];
    $contents = $_POST['contents'];

    // ページ内バリデーション
    if ($title == '') {
        // ニックネームのフォームが空のため、画面にエラーを出力
        $errors['title'] = 'blank'; // blank部分はどんな文字列でも良い
    }

    if ($contents == '') {
        $errors['contents'] = 'blank';
    }

    // エラーがなかった場合の処理
    if (empty($errors)) {

      $_SESSION['join'] = $_POST; // joinは何でも良い
      header('Location: check_diary.php');
      exit();
     }
  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NexSeed Diary 新規日記登録</title>

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
              <a class="navbar-brand" href="3week.php"><span class="strong-title"><i class="fa fa-twitter-square"></i> NexSeed Diary</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php" class="btn-xs btn-danger" style="color: white;">ログアウト</a></li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

<!-- formのmethod="POST" action inputのsubmit 大事 -->
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>新しい日記の登録</legend>
        <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data"><!-- enctypeがないと$_FILESが作成されない-->
          <!-- ニックネーム -->
          <div class="form-group">
            <label class="col-sm-4 control-label">日記のタイトル</label>
            <div class="col-sm-8">
              <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" placeholder="例： 神谷の一日">
              <?php if(isset($errors['title']) && $errors['title'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                タイトルを入力してください
              </p>
               <?php endif; ?>
            </div>
          </div>
          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">日記の内容</label>
            <div class="col-sm-8">
              <textarea type="contents" cols="100" rows="4" name="contents" class="form-control" value="<?php echo $contents; ?>" placeholder="例： スタバに行ってきたよ"></textarea> 
              <?php if(isset($errors['contents']) && $errors['contents'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                日記の内容を入力してください
              </p>
              <?php endif; ?>
            </div>
          </div>
          <div class="row" style="text-align: center;">
          <a class="btn btn-info" href="3week.php" style="margin-right: 20px">一覧へ戻る</a>
          <input type="submit" class="btn btn-success" value="確認画面へ">
          </div>
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


