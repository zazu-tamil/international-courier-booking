<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1><?php echo $title?></h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Helpdesk</a></li> 
    <li class="active"><?php echo $title?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <div class="box box-success">
    <div class="box-header with-border">
      <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> New Support Ticket </button>
        
    </div>
    <div class="box-body table-responsive no-padding"> 
     
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>  
                <th>Category</th>  
                <th>To Franchise</th>  
                <th>AWB NO</th>  
                <th>Subject</th>  
                <th>Description</th>  
                <th>Priority</th>  
                <th>Comments</th>  
                <th>Status</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo date('d-m-Y h:i a', strtotime($ls['created_datetime']));?><br /><i class="badge"><?php echo $ls['frm_franchise']?></i></td>   
                    <td><?php echo $ls['hd_category_name']?></td>   
                    <td><?php echo $ls['to_franchise_name']?><br /><?php echo $ls['to_franchise']?></td>   
                    <td><?php echo $ls['awbno']?></td>   
                    <td><?php echo $ls['subject']?></td>   
                    <td><?php echo $ls['description']?></td>   
                    <td><?php echo $ls['priority']?></td>   
                    <td class="text-center"><strong class="label label-warning"><?php echo $ls['cnt']?></strong></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <a href="<?php echo site_url('ticket/' . $ls['hd_ticket_id']); ?>" target="_blank" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></a>
                    </td>          
                    <?php if($this->session->userdata('cr_is_admin') == '1') {?>                        
                    <td class="text-center">
                        <button value="<?php echo $ls['hd_ticket_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>   
                    <?php } ?>                                    
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table>
        
                <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                            <div class="modal-header"> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title" id="scrollmodalLabel">Add <?php echo $title?></h2>
                                <input type="hidden" name="mode" value="Add" />  
                            </div>
                            <div class="modal-body">
                                 <div class="row"> 
                                     <div class="form-group col-md-12">
                                        <label>Franchise</label>
                                        <?php echo form_dropdown('to_franchise_id',array('' => 'Select') + $franchise_opt ,set_value('to_franchise_id','') ,' id="to_franchise_id" class="form-control1" style="width: 100%;" required="true"');?>                                             
                                     </div>
                                     <div class="form-group col-md-12">
                                        <label>Category</label>
                                        <?php echo form_dropdown('hd_category_id',array('' => 'Select') + $hd_category_opt ,set_value('hd_category_id','') ,' id="hd_category_id" class="form-control" required="true"');?>                                             
                                     </div>
                                     <div class="form-group col-md-12">
                                        <label>AWB No</label>
                                        <input class="form-control" type="text" name="awbno" id="awbno" value="" placeholder="AWB No" required="true">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Subject</label>
                                        <input class="form-control" type="text" name="subject" id="subject" value="" placeholder="Subject" required="true">                                             
                                     </div> 
                                    
                                     <div class="form-group col-md-12">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" id="description" required="true"></textarea>                                             
                                     </div> 
                                      <div class="form-group col-md-6">
                                        <label>Attachement</label>
                                        <input class="form-control" type="file" name="attach_doc" id="attach_doc" />                                             
                                     </div> 
                                     <div class="form-group col-md-6">
                                        <label>Priority</label>
                                        <?php echo form_dropdown('priority',$priority_opt ,set_value('priority','') ,' id="priority" class="form-control" required="true"');?>                                             
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
