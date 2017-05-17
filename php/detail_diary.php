<?php

session_start();
require('dbconnect.php');

if (!isset($_REQUEST['user_id'])) {
    header('Location: search.php');
    exit();
}

// var_dump($_REQUEST);
// echo '<br>';
// echo $_REQUEST['user_id'];

// 選択したリスト一件取得
$sql = 'SELECT * FROM `users`  WHERE `user_id`=?';
$data = array($_REQUEST['user_id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($user);
echo $user['picture_path'];

$sql='SELECT * FROM `areas`';
        $stmt2= $dbh->prepare($sql);
        $stmt2->execute();

while ($area=$stmt2->fetch(PDO::FETCH_ASSOC)) {
  if ($user['area_id']==$area['area_id']) {
    echo $area['area_name'];
  }
}


?>

<br>
<br>
<br>
<br>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/result_search.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/background.css">
  <title>詳細結果</title>

  <?php
    require('mypage_header.php');
  ?> 

</head>
<body>
<!-- <img class="bg" src="../assets/images/grass_wallpaper.JPG"> -->

  <br>
  <br>
  <br>
  <br>
  <br>

  <!-- <div class="container">
   <div class="row">

    <div class=""><h2>ユーザー詳細ページ</h2></div> -->

    <div class="col-md-6 col-lg-offset-3 centered">
    <div class="panel panel-default">
    <div class="panel-heading"><h4>ユーザープロフィール</h4></div>
    <div class="panel-body">
    <div class="box box-info">
    <div class="box-body">
    <div class="col-sm-6 col-lg-offset-4 centered">
        <div>
          <img src="../img/users_picture/<?php echo $user['picture_path']; ?>" style="width: 130px; height: 130px">
          <input id="profile-image-upload" class="hidden" type="file">
              <!--Upload Image Js And Css-->
        </div>
        <br>
              <!-- /input-group -->
        </div>
        <div class="clearfix"></div>
          <hr style="margin:5px 0 5px 0;">
          <br>

          <div class="info">
          <div class="col-sm-5 col-xs-6 tital">姓</div><div class="col-xs-1" >:</div><div class="col-xs-6 tital" style="text-align: left;"><?php echo $user['last_name']; ?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-sm-5 col-xs-6 tital">名</div><div class="col-xs-1">:</div><div class="col-xs-6 tital" style="text-align: left;"><?php echo $user['first_name']; ?></div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-sm-5 col-xs-6 tital">性別</div><div class="col-xs-1">:</div>
          <div class="col-sm-2 col-xs-6 tital" style="text-align: left">
          <?php
                $gender= $user['gender'];
                if ($gender == 1) {
                echo "男性";
                } elseif($gender == 2) {
                echo "女性";
                }else{
                  echo"不明";
                }
          ?>
          </div>

          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-sm-5 col-xs-6 tital">都道府県</div><div class="col-xs-1">:</div><div class="col-sm-2 col-xs-6 tital" style="text-align: left;">
            <?php  $sql='SELECT * FROM `areas`';
                        $stmt2= $dbh->prepare($sql);
                        $stmt2->execute();
                        while ($area=$stmt2->fetch(PDO::FETCH_ASSOC)) {
                          if ($user['area_id']==$area['area_id']) {
                            echo $area['area_name'];
                          }
                        }
                        ?>
          </div>
          <div class="clearfix"></div>
          <div class="bot-border"></div>

          <div class="col-sm-5 col-xs-6 tital">市区町村</div><div class="col-xs-1">:</div><div class="col-sm-2 col-xs-6 tital" style="text-align: left;">
            <?php echo $user['area_detail2']; ?>
                  <!-- /.box-body -->
        </div>
              <!-- /.box -->
      </div>
    </div>
  </div>
</div>

  <script>
  $(function() {
    $('#profile-image1').on('click', function() {
        $('#profile-image-upload').click();
    });
});
  </script>
  </div>
  </div>
  </div>

<!--  -->

    <div class="col-sm-6 col-lg-offset-5 centered">
      <a class="btn btn-primary" href="search.php">戻る</a> 
      <a class="btn btn-primary" href="sns_reservation.php?user_id=<?php echo $user['user_id']; ?>">コンタクトを取る</a>
    </div>

    <br>
    <br>

    <!-- <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/jquery-migrate.js"></script>
    <script src="../assets/js/jquery.js"></script> -->
  </body>
</html>