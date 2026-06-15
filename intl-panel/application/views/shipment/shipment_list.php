<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-cubes"></i> Consignment Directory</h3>
        <?php if($this->session->userdata('role_id') != 4): ?>
          <a href="<?php echo site_url('shipments/book'); ?>" class="btn btn-success btn-sm pull-right">
            <i class="fa fa-plus"></i> New Booking (Office Staff Only)
          </a>
        <?php endif; ?>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>AWB Number</th>
              <th>Booking Date</th>
              <th>Origin <i class="fa fa-arrow-right"></i> Destination</th>
              <th>Courier Partner</th>
              <th>Exporter Profile</th>
              <th>Weight</th>
              <th>Shipping Charges</th>
              <th>Transit Status</th>
              <th>Verification</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($shipments as $s): ?>
              <tr>
                <td><strong><?php echo $s->awb_number; ?></strong></td>
                <td><?php echo date('d M Y', strtotime($s->booking_date)); ?></td>
                <td><?php echo $s->origin_country_name; ?> <i class="fa fa-arrow-right text-muted"></i> <?php echo $s->dest_country_name; ?></td>
                <td><?php echo $s->courier_partner_name; ?> (<?php echo $s->service_type; ?>)</td>
                <td><?php echo $s->customer_profile_name; ?></td>
                <td><?php echo $s->chargeable_weight; ?> kg</td>
                <td>₹<?php echo number_format($s->estimated_charges, 2); ?></td>
                <td>
                  <?php 
                    $lbl = 'label-default';
                    if($s->status == 'Booking Created') $lbl = 'label-info';
                    elseif($s->status == 'Verification Pending') $lbl = 'label-warning';
                    elseif($s->status == 'Ready For Dispatch' || $s->status == 'Released For Transit') $lbl = 'label-success';
                    elseif($s->status == 'Delivered') $lbl = 'label-primary';
                  ?>
                  <span class="label <?php echo $lbl; ?>"><?php echo $s->status; ?></span>
                </td>
                <td>
                  <?php if($s->verification_status == 'Completed'): ?>
                    <span class="text-success" style="font-size: 13px;"><i class="fa fa-check-circle"></i> OTP Verified & Signed</span>
                  <?php else: ?>
                    <span class="text-danger" style="font-size: 13px;"><i class="fa fa-warning"></i> Verification Pending</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?php echo site_url('shipments/view/' . $s->id); ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View Summary</a>
                  <?php if($this->session->userdata('role_id') != 4): ?>
                    <a href="<?php echo site_url('shipments/edit/' . $s->id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                  <?php endif; ?>
                  
                  <?php if($this->session->userdata('role_id') == 4 && $s->verification_status == 'Pending'): ?>
                    <a href="<?php echo site_url('customer/verify/' . $s->id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Sign & Verify</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
