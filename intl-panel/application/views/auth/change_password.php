<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-lock"></i> Update Account Password</h3>
      </div>
      
      <?php echo form_open('change-password'); ?>
        <div class="box-body">
          <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="old_password" class="form-control" placeholder="Enter current password" required>
          </div>
          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="Minimum 6 characters" required>
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" required>
          </div>
        </div>
        
        <div class="box-footer">
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Password</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
