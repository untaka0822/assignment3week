<?php
session_start();

// セッションの中身を空の配列で上書きする
$_SESSION = array();

// 
if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy(); // SESSIONの情報を消す

// COOKIE情報も削除
setcookie('email', '', time() - 3000);
setcookie('password', '', time() - 3000);

header('Location: 3week.php');
exit();

?>