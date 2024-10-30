
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FastCat Merchant Portal - Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/fastcat.png" />
  <script type="text/javascript">
      google.charts.load('visualization', "1", {
          packages: ['corechart']
      });
  </script>
    <style>
        ::-webkit-scrollbar-track {
          background: #f1f1f1; 
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
          background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
          background: #555; 
        }
        ::-webkit-scrollbar {
            height: 4px;              /* height of horizontal scrollbar ← You're missing this */
            width: 4px;               /* width of vertical scrollbar */
            border: 1px solid #d5d5d5;
          }
    </style>
</head>
<body>
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="/dashboard">
            <img src="assets/images/fastcat.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="/dashboard">
            <img src="assets/images/fastcat.png" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">
                <?php
                /* This sets the $time variable to the current hour in the 24 hour clock format */
                date_default_timezone_set("Asia/Kolkata"); 
                $time = date("G");
                /* If the time is less than 1200 hours, show good morning */
                if ($time < "10") {
                    echo "Good morning";
                } else
                /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
                if ($time >= "10" && $time < "15") {
                    echo "Good afternoon";
                } else
                /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                if ($time >= "15" && $time < "19") {
                    echo "Good evening";
                } else
                /* Finally, show good night if the time is greater than or equal to 1900 hours */
                if ($time >= "19") {
                    echo "Good night";
                }
                ?>, 
                <span class="text-black fw-bold"><?= ucfirst($userInfo['merchantName']); ?></span></h1>
            <h3 class="welcome-sub-text">Your performance summary this month </h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <p class="mb-1 mt-3 font-weight-semibold"><?= ucfirst($userInfo['merchantName']); ?></p>
                <p class="fw-light text-muted mb-0"><?= $userInfo['Email']; ?></p>
              </div>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
              <a class="dropdown-item" href="<?= site_url('/logout');?>"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border me-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border me-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="<?=site_url('dashboard')?>">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=site_url('booking')?>">
              <i class="mdi mdi-calendar-plus menu-icon"></i>
              <span class="menu-title">Book Now</span>
            </a>
          </li>
          <li class="nav-item nav-category">Pages</li>
          <li class="nav-item">
            <a class="nav-link" href="<?=site_url('transaction-history')?>" aria-expanded="false">
              <i class="menu-icon mdi mdi-card-text-outline"></i>
              <span class="menu-title">Transaction History</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="menu-icon mdi mdi-chart-line"></i>
              <span class="menu-title">Reports</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?=site_url('current-balance')?>">Current Balance</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?=site_url('statement-account')?>">Statement of Account</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://fastcat-book.com/feedback" target="_blank" aria-expanded="false">
              <i class="menu-icon mdi mdi-message"></i>
              <span class="menu-title">Send us Feedback</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="mailto:customercare.apfc@fastcat.com.ph" aria-expanded="false">
              <i class="menu-icon mdi mdi-headset"></i>
              <span class="menu-title">Customer Service</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=site_url('account-settings')?>" aria-expanded="false">
              <i class="menu-icon mdi mdi-account-settings"></i>
              <span class="menu-title">Account Settings</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url('/logout');?>">
              <i class="menu-icon mdi mdi-power"></i>
              <span class="menu-title">Sign Out</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                  <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <center><?= session()->getFlashdata('fail'); ?></center>
                    </div>
                  <?php endif ?>
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="" role="tab" aria-selected="false">Dashboard</a>
                    </li>
                  </ul>
                  <div>
                    <div class="btn-wrapper">
                      <a href="#" class="btn btn-otline-dark" onclick="Print()"><i class="icon-printer"></i> Print</a>
                      <a href="#" class="btn btn-primary text-white me-0" id="download"><i class="icon-download"></i> Export</a>
                    </div>
                  </div>
                </div>
                <div class="tab-content tab-content-basic" id="pdf">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="statistics-details d-flex align-items-center justify-content-between">
                          <div>
                            <p class="statistics-title">Monthly Transactions</p>
                            <h3 class="rate-percentage" id="monthlyExpense">0.00</h3>
                          </div>
                          <div>
                            <p class="statistics-title">Daily Transactions</p>
                            <h3 class="rate-percentage" id="dailyExpense">0.00</h3>
                          </div>
                          <div class="d-none d-md-block">
                            <p class="statistics-title">New Balance</p>
                            <h3 class="rate-percentage" id="balance">0.00</h3>
                          </div>
                          <div>
                            <p class="statistics-title">Total Booking</p>
                            <h3 class="rate-percentage" id="total">0</h3>
                          </div>
                          <div class="d-none d-md-block">
                            <p class="statistics-title">Pending</p>
                            <h3 class="rate-percentage" id="pending">0</h3>
                          </div>
                          <div class="d-none d-md-block">
                            <p class="statistics-title">Reserved</p>
                            <h3 class="rate-percentage" id="reserved">0</h3>
                          </div>
                        </div>
                      </div>
                    </div> 
                    <div class="row">
                      <div class="col-lg-8 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                  <div>
                                    <h4 class="card-title card-title-dash">Reservation Overview</h4>
                                   <p class="card-subtitle card-subtitle-dash">Total Daily Expenses per reservation</p>
                                  </div>
                                </div>
                                <div class="chartjs-bar-wrapper mt-3">
                                    <div id="chartContainer" style="height:300px;">
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                  <div>
                                    <h4 class="card-title card-title-dash">Reservation</h4>
                                  </div>
                                  <div>
                                      <a href="<?=site_url('booking')?>" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">
                                        <i class="mdi mdi-calendar-plus"></i>New Book
                                      </a>
                                  </div>
                                </div>
                                <div class="table-responsive">
                                  <table class="table select-table">
                                    <thead>
                                        <th>Date</th>
                                        <th>Booking #</th>
                                        <th>Route</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody id="tblrecent">
                                      
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                      <h4 class="card-title card-title-dash">Recent Schedules</h4>
                                    </div>
                                    <div class="list-wrapper">
                                      <ul class="todo-list todo-list-rounded" id="list_schedules">
    
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"><a href="https://www.bootstrapdash.com/" target="_blank">FerryLink Merchant Template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/Chart.roundedBarCharts.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
  <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
  <!-- End custom js for this page-->
    <script>
        $(document).ready(function(){recent();daily();monthly();total();pending();reserve();listSchedules();balance();
        });function monthly(){var user = <?php echo session()->get('merchantID') ?>;
            $.ajax({url:"<?=site_url('monthly-expense')?>",method:"GET",data:{user:user},success:function(data){$('#monthlyExpense').html(data);}
            });}function daily(){var user = <?php echo session()->get('merchantID') ?>;$.ajax({url:"<?=site_url('daily-expense')?>",method:"GET",data:{user:user},success:function(data){$('#dailyExpense').html(data);}});}function reserve(){var user = <?php echo session()->get('merchantID') ?>;$.ajax({url:"<?=site_url('total-reserved')?>",method:"GET",data:{user:user},success:function(data){if(data===""){$('#reserved').html("0");}else{$('#reserved').html(data);}}});}function pending(){var user = <?php echo session()->get('merchantID') ?>;$.ajax({url:"<?=site_url('total-pending')?>",method:"GET",data:{user:user},success:function(data){if(data===""){$('#pending').html("0");}else{$('#pending').html(data);}}});}function total(){var user = <?php echo session()->get('merchantID') ?>;$.ajax({url:"<?=site_url('total-book')?>",method:"GET",data:{user:user},success:function(data){if(data===""){$('#total').html("0");}else{$('#total').html(data);}}});}function recent(){var user = <?php echo session()->get('merchantID') ?>;$.ajax({url:"<?=site_url('recent')?>",method:"GET",data:{user:user},success:function(data){if(data===""){$('#tblrecent').html("<tr><td colspan='5'><center>No Data</center></td></tr>");}else{$('#tblrecent').html(data);}}});}function listSchedules(){$.ajax({url:"<?=site_url('list-schedules')?>",method:"GET",success:function(response){if(response===""){$('#list_schedules').html("<li class='d-block'><center>No Record(s)</center></li>");}else{$('#list_schedules').html(response);}}});}function balance(){var user = <?php echo session()->get('loggedUser') ?>;$.ajax({url:"<?=site_url('get-balance')?>",method:"GET",data:{user:user},success:function(response){if(response==""){$('#balance').html("0.00");}else{$('#balance').html(response);}}});}
        google.charts.setOnLoadCallback(reservationChart);
        function reservationChart() 
        {
 
            /* Define the chart to be drawn.*/
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Amount'],
                <?php 
                 foreach ($query as $row){
                 echo "['".$row->TrxnDate."',".$row->total."],";
                 }
                 ?>
            ]);

            var options = {
              title: '',
              curveType: 'function',
              legend: { position: 'bottom' }
            };
            /* Instantiate and draw the chart.*/
            var chart = new google.visualization.ColumnChart(document.getElementById('chartContainer'));
            chart.draw(data, options);
      }
    function Print() {
        var printContents = document.getElementById("pdf").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    $('#download').on('click',function()
    {
        $('#pdf').css("display", "block");
        var HTML_Width = $("#pdf").width();
        var HTML_Height = $("#pdf").height();
        var top_left_margin = 15;
        var PDF_Width = HTML_Width + (top_left_margin * 2);
        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);//* 1.5
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;
            
        var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
            
        html2canvas($("#pdf")[0]).then(function (canvas) {
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            pdf.save("report.pdf");
        });
    });
    </script>
</body>

</html>

