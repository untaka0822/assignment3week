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
  $members = $stmt1->fetch(PDO::FETCH_ASSOC);

  } else {
    // ログインしていない場合
    header('Location: login.php');
    exit();
  }

  // ユーザーの持ってる日記全件取得
  $sql = 'SELECT * FROM `diary` WHERE `user_id`=?';
  $data = array($_SESSION['login_member_id']);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  $diary = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo '<br>';
    // echo '<br>';
    // echo '<br>';
    // echo '<br>';
    // echo '<pre>';
    // var_dump($_SESSION['join']);
    // echo '</pre>';

  // エラー処理格納用配列
  $errors = array();

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

      // エラーがなかったら
      if (empty($errors)) {
      // 画像のバリエーション
      $file_name = $_FILES['picture_path']['name'];
      // name部分は固定、picture_path部分はinputタグのtype="file"のname部分
      if (!empty($file_name)) {
         // 画像が選択されていた場合
        $ext = substr($file_name, -3); // $_file_nameから拡張子部分取得
        $ext = strtolower($ext); // 指定したstring内の文字を小文字にする

      if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
        $errors['picture_path'] = 'type';
        }

      } else {
          // 画像が未選択の場合
        $errors['picture_path'] = 'blank';

        }
      }

      // エラーがなかった場合の処理
      if (empty($errors)) {

      // 画像アップロード処理
      $picture_name = date('YmdHis') . $file_name;
      move_uploaded_file($_FILES['picture_path']['tmp_name'], '../diary_picture/' . $picture_name);

      $_SESSION['join'] = $_POST; // joinは何でも良い
      $_SESSION['join']['picture_path'] = $picture_name;// ここがpicture_pathでUPDATEされる

      // var_dump($_SESSION['join']); // headerで飛ばす前にvar_dumpで確認する 位置も重要

      // UPDATE文
      $sql = 'UPDATE `diary` SET `title`=?, `contents`=?, `picture_path`=?, `created`=NOW() WHERE `diary_id`=?'; // WHERE 重要
      $data = array($_SESSION['join']['title'], $_SESSION['join']['contents'], $_SESSION['join']['picture_path'], $_SESSION['join']['diary_id']); // $_SESSION['join'] = $_POSTのため 82行目
      $stmt2 = $dbh->prepare($sql);
      $stmt2->execute($data);

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
    <link rel="stylesheet" type="text/css" href="../resource/lightbox.css" media="screen,tv" />
    <script type="text/javascript" src="../resource/lightbox_plus.js"></script>
</head>

  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top" style="background-color: deepskyblue;">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="3week.php"><span class="strong-title" style="color: white"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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
      <div class="col-md-4 col-md-offset-4 content-margin-top">
        <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
          <h3>日記の編集</h3>
	        <div class="msg">
	          日記のタイトル : <input type="text" name="title" value="<?php echo $_SESSION['join']['title']; ?>">
            <?php if(isset($errors['title']) && $errors['title'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                タイトルを入力してください
              </p>
            <?php endif; ?>
	           <p>
	           日記の内容 : <br>
	            <textarea name="contents" cols="100" rows="4" class="form-control"><?php echo $_SESSION['join']['contents']; ?></textarea>
              <?php if(isset($errors['contents']) && $errors['contents'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                日記の内容を入力してください
              </p>
               <?php endif; ?>
	          </p>
	          <p class="day">
	            <?php echo $_SESSION['join']['created']; ?>
              <input type="hidden" name="created" value="<?php echo $_SESSION['join']['created']; ?>">
	          </p>
            <input type="file" name="picture_path" class="form-control">
              <div class="preview" /><a href="../diary_picture/<?php echo $_SESSION['join']['picture_path']; ?>" rel="lightbox"><img src="../diary_picture/<?php echo $_SESSION['join']['picture_path']; ?>" style="width: 60%; height: 24%; margin-top: 10px;" class="effectable"></a>
                <?php if(isset($errors['picture_path']) && $errors['picture_path'] == 'blank'): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                  日記の画像を選択してください
                  </p>
                <?php endif; ?>

                <?php if(isset($errors['picture_path']) && $errors['picture_path'] == 'type'): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                   日記の画像は「.jpg」「.png」「.gif」の画像を選択してください
                  </p>
                <?php endif; ?>

                <?php if(!empty($errors)): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                    再度、日記の画像を指定してください
                  </p>
                <?php endif; ?>
              </div>
	        </div> 
          <input type="hidden" name="diary_id" value="<?php echo $_SESSION['join']['diary_id']; ?>"> <!-- 大事！条件が一致していないとバグる --> 
            <input type="submit" name="update" value="更新" class="btn btn-warning" style="text-align: center; margin-top: 10px;">
            <a href="3week.php" class="btn btn-default" style="margin-top: 10px;">一覧へ戻る</a>
        </form>
      </div>

      <nav class="navbar navbar-default navbar-fixed-bottom" style="background-color: deepskyblue;">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="3week.php"><span class="strong-title" style="color: white; text-align: right;"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script>
  // documentと毎回書くのがだるいので$に置き換え
var $ = document; 
var $form = $.querySelector('form');// jQueryの $("form")相当

//jQueryの$(function() { 相当(ただし厳密には違う)
$.addEventListener('DOMContentLoaded', function() {
    //画像ファイルプレビュー表示
    //  jQueryの $('input[type="file"]')相当
    // addEventListenerは on("change", function(e){}) 相当
    $.querySelector('input[type="file"]').addEventListener('change', function(e) {
        var file = e.target.files[0],
               reader = new FileReader(),
               $preview =  $.querySelector(".preview"), // jQueryの $(".preview")相当
               t = this;
        
        // 画像ファイル以外の場合は何もしない
        if(file.type.indexOf("image") < 0){
          return false;
        }
        
        reader.onload = (function(file) {
          return function(e) {
             //jQueryの$preview.empty(); 相当
             while ($preview.firstChild) $preview.removeChild($preview.firstChild);

            // imgタグを作成
            var img = document.createElement( 'img' );
            img.setAttribute('src',  e.target.result);
            img.setAttribute('width', '150px');
            img.setAttribute('title',  file.name);
            // imgタグを$previeの中に追加
            $preview.appendChild(img);
          }; 
        })(file);
        
        reader.readAsDataURL(file);
    }); 
});

</script>
  </body>
</html>
