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
    $login_user = $stmt1->fetch(PDO::FETCH_ASSOC);

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

  try { 
        // DBへの登録処理
        $sql = 'INSERT INTO `diary` SET `diary_id`=?, `user_id`=?, `title`=?, `contents`=?, `created`=NOW()';
        $data = array($diary_id, $login_user['member_id'], $title, $contents);
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
  <div>
    日記のタイトル：<br>
    <?php echo $_SESSION['join']['title']; ?>
  </div>
  <div>
    日記の内容：<br>
    <?php echo $_SESSION['join']['contents']; ?>
  </div>
  <br>
  <form method="POST" action="">
    <input type="hidden" name="hoge" value="fuga"> <!-- 値を表示せずにDBに保存するときはhidden -->
    <input type="submit" value="完了">
  </form>
</body>
</html>
