<?php  include_once(VIEWPATH . '/inc/header.php'); 
/*echo "<pre>";
print_r($opening);
print_r($transaction);
echo "</pre>";*/
?>
 <section class="content-header">
  <h1>Franchise Wallet Statement</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Franchise Wallet Statement</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('wallet-statement') ?>" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-4"> 
                    <label>Month</label>
                    <input type="month" class="form-control" id="srch_month" name="srch_month" value="<?php echo set_value('srch_month',$srch_month);?>">                                           
                 </div> 
                 
                 <div class="form-group col-md-4">
                    <label>Franchise</label> 
                    <?php echo form_dropdown('srch_franchise_id',array('' => 'Select') + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control"');?> 
                 </div> 
                <div class="form-group col-md-4">
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
              <h3 class="box-title text-white">Franchise Wallet Statement [ <?php echo $opening['franchise_type_name'] . ' - ' . $opening['contact_person'] . ' : ' . $opening['branch_code']; ?> ] [ <?php echo date('M - Y', strtotime($srch_month.'-01')); ?> ]<span></span></h3> 
            </div>
            <div class="box-body table-responsive">  
                <?php  if(!empty($opening)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-green-gradient"> 
                        <th width="10%">Date</th> 
                        <th>Particulars</th> 
                        <th width="20%" class="text-right">In</th> 
                        <th width="20%" class="text-right">Out</th> 
                    </tr> 
                    </thead>
                    <tbody>
                     <tr class="bg-light-blue"> 
                        <td><?php echo date('d-m-Y', strtotime($srch_month.'-01')); ?></td>
                        <td>Opening Balance </td>
                        <td colspan="2" class="text-center"><?php echo number_format($opening['balance'],2); ?></td>  
                     </tr>    
                     <?php 
                        $op_b = $opening['balance'];
                        $tr_in = 0;
                        $tr_out = 0;
                        foreach($transaction as $k=> $info){
                        $tr_in += $info['in_amt'];
                        $tr_out += $info['out_amt'];
                     ?> 
                     <tr> 
                        <td><?php echo date('d-m-Y', strtotime($info['t_date'])); ?></td>
                        <td><?php echo $info['particular']; ?></td>
                        <td class="text-right"><?php echo number_format($info['in_amt'],2); ?></td> 
                        <td class="text-right"><?php echo number_format($info['out_amt'],2); ?></td> 
                     </tr> 
                     <?php } 
                     $cl_bal = ($op_b + $tr_in) - $tr_out;
                     ?>
                     <tr>
                        <td colspan="2" class="text-right">Total</td>
                        <td class="text-right"><?php echo number_format($tr_in,2); ?></td>
                        <td class="text-right"><?php echo number_format($tr_out,2); ?></td>
                     </tr>
                     <tr class="bg-maroon"> 
                        <td><?php echo date('t-m-Y', strtotime($srch_month.'-01')); ?></td>
                        <td>Closing Balance </td> 
                        <td colspan="2" class="text-center"><?php echo number_format($cl_bal,2); ?></td>  
                     </tr> 
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
           
            </div>  
            
            <?php } ?>  
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
