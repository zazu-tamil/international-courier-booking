<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Customer Register | <?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 40px 0;
      color: #fff;
    }
    .register-container {
      max-width: 800px;
      width: 100%;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.3);
      border: 1px solid rgba(255, 255, 255, 0.1);
      margin: 20px;
    }
    .register-box {
      background: #ffffff;
      color: #333;
      padding: 30px;
      border-radius: 12px;
    }
    .btn-primary {
      background: #3c8dbc;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background: #2a688d;
      transform: translateY(-2px);
    }
    .form-control {
      border-radius: 8px;
      padding: 10px 12px;
      height: auto;
      border: 1px solid #ddd;
    }
    .form-group label {
      font-weight: 600;
      color: #444;
    }
  </style>
</head>
<body>

<div class="register-container">
  <div class="text-center" style="margin-bottom: 25px;">
    <h2 style="font-weight: 700; margin: 0; color: #fff;"><i class="fa fa-paper-plane-o"></i> CourierSyndicate</h2>
    <p style="color: rgba(255,255,255,0.7); font-size: 14px;">Customer Portal Registration</p>
  </div>

  <div class="register-box">
    <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 20px; text-align: center; color: #333;">Create Customer Account</h3>
    
    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?php echo form_open('register'); ?>
      <div class="row">
        <!-- Customer Type -->
        <div class="col-md-6 form-group">
          <label>Customer Type <span class="text-danger">*</span></label>
          <select name="customer_type" id="customer_type" class="form-control" required>
            <option value="individual" <?php echo set_select('customer_type', 'individual', TRUE); ?>>Individual</option>
            <option value="business" <?php echo set_select('customer_type', 'business'); ?>>Business Entity</option>
          </select>
        </div>

        <!-- Name -->
        <div class="col-md-6 form-group">
          <label>Full Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="Enter full name" required value="<?php echo set_value('name'); ?>">
        </div>
      </div>

      <div class="row">
        <!-- Company Name (Business only) -->
        <div class="col-md-6 form-group" id="company_group" style="display: none;">
          <label>Company Name <span class="text-danger">*</span></label>
          <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter company name" value="<?php echo set_value('company_name'); ?>">
        </div>

        <!-- Mobile -->
        <div class="col-md-6 form-group">
          <label>Mobile Number <span class="text-danger">*</span></label>
          <input type="text" name="mobile" class="form-control" placeholder="Primary contact number" required value="<?php echo set_value('mobile'); ?>">
        </div>

        <!-- Email -->
        <div class="col-md-6 form-group">
          <label>Email Address <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" placeholder="Used as username login" required value="<?php echo set_value('email'); ?>">
        </div>
      </div>

      <div class="row">
        <!-- Password -->
        <div class="col-md-6 form-group">
          <label>Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
        </div>

        <!-- Confirm Password -->
        <div class="col-md-6 form-group">
          <label>Confirm Password <span class="text-danger">*</span></label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Repeat your password" required>
        </div>
      </div>

      <div class="form-group">
        <label>Street Address <span class="text-danger">*</span></label>
        <textarea name="address" class="form-control" rows="2" placeholder="Full billing/pickup street address" required><?php echo set_value('address'); ?></textarea>
      </div>

      <div class="row">
        <!-- City -->
        <div class="col-md-3 form-group">
          <label>City <span class="text-danger">*</span></label>
          <input type="text" name="city" class="form-control" required value="<?php echo set_value('city'); ?>">
        </div>

        <!-- State -->
        <div class="col-md-3 form-group">
          <label>State <span class="text-danger">*</span></label>
          <input type="text" name="state" class="form-control" required value="<?php echo set_value('state'); ?>">
        </div>

        <!-- Country -->
        <div class="col-md-3 form-group">
          <label>Country <span class="text-danger">*</span></label>
          <select name="country_id" class="form-control" required>
            <option value="">Select country</option>
            <?php foreach($countries as $c): ?>
              <option value="<?php echo $c->id; ?>" <?php echo set_select('country_id', $c->id); ?>><?php echo $c->country_name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- ZIP -->
        <div class="col-md-3 form-group">
          <label>ZIP/Postal Code <span class="text-danger">*</span></label>
          <input type="text" name="zip_code" class="form-control" required value="<?php echo set_value('zip_code'); ?>">
        </div>
      </div>

      <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary btn-block">Complete Registration</button>
      </div>
    <?php echo form_close(); ?>

    <p class="text-center" style="margin-top: 20px; color: #666; margin-bottom: 0;">
      Already registered? <a href="<?php echo site_url('login'); ?>" style="font-weight: 600; color: #3c8dbc;">Sign In here</a>
    </p>
  </div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<script>
  $(document).ready(function() {
    function toggleCompany() {
      if ($('#customer_type').val() == 'business') {
        $('#company_group').show();
        $('#company_name').prop('required', true);
      } else {
        $('#company_group').hide();
        $('#company_name').prop('required', false).val('');
      }
    }
    
    toggleCompany();
    $('#customer_type').change(toggleCompany);
  });
</script>
</body>
</html>
