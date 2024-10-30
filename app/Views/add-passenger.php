
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FastCat Merchant Portal - Add Passenger</title>
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
          <a class="navbar-brand brand-logo" href="javascript:void(0);">
            <img src="assets/images/fastcat.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="javascript:void(0);">
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
              <a class="dropdown-item" href="javascript:void(0);" id="btnOut"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
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
            <a class="nav-link" href="javascript:void(0);" id="btnConfirm">
              <i class="mdi mdi-arrow-left menu-icon"></i>
              <span class="menu-title">Back to Dashboard</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="javascript:void(0);">
              <i class="mdi mdi-account-multiple-plus menu-icon"></i>
              <span class="menu-title">Add Passenger</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Passenger Details</div>
                            <form method="post" class="forms-sample" id="frmPassenger">
                                <input type="hidden" name="schedule" id="schedule" value="<?php echo $_POST['departure'] ?>"/> 
                                <input type="hidden" name="port" id="port" value="<?php echo base64_decode(urldecode($_POST['portID'])) ?>"/> 
                                <input type="hidden" name="user" id="user" value="<?php echo session()->get('loggedUser') ?>"/>
                                <input type="hidden" name="date" id="date" value="<?php echo $_POST['date'] ?>"/>
                                <input type="hidden" name="agent" value="<?=$merchant['agentID']?>"/>
                                <div class="col-12">
                                    <label><small>With Vehicle : </small></label>
                                    <input type="radio" class="btn form-input-check" name="passenger_type" style="width:20px;height:20px;" value="FULL" checked/> No
                                    &nbsp;
                                    <input type="radio" class="btn form-input-check" name="passenger_type" style="width:20px;height:20px;" value="DRIVER"/> Yes
                                </div>
                                <div class="form-group" style="display:none;" id="withVehicle">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label>Type of Vehicle</label>
                                            <select name="vehicle" id="vehicle" class="form-control form-control-lg">
                                                <option value="">Choose</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Model</label>
                                            <select name="model" id="model" class="form-control form-control-lg">
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Plate No</label>
                                            <input type="text" name="plateNumber" id="plateNumber" class="form-control form-control-lg"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Institutional Account</label>
                                    <input type="text" class="form-control form-control-lg" value="<?=$merchant['Agent_Name']?>" name="inst_account"  style="background-color:#ffffff;" readonly/>
                                </div>
                                <div class="form-group">
                                    <div class="row g-3">
                                        <div class="col-lg-3">
                                            <label>Discount (%)</label>
                                            <input type="text" class="form-control form-control-lg" name="discount" id="discount"  style="background-color:#ffffff;"  readonly/>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Accommodation</label>
                                            <input type="text" class="form-control form-control-lg" id="accommodation" name="accommodation" value="<?php echo $_POST['accommodation'] ?>"  style="background-color:#ffffff;" readonly/>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Passenger Type</label>
                                            <select class="form-control form-control-lg" name="customer_type" id="customer_type">
                                                <option value="">Choose</option>
                                                <option>Adult</option>
                                                <option>Driver</option>
                                                <option>PWD</option>
                                                <option>Senior Citizen</option>
                                                <option>Student</option>
                                                <option>Children (4 to 7 years old)</option>
                                                <option>Children 3 years old and below</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Passenger's Name</label>
                                    <input type="text" class="form-control form-control-lg" name="passenger"/>
                                </div>
                                <div class="form-group">
                                    <label>Address (Optional)</label>
                                    <input type="text" class="form-control form-control-lg" name="address"/>
                                </div>
                                <div class="form-group">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label>Contact No</label>
                                            <input type="phone" name="phone" id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  class="form-control form-control-lg" maxlength="11"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Gender</label>
                                            <select name="gender" id="gender" class="form-control form-control-lg">
                                                <option value="">Choose</option>
                                                <option>Male</option>
                                                <option>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Age</label>
                                            <input type="number" name="age" id="age" class="form-control form-control-lg"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" id="btnAdd" value="Save Records"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card card-rounded">
                        <div class="card-body">
                            <div class="card-title">Summary <button type="button" style="float:right" class="btn btn-default btn-sm" id="total_amount"></button></div>
                            <div>
                                <label>Port/Terminal : <label id="selectedPort"></label></label>
                            </div>
                            <div>
                                <label>Departure Time : <label id="selectedDeparture"></label></label>
                            </div>
                            <div class="list-wrapper">
                                <ul class="todo-list todo-list-rounded" id="passenger_list" style="height:400px;overflow-y:auto;">
                                </ul>
                                <span class="badge bg-primary">Results : <label id="total">0</label></span>
                            </div>
                            <form method="post" action="<?=base_url('confirmation')?>">
                                <input type="hidden" name="schedule" id="schedule" value="<?php echo $_POST['departure'] ?>"/> 
                                <input type="hidden" name="port" id="port" value="<?php echo base64_decode(urldecode($_POST['portID'])) ?>"/> 
                                <input type="hidden" name="user" id="user" value="<?php echo session()->get('loggedUser') ?>"/>
                                <input type="hidden" name="date" id="date" value="<?php echo $_POST['date'] ?>"/>
                                <input type="hidden" name="agent" value="<?=$merchant['agentID']?>"/>
                                <button type="submit" class="btn btn-primary btn-md" id="btnContinue" style="float:right;" onclick="return confirm('Are you sure you want to submit this form?');" disabled><span class="mdi mdi-arrow-right"></span> Proceed</button>    
                            </form>
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
        $(document).ready(function(){
           Discount();loadPassenger();getSchedule();
        });
        function getSchedule()
        {
            var id = $('#schedule').val();
            $.ajax({
                url:"<?=site_url('get-schedule')?>",method:"GET",
                data:{value:id},
                success:function(response)
                {
                    $('#selectedDeparture').html(response);
                }
            });
            //port
            var port = $('#port').val();
            $.ajax({
                url:"<?=site_url('get-port')?>",method:"GET",
                data:{port:port},
                success:function(response)
                {
                    $('#selectedPort').html(response);
                }
            });
        }
        function validatePassenger()
        {
            //check if vehicle type added
            //activate the FOC feature if exist
            var user = <?php echo session()->get('merchantID') ?>;
            var id = $('#schedule').val();
            var date = $('#date').val();
            $.ajax({
                url:"<?=site_url('verification')?>",method:"GET",
                data:{user:user,schedule:id,date:date},
                success:function(response)
                {
                    var option = document.createElement("option");
                    option.text = "Free of Charge";
                    option.value = "FOC";
                    if(response==="")
                    {
                        var selectobject = document.getElementById("customer_type");
                        for (var i=0; i<selectobject.length; i++) {
                            if (selectobject.options[i].value == 'FOC')
                                selectobject.remove(i);
                        }
                    }
                    else if(response==="0"){
                        var selectobject = document.getElementById("customer_type");
                        for (var i=0; i<selectobject.length; i++) {
                            if (selectobject.options[i].value == 'FOC')
                                selectobject.remove(i);
                        }
                    }
                    else{
                        
                        if($("#customer_type option[value='FOC']").length > 0){ 
                        }else{
                        var select = document.getElementById("customer_type");
                        select.appendChild(option);
                        }
                    }
                }
            });
        }
        $('#btnOut').on('click',function(e){
           e.preventDefault();
           alertify.confirm('<span class="mdi mdi-alert-box"></span> System Message', '<center><h2>Do you want to leave booking page?</h2>Leaving the page will clear the current booking data.</center>', function(){ 
               //delete all the records
                var user = <?php echo session()->get('loggedUser') ?>;
                var id = $('#schedule').val();
                var date = $('#date').val();
                $.ajax({
                    url:"<?=site_url('remove-all')?>",method:"POST",
                    data:{user:user,schedule:id,date:date},
                    success:function(response)
                    {
                        console.log(response);
                        window.location.href="<?= site_url('/logout');?>";
                    }
                });
           }, function(){ 
               //do nothing
           });
        });
        $('#btnConfirm').on('click',function(e){
           e.preventDefault();
           alertify.confirm('<span class="mdi mdi-alert-box"></span> System Message', '<center><h2>Do you want to leave booking page?</h2>Leaving the page will clear the current booking data.</center>', function(){ 
               //delete all the records
                var user = <?php echo session()->get('loggedUser') ?>;
                var id = $('#schedule').val();
                var date = $('#date').val();
                $.ajax({
                    url:"<?=site_url('remove-all')?>",method:"POST",
                    data:{user:user,schedule:id,date:date},
                    success:function(response)
                    {
                        console.log(response);
                        window.location.href="<?=site_url('dashboard')?>";
                    }
                });
           }, function(){ 
               //do nothing
           });
        });
        $(document).on('click','.delete',function(){
            var ask = confirm("Do you want to remove this selected record?");
            if(ask){
                var val = $(this).val();
                $.ajax({
                    url:"<?=site_url('remove-passenger')?>",method:"POST",
                    data:{value:val},
                    success:function(response)
                    {
                        if(response==="Success"){
                            alertify.success("Successfully removed!");
                            loadPassenger();
                        }
                        else{
                           alertify.error(response); 
                        }
                    }
                });
            }
        });
        function Discount()
        {
            var port = $('#port').val();
            var agent = <?=$merchant['agentID']?>;
            $.ajax({
                url:"<?=site_url('discount')?>",method:"GET",
                data:{port:port,agent:agent},
                success:function(data)
                {
                    if(data===""){$('#discount').attr("value","0.00");}else{$('#discount').attr("value",data);}
                }
            });
        }
        $('input:radio[name="passenger_type"]').change(function() {
            if ($(this).val() === 'DRIVER') {
                $('#withVehicle').slideDown();
                Vehicles(); 
            } else {
                $('#withVehicle').slideUp();
                $('#model').find('option').remove();
                $("#vehicle").val($("#vehicle option:first").val());
            }
        });
        function Vehicles()
        {
            $.ajax({
                url:"<?=site_url('vehicles')?>",method:"GET",
                success:function(response)
                {
                    $('#vehicle').append(response);
                }
            });
        }
        
        $('#vehicle').change(function(e){
            var val = $(this).val();
            $('#model').find('option').remove();
            $.ajax({
                url:"<?=site_url('vehicle-model')?>",method:"GET",
                data:{value:val},
                success:function(response)
                {
                    $('#model').append(response);
                }
            });
        });
        function loadPassenger()
        {
            var user = <?php echo session()->get('loggedUser') ?>;
            var id = $('#schedule').val();
            var date = $('#date').val();
            $.ajax({
                url:"<?=site_url('populate')?>",method:"GET",
                data:{user:user,schedule:id,date:date},
                success:function(response)
                {
                    if(response===""){
                        $('#passenger_list').html("<li class='d-block'><center>No Record(s)</center></li>");
                        $('#btnContinue').attr("disabled",true);
                    }
                    else{
                        $('#passenger_list').html(response);
                        $('#btnContinue').attr("disabled",false);
                    }
                    var count = $('#passenger_list').children('li').length;
                    $('#total').html(count);
                }
            });
            //generate the total amount
            $.ajax({
                url:"<?=site_url('summary')?>",method:"GET",
                data:{user:user,schedule:id,date:date},
                success:function(response)
                {
                    $('#total_amount').html("Total Amount : " + response);   
                }
            });
        }
        
        $('#btnAdd').on('click',function(e){
            e.preventDefault(); 
            var data = $('#frmPassenger').serialize();
            $(this).attr("value","Saving. Please wait!");
            $.ajax({
                url:"<?=site_url('save-passenger')?>",method:"POST",
                data:data,
                success:function(response)
                {
                    if(response==="Success"){
                        alertify.success("Successfully saved!");
                        $('#frmPassenger')[0].reset();
                        loadPassenger();
                        $('#withVehicle').slideUp();$('#model').find('option').remove();
                    }
                    else{
                        alertify.error(response);
                    }
                    validatePassenger();
                    $('#btnAdd').attr("value","Save Records");
                }
            });
        });
    </script>
</body>

</html>

