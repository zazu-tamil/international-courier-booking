<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Customers Directory</h3>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Company Name</th>
              <th>Type</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Country</th>
              <th>Outstanding Balance</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($customers as $c): ?>
              <tr>
                <td><strong><?php echo $c->name; ?></strong></td>
                <td><?php echo $c->company_name ? $c->company_name : '<span class="text-muted">-</span>'; ?></td>
                <td>
                  <span class="label <?php echo ($c->customer_type == 'business') ? 'label-info' : 'label-default'; ?>">
                    <?php echo ucfirst($c->customer_type); ?>
                  </span>
                </td>
                <td><?php echo $c->email; ?></td>
                <td><?php echo $c->mobile; ?></td>
                <td><?php echo $c->country_name; ?></td>
                <td>₹<?php echo number_format($c->outstanding_balance, 2); ?></td>
                <td>
                  <span class="label <?php echo ($c->status == 'Active') ? 'label-success' : 'label-danger'; ?>">
                    <?php echo $c->status; ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-customer-btn"
                          data-id="<?php echo $c->id; ?>"
                          data-name="<?php echo htmlspecialchars($c->name); ?>"
                          data-company="<?php echo htmlspecialchars($c->company_name); ?>"
                          data-type="<?php echo $c->customer_type; ?>"
                          data-email="<?php echo htmlspecialchars($c->email); ?>"
                          data-mobile="<?php echo htmlspecialchars($c->mobile); ?>"
                          data-address="<?php echo htmlspecialchars($c->address); ?>"
                          data-city="<?php echo htmlspecialchars($c->city); ?>"
                          data-state="<?php echo htmlspecialchars($c->state); ?>"
                          data-country="<?php echo $c->country_id; ?>"
                          data-zip="<?php echo htmlspecialchars($c->zip_code); ?>"
                          data-limit="<?php echo $c->credit_limit; ?>"
                          data-days="<?php echo $c->credit_days; ?>"
                          data-status="<?php echo $c->status; ?>"
                          data-toggle="modal" data-target="#editCustomerModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <?php if($this->session->userdata('role_id') == 1): ?>
                    <a href="<?php echo site_url('customers/delete/' . $c->id); ?>" 
                       class="btn btn-danger btn-xs" 
                       onclick="return confirm('Are you sure you want to delete this customer profile? This will also disable their user login.');">
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
  </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="editCustomerForm" action="" method="POST">
        <!-- CI3 CSRF Token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Customer Profile</h4>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Customer Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Company Name</label>
              <input type="text" name="company_name" id="edit_company" class="form-control">
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Customer Type <span class="text-danger">*</span></label>
              <select name="customer_type" id="edit_type" class="form-control" required>
                <option value="individual">Individual</option>
                <option value="business">Business</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Mobile Number <span class="text-danger">*</span></label>
              <input type="text" name="mobile" id="edit_mobile" class="form-control" required>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Email Address <span class="text-danger">*</span></label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>ZIP Code <span class="text-danger">*</span></label>
              <input type="text" name="zip_code" id="edit_zip" class="form-control" required>
            </div>
          </div>
          
          <div class="form-group">
            <label>Physical Address <span class="text-danger">*</span></label>
            <textarea name="address" id="edit_address" class="form-control" rows="2" required></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-4 form-group">
              <label>City <span class="text-danger">*</span></label>
              <input type="text" name="city" id="edit_city" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
              <label>State <span class="text-danger">*</span></label>
              <input type="text" name="state" id="edit_state" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
              <label>Country <span class="text-danger">*</span></label>
              <select name="country_id" id="edit_country" class="form-control" required>
                <?php foreach($countries as $co): ?>
                  <option value="<?php echo $co->id; ?>"><?php echo $co->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4 form-group">
              <label>Credit Limit (₹) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="credit_limit" id="edit_limit" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
              <label>Credit Days <span class="text-danger">*</span></label>
              <input type="number" name="credit_days" id="edit_days" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
              <label>Status <span class="text-danger">*</span></label>
              <select name="status" id="edit_status" class="form-control" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
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
    $('.edit-customer-btn').click(function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var company = $(this).data('company');
      var type = $(this).data('type');
      var email = $(this).data('email');
      var mobile = $(this).data('mobile');
      var address = $(this).data('address');
      var city = $(this).data('city');
      var state = $(this).data('state');
      var country = $(this).data('country');
      var zip = $(this).data('zip');
      var limit = $(this).data('limit');
      var days = $(this).data('days');
      var status = $(this).data('status');
      
      $('#editCustomerForm').attr('action', '<?php echo site_url("customers/edit/"); ?>' + id);
      $('#edit_name').val(name);
      $('#edit_company').val(company);
      $('#edit_type').val(type);
      $('#edit_email').val(email);
      $('#edit_mobile').val(mobile);
      $('#edit_address').val(address);
      $('#edit_city').val(city);
      $('#edit_state').val(state);
      $('#edit_country').val(country);
      $('#edit_zip').val(zip);
      $('#edit_limit').val(limit);
      $('#edit_days').val(days);
      $('#edit_status').val(status);
    });
  });
</script>
