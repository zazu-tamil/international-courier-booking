<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-truck"></i> Service Types Master</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addServiceModal">
          <i class="fa fa-plus"></i> Add New Service Type
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Service Name</th>
              <th>Description / Internal Note</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($service_types as $s): ?>
              <tr>
                <td><?php echo $s->id; ?></td>
                <td><strong><?php echo $s->service_name; ?></strong></td>
                <td><?php echo $s->description; ?></td>
                <td><?php echo date('d M Y H:i', strtotime($s->created_at)); ?></td>
                <td>
                  <button class="btn btn-primary btn-xs edit-service-btn" 
                          data-id="<?php echo $s->id; ?>"
                          data-name="<?php echo htmlspecialchars($s->service_name); ?>"
                          data-desc="<?php echo htmlspecialchars($s->description); ?>"
                          data-toggle="modal" data-target="#editServiceModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="<?php echo site_url('service-types/delete/' . $s->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this service type?');">
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

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('service-types/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create Service Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Service Name <span class="text-danger">*</span></label>
            <input type="text" name="service_name" class="form-control" placeholder="e.g. Express" required>
          </div>
          <div class="form-group">
            <label>Description / Internal Note</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Optional notes about this service"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Service</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editServiceForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Service Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Service Name <span class="text-danger">*</span></label>
            <input type="text" name="service_name" id="edit_service_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Description / Internal Note</label>
            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
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
    $('.edit-service-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var desc = $(this).data('desc');
      
      $('#editServiceForm').attr('action', '<?php echo site_url("service-types/edit/"); ?>' + id);
      $('#edit_service_name').val(name);
      $('#edit_description').val(desc);
    });
  });
</script>
