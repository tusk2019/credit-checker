<?php

if(!isset($_SESSION)) {
    session_start();
}

$billing_service = null;
$contract_date = null;
$date_of_payment = null;
$payment = null;
$user_id = null;
$serial_no = null;
$update_flg=0;
$delete_flg=0;

if(!empty($_POST['billing_service'])) {
    $billing_service = htmlspecialchars($_POST['billing_service']);
}

if(!empty($_POST['contract_date'])) {
    $contract_date = htmlspecialchars($_POST['contract_date']);
}

if(!empty($_POST['date_of_payment'])) {
    $date_of_payment = htmlspecialchars($_POST['date_of_payment']);
}

if(!empty($_POST['payment'])) {
    $payment = htmlspecialchars($_POST['payment']);
}

if(!empty($_POST['user_id'])) {
    $user_id = htmlspecialchars($_POST['user_id']);
}

if(!empty($_POST['serial_no'])) {
  $serial_no = htmlspecialchars($_POST['serial_no']);
}

if(!empty($_POST['update_flg'])) {
  $update_flg = htmlspecialchars($_POST['update_flg']);
}

if(!empty($_POST['delete_flg'])) {
  $delete_flg = htmlspecialchars($_POST['delete_flg']);
}

try {
    // DBへ接続
    $dbh = new PDO("pgsql:host=127.0.0.1; dbname=booktown;", 'booktown', 'kouki0328');

    if($delete_flg==1) {
      $sql = "UPDATE credit
      SET delete_flg = 1,
          update_stamp = now()
      WHERE  serial_no = $serial_no
      AND    user_id = $user_id";
      $data = $dbh->query($sql);
    }

    if($update_flg==1) {
      $sql = "UPDATE credit
      SET billing_service = '$billing_service',
          contract_date = '$contract_date',
          date_of_payment = '$date_of_payment',
          payment = $payment,
          update_stamp = now()
      WHERE  serial_no = $serial_no
      AND    user_id = $user_id";
      $data = $dbh->query($sql);
    } else {
    //ログイン画面でpostされたログインIDとパスワードをaccountテーブルで検索し、該当すればそのlogin_id, passwordを取得
        //$sql = "SELECT login_id, password FROM account WHERE login_id = '" .$login_id. "' AND password = '" .$password. "'";
        $sql = "INSERT INTO credit (billing_service, contract_date, date_of_payment, payment, delete_flg, create_stamp, update_stamp, user_id) 
    VALUES ('$billing_service', '$contract_date', '$date_of_payment', $payment, 0, now(), now(), $user_id)";
        $data = $dbh->query($sql);
    }

} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}

// 接続を閉じる
$dbh = null;
