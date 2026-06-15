<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-globe"></i> International Countries Database</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addCountryModal">
          <i class="fa fa-plus"></i> Add Country
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Country Name</th>
              <th>ISO Code</th>
              <th>Dial Code</th>
              <th>Currency</th>
              <th>Customs Invoice Required</th>
              <th>Prohibited / Restricted Items</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($countries as $c): ?>
              <tr>
                <td><strong><?php echo $c->country_name; ?></strong></td>
                <td><span class="label label-default"><?php echo $c->iso_code; ?></span></td>
                <td>+<?php echo $c->country_code; ?></td>
                <td><?php echo $c->currency; ?></td>
                <td>
                  <?php if($c->customs_required == 1): ?>
                    <span class="label label-warning"><i class="fa fa-shield"></i> Yes (Customs PDF required)</span>
                  <?php else: ?>
                    <span class="text-muted">No</span>
                  <?php endif; ?>
                </td>
                <td style="max-width: 250px; font-size: 13px;"><?php echo $c->restricted_items ? $c->restricted_items : '<em class="text-muted">None</em>'; ?></td>
                <td>
                  <span class="label <?php echo ($c->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                    <?php echo $c->status; ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-country-btn" 
                          data-id="<?php echo $c->id; ?>"
                          data-name="<?php echo $c->country_name; ?>"
                          data-iso="<?php echo $c->iso_code; ?>"
                          data-dial="<?php echo $c->country_code; ?>"
                          data-currency="<?php echo $c->currency; ?>"
                          data-customs="<?php echo $c->customs_required; ?>"
                          data-restricted="<?php echo htmlspecialchars($c->restricted_items); ?>"
                          data-status="<?php echo $c->status; ?>"
                          data-toggle="modal" data-target="#editCountryModal">
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

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('countries/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add New Country Destination</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Country Name <span class="text-danger">*</span></label>
              <input type="text" name="country_name" class="form-control" placeholder="e.g. Australia" required>
            </div>
            <div class="col-md-6 form-group">
              <label>ISO Code (3 letters) <span class="text-danger">*</span></label>
              <input type="text" name="iso_code" class="form-control" placeholder="e.g. AUS" maxlength="3" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Dial Code (e.g. 61) <span class="text-danger">*</span></label>
              <input type="text" name="country_code" class="form-control" placeholder="Dial prefix" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Currency Code (e.g. AUD)</label>
              <input type="text" name="currency" class="form-control" placeholder="USD" value="USD">
            </div>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="customs_required" value="1"> 
              <strong>Customs Invoice Required</strong> (Triggers mandatory Commercial Invoice & Customs PDF generation)
            </label>
          </div>
          <div class="form-group">
            <label>Restricted / Prohibited Items List</label>
            <textarea name="restricted_items" class="form-control" rows="3" placeholder="Comma-separated items that are banned or warn-triggered during booking (e.g. Batteries, Liquids, Medicine)"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Country</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editCountryForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Country Configuration</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Country Name <span class="text-danger">*</span></label>
              <input type="text" name="country_name" id="edit_name" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>ISO Code</label>
              <input type="text" id="edit_iso" class="form-control" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Dial Code <span class="text-danger">*</span></label>
              <input type="text" name="country_code" id="edit_dial" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Currency Code</label>
              <input type="text" name="currency" id="edit_currency" class="form-control">
            </div>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="customs_required" id="edit_customs" value="1"> 
              <strong>Customs Invoice Required</strong>
            </label>
          </div>
          <div class="form-group">
            <label>Restricted / Prohibited Items List</label>
            <textarea name="restricted_items" id="edit_restricted" class="form-control" rows="3"></textarea>
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
    $('.edit-country-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var iso = $(this).data('iso');
      var dial = $(this).data('dial');
      var currency = $(this).data('currency');
      var customs = $(this).data('customs');
      var restricted = $(this).data('restricted');
      var status = $(this).data('status');
      
      $('#editCountryForm').attr('action', '<?php echo site_url("countries/edit/"); ?>' + id);
      $('#edit_name').val(name);
      $('#edit_iso').val(iso);
      $('#edit_dial').val(dial);
      $('#edit_currency').val(currency);
      $('#edit_restricted').val(restricted);
      $('#edit_status').val(status);
      
      if (customs == 1) {
        $('#edit_customs').prop('checked', true);
      } else {
        $('#edit_customs').prop('checked', false);
      }
    });
  });
</script>
