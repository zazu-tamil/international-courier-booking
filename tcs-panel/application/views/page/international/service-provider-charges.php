<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1> International Service Provider Charges </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> International</a></li> 
    <li class="active">Service Provider Charges</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <div class="box box-info">
    <div class="box-header with-border">
         <h4>Service Provider Charges</h4>
    </div>
    <div class="box-body table-responsive">
       
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Service Provider</th> 
                <th>GST</th> 
                <th>FSC</th> 
                <th>Addt.SC</th> 
                <th class="text-center">Charges</th>  
                <th class="text-center">Margin</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1);?></td> 
                    <td><?php echo $ls['service_provider']?><br /><i class="text-sm label label-info"><?php echo $ls['last_updated_date']?></i></td>    
                    <td><?php echo $ls['gst']?></td>    
                    <td><?php echo $ls['fsc']?></td>    
                    <td><?php echo $ls['addt_sc']?></td>    
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#chrg_modal" value="<?php echo $ls['intl_setting_id']?>" data="<?php echo $ls['service_provider']?>"   class="edit_record btn btn-primary btn-xs" title="Charges"><i class="fa fa-plus"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <?php if($ls['intl_setting_id'] != 0) {?>
                        <button data-toggle="modal" data-target="#margin_modal" value="<?php echo $ls['intl_setting_id']?>" data="<?php echo $ls['service_provider']?>"  class="margin_record btn btn-success btn-xs" title="Margin"><i class="fa fa-briefcase"></i></button>
                        <?php } ?>
                    </td>                                          
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table>
        
        <div class="modal fade" id="chrg_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmadd">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="scrollmodalLabel">International Service Provider Charges</h3>
                        <input type="hidden" name="mode" id="mode" value="Add" />
                        <input type="hidden" name="intl_setting_id" id="intl_setting_id" />
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-12"> 
                            <label>Service Provider</label>
                            <input class="form-control" type="text" name="service_provider" id="service_provider" readonly="true" value="">                                             
                         </div>
                         <div class="form-group col-md-4"> 
                            <label>FSC [%]</label>
                            <input class="form-control" type="number" step="any" name="fsc" id="fsc" value="">                                             
                         </div>
                         <div class="form-group col-md-4"> 
                            <label>GST [%]</label>
                            <input class="form-control" type="number" step="any" name="gst" id="gst" value="">                                             
                         </div>
                         <div class="form-group col-md-4"> 
                            <label>Addt Surcharges</label>
                            <input class="form-control" type="number" step="any" name="addt_sc" id="addt_sc" value="">                                             
                         </div> 
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                        <input type="submit" name="Save" value="Save"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div> 
                
                <div class="modal fade" id="margin_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmedit">
                            <div class="modal-header"> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Franchise Margin</h3>
                                <input type="hidden" name="mode" id="mode" value="Add Margin" />
                                <input type="hidden" name="intl_setting_id" id="intl_setting_id" />
                            </div>
                            <div class="modal-body"> 
                                <div class="form-group col-md-12"> 
                                    <label>Service Provider</label>
                                    <input class="form-control" type="text" name="service_provider" id="service_provider" readonly="true" value="" disabled="true">                                             
                                 </div>
                                 <div class="form-group col-md-12 table-responsive">
                                 <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>Franchise Type</th>
                                        <th>Margin [%]</th>
                                    </tr>
                                    <?php foreach($franchise_type_opt as $fy_id => $fy_name) { ?>
                                        <tr>
                                            <td width="60%">
                                             <input class="form-control" type="hidden" name="franchise_type_id[<?php echo $fy_id; ?>]" id="franchise_type_id_<?php echo $fy_id; ?>" value="<?php echo $fy_id; ?>">                                             
                                             <?php echo $fy_name; ?>  
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="number" step="any" name="margin[<?php echo $fy_id; ?>]" id="margin_<?php echo $fy_id; ?>" value="0"> 
                                            </td>
                                        </tr>
                                     <?php } ?>
                                 </table> 
                                 </div>
                                  
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                                <input type="submit" name="Save" value="Save"  class="btn btn-primary" />
                            </div> 
                            </form>
                        </div>
                    </div>
                </div>
        
        
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
