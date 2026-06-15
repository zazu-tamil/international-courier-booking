<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>DRS List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Delivery</a></li> 
    <li class="active">DRS List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
<div class="box box-info no-print"> 
    <div class="box-header with-border">
      <h3 class="box-title text-white">Search Filter</h3>
    </div>
    <div class="box-body">
         <form method="post" action="<?php echo site_url('drs-list') ?>" id="frmsearch">          
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
            <div class="form-group col-md-2 text-left">
                <br />
                <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show DRS List</button>
            </div>
         </div>  
        </form>
     </div> 
 </div>  
  <div class="box box-success">
    <div class="box-header with-border">
       <h4 class="">DRS List</h4>  
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive"> 
       <table class="table table-hover table-bordered table-striped table-responsive">
        <thead> 
            <tr>
                <th>#</th> 
                <th>DRS No</th>  
                <th>Date</th>  
                <th>Delivery Branch</th>  
                <th>No of AWB Nos</th> 
                <th>Remarks</th> 
                <th>Delivery By</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr> 
        </thead>
          <tbody>
               <?php
                   if(!empty($record_list)) { 
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td>  
                    <td><?php echo $ls['drs_no']?></td>  
                    <td><?php echo date('d-m-Y h:i a', strtotime($ls['drs_date'] . $ls['drs_time'])) ?></td>  
                    <td><?php echo $ls['branch_city_code']?></td>  
                    <td class="text-center"><?php echo $ls['no_of_awb']?></td> 
                    <td><?php echo $ls['remarks']?></td> 
                    <td><?php echo $ls['delivery_by']?></td>  
                    <td class="text-center">
                        <a href="<?php echo site_url('print-drs/').$ls['drs_no']  ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i></a>
                    </td>                                      
                </tr>
                <?php
                    }
                    }
                ?>                                 
            </tbody>
      </table> 
      
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="form-group col-sm-6">
            <label>Total Records : <?php echo $total_records;?></label>
        </div>
        <div class="form-group col-sm-6">
            <?php echo $pagination; ?>
        </div>
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
