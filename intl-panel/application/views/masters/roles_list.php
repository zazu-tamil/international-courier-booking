<div class="row">
  <div class="col-xs-12">
    
    <!-- Custom tab layout -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_roles" data-toggle="tab"><i class="fa fa-users"></i> Roles Directory</a></li>
        <li><a href="#tab_matrix" data-toggle="tab"><i class="fa fa-check-square-o"></i> Permissions Matrix</a></li>
        <li><a href="#tab_permissions" data-toggle="tab"><i class="fa fa-book"></i> System Permissions Glossary</a></li>
      </ul>
      
      <div class="tab-content">
        
        <!-- Tab 1: Roles List -->
        <div class="tab-pane active" id="tab_roles">
          <div class="row" style="margin-bottom: 15px;">
            <div class="col-xs-12">
              <h4 style="margin-top: 0; display: inline-block;"><i class="fa fa-circle-o text-blue"></i> Defined System &amp; Custom Roles</h4>
              <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addRoleModal">
                <i class="fa fa-plus"></i> Add Custom Role
              </button>
            </div>
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 80px;">Role ID</th>
                  <th>Role Name</th>
                  <th>Description</th>
                  <th style="width: 150px;">Role Type</th>
                  <th style="width: 150px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($roles as $r): ?>
                  <tr>
                    <td><strong><?php echo $r->id; ?></strong></td>
                    <td><span class="text-blue" style="font-weight: 600;"><?php echo htmlspecialchars($r->name); ?></span></td>
                    <td><?php echo htmlspecialchars($r->description); ?></td>
                    <td>
                      <?php if($r->id <= 4): ?>
                        <span class="label label-primary"><i class="fa fa-lock"></i> Built-in System Role</span>
                      <?php else: ?>
                        <span class="label label-warning"><i class="fa fa-user-plus"></i> Custom Role</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if($r->id <= 4): ?>
                        <button class="btn btn-default btn-xs" disabled title="System roles cannot be modified">
                          <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button class="btn btn-default btn-xs" disabled title="System roles cannot be deleted">
                          <i class="fa fa-trash"></i> Delete
                        </button>
                      <?php else: ?>
                        <button class="btn btn-warning btn-xs edit-role-btn" 
                                data-id="<?php echo $r->id; ?>"
                                data-name="<?php echo htmlspecialchars($r->name); ?>"
                                data-description="<?php echo htmlspecialchars($r->description); ?>"
                                data-toggle="modal" data-target="#editRoleModal">
                          <i class="fa fa-pencil"></i> Edit
                        </button>
                        <a href="<?php echo site_url('roles/delete/' . $r->id); ?>" 
                           class="btn btn-danger btn-xs" 
                           onclick="return confirm('Are you sure you want to delete this custom role? This will also wipe its permissions maps.');">
                          <i class="fa fa-trash"></i> Delete
                        </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Tab 2: Permission Matrix -->
        <div class="tab-pane" id="tab_matrix">
          <h4 style="margin-top: 0; margin-bottom: 20px;"><i class="fa fa-sliders text-green"></i> Role-Permissions Checkbox Mapping Matrix</h4>
          
          <div class="row">
            <div class="col-md-3">
              <!-- Roles List Sidebar on Left -->
              <ul class="nav nav-pills nav-stacked" style="background-color: #f9f9f9; border-radius: 4px; padding: 5px;">
                <?php $first = true; foreach($roles as $r): ?>
                  <li class="<?php echo $first ? 'active' : ''; ?>">
                    <a href="#role_pane_<?php echo $r->id; ?>" data-toggle="pill">
                      <i class="fa fa-circle-o text-muted"></i> <strong><?php echo htmlspecialchars($r->name); ?></strong>
                      <?php if($r->id <= 4): ?>
                        <span class="pull-right text-muted" style="font-size: 10px;"><i class="fa fa-lock"></i> System</span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php $first = false; endforeach; ?>
              </ul>
            </div>
            
            <div class="col-md-9" style="border-left: 1px solid #eee;">
              <div class="tab-content" style="padding-left: 15px;">
                <?php $first = true; foreach($roles as $r): ?>
                  <div class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="role_pane_<?php echo $r->id; ?>">
                    <h3 style="margin-top: 0; font-size: 18px;">
                      Permissions for: <strong class="text-blue"><?php echo htmlspecialchars($r->name); ?></strong>
                    </h3>
                    <p class="text-muted" style="font-size: 12px; margin-bottom: 20px;"><?php echo htmlspecialchars($r->description); ?></p>
                    
                    <?php echo form_open('roles/save-permissions'); ?>
                      <input type="hidden" name="role_id" value="<?php echo $r->id; ?>">
                      
                      <div class="row">
                        <?php foreach($permissions as $p): ?>
                          <div class="col-md-6" style="margin-bottom: 12px;">
                            <div class="checkbox" style="margin: 0;">
                              <label style="font-weight: 600;">
                                <input type="checkbox" name="permissions[]" value="<?php echo $p->id; ?>" 
                                       <?php echo in_array($p->id, $role_permissions[$r->id]) ? 'checked' : ''; ?>>
                                <span class="text-blue" style="font-family: monospace;"><?php echo $p->name; ?></span>
                              </label>
                              <div style="font-size: 11px; color: #777; padding-left: 20px;">
                                <?php echo htmlspecialchars($p->description); ?>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <hr style="margin: 15px 0;">
                      <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-save"></i> Save Permissions Mapping for <?php echo htmlspecialchars($r->name); ?>
                      </button>
                    <?php echo form_close(); ?>
                  </div>
                <?php $first = false; endforeach; ?>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tab 3: Permissions Glossary Reference -->
        <div class="tab-pane" id="tab_permissions">
          <h4 style="margin-top: 0; margin-bottom: 15px;"><i class="fa fa-book text-purple"></i> Available Permissions Glossary</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 80px;">ID</th>
                  <th style="width: 200px;">Permission Name</th>
                  <th>System Action / Description</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($permissions as $p): ?>
                  <tr>
                    <td><strong><?php echo $p->id; ?></strong></td>
                    <td><code class="text-blue" style="font-weight: bold;"><?php echo $p->name; ?></code></td>
                    <td><?php echo htmlspecialchars($p->description); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </div>
    
  </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('roles/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add Custom Role</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Role Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Finance Auditor" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe what users under this role are authorized to do"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Role</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editRoleForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Custom Role Details</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Role Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_role_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="edit_role_description" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Details</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-role-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var desc = $(this).data('description');
      
      $('#editRoleForm').attr('action', '<?php echo site_url("roles/edit/"); ?>' + id);
      $('#edit_role_name').val(name);
      $('#edit_role_description').val(desc);
    });
  });
</script>
