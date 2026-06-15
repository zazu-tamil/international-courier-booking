<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Review KYC Document details</h3>
        <a href="<?php echo site_url('kyc-requests'); ?>" class="btn btn-default btn-xs pull-right"><i class="fa fa-arrow-left"></i> Back</a>
      </div>
      
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th style="width: 150px;">Customer Profile</th>
            <td><strong><?php echo $kyc->customer_name; ?></strong> (<?php echo $kyc->email; ?>)</td>
          </tr>
          <tr>
            <th>Customer Type</th>
            <td><span class="label label-default"><?php echo ucfirst($kyc->customer_type); ?></span></td>
          </tr>
          
          <?php if($kyc->passport_number): ?>
            <tr>
              <th>Passport ID</th>
              <td><code><?php echo $kyc->passport_number; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->aadhaar_number): ?>
            <tr>
              <th>Aadhaar Number</th>
              <td><code><?php echo $kyc->aadhaar_number; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->gst_number): ?>
            <tr>
              <th>GSTIN Number</th>
              <td><code><?php echo $kyc->gst_number; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->pan_number): ?>
            <tr>
              <th>PAN Number</th>
              <td><code><?php echo $kyc->pan_number; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->trade_license): ?>
            <tr>
              <th>Trade License</th>
              <td><code><?php echo $kyc->trade_license; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->company_registration_certificate): ?>
            <tr>
              <th>Company Reg ID</th>
              <td><code><?php echo $kyc->company_registration_certificate; ?></code></td>
            </tr>
          <?php endif; ?>

          <?php if($kyc->authorized_person): ?>
            <tr>
              <th>Authorized Signatory</th>
              <td><?php echo nl2br($kyc->authorized_person); ?></td>
            </tr>
          <?php endif; ?>
        </table>
        
        <h4 style="margin-top: 25px; font-weight: 600;"><i class="fa fa-paperclip"></i> Uploaded Document Attachments</h4>
        <div class="row text-center" style="margin-top: 15px; margin-bottom: 25px;">
          <div class="col-xs-6">
            <?php if($kyc->id_proof_file): ?>
              <a href="<?php echo base_url($kyc->id_proof_file); ?>" target="_blank" class="btn btn-warning btn-sm btn-block"><i class="fa fa-file-image-o"></i> View ID Proof</a>
            <?php else: ?>
              <span class="text-muted">No ID proof file</span>
            <?php endif; ?>
          </div>
          <div class="col-xs-6">
            <?php if($kyc->address_proof_file): ?>
              <a href="<?php echo base_url($kyc->address_proof_file); ?>" target="_blank" class="btn btn-warning btn-sm btn-block"><i class="fa fa-file-image-o"></i> View Address Proof</a>
            <?php else: ?>
              <span class="text-muted">No address proof file</span>
            <?php endif; ?>
          </div>
        </div>

        <hr>

        <h4 style="font-weight: 600;"><i class="fa fa-gavel"></i> Administrative Decision</h4>
        <?php echo form_open('kyc-requests/review/' . $kyc->id); ?>
          <div class="form-group">
            <label>Verify Status Action</label>
            <select name="status" id="action_status" class="form-control" required>
              <option value="approved">Approve KYC (Releases verified shipments for transit)</option>
              <option value="rejected">Reject KYC (Blocks transit movements)</option>
            </select>
          </div>
          
          <div class="form-group" id="reject_reason_box" style="display: none;">
            <label class="text-danger">Reason for Rejection <span class="text-danger">*</span></label>
            <textarea name="reject_reason" id="reject_reason" class="form-control" rows="3" placeholder="Explain what document was incorrect or unreadable..."></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save Decision</button>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    function toggleReason() {
      if ($('#action_status').val() === 'rejected') {
        $('#reject_reason_box').show();
        $('#reject_reason').prop('required', true);
      } else {
        $('#reject_reason_box').hide();
        $('#reject_reason').prop('required', false).val('');
      }
    }
    
    toggleReason();
    $('#action_status').change(toggleReason);
  });
</script>
