<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Users for Branch: <?php echo htmlspecialchars($branch->name); ?></h3>
        <a href="<?php echo site_url('branches'); ?>" class="btn btn-default btn-sm pull-right">
          <i class="fa fa-arrow-left"></i> Back to Branches
        </a>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($users)): ?>
              <?php foreach($users as $u): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($u->username); ?></strong></td>
                  <td><?php echo htmlspecialchars($u->email); ?></td>
                  <td><?php echo htmlspecialchars($u->role_name); ?></td>
                  <td>
                    <span class="label <?php echo ($u->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                      <?php echo $u->status; ?>
                    </span>
                  </td>
                  <td><?php echo date('d M Y, h:i A', strtotime($u->created_at)); ?></td>
                  <td>
                    <button class="btn btn-primary btn-xs edit-user-btn" 
                            data-id="<?php echo $u->id; ?>"
                            data-username="<?php echo htmlspecialchars($u->username); ?>"
                            data-email="<?php echo htmlspecialchars($u->email); ?>"
                            data-role="<?php echo $u->role_id; ?>"
                            data-status="<?php echo $u->status; ?>"
                            data-toggle="modal" data-target="#editBranchUserModal">
                      <i class="fa fa-pencil"></i> Edit
                    </button>
                    <?php if($this->session->userdata('role_id') == 1): ?>
                      <a href="<?php echo site_url('branches/delete-user/' . $u->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this user?');">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">No users found for this branch.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Edit Branch User Modal -->
<div class="modal fade" id="editBranchUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('', array('id' => 'editBranchUserForm')); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Branch User</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" id="edit_username" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
          </div>

          <div class="form-group">
            <label>User Role <span class="text-danger">*</span></label>
            <select name="role_id" id="edit_role" class="form-control" required>
              <?php foreach($roles as $role): ?>
                <option value="<?php echo $role->id; ?>"><?php echo htmlspecialchars($role->name); ?></option>
              <?php endforeach; ?>
            </select>
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
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('.edit-user-btn').click(function() {
        var id = $(this).data('id');
        $('#edit_username').val($(this).data('username'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_role').val($(this).data('role'));
        $('#edit_status').val($(this).data('status'));
        
        var actionUrl = '<?php echo site_url('branches/edit-user/'); ?>' + id;
        $('#editBranchUserForm').attr('action', actionUrl);
    });
});
</script>
