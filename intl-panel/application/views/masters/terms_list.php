<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Terms & Conditions Versions</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addTermsModal">
          <i class="fa fa-plus"></i> Create New Version
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Title</th>
              <th>Version</th>
              <th>Effective Date</th>
              <th>Status</th>
              <th>Date Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($terms as $t): ?>
              <tr>
                <td><strong><?php echo $t->title; ?></strong></td>
                <td><span class="label label-default">v<?php echo $t->version_number; ?></span></td>
                <td><?php echo date('d M Y', strtotime($t->effective_date)); ?></td>
                <td>
                  <?php if($t->status == 'Published'): ?>
                    <span class="label label-success"><i class="fa fa-check"></i> Published (Active)</span>
                  <?php elseif($t->status == 'Archived'): ?>
                    <span class="label label-default">Archived</span>
                  <?php else: ?>
                    <span class="label label-warning">Draft</span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('d M Y H:i', strtotime($t->created_at)); ?></td>
                <td>
                  <button class="btn btn-primary btn-xs edit-terms-btn" 
                          data-id="<?php echo $t->id; ?>"
                          data-title="<?php echo $t->title; ?>"
                          data-version="<?php echo $t->version_number; ?>"
                          data-effective="<?php echo $t->effective_date; ?>"
                          data-status="<?php echo $t->status; ?>"
                          data-content="<?php echo htmlspecialchars($t->terms_content); ?>"
                          data-toggle="modal" data-target="#editTermsModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Terms Modal -->
<div class="modal fade" id="addTermsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <?php echo form_open('terms/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create Terms & Conditions Version</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="e.g. Standard Terms of Service v1.2" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Version Number <span class="text-danger">*</span></label>
              <input type="text" name="version_number" class="form-control" placeholder="e.g. 1.2" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Effective Date <span class="text-danger">*</span></label>
              <input type="date" name="effective_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="Draft">Draft</option>
              <option value="Published">Published (Will archive existing published terms)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Terms Content <span class="text-danger">*</span></label>
            <textarea name="terms_content" id="terms_content_add" class="form-control" rows="10" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Version</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Terms Modal -->
<div class="modal fade" id="editTermsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="editTermsForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Terms Version Details</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Version Number <span class="text-danger">*</span></label>
              <input type="text" name="version_number" id="edit_version" class="form-control" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Effective Date <span class="text-danger">*</span></label>
              <input type="date" name="effective_date" id="edit_effective" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="Draft">Draft</option>
              <option value="Published">Published (Will archive other published terms)</option>
              <option value="Archived">Archived</option>
            </select>
          </div>
          <div class="form-group">
            <label>Terms Content <span class="text-danger">*</span></label>
            <textarea name="terms_content" id="terms_content_edit" class="form-control" rows="10" required></textarea>
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
    // Initialize CKEditors
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.replace('terms_content_add');
    var editorEdit = CKEDITOR.replace('terms_content_edit');

    $('.edit-terms-btn').click(function() {
      var id = $(this).data('id');
      var title = $(this).data('title');
      var version = $(this).data('version');
      var effective = $(this).data('effective');
      var status = $(this).data('status');
      
      // Decode HTML content safely
      var contentEl = document.createElement('div');
      contentEl.innerHTML = $(this).data('content');
      var content = contentEl.textContent || contentEl.innerText;

      $('#editTermsForm').attr('action', '<?php echo site_url("terms/edit/"); ?>' + id);
      $('#edit_title').val(title);
      $('#edit_version').val(version);
      $('#edit_effective').val(effective);
      $('#edit_status').val(status);
      
      // Set editor value
      editorEdit.setData(content);
    });
  });
</script>
