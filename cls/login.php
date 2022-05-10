<?php

session_start();
$user_id = null;

if (!isset($_COOKIE["PHPSESSID"])) {

} else {
  $_SESSION = array();
}

$sid = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['ID']=$sid;

//POSTデータ取得(未完成)
if (!isset($_POST['login_id'])) {
    //IDが未設定時、ログインページへ戻す
    header("Location: ../index.php?type=0");
    exit;
}
if (!isset($_POST['password'])) {
    //パスワードが未設定時、ログインページへ戻す
    header("Location: ../index.php?type=2");
    exit;
}

$login_id = null;
$password = null;

//$login_id = pg_escape_string(htmlspecialchars($_POST['login_id'], ENT_QUOTES));
$login_id = htmlspecialchars($_POST['login_id'], ENT_QUOTES);
//$password = pg_escape_string(htmlspecialchars($_POST['password'], ENT_QUOTES));
$password = htmlspecialchars($_POST['password'], ENT_QUOTES);

//セッションにユーザーIDとユーザー名を入れる

try {
    // DBへ接続
    require("dbconnect.php");

    //ログイン画面でpostされたログインIDとパスワードをaccountテーブルで検索し、該当すればそのlogin_id, passwordを取得
    $sql = "SELECT serial_no, user_name, login_id, password FROM users WHERE login_id = '" .$login_id. "' AND delete_flg = 0";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $data = $sth->fetch();
    //var_dump($data);
    // exit();
    //データが取れなかった時はfalseになる
    if ($data == false) {
      //存在しない（ログインページに戻る）
      header("Location: ../index.html?error=1");
    } else {
      if( password_verify($password, $data['password']) ) {
      $user_id = array_shift($data);
      $_SESSION['USERID'] = $user_id;
      $_SESSION['LOGIN_ID'] = $data['login_id'];
      //後払いリストへ移動
      header("Location: ../pages/page_credit_list.php");
      }else {
        //存在しない（ログインページに戻る）
        header("Location: ../index.html?error=1");
      }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}

// 接続を閉じる
$dbh = null;
