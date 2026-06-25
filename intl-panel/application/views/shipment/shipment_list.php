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
        <?php if($this->session->userdata('role_id') != 4 && !empty($customers)): ?>
          <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4">
              <div class="form-group" style="margin-bottom: 0;">
                <label class="control-label">Filter by Customer (Exporter):</label>
                <select id="customerFilter" class="form-control input-sm">
                  <option value="">All Customers</option>
                  <?php foreach($customers as $cust): ?>
                    <option value="<?php echo htmlspecialchars($cust->name); ?>"><?php echo $cust->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        <?php endif; ?>

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
                <td data-search="<?php echo htmlspecialchars($s->customer_profile_name); ?>"><?php echo $s->customer_profile_name; ?></td>
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
                  <a href="<?php echo site_url('shipments/view/' . $s->id); ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View</a>
                  <?php if($this->session->userdata('role_id') != 4 && $this->session->userdata('role_id') != 3): ?>
                    <a href="<?php echo site_url('shipments/edit/' . $s->id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="<?php echo site_url('shipments/delete/' . $s->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this shipment?');"><i class="fa fa-trash"></i> Delete</a>
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

<script>
  $(document).ready(function() {
    // Custom Datatables Filter for Customer (Exporter Profile)
    $('#customerFilter').on('change', function() {
      var val = $(this).val();
      var table = $('.dataTable').DataTable();
      // Column 4 is Exporter Profile. We perform an exact regex search.
      table.column(4).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
    });
  });
</script>
