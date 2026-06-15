<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Customer Domestic Rate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li><a href="<?php echo site_url('customer-list')?>"><i class="fa fa-user"></i> Customer List</a></li> 
    <li class="active">Customer Domestic Rate</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box --> 
  <div class="box box-info"> 
        <div class="box-body">  
            <div class="row"> 
                <div class="col-sm-12 col-md-12 text-right"> 
                  <button type="button" class="btn btn-info btn_back" onclick="window.history.back();">Back To Customer List</button>
                </div>
            </div> 
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
                    <th colspan="4" class="text-center">Source & Destination</th>                                     
                    <th rowspan="2" class="text-center">Init.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Init.Charges</th>
                    <th rowspan="2" class="text-center">Addt.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Addt.Charges</th> 
                    <th rowspan="2" class="text-center">Action</th>  
                </tr>
                 <tr> 
                    <th>Within Region</th>
                    <th>Within State</th>
                    <th>Within City</th>
                    <th>Metro</th>
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
                        <input type="checkbox" name="flg_region" class="flg_region flat-red" value="1" <?php echo ($info['flg_region'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center"> 
                        <input type="checkbox" name="flg_state" class="flg_state flat-red" value="1" <?php echo ($info['flg_state'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_city" class="flg_city flat-red" value="1" <?php echo ($info['flg_city'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_metro" class="flg_metro flat-red" value="1" <?php echo ($info['flg_metro'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center"><?php echo form_input('min_weight', $info['min_weight'],'size="3" class="form-control min_weight text-right"');?></td>
                    <td class="text-right"><?php echo form_input('min_charges',$info['min_charges'],'size="3" class="form-control min_charges text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_weight',$info['addt_weight'],'size="3" class="form-control addt_weight text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_charges',$info['addt_charges'],'size="3" class="form-control addt_charges text-right"')?></td>
                    <td class="text-center"> 
                        <button data="Air" value="<?php echo $info['customer_domestic_rate_id']?>" customer_data="<?php echo $info['customer_id']?>" class="update_record btn btn-success" title="Update"><i class="fa fa-check"></i></button>
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
                    <th colspan="4" class="text-center">Source & Destination</th>                                     
                    <th rowspan="2" class="text-center">Init.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Init.Charges</th>
                    <th rowspan="2" class="text-center">Addt.Wgt <br /><i class="badge">in Kgs</i></th>
                    <th rowspan="2" class="text-center">Addt.Charges</th> 
                    <th rowspan="2" class="text-center">Action</th>  
                </tr>
                 <tr> 
                    <th>Within Region</th>
                    <th>Within State</th>
                    <th>Within City</th>
                    <th>Metro</th>
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
                        <input type="checkbox" name="flg_region" class="flg_region flat-red" value="1" <?php echo ($info['flg_region'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center"> 
                        <input type="checkbox" name="flg_state" class="flg_state flat-red" value="1" <?php echo ($info['flg_state'] == 1 ? 'Checked': '');?> />
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_city" class="flg_city flat-red" value="1" <?php echo ($info['flg_city'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center">  
                        <input type="checkbox" name="flg_metro" class="flg_metro flat-red" value="1" <?php echo ($info['flg_metro'] == 1 ? 'Checked': '');?> />                        
                    </td>
                    <td class="text-center"><?php echo form_input('min_weight', $info['min_weight'],'size="3" class="form-control min_weight text-right"');?></td>
                    <td class="text-right"><?php echo form_input('min_charges',$info['min_charges'],'size="3" class="form-control min_charges text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_weight',$info['addt_weight'],'size="3" class="form-control addt_weight text-right"')?></td>
                    <td class="text-right"><?php echo form_input('addt_charges',$info['addt_charges'],'size="3" class="form-control addt_charges text-right"')?></td>
                    <td class="text-center">
                        <button data="Surface" value="<?php echo $info['customer_domestic_rate_id']?>" customer_data="<?php echo $info['customer_id']?>" class="update_record btn btn-success" title="Update"><i class="fa fa-check"></i></button>
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
