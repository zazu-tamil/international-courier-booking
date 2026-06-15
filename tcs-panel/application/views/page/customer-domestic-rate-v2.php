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
                <div class="col-sm-6 col-md-6 text-left"> 
                  <button type="button" class="btn btn-info btn_back" data-toggle="modal" data-target="#add_modal">Add Customer Rate</button>
                </div>
                <div class="col-sm-6 col-md-6 text-right"> 
                  <a href="<?php echo site_url('customer-list')?>" class="btn btn-info btn_back" >Back To Customer List</a>
                </div>
            </div> 
        </div> 
    </div> 
    
  <?php
  foreach($record_list as $rate_slap => $info1){
  ?>  
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title"><?php echo $rate_slap; ?> </h3>  
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
       
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
                    <th rowspan="2">Addt.Wgt <i class="badge">in Kgs</i></th>
                    <th rowspan="2">Addt.Charges</th> 
                    <th rowspan="2" class="text-center" colspan="2">Action</th>  
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
                   foreach($info1 as $j=> $info){
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
                    <td class="text-center"><?php echo form_input('addt_weight', $info['addt_weight'],'size="3" class="form-control addt_weight text-right"');?></td>
                    <td class="text-right"><?php echo form_input('addt_charges',$info['addt_charges'],'size="3" class="form-control addt_charges text-right"')?></td>
                    <td class="text-center"> 
                        <button data="<?php echo $info['c_type']?>" value="<?php echo $info['customer_domestic_rate_id']?>" customer_data="<?php echo $info['customer_id']?>" class="update_record btn btn-success" title="Update"><i class="fa fa-check"></i></button>
                    </td>                                  
                    <td class="text-center"> 
                        <button  value="<?php echo $info['customer_domestic_rate_id']?>"   class="del_record btn btn-warning" title="Delete"><i class="fa fa-recycle"></i></button>
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
  <?php }  ?>
  
   
   
    <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="" id="frmadd">
                <div class="modal-header">
                    <h3 class="modal-title" id="scrollmodalLabel">Add Customer Rate</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <input type="hidden" name="mode" value="Add Rate" />
                </div>
                <div class="modal-body">
                     <div class="row"> 
                         <div class="form-group col-md-4">  
                            <label>Type</label> 
                            <div class="radio">
                                <label>
                                    <input type="radio" name="c_type"  value="Air" checked="true" class="flat-red" /> Air 
                                </label> 
                            </div>
                          
                            <div class="radio">
                                <label>
                                     <input type="radio" name="c_type"  value="Surface" class="flat-red" /> Surface
                                </label>
                            </div> 
                         </div>
                         <div class="form-group col-md-4">
                            <label>Min Weight</label>
                            <div class="input-group">
                                <input type="number" id="from_weight" name="from_weight" step="any" class="form-control" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-primary">in Kg</button></div>
                            </div>
                         </div>
                         <div class="form-group col-md-4">
                            <label>Max Weight</label>
                            <div class="input-group">
                                <input type="number" id="to_weight" name="to_weight" step="any" class="form-control" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-primary">in Kg</button></div>
                            </div>
                         </div>
                     </div>
                    <div class="row"> 
                         <div class="form-group col-md-4">
                            <label>Source & Destination<br /> Within the Same  </label>                                             
                         </div> 
                         <div class="form-group col-md-2">
                           <label>Region</label><br />
                           <label>
                              <input type="checkbox" class="minimal" name="flg_region" id="flg_region" value="1">
                            </label>                                  
                         </div> 
                         <div class="form-group col-md-2">
                           <label>State</label><br />
                           <label>
                              <input type="checkbox" class="minimal" name="flg_state" id="flg_state" value="1">
                            </label>                                  
                         </div>  
                         <div class="form-group col-md-2">
                            <label>City</label><br />
                           <label>
                              <input type="checkbox" class="minimal" name="flg_city" id="flg_city" value="1">
                            </label>                                  
                         </div>
                         <div class="form-group col-md-2">
                            <label>Metro</label><br />
                           <label>
                              <input type="checkbox" class="minimal" name="flg_metro" id="flg_metro" value="1">
                            </label>                                  
                         </div>
                     </div>  
                     <div class="row"> 
                        <div class="form-group col-md-6">
                            <label>Initial Weight </label>   
                            <div class="input-group">
                                <input type="number" id="min_weight" name="min_weight" step="any" class="form-control col-4" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-primary">in Kg</button></div>
                            </div>                                           
                        </div>
                        <div class="form-group col-md-6">
                            <label>Initial Wgt Charges</label> 
                            <div class="input-group"> 
                                <input class="form-control" type="number" name="min_charges" id="min_charges" step="any" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                            </div>                                             
                        </div>
                     </div> 
                     <div class="row">
                        <div class="form-group col-md-6">
                            <label>Additional Every Weight </label> 
                            <div class="input-group">
                                <input type="number" id="addt_weight" name="addt_weight" class="form-control" step="any" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-primary">in Kg</button></div>
                            </div>                                             
                        </div>
                        <div class="form-group col-md-6">
                            <label>Additional Wgt Charges</label> 
                            <div class="input-group"> 
                                <input class="form-control" type="number" name="addt_charges" id="addt_charges" step="any" style="text-align: right;">
                                <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                            </div>
                        </div> 
                     </div> 
                     <div class="row">
                         <div class="form-group col-md-12">
                            <label>Status</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status"  value="Active" checked="true" class="flat-red" /> Active 
                                </label> 
                            </div>
                            <div class="radio">
                                <label>
                                     <input type="radio" name="status"  value="InActive" class="flat-red" /> InActive
                                </label>
                            </div> 
                         </div>
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
   
    
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
