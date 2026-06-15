<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1> Change Password </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Change Password</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
  <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Profile Name : <strong><?php echo $user_name; ?></strong> </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" >
              <div class="box-body">
                <div class="form-group">
                  <label for="user_name" class="col-sm-4 control-label">Login Name</label> 
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo  $login_name ;?>" readonly="true">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo  $user_id ;?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="old_password" class="col-sm-4 control-label">Old Password</label> 
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="new_password" class="col-sm-4 control-label">New Password</label> 
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="retype_password" class="col-sm-4 control-label">Re-Type Password</label> 
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="retype_password" name="retype_password" placeholder="Re-type Password">
                  </div>
                </div> 
              </div>
              <!-- /.box-body -->
              <div class="box-footer"> 
                <button type="button" class="btn btn-info pull-right btn_chg_pwd" name="btn_chg_pwd">Change Password</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
