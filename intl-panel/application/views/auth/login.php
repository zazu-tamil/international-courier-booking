<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login | <?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      color: #fff;
    }
    .login-container {
      display: flex;
      max-width: 900px;
      width: 100%;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0,0,0,0.3);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .login-branding {
      flex: 1;
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=600') center/cover;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .login-form-box {
      flex: 1;
      background: #ffffff;
      color: #333;
      padding: 40px;
    }
    .btn-primary {
      background: #3c8dbc;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background: #2a688d;
      transform: translateY(-2px);
    }
    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      height: auto;
      border: 1px solid #ddd;
    }
    .form-control:focus {
      border-color: #3c8dbc;
      box-shadow: none;
    }
    .public-tracking-card {
      margin-top: 15px;
      background: #f7f9fa;
      border-radius: 8px;
      padding: 15px;
      border: 1px solid #e1e8ed;
    }
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
        margin: 20px;
      }
      .login-branding {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-branding">
    <div>
      <h2 style="font-weight: 700; margin: 0; color: #fff;"><i class="fa fa-paper-plane-o"></i> CourierSyndicate</h2>
      <p style="color: rgba(255,255,255,0.7); font-size: 14px;">Enterprise Logistics Solutions</p>
    </div>
    <div style="margin-top: 40px;">
      <h3 style="font-weight: 600; color: #fff;">Enterprise-Grade International Cargo ERP</h3>
      <p style="color: rgba(255,255,255,0.8); line-height: 1.6;">Secure, multi-branch, franchise-ready shipment scheduling system incorporating mandatory customer digital signatures and OTP verification logs.</p>
    </div>
    <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin: 0;">&copy; 2026 CourierSyndicate. All rights reserved.</p>
  </div>

  <div class="login-form-box">
    <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 20px;">Account Sign In</h3>
    
    <?php if($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?php echo form_open('login'); ?>
      <div class="form-group">
        <label style="font-weight: 600;">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo set_value('email'); ?>">
      </div>
      <div class="form-group">
        <label style="font-weight: 600;">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    <?php echo form_close(); ?>

    <!-- <p class="text-center" style="margin-top: 20px;">
      Don't have a Customer account? <a href="<?php echo site_url('register'); ?>" style="font-weight: 600; color: #3c8dbc;">Register here</a>
    </p> -->

    <!-- Public tracking widget -->
    <div class="public-tracking-card">
      <h5 style="margin-top: 0; font-weight: 700;"><i class="fa fa-search"></i> Quick Shipment Tracking</h5>
      <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Track your package without signing in. Enter AWB or mobile number:</p>
      <form action="<?php echo site_url('tracking'); ?>" method="GET">
        <div class="input-group">
          <input type="text" name="awb" class="form-control input-sm" placeholder="e.g. CSYN-INT-2026-000001" required>
          <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" type="submit" style="padding: 7px 15px;"><i class="fa fa-arrow-right"></i></button>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
