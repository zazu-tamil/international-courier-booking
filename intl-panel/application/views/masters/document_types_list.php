<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Document Types Master</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addDocumentModal">
          <i class="fa fa-plus"></i> Add New Document Type
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Document Name</th>
              <th>Description / Internal Note</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($document_types as $d): ?>
              <tr>
                <td><?php echo $d->id; ?></td>
                <td><strong><?php echo $d->doc_type_name; ?></strong></td>
                <td><?php echo $d->description; ?></td>
                <td><?php echo date('d M Y H:i', strtotime($d->created_at)); ?></td>
                <td>
                  <button class="btn btn-primary btn-xs edit-document-btn" 
                          data-id="<?php echo $d->id; ?>"
                          data-name="<?php echo htmlspecialchars($d->doc_type_name); ?>"
                          data-desc="<?php echo htmlspecialchars($d->description); ?>"
                          data-toggle="modal" data-target="#editDocumentModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="<?php echo site_url('document-types/delete/' . $d->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this document type?');">
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

<!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('document-types/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create Document Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Document Type Name <span class="text-danger">*</span></label>
            <input type="text" name="doc_type_name" class="form-control" placeholder="e.g. Passport Copy" required>
          </div>
          <div class="form-group">
            <label>Description / Internal Note</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Optional notes about this document"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Document Type</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Document Modal -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editDocumentForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Document Type</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Document Type Name <span class="text-danger">*</span></label>
            <input type="text" name="doc_type_name" id="edit_doc_name" class="form-control" required>
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
    $('.edit-document-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var desc = $(this).data('desc');
      
      $('#editDocumentForm').attr('action', '<?php echo site_url("document-types/edit/"); ?>' + id);
      $('#edit_doc_name').val(name);
      $('#edit_description').val(desc);
    });
  });
</script>
