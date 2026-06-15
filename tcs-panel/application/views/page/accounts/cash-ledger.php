<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Cash Ledger</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Petty Cash</a></li> 
    <li class="active">Cash Ledger</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
    <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('cash-ledger')?>" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>" required="true">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_to_date" name="srch_to_date" value="<?php echo set_value('srch_to_date',$srch_to_date);?>" required="true">
                    </div>
                    <!-- /.input group -->                                             
                 </div>
                 <div class="form-group col-md-4">
                    <label>Employee</label>
                    <?php echo form_dropdown('srch_agent_id',array('' => 'Select') + $emp_opt ,set_value('srch_agent_id') ,' id="srch_agent_id" class="form-control" required="true"');?>                                             
                 </div> 
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show'"><i class="fa fa-search"></i> Show</button>
                </div>
             </div>  
            </form>
         </div> 
     </div>  
     <?php
	 if(!empty($srch_agent_id)) {
    ?>   
  <div class="box box-info">
    <div class="box-header with-border">
        <h4>Cash Ledger : [ <?php echo date('d-m-Y', strtotime($srch_from_date));?> to <?php echo date('d-m-Y', strtotime($srch_to_date));?> ] <strong class="pull-right label label-success"> <?php echo $emp_opt[$srch_agent_id]; ?></strong></h4>     
    </div>
    <div class="box-body table-responsive"> 
       <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>  
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Cash Inward</th> 
                            <th>Cash Outward</th>  
                        </tr>
                    </thead>
                    <tbody> 
                       <?php
                            $tot_in_amount = $tot_out_amount = 0;
                           foreach($record_list as $j=> $info){ 
                               $tot_in_amount += $info['cash_in']; 
                               $tot_out_amount += $info['cash_out']; 
                        ?>                               
                        <tr>
                             <td><?php echo ($this->uri->segment(2, 0) + ($j+1));?></td>
                            <td><?php echo date('d-m-Y', strtotime($info['t_date']));?></td>
                            <td><?php echo $info['particular']?></td> 
                            <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($info['cash_in'],2)?></td>  
                            <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($info['cash_out'],2)?></td>  
                        <?php } ?>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($tot_in_amount,2)?></th>
                            <th class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($tot_out_amount,2)?></th> 
                        </tr>
                        <?php //} ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-center">Closing Balance : <i class="fa fa-rupee"></i> <?php echo number_format(($tot_in_amount - $tot_out_amount) ,2)?></th> 
                        </tr>
                    </tfoot>
                </table>  
        
                 
        
        
    </div>
    <!-- /.box-body -->
     
  </div>
  <!-- /.box -->
  <?php
	}
?>

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
