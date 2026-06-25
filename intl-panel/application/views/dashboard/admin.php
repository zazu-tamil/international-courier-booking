<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-aqua">
      <span class="info-box-icon"><i class="fa fa-shopping-bag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today's Bookings</span>
        <span class="info-box-number"><?php echo $today_bookings; ?></span>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-green">
      <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today's Revenue</span>
        <span class="info-box-number">₹<?php echo number_format($today_revenue, 2); ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <?php if ($this->session->userdata('role_id') == 3): ?>
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">My Total Bookings</span>
          <span class="info-box-number"><?php echo $total_bookings; ?></span>
        </div>
      </div>
    <?php elseif ($this->session->userdata('role_id') == 2): ?>
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Branch Total Bookings</span>
          <span class="info-box-number"><?php echo $total_bookings; ?></span>
        </div>
      </div>
    <?php else: ?>
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-id-badge"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Pending KYC</span>
          <span class="info-box-number"><?php echo $pending_kyc; ?></span>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-red">
      <span class="info-box-icon"><i class="fa fa-shield"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Verification</span>
        <span class="info-box-number"><?php echo $pending_verification; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-purple"><i class="fa fa-truck"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Ready For Dispatch</span>
        <span class="info-box-number"><?php echo $ready_for_dispatch; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-navy"><i class="fa fa-plane"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">In Transit</span>
        <span class="info-box-number"><?php echo $in_transit; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-teal"><i class="fa fa-check-square-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Delivered</span>
        <span class="info-box-number"><?php echo $delivered; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-orange"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><?php echo ($this->session->userdata('role_id') == 3) ? 'My Active Customers' : (($this->session->userdata('role_id') == 2) ? 'Branch Active Customers' : 'Active Customers'); ?></span>
        <span class="info-box-number"><?php echo $active_customers; ?></span>
      </div>
    </div>
  </div>
</div>

<!-- Detailed Verifications status widget -->
<div class="row">
  <div class="col-md-12">
    <div class="box box-solid bg-blue-active">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-check-circle-o"></i> Verification Workflows Checklist Stats</h3>
      </div>
      <div class="box-body text-center">
        <div class="col-xs-4" style="border-right: 1px solid rgba(255,255,255,0.2)">
          <h2 style="font-weight: 700; margin: 0;"><?php echo $pending_declaration; ?></h2>
          <p style="margin: 0; font-size: 13px;">Pending Declarations</p>
        </div>
        <div class="col-xs-4" style="border-right: 1px solid rgba(255,255,255,0.2)">
          <h2 style="font-weight: 700; margin: 0;"><?php echo $pending_terms; ?></h2>
          <p style="margin: 0; font-size: 13px;">Pending T&C Agreements</p>
        </div>
        <div class="col-xs-4">
          <h2 style="font-weight: 700; margin: 0;"><?php echo $pending_otp; ?></h2>
          <p style="margin: 0; font-size: 13px;">Pending OTPs</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row">
  <div class="col-md-8">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-line-chart"></i> Monthly Shipping Revenue (Last 6 Months)</h3>
      </div>
      <div class="box-body">
        <div class="chart">
          <canvas id="revenueChart" style="height: 250px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Courier Partner Shares</h3>
      </div>
      <div class="box-body">
        <canvas id="courierChart" style="height: 250px;"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Country-wise Shipment Volumes (Top 5)</h3>
      </div>
      <div class="box-body">
        <div class="chart">
          <canvas id="countryChart" style="height: 230px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Success rate card -->
    <div class="box box-solid bg-green-gradient">
      <div class="box-header">
        <i class="fa fa-calendar"></i>
        <h3 class="box-title">System Performance</h3>
      </div>
      <div class="box-body text-center">
        <input type="text" class="knob" value="<?php echo $delivery_success_rate; ?>" data-width="120" data-height="120" data-fgColor="#ffffff" data-easyPieChart style="border: none; font-size: 30px; font-weight: bold; background: none; color: #fff; text-align: center;" readonly>
        <div class="knob-label" style="margin-top: 15px; font-size: 16px; font-weight: 600;">Delivery Success Rate</div>
      </div>
      <div class="box-footer text-black">
        <div class="row">
          <div class="col-xs-6 text-center" style="border-right: 1px solid #f4f4f4">
            <h4 style="margin: 0; font-weight: bold;"><?php echo $delivered; ?></h4>
            <p style="margin: 0; font-size: 12px; color: #777;">Delivered Shipments</p>
          </div>
          <div class="col-xs-6 text-center">
            <h4 style="margin: 0; font-weight: bold;"><?php echo $pending_pickups; ?></h4>
            <p style="margin: 0; font-size: 12px; color: #777;">Pending Pickups</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart Scripts Initialization -->
<script>
  $(function () {
    // 1. Revenue Chart
    var revCanvas = $('#revenueChart').get(0).getContext('2d');
    var revChart = new Chart(revCanvas);
    var revData = {
      labels  : <?php echo $chart_months; ?>,
      datasets: [
        {
          label               : 'Revenue',
          fillColor           : 'rgba(60,141,188,0.2)',
          strokeColor         : 'rgba(60,141,188,1)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : <?php echo $chart_revenues; ?>
        }
      ]
    };
    var revOptions = {
      showScale               : true,
      scaleShowGridLines      : true,
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      scaleGridLineWidth      : 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines  : true,
      bezierCurve             : true,
      bezierCurveTension      : 0.3,
      pointDot                : true,
      pointDotRadius          : 4,
      pointDotStrokeWidth     : 1,
      pointHitDetectionRadius : 20,
      datasetStroke           : true,
      datasetStrokeWidth      : 2,
      datasetFill             : true,
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      maintainAspectRatio     : false,
      responsive              : true
    };
    revChart.Line(revData, revOptions);

    // 2. Courier Partner Chart
    var courierCanvas = $('#courierChart').get(0).getContext('2d');
    var courierChart = new Chart(courierCanvas);
    var rawLabels = <?php echo $chart_couriers; ?>;
    var rawCounts = <?php echo $chart_courier_counts; ?>;
    
    var colors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];
    var courierData = [];
    for (var i = 0; i < rawLabels.length; i++) {
      courierData.push({
        value: rawCounts[i],
        color: colors[i % colors.length],
        highlight: colors[i % colors.length],
        label: rawLabels[i]
      });
    }
    
    var pieOptions = {
      segmentShowStroke    : true,
      segmentStrokeColor   : '#fff',
      segmentStrokeWidth   : 2,
      percentageInnerCutout: 50, // This is 0 for Pie charts
      animationSteps       : 100,
      animationEasing      : 'easeOutBounce',
      animateRotate        : true,
      animateScale         : false,
      responsive           : true,
      maintainAspectRatio  : false,
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    };
    courierChart.Doughnut(courierData, pieOptions);

    // 3. Country Chart
    var countryCanvas = $('#countryChart').get(0).getContext('2d');
    var countryChart = new Chart(countryCanvas);
    var countryData = {
      labels  : <?php echo $chart_countries; ?>,
      datasets: [
        {
          label               : 'Shipments',
          fillColor           : 'rgba(0,166,90,0.8)',
          strokeColor         : 'rgba(0,166,90,1)',
          pointColor          : '#00a65a',
          pointStrokeColor    : '#rgba(0,166,90,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(0,166,90,1)',
          data                : <?php echo $chart_country_counts; ?>
        }
      ]
    };
    var barOptions = {
      scaleBeginAtZero        : true,
      scaleShowGridLines      : true,
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      scaleGridLineWidth      : 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines  : true,
      barShowStroke           : true,
      barStrokeWidth          : 2,
      barValueSpacing         : 5,
      barDatasetSpacing       : 1,
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      responsive              : true,
      maintainAspectRatio     : false
    };
    countryChart.Bar(countryData, barOptions);
  });
</script>
