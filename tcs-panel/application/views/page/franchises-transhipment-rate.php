<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Master Transhipment Rate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Transhipment Rate</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
    <div class="box box-success">
        <div class="box-body">   
            <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
        </div>
    </div>
  <!-- Default box -->
  <?php foreach($record_list as $type => $info) {  ?>
  <?php foreach($info as $weight => $info1) {  ?>
  <div class="box box-info">
    <div class="box-header with-border ">
       <h3 class="box-title"><?php echo $type . ' [ ' . $weight . ' ]' ?></h3>  
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
                    <th rowspan="2" class="text-center">Conn.Charges</th> 
                    <th rowspan="2" class="text-center">Deliv.Charges</th> 
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
                    <td class="text-center"><?php echo ($j + 1 );?></td> 
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
                    <td class="text-right"><?php echo $info['min_charges']?></td> 
                    <td class="text-right"><?php echo $info['delivery_charges']?></td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" data="<?php echo $info['c_type']?>"   value="<?php echo $info['transhipment_rate_id']?>" class="edit_record btn btn-primary" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                                                       
                </tr>
                <?php } ?>                                 
            </tbody>
        </table>  
        
    </div>
    <!-- /.box-body -->
     
  </div>  
   <?php } ?> 
   <?php } ?> 
  
   
   
   <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmedit">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel">Add : Transhipment Rate</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" /> 
                        <input type="hidden" name="c_type" id="c_type" value="Transhipment Weight" /> 
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
                            <div class="form-group col-md-3">
                                <label>Weight From</label>
                                <input type="number" name="from_weight" id="from_weight" step="any" value="" class="form-control" /> 
                            </div>
                            <div class="form-group col-md-3">
                                <label>Weight To</label> 
                                <input type="number" name="to_weight" id="to_weight" step="any" value="" class="form-control" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Connection Charges</label> 
                                <div class="input-group"> 
                                    <input class="form-control" type="number" name="min_charges" id="min_charges" step="any" style="text-align: right;">
                                    <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                                </div>                                             
                            </div>
                            <div class="form-group col-md-3">
                                <label>Delivery Charges</label> 
                                <div class="input-group"> 
                                    <input class="form-control" type="number" name="delivery_charges" id="delivery_charges" step="any" style="text-align: right;">
                                    <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                                </div>                                             
                            </div>
                             <div class="form-group col-md-3">
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
   <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmedit">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel">Edit : Transhipment Rate</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="transhipment_rate_id" id="transhipment_rate_id" />
                        <input type="hidden" name="c_type" id="c_type" /> 
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
                            <div class="form-group col-md-3">
                                <label>Weight From</label>
                                <input type="number" name="from_weight" id="from_weight" step="any" value="" class="form-control" /> 
                            </div>
                            <div class="form-group col-md-3">
                                <label>Weight To</label> 
                                <input type="number" name="to_weight" id="to_weight" step="any" value="" class="form-control" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Connection Charges</label> 
                                <div class="input-group"> 
                                    <input class="form-control" type="number" name="min_charges" id="min_charges" step="any" style="text-align: right;">
                                    <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                                </div>                                             
                            </div>
                            <div class="form-group col-md-3">
                                <label>Delivery Charges</label> 
                                <div class="input-group"> 
                                    <input class="form-control" type="number" name="delivery_charges" id="delivery_charges" step="any" style="text-align: right;">
                                    <div class="input-group-btn"><button class="btn btn-success"><i class="fa fa-rupee"></i></button></div>
                                </div>                                             
                            </div>
                             <div class="form-group col-md-3">
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
