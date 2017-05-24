<?php
session_start();
require('dbconnect.php');

// $_SESSION = array();
// unset($_SESSION);
// session_destroy();

$email='';
$password='';

$errors = array();

// 自動ログイン機能
if (isset($_COOKIE['email']) && $_COOKIE['email'] == '') {

    // クッキーが保存されていれば、$_POSTをクッキーの情報から生成
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';

}

// ログインボタンが押された時
if (!empty($_POST)) {
	$email = $_POST['email'];
	$password = $_POST['password'];
// 入力されたメールアドレスとパスワードの組み合わせがデータベースに登録されているかチェック
	if ($email != '' && $password != '') {

		$sql = 'SELECT * FROM `members` WHERE `email`=? AND `password`=?';
		$data = array($email, sha1($password));
		$stmt = $dbh->prepare($sql);
		$stmt->execute($data); // データが1件か、0件か
		$record = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($record == false) {  // trueにして処理を逆にしても同じ
			 	// そうでなければエラーメッセージ
				// echo 'ログイン処理失敗';
        $errors['login'] = 'failed';

		} else {
			  // されていなければログイン処理
				// echo 'ログイン処理成功';
		    $_SESSION['login_member_id'] = $record['member_id'];
        $_SESSION['time'] = time();


        // 自動ログイン設定
        if ($_POST['save'] == 'on') {
          // クッキーにログイン情報を保存
          setcookie('email', $email, time() + 60*60*24*30); // 保存期間を決められる
          setcookie('password', $password, time() + 60*60*24*30);
          // 使い方 setcookie(キー, 値, 保存期間);
          // $_COOKIE['キー']; → 値
        }
        // ログインした際の時間を$_SESSIONに保存
				header('Location: 3week.php');
				exit();
		}

 } else {
  // 入力フォームが空だった場合の処理
  $errors['login'] = 'blank';
 }

}

?>
<br>
<br>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
	<title>ログインページ</title>
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

  <div class="container" style="text-align: center">
  	<h1>ログイン</h1>
      <div class="row">
        <form method="POST" action="">
          <div class="col-sm-12">
    			<label>メールアドレス</label><br>
    			<input type="email" name="email" value="<?php echo $email; ?>">
      			<?php if(isset($errors['login']) && $errors['login'] == 'blank'): ?> <!-- コロン構文 -->
      				<p style="color: red; font-size: 10px; margin-top: 2px;">
      					メールアドレスとパスワードを入力してください
      				</p>
      			<?php endif; ?>

            <?php if(isset($errors['login']) && $errors['login'] == 'failed'): ?> <!-- コロン構文 -->
              <p style="color: red; font-size: 10px; margin-top: 2px;">
                ログインに失敗しました。再度正しい情報でログインしてください
              </p>
            <?php endif; ?>
          </div>
          <div class="col-sm-12">
      			<label>パスワード</label><br>
      			<input type="password" name="password" value="<?php echo $password; ?>">
          </div>
          <div class="col-sm-12" style="margin-top: 10px;">
        		<input type="submit" value="ログイン" class="btn btn-default">
            <input type="checkbox" name="save" value="on">自動ログイン機能 <!-- $_POST['save'] = 'on'; -->
            <a href="index.php" class="btn btn-default">会員登録に戻る</a>
          </div>
        </form> 
    </div>
   </div>
</body>
</html>
