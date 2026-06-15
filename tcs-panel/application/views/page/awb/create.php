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
        <ul class="nav nav-tabs">
            <li class="active"><a href="#awb" data-toggle="tab">AWB Info</a></li>
            <li><a href="#shipper" data-toggle="tab">Shipper</a></li>
            <li><a href="#receiver" data-toggle="tab">Receiver</a></li>
            <li><a href="#boxes" data-toggle="tab">Dimensions</a></li>
            <li><a href="#invoice" data-toggle="tab">Invoice</a></li>
            <li><a href="#items" data-toggle="tab">Invoice Items</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="awb"> ... </div>
          <div class="tab-pane" id="shipper"> ... </div>
          <div class="tab-pane" id="receiver"> ... </div>
          <div class="tab-pane" id="boxes"> ... </div>
          <div class="tab-pane" id="invoice"> ... </div>
          <div class="tab-pane" id="items"> ... </div>
        </div>

    </div>
</div>
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>  