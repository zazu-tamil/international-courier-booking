<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Master Domestic Rate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Mst Domestic Rate</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
  <?php foreach($record_list as $type => $info1) {  ?>
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title"><?php echo $type; ?></h3>  
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
                  foreach($info1 as $j=> $info){
                ?>
               
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($info['rate_as_on'])) ?></td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_region']?></span>
                        <?php if($info['flg_region'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_state']?></span>
                        <?php if($info['flg_state'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center">  <span class="text-hide"><?php echo $info['flg_city']?></span>
                        <?php if($info['flg_city'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_metro']?></span>
                        <?php if($info['flg_metro'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"><?php echo $info['min_weight']?></td>
                    <td class="text-right"><?php echo $info['min_charges']?></td>
                    <td class="text-center"><?php echo $info['addt_weight']?></td>
                    <td class="text-right"><?php echo $info['addt_charges']?></td> 
                    <td class="text-right"><?php echo $info['addt_weight_1']?></td> 
                    <td class="text-right"><?php echo $info['addt_charges_1']?></td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" data="<?php echo $info['c_type']?>" data-prod="<?php echo $info['p_type']?>" value="<?php echo $info['domestic_rate_id']?>" class="edit_record btn btn-primary" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                                                       
                </tr>
                <?php } ?>                                 
            </tbody>
        </table>  
        
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="form-group col-sm-6">
            <label>Total Records : <?php echo count($record_list[$type]);?></label>
        </div>
        <div class="form-group col-sm-6">
            <?php echo $pagination; ?>
        </div>
    </div>
    <!-- /.box-footer-->
  </div>  
   <?php } ?> 
  
   <?php /* ?>
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title">Air - Domestic Rate</h3>  
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
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($info['rate_as_on'])) ?></td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_region']?></span>
                        <?php if($info['flg_region'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_state']?></span>
                        <?php if($info['flg_state'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center">  <span class="text-hide"><?php echo $info['flg_city']?></span>
                        <?php if($info['flg_city'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_metro']?></span>
                        <?php if($info['flg_metro'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"><?php echo $info['min_weight']?></td>
                    <td class="text-right"><?php echo $info['min_charges']?></td>
                    <td class="text-center"><?php echo $info['addt_weight']?></td>
                    <td class="text-right"><?php echo $info['addt_charges']?></td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" data="Air" value="<?php echo $info['domestic_rate_id']?>" class="edit_record btn btn-primary" title="Edit"><i class="fa fa-edit"></i></button>
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
        <div class="form-group col-sm-6">
            <label>Total Records : <?php echo count($record_list['Air']);?></label>
        </div>
        <div class="form-group col-sm-6">
            <?php echo $pagination; ?>
        </div>
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
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($info['rate_as_on'])) ?></td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_region']?></span>
                        <?php if($info['flg_region'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_state']?></span>
                        <?php if($info['flg_state'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center">  <span class="text-hide"><?php echo $info['flg_city']?></span>
                        <?php if($info['flg_city'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>
                    <td class="text-center"> <span class="text-hide"><?php echo $info['flg_metro']?></span>
                        <?php if($info['flg_metro'] == 1) { ?><i class="fa fa-check text-success"></i><?php } else {?><i class="fa fa-remove text-danger"></i><?php } ?>
                    </td>                    
                    <td class="text-center"><?php echo $info['min_weight']?></td>
                    <td class="text-right"><?php echo $info['min_charges']?></td>
                    <td class="text-center"><?php echo $info['addt_weight']?></td>
                    <td class="text-right"><?php echo $info['addt_charges']?></td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" data="Surface" value="<?php echo $info['domestic_rate_id']?>" class="edit_record btn btn-primary" title="Edit"><i class="fa fa-edit"></i></button>
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
        <div class="form-group col-sm-6">
            <label>Total Records : <?php echo count($record_list['Surface']);?></label>
        </div>
        <div class="form-group col-sm-6">
            <?php echo $pagination; ?>
        </div>
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->
   <?php */ ?>
   
   
   <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmedit">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel">Edit : Domestic Rate</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="domestic_rate_id" id="domestic_rate_id" />
                        <input type="hidden" name="c_type" id="c_type" />
                        <input type="hidden" name="p_type" id="p_type" />
                    </div>
                    <div class="modal-body"> 
                        <div class="row"> 
                             <div class="form-group col-md-4">
                                <label>Source & Destination Within the Same  </label>                                             
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
                                    <input type="number" id="min_weight" name="min_weight" step="any" class="form-control col-2" style="text-align: right;">
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
                            <div class="form-group col-md-6">
                                <label>Additional Every Weight </label> 
                                <div class="input-group">
                                    <input type="number" id="addt_weight_1" name="addt_weight_1" class="form-control" step="any" style="text-align: right;">
                                    <div class="input-group-btn"><button class="btn btn-primary">in Kg</button></div>
                                </div>                                             
                            </div>
                            <div class="form-group col-md-6">
                                <label>Additional Wgt Charges</label> 
                                <div class="input-group"> 
                                    <input class="form-control" type="number" name="addt_charges_1" id="addt_charges_1" step="any" style="text-align: right;">
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
                        <input type="submit" name="Save" value="Update"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div> 
   
    
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
