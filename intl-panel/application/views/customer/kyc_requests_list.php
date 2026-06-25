<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-id-card"></i> Customer KYC Verification Directory</h3>
      </div>
      
      <div class="box-body table-responsive">
        <?php if(!empty($kyc_list)): ?>
          <?php 
            $unique_customers = array();
            foreach($kyc_list as $k) {
                if ($k->customer_name) {
                    $unique_customers[$k->customer_id] = $k->customer_name;
                }
            }
            asort($unique_customers);
          ?>
          <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4">
              <div class="form-group" style="margin-bottom: 0;">
                <label class="control-label">Filter by Customer Name:</label>
                <select id="kycCustomerFilter" class="form-control input-sm">
                  <option value="">All Customers</option>
                  <?php foreach($unique_customers as $c_id => $c_name): ?>
                    <option value="<?php echo htmlspecialchars($c_name); ?>"><?php echo $c_name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Customer Type</th>
              <th>Aadhaar / Passport</th>
              <th>GST / PAN Number</th>
              <th>Status</th>
              <th>Date Requested</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($kyc_list as $k): ?>
              <tr>
                <td data-search="<?php echo htmlspecialchars($k->customer_name); ?>">
                  <strong><?php echo $k->customer_name; ?></strong>
                  <?php if($k->company_name): ?><br><small class="text-muted"><?php echo $k->company_name; ?></small><?php endif; ?>
                </td>
                <td><span class="label label-default"><?php echo ucfirst($k->customer_type); ?></span></td>
                <td>
                  <?php 
                    if($k->passport_number) echo "Passport: " . $k->passport_number;
                    if($k->aadhaar_number) echo "Aadhaar: " . $k->aadhaar_number;
                    if(!$k->passport_number && !$k->aadhaar_number) echo "-";
                  ?>
                </td>
                <td>
                  <?php 
                    if($k->gst_number) echo "GST: " . $k->gst_number . "<br>";
                    if($k->pan_number) echo "PAN: " . $k->pan_number;
                    if(!$k->gst_number && !$k->pan_number) echo "-";
                  ?>
                </td>
                <td>
                  <?php if($k->status == 'pending'): ?>
                    <span class="label label-warning"><i class="fa fa-clock-o"></i> Pending Review</span>
                  <?php elseif($k->status == 'approved'): ?>
                    <span class="label label-success"><i class="fa fa-check-circle"></i> Approved</span>
                  <?php elseif($k->status == 'rejected'): ?>
                    <span class="label label-danger"><i class="fa fa-times-circle"></i> Rejected</span>
                  <?php else: ?>
                    <span class="label label-default"><i class="fa fa-info-circle"></i> Not Submitted</span>
                  <?php endif; ?>
                </td>
                <td><?php echo $k->created_at ? date('d M Y H:i', strtotime($k->created_at)) : '-'; ?></td>
                <td>
                  <?php if($k->id): ?>
                    <a href="<?php echo site_url('kyc-requests/review/' . $k->id); ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Review</a>
                    <?php if($this->session->userdata('role_id') == 1): ?>
                      <a href="<?php echo site_url('kyc-requests/delete/' . $k->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to clear/delete this customer\'s KYC records? This will delete the uploaded files.');"><i class="fa fa-trash"></i> Delete KYC</a>
                    <?php endif; ?>
                  <?php endif; ?>
                  <a href="<?php echo site_url('kyc-requests/manage/' . $k->customer_id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-upload"></i> Add/Edit KYC</a>
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
    $('#kycCustomerFilter').on('change', function() {
      var val = $(this).val();
      var table = $('.dataTable').DataTable();
      // Column 0 is Customer Name. We perform an exact regex search.
      table.column(0).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
    });
  });
</script>
