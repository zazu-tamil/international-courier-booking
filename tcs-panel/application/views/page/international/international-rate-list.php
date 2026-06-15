<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     International Rate List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> International</a></li> 
    <li class="active">International Rate List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 

    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">International Shippment GST & FSC Charges Info </h3>
        </div>
       <div class="box-body">
         <form action="<?php echo site_url('international-rate-list'); ?>" method="post" id="frmset"> 
            <div class="row">
                <div class="col-md-3">
                    <label>Last Updated Date: </label>
                    <input type="text" name="last_updated_date" id="last_updated_date"  class="form-control" value="<?php echo date('d-m-Y h:i a', strtotime($intl_setting_info['last_updated_date']));?>" readonly="true" />    
                </div>
                <div class="col-md-3">
                    <label>GST % </label>
                    <input type="text" name="gst" id="gst"  class="form-control" value="<?php echo $intl_setting_info['gst']?>" />    
                </div>
                <div class="col-md-3">
                    <label>FSC %  </label>
                    <input type="text" name="fsc" id="fsc"  class="form-control" value="<?php echo $intl_setting_info['fsc']?>" />    
                </div>
                <div class="col-md-3">
                    <label>
                        <input type="hidden" name="mode" value="setting" />
                        <input type="hidden" name="intl_setting_id" id="intl_setting_id" value="<?php echo $intl_setting_info['intl_setting_id']?>" />
                    </label>
                    <button type="submit" name="btn_update" value="" class="btn btn-success"><i class="fa fa-save"></i> Update</button>    
                </div>
            </div>
         </form>
       </div>
    </div>   
  <!-- Default box -->
  <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        </div>
       <div class="box-body"> 
            <form action="<?php echo site_url('international-rate-list'); ?>" method="post" id="frm">
            <div class="row">
                <div class="col-sm-4 col-md-3"> 
                    <label for="srch_pkg_type">Country</label>
                    <?php echo form_dropdown('srch_country_id',array('' => 'Select Country') + $country_opt,set_value('srch_country_id',$srch_country_id) ,' id="srch_country_id" class="form-control" required');?>
                </div>
                <div class="col-sm-2 col-md-2"> 
                    <label for="srch_pkg_type">Package Type</label>
                    <?php echo form_dropdown('srch_pkg_type',array('' => 'Select Type') + $package_type_opt,set_value('srch_pkg_type',$srch_pkg_type) ,' id="srch_pkg_type" class="form-control" required');?>
                </div> 
                <div class="col-sm-2 col-md-3"> 
                    <label for="srch_service_provider_id">Service Type</label>
                    <?php echo form_dropdown('srch_service_provider_id',array('' => 'Select') + $service_provider_opt  ,set_value('srch_service_provider_id',$srch_service_provider_id) ,' id="srch_service_provider_id" class="form-control" required ');?>
                </div> 
                <div class="col-sm-2 col-md-2"> 
                    <label for="srch_pkg_weight">Package Weight</label>
                    <?php echo form_dropdown('srch_pkg_weight',array('' => 'All Weight') + $package_weight_opt  ,set_value('srch_pkg_weight',$srch_pkg_weight) ,' id="srch_pkg_weight" class="form-control"');?>
                </div> 
                <div class="col-sm-2 col-md-2"> 
                <br />
                    <button class="btn btn-info" type="submit">Show</button>
                </div>
            </div>
            </form> 
       </div> 
    </div> 
  <div class="box box-success">
    <div class="box-header with-border">
        <b>International rate</b>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive">
       <form action="<?php echo site_url('international-rate-list'); ?>" method="post" id="frm1">
       <table class="table table-hover table-bordered table-striped">
        <thead> 
            <tr>
                <th>#</th>
                <th>Country</th> 
                <th>Service Type</th> 
                <th>Package Type</th> 
                <th>Package Weight</th> 
                <th width="15%">Base Rate</th> 
            </tr> 
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td>
                        <?php echo ($j+1)?> 
                    </td>   
                    <td>
                         <input type="hidden" name="country_id[]" value="<?php echo $ls['country_id']?>" />
                        <?php echo $ls['country_name']?>
                    </td> 
                    <td>
                        <input type="hidden" name="international_service_provider_id[]" value="<?php echo $ls['international_service_provider_id']?>" />
                         <?php echo $ls['international_service_provider']?> 
                          
                    </td>   
                    <td><input type="hidden" name="package_type_id[]" value="<?php echo $ls['package_type_id']?>" /><?php echo $ls['package_type_name']?></td>  
                    <td><input type="hidden" name="package_weight_id[]" value="<?php echo $ls['package_weight_id']?>" /><?php echo ($ls['package_weight_range']);?></td>   
                    <td><input type="number" step="any" name="rate[]" value="<?php echo $ls['rate']?>" class="form-control" size="3" /></td>   
                                                         
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right" > <button class="btn btn-success" name="btn_save" value="Save" type="submit"><i class="fa fa-save"></i> Save</button></td>
                </tr>
            </tfoot>
      </table>
      </form>  
         
        
        
    </div>
    <!-- /.box-body --> 
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
