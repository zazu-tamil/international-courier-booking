<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>In Scan Entry</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Booking</a></li> 
    <li class="active">In Scan Entry</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Single : In Scan Entry</h3>
            </div>
        <div class="box-body">
            <div class="row">   
                 <div class="form-group col-md-3">
                    <input type="hidden" name="mode" value="Add" />
                    <label>Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="booking_date" name="booking_date" value="<?php echo date('Y-m-d');?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3">
                    <label>Time</label>
                     <div class="input-group">
                        <input type="time" class="form-control" name="booking_time" id="booking_time" value="<?php echo date('H:i'); ?>">
     
                      </div>
                      <!-- /.input group -->                                           
                 </div> 
                 <div class="form-group col-md-4">
                    <label>AWB No</label>
                      <div class="input-group input-group-sm">
                        <input class="form-control inscanawbno " type="text" name="awbno" id="awbno" value="" placeholder="AWB No" required="true">  
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-success btn-flat" id="btn_inscan" name="btn_inscan" value="Single"><i class="fa fa-save"></i>  Save</button>
                            </span>
                           
                      </div>    
                      <span class="text-red awbno_warning"></span>                                
                 </div>  
             </div> 
         </div> 
         </div>  
        </form> 
        
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Multiple : In Scan Entry</h3>
            </div>
        <div class="box-body">
            <div class="row">   
                 <div class="form-group col-md-3">
                    <input type="hidden" name="mode" value="Add" />
                    <label>Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="booking_date" name="booking_date" value="<?php echo date('Y-m-d');?>">
                    </div>                                   
                 </div>  
                 <div class="form-group col-md-3"> 
                    <label>Time</label>
                     <div class="input-group">
                        <input type="time" class="form-control" name="booking_time" id="booking_time" value="<?php echo date('H:i'); ?>">
     
                      </div>                                           
                 </div>                      
                 <div class="form-group col-md-6">
                    <label>AWB No </label>  
                    <textarea class="form-control multiple_awbno" name="awbno" id="awbno" rows="5" placeholder="AWB No" required="true"></textarea>  
                    <br /><i class="text-maroon"> Note : Seperate by Comma for multiple AWBNO</i>  
                 </div>  
                 
             </div> 
             <div class="row">   
                 <div class="form-group col-md-6"> </div>
                 <div class="form-group col-md-6"> 
                    <button type="submit" class="btn btn-success btn-flat" name="btn_inscan" value="Multiple"><i class="fa fa-save"></i>  Save</button>  
                 </div>  
                 
             </div> 
         </div> 
         </div>  
        </form>  
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
