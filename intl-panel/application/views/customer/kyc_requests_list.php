<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-id-card"></i> Customer KYC Verification Directory</h3>
      </div>
      
      <div class="box-body table-responsive">
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
                <td>
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
                  <?php else: ?>
                    <span class="label label-danger"><i class="fa fa-times-circle"></i> Rejected</span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('d M Y H:i', strtotime($k->created_at)); ?></td>
                <td>
                  <a href="<?php echo site_url('kyc-requests/review/' . $k->id); ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Review & Action</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
