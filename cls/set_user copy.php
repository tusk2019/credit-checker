<?php

if (!isset($_SESSION)) {
    session_start();
}

$user_name = null;
$login_id = null;
$password = null;
$update_flg = null;
$serial_no = null;
$delete_flg = null;

if (!empty($_POST['user_name'])) {
    $user_name = htmlspecialchars($_POST['user_name']);
}

if (!empty($_POST['login_id'])) {
    $login_id = htmlspecialchars($_POST['login_id']);
}

if (!empty($_POST['password'])) {
    $password = htmlspecialchars($_POST['password']);
}

if (!empty($_POST['update_flg'])) {
    $update_flg = htmlspecialchars($_POST['update_flg']);
}

if (!empty($_POST['serial_no'])) {
		$serial_no = htmlspecialchars($_POST['serial_no']);
}

if (!empty($_POST['delete_flg'])) {
		$delete_flg = htmlspecialchars($_POST['delete_flg']);
}

try {
    // DBへ接続
    $dbh = new PDO("pgsql:host=127.0.0.1; dbname=booktown;", 'booktown', 'kouki0328');
		if($delete_flg == 1) {
			$sql = "UPDATE users
							SET delete_flg = 1,
							update_stamp = now()
							WHERE  serial_no = $serial_no";
			$dbh->query($sql);
			//creditのデータも論理削除
			$sql2 = "UPDATE credit
							SET delete_flg = 1,
							update_stamp = now()
							WHERE  user_id = $user_id";
			$dbh->query($sql2);
			//設定ページへ移動
			header("Location: ../pages/page_config.php?success=2");
			exit();
    }
		//設定で変更したアカウント情報(要編集)
    if($update_flg == 1) {
			$sql = "UPDATE users
							SET deleet_flg = 1,
							update_stamp = now()
							WHERE  serial_no = $serial_no";
			$dbh->query($sql);
			//設定ページへ移動
			header("Location: ../pages/page_config.php?success=1");
			exit();
    }

    //ログイン画面でpostされたログインIDをusersテーブルで検索し、該当するものがあれば、SQL文発行を中断し、会員登録ページにアラートを送る。
    if($login_id == true) {
      $login_id_check = "select login_id from users where login_id = '$login_id' ";
      $sth = $dbh->query($login_id_check);
      $sth->execute();
      if ($sth->fetch() === false) {
          //存在しない（データを登録する）
          $sql = "INSERT INTO users (user_name, login_id, password, delete_flg, create_stamp, update_stamp) 
          VALUES ('$user_name', '$login_id', '$password', 0, now(), now())";
          $data = $dbh->query($sql);
          //ログインページへ移動
					header("Location: ../index.html?success=1");
					exit();
        } else {
          //存在する（データを登録せずに、アラートを送る）
          header("Location: ../pages/page_sign_up.html?error=1");
          exit();
        }
    }

} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}

// 接続を閉じる
$dbh = null;
