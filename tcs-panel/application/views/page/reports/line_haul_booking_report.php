<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Line Haul Booking Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Line Haul Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_to_date" name="srch_to_date" value="<?php echo set_value('srch_to_date',$srch_to_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div>
                 <div class="form-group col-md-4">
                    <label>Co-Loader</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_co_loader_id',array('' => 'All') + $co_loader_opt  ,set_value('srch_co_loader_id','') ,' id="srch_co_loader_id" class="form-control"');?> 
                            
                      </div>                                   
                 </div>  
                  
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show Reports</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
         <?php if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Line Haul Booking Report <span></span></h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>S.No</th>
                        <th>Courier Service</th>  
                        <th>Date</th>  
                        <th>Origin</th>  
                        <th>Destination</th>  
                        <th>Co-Loader</th> 
                        <th>Mode</th>  
                        <th class="text-right">No of Box</th> 
                        <th class="text-right">V.Weight</th>  
                        <th class="text-right">Weight</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = $tot['v_weight'] = 0;
                        foreach($record_list as $j => $info) { 
                          $tot['no_of_pieces'] += $info['no_of_box'];  
                          $tot['weight'] += $info['total_weight'];  
                          $tot['v_weight'] += $info['volumetric_weight'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['co_loader_name']?></td> 
                            <td><?php echo $info['booking_date']?></td>   
                            <td><?php echo $info['origin']?></td>   
                            <td><?php echo $info['destination']?></td>   
                            <td><?php echo $info['service']?></td> 
                            <td><?php echo $info['v_mode']?></td> 
                            <td class="text-right"><?php echo $info['no_of_box']?></td>   
                            <td class="text-right"><?php echo $info['volumetric_weight']?></td>   
                            <td class="text-right"><?php echo $info['total_weight']?></td> 
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['v_weight'],3)?></th> 
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th> 
                                <th></th>
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-6 text-left">
                    <label>Total Records : <?php echo count($record_list);?></label>
                </div>
                <div class="form-group col-sm-6 text-right">
                    <?php //echo $pagination; ?>
                    <a class="btn btn-success dl-xls" data="Line Haul Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
