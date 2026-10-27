<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($page_title) ? $page_title : 'Courier ERP'; ?> | International Panel</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo base_url('asset/images/logo-icon.png'); ?>" type="image/png">
  <link rel="icon" href="<?php echo base_url('asset/images/logo-icon.png'); ?>" type="image/png">
  
  <!-- CSS assets -->
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/select2/dist/css/select2.min.css'); ?>">
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

    /* Select2 AdminLTE Enhancements */
    .select2-container--default .select2-selection--single {
      height: 34px !important;
      border: 1px solid #d2d6de !important;
      border-radius: 4px !important;
      padding: 6px 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 20px !important;
      padding-left: 0 !important;
      color: #333 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 32px !important;
      right: 6px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear {
      margin-right: 20px !important;
      color: #dd4b39 !important;
      font-size: 16px !important;
    }
    .select2-dropdown {
      border-color: #3c8dbc !important;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15) !important;
      z-index: 99999 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #3c8dbc !important;
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
          <img src="<?php echo base_url('assets/img/' . COMPANY_LOGO); ?>" alt="Logo"   style="max-height: 40px; margin-top: -5px; border-radius:5px; background-color:white; padding:5px; ">
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
          <?php if ($this->uri->segment(1) == 'dashboard' || empty($this->uri->segment(1))): ?>
          <li>
            <a href="javascript:void(0);" id="navRateCalcBtn" class="btn-trigger-rate-calc" onclick="openRateCalculatorModal(event);" title="Open International Rate Calculator" style="font-weight: 600;">
              <i class="fa fa-calculator text-yellow" style="margin-right: 4px;"></i> <span class="hidden-xs">Rate Calculator</span>
            </a>
          </li>
          <?php endif; ?>
          <?php if ($this->session->userdata('role_id') == 1): // Visible for Super Admin ?>
          <li class="messages-menu">
            <a href="<?php echo site_url('chat'); ?>" title="Internal Staff Chat & Audit Hub" style="position:relative;">
              <i class="fa fa-comments-o" style="font-size:16px;"></i>
              <span class="label label-danger chat-global-unread-badge" style="display:none; position:absolute; top:9px; right:4px; font-size:10px; border-radius:8px; padding:2px 5px;">0</span>
            </a>
          </li>
          <?php endif; ?>
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

           <li class="<?php echo ($this->uri->segment(1) == 'chat') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('chat'); ?>">
              <i class="fa fa-comments"></i> <span>Internal Chat</span>
              <span class="pull-right-container">
                <span class="label label-primary pull-right chat-sidebar-unread-badge" style="display:none; font-size:11px; border-radius:10px;">0</span>
              </span>
            </a>
          </li> 

          <?php if ($this->session->userdata('role_id') != 3): // Hide for Franchise Users ?>
            <li class="<?php echo ($this->uri->segment(1) == 'kyc-requests') ? 'active' : ''; ?>">
              <a href="<?php echo site_url('kyc-requests'); ?>"><i class="fa fa-id-card"></i> <span>KYC Requests</span></a>
            </li>

            <li class="<?php echo ($this->uri->segment(1) == 'customers') ? 'active' : ''; ?>">
              <a href="<?php echo site_url('customers'); ?>"><i class="fa fa-users"></i> <span>Customers</span></a>
            </li>

            <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2): // Super Admin or Branch Admin ?>
              <li class="<?php echo ($this->uri->segment(1) == 'customer' && $this->uri->segment(2) == 'wallet-requests') ? 'active' : ''; ?>">
                <a href="<?php echo site_url('customer/wallet-requests'); ?>"><i class="fa fa-money"></i> <span>Wallet Load Requests</span></a>
              </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2): // Super Admin or Branch Admin ?>
            <!-- Domestic Operations Menu -->
            <li class="treeview <?php echo in_array($this->uri->segment(1), array('domestic', 'pincode-list', 'product-categories')) ? 'active menu-open' : ''; ?>">
              <a href="#"><i class="fa fa-truck"></i> <span>Domestic</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php echo ($this->uri->segment(1) == 'domestic' && $this->uri->segment(2) == 'bookings') ? 'active' : ''; ?>"><a href="<?php echo site_url('domestic/bookings'); ?>"><i class="fa fa-circle-o"></i> Domestic booking list</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'domestic' && ($this->uri->segment(2) == 'rates' || $this->uri->segment(2) == 'rate-chart')) ? 'active' : ''; ?>"><a href="<?php echo site_url('domestic/rates'); ?>"><i class="fa fa-circle-o"></i> Domestic Rate Chart</a></li>
                <li class="<?php echo (in_array($this->uri->segment(1), array('domestic', 'pincode-list')) && ($this->uri->segment(2) == 'pincodes' || $this->uri->segment(1) == 'pincode-list')) ? 'active' : ''; ?>"><a href="<?php echo site_url('domestic/pincodes'); ?>"><i class="fa fa-circle-o"></i> Pincode List</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'domestic' && in_array($this->uri->segment(2), array('categories', 'product-categories'))) || $this->uri->segment(1) == 'product-categories' ? 'active' : ''; ?>"><a href="<?php echo site_url('domestic/categories'); ?>"><i class="fa fa-circle-o"></i> Product Category</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role_id') == 1): // Only Super Admin ?>
            <li class="treeview <?php echo in_array($this->uri->segment(1), array('branches', 'franchises', 'countries', 'geo-locations', 'partners', 'rates', 'oda-charges', 'terms', 'movement-stages', 'service-types', 'document-types', 'restricted-items', 'charge-types', 'app-settings', 'notification-logs', 'roles')) ? 'active menu-open' : ''; ?>">
              <a href="#"><i class="fa fa-gears"></i> <span>Master Settings</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php echo ($this->uri->segment(1) == 'roles') ? 'active' : ''; ?>"><a href="<?php echo site_url('roles'); ?>"><i class="fa fa-circle-o"></i> Roles &amp; Permissions</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'branches') ? 'active' : ''; ?>"><a href="<?php echo site_url('branches'); ?>"><i class="fa fa-circle-o"></i> Branches</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'franchises') ? 'active' : ''; ?>"><a href="<?php echo site_url('franchises'); ?>"><i class="fa fa-circle-o"></i> Franchises</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'countries') ? 'active' : ''; ?>"><a href="<?php echo site_url('countries'); ?>"><i class="fa fa-circle-o"></i> Countries</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'geo-locations') ? 'active' : ''; ?>"><a href="<?php echo site_url('geo-locations'); ?>"><i class="fa fa-map-marker"></i> Geo Locations</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'partners') ? 'active' : ''; ?>"><a href="<?php echo site_url('partners'); ?>"><i class="fa fa-circle-o"></i> Courier Partners</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'rates') ? 'active' : ''; ?>"><a href="<?php echo site_url('rates'); ?>"><i class="fa fa-calculator"></i> Shipping Rates Matrix v2</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'oda-charges') ? 'active' : ''; ?>"><a href="<?php echo site_url('oda-charges'); ?>"><i class="fa fa-map-pin"></i> ODA Charges Master</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'terms') ? 'active' : ''; ?>"><a href="<?php echo site_url('terms'); ?>"><i class="fa fa-circle-o"></i> Terms & Conditions</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'movement-stages') ? 'active' : ''; ?>"><a href="<?php echo site_url('movement-stages'); ?>"><i class="fa fa-circle-o"></i> Movement Status Stages</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'service-types') ? 'active' : ''; ?>"><a href="<?php echo site_url('service-types'); ?>"><i class="fa fa-circle-o"></i> Service Types</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'document-types') ? 'active' : ''; ?>"><a href="<?php echo site_url('document-types'); ?>"><i class="fa fa-circle-o"></i> Document Types</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'restricted-items') ? 'active' : ''; ?>"><a href="<?php echo site_url('restricted-items'); ?>"><i class="fa fa-circle-o"></i> Restricted Items Warns</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'charge-types') ? 'active' : ''; ?>"><a href="<?php echo site_url('charge-types'); ?>"><i class="fa fa-circle-o"></i> Charge Types</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'app-settings') ? 'active' : ''; ?>"><a href="<?php echo site_url('app-settings'); ?>"><i class="fa fa-circle-o"></i> App Settings</a></li>
                <li class="<?php echo ($this->uri->segment(1) == 'notification-logs') ? 'active' : ''; ?>"><a href="<?php echo site_url('notification-logs'); ?>"><i class="fa fa-circle-o"></i> Notification Logs</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2): ?>
            <li class="treeview <?php echo ($this->uri->segment(1) == 'reports') ? 'active menu-open' : ''; ?>">
              <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php echo ($this->uri->segment(1) == 'reports' && $this->uri->segment(2) == 'tracking_idle') ? 'active' : ''; ?>"><a href="<?php echo site_url('reports/tracking_idle'); ?>"><i class="fa fa-circle-o"></i> Tracking Idle Report</a></li>
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
        <li class="<?php echo ($this->uri->segment(1) == 'logout') ? 'active' : ''; ?>">
          <a href="<?php echo site_url('logout'); ?>"><i class="fa fa-sign-out"></i> <span>Log Out</span></a>
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

<!-- Load International Rate Calculator Modal Partial directly under body to avoid AdminLTE z-index/stacking context issues -->
<?php if (isset($countries) && !empty($countries)): ?>
  <?php $this->load->view('dashboard/rate_calculator_modal'); ?>
<?php endif; ?>

<!-- Scripts -->
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/select2/dist/js/select2.full.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/dist/js/adminlte.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/Chart.js/Chart.js'); ?>"></script>

<script>
  $(document).ready(function() {
    $('.dataTable').not('#geoLocationsTable, #ratesV2Table, #pincodesTable').each(function() {
      if (!$.fn.DataTable.isDataTable(this)) {
        $(this).DataTable({
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : false,
          'info'        : true,
          'autoWidth'   : false,
          'responsive'  : true
        });
      }
    });
  });
</script>

<?php if ($this->session->userdata('role_id') && $this->session->userdata('role_id') != 4): // Active for all internal staff ?>
<script>
  // Global Notification Sound Synthesizer using Web Audio API
  window.playChatNotificationChime = function() {
    try {
      if (localStorage.getItem('chat_sound_enabled') === 'false') return;
      var AudioContext = window.AudioContext || window.webkitAudioContext;
      if (!AudioContext) return;
      var ctx = window._chatAudioCtx || new AudioContext();
      window._chatAudioCtx = ctx;
      if (ctx.state === 'suspended') {
        ctx.resume();
      }
      var now = ctx.currentTime;
      // Tone 1: 587.33 Hz (D5)
      var osc1 = ctx.createOscillator();
      var gain1 = ctx.createGain();
      osc1.type = 'sine';
      osc1.frequency.setValueAtTime(587.33, now);
      gain1.gain.setValueAtTime(0, now);
      gain1.gain.linearRampToValueAtTime(0.2, now + 0.03);
      gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
      osc1.connect(gain1);
      gain1.connect(ctx.destination);
      osc1.start(now);
      osc1.stop(now + 0.25);

      // Tone 2: 880 Hz (A5)
      var osc2 = ctx.createOscillator();
      var gain2 = ctx.createGain();
      osc2.type = 'sine';
      osc2.frequency.setValueAtTime(880, now + 0.12);
      gain2.gain.setValueAtTime(0, now + 0.12);
      gain2.gain.linearRampToValueAtTime(0.25, now + 0.15);
      gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
      osc2.connect(gain2);
      gain2.connect(ctx.destination);
      osc2.start(now + 0.12);
      osc2.stop(now + 0.45);
    } catch(e) {
      // Silent catch if audio not permitted
    }
  };

  // Global Internal Chat Unread Poller & Online Heartbeat
  (function() {
    var lastTotalUnread = -1;

    function updateChatBadges() {
      $.ajax({
        url: '<?php echo site_url("chat/unread-count"); ?>',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(res) {
          if (res && res.status === 'success') {
            var count = parseInt(res.total_unread) || 0;
            // Play sound if new message arrived while not on chat page
            if (lastTotalUnread !== -1 && count > lastTotalUnread) {
              // If not already inside the active chat view
              if (!window._isInActiveChatView) {
                window.playChatNotificationChime();
              }
            }
            lastTotalUnread = count;

            if (count > 0) {
              var displayCount = count > 99 ? '99+' : count;
              $('.chat-global-unread-badge').text(displayCount).show();
              $('.chat-sidebar-unread-badge').text(displayCount).show();
            } else {
              $('.chat-global-unread-badge').hide();
              $('.chat-sidebar-unread-badge').hide();
            }
          }
        }
      });
    }

    $(document).ready(function() {
      // Run on page load
      updateChatBadges();
      // Periodically refresh every 15 seconds
      setInterval(updateChatBadges, 15000);
    });
  })();
</script>
<?php endif; ?>
</body>
</html>
