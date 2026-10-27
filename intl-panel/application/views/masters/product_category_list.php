<!-- Content Header (Page header) -->
<section class="content-header" style="padding-top: 5px; padding-bottom: 15px;">
  <h1>
    <i class="fa fa-tags text-primary"></i> Product Category Master
    <small>Manage shipment product categories, special handling surcharges &amp; statuses</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo site_url('domestic/bookings'); ?>">Domestic</a></li>
    <li class="active">Product Category</li>
  </ol>
</section>

<!-- KPI Stats Row -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-aqua">
      <span class="info-box-icon"><i class="fa fa-tags"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Categories</span>
        <span class="info-box-number"><?php echo isset($stats->total_categories) ? $stats->total_categories : count($categories); ?></span>
        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
        <span class="progress-description">Classification Types</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-green">
      <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Active Categories</span>
        <span class="info-box-number"><?php echo isset($stats->active_categories) ? $stats->active_categories : 0; ?></span>
        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
        <span class="progress-description">Available for Bookings</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-red">
      <span class="info-box-icon"><i class="fa fa-pause-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">InActive Categories</span>
        <span class="info-box-number"><?php echo isset($stats->inactive_categories) ? $stats->inactive_categories : 0; ?></span>
        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
        <span class="progress-description">Temporarily Disabled</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-purple">
      <span class="info-box-icon"><i class="fa fa-money"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">With Extra Surcharge</span>
        <span class="info-box-number"><?php echo isset($stats->extra_charge_categories) ? $stats->extra_charge_categories : 0; ?></span>
        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
        <span class="progress-description">Special Handling Fee</span>
      </div>
    </div>
  </div>
</div>

<!-- Main Row -->
<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border" style="padding: 12px 18px;">
        <h3 class="box-title" style="font-size: 16px; font-weight: 700;">
          <i class="fa fa-list"></i> Product Category Directory
          <small class="badge bg-blue" style="margin-left: 8px;"><?php echo count($categories); ?> Records</small>
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addCategoryModal" style="background-color: #28a745; border-color: #28a745; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Add New Category
          </button>
        </div>
      </div>

      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable" style="width: 100%; font-size: 13px;">
          <thead>
            <tr class="bg-gray-light">
              <th style="width: 60px;" class="text-center">#</th>
              <th>Category Name</th>
              <th style="width: 160px;" class="text-center">Extra Handling Charge</th>
              <th style="width: 100px;" class="text-center">Status</th>
              <th style="width: 160px;" class="text-center">Created Date</th>
              <th style="width: 130px;" class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($categories)): ?>
              <?php foreach ($categories as $idx => $cat): ?>
                <tr>
                  <td class="text-center" style="vertical-align: middle; font-weight: 600;"><?php echo ($idx + 1); ?></td>
                  <td style="vertical-align: middle;">
                    <i class="fa fa-tag text-primary" style="margin-right: 6px;"></i>
                    <strong style="font-size: 14px; color: #333;"><?php echo htmlspecialchars($cat->category_name); ?></strong>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">
                    <?php if (floatval($cat->extra_charge) > 0): ?>
                      <span class="badge bg-green" style="font-size: 13px; font-weight: 700; padding: 4px 8px;">
                        &#8377;<?php echo number_format($cat->extra_charge, 2); ?>
                      </span>
                    <?php else: ?>
                      <span class="text-muted">&#8377;0.00</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">
                    <?php if ($cat->status === 'Active'): ?>
                      <span class="label label-success"><i class="fa fa-check-circle"></i> Active</span>
                    <?php else: ?>
                      <span class="label label-danger"><i class="fa fa-pause-circle"></i> InActive</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center" style="vertical-align: middle; color: #666;">
                    <?php echo !empty($cat->created_at) ? date('d-M-Y H:i', strtotime($cat->created_at)) : '-'; ?>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs edit-cat-btn"
                              data-id="<?php echo $cat->category_id; ?>"
                              data-name="<?php echo htmlspecialchars($cat->category_name, ENT_QUOTES); ?>"
                              data-charge="<?php echo floatval($cat->extra_charge); ?>"
                              data-status="<?php echo $cat->status; ?>"
                              title="Edit Category">
                        <i class="fa fa-pencil"></i> Edit
                      </button>
                      <a href="<?php echo site_url('domestic/categories/delete/' . $cat->category_id); ?>" 
                         class="btn btn-danger btn-xs" 
                         onclick="return confirm('Are you sure you want to delete product category \'<?php echo addslashes($cat->category_name); ?>\'?');"
                         title="Delete Category">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- ADD PRODUCT CATEGORY MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel">
  <div class="modal-dialog" role="document">
    <?php echo form_open('domestic/categories/add'); ?>
      <div class="modal-content" style="border-radius: 6px; overflow: hidden;">
        <div class="modal-header bg-green" style="background-color: #28a745; color: #fff; padding: 12px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; font-size: 24px; margin-top: -2px;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addCategoryModalLabel" style="font-weight: 600; font-size: 17px;">
            <i class="fa fa-plus-circle"></i> Add New Product Category
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px 25px;">
          
          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="category_name" class="form-control" placeholder="e.g. Electronics, Liquid, Fragile, Apparel..." required>
            <p class="help-block text-muted" style="font-size: 12px; margin-bottom: 0;">Unique classification label for consignments</p>
          </div>

          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Extra Handling Charge</label>
            <div class="input-group">
              <span class="input-group-addon" style="font-weight: 700; background-color: #f7f7f7;">&#8377;</span>
              <input type="number" step="0.01" min="0" name="extra_charge" class="form-control" value="0.00" placeholder="0.00">
            </div>
            <p class="help-block text-muted" style="font-size: 12px; margin-bottom: 0;">Optional surcharge automatically applied for special handling of items in this category</p>
          </div>

          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Status</label>
            <select name="status" class="form-control">
              <option value="Active" selected>Active</option>
              <option value="InActive">InActive</option>
            </select>
          </div>

        </div>
        <div class="modal-footer" style="background-color: #f9f9f9; padding: 12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="min-width: 80px;">Cancel</button>
          <button type="submit" class="btn btn-success" style="background-color: #28a745; border-color: #28a745; font-weight: 600;">
            <i class="fa fa-save"></i> Save Category
          </button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- EDIT PRODUCT CATEGORY MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel">
  <div class="modal-dialog" role="document">
    <?php echo form_open('', array('id' => 'editCategoryForm')); ?>
      <input type="hidden" name="category_id" id="edit_category_id">
      <div class="modal-content" style="border-radius: 6px; overflow: hidden;">
        <div class="modal-header bg-primary" style="background-color: #007bff; color: #fff; padding: 12px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; font-size: 24px; margin-top: -2px;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editCategoryModalLabel" style="font-weight: 600; font-size: 17px;">
            <i class="fa fa-pencil-square-o"></i> Edit Product Category
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px 25px;">
          
          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
          </div>

          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Extra Handling Charge</label>
            <div class="input-group">
              <span class="input-group-addon" style="font-weight: 700; background-color: #f7f7f7;">&#8377;</span>
              <input type="number" step="0.01" min="0" name="extra_charge" id="edit_extra_charge" class="form-control">
            </div>
            <p class="help-block text-muted" style="font-size: 12px; margin-bottom: 0;">Optional surcharge automatically applied for special handling of items in this category</p>
          </div>

          <div class="form-group">
            <label style="font-weight: 700; color: #333;">Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="Active">Active</option>
              <option value="InActive">InActive</option>
            </select>
          </div>

        </div>
        <div class="modal-footer" style="background-color: #f9f9f9; padding: 12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="min-width: 80px;">Cancel</button>
          <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; font-weight: 600;">
            <i class="fa fa-save"></i> Update Changes
          </button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
$(document).ready(function() {
  $('.edit-cat-btn').on('click', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var charge = parseFloat($(this).data('charge') || 0).toFixed(2);
    var status = $(this).data('status');

    $('#edit_category_id').val(id);
    $('#edit_category_name').val(name);
    $('#edit_extra_charge').val(charge);
    $('#edit_status').val(status);

    $('#editCategoryForm').attr('action', "<?php echo site_url('domestic/categories/edit/'); ?>" + id);
    $('#editCategoryModal').modal('show');
  });
});
</script>
