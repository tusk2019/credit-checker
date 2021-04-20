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
    //店舗IDが未設定時、ログインページへ戻す
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

$login_id = pg_escape_string(htmlspecialchars($_POST['login_id'], ENT_QUOTES));
$password = pg_escape_string(htmlspecialchars($_POST['password'], ENT_QUOTES));

//セッションにユーザーIDとユーザー名を入れる

try {
    // DBへ接続
    $dbh = new PDO("pgsql:host=127.0.0.1; dbname=booktown;", 'booktown', 'kouki0328');

    //ログイン画面でpostされたログインIDとパスワードをaccountテーブルで検索し、該当すればそのlogin_id, passwordを取得
    $sql = "SELECT serial_no, user_name, login_id, password FROM users WHERE login_id = '" .$login_id. "' AND password = '" .$password. "'
    AND delete_flg = 0";
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
      // $data = $sth->fetch();
      // var_dump($data);
      // exit();
      //データ
      // foreach ($data as $value) {
      //   $user_id = $value['serial_no'];
      //   $_SESSION['USERID'] = $user_id;
      // }
      $user_id = array_shift($data);
      $_SESSION['USERID'] = $user_id;
      // $user_name = $data['user_name'];
      // $_SESSION['USERNM'] = $user_name;
      //後払いリストへ移動
      header("Location: ../pages/page_credit_list.php");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}

// 接続を閉じる
$dbh = null;
