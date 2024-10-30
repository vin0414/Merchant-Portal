
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FastCat Merchant Portal - Book Now</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/fastcat.png" />
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
            width: 0px;               /* width of vertical scrollbar */
            border: 1px solid #d5d5d5;
          }
        th,td{padding:10px;}
        .radio-buttons 
        {
          width: 100%;
          margin: 0 auto;
          text-align: center;
        }

        .custom-radio input 
        {
          display: none;
        }

        .radio-btn 
        {
          margin: 10px;
          width: 100px;
          height: 30px;
          border: 3px solid transparent;
          display: inline-block;
          border-radius: 10px;
          position: relative;
          text-align: center;
          cursor: pointer;
        }

        .radio-btn > i {
          color: #ffffff;
          background-color: #CC5500;
          font-size: 10px;
          position: absolute;
          top: -15px;
          left: 50%;
          border-radius: 50px;
          padding: 3px;
          transition: 0.5s;
          pointer-events: none;
          opacity: 0;
        }

        .radio-btn .hobbies-icon 
        {
          position: absolute;
          top: 40%;
          left: 50%;
          transform: translate(-50%, -50%);
        }
        .radio-btn .hobbies-icon img
        {
          display:block;
          width:100%;
          margin-bottom:20px;

        }
        .radio-btn .hobbies-icon i 
        {
          color: #FFDAE9;
          line-height: 80px;
          font-size: 10px;
        }

        .radio-btn .hobbies-icon h3 
        {
          color: #555;
          font-size: 10px;
          font-weight: 300;
          text-transform: uppercase;
          letter-spacing:1px;
        }

        .custom-radio input:checked + .radio-btn 
        {
          border: 2px solid #CC5500;
          background-color: #CC5500;
        }

        .custom-radio input:checked + .radio-btn > i 
        {
          opacity: 1;
          transform: translateX(-50%) scale(1);
        }
    </style>
    <script>
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
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
            <h3 class="welcome-sub-text"><?= $title ?></h3>
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
            <div class="row g-3">
                <div class="col-lg-9">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="card-title"><span class="mdi mdi-calendar"></span> Schedule</div>
                                    <form method="GET" class="row g-3" id="frmBook">
                                        <div class="col-lg-3">
                                            <select class="form-control form-control-lg" name="origin" id="origin">
                                                <option value="">Origin</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="form-control form-control-lg" name="destination" id="destination">
                                                <option value="">Destination</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="date" class="form-control form-control-lg" name="departureDate" id="departureDate"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary form-control form-control-lg" id="search"><span class="mdi mdi-magnify"></span> Search Trips</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <br/>
                            <div class="row g-3" id="results" style="height:400px;overflow-y:auto;">
                            </div>
                        </div>
                        <div class="col-12">
                            <p>Total: <label class="badge bg-primary" id="total">0</label></p>
                        </div>
                    </div> 
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Details</h5>
                            <div id="hideMessage">No Selected Schedule to continue booking</div>
                            <form method="post" class="forms-sample" action="<?=site_url('add-passenger')?>" id="hideForm" style="display:none;">
                                <input type="hidden" id="departure" name="departure"/>
                                <input type="hidden" id="portName" name="portID"/>
                                <input type="hidden" id="date_depart" name="date"/>
                                <div class="list-wrapper">
                                    <ul class="todo-list todo-list-rounded">
                                        <li class="d-block">
                                            <div class="w-100">
                                                <label class="form-check-label" id="departure_date"></label>
                                                <div class="d-flex mt-2">
                                                    <label id="fromPort" style="font-weight:bold;"></label>
                                                    &nbsp;<span class="mdi mdi-arrow-right"></span>&nbsp;
                                                    <label id="toPort" style="font-weight:bold;float:right;"></label>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-block">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label>Departure</label>
                                                    <label id="timeDepart" style="font-weight:bold;"></label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>Arrival</label>
                                                    <label id="timeArrival" style="font-weight:bold;"></label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <br/>
                                <!--<input type="checkbox" class="form-check-input" value="upgrade" id="upgrade" onclick="upgradeFunction()"/>&nbsp;Change?-->
                                <div class="form-group" id="selectAccommodation" required>
                                    <p>Accommodation</p>
                                    <select class="form-control form-control-lg" name="accommodation" id="accommodation" required>
                                        <option value="">Choose</option>
                                        <!--<option>Economy</option>-->
                                        <!--<option>Premium Economy</option>-->
                                        <!--<option>Premium Economy w/o AC</option>-->
                                        <!--<option>Business Class</option>-->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="font-size:10px;">PHP</label>
                                    <label id="rate" style="font-weight:bold;font-size:20px;">0.00</label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="btnProceed" disabled><span class="mdi mdi-arrow-right"></span> Proceed</button>
                                </div>
                                <div class="form-group" id="addBookingLoading" style="display:none;">
                                    <button class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br/>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">Seat Capacity</div>
                            <div class="table-responsive">
                                <table class="table-bordered" style="width:100%;font-size:12px;">
                                    <thead>
                                        <th class="bg-primary text-white">Accommodation</th>
                                        <th class="bg-primary text-white">Seat</th>
                                    </thead>
                                    <tbody id="tblavailable"></tbody>
                                </table>
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
  <!-- End custom js for this page-->
    <script>
        var date = new Date(); // Now
        date.setDate(date.getDate());
        $('#departureDate').val(convert(date));
        $('#departureDate').attr('min',convert(date));
        function convert(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }
        function upgradeFunction()
        {
            var checkBox = document.getElementById("upgrade");
            if (checkBox.checked == true){
                $('#selectAccommodation').css("display","block");
            }
            else
            {
                $('#selectAccommodation').css("display","none");
                document.getElementById("accommodation").selectedIndex = 0;
                getRate();
            }
        }
        $(document).ready(function()
        {
            loadOrigin();
        });
        const getAccommodations = () => {
            $('#btnProceed').attr("disabled",true);
            document.getElementById("btnProceed").style="display:none";
            document.getElementById("addBookingLoading").style="display:block";
                
            const choice = 'one-way';
            const departID = $('#departure').val();
            const fdate = $('#date_depart').val();
            const port = $('#fromPort').text();
            $.ajax({
                url:"<?=site_url('get-online-accommodations')?>",
                method:"GET",
                data:{
                    departID:departID,
                    returnID:'',
                    fromdate:fdate,
                    todate:'',
                    choice:choice,
                    departureVessel: '',
                    returnVessel: '',
                    port:port
                },
                success:function(data)
                {
                    $('#accommodation').html(data);
                    document.getElementById("addBookingLoading").style="display:none";
                    document.getElementById("btnProceed").style="display:block";
                    $('#btnProceed').attr("disabled",false);
                    
                }
            });
        }
        function getTime()
        {
            fetch("https://worldtimeapi.org/api/timezone/Asia/Manila"
              )
              .then((response) => response.json())
              .then((data) => {
                let responseDate = data.datetime.split('T');
                $('#dates').html(responseDate[0]);
                $('#times').html(responseDate[1].substring(0, 8));
                document.getElementById("date").value=responseDate[0];
                document.getElementById("time").value=responseDate[1].substring(0, 8);
              });
            setTimeout(showTime, 1000);
        }
        function getRate()
        {
           var val = "Economy";
           var id = $('#departure').val();
           $.ajax({
               url:"<?=site_url('fetch-rate')?>",method:"GET",
               data:{schedule:id,accommodation:val},
               success:function(response)
               {
                   if(response===""){
                       $('#rate').html("0.00"); 
                       $('#btnProceed').attr("disabled",true);
                   }
                   else{
                       $('#rate').html(response);   
                       $('#btnProceed').attr("disabled",false);
                   }
               }
           });
        }
        $('#accommodation').change(function(){
           var val = $(this).val();
           var id = $('#departure').val();
           $.ajax({
               url:"<?=site_url('fetch-rate')?>",method:"GET",
               data:{schedule:id,accommodation:val},
               success:function(response)
               {
                   if(response===""){
                       $('#rate').html("0.00"); 
                       $('#btnProceed').attr("disabled",true);
                   }
                   else{
                       $('#rate').html(response);   
                       $('#btnProceed').attr("disabled",false);
                   }
               }
           });
        });
        function loadOrigin()
        {
            $.ajax({
                url:"<?= site_url('origin') ?>",method:"GET",
                success:function(data)
                {
                    $('#origin').append(data);
                }
            });
        }
        $('#origin').change(function()
          {
              var val = $(this).val();
              $('#destination').find('option').remove();
              $.ajax({
                    url:"<?= site_url('destination') ?>",method:"GET",
                    data:{origin:val},
                    success:function(data)
                    {
                        $('#destination').append(data);
                    }
                });
          });
        $(document).on('click','.choose',function()
        {
            $('#hideForm').slideDown();
            $('#hideMessage').slideUp();
            var val = $(this).val();
            $('#departure').attr("value",val);
            var date_now = $('#departureDate').val();
            $('#date_depart').attr("value",date_now);
            var port = $('#port').val();
            var origin = $('#origin').val();
            var destination = $('#destination').val();
            $.ajax({
                url:"<?=site_url('departure-time')?>",method:"GET",
                data:{value:val},dataType:"json",
                success:function(data)
                {
                    $('#timeDepart').html(data["departure"]);
                    $('#timeArrival').html(data["arrival"]);
                    $('#fromPort').html(origin);
                    $('#toPort').html(destination);
                    $('#portName').attr("value",port);
                    getRate();
                    var date = new Date(date_now);
                    var month = new Array();
                      month[0] = "Jan";
                      month[1] = "Feb";
                      month[2] = "Mar";
                      month[3] = "Apr";
                      month[4] = "May";
                      month[5] = "Jun";
                      month[6] = "Jul";
                      month[7] = "Aug";
                      month[8] = "Sept";
                      month[9] = "Oct";
                      month[10] = "Nov";
                      month[11] = "Dec";

                      day = date.getDate();

                      if(day < 10)
                      {
                         day = "0"+day;
                      }
                    $('#departure_date').html(day  + "-" +month[date.getMonth()] + " " + date.getFullYear());
                    getAccommodations();
                }
            });
            $('#tblavailable').html("<tr><td colspan='2'><center>Loading...</center></td></tr>");
            $.ajax({
                url:"<?=site_url('available-seats')?>",method:"GET",
                data:{value:val,date:date_now},
                success:function(data)
                {
                    $('#tblavailable').html(data);
                }
            });
        });
        $('#search').on('click',function(e)
        {
            e.preventDefault();
            var data = $('#frmBook').serialize();
            $('#hideForm').slideUp();
            $('#hideMessage').slideDown();
            $('#results').html("<div style='margin-top:50px;padding:10px;'><center>Loading...</center></div>");
            $.ajax({
                url:"<?=site_url('get-schedules')?>",method:"GET",
                data:data,
                success:function(data)
                {
                    if(data===""){
                        $('#results').html("<div style='margin-top:50px;padding:10px;'><center>No Available schedule(s)</center></div>");   
                    }
                    else{
                        $('#results').html(data); 
                    }
                    var count = $('#results').children('div').length;
                    $('#total').html(count);
                }
            });
        });
    </script>
</body>

</html>

