<?php

echo '<br>';
echo '<br>';
echo '<br>';

session_start();
require('dbconnect.php');

if (!isset($_REQUEST['member_id'])) {
    header('Location: search.php');
    exit();
}

// var_dump($_REQUEST);
// echo '<br>';
// echo $_REQUEST['user_id'];

// 選択したリスト一件取得
$sql = 'SELECT * FROM `members`  WHERE `member_id`=?';
$data = array($_REQUEST['member_id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$detail = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
  
  $_SESSION['join'] = $_POST;
  header('Location: edit_user.php');
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
              <a class="navbar-brand" href="3week.php"><span class="strong-title" style="color: white;"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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
      <a href="../member_picture/<?php echo $detail['picture_path']; ?>" rel="lightbox"><img src="../member_picture/<?php echo $detail['picture_path']; ?>" style="width: 32%; height: 40%; text-align: center; padding-top: 3px; padding-bottom: 10px;" class="effectable"></a>

        <div class="individual" style="float: left; margin-left: 200px; border-bottom: 1px solid #e5e5e5;">
        <h2 style="border-bottom: 1px solid #e5e5e5; margin-bottom: 20px">ユーザー情報</h2> 
          <h4 style="margin: 14px;">ユーザー名 : <?php echo $detail['nick_name']; ?></h4>

          <h4 style="margin: 14px;">メールアドレス : <?php echo $detail['email']; ?></h4>

          <h4 style="margin: 14px;">アカウント作成日 : <?php echo $detail['created']; ?></h4>
          
          <form name="form3" method="POST" action="">
              <a href="3week.php" class="btn btn-default" style="margin-top: 20px;">トップに戻る</a> 
              <input class="btn btn-success" type="submit" value="編集する" style="margin-top: 20px;">
              <input type="hidden" name="submit-type" value="edit">
              <input type="hidden" name="member_id" value="<?php echo $detail['member_id']; ?>">
              <input type="hidden" name="email" value="<?php echo $detail['email']; ?>">
              <input type="hidden" name="password" value="<?php echo $detail['password']; ?>">
              <input type="hidden" name="created" value="<?php echo $detail['created']; ?>">
          </form>
        </div>
    </div>

    <div>
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
              <a class="navbar-brand" href="3week.php"><span class="strong-title" style="color: white;"><i class="fa fa-linux"></i> NexSeed Diary</span></a>
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
