<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building-o"></i> Branches Directory</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addBranchModal">
          <i class="fa fa-plus"></i> Add Branch
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Branch Code</th>
              <th>Branch Name</th>
              <th>Contact Person</th>
              <th>Mobile</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($branches as $b): ?>
              <tr>
                <td><strong><?php echo $b->branch_code; ?></strong></td>
                <td><?php echo $b->name; ?></td>
                <td><?php echo $b->contact_person; ?></td>
                <td><?php echo $b->mobile; ?></td>
                <td><?php echo $b->email; ?></td>
                <td>
                  <span class="label <?php echo ($b->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                    <?php echo $b->status; ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-branch-btn" 
                          data-id="<?php echo $b->id; ?>"
                          data-name="<?php echo $b->name; ?>"
                          data-code="<?php echo $b->branch_code; ?>"
                          data-address="<?php echo $b->address; ?>"
                          data-contact="<?php echo $b->contact_person; ?>"
                          data-mobile="<?php echo $b->mobile; ?>"
                          data-email="<?php echo $b->email; ?>"
                          data-status="<?php echo $b->status; ?>"
                          data-toggle="modal" data-target="#editBranchModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <?php if($this->session->userdata('role_id') == 1): ?>
                    <a href="<?php echo site_url('branches/delete/' . $b->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this branch?');">
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

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('branches/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create New Branch</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Branch Code <span class="text-danger">*</span></label>
            <input type="text" name="branch_code" class="form-control" placeholder="e.g. BR-DEL" required>
          </div>
          <div class="form-group">
            <label>Branch Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. New Delhi Branch" required>
          </div>
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control" placeholder="Manager Name">
          </div>
          <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" class="form-control" placeholder="Contact number">
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="branch@couriersyn.com">
          </div>
          <div class="form-group">
            <label>Physical Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Full street address"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Branch</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editBranchForm" action="" method="POST">
        <!-- CI3 CSRF Token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Branch Details</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Branch Code</label>
            <input type="text" id="edit_code" class="form-control" disabled>
          </div>
          <div class="form-group">
            <label>Branch Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" id="edit_contact" class="form-control">
          </div>
          <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" id="edit_mobile" class="form-control">
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" id="edit_email" class="form-control">
          </div>
          <div class="form-group">
            <label>Physical Address</label>
            <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
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
          <button type="submit" class="btn btn-primary">Update Details</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-branch-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var code = $(this).data('code');
      var address = $(this).data('address');
      var contact = $(this).data('contact');
      var mobile = $(this).data('mobile');
      var email = $(this).data('email');
      var status = $(this).data('status');
      
      $('#editBranchForm').attr('action', '<?php echo site_url("branches/edit/"); ?>' + id);
      $('#edit_name').val(name);
      $('#edit_code').val(code);
      $('#edit_address').val(address);
      $('#edit_contact').val(contact);
      $('#edit_mobile').val(mobile);
      $('#edit_email').val(email);
      $('#edit_status').val(status);
    });
  });
</script>
