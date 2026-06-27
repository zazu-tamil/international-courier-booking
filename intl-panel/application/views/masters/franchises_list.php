<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-handshake-o"></i> Franchise Network Directory</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#registerFranchiseModal">
          <i class="fa fa-plus"></i> Register Franchise
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Franchise Code</th>
              <th>Name</th>
              <th>Login Email</th>
              <th>Security Deposit</th>
              <th>Agreement Date</th>
              <th>Revenue Share</th>
              <th>Commission</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($franchises as $f): ?>
              <tr>
                <td><strong><?php echo $f->franchise_code; ?></strong></td>
                <td><?php echo $f->name; ?></td>
                <td><?php echo $f->user_email; ?></td>
                <td>₹<?php echo number_format($f->deposit_amount, 2); ?></td>
                <td><?php echo $f->agreement_date ? date('d M Y', strtotime($f->agreement_date)) : '-'; ?></td>
                <td><?php echo $f->revenue_sharing_percentage; ?>%</td>
                <td><?php echo $f->commission_percentage; ?>%</td>
                <td>
                  <span class="label <?php echo ($f->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                    <?php echo $f->status; ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-franchise-btn" 
                          data-id="<?php echo $f->id; ?>"
                          data-name="<?php echo htmlspecialchars($f->name); ?>"
                          data-code="<?php echo $f->franchise_code; ?>"
                          data-email="<?php echo htmlspecialchars($f->user_email); ?>"
                          data-deposit="<?php echo $f->deposit_amount; ?>"
                          data-agreement="<?php echo $f->agreement_date; ?>"
                          data-sharing="<?php echo $f->revenue_sharing_percentage; ?>"
                          data-commission="<?php echo $f->commission_percentage; ?>"
                          data-status="<?php echo $f->status; ?>"
                          data-toggle="modal" data-target="#editFranchiseModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <?php if($this->session->userdata('role_id') == 1): ?>
                    <a href="<?php echo site_url('franchises/delete/' . $f->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to completely delete this franchise and its login user?');">
                      <i class="fa fa-trash"></i> Delete
                    </a>
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

<!-- Register Franchise Modal -->
<div class="modal fade" id="registerFranchiseModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('franchises/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Register New Franchise Partner</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Franchise Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Partner Name" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Franchise Code <span class="text-danger">*</span></label>
              <input type="text" name="franchise_code" class="form-control" placeholder="e.g. FR-MUM" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Login Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="franchise@couriersyn.com" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Login Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Password for login" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Security Deposit Amount</label>
              <input type="number" name="deposit_amount" class="form-control" placeholder="₹0.00" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Agreement Date</label>
              <input type="date" name="agreement_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Revenue Sharing Percentage (%)</label>
              <input type="number" step="0.01" name="revenue_sharing_percentage" class="form-control" placeholder="0.00" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Commission Percentage (%)</label>
              <input type="number" step="0.01" name="commission_percentage" class="form-control" placeholder="0.00" value="0">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Register Partner</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Franchise Modal -->
<div class="modal fade" id="editFranchiseModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editFranchiseForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Modify Franchise Settings</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Franchise Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Franchise Code</label>
              <input type="text" id="edit_code" class="form-control" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Login Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Security Deposit Amount</label>
              <input type="number" name="deposit_amount" id="edit_deposit" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Agreement Date</label>
              <input type="date" name="agreement_date" id="edit_agreement" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Revenue Sharing Percentage (%)</label>
              <input type="number" step="0.01" name="revenue_sharing_percentage" id="edit_sharing" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Commission Percentage (%)</label>
              <input type="number" step="0.01" name="commission_percentage" id="edit_commission" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-franchise-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var code = $(this).data('code');
      var deposit = $(this).data('deposit');
      var agreement = $(this).data('agreement');
      var sharing = $(this).data('sharing');
      var commission = $(this).data('commission');
      var status = $(this).data('status');
      
      $('#editFranchiseForm').attr('action', '<?php echo site_url("franchises/edit/"); ?>' + id);
      $('#edit_name').val(name);
      $('#edit_code').val(code);
      $('#edit_email').val($(this).data('email'));
      $('#edit_deposit').val(deposit);
      $('#edit_agreement').val(agreement);
      $('#edit_sharing').val(sharing);
      $('#edit_commission').val(commission);
      $('#edit_status').val(status);
    });
  });
</script>
