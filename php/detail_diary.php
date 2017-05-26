<?php

echo '<br>';
echo '<br>';
echo '<br>';

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

if (!empty($_POST)) {
  
  $_SESSION['join'] = $_POST;
  header('Location: edit.php');
  exit();
}

// echo '<pre>';
// var_dump($detail);
// echo '</pre>';

?>

<br>
<br>
<br>

<!DOCTYPE html>
<html lang="ja">
<head>

  <meta charset="utf-8">
  <title>詳細ページ</title>
  <link rel="stylesheet" type="text/css" href="../css/detail_diary.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../resource/lightbox.css" media="screen,tv" />
  <script type="text/javascript" src="../resource/lightbox_plus.js"></script>

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
              <a class="navbar-brand" href="3week.php" style="font-family: serif;"><span class="strong-title" style="color: white;"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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

  <div id="all-box">
    <div id="a-box">
      <a href="../diary_picture/<?php echo $detail['picture_path']; ?>" rel="lightbox"><img src="../diary_picture/<?php echo $detail['picture_path']; ?>" style="width: 32%; height: 40%; text-align: center; padding-top: 3px; padding-bottom: 10px;" class="effectable"></a>

        <div class="individual" style="float: left; margin-left: 200px; border-bottom: 1px solid #e5e5e5;">
        <h2 style="border-bottom: 1px solid #e5e5e5; margin-bottom: 20px; font-family: serif;">日記の情報</h2>  
          <h4 style="margin: 14px; font-family: serif;">タイトル : <?php echo $detail['title']; ?></h4>

          <h4 style="margin: 14px; font-family: serif;">日記の内容 : <?php echo $detail['contents']; ?></h4>

          <h4 style="margin: 14px; font-family: serif;">作成日 : <?php echo $detail['created']; ?></h4>

          <form name="form3" method="POST" action="">
              <a href="3week.php" class="btn btn-default" style="margin-bottom: 10px; font-family: serif;"">トップに戻る</a> 
              <input class="btn btn-success" type="submit" value="編集する" style="margin-bottom: 10px; font-family: serif;"">
              <input type="hidden" name="diary_id" value="<?php echo $detail['diary_id']; ?>">
              <input type="hidden" name="title" value="<?php echo $detail['title']; ?>">
              <input type="hidden" name="contents" value="<?php echo $detail['contents']; ?>">
              <input type="hidden" name="picture_path" value="<?php echo $detail['picture_path']; ?>">
              <input type="hidden" name="created" value="<?php echo $detail['created']; ?>">
          </form>
        </div>
    </div>

     <div id="d-box">
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
              <a class="navbar-brand" href="3week.php" style="font-family: serif; padding-left: 1000px;"><span class="strong-title" style="color:white;"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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

  

</body>
</html>
