<!-- Content Header (Page header) -->
<section class="content-header" style="padding-top: 5px; padding-bottom: 15px;">
  <h1>
    <i class="fa fa-cubes text-primary"></i> Domestic Booking List
    <small>Manage and track domestic courier consignments</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="javascript:void(0);">Domestic</a></li>
    <li class="active">Booking List</li>
  </ol>
</section>

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Domestic Consignments</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo site_url('domestic/pincodes'); ?>" class="btn btn-default btn-sm">
            <i class="fa fa-map-pin"></i> View Pincode Directory
          </a>
          <a href="<?php echo site_url('domestic/rates'); ?>" class="btn btn-info btn-sm">
            <i class="fa fa-calculator"></i> Domestic Rate Chart
          </a>
        </div>
      </div>
      <div class="box-body text-center" style="padding: 60px 20px;">
        <i class="fa fa-truck text-muted" style="font-size: 64px; margin-bottom: 20px;"></i>
        <h3 style="font-weight: 600; color: #444;">Domestic Booking Module</h3>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto 20px auto; font-size: 14px;">
          This section will host domestic consignment bookings, manifesting, label printing, and status tracking connected with the domestic pincode master.
        </p>
        <div class="btn-group">
          <a href="<?php echo site_url('domestic/pincodes'); ?>" class="btn btn-primary">
            <i class="fa fa-map-marker"></i> Manage Serviceable Pincodes
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
