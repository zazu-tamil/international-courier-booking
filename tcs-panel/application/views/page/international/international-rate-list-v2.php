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
    <?php
	//print_r($package_weight_opt);
    ?>    
    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">International Rate Import From Excel File Info </h3>
        </div>
       <div class="box-body">
           <form method="post" action="<?php echo site_url('international-rate-list-v2'); ?>" enctype="multipart/form-data">
            <div class="col-md-4 form-group"> 
                    <label>Upload Excel File</label>
                    <input type="file" name="xls_file" id="xls_file" class="form-control" required="true" /> 
            </div>
            <div class="col-md-4 form-group"> 
                    <label>Click To Import</label>  
                    <button type="submit" name="btn_import" value="Import_xls" class="btn btn-success btn-sm form-control">Import International Rate</button>
             </div>
            </form> 
       </div>
    </div>   
  <div class="box box-warning collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title text-red"><i class="fa fa-recycle"></i> Record Delete Option</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-plus"></i>
            </button> 
          </div>
        </div>
       <div class="box-body text-red"> 
            <form action="<?php echo site_url('international-rate-list-v2'); ?>" method="post" id="frmdelete">
            <div class="row">
                <div class="col-sm-2 col-md-3"> 
                    <label for="del_service_provider">Service Provider</label>
                    <?php echo form_dropdown('del_service_provider', array('' => 'Select ') + $service_provider_opt  ,set_value('del_service_provider') ,' id="del_service_provider" class="form-control" required="true" ');?>
                </div> 
                <div class="col-sm-4 col-md-3"> 
                    <label for="srch_pkg_type">Country</label>
                    <?php echo form_dropdown('del_country',array('' => 'All') + $country_opt,set_value('del_country') ,' id="del_country" class="form-control" ');?>
                </div>  
                <div class="col-sm-2 col-md-2"> 
                    <label for="srch_pkg_weight">Package Weight</label>
                    <?php echo form_dropdown('del_pkg_weight',array('' => 'All') + $package_weight_opt  ,set_value('del_pkg_weight') ,' id="del_pkg_weight" class="form-control"');?>
                </div> 
                <div class="col-sm-2 col-md-2"> 
                    <br />
                    <button type="button" name="btn_delete" id="btn_delete" class="btn btn btn-danger btn-sm" title="Delete" value="Delete" ><i class="fa fa-remove"></i> Delete Record</button>
                </div>
            </div>
            </form> 
       </div> 
  </div>  
  <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        </div>
       <div class="box-body"> 
            <form action="<?php echo site_url('international-rate-list-v2'); ?>" method="post" id="frm">
            <div class="row">
                <div class="col-sm-2 col-md-3"> 
                    <label for="srch_service_provider">Service Provider</label>
                    <?php echo form_dropdown('srch_service_provider',array('' => 'All') + $service_provider_opt  ,set_value('srch_service_provider',$srch_service_provider) ,' id="srch_service_provider" class="form-control" ');?>
                </div> 
                <div class="col-sm-4 col-md-3"> 
                    <label for="srch_pkg_type">Country</label>
                    <?php echo form_dropdown('srch_country',array('' => 'All') + $country_opt,set_value('srch_country',$srch_country) ,' id="srch_country" class="form-control" ');?>
                </div>  
                <div class="col-sm-2 col-md-2"> 
                    <label for="srch_pkg_weight">Package Weight</label>
                    <?php echo form_dropdown('srch_pkg_weight',array('' => 'All') + $package_weight_opt  ,set_value('srch_pkg_weight',$srch_pkg_weight) ,' id="srch_pkg_weight" class="form-control"');?>
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
        <h4>International Rate List</h4>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive">
       <form action="<?php echo site_url('international-rate-list-v2'); ?>" method="post" id="frm1">
       <table class="table table-hover table-bordered table-striped">
        <thead> 
            <tr>
                <th>#</th>
                <th>Country</th> 
                <th>Service Provider</th>  
                <th>Package Weight</th> 
                <th width="15%">Base Rate</th> 
                <th colspan="2">SP Rate</th> 
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
                         <input type="hidden" name="country[]" value="<?php echo $ls['country']?>" />
                        <?php echo $ls['country']?>
                    </td> 
                    <td>
                        <input type="hidden" name="service_provider[]" value="<?php echo $ls['service_provider']?>" />
                         <?php echo $ls['service_provider']?> <br />
                         <i class="text-sm label label-success">
                            FSC:<?php echo $ls['fsc']?> , GST:<?php echo $ls['gst']?> , Addt : <?php echo $ls['addt_sc']?>
                         </i>
                          
                    </td>   
                    <td><input type="hidden" name="weight[]" value="<?php echo $ls['weight']?>" /><?php echo ($ls['weight']);?></td>   
                    <td><input type="number" step="any" name="base_rate[]" value="<?php echo $ls['base_rate']?>" class="form-control" size="3" /></td>   
                    <td class="text-right"><?php echo number_format($ls['sp_rate'],2); ?></td>                                   
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right" > 
                        <input type="hidden" name="srch_service_provider" value="<?php echo $srch_service_provider; ?>" />
                        <input type="hidden" name="srch_country" value="<?php echo $srch_country; ?>" />
                        <input type="hidden" name="srch_pkg_weight" value="<?php echo $srch_pkg_weight; ?>" />
                    <button class="btn btn-success" name="btn_save" value="Save" type="submit"><i class="fa fa-save"></i> Update</button>
                    </td>
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
