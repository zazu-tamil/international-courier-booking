<?php  include_once(VIEWPATH . '/inc/header.php'); 
/*
echo "<pre>";
print_r($ticket_info);
print_r($ticket_comment_info);
echo "</pre>";
*/ 
?>
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
  <?php if(!empty($ticket_info)) { ?> 
  <div class="box box-success">
    <div class="box-header with-border">
        <div class="pull-right btn bg-black-gradient"> 
        Ticket No: <?php echo str_pad($ticket_info['hd_ticket_id'],6,0,STR_PAD_LEFT);?>  
        </div> 
        <i class="fa fa-bullhorn"></i> 
        <h3 class="box-title">Ticket Details</h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Date</th>
                <td><?php echo date('d-m-Y h:i a', strtotime($ticket_info['created_datetime']));?></td>
                <th>Category</th>
                <td><?php echo $ticket_info['hd_category_name'];?></td>
            </tr>
            <tr>
                <th>Subject</th>
                <td><?php echo $ticket_info['subject'];?></td>
                <th>Doc Attached</th>
                <td>
                    <?php if(!empty($ticket_info['attach_doc'])) {?>
                    <a href="<?php echo base_url($ticket_info['attach_doc']);?>" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>Description</th>
                <td colspan="3"><?php echo $ticket_info['description'];?></td> 
            </tr>
            <tr>
                <th>Support Ticket To</th>
                <td><?php echo $ticket_info['to_franchise_name'];?> - <?php echo $ticket_info['to_franchise'];?></td>
                <th>Created By</th>
                <td><?php echo $ticket_info['frm_franchise'];?></td>
            </tr> 
            <tr>
                <th colspan="4">
                    <form method="post" action="" id="frm_share">
                        Share To <br />
                        <?php 
                            $share_to = explode(',',($ticket_info['share_to']));
                            echo form_dropdown('share_to[]',$franchise_opt ,set_value('share_to',$share_to) ,' id="share_to" class="form-control1 select2" style="width: 100%;" required="true" multiple="true"');?> 
                        <input type="hidden" name="share_hd_ticket_id" id="share_hd_ticket_id" value="<?php echo $ticket_info['hd_ticket_id'] ?>" />
                        <input type="hidden" name="tbl" id="tbl" value="ticket-share" />
                        <br />
                        <br />
                        <button type="button" class="btn btn-info pull-right btn_share"><i class="fa fa-plus-circle"></i> Update</button>
                    </form>
                </th> 
            </tr>
            <tr>
                <th>Priority</th>
                <td><em class="label label-danger"><?php echo $ticket_info['priority'];?></em></td>
                <th>Status</th>
                <td><strong class="label label-info"><?php echo $ticket_info['status'];?></strong></td>
            </tr>
        </table>
        
        
    </div>  
  </div> 
  <div class="box box-success">
    <div class="box-header with-border"> 
      <i class="fa fa-commenting"></i> 
      <h3 class="box-title">Comments</h3>
      <div class="pull-right">
        <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add  Ticket Comments </button>
       </div>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>By</th>
                <th>Comment</th>
                <th>Doc</th>
                <th>Status</th>
            </tr>
            <?php foreach($ticket_comment_info as $i => $info){ ?>
                <tr>
                    <td><?php echo ($i+1); ?></td>
                    <td><?php echo date('d-m-Y h:i a', strtotime($info['created_datetime']));?></td>
                    <td><?php echo ($info['franchise_id'] == 0 ? 'Admin' : $franchise_opt[$info['franchise_id']]); ?></td>
                    <td><?php echo $info['comment']; ?></td>
                    <td>
                        <?php if(!empty($info['attach_doc'])) {?>
                        <a href="<?php echo base_url($info['attach_doc']);?>" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                        <?php } ?>
                    </td>
                    <td><?php echo $info['status']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="box-footer text-center">
        <a href="<?php echo site_url('ticket-list'); ?>" class="btn btn-warning">Back to Ticket List</a>
    </div>
  </div>     
  


<div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action="" id="frmadd">
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="modal-title" id="scrollmodalLabel">Add Ticket Comments</h2>
                <input type="hidden" name="mode" value="Add Ticket Comments" />
                <input type="hidden" name="hd_ticket_id" id="hd_ticket_id" value="<?php echo $ticket_info['hd_ticket_id'] ?>" />
            </div>
            <div class="modal-body">
              <div class="row"> 
                 <div class="form-group col-md-12">
                    <label>Reply By</label> 
                    <?php //echo form_dropdown('franchise_id',array('' => 'Select') + $franchise_opt ,set_value('franchise_id','') ,' id="franchise_id" class="form-control" required="true"');?>                                         
                    <input class="form-control" type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata('cr_user_id'); ?>">   
                    <input class="form-control" type="hidden" name="franchise_id" id="franchise_id" value="<?php echo $this->session->userdata('cr_franchise_id'); ?>" >   
                    <input class="form-control" type="text" name="franchise" id="franchise" value="<?php echo ($this->session->userdata('cr_franchise_id') != 0 ? $franchise_opt[$this->session->userdata('cr_franchise_id')] : 'Admin'); ?>" readonly="true">   
                 </div>  
                 <div class="form-group col-md-12">
                    <label>Comment</label>  
                    <?php echo form_textarea('comment','', 'id="comment" class="form-control" required="true"') ;?>                                           
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Attachement</label>
                    <input class="form-control" type="file" name="attach_doc" id="attach_doc" />  
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Status</label>  
                    <?php if( ($this->session->userdata('cr_user_id')  == $ticket_info['user_id']) || ($this->session->userdata('cr_tracking_upd_rights') == 1)) { ?>
                        <?php echo form_dropdown('status', $status_opt  ,set_value('status' , $ticket_info['status']) ,' id="status" class="form-control" ');?>
                    <?php } else { ?>
                        <input class="form-control" type="text" name="status" id="status" value="<?php echo $ticket_info['status']; ?>"  readonly="true" />  
                    <?php } ?>
                    
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
<?php } ?>
</section> 
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?> 