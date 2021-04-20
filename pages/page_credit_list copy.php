<?php

session_start();
$user_id = null;
$user_nm = null;
//$user_id = $_SESSION['USERID'];

if(!empty($_SESSION['USERID'])) {
  $user_id = $_SESSION['USERID'];
}

if(!empty($_SESSION['USERNM'])) {
  $user_nm = $_SESSION['USERNM'];
}
try {
    // DBへ接続
    $dbh = new PDO("pgsql:host=127.0.0.1; dbname=booktown;", 'booktown', 'kouki0328');

    //ログイン画面でpostされたログインIDとパスワードをaccountテーブルで検索し、該当すればそのlogin_id, passwordを取得
    //$sql = "SELECT login_id, password FROM account WHERE login_id = '" .$login_id. "' AND password = '" .$password. "'";
    // SQL作成
    $sql = "SELECT * FROM credit WHERE user_id = ".$user_id. " AND delete_flg = 0 ORDER BY serial_no ASC";
    $data = $dbh->query($sql)->fetchAll();
    $sql2 = "SELECT user_name FROM users WHERE user_id = ".$user_id. " AND delete_flg = 0 ORDER BY serial_no ASC";
    $user_data = $dbh->query($sql2)->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}

// 接続を閉じる
$dbh = null;

?>

<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Material Dashboard by Creative Tim
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">
        後払いチェッカー
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active ">
            <a class="nav-link" href="./page_credit_list.php">
              <i class="material-icons">dashboard</i>
              <p>後払いリスト</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./page_config.php">
              <!-- <i class="material-icons">dashboard</i> -->
              <i class="fa fa-cog"></i>
              <p>設定</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="../cls/logout.php">
              <!-- <i class="material-icons">person</i> -->
              <i class="fa fa-sign-out"></i>
              <p>ログアウト</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">後払いリスト</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
        </div>
      </nav>
      <!-- End Navbar -->
      <!-- <div class="content">
        
      </div> -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title "><span id="usersName"><?php echo $user_nm; ?></span>さんの後払いリスト<button type="button" class="btn btn-info" id="addBtn" style="float: right;"
                  data-toggle="modal" data-target="#setModal" data-user-id="<?php echo $user_id; ?>">追加</button></h4>
                  <!-- <p class="card-category"> Here is a subtitle for this table</p> -->
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>
                          課金サービス名
                        </th>
                        <th>
                          契約日
                        </th>
                        <th>
                          支払い日
                        </th>
                        <th>
                          支払い金額（円）
                        </th>
                        <th>
                          支払い日まで（日）
                        </th>
                        <th>
                          編集
                        </th>
                        <th>
                          解約済み
                        </th>
                      </thead>
                      <tbody>
                      <?php foreach ($data as $row): ?>
                        <tr>
                          <!--UPDATEの時はserial_noを使う-->
                          <?php echo '<td class="credit-serial-no" style="display:none;">'.$row['serial_no'].'</td>'; ?>
                          <?php echo '<td class="billing-service">'.$row['billing_service'].'</td>'; ?>
                          <?php echo '<td class="contract-date">'.$row['contract_date'].'</td>'; ?>
                          <?php echo '<td class="date-of-payment">'.$row['date_of_payment'].'</td>'; ?>
                          <?php echo '<td class="payment">'.$row['payment'].'</td>'; ?>
                          <?php
                          date_default_timezone_set("Asia/Tokyo");
                          $date_of_payment = $row['date_of_payment'];
                          $end = strtotime($date_of_payment);
                          $now = strtotime('now');
                          $days = ceil(($end - $now) / (60*60*24));
                          if ($days > 0) {
                              //$days++;
                              $date_counter = '<td>残り <strong>' . $days . '</strong> 日</td>';
                          } elseif ($days == 0) {
                              $date_counter = '<td>本日が支払日です。</td>';
                          } elseif ($days < 0) {
                              $date_counter = '<td>支払い日終了後、' . -$days . ' 日経過しました。</td>';
                          }
                          echo '<p>' . $date_counter . '</p>';
                          ?>
                          <td><button type="button" class="btn btn-success edit-btn" data-sn="<?php echo $row['serial_no']; ?>" data-toggle="modal"
                              data-target="#setModal" data-user-id="<?php echo $user_id; ?>">編集</button></td>
                          <td><button type="button" class="btn btn-danger delete-btn" data-toggle="modal"
                              data-target="#deleteModal" data-sn="<?php echo $row['serial_no']; ?>" data-user-id="<?php echo $user_id; ?>">解約済み</button></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- setModal -->
        <div class="modal fade" id="setModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">後払い情報登録</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" action="../cls/set_credit.php" id="setCreditDatas">
                  <input type="hidden" id="serialNo" value="" name="serial_no">
                  <input type="hidden" id="userId" value="" name="user_id">
                  <input type="hidden" id="updateFlg" value="" name="update_flg">
                  <div class="form-group">
                    <label for="exampleFormControlInput1">課金サービス名</label>
                    <input type="text" id="billingService" class="form-control" name="billing_service" id="exampleFormControlInput1"
                      placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">契約日</label>
                    <input type="text" id="contractDate" name="contract_date" class="form-control"
                      placeholder="日付を選択してください">
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">支払い日（日）</label>
                    <input type="text" id="dateOfPayment" class="form-control" name="date_of_payment"
                      placeholder=" 日付を選択してください">
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">支払い金額（円）(半角数字)</label>
                    <input type="number" id="payment" name="payment" class="form-control" id="exampleFormControlInput1"
                      placeholder="">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <button type="button" id="setBtn" class="btn btn-primary">登録する</button>
              </div>
            </div>
          </div>
        </div>

        <!--解約済みモーダル-->
        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" action="../cls/set_credit.php">
                  <input type="hidden" id="deleteFlg" name="delete_flg" value="1">
                  <input type="hidden" id="delSerialNo" name="serial_no" value="">
                  <input type="hidden" id="delUserId" name="user_id" value="">
                「<span id="deleteServiceName"></span>」の後払いデータを削除してもよろしいでしょうか？
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                <button type="button" class="btn btn-primary delete-ok-btn">はい</button>
              </div>
            </div>
          </div>
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
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="../assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <!-- datepicker -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://rawgit.com/jquery/jquery-ui/master/ui/i18n/datepicker-ja.js"></script>
  <!-- jquery-validate-1.17.0 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
  <script>
    $(document).ready(function () {
      $().ready(function () {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function (event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function () {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function () {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function () {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function () {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function () {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function () {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function () {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function () {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function () {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function () {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $("#contractDate").datepicker();
  </script>
  <script>
    $("#dateOfPayment").datepicker();
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#setCreditDatas').validate({
        rules: {
          billing_service: {
            required: true
          },
          contract_date: {
            required: true,
            dateISO: true
          },
          date_of_payment: {
            required: true,
            dateISO: true
          }
        },
        messages: {
          billing_service: {
            required: "課金サービス名を入力してください。"
          },
          contract_date: {
            required: "日付を選択してください。",
            dateISO: "日付を正しく入力してください。"
          },
          date_of_payment: {
            required: "パスワードを入力してください。",
            dateISO: "日付を正しく入力してください。"
          }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
  </script>

  <script>
    $(function(){
      // Ajax button click
      $('#setBtn').on('click',function(){
          $.ajax({
              url:'../cls/set_credit.php',
              type:'POST',
              data:{
                  'billing_service':$('#billingService').val(),
                  'contract_date':$('#contractDate').val(),
                  'date_of_payment':$('#dateOfPayment').val(),
                  'payment':$('#payment').val(),
                  'user_id':$('#userId').val(),
                  'serial_no':$('#serialNo').val(),
                  'update_flg':$('#updateFlg').val()
              }
          })
          // Ajaxリクエストが成功した時発動
          .done( function(result) {
              alert('登録に成功しました');
              location.href='page_credit_list.php';
          })
          // Ajaxリクエストが失敗した時発動
          .fail( function(data) {
              alert('失敗しました');
              console.log(data);
          })          
      });
    });
  </script>
    
  <script>
  $("[with='alphanum']")
  .off(".inputcontrol.alphanum")
  .on("keyup.inputcontrol.alphanum", function(){
    $(this).val($(this).val().replace(/[^0-9a-zA-Z]/g,""));
  });
  </script>

  <!-- page script -->
  <script>
  //データのシリアルナンバーを保存する変数snを宣言
  var userId;
  var sn;

  $('.edit-btn').on('click',function(){
    sn = $(this).data('sn');
    $('#serialNo').val(sn);
    userId = $(this).data('user-id');
    $('#userId').val(userId);
    var billingService = $(this).closest('td').closest('tr').find('.billing-service').text();
    var contractDate = $(this).closest('td').closest('tr').find('.contract-date').text();
    var dateOfPayment = $(this).closest('td').closest('tr').find('.date-of-payment').text();
    var payment = $(this).closest('td').closest('tr').find('.payment').text();
    $('#billingService').val(billingService);
    $('#contractDate').val(contractDate);
    $('#dateOfPayment').val(dateOfPayment);
    $('#payment').val(payment);
    $('#updateFlg').val(1);
    //
  });

  $('#addBtn').on('click',function(){
    userId = $(this).data('user-id');
    $('#userId').val(userId);
  });

  $('.delete-btn').on('click',function(){
    sn = $(this).data('sn');
    $('#delSerialNo').val(sn);
    userId = $(this).data('user-id');
    $('#delUserId').val(userId);
    var billingService = $(this).closest('td').closest('tr').find('.billing-service').text();
    $('#deleteServiceName').text(billingService);
  });
  </script>
  <script>
    $(function(){
      // Ajax button click
      $('.delete-ok-btn').on('click',function(){
          $.ajax({
              url:'../cls/set_credit.php',
              type:'POST',
              data:{
                  'delete_flg':$('#deleteFlg').val(),
                  'user_id':$('#delUserId').val(),
                  'serial_no':$('#delSerialNo').val()
              }
          })
          // Ajaxリクエストが成功した時発動
          .done( function(result) {
              alert('削除に成功しました');
              location.href='page_credit_list.php';
          })
          // Ajaxリクエストが失敗した時発動
          .fail( function(data) {
              alert('失敗しました');
              console.log(data);
          })          
      });
    });
  </script>
</body>

</html>