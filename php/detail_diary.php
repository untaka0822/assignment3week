<?php

session_start();
require('dbconnect.php');

if (!isset($_REQUEST['diary_id'])) {
    header('Location: search.php');
    exit();
}

// var_dump($_REQUEST);
// echo '<br>';
// echo $_REQUEST['user_id'];

// 選択したリスト一件取得
$sql = 'SELECT * FROM `diary`  WHERE `diary_id`=?';
$data = array($_REQUEST['diary_id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$detail = $stmt->fetch(PDO::FETCH_ASSOC);

echo '<pre>';
var_dump($detail);
echo '</pre>';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>詳細ページ</title>
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  
</head>
<body>

</body>
</html>
