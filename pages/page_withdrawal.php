<?php
session_start();
$user_id = null;
//$user_id = $_SESSION['USERID'];

if(!empty($_SESSION['USERID'])) {
  $user_id = $_SESSION['USERID'];
}
?>
<!doctype html>
<html lang="ja">

<head>
  <title>Hello, world!</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Kit CSS -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <style>
    /* .navbar.navbar-absolute{
      position: none !important;
    } */
  </style>
</head>

<body>
  <div class="wrapper ">
    <div class="main-panel" style="float: left; width:100%">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">退会</a>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->
          <form method="POST" action="../cls/set_user.php">
            <div class="alert alert-danger" role="alert">
              退会が完了するとあなたのアカウントは削除されます。それでもよろしいでしょうか？
            </div>
            <input type="hidden" name="delete_flg" value="1">
            <input type="hidden" name="serial_no" value="<?php echo $user_id; ?>">
            <button type="submit" class="btn btn-danger">アカウント削除</button>
            <button type="button" class="btn btn-success" onclick="location.href='page_config.php'">キャンセル</button>
          </form>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made by Koki Motomura with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>
</body>

</html>