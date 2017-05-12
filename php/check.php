<?php
session_start();
require('dbconnect.php');
// index.phpで入力された内容を受取り表示する

// $_SESSION スーパーグローバル変数

// index.phpを正しく通って来なかった場合、強制的にindex.phpに遷移
if (!isset($_SESSION['join'])) { 
    header('Location: index.php');
    exit();
}
// 会員登録ボタンが押された際
if (!empty($_POST)) {
    $nick_name = $_SESSION['join']['nick_name'];
    $email = $_SESSION['join']['email'];
    $password = $_SESSION['join']['password'];
    $password = sha1($password); // sha1 = 指定した文字列をハッシュ化(暗号化)する 16進数
    $picture_path = $_SESSION['join']['picture_path'];
     
    // DBに会員情報を登録
    try{
        // 例外が発生する可能性のある処理
        $sql = 'INSERT INTO `members` SET `nick_name`=?,
                                          `email`=?,
                                          `password`=?,
                                          `picture_path`=?,
                                          `created`=NOW()';
        $data = array($nick_name, $email, $password, $picture_path);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        // $_SESSIONの情報を削除
        unset($_SESSION['join']);

        // thanks.phpへ遷移
        header('Location: thanks.php');
        exit();

    } catch(PDOException $e) {
        // 例外が発生した場合の処理
        echo 'SQL文実行時エラー: ' . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>NexSeed Diary 確認</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
  <div>
    ニックネーム：<br>
    <?php echo $_SESSION['join']['nick_name']; ?>
  </div>
  <div>
    メールアドレス：<br>
    <?php echo $_SESSION['join']['email']; ?>
  </div>
  <div>
    パスワード：<br>
    <?php echo $_SESSION['join']['password']; ?>
  </div>
  <img src="../member_picture/<?php echo $_SESSION['join']['picture_path']; ?>" width="200">
  <br>
  <form method="POST" action="">  
    <input type="hidden" name="hoge" value="fuga">
    <a href="index.php">書き直す</a> <!-- &から始まる文字はHTML上で表示できない文字の時に使う　例: & < > etc... -->
    <input type="submit" value="会員登録">
  </form>
</body>
</html>
