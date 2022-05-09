<?php
$host = "mysql57.booktown.sakura.ne.jp";
$dbName = "booktown_db";
$user = "booktown";
$dbPassword = "mihara913";
$dsn = "mysql:host={$host};dbname={$dbName};charser=utf8";

try {
   $dbh = new PDO($dsn, $user, $dbPassword);
} catch (Exception $e) {
   echo "<span class='error'>データベース接続エラーがありました。</span><br>";
   echo $e->getMessage();
   exit();
}
?>