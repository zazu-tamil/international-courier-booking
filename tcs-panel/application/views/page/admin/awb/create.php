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
<div class="box">
    <div class="box-header with-border"> </div>
    <div class="box-body"> 
         <form action="<?= base_url('awb/store') ?>" method="post" enctype="multipart/form-data">
  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#awb" role="tab" data-toggle="tab">AWB Info</a></li>
    <li><a href="#shipper" role="tab" data-toggle="tab">Shipper</a></li>
    <li><a href="#receiver" role="tab" data-toggle="tab">Receiver</a></li>
    <li><a href="#boxes" role="tab" data-toggle="tab">Dimensions</a></li>
    <li><a href="#invoice" role="tab" data-toggle="tab">Invoice</a></li>
    <li><a href="#items" role="tab" data-toggle="tab">Invoice Items</a></li>
  </ul>

  <div class="tab-content p-3">

    <!-- AWB Info -->
    <div class="tab-pane active" id="awb">
      <div class="mt-5"> 
      <?php $this->load->view('page/admin/awb/tabs/awb_info'); ?>
      </div>
    </div>

    <!-- Shipper -->
    <div class="tab-pane" id="shipper">
      <?php //$this->load->view('awb/tabs/shipper'); ?>
    </div>

    <!-- Receiver -->
    <div class="tab-pane" id="receiver">
      <?php //$this->load->view('awb/tabs/receiver'); ?>
    </div>

    <!-- Box Dimensions -->
    <div class="tab-pane" id="boxes">
      <?php //$this->load->view('awb/tabs/box_dimensions'); ?>
    </div>

    <!-- Shipment Invoice -->
    <div class="tab-pane" id="invoice">
      <?php //$this->load->view('awb/tabs/invoice'); ?>
    </div>

    <!-- Shipment Invoice Items -->
    <div class="tab-pane" id="items">
      <?php //$this->load->view('awb/tabs/invoice_items'); ?>
    </div>

  </div>

  <div class="text-right mt-3">
    <button type="submit" class="btn btn-primary">Create AWB</button>
  </div>
</form>


    </div>
</div>
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>  