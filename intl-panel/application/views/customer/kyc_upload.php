<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-id-card-o"></i> Submit KYC Verification Documents</h3>
      </div>
      
      <?php echo form_open_multipart('customer/kyc'); ?>
        <div class="box-body">
          <!-- Current KYC status -->
          <div class="well well-sm">
            <h4 style="margin-top: 5px;">KYC Verification Status: 
              <?php if(!$kyc || $kyc->status == 'pending'): ?>
                <span class="label label-warning"><i class="fa fa-clock-o"></i> Pending Verification</span>
              <?php elseif($kyc->status == 'approved'): ?>
                <span class="label label-success"><i class="fa fa-check-circle"></i> Approved & Cleared</span>
              <?php elseif($kyc->status == 'rejected'): ?>
                <span class="label label-danger"><i class="fa fa-times-circle"></i> Rejected</span>
              <?php endif; ?>
            </h4>
            <?php if($kyc && $kyc->status == 'rejected'): ?>
              <p class="text-danger" style="margin-top: 10px;"><strong>Reason for Rejection:</strong> <?php echo $kyc->reject_reason; ?></p>
            <?php endif; ?>
            <?php if($kyc && $kyc->status == 'approved'): ?>
              <p class="text-success" style="margin-top: 10px;">Approved by Admin on <?php echo date('d M Y H:i', strtotime($kyc->approved_at)); ?>.</p>
            <?php endif; ?>
          </div>

          <?php if(!$kyc || $kyc->status != 'approved'): ?>
            <div class="row">
              <div class="col-md-12 form-group">
                <label>Customer Type (Individual or Corporate Business)</label>
                <select id="kyc_type" class="form-control" disabled>
                  <option value="individual" <?php echo ($this->session->userdata('role_id') == 4 && $this->db->get_where('customers', array('id' => $this->session->userdata('customer_id')))->row()->customer_type == 'individual') ? 'selected' : ''; ?>>Individual</option>
                  <option value="business" <?php echo ($this->session->userdata('role_id') == 4 && $this->db->get_where('customers', array('id' => $this->session->userdata('customer_id')))->row()->customer_type == 'business') ? 'selected' : ''; ?>>Business Entity</option>
                </select>
              </div>
            </div>

            <!-- INDIVIDUAL FIELD TARGETS -->
            <div id="individual_fields" style="display: none;">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Passport Number</label>
                  <input type="text" name="passport_number" class="form-control" placeholder="Enter passport ID" value="<?php echo $kyc ? $kyc->passport_number : ''; ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label>Aadhaar Card Number</label>
                  <input type="text" name="aadhaar_number" class="form-control" placeholder="12 digit Aadhaar" value="<?php echo $kyc ? $kyc->aadhaar_number : ''; ?>" maxlength="12">
                </div>
              </div>
            </div>

            <!-- BUSINESS FIELD TARGETS -->
            <div id="business_fields" style="display: none;">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>GST Registration Number (GSTIN)</label>
                  <input type="text" name="gst_number" class="form-control" placeholder="15-digit GSTIN" value="<?php echo $kyc ? $kyc->gst_number : ''; ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label>Company PAN Card Number</label>
                  <input type="text" name="pan_number" class="form-control" placeholder="10-digit PAN" value="<?php echo $kyc ? $kyc->pan_number : ''; ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Trade License ID</label>
                  <input type="text" name="trade_license" class="form-control" placeholder="License Number" value="<?php echo $kyc ? $kyc->trade_license : ''; ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label>Company Registration Number</label>
                  <input type="text" name="company_registration_certificate" class="form-control" placeholder="Registration ID" value="<?php echo $kyc ? $kyc->company_registration_certificate : ''; ?>">
                </div>
              </div>
              <div class="form-group">
                <label>Authorized Signatory Person Details</label>
                <textarea name="authorized_person" class="form-control" rows="2" placeholder="Name, designation, and contact details of authorized signing executive"><?php echo $kyc ? $kyc->authorized_person : ''; ?></textarea>
              </div>
            </div>

            <hr>
            
            <!-- FILE UPLOADS -->
            <div class="row">
              <div class="col-md-6 form-group">
                <label>Upload ID Proof File (Passport, Aadhaar, PAN) <span class="text-danger">*</span></label>
                <input type="file" name="id_proof" class="form-control" accept="image/*,application/pdf" <?php echo (!$kyc || !$kyc->id_proof_file) ? 'required' : ''; ?>>
                <?php if($kyc && $kyc->id_proof_file): ?>
                  <p class="help-block"><a href="<?php echo base_url($kyc->id_proof_file); ?>" target="_blank" class="text-info"><i class="fa fa-external-link"></i> View current ID Proof doc</a></p>
                <?php endif; ?>
              </div>
              
              <div class="col-md-6 form-group">
                <label>Upload Address Proof File (Electricity Bill, Trade License) <span class="text-danger">*</span></label>
                <input type="file" name="address_proof" class="form-control" accept="image/*,application/pdf" <?php echo (!$kyc || !$kyc->address_proof_file) ? 'required' : ''; ?>>
                <?php if($kyc && $kyc->address_proof_file): ?>
                  <p class="help-block"><a href="<?php echo base_url($kyc->address_proof_file); ?>" target="_blank" class="text-info"><i class="fa fa-external-link"></i> View current Address Proof doc</a></p>
                <?php endif; ?>
              </div>
            </div>
            
            <p class="text-muted"><i class="fa fa-info-circle"></i> Supported formats: PDF, JPG, PNG. Max size: 5MB per document.</p>

          <?php else: ?>
            <!-- KYC details display for approved -->
            <div class="row">
              <?php if($kyc->passport_number): ?>
                <div class="col-md-6"><p><strong>Passport Number:</strong> <?php echo $kyc->passport_number; ?></p></div>
              <?php endif; ?>
              <?php if($kyc->aadhaar_number): ?>
                <div class="col-md-6"><p><strong>Aadhaar Number:</strong> <?php echo $kyc->aadhaar_number; ?></p></div>
              <?php endif; ?>
              <?php if($kyc->gst_number): ?>
                <div class="col-md-6"><p><strong>GST Number:</strong> <?php echo $kyc->gst_number; ?></p></div>
              <?php endif; ?>
              <?php if($kyc->pan_number): ?>
                <div class="col-md-6"><p><strong>PAN Number:</strong> <?php echo $kyc->pan_number; ?></p></div>
              <?php endif; ?>
              <?php if($kyc->trade_license): ?>
                <div class="col-md-6"><p><strong>Trade License:</strong> <?php echo $kyc->trade_license; ?></p></div>
              <?php endif; ?>
              <?php if($kyc->company_registration_certificate): ?>
                <div class="col-md-6"><p><strong>Company Registration ID:</strong> <?php echo $kyc->company_registration_certificate; ?></p></div>
              <?php endif; ?>
            </div>
            <div class="row" style="margin-top: 15px;">
              <div class="col-md-6">
                <a href="<?php echo base_url($kyc->id_proof_file); ?>" target="_blank" class="btn btn-default btn-sm btn-block"><i class="fa fa-id-card"></i> View ID Proof</a>
              </div>
              <div class="col-md-6">
                <a href="<?php echo base_url($kyc->address_proof_file); ?>" target="_blank" class="btn btn-default btn-sm btn-block"><i class="fa fa-map-marker"></i> View Address Proof</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
        
        <?php if(!$kyc || $kyc->status != 'approved'): ?>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Upload & Submit KYC</button>
          </div>
        <?php endif; ?>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    function toggleFields() {
      var type = $('#kyc_type').val();
      if (type === 'individual') {
        $('#individual_fields').show();
        $('#business_fields').hide();
      } else {
        $('#individual_fields').hide();
        $('#business_fields').show();
      }
    }
    toggleFields();
  });
</script>
