<?php
session_start();
require('dbconnect.php');
// index.phpで入力された内容を受取り表示する

// $_SESSION スーパーグローバル変数

// index.phpを正しく通って来なかった場合、強制的にindex.phpに遷移
if (!isset($_SESSION['join'])) { 
    header('Location: 3week.php');
    exit();
}
// 会員登録ボタンが押された際
if (!empty($_POST)) {
    $title = $_SESSION['join']['title'];
    $contents = $_SESSION['join']['contents'];
     
    // DBに会員情報を登録
    try{
        // 例外が発生する可能性のある処理
        $sql = 'INSERT INTO `diary` SET `title`=?, `contents`=?';
        $data = array($title, $contents);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        // $_SESSIONの情報を削除
        unset($_SESSION['join']);

        // thanks.phpへ遷移
        header('Location: 3week.php');
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
    日記のタイトル：<br>
    <?php echo $_SESSION['join']['title']; ?>
  </div>
  <div>
    日記の内容：<br>
    <?php echo $_SESSION['join']['contents']; ?>
  </div>
  <br>
  <form method="POST" action="">  
    <input type="hidden" name="hoge" value="fuga">
    <a href="diary.php"></a>
    <input type="submit" value="完了">
  </form>
</body>
</html>
