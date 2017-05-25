<?php
	session_start();
  require('dbconnect.php');

  // $_SESSION['login_member_id']がセットされてない時
  if (!isset($_SESSION['login_member_id'])) {
      header('Location: login.php');
      exit();
  }

  // ログイン判定
  if (isset($_SESSION['login_member_id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
    $sql = 'SELECT * FROM `members` WHERE `member_id`=?';
    $data = array($_SESSION['login_member_id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $members = $stmt->fetch(PDO::FETCH_ASSOC);

  } else {
    header('Location: login.php');
    exit();
  }

  // エラー処理格納用配列
  $errors = array();

  // 更新ボタンが押された時
  if (!empty($_POST)) {

    $nick_name = $_POST['nick_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = sha1($password);

    // ページ内バリデーション
      if ($nick_name == '') {
          // ニックネームのフォームが空のため、画面にエラーを出力
          $errors['nick_name'] = 'blank'; // blank部分はどんな文字列でも良い
      }

      if ($email == '') {
          $errors['email'] = 'blank';
      }

      if ($password == '') {
        $errors['password'] = 'blank';
      } elseif (strlen($password) < 4) {
          $errors['password'] = 'length';
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

      // メールアドレスの重複チェック
      if (empty($errors)) {
          // DBのmembersテーブルに入力されたメールアドレスと同じデータがあるかどうか検索し取得
          try {
              $sql = 'SELECT COUNT(*) AS `cnt` FROM `members` WHERE `email`=?';
              $data = array($email);
              $stmt = $dbh->prepare($sql);
              $stmt->execute($data);
              $record = $stmt->fetch(PDO::FETCH_ASSOC);

              if ($record['cnt'] > 0) {
                // 同じメールアドレスがDB内に存在したため
                $errors['email'] = 'duplicate';
              }

          } catch (PDOException $e) {
            echo 'SQL文実行時エラー : ' . $e->message();
          }
       }

      // エラーがなかった場合の処理
      if (empty($errors)) {

      // 画像アップロード処理
      $picture_name = date('YmdHis') . $file_name;
      move_uploaded_file($_FILES['picture_path']['tmp_name'], '../member_picture/' . $picture_name);

      $_SESSION['join'] = $_POST; // joinは何でも良い
      $_SESSION['join']['picture_path'] = $picture_name; // ここがpicture_pathでUPDATEされる
      
      // UPDATE文
      $sql = 'UPDATE `members` SET `nick_name`=?, `email`=?, `password`=?, `picture_path`=? WHERE `member_id`=?';
      $data = array($_POST['nick_name'], $_POST['email'], sha1($_POST['password']), $_SESSION['join']['picture_path'], $_SESSION['login_member_id']);
      $stmt1 = $dbh->prepare($sql);
      $stmt1->execute($data);

      header('Location: 3week.php');
      exit();

      }
    }

    // echo '<pre>';
    // var_dump($_SESSION['join']);
    // echo '</pre>';
?>
<br>
<br>
<br>
<br>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ユーザー編集画面</title>
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../css.main.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
  <link rel="stylesheet" type="text/css" href="../resource/lightbox.css" media="screen,tv" />
  <script type="text/javascript" src="../resource/lightbox_plus.js"></script>
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

  <div class="container">
    <div class="row">
      <div class="col-md-5 col-md-offset-3 content-margin-top">
      
       <legend>ユーザー編集画面</legend>
        <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data"><!-- enctypeがないと$_FILESが作成されない-->
          <!-- ニックネーム -->
          <div class="form-group">
            <label class="col-sm-4 control-label">ニックネーム</label>
            <div class="col-sm-8">
              <input type="text" name="nick_name" class="form-control" placeholder="<?php echo $members['nick_name']; ?>"> <!-- $_SESSION['join'] = $_POSTのため -->
              <?php if(isset($errors['nick_name']) && $errors['nick_name'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                ニックネームを入力してください
              </p>
               <?php endif; ?>
            </div>
          </div>
          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">メールアドレス</label>
            <div class="col-sm-8">
              <input type="email" name="email" class="form-control" placeholder="<?php echo $members['email']; ?>">
              <?php if(isset($errors['email']) && $errors['email'] == 'blank'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                メールアドレスを入力してください
              </p>
              <?php endif; ?>

              <?php if(isset($errors['email']) && $errors['email'] == 'duplicate'): ?> <!-- コロン構文 -->
                <p style="color: red; font-size: 10px; margin-top: 2px;">
                  指定したメールアドレスは既に登録されています。
                </p>
              <?php endif; ?>
            </div>
          </div>
          <!-- パスワード -->
          <div class="form-group">
            <label class="col-sm-4 control-label">パスワード</label>
            <div class="col-sm-8">
              <input type="password" name="password" class="form-control">
              <?php if(isset($errors['password']) && $errors['password'] == 'blank'): ?> <!-- コロン構文 -->
                <p style="color: red; font-size: 10px; margin-top: 2px;">
                  パスワードを入力してください
                </p>
              <?php endif; ?>

              <?php if(isset($errors['password']) && $errors['password'] == 'length'): ?> <!-- コロン構文 -->
                <p style="color: red; font-size: 10px; margin-top: 2px;">
                  パスワードは4文字以上で入力してください
                </p>
              <?php endif; ?>
            </div>
          </div>
          <!-- プロフィール写真 -->
          <div class="form-group">
            <label class="col-sm-4 control-label">プロフィール写真</label>
            <div class="col-sm-7">
                <input type="file" name="picture_path" class="form-control">
              <div class="preview" /><a href="../member_picture/<?php echo $members['picture_path']; ?>" rel="lightbox"><img src="../member_picture/<?php echo $members['picture_path']; ?>" style="width: 120%; height: 35%;" class="effectable"></a></div>
                <?php if(isset($errors['picture_path']) && $errors['picture_path'] == 'blank'): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                   プロフィール画像を選択してください
                  </p>
                <?php endif; ?>

                <?php if(isset($errors['picture_path']) && $errors['picture_path'] == 'type'): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                    プロフィール画像は「.jpg」「.png」「.gif」の画像を選択してください
                  </p>
                <?php endif; ?>

                <?php if(!empty($errors)): ?> <!-- コロン構文 -->
                  <p style="color: red; font-size: 10px; margin-top: 2px;">
                    再度、プロフィール画像を指定してください
                  </p>
                <?php endif; ?>
            </div>
          </div>
          <div style="text-align: center;">
            <input type="submit" class="btn btn-warning" name="update" value="更新" style="margin-top: 10px;">
            <a href="3week.php" class="btn btn-default" style="margin-top: 10px;">トップへ戻る</a>
          </div>
        </form>
      </div>
    </div>
  </div>

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