<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1> International Rate Calculator </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> International</a></li> 
    <li class="active">International Rate Calculator</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
    <div class="row">
         <div class="form-group col-md-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Rate Calculator</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                     <div class="form-group col-md-12">
                        <label>Destination</label>
                        <?php echo form_dropdown('srch_country_id',array('' => 'Select Country') + $country_opt,set_value('srch_country_id') ,' id="srch_country_id" class="form-control" required');?>
                     </div> 
                     <div class="form-group col-md-12">
                        <label>Package Type</label>
                        <?php echo form_dropdown('srch_pkg_type',array('' => 'Package Type') + $package_type_opt,set_value('srch_pkg_type') ,' id="srch_pkg_type" class="form-control" required');?>
                     </div> 
                    </div> 
                    <div class="row">
                     <div class="form-group col-md-12">
                        <label>Weight <i class="text-sm">In Kgs</i></label>
                        <?php echo form_dropdown('srch_pkg_weight',array('' => 'Select Weight') + $package_weight_opt   ,set_value('srch_pkg_weight') ,' id="srch_pkg_weight" class="form-control" required');?>
                     </div> 
                    </div> 
                     
                    <div class="row">
                     <div class="form-group col-md-12 text-center">
                        <button type="button" class="btn btn-info btn-flat btn_calc" name="btn_calc" value="Calc"><i class="fa fa-calculator" ></i> Show</button>
                     </div> 
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 text-center rate_show">
                        
                             
                        
                        </div>
                    </div> 
                </div>
            </div> 
         </div>
         <div class="form-group col-md-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Volumetric Weight Dimension Calculator </h3>
                </div>
                <div class="box-body">  
                <i class="text-red">Length, Width & Height in <strong>Cms</strong></i>
                    <div class="row">
                         <div class="form-group col-xs-3">
                            <label>Length</label>
                            <input type="number" step="any" class="form-control calc" name="length_1" id="length_1" placeholder="Length">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Width</label>
                            <input type="number" step="any" class="form-control calc"  name="width_1" id="width_1" placeholder="Width">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Height</label>
                            <input type="number" step="any" class="form-control calc"  name="height_1" id="height_1" placeholder="Height">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>V.Weight</label>
                            <input type="number" step="any" class="form-control calc" name="weight_1" id="weight_1" placeholder="Weight" readonly="true">
                         </div> 
                    </div> 
                    <div class="row">
                         <div class="form-group col-xs-3">
                            <label>Length</label>
                            <input type="number" step="any" class="form-control calc" name="length_2" id="length_2" placeholder="Length">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Width</label>
                            <input type="number" step="any" class="form-control calc"  name="width_2" id="width_2" placeholder="Width">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Height</label>
                            <input type="number" step="any" class="form-control calc"  name="height_2" id="height_2" placeholder="Height">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>V.Weight</label>
                            <input type="number" step="any" class="form-control calc" name="weight_2" id="weight_2" placeholder="Weight" readonly="true">
                         </div> 
                    </div> 
                    <div class="row">
                         <div class="form-group col-xs-3">
                            <label>Length</label>
                            <input type="number" step="any" class="form-control calc" name="length_3" id="length_3" placeholder="Length">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Width</label>
                            <input type="number" step="any" class="form-control calc"  name="width_3" id="width_3" placeholder="Width">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Height</label>
                            <input type="number" step="any" class="form-control calc"  name="height_3" id="height_3" placeholder="Height">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>V.Weight</label>
                            <input type="number" step="any" class="form-control calc" name="weight_3" id="weight_3" placeholder="Weight" readonly="true">
                         </div> 
                    </div>  
                    
                    <div class="row">
                         <div class="form-group col-xs-3">
                            <label>Length</label>
                            <input type="number" step="any" class="form-control calc" name="length_4" id="length_4" placeholder="Length">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Width</label>
                            <input type="number" step="any" class="form-control calc"  name="width_4" id="width_4" placeholder="Width">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Height</label>
                            <input type="number" step="any" class="form-control calc"  name="height_4" id="height_4" placeholder="Height">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>V.Weight</label>
                            <input type="number" step="any" class="form-control calc" name="weight_4" id="weight_4" placeholder="Weight" readonly="true">
                         </div> 
                    </div> 
                    
                    <div class="row">
                         <div class="form-group col-xs-3">
                            <label>Length</label>
                            <input type="number" step="any" class="form-control calc" name="length_5" id="length_5" placeholder="Length">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Width</label>
                            <input type="number" step="any" class="form-control calc"  name="width_5" id="width_5" placeholder="Width">
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>Height</label>
                            <input type="number" step="any" class="form-control calc"  name="height_5" id="height_5" placeholder="Height" >
                         </div> 
                         <div class="form-group col-xs-3">
                            <label>V.Weight</label>
                            <input type="number" step="any" class="form-control calc" name="weight_5" id="weight_5" placeholder="Weight" readonly="true">
                         </div> 
                    </div> 
                     
                </div>
                <div class="box-footer text-fuchsia text-center">
                    <label>Total Weight : <span id="tot_wt"></span></label>
                </div>
            </div> 
         </div>         
    </div>
   
   <?php if($this->session->userdata('cr_is_admin') == '1')  { ?>
    <div class="row">
        <div class="form-group col-md-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Rate Calculator - V2</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                     <div class="form-group col-md-12">
                        <label>Destination</label>
                        <?php echo form_dropdown('srch_country_id_v2',array('' => 'Select Country') + $country_opt1,set_value('srch_country_id_v2') ,' id="srch_country_id_v2" class="form-control" required');?>
                     </div>  
                    </div> 
                    <div class="row">
                     <div class="form-group col-md-12">
                        <label>Weight <i class="text-sm">In Kgs</i></label>
                        <?php echo form_dropdown('srch_pkg_weight_v2',array('' => 'Select Weight')   ,set_value('srch_pkg_weight_v2') ,' id="srch_pkg_weight_v2" class="form-control" required');?>
                     </div> 
                    </div>  
                    <div class="row">
                     <div class="form-group col-md-12 text-center">
                        <button type="button" class="btn btn-info btn-flat btn_calc_v2" name="btn_calc_v2" value="Calc"><i class="fa fa-calculator" ></i> Show Rate</button>
                     </div> 
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 text-center rate_show_v2">
                        
                             
                        
                        </div>
                    </div> 
                </div>
            </div> 
         </div>
    </div>
    <?php } ?>
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
