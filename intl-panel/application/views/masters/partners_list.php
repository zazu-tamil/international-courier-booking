<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-hand-o-right"></i> Courier Integration Partners</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addPartnerModal">
          <i class="fa fa-plus"></i> Add Partner
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Partner Name</th>
              <th>API Credentials payload</th>
              <th>Tracking URL Schema</th>
              <th>Supported Service Types</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($partners as $p): ?>
              <tr>
                <td><strong><?php echo $p->partner_name; ?></strong></td>
                <td><code style="font-size: 11px;"><?php echo htmlspecialchars($p->api_credentials); ?></code></td>
                <td><a href="#" class="text-muted" style="font-size: 12px;"><?php echo $p->tracking_url; ?></a></td>
                <td>
                  <?php 
                    $services = explode(',', $p->service_types);
                    foreach($services as $srv):
                  ?>
                    <span class="label label-primary"><?php echo trim($srv); ?></span>
                  <?php endforeach; ?>
                </td>
                <td>
                  <span class="label <?php echo ($p->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                    <?php echo $p->status; ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-partner-btn" 
                          data-id="<?php echo $p->id; ?>"
                          data-name="<?php echo $p->partner_name; ?>"
                          data-creds="<?php echo htmlspecialchars($p->api_credentials); ?>"
                          data-url="<?php echo $p->tracking_url; ?>"
                          data-services="<?php echo $p->service_types; ?>"
                          data-status="<?php echo $p->status; ?>"
                          data-toggle="modal" data-target="#editPartnerModal">
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

<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('partners/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add Courier Partner</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Partner Name <span class="text-danger">*</span></label>
            <input type="text" name="partner_name" class="form-control" placeholder="e.g. DHL Express" required>
          </div>
          <div class="form-group">
            <label>API Credentials (JSON payload)</label>
            <textarea name="api_credentials" class="form-control" rows="3" placeholder='{"api_key": "xxx", "account": "yyy"}'></textarea>
          </div>
          <div class="form-group">
            <label>Tracking URL Schema (AWB is appended to end)</label>
            <input type="url" name="tracking_url" class="form-control" placeholder="https://example.com/track?no=">
          </div>
          <div class="form-group">
            <label>Service Types (Comma separated)</label>
            <input type="text" name="service_types" class="form-control" placeholder="Express, Economy, Standard" value="Express,Economy">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Partner</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Partner Modal -->
<div class="modal fade" id="editPartnerModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editPartnerForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Courier Partner Integration</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Partner Name <span class="text-danger">*</span></label>
            <input type="text" name="partner_name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>API Credentials (JSON payload)</label>
            <textarea name="api_credentials" id="edit_creds" class="form-control" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Tracking URL Schema</label>
            <input type="url" name="tracking_url" id="edit_url" class="form-control">
          </div>
          <div class="form-group">
            <label>Service Types (Comma separated)</label>
            <input type="text" name="service_types" id="edit_services" class="form-control">
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
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-partner-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var creds = $(this).data('creds');
      var url = $(this).data('url');
      var services = $(this).data('services');
      var status = $(this).data('status');
      
      $('#editPartnerForm').attr('action', '<?php echo site_url("partners/edit/"); ?>' + id);
      $('#edit_name').val(name);
      $('#edit_creds').val(creds);
      $('#edit_url').val(url);
      $('#edit_services').val(services);
      $('#edit_status').val(status);
    });
  });
</script>
