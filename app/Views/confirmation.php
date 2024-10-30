
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FastCat Merchant Portal - Confirmationr</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="/assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="/assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="/assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="/assets/images/fastcat.png" />
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
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
            <img src="/assets/images/fastcat.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="/dashboard">
            <img src="/assets/images/fastcat.png" alt="logo" />
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
          <li class="nav-item active">
            <a class="nav-link" href="javascript:void(0);">
              <i class="mdi mdi-cart menu-icon"></i>
              <span class="menu-title">Confirmation</span>
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
            <div class="alert alert-primary" role="alert">
                <span class="mdi mdi-information-outline"></span> Kindly download and present your "Trip Ticket/e-Ticket" to the staff for verification of reservation.
            </div>
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Passenger Details</div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="tblcustomer">
                                    <thead>
                                        <th>Passenger</th>
                                        <th>Accommodation</th>
                                        <th>Seat No</th>
                                        <th>Discount</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                       <?php if($passenger): ?>  
                                            <?php foreach($passenger as $row): ?>
                                            <tr>
                                                <td><?php echo $row['Fullname']; ?></td>
                                                <td><?php echo $row['Accommodation']; ?></td>
                                                <td><?php echo $row['SeatNumber']; ?></td>
                                                <td><?php echo $row['Discount']*100; ?>%</td>
                                                <td style="text-align:right;"><?php echo number_format($row['Amount'],2); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm edit" value="<?php echo $row['recordID'] ?>">
                                                        <span class="mdi mdi-pencil"></span> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Vehicle Details</div>
                            <div class="table-reponsive">
                                <table class="table table-striped" id="tblcargo">
                                    <thead>
                                        <th>Vehicle Type</th>
                                        <th>Model</th>
                                        <th>Plate No</th>
                                    </thead>
                                    <tbody>
                                       <?php if($vehicle): ?>  
                                            <?php foreach($vehicle as $row): ?>
                                            <tr>
                                                <td><?php echo $row->Name ?></td>
                                                <td><?php echo $row->model ?></td>
                                                <td><?php echo $row->plate_number ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Payment Summary
                                <a href="javascript:void(0);" style="float:right;text-decoration:none;font-size:12px;" data-bs-toggle="popover" title="Administrative Fee" data-bs-content="Handling and processing transactions or providing services. System maintenance and enhancement">FAQ&nbsp;<span class="mdi mdi-information-outline"></span></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody id="tblpayment"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Total Amount</div>
                            <h1 id="total"><?php if($payment): ?>
                                <?php foreach($payment as $row): ?>
                                <?php echo number_format($row['TotalAmount'],2) ?>
                                <?php endforeach; ?><?php endif; ?>
                            </h1>
                            <?php
                            if($userInfo['merchantType']=="Travel Agency")
                            {
                                ?>
                                <a href="/e-ticket/<?= $code; ?>" class="btn btn-outline-primary btn-sm" id="btnDownload">
                                <span class="mdi mdi-download"></span>&nbsp;Download e-Ticket</a>
                                <?php
                            }
                            else
                            {
                            ?>
                            <a href="/download/<?= $code; ?>" class="btn btn-outline-primary btn-sm" id="btnDownload">
                                <span class="mdi mdi-download"></span>&nbsp;Download</a>
                            <?php } ?>
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
  <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <script src="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="/assets/js/off-canvas.js"></script>
  <script src="/assets/js/hoverable-collapse.js"></script>
  <script src="/assets/js/template.js"></script>
  <script src="/assets/js/settings.js"></script>
  <script src="/assets/js/todolist.js"></script>
  <script src="/assets/js/tooltips.js"></script>
  <script src="/assets/js/popover.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <!-- endinject -->
  <!-- End custom js for this page-->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <div class="modal-title" id="exampleModalLabel">Edit Details</div>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="result"></div>    
        </div>
      </div>
    </div>
  </div>
    <script>
       
       $(document).ready(function()
       {
          loadPayment(); 
       });
        $(document).on('click','.update',function(e){
            e.preventDefault();
            var data = $('#frmDetails').serialize();
            $.ajax({
                url:"<?=site_url("save")?>",method:"POST",
                data:data,
                success:function(response)
                {
                    alertify.success(response);
                    $('#viewModal').modal('hide');
                    location.reload();
                }
            });
        });
        $(document).on('click','.edit',function(){
           var ask = confirm("Do you want to modify the passenger details?");
           if(ask){
               var val = $(this).val();
               $.ajax({
                   url:"<?=site_url('view-details')?>",method:"GET",
                   data:{value:val},
                   success:function(data)
                   {
                       $('#viewModal').modal('show');
                       $('#result').html(data);
                   }
               });
           }
        });
        function loadPayment()
        {
            var user = <?php echo session()->get('loggedUser') ?>;
            var code = "<?php echo $code ?>";
            $.ajax({
                url:"<?=site_url('break-down')?>",method:"GET",
                data:{user:user,code:code},
                success:function(response)
                {
                    $('#tblpayment').html(response);
                }
            });
        }
    </script>
</body>

</html>

