<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-money"></i> Additional Charges Types</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addChargeModal">
          <i class="fa fa-plus"></i> Add New Charge Type
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Charge Name</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($charges as $c): ?>
              <tr>
                <td><?php echo $c->id; ?></td>
                <td><strong><?php echo $c->charge_name; ?></strong></td>
                <td>
                  <?php if($c->status == 'Active'): ?>
                    <span class="label label-success">Active</span>
                  <?php else: ?>
                    <span class="label label-danger">Inactive</span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('d M Y H:i', strtotime($c->created_at)); ?></td>
                <td>
                  <button class="btn btn-primary btn-xs edit-charge-btn" 
                          data-id="<?php echo $c->id; ?>"
                          data-name="<?php echo htmlspecialchars($c->charge_name); ?>"
                          data-status="<?php echo htmlspecialchars($c->status); ?>"
                          data-toggle="modal" data-target="#editChargeModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="<?php echo site_url('masters/delete_additional_charge/' . $c->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this charge type?');">
                    <i class="fa fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Charge Modal -->
<div class="modal fade" id="addChargeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('masters/add_additional_charge'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create Additional Charge Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Charge Name <span class="text-danger">*</span></label>
            <input type="text" name="charge_name" class="form-control" placeholder="e.g. Fuel Surcharge" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Charge Type</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Charge Modal -->
<div class="modal fade" id="editChargeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editChargeForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Additional Charge Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Charge Name <span class="text-danger">*</span></label>
            <input type="text" name="charge_name" id="edit_charge_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" id="edit_status" class="form-control" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-charge-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var status = $(this).data('status');
      
      $('#editChargeForm').attr('action', '<?php echo site_url("masters/edit_additional_charge/"); ?>' + id);
      $('#edit_charge_name').val(name);
      $('#edit_status').val(status);
    });
  });
</script>
