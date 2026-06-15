<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url() ?>/asset/images/user.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('cr_user_name');?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
       
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Dashboard</li>
        <li <?php if($this->uri->segment(1, 0) === 'dash') echo 'class="active"'; ?>><a href="<?php echo site_url('dash') ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
        <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
        <li <?php if($this->uri->segment(1, 0) === 'calender') echo 'class="active"'; ?>><a href="<?php echo site_url('calender') ?>"><i class="fa fa-calendar"></i> Calender</a></li>
        <?php } ?>
        <li class="header">AWB Booking</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('in-scan-entry','in-scan','in-scan-list','in-scan-edit'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Booking</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!--<li <?php if($this->uri->segment(1, 0) === 'in-scan') echo 'class="active"'; ?>><a href="<?php echo site_url('in-scan') ?>"><i class="fa fa-circle-o"></i> In Scan Entry</a></li>-->
            <li <?php if($this->uri->segment(1, 0) === 'in-scan-entry') echo 'class="active"'; ?>><a href="<?php echo site_url('in-scan-entry') ?>"><i class="fa fa-circle-o"></i> EDP Entry</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'in-scan-list') echo 'class="active"'; ?>><a href="<?php echo site_url('in-scan-list') ?>"><i class="fa fa-circle-o"></i> EDP List [Booking List] </a></li>
            
            <!--<li <?php if($this->uri->segment(1, 0) === 'create-booking') echo 'class="active"'; ?>><a href="<?php echo site_url('create-booking') ?>"><i class="fa fa-circle-o"></i> EDP Entry</a></li>-->
          </ul>
        </li>
        <li class="header">Manifest</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('open-manifest','receive-manifest'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Manifest</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'generate-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('generate-manifest') ?>"><i class="fa fa-circle-o"></i> Generate Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'open-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('open-manifest') ?>"><i class="fa fa-circle-o"></i> Open Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'receive-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('receive-manifest') ?>"><i class="fa fa-circle-o"></i> Receive Manifest</a></li>
          </ul>
        </li>
        <li class="header">Delivery</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('delivery-runsheet','delivery-updatation','drs-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Delivery</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'delivery-runsheet') echo 'class="active"'; ?>><a href="<?php echo site_url('delivery-runsheet') ?>"><i class="fa fa-circle-o"></i> Delivery Runsheet</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'drs-list') echo 'class="active"'; ?>><a href="<?php echo site_url('drs-list') ?>"><i class="fa fa-circle-o"></i> DRS Print</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'delivery-updation') echo 'class="active"'; ?>><a href="<?php echo site_url('delivery-updation') ?>"><i class="fa fa-circle-o"></i> Delivery Updation</a></li>
          </ul>
        </li>
        <li class="header">Help Desk</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('hd-category-list','ticket','ticket-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-ticket"></i> <span>Help Desk</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'ticket-list') echo 'class="active"'; ?>><a href="<?php echo site_url('ticket-list') ?>"><i class="fa fa-ticket"></i> Ticket List</a></li>
             <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
             <li <?php if($this->uri->segment(1, 0) === 'hd-category-list') echo 'class="active"'; ?>><a href="<?php echo site_url('hd-category-list/0') ?>"><i class="fa fa-ticket"></i> Ticket Category</a></li>
             <?php }  ?>   
          </ul>
        </li>
        <?php
	       /*
        ?>
        <!--
        <li class="header">Co-Courier Delivery</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('coco-delivery-runsheet-list','co-courier-drs-status-list','co-courier-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Co-Courier Delivery</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'coco-delivery-runsheet-list') echo 'class="active"'; ?>><a href="<?php echo site_url('coco-delivery-runsheet-list') ?>"><i class="fa fa-circle-o"></i> Co-Courier Delivery List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'co-courier-drs-status-list') echo 'class="active"'; ?>><a href="<?php echo site_url('co-courier-drs-status-list') ?>"><i class="fa fa-circle-o"></i> Co-Courier Delivery Status List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'co-courier-list') echo 'class="active"'; ?>><a href="<?php echo site_url('co-courier-list') ?>"><i class="fa fa-circle-o"></i> Co-Courier List</a></li>
            
          </ul>
        </li>
        
        <li class="header">Line Haul</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('line-haul-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Line Haul</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'line-haul-list') echo 'class="active"'; ?>><a href="<?php echo site_url('line-haul-list') ?>"><i class="fa fa-circle-o"></i>Line Haul List</a></li>
              
          </ul>
        </li>-->
        <?php
	       */
        ?>
        <li class="header">AWB No Tracking</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('tracking-entry','awb-tracking'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>AWB Tracking</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'awb-tracking') echo 'class="active"'; ?>><a href="<?php echo site_url('awb-tracking') ?>" target="_blank"><i class="fa fa-circle-o"></i> AWB No Tracking </a></li>
             <li <?php if($this->uri->segment(1, 0) === 'tracking-entry') echo 'class="active"'; ?>><a href="<?php echo site_url('tracking-entry') ?>"><i class="fa fa-circle-o"></i> Tracking Status Entry</a></li>
          </ul>
        </li>
        <li class="header">Invoice</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('customer-invoice-generate','customer-invoice-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Customer Invoice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'customer-invoice-generate') echo 'class="active"'; ?>><a href="<?php echo site_url('customer-invoice-generate') ?>"><i class="fa fa-circle-o"></i> Customer Invoice Generate</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'customer-invoice-list') echo 'class="active"'; ?>><a href="<?php echo site_url('customer-invoice-list') ?>"><i class="fa fa-circle-o"></i> Customer Invoice List</a></li>
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('ts-invoice-generate',))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>TS Invoice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'ts-invoice-generate') echo 'class="active"'; ?>><a href="<?php echo site_url('ts-invoice-generate') ?>"><i class="fa fa-circle-o"></i> TS Invoice Generate</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'ts-invoice-list') echo 'class="active"'; ?>><a href="<?php echo site_url('ts-invoice-list') ?>"><i class="fa fa-circle-o"></i> TS Invoice List</a></li>
          </ul>
        </li>
        <li class="header">Cash Book</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('cash-inward-list','cash-outward-list','account-head-list','sub-account-head-list','cash-ledger','wallet-payment-transfer-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-rupee"></i> <span>Cash Book</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">  
             <li <?php if($this->uri->segment(1, 0) === 'wallet-payment-transfer-list') echo 'class="active"'; ?>><a href="<?php echo site_url('wallet-payment-transfer-list') ?>"><i class="fa fa-rupee"></i> Wallet Payment Transfer List</a></li> 
             
             <hr />
             <li <?php if($this->uri->segment(1, 0) === 'cash-inward-list') echo 'class="active"'; ?>><a href="<?php echo site_url('cash-inward-list') ?>"><i class="fa fa-rupee"></i> Cash Inward List</a></li> 
             <li <?php if($this->uri->segment(1, 0) === 'cash-outward-list') echo 'class="active"'; ?>><a href="<?php echo site_url('cash-outward-list') ?>"><i class="fa fa-rupee"></i> Cash Outward List</a></li> 
             <li <?php if($this->uri->segment(1, 0) === 'account-head-list') echo 'class="active"'; ?>><a href="<?php echo site_url('account-head-list') ?>"><i class="fa fa-rupee"></i> Account Head List</a></li> 
             <li <?php if($this->uri->segment(1, 0) === 'sub-account-head-list') echo 'class="active"'; ?>><a href="<?php echo site_url('sub-account-head-list') ?>"><i class="fa fa-rupee"></i> Sub-Account Head List</a></li> 
             <li <?php if($this->uri->segment(1, 0) === 'cash-ledger') echo 'class="active"'; ?>><a href="<?php echo site_url('cash-ledger') ?>"><i class="fa fa-rupee"></i> Cash Ledger</a></li> 
            
          </ul>
        </li>
        <li class="header">International</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('service-provider-charges','international-rate-calc','package-weight-list','service-provider-list','international-rate-list','international-rate-list-v2','international-consignment-list', 'international-tracking-status','oda-charges-list','ah-charges-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-plane"></i> <span>International</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li <?php if($this->uri->segment(1, 0) === 'international-rate-list-v2') echo 'class="active"'; ?>><a href="<?php echo site_url('international-rate-list-v2') ?>"><i class="fa fa-circle-o"></i>International Rate List - V2</a></li>
              <li <?php if($this->uri->segment(1, 0) === 'service-provider-charges') echo 'class="active"'; ?>><a href="<?php echo site_url('service-provider-charges') ?>"><i class="fa fa-circle-o"></i>Service Providers Charges</a></li>
             
             <hr />
             <li <?php if($this->uri->segment(1, 0) === 'international-consignment-list') echo 'class="active"'; ?>><a href="<?php echo site_url('international-consignment-list') ?>"><i class="fa fa-circle-o"></i>International Booking</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'international-rate-calc') echo 'class="active"'; ?>><a href="<?php echo site_url('international-rate-calc') ?>"><i class="fa fa-circle-o"></i>International Rate Calc</a></li>
              <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
             <li <?php if($this->uri->segment(1, 0) === 'package-weight-list') echo 'class="active"'; ?>><a href="<?php echo site_url('package-weight-list') ?>"><i class="fa fa-circle-o"></i>Package Weight List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'service-provider-list') echo 'class="active"'; ?>><a href="<?php echo site_url('service-provider-list') ?>"><i class="fa fa-circle-o"></i>Service Providers List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'international-rate-list') echo 'class="active"'; ?>><a href="<?php echo site_url('international-rate-list') ?>"><i class="fa fa-circle-o"></i>International Rate List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'international-tracking-status') echo 'class="active"'; ?>><a href="<?php echo site_url('international-tracking-status') ?>"><i class="fa fa-circle-o"></i>Intl Tracking Master List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'oda-charges-list') echo 'class="active"'; ?>><a href="<?php echo site_url('oda-charges-list') ?>"><i class="fa fa-circle-o"></i>ODA Charges Type List</a></li>
             <li <?php if($this->uri->segment(1, 0) === 'ah-charges-list') echo 'class="active"'; ?>><a href="<?php echo site_url('ah-charges-list') ?>"><i class="fa fa-circle-o"></i>AH Charges Type List</a></li>
             <?php }  ?>
          </ul>
        </li>
        <li class="header">Reports</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('attendance-report','co-loader-wise-booking-report','customer-booking-report','city-wise-booking-summary','franchise-booking-report','franchise-NDR-report','manifest-report','drs-report','inbound-consignment-report','servicable-pincode-report','line-haul-report','co-courier-delivery-report','wallet-statement' ))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-bar-chart"></i> <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
            <li <?php if($this->uri->segment(1, 0) === 'attendance-report') echo 'class="active"'; ?>><a href="<?php echo site_url('attendance-report') ?>"><i class="fa fa-file-text-o"></i> Attendance Report</a></li>
             <?php } ?>
            <li <?php if($this->uri->segment(1, 0) === 'customer-booking-report') echo 'class="active"'; ?>><a href="<?php echo site_url('customer-booking-report') ?>"><i class="fa fa-file-text-o"></i> Customer Wise Consignment</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-booking-report') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-booking-report') ?>"><i class="fa fa-file-text-o"></i> Franchise Wise Consignment[Out-Bound]</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-NDR-report') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-NDR-report') ?>"><i class="fa fa-file-text-o"></i> NDR Report</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'co-loader-wise-booking-report') echo 'class="active"'; ?>><a href="<?php echo site_url('co-loader-wise-booking-report') ?>"><i class="fa fa-file-text-o"></i> Co-Loader Wise Booking </a></li>
            
            <li <?php if($this->uri->segment(1, 0) === 'city-wise-booking-summary') echo 'class="active"'; ?>><a href="<?php echo site_url('city-wise-booking-summary') ?>"><i class="fa fa-file-text-o"></i> City Wise Summary </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'manifest-report') echo 'class="active"'; ?>><a href="<?php echo site_url('manifest-report') ?>"><i class="fa fa-file-text-o"></i> Manifest Report </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'drs-report') echo 'class="active"'; ?>><a href="<?php echo site_url('drs-report') ?>"><i class="fa fa-file-text-o"></i> DRS Report </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'inbound-consignment-report') echo 'class="active"'; ?>><a href="<?php echo site_url('inbound-consignment-report') ?>"><i class="fa fa-file-text-o"></i>In-bound Consignment </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'servicable-pincode-report') echo 'class="active"'; ?>><a href="<?php echo site_url('servicable-pincode-report') ?>"><i class="fa fa-file-text-o"></i>Serviceable Pincode With Franchis Details </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'line-haul-report') echo 'class="active"'; ?>><a href="<?php echo site_url('line-haul-report') ?>"><i class="fa fa-file-text-o"></i>Line Haul Report </a></li>
            <li <?php if($this->uri->segment(1, 0) === 'co-courier-delivery-report') echo 'class="active"'; ?>><a href="<?php echo site_url('co-courier-delivery-report') ?>"><i class="fa fa-file-text-o"></i>Co-Courier Delivery Report </a></li>
            <hr />
            <li <?php if($this->uri->segment(1, 0) === 'wallet-statement') echo 'class="active"'; ?>><a href="<?php echo site_url('wallet-statement') ?>"><i class="fa fa-file-text-o"></i>Wallet Statement</a></li>
            
          </ul>
        </li>
        <!--
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('b2h-manifest','b2h-manifest-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Branch to HUB</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'b2h-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('b2h-manifest') ?>"><i class="fa fa-circle-o"></i> B2H Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'b2h-manifest-list') echo 'class="active"'; ?>><a href="<?php echo site_url('b2h-manifest-list') ?>"><i class="fa fa-circle-o"></i> B2H Manifest List</a></li>
            
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('h2h-manifest','h2h-manifest-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>HUB to HUB</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'h2h-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('h2h-manifest') ?>"><i class="fa fa-circle-o"></i> H2H Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'h2h-manifest-list') echo 'class="active"'; ?>><a href="<?php echo site_url('h2h-manifest-list') ?>"><i class="fa fa-circle-o"></i> H2H Manifest List</a></li>
            
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('h2b-manifest','h2b-manifest-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>HUB to Branch</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'h2b-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('h2b-manifest') ?>"><i class="fa fa-circle-o"></i> H2B Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'h2b-manifest-list') echo 'class="active"'; ?>><a href="<?php echo site_url('h2b-manifest-list') ?>"><i class="fa fa-circle-o"></i> H2B Manifest List</a></li>
            
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('b2b-manifest','b2b-manifest-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Branch to Branch</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'b2b-manifest') echo 'class="active"'; ?>><a href="<?php echo site_url('b2b-manifest') ?>"><i class="fa fa-circle-o"></i> B2B Manifest</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'b2b-manifest-list') echo 'class="active"'; ?>><a href="<?php echo site_url('b2b-manifest-list') ?>"><i class="fa fa-circle-o"></i> B2B Manifest List</a></li>
            
          </ul>
        </li> -->
        <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
        <li class="header">Notice Board</li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('notice-board-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-newspaper-o"></i> <span>Notice Board</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'notice-board-list') echo 'class="active"'; ?>><a href="<?php echo site_url('notice-board-list') ?>"><i class="fa fa-newspaper-o"></i> Notice Board List</a></li> 
            
          </ul>
        </li>
        <?php } ?>
        
        <li class="header">Masters</li>
        <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('doc-upload-type-list','tracking-status-list','ndr-list','co-loader-list','pincode-list','servicable-pincode-list','country-list','state-list','city-list','domestic-rate','carrier-list','service-list','package-type-list','product-type-list','customer-type-list','commodity-type-list','hub-branch-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-cubes"></i> <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu"> 
              
            <li <?php if($this->uri->segment(1, 0) === 'pincode-list') echo 'class="active"'; ?>><a href="<?php echo site_url('pincode-list') ?>"><i class="fa fa-circle-o"></i> Mst.Pincode List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'servicable-pincode-list') echo 'class="active"'; ?>><a href="<?php echo site_url('servicable-pincode-list') ?>"><i class="fa fa-circle-o"></i> Serviceable Pincode List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'country-list') echo 'class="active"'; ?>><a href="<?php echo site_url('country-list') ?>"><i class="fa fa-circle-o"></i> Country List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'state-list') echo 'class="active"'; ?>><a href="<?php echo site_url('state-list') ?>"><i class="fa fa-circle-o"></i> State List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'city-list') echo 'class="active"'; ?>><a href="<?php echo site_url('city-list') ?>"><i class="fa fa-circle-o"></i> City List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'hub-branch-list') echo 'class="active"'; ?>><a href="<?php echo site_url('hub-branch-list') ?>"><i class="fa fa-circle-o"></i> HUB/Branch Code List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'domestic-rate') echo 'class="active"'; ?>><a href="<?php echo site_url('domestic-rate') ?>"><i class="fa fa-rupee"></i> MST Domestic Rate</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'transhipment-rate') echo 'class="active"'; ?>><a href="<?php echo site_url('transhipment-rate') ?>"><i class="fa fa-rupee"></i> Transhipment Rate</a></li>
            <hr /> 
            <li <?php if($this->uri->segment(1, 0) === 'co-loader-list') echo 'class="active"'; ?>><a href="<?php echo site_url('co-loader-list') ?>"><i class="fa fa-circle-o"></i> Co-Loader List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'ndr-list') echo 'class="active"'; ?>><a href="<?php echo site_url('ndr-list') ?>"><i class="fa fa-circle-o"></i> NDR List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'carrier-list') echo 'class="active"'; ?>><a href="<?php echo site_url('carrier-list') ?>"><i class="fa fa-circle-o"></i> Carrier List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'service-list') echo 'class="active"'; ?>><a href="<?php echo site_url('service-list') ?>"><i class="fa fa-circle-o"></i> Service List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'package-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('package-type-list') ?>"><i class="fa fa-circle-o"></i> Package Type List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'product-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('product-type-list') ?>"><i class="fa fa-circle-o"></i> Product Type List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'commodity-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('commodity-type-list') ?>"><i class="fa fa-circle-o"></i> Commodity Type List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'customer-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('customer-type-list') ?>"><i class="fa fa-circle-o"></i> Customer Type List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'tracking-status-list') echo 'class="active"'; ?>><a href="<?php echo site_url('tracking-status-list') ?>"><i class="fa fa-circle-o"></i> Tracking Status List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'doc-upload-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('doc-upload-type-list') ?>"><i class="fa fa-circle-o"></i> Doc Upload Type List</a></li>
             
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('recycle-booking-list','recycle-intl-booking-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-recycle"></i> <span>Recycle Bin</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'recycle-booking-list') echo 'class="active"'; ?>><a href="<?php echo site_url('recycle-booking-list') ?>"><i class="fa fa-circle-o"></i> Recycle Domestic Booking</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'recycle-intl-booking-list') echo 'class="active"'; ?>><a href="<?php echo site_url('recycle-intl-booking-list') ?>"><i class="fa fa-circle-o"></i> Recycle International Booking</a></li>
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('franchise-type-list','franchise-list','franchise-domestic-rate','franchise-doc-upload-list','franchise-awbill-list','opening-balance-wallet-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Franchises</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'franchise-type-list') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-type-list') ?>"><i class="fa fa-circle-o"></i> Franchise Type List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-list') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-list') ?>"><i class="fa fa-circle-o"></i> Franchise List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-doc-upload-list') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-doc-upload-list') ?>"><i class="fa fa-circle-o"></i> Franchise Doc Upload List</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-domestic-rate') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-domestic-rate') ?>"><i class="fa fa-circle-o"></i> Franchise Domestic Rate</a></li>
            <li <?php if($this->uri->segment(1, 0) === 'franchise-awbill-list') echo 'class="active"'; ?>><a href="<?php echo site_url('franchise-awbill-list') ?>"><i class="fa fa-circle-o"></i> Franchise AWBill List</a></li> 
            <li <?php if($this->uri->segment(1, 0) === 'opening-balance-wallet-list') echo 'class="active"'; ?>><a href="<?php echo site_url('opening-balance-wallet-list') ?>"><i class="fa fa-rupee"></i> Wallet Opening Balance</a></li> 
             
          </ul>
        </li>
        <?php } ?>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('agent-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Agents / Employee</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'agent-list') echo 'class="active"'; ?>><a href="<?php echo site_url('agent-list') ?>"><i class="fa fa-circle-o"></i> Agent/Employee List</a></li>
          </ul>
        </li>
        <li class="treeview <?php if(in_array($this->uri->segment(1, 0),array('customer-list'))) echo 'active'; ?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Customer</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1, 0) === 'customer-list') echo 'class="active"'; ?>><a href="<?php echo site_url('customer-list') ?>"><i class="fa fa-circle-o"></i> Customer List</a></li>
          </ul>
        </li>
         <?php if($this->session->userdata('cr_is_admin') == '1') {  ?>
        <li><a href="<?php echo site_url('change-password') ?>" class="text-white"><i class="fa fa-user-secret"></i> Change Password</a></li> 
         <?php } ?>
        <li><a href="<?php echo site_url('logout') ?>" class="text-fuchsia"><i class="fa fa-sign-out"></i> Logout</a></li> 
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  