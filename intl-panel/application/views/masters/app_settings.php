<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-gears"></i> Application & Gateway Settings</h3>
      </div>
      
      <?php echo form_open_multipart('app-settings', array('id' => 'settingsForm', 'class' => 'form-horizontal')); ?>
        <div class="box-body" style="padding: 20px;">
          
          <!-- Tab Navigation -->
          <ul class="nav nav-tabs" id="settingsTabs" style="margin-bottom: 25px;">
            <li class="active"><a href="#tab-company" data-toggle="tab"><i class="fa fa-building"></i> Company Profile</a></li>
            <li><a href="#tab-smtp" data-toggle="tab"><i class="fa fa-envelope"></i> SMTP Email Server</a></li>
            <li><a href="#tab-sms" data-toggle="tab"><i class="fa fa-commenting"></i> SMS Gateway API</a></li>
            <li><a href="#tab-whatsapp" data-toggle="tab"><i class="fa fa-whatsapp"></i> WhatsApp API</a></li>
          </ul>

          <div class="tab-content">
            
            <!-- TAB 1: COMPANY PROFILE -->
            <div class="tab-pane active" id="tab-company">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-building-o"></i> Company Profile Details</h4>
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Company Name <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars(isset($settings['company_name']) ? $settings['company_name'] : ''); ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Company Logo</label>
                <div class="col-sm-8">
                  <?php if(isset($settings['company_logo']) && !empty($settings['company_logo'])): ?>
                    <div style="margin-bottom: 10px;">
                      <img src="<?php echo base_url('assets/img/'.$settings['company_logo']); ?>" alt="Current Logo" style="max-height: 80px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" name="company_logo" class="form-control" accept="image/*">
                  <small class="help-block">Upload a logo (JPG, PNG, GIF). Max size: 2MB.</small>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Address</label>
                <div class="col-sm-8">
                  <textarea name="company_address" class="form-control" rows="3"><?php echo htmlspecialchars(isset($settings['company_address']) ? $settings['company_address'] : ''); ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Contact Email</label>
                <div class="col-sm-8">
                  <input type="email" name="company_email" class="form-control" value="<?php echo htmlspecialchars(isset($settings['company_email']) ? $settings['company_email'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">GSTIN / TAX ID</label>
                <div class="col-sm-8">
                  <input type="text" name="company_gst" class="form-control" value="<?php echo htmlspecialchars(isset($settings['company_gst']) ? $settings['company_gst'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Mobile Number</label>
                <div class="col-sm-8">
                  <input type="text" name="company_mobile" class="form-control" value="<?php echo htmlspecialchars(isset($settings['company_mobile']) ? $settings['company_mobile'] : ''); ?>">
                </div>
              </div>
            </div>

            <!-- TAB 2: SMTP EMAIL SERVER -->
            <div class="tab-pane" id="tab-smtp">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-envelope-o"></i> SMTP Mail Server Configuration</h4>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="smtp_enabled" value="1" <?php echo (isset($settings['smtp_enabled']) && $settings['smtp_enabled'] == '1') ? 'checked' : ''; ?>>
                      <strong>Enable SMTP Email Service</strong> (Check to send notifications via email)
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Host</label>
                <div class="col-sm-8">
                  <input type="text" name="smtp_host" class="form-control" placeholder="e.g. smtp.gmail.com" value="<?php echo htmlspecialchars(isset($settings['smtp_host']) ? $settings['smtp_host'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Port</label>
                <div class="col-sm-8">
                  <input type="text" name="smtp_port" class="form-control" placeholder="e.g. 587" value="<?php echo htmlspecialchars(isset($settings['smtp_port']) ? $settings['smtp_port'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Username</label>
                <div class="col-sm-8">
                  <input type="text" name="smtp_user" class="form-control" value="<?php echo htmlspecialchars(isset($settings['smtp_user']) ? $settings['smtp_user'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Password</label>
                <div class="col-sm-8">
                  <input type="password" name="smtp_pass" class="form-control" value="<?php echo htmlspecialchars(isset($settings['smtp_pass']) ? $settings['smtp_pass'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Encryption</label>
                <div class="col-sm-8">
                  <select name="smtp_crypto" class="form-control">
                    <option value="" <?php echo (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == '') ? 'selected' : ''; ?>>None</option>
                    <option value="ssl" <?php echo (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                    <option value="tls" <?php echo (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- TAB 3: SMS GATEWAY API -->
            <div class="tab-pane" id="tab-sms">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-comment-o"></i> SMS Gateway Configuration</h4>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="sms_enabled" value="1" <?php echo (isset($settings['sms_enabled']) && $settings['sms_enabled'] == '1') ? 'checked' : ''; ?>>
                      <strong>Enable SMS Gateway Notifications</strong> (Check to send SMS notifications)
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Gateway API URL</label>
                <div class="col-sm-8">
                  <input type="url" name="sms_api_url" class="form-control" placeholder="https://api.smsgateway.com/send" value="<?php echo htmlspecialchars(isset($settings['sms_api_url']) ? $settings['sms_api_url'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">API Key / Token</label>
                <div class="col-sm-8">
                  <input type="text" name="sms_api_key" class="form-control" value="<?php echo htmlspecialchars(isset($settings['sms_api_key']) ? $settings['sms_api_key'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Sender ID / Header</label>
                <div class="col-sm-8">
                  <input type="text" name="sms_sender_id" class="form-control" placeholder="e.g. CSINTL" value="<?php echo htmlspecialchars(isset($settings['sms_sender_id']) ? $settings['sms_sender_id'] : ''); ?>">
                </div>
              </div>
            </div>

            <!-- TAB 4: WHATSAPP API SETTINGS -->
            <div class="tab-pane" id="tab-whatsapp">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-whatsapp"></i> WhatsApp Gateway API Configuration</h4>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="whatsapp_enabled" value="1" <?php echo (isset($settings['whatsapp_enabled']) && $settings['whatsapp_enabled'] == '1') ? 'checked' : ''; ?>>
                      <strong>Enable WhatsApp API Notifications</strong> (Check to send WhatsApp updates)
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">WhatsApp API URL</label>
                <div class="col-sm-8">
                  <input type="url" name="whatsapp_api_url" class="form-control" placeholder="https://api.whatsappgateway.com/send" value="<?php echo htmlspecialchars(isset($settings['whatsapp_api_url']) ? $settings['whatsapp_api_url'] : ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Access Token / Key</label>
                <div class="col-sm-8">
                  <input type="text" name="whatsapp_api_key" class="form-control" value="<?php echo htmlspecialchars(isset($settings['whatsapp_api_key']) ? $settings['whatsapp_api_key'] : ''); ?>">
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="box-footer" style="padding: 20px 20px 20px 0;">
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-check-circle"></i> Save Settings</button>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
