
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FastCat Merchant Portal - Account Setting</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
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
            <div class="row">
                <div class="col-lg-8 d-flex flex-column">
                    <div class="row flex-grow">
                        <div class="col-12 grid-margin">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="d-sm-flex justify-content-between align-items-start">
                                        <div>
                                            <h4 class="card-title card-title-dash">Account Details</h4>
                                        </div>
                                    </div>
                                    <form method="post" class="forms-sample" action="<?=site_url('update-information')?>">
                                        <?php if(!empty(session()->getFlashdata('failed'))) : ?>
                                            <div class="alert alert-danger" role="alert">
                                                <center><?= session()->getFlashdata('failed'); ?></center>
                                            </div>
                                        <?php endif ?>
                                        <?php if(!empty(session()->getFlashdata('successful'))) : ?>
                                            <div class="alert alert-success" role="alert">
                                                <center><?= session()->getFlashdata('successful'); ?></center>
                                            </div>
                                        <?php endif ?>
                                        <input type="hidden" name="merchantID" value="<?php echo session()->get('merchantID'); ?>"/>
                                        <div class="form-group">
                                            <label>Merchant's Name</label>
                                            <input type="text" class="form-control form-control-lg" name="merchantName" value="<?=$merchant['Agent_Name']?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Merchant's Address</label>
                                            <textarea class="form-control" name="address" style="height:120px;"><?=$merchant['Address']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label>Merchant's Contact #</label>
                                                    <input type="phone" class="form-control form-control-lg" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" name="contactNumber" value="<?=$merchant['Number']?>" maxlength="11"/>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>Merchant's Email Address</label>
                                                    <input type="email" class="form-control form-control-lg" name="email" value="<?=$merchant['EmailAddress']?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" id="btnUpdate" value="Save Changes"/>
                                        </div>
                                    </form>
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
                                    <div class="d-sm-flex justify-content-between align-items-start">
                                        <div>
                                            <h4 class="card-title card-title-dash">Change Password</h4>
                                        </div>
                                    </div>
                                    <form method="post" class="forms-sample" action="<?=site_url('updatePassword')?>">
                                        <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                                            <div class="alert alert-danger" role="alert">
                                                <center><?= session()->getFlashdata('fail'); ?></center>
                                            </div>
                                        <?php endif ?>
                                        <?php if(!empty(session()->getFlashdata('success'))) : ?>
                                            <div class="alert alert-success" role="alert">
                                                <center><?= session()->getFlashdata('success'); ?></center>
                                            </div>
                                        <?php endif ?>
                                        <input type="hidden" name="merchantID" value="<?php echo session()->get('loggedUser'); ?>"/>
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" class="form-control form-control-lg" id="current_password" name="current_password" maxlength="16" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" class="form-control form-control-lg" name="new_password" id="new_password" maxlength="16" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control form-control-lg" name="confirm_password" id="confirm_password" maxlength="16" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                                        </div>
                                        <div class="form-group">
                                             <input type="checkbox" onclick="myFunction()"> Show Password
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" id="btnSave" value="Save Changes"/>
                                        </div>
                                    </form>
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
  <!-- End custom js for this page-->
    <script>
        function myFunction() {
            var x = document.getElementById("current_password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
            var xx = document.getElementById("new_password");
            if (xx.type === "password") {
                xx.type = "text";
            } else {
                xx.type = "password";
            }
            var xxs = document.getElementById("confirm_password");
            if (xxs.type === "password") {
                xxs.type = "text";
            } else {
                xxs.type = "password";
            }
        }
    </script>
</body>

</html>

