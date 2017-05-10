<?php
  
  require('dbconnect.php');

  $sql = 'INSERT INTO `diary` SET `id`=?, `title`=?, `contents`=?, `created`=NOW()';
  $data = array();
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>assignment</title>
  <link rel="stylesheet" type="text/css" href="../3week.css">
</head>

<body>
<div id="site-box">
  <div id="a-box">
    <h2 style="color: yellow">NexSeed Diary</h2>
  </div>

<?php while($diary = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
  <div id="b-box">
    <div class="content1">
      <div class="diary">
        <a href="">か</a><br>
        <p class="date">2016年8月22日</p>
      </div>
    </div>
  </div>
<?php endwhile; ?>
  <div id="c-box">
    <div class="content2">
      <?php
          date_default_timezone_set('Asia/Tokyo');
          $time = intval(date('H'));
          if (6 <= $time && $time <= 11) { // 06:01～11:00の時間帯のとき ?>
          <p>おはようございます、ゲストさん</p>
          <?php } elseif (11 <= $time && $time <= 17) { // 11:01〜17:59の時間帯のとき ?>
          <p>こんにちわ、ゲストさん</p>
          <?php } else { // それ以外の時間帯のとき (18:00 〜 05:59の時間帯) ?>
          <p>こんばんわ、ゲストさん</p>
      <?php } ?>
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