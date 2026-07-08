<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Billing Charge Types</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addChargeTypeModal">
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
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($charge_types as $ct): ?>
              <tr>
                <td><?php echo $ct->id; ?></td>
                <td><strong><?php echo htmlspecialchars($ct->charge_name); ?></strong></td>
                <td>
                  <?php if($ct->status == 'Active'): ?>
                    <span class="label label-success">Active</span>
                  <?php else: ?>
                    <span class="label label-danger">Inactive</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-btn" 
                          data-id="<?php echo $ct->id; ?>"
                          data-name="<?php echo htmlspecialchars($ct->charge_name, ENT_QUOTES); ?>"
                          data-status="<?php echo $ct->status; ?>"
                          data-toggle="modal" data-target="#editChargeTypeModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="<?php echo site_url('charge-types/delete/' . $ct->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this charge type? This might affect billing and invoices.');">
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

<!-- Add Charge Type Modal -->
<div class="modal fade" id="addChargeTypeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?php echo form_open('charge-types/add'); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Charge Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Charge Name <span class="text-danger">*</span></label>
            <input type="text" name="charge_name" class="form-control" required placeholder="e.g. Fuel Surcharge">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Charge Type</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- Edit Charge Type Modal -->
<div class="modal fade" id="editChargeTypeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?php echo form_open('', array('id' => 'editChargeTypeForm')); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Edit Charge Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Charge Name <span class="text-danger">*</span></label>
            <input type="text" name="charge_name" id="edit_charge_name" class="form-control" required>
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Charge Type</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
$(document).ready(function(){
    $('.edit-btn').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');
        
        $('#edit_charge_name').val(name);
        $('#edit_status').val(status);
        
        $('#editChargeTypeForm').attr('action', '<?php echo site_url("charge-types/edit/"); ?>' + id);
    });
});
</script>
