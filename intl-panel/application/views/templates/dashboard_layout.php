<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($page_title) ? $page_title : 'Courier ERP'; ?> | International Panel</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- CSS assets -->
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/skin-blue-light.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/time-line.css'); ?>">
  
  <!-- SweetAlert2 & Google Fonts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    .main-sidebar {
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .box {
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      border-top: 3px solid #3c8dbc;
    }
    .info-box {
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
  </style>

  <!-- jQuery must load in head for some modules -->
  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">
    <a href="<?php echo site_url('dashboard'); ?>" class="logo">
      <span class="logo-mini"><b>C</b>S</span>
      <span class="logo-lg">
        <?php if(defined('COMPANY_LOGO') && !empty(COMPANY_LOGO)): ?>
          <img src="<?php echo base_url('assets/img/' . COMPANY_LOGO); ?>" alt="Logo" style="max-height: 40px; margin-top: -5px;">
        <?php else: ?>
          <b><?php echo defined('COMPANY_NAME') && !empty(COMPANY_NAME) ? explode(' ', COMPANY_NAME)[0] : 'Courier'; ?></b><?php echo defined('COMPANY_NAME') && count(explode(' ', COMPANY_NAME)) > 1 ? explode(' ', COMPANY_NAME)[1] : 'Syndicate'; ?>
        <?php endif; ?>
      </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="user user-menu">
            <a href="#">
              <i class="fa fa-user-circle"></i>
              <span class="hidden-xs"><?php echo $this->session->userdata('username'); ?> (<?php echo $this->session->userdata('role_name'); ?>)</span>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('logout'); ?>" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left Sidebar -->
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        
        <li class="<?php echo ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>">
          <a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
        </li>

        <?php if ($this->session->userdata('role_id') != 4): // Staff Menu ?>
          
          <li class="<?php echo ($this->uri->segment(1) == 'shipments') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('shipments'); ?>"><i class="fa fa-cubes"></i> <span>Shipment Bookings</span></a>
          </li>

          <?php if ($this->session->userdata('role_id') != 3): // Hide for Franchise Users ?>
            <li class="<?php echo ($this->uri->segment(1) == 'kyc-requests') ? 'active' : ''; ?>">
              <a href="<?php echo site_url('kyc-requests'); ?>"><i class="fa fa-id-card"></i> <span>KYC Requests</span></a>
            </li>

            <li class="<?php echo ($this->uri->segment(1) == 'customers') ? 'active' : ''; ?>">
              <a href="<?php echo site_url('customers'); ?>"><i class="fa fa-users"></i> <span>Customers</span></a>
            </li>

            <?php if ($this->session->userdata('role_id') == 1): // Only Super Admin ?>
            <li class="treeview <?php echo in_array($this->uri->segment(1), array('branches', 'franchises', 'countries', 'partners', 'rates', 'terms', 'movement-stages', 'service-types', 'restricted-items', 'app-settings', 'notification-logs', 'roles')) ? 'active menu-open' : ''; ?>">
              <a href="#"><i class="fa fa-gears"></i> <span>Master Settings</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php echo ($this->uri->segment(1) == 'roles') ? 'active' : ''; ?>"><a href="<?php echo site_url('roles'); ?>"><i class="fa fa-circle-o"></i> Roles &amp; Permissions</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'branches') ? 'active' : ''; ?>"><a href="<?php echo site_url('branches'); ?>"><i class="fa fa-circle-o"></i> Branches</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'franchises') ? 'active' : ''; ?>"><a href="<?php echo site_url('franchises'); ?>"><i class="fa fa-circle-o"></i> Franchises</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'countries') ? 'active' : ''; ?>"><a href="<?php echo site_url('countries'); ?>"><i class="fa fa-circle-o"></i> Countries</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'partners') ? 'active' : ''; ?>"><a href="<?php echo site_url('partners'); ?>"><i class="fa fa-circle-o"></i> Courier Partners</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'rates') ? 'active' : ''; ?>"><a href="<?php echo site_url('rates'); ?>"><i class="fa fa-circle-o"></i> Shipping Rates Matrix</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'terms') ? 'active' : ''; ?>"><a href="<?php echo site_url('terms'); ?>"><i class="fa fa-circle-o"></i> Terms & Conditions</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'movement-stages') ? 'active' : ''; ?>"><a href="<?php echo site_url('movement-stages'); ?>"><i class="fa fa-circle-o"></i> Movement Status Stages</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'service-types') ? 'active' : ''; ?>"><a href="<?php echo site_url('service-types'); ?>"><i class="fa fa-circle-o"></i> Service Types</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'restricted-items') ? 'active' : ''; ?>"><a href="<?php echo site_url('restricted-items'); ?>"><i class="fa fa-circle-o"></i> Restricted Items Warns</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'app-settings') ? 'active' : ''; ?>"><a href="<?php echo site_url('app-settings'); ?>"><i class="fa fa-circle-o"></i> App Settings</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'notification-logs') ? 'active' : ''; ?>"><a href="<?php echo site_url('notification-logs'); ?>"><i class="fa fa-circle-o"></i> Notification Logs</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <li class="<?php echo ($this->uri->segment(1) == 'customer' && $this->uri->segment(2) == 'statement') ? 'active' : ''; ?>">
              <a href="<?php echo site_url('customer/statement'); ?>"><i class="fa fa-file-text-o"></i> <span>Customer Statements</span></a>
            </li>
          <?php endif; ?>

        <?php else: // Customer Menu ?>
          
          <li class="<?php echo ($this->uri->segment(1) == 'shipments') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('shipments'); ?>"><i class="fa fa-cube"></i> <span>My Shipments</span></a>
          </li>

          <li class="<?php echo ($this->uri->segment(1) == 'customer' && $this->uri->segment(2) == 'kyc') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('customer/kyc'); ?>"><i class="fa fa-id-card"></i> <span>Upload KYC Docs</span></a>
          </li>

          <li class="<?php echo ($this->uri->segment(1) == 'customer' && $this->uri->segment(2) == 'wallet') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('customer/wallet'); ?>"><i class="fa fa-google-wallet"></i> <span>My Wallet Balance</span></a>
          </li>

          <li class="<?php echo ($this->uri->segment(1) == 'customer' && $this->uri->segment(2) == 'statement') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('customer/statement'); ?>"><i class="fa fa-file-text-o"></i> <span>Statement of Account</span></a>
          </li>

        <?php endif; ?>

        <li class="<?php echo ($this->uri->segment(1) == 'change-password') ? 'active' : ''; ?>">
          <a href="<?php echo site_url('change-password'); ?>"><i class="fa fa-lock"></i> <span>Change Password</span></a>
        </li>
      </ul>
    </section>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Flash Notifications -->
    <section class="content-header" style="padding-bottom: 0;">
      <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-check"></i> Success!</h4>
          <?php echo $this->session->flashdata('success'); ?>
        </div>
      <?php endif; ?>
      <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
          <?php echo $this->session->flashdata('error'); ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- Main Dynamic Content Load -->
    <section class="content">
      <?php if(isset($view_path)) { $this->load->view($view_path); } ?>
    </section>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer text-center">
    <strong>Copyright &copy; 2026 <a href="#"><?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></a>.</strong> All rights reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/dist/js/adminlte.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/Chart.js/Chart.js'); ?>"></script>

<script>
  $(document).ready(function() {
    $('.dataTable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false,
      'responsive'  : true
    });
  });
</script>
</body>
</html>
