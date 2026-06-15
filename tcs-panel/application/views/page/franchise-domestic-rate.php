<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Franchise Domestic Rate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Franchise Domestic Rate</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
    <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        </div>
        <div class="box-body"> 
            <form action="" method="post" id="frmsrch">
            <div class="row"> 
                <div class="col-sm-3 col-md-3"> 
                    <label for="srch_franchise_type">Franchise Type</label>
                    <?php echo form_dropdown('srch_franchise_type',array('' => 'Select Franchise Type') + $franchise_type_opt,set_value('srch_franchise_type',$srch_franchise_type) ,' id="srch_franchise_type" class="form-control"');?>
                </div>
                <div class="col-sm-3 col-md-3"> 
                    <label for="srch_state">State</label>
                    <?php echo form_dropdown('srch_state',array('' => 'Select the State') + $state_opt,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                </div>
                <div class="col-sm-3 col-md-4">
                    <label>Contact Person</label>
                    <?php echo form_dropdown('srch_franchise_id',array('' => 'Select Franchise') + $franchise_opt ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control"');?> 
                </div>
                <div class="col-sm-3 col-md-2"> 
                <br />
                    <button class="btn btn-info" type="submit">Show</button>
                </div>
            </div>
            </form> 
        </div> 
    </div>  
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title">Air - Domestic Rate  </h3>  
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive">
         <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>                                
                <tr>
                    <th rowspan="2" class="text-center">S.No</th>
                    <th rowspan="2" class="text-center">Last Updated</th>
                    <th colspan="2" class="text-center">Source & Destination</th>                                     
                    <th rowspan="2" class="text-center">Init.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Init.Charges</th>
                    <th rowspan="2" class="text-center">Addt.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Addt.Charges</th> 
                    <th rowspan="2" class="text-center">Action</th>  
                </tr>
                 <tr> 
                    <th>Within State</th>
                    <th>Within City</th>
                </tr>
            </thead>
            <tbody>
               <?php
                   foreach($record_list['Air'] as $j=> $info){
                ?>
               
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 );?></td> 
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($info['rate_as_on'])) ?></td>
                    <td class="text-center"> 
                        <input type="checkbox" name="flg_state" class="flg_state flat-red" value="1" <?php echo ($info['flg_state'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_city" class="flg_city flat-red" value="1" <?php echo ($info['flg_city'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center"><?php echo form_input('min_weight', $info['min_weight'],'size="3" class="form-control min_weight text-right"');?></td>
                    <td class="text-right"><?php echo form_input('min_charges',$info['min_charges'],'size="3" class="form-control min_charges text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_weight',$info['addt_weight'],'size="3" class="form-control addt_weight text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_charges',$info['addt_charges'],'size="3" class="form-control addt_charges text-right"')?></td>
                    <td class="text-center"> 
                        <button data="Air" value="<?php echo $info['franchise_domestic_rate_id']?>" franchise_data="<?php echo $info['franchise_id']?>" class="update_record btn btn-success" title="Update"><i class="fa fa-check"></i></button>
                    </td>                                  
                                                       
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
        </table>  
        
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
         
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->
  
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title">Surface - Domestic Rate</h3>  
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive">  
         <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>                                
                <tr>
                    <th rowspan="2" class="text-center">S.No</th>
                    <th rowspan="2" class="text-center">Last Updated</th>
                    <th colspan="2" class="text-center">Source & Destination</th>                                     
                    <th rowspan="2" class="text-center">Init.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Init.Charges</th>
                    <th rowspan="2" class="text-center">Addt.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Addt.Charges</th> 
                    <th rowspan="2" class="text-center">Action</th>  
                </tr>
                 <tr> 
                    <th>Within State</th>
                    <th>Within City</th>
                </tr>
            </thead>
            <tbody>
               <?php
                   foreach($record_list['Surface'] as $j=> $info){
                ?>
               
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 );?></td> 
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($info['rate_as_on'])) ?></td>
                    <td class="text-center"> 
                        <input type="checkbox" name="flg_state" class="flg_state flat-red" value="1" <?php echo ($info['flg_state'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_city" class="flg_city flat-red" value="1" <?php echo ($info['flg_city'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center"><?php echo form_input('min_weight', $info['min_weight'],'size="3" class="form-control min_weight text-right"');?></td>
                    <td class="text-right"><?php echo form_input('min_charges',$info['min_charges'],'size="3" class="form-control min_charges text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_weight',$info['addt_weight'],'size="3" class="form-control addt_weight text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_charges',$info['addt_charges'],'size="3" class="form-control addt_charges text-right"')?></td>
                    <td class="text-center">
                        <button data="Surface" value="<?php echo $info['franchise_domestic_rate_id']?>" franchise_data="<?php echo $info['franchise_id']?>" class="update_record btn btn-success" title="Update"><i class="fa fa-check"></i></button>
                    </td>                                  
                                                 
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
        </table>  
        
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
