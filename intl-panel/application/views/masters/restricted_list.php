<div class="row">
  <div class="col-xs-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-warning text-yellow"></i> Country-Specific Prohibited &amp; Restricted Items Directory</h3>
      </div>
      <div class="box-body table-responsive">
        <p class="text-muted">Below is a checklist of prohibited international cargo commodities grouped by destination countries. Entering any of these items during shipment booking will trigger instant alerts to shipping staff and exporters.</p>
        
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th style="width: 200px;">Country</th>
              <th style="width: 100px;">ISO Code</th>
              <th>Prohibited &amp; Restricted Items</th>
              <th style="width: 150px;">Customs Invoice Required</th>
              <th style="width: 150px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($countries as $c): ?>
              <tr>
                <td><strong><?php echo $c->country_name; ?></strong></td>
                <td><span class="label label-default"><?php echo $c->iso_code; ?></span></td>
                <td>
                  <?php if(empty($c->restricted_items)): ?>
                    <span class="text-success"><i class="fa fa-check-circle"></i> No specific restrictions cataloged.</span>
                  <?php else: ?>
                    <?php 
                      $items = explode(',', $c->restricted_items);
                      foreach($items as $it):
                        if (trim($it) === '') continue;
                    ?>
                      <span class="label label-danger" style="display: inline-block; margin: 3px; font-size: 11px; padding: 4px 6px;">
                        <i class="fa fa-ban"></i> <?php echo htmlspecialchars(trim($it)); ?>
                        <a href="<?php echo site_url('restricted-items/delete/' . $c->id . '/' . urlencode(trim($it))); ?>" 
                           style="color: #fff; margin-left: 5px; font-weight: bold; opacity: 0.8; text-decoration: none;"
                           onclick="return confirm('Remove \'<?php echo htmlspecialchars(trim($it)); ?>\' from restricted list?');"
                           title="Remove item">&times;</a>
                      </span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($c->customs_required == 1): ?>
                    <span class="label label-warning"><i class="fa fa-shield"></i> Yes</span>
                  <?php else: ?>
                    <span class="text-muted">No</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-success btn-xs add-restricted-btn" 
                          data-id="<?php echo $c->id; ?>" 
                          data-country="<?php echo htmlspecialchars($c->country_name); ?>"
                          data-toggle="modal" data-target="#addRestrictedModal">
                    <i class="fa fa-plus"></i> Add Item
                  </button>
                  <button class="btn btn-warning btn-xs edit-restricted-btn" 
                          data-id="<?php echo $c->id; ?>" 
                          data-country="<?php echo htmlspecialchars($c->country_name); ?>" 
                          data-items="<?php echo htmlspecialchars($c->restricted_items); ?>"
                          data-toggle="modal" data-target="#editRestrictedModal">
                    <i class="fa fa-pencil"></i> Edit List
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

<!-- Add Restricted Item Modal -->
<div class="modal fade" id="addRestrictedModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('restricted-items/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add Restricted Item for <span id="add_country_label" class="text-blue" style="font-weight: 600;"></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="country_id" id="add_country_id">
          <div class="form-group">
            <label>Restricted Item Name <span class="text-danger">*</span></label>
            <input type="text" name="item" class="form-control" placeholder="e.g. Lithium Batteries" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Add Item</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Restricted Items Modal -->
<div class="modal fade" id="editRestrictedModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editRestrictedForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Restricted List for <span id="edit_country_label" class="text-blue" style="font-weight: 600;"></span></h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Restricted Items (Comma-Separated) <span class="text-danger">*</span></label>
            <textarea name="restricted_items" id="edit_restricted_list" class="form-control" rows="4" placeholder="e.g. Liquids, Matches, Batteries"></textarea>
            <small class="text-muted">Separate items with commas. Example: Liquids, Tobacco, Weapons</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update List</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Add Item Click
    $('.add-restricted-btn').click(function() {
      var id = $(this).data('id');
      var country = $(this).data('country');
      $('#add_country_id').val(id);
      $('#add_country_label').text(country);
    });

    // Edit List Click
    $('.edit-restricted-btn').click(function() {
      var id = $(this).data('id');
      var country = $(this).data('country');
      var items = $(this).data('items');
      
      $('#editRestrictedForm').attr('action', '<?php echo site_url("restricted-items/edit/"); ?>' + id);
      $('#edit_country_label').text(country);
      $('#edit_restricted_list').val(items);
    });
  });
</script>
