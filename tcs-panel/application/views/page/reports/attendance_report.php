<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Attendance Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Attendance Report</li>
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
                    <label>User</label>
                      <div class="form-group">
                        <?php echo form_dropdown('srch_user_id',array('' => 'All') + $user_opt  ,set_value('srch_user_id',$srch_user_id) ,' id="srch_user_id" class="form-control"');?> 
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
              <h3 class="box-title text-white">Attendance Report - <span><?php echo date('d-m-Y', strtotime($srch_from_date))?> to <?php echo date('d-m-Y', strtotime($srch_to_date))?></span></h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th> 
                        <th>Date</th>
                        <th>In Time</th>  
                        <th>Out Time</th>  
                        <th>Work Hours</th>  
                        <th>Status</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php foreach($record_list as $user => $det) {  ?>
                        <tr>
                            <th colspan="6" class="text-fuchsia">User: <?php echo $user; ?></th>
                        </tr>
                        <?php foreach($det as $j => $info) {  ?>
                        <tr>
                            <td><?php echo ($j+1)?></td> 
                            <td><?php echo date('d-m-Y', strtotime($info['in_time']))?></td>   
                            <td><?php echo date('h:i a', strtotime($info['in_time']))?></td>   
                            <td><?php echo ($info['status'] == 'Out' ? date('h:i a', strtotime($info['out_time'])) : '-')?></td>   
                            <td><?php echo ($info['status'] == 'Out' ? date('h:i', strtotime($info['tot_hr'])): '-')?></td>  
                            <td><?php echo $info['status']?></td>
                        </tr>
                        <?php } ?>
                        <?php } ?> 
                    </tbody> 
                </table>   
                  <?php } ?>
            </div> 
            </div> 
            <?php } ?> 
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
