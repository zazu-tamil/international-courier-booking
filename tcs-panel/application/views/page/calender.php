<?php  include_once(VIEWPATH . '/inc/header.php'); 
//print_r($leave_available);
?>
<section class="content-header">
  <h1> <i class="fa fa-calendar"></i> Calendar </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Calendar</li>
  </ol>
</section>
<section class="content">
  <div class="row"> 
    <?php
	/*
    <div class="col-md-3 hide"> 
      <form method="post" action="" id="frmadd">
      <div class="box box-info">
        <div class="box-header with-border">
          <h4 class="box-title">Calendar Menu</h4>
          <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button> 
          </div>     
        </div>
        <div class="box-body">  
              <button type="button" class="btn btn-app" data-toggle="modal" data-target="#H_add_modal" style="background-color: red; color:#FFF;">
                <i class="fa fa-umbrella"></i> Holidays
              </button>
              <a class="btn btn-app" data-toggle="modal" data-target="#E_add_modal" style="background-color: #808000; color:#FFF;">
                <i class="fa fa-calendar"></i> Events
              </a>
              <a class="btn btn-app" data-toggle="modal" data-target="#Ex_add_modal" style="background-color: #323773; color:#FFF;">
                <i class="fa fa-sticky-note"></i> Exams
              </a>
              <a class="btn btn-app" data-toggle="modal" data-target="#RMD_add_modal" style="background-color: #42C1B3; color:#FFF;">
                <i class="fa fa-bell-o"></i> Reminder
              </a>
              
             
        </div> 
      </div>    
      </form>  
    </div>
     */
?>
    <div class="col-md-10">
      <div class="box box-info">
        <div class="box-body">
          <!-- THE CALENDAR -->
          <div id="calendar"></div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
     
  </div>  
      
     
            
        
    
    
<div class="modal fade" id="RMD_add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action="" id="frmadd">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="scrollmodalLabel"><strong>Add Reminder</strong></h3> 
                <input type="hidden" name="mode" value="Add Reminder" />
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-md-6">
                    <label>Reminder Date & Time</label>
                    <input class="form-control" type="datetime-local" name="reminder_time" id="reminder_time" value="" required="true">                                             
                 </div>  
                 <div class="form-group col-md-6">
                    <label>Reminder Title</label>
                    <input class="form-control" type="text" name="title" id="title" value="" required="true">                                             
                 </div> 
              </div>  
              <div class="row"> 
                 <div class="form-group col-md-12">
                    <label>Description</label>
                    <?php echo form_textarea('description','','class="form-control" id="description"');?>
                 </div>
              </div>  
              <div class="row">  
                 <div class="form-group col-md-4">
                    <label>Recurrence Interval</label>
                    <?php echo form_dropdown('recurrence_interval',$recurrence_interval_opt,set_value('recurrence_interval'),'id="recurrence_interval" class="form-control" ');?>
                 </div> 
                 <div class="form-group col-md-4">
                    <label>Priority</label>
                    <?php echo form_dropdown('priority',$priority_opt,set_value('priority'),'id="priority" class="form-control" ');?>
                 </div>  
                 <div class="form-group col-md-4">
                    <label>Status</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="Active" checked="true" /> Active 
                        </label> 
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="status"  value="InActive"  /> InActive
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


<div class="modal fade" id="RMD_edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action="" id="frmadd">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="scrollmodalLabel"><strong>Edit Reminder</strong></h3> 
                <input type="hidden" name="mode" value="Edit Reminder" />
                <input type="hidden" name="reminder_id" id="reminder_id" value="" />
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-md-6">
                    <label>Reminder Date & Time</label>
                    <input class="form-control" type="datetime-local" name="reminder_time" id="reminder_time" value="" required="true">                                             
                 </div>  
                 <div class="form-group col-md-6">
                    <label>Reminder Title</label>
                    <input class="form-control" type="text" name="title" id="title" value="" required="true">                                             
                 </div> 
              </div>  
              <div class="row"> 
                 <div class="form-group col-md-12">
                    <label>Description</label>
                    <?php echo form_textarea('description','','class="form-control" id="description"');?>
                 </div>
              </div>  
              <div class="row">  
                 <div class="form-group col-md-4">
                    <label>Recurrence Interval</label>
                    <?php echo form_dropdown('recurrence_interval',$recurrence_interval_opt,set_value('recurrence_interval'),'id="recurrence_interval" class="form-control" ');?>
                 </div> 
                 <div class="form-group col-md-4">
                    <label>Priority</label>
                    <?php echo form_dropdown('priority',$priority_opt,set_value('priority'),'id="priority" class="form-control" ');?>
                 </div>  
                 <div class="form-group col-md-4">
                    <label>Status</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="Active" checked="true" /> Active 
                        </label> 
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="status"  value="InActive"  /> InActive
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
        
  
  <!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

 
  
  <?php  include_once(VIEWPATH . 'inc/footer.php'); ?>