<?php
session_start();
require('dbconnect.php');

// ログイン判定
if (isset($_SESSION['login_member_id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt1 = $dbh->prepare($sql);
  $stmt1->execute($data);

  } else {
    // ログインしていない場合
    header('Location: login.php');
    exit();
  }


  // 編集したいツイートデータの取得・表示
  // 投稿一件取得
  // echo '<br>';
  // echo '<br>';
  // echo '<br>';
  // echo '<pre>';
  // var_dump($_SESSION['join']);
  // echo '</pre>';

  // 更新ボタンが押された際
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
      
      // UPDATE文
      $sql = 'UPDATE `diary` SET `title`=?, `contents`=?, `created`=NOW() WHERE `diary_id`=?'; // WHERE 重要
      $data = array($title, $contents, $_SESSION['join']['diary_id']);
      $re_stmt = $dbh->prepare($sql);
      $re_stmt->execute($data);

      header('Location: 3week.php');
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

    <title>Diary Edit</title>

    <!-- Bootstrap -->
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
                <li><a href="logout.php" class="btn-xs btn-danger" style="color: white;">ログアウト</a></li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4 content-margin-top">
        <form method="POST" action="" class="form-horizontal" role="form">
          <h3>Edit Diary</h3>
	        <div class="msg">
	          Diary Title : <input type="text" name="title" value="<?php echo $_SESSION['join']['title']; ?>">
            <?php if(isset($errors['title']) && $errors['title'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                タイトルを入力してください
              </p>
            <?php endif; ?>
	           <p>
	           Diary Contents : <br>
	            <textarea name="contents" cols="100" rows="4" class="form-control" value=""><?php echo $_SESSION['join']['contents']; ?></textarea>
              <?php if(isset($errors['contents']) && $errors['contents'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                日記の内容を入力してください
              </p>
               <?php endif; ?>
	          </p>
	          <p class="day">
	            <?php echo $_SESSION['join']['created']; ?>
              <input type="hidden" name="created">
	          </p>
            <a href="3week.php" class="btn btn-info btn-xs">一覧へ戻る</a>
            <input type="submit" value="更新" class="btn btn-warning btn-xs" style="text-align: center;">
	        </div> 
        </form>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
