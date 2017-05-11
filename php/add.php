
<?php
  session_start();
  require('dbconnect.php');

  $id = '';
  $title = '';
  $contents = '';

  if (!empty($_POST)) {
    $sql = 'INSERT INTO `diary` SET `id`=?, `title`=?, `contents`=?';
    $data = array($id, $title, $contents);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>日記追加画面</title>
  <link rel="stylesheet" type="text/css" href="../css/add.css">
</head>

<body>
<div id="site-box">
  <div id="a-box">
    <h2 style="color: yellow">NexSeed Diary</h2>
  </div>

  <div id="b-box">
    <div class="content1">
      <div class="diary">
        <form method="POST" action="3week.php">
          <input id="diary_title" style="width: 200px; height: 10px;" type="text" name="title" placeholder="日記のタイトルを入力してください">
          <textarea style="width: 400px; height: 100px;" name="contents" placeholder="日記の内容を入力してください"></textarea>
          <input type="submit" value="完了">
          <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
        </form>
      </div>   
    </div>
  </div>

  <div id="c-box">
    <div class="content2">
      <p class="data1">こんにちは、ゲストさん</p>
      <p class="data1"><a class="history" href="">2016年10月の日記</a></p><br>
      <p class="data2"><a class="history" href="">2016年09月の日記</a></p><br>
      <p class="data2"><a class="history" href="">2016年08月の日記</a></p>
    </div>
  </div>
  <div id="d-box">
   <h5 style="color: white; font-size: 4px">Copyright @ NexSeed inc All Rights Reserved</h5>
  </div>
</div> 
</body>
</html>
  
