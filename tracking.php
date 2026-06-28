<?php  
include_once('dbcommon.php');

if(isset($_REQUEST['awbno'])) {

 $sql = "  
        select DISTINCT
                a.booking_id,
                a.awbno,
                a.origin_pincode,
                b.state_name as origin_state,
                d.city_name as origin_city,
                a.no_of_pieces,
                a.weight, 
                a.dest_pincode,
                b1.state_name as dest_state,
                d1.city_name as dest_city,
                a.sender_name,
                a.receiver_name, 
                a.status,
                a.status_city_code,
                a.exp_dv_date
                from crit_booking_info  as a  
                left join crit_states_info as b on b.state_code = a.origin_state_code and b.status = 'Active'
                left join crit_city_info as d on d.city_code = a.origin_city_code and d.status = 'Active' 
                left join crit_states_info as b1 on b1.state_code = a.dest_state_code and b.status = 'Active'
                left join crit_city_info as d1 on d1.city_code = a.dest_city_code and d.status = 'Active'
                where a.awbno in ( ". ($_REQUEST['awbno']) .")  
                and a.status !='Delete' 
                order by a.dest_pincode asc 
         
      ";

  $res= mysqli_query($link,$sql);
  
  $domestic_cnt =  mysqli_affected_rows($link);
  
  if($domestic_cnt > 0) {
  //echo "Affected rows: " . $domestic_cnt ;
  
  $booking_info = array();  
  while($row = mysqli_fetch_assoc($res))
  {
  	$booking_info = $row; 
  }   
  
  //print_r($booking_info);
   
 /*$sql1 = "  
        select 
        c.tracking_status,
        c.created_date
        from crit_tracking_info as c 
        where c.pickup_id = '". $_REQUEST['awbno']."'
        order by c.created_date asc 
      "; */
      
 $sql1 ="
            select 
            a.awbno ,
            a.awb_tracking_id,
            a.tracking_status,
            a.city_code,
            b.city_name  as city,
            a.status_date,
            a.status_time,
            a.remarks 
            from crit_awb_tracking_info as a 
            left join crit_city_info  as b on b.city_code = a.city_code
            where a.awbno in (". ($_REQUEST['awbno']) .")  
            and b.status = 'Active'
            group by a.awb_tracking_id
            order by a.status_date, a.status_time asc 
  ";    

 $res= mysqli_query($link,$sql1);
 
 
  $tracking_info = array();  
  $tracking_status = array();  
  while($row = mysqli_fetch_assoc($res))
  {
  	$tracking_info[] = $row;
    $tracking_status[] = $row['tracking_status'];   
  }  
  
       $sql2 = " 
            select 
            a.awbno,
            a.drs_no,
            a.drs_date,
            a.drs_time,
            a.branch_city_code,
            a.drs_status, 
            a.remarks, 
            a.pod_img, 
            a.delivered_to, 
            a.delivered_to_mobile, 
            a.delivered_date, 
            a.delivered_time  
            from crit_drs_info as a 
            where a.awbno in ( ". $_REQUEST['awbno'] .")   
            order by a.drs_date , a.drs_time asc 
        "; 
        
        $res= mysqli_query($link,$sql2);
        $delivery_info = array();
        
        while($row = mysqli_fetch_assoc($res))
        {
            $delivery_info[$row['awbno']] = $row;     
        }  
  
  /* 
  echo "<pre>";
  print_r($booking_info);  
  print_r($tracking_info);  
  echo "</pre>";  
  */
   
  
 
 } else { // International
    
      $sql = " 
        select
            a.international_consignment_id,
            CONCAT(a.prefix_awbno , a.awbno) as awbno1,
            a.awbno as awbno,
            a.booking_date,
            a.booking_time,
            a.sender_name,
            a.receiver_name,
            b.country_name as country,
            a.no_of_pieces,
            d.package_type_name as package_type,
            e.international_service_provider,
            c.package_weight_range as package_weight,
            a.sr_awbno,
            a.description_of_goods,
            a.exp_dv_date
        from crit_international_consignment_info as a 
        left join crit_country_info as b on b.country_id = a.country_id
        left join crit_package_weight_info as c on c.package_weight_id = a.package_weight_id
        left join crit_package_type_info as d on d.package_type_id = a.package_type_id
        left join crit_international_service_provider_info as e on e.international_service_provider_id = a.international_service_provider_id
        where  a.awbno  = '". $_REQUEST['awbno'] ."'
        and a.status != 'Delete'
        order by a.international_consignment_id asc 
          ";

    $res1= mysqli_query($link, $sql);
    
    $international_cnt =  mysqli_affected_rows($link);
    
    if($international_cnt > 0) {
    
    $booking_info = array();  
    $tracking_info = array();  
    while($row = mysqli_fetch_assoc($res1))
    {
        $booking_info = $row; 
    }   
    
    
     $sql1 = " 
        SELECT
		a1.tracking_status,
        a1.city_code,
        a1.status_date,
        a1.status_time,
        a1.remarks ,
        a1.pod
        from (
                (
                    select 
                    a.tracking_status,
                    a.location as city_code,
                    a.status_date,
                    a.status_time,
                    a.remarks ,
                    a.pod
                    from crit_intl_tracking_info as a 
                    left join crit_international_consignment_info as b on b.international_consignment_id = a.international_consignment_id
                    where a.tracking_status != 'Delete' and b.status != 'Delete' and b.awbno  = '". $_REQUEST['awbno'] ."'  
                    order by a.status_date, a.status_time asc 
                ) 
             ) AS a1
        ORDER BY
            a1.status_date,
            a1.status_time asc
            
";

    $res1= mysqli_query($link , $sql1); 
    
    $tracking_status = array();
    
    while($row = mysqli_fetch_assoc($res1))
    {
        $tracking_info[] = $row; 
        $tracking_status[] = $row['tracking_status'];
         
    }  
    
    
 /* echo "<pre>";
  print_r($booking_info);  
  print_r($tracking_info);  
  echo "</pre>";  */
    
    
 }
 }
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Courier Syndicate - It's Delivered</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Best Courier Service in Kerala" name="keywords">
    <meta content="We offers integrated express services network for the swift, safe and sure transportation and distribution of your consignment document, parcels and commercial goods." name="description">

    <!-- Favicon -->
    <link href="img/logo-icon.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/timeline-cr.css" rel="stylesheet">
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-3PJ5BCNRH0');
    </script>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center text-white">
                    <!--<small><a href="tel:+917736893993" target="_blank"><i class="fa fa-mobile mr-2"></i><strong>+91 773 689 3993</strong></a></small>
                    <small class="px-3">|</small>-->
                    <small><a href="mailto:support@couriersyndicate.co.in" target="_blank" class="text-white"><i class="fa fa-envelope mr-2"></i>support@couriersyndicate.co.in</a></small>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-white pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-lg-5">
            <a href="index.html" class="navbar-brand ml-lg-3" style="border: 0px solid red; width: 70%;">
                <img src="img/logo.png" class="img-fluid img-responsive" alt="courier syndicate" width="40%" />
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                 <div class="navbar-nav m-auto py-0">
                    <a href="index.html" class="nav-item nav-link ">Home</a>
                    <a href="index.html#about" class="nav-item nav-link">About</a>
                    <a href="index.html#service" class="nav-item nav-link ">Service</a> 
                    <a href="tracking.php" class="nav-item nav-link active">Tracking</a> 
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Help Desk</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <!--<a href="#testimonial" class="dropdown-item">Testimonial</a> -->
                            <a href="index.html#pickup" class="nav-link dropdown-item">Pickup Request</a>
                            <a href="index.html#pinsearch" class="nav-link dropdown-item">Pincode Search</a>
                        </div>
                    </div> 
                    <a href="index.html#contact" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-5">
        <div class="container text-center py-5">
            <h1 class="text-white display-3">Courier Tracking</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Tracking</p>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="container-fluid pb-5">
        <div class="container">
            <div class="text-center pb-2">
                <h1 class="text-primary text-uppercase font-weight-bold">Shipment Tracking</h1><br />
                <form method="post" action="tracking.php">
                <div class="row">
                     
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                        <div class="input-group"> 
                            <input type="number" name="awbno" id="awbno" value="<?php if(isset($_REQUEST['awbno'])) echo $_REQUEST['awbno']; ?>" class="form-control border-dark" style="padding: 30px;" placeholder="Eg:41000001">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-3" name="btn_search" value="Tracking" type="submit"><i class="fa fa-search"></i> Track</button>
                            </div> 
                        </div>
                        </div>
                   </div> 
               </div> 
               </form>
            </div>
        </div>
    </div>

     
    <!-- Pricing Plan End -->
    <?php if($domestic_cnt > 0 and !empty($tracking_info) ){ $awbno = $_REQUEST['awbno']?> 
    <div class="container">
        <h3>TRACKING INFO - AWBNO : <?php if(isset($_REQUEST['awbno'])) echo $_REQUEST['awbno'] . " [ " . $booking_info['status'] . " ] "; ?></h3>
       <div class="row"> 
          <div class="col-md-12 col-lg-12">
             <div id="tracking-pre"></div>
             <div id="tracking">
                <div class="text-center tracking-status-intransit" >
                   <p class="tracking-status text-tight">
                        <strong>Origin</strong> <br />
                        <?php echo "Pincode: " .$booking_info['origin_pincode'];?><br />
                        <?php echo $booking_info['origin_state'] . ' - ' . $booking_info['origin_city'];?> <br />
                        <?php echo "Sender: " .$booking_info['sender_name'];?>
                   </p>
                </div>
                <div class="tracking-list">
                   <?php foreach($tracking_info as $i => $info) {?> 
                    <div class="tracking-item" style="border-left: 4px dotted blue;">
                      <div class="tracking-icon status-outfordelivery">
                          <i class="fas fa-cube"></i> 
                      </div>
                      <div class="tracking-date"><?php echo date('M d, Y', strtotime($info['status_date'])) ;?><span><?php echo date('h:i A', strtotime($info['status_time'])) ;?></span></div>
                      <div class="tracking-content">
                        <strong><?php echo $info['tracking_status'];?></strong> <span><?php echo $info['city'];?></span>
                           <br />
                           <?php echo $info['remarks'];?>
                           <?php 
                            if( isset($delivery_info[$awbno]) and $info['tracking_status'] == $delivery_info[$awbno]['drs_status']) { ?>
                                 <br />
                                 <a href="/tracking/<?php echo $delivery_info[$awbno]['pod_img'];?>" target="_blank"><img src="/tracking/<?php echo $delivery_info[$awbno]['pod_img'];?>" width="20%" class="img-thumbnail" /></a>
                                 <br />
                                 <?php echo $delivery_info[$awbno]['delivered_to'];?> - <?php echo $delivery_info[$awbno]['delivered_to_mobile'];?>
                           <?php  } ?>
                      </div>
                    </div>
                   <?php } ?> 
                   <?php if(count($tracking_info) == 1) {?>
                    <div class="tracking-item">
                      <div class="tracking-icon status-intransit">
                         <i class="fas fa-cube fa-2x"></i> 
                      </div>
                      <div class="tracking-date"> <span> </span></div>
                      <div class="tracking-content">In-Transit</div>
                    </div>
                   <?php } ?>  
                   <?php if((! in_array('Out for Delivery', $tracking_status)) || (! in_array('Delivered', $tracking_status))) {?>
                    <div class="tracking-item">
                      <div class="tracking-icon status-intransit">
                          <i class="fas fa-cube fa-2x"></i> 
                      </div>
                      <div class="tracking-date"> <span> </span></div>
                      <div class="tracking-content">Out for Delivery</div>
                    </div>
                   <?php } ?> 
                   <?php if((! in_array('Delivered', $tracking_status))) {?>
                   <div class="tracking-item">
                      <div class="tracking-icon status-intransit">
                          <i class="fas fa-cube fa-2x"></i> 
                      </div>
                      <div class="tracking-date"> <span> </span></div>
                      <div class="tracking-content">Delivered</div>
                    </div>
                     <?php } ?>
                    
                </div>
                <div class="text-center tracking-status-intransit" >
                   <p class="tracking-status text-tight">
                        <strong>Destination</strong> <br />
                        <?php echo "Pincode: " . $booking_info['dest_pincode'];?><br />
                        <!--<br />
                        <?php echo $booking_info['dest_state'] . ' - ' . $booking_info['dest_city'];?> -->
                        <?php echo "Receiver: " .$booking_info['receiver_name'];?><br />
                        <div style="font-size: 14px;" class="text-white"><?php if(!empty($booking_info['exp_dv_date'])) echo "Expected Delivery Date: " .date('M d, Y', strtotime($booking_info['exp_dv_date']));?></div>
                   </p>
                </div>
             </div>
          </div>
       </div>
    </div>
<?php } elseif($international_cnt > 0) {  $awbno = $_REQUEST['awbno'] ?>
        <div class="container">
        <h3>INTERNATIONAL SHIPMENT TRACKING INFO - AWBNO : <?php if(isset($_REQUEST['awbno'])) echo $_REQUEST['awbno']  ; ?></h3>
       <div class="row"> 
          <div class="col-md-12 col-lg-12">
             <div id="tracking-pre"></div>
             <div id="tracking">
                <div class="text-center tracking-status-intransit" >
                   <p class="tracking-status text-tight">
                        <strong>Origin</strong> <br />
                        <?php echo  $booking_info['sender_name'];?>  
                   </p>
                </div>
                <div class="tracking-list">
                   <?php foreach($tracking_info as $i => $info) {?> 
                    <div class="tracking-item" style="border-left: 4px dotted blue;">
                      <div class="tracking-icon status-outfordelivery">
                          <i class="fas fa-cube"></i> 
                      </div>
                      <div class="tracking-date"><?php echo date('M d, Y', strtotime($info['status_date'])) ;?><span><?php echo date('h:i A', strtotime($info['status_time'])) ;?></span></div>
                      <div class="tracking-content">
                        <strong><?php echo $info['tracking_status'];?></strong> <span><?php echo $info['city_code'];?></span>
                           <br />
                           <?php echo $info['remarks'];?>
                           <?php if(!empty($info['pod'])) {?>
                            <br /><a href="tcs-panel/<?php echo $info['pod'];?>" target="_blank" class="btn-link">VIEW POD</a>   
                           <?php } ?> 
                      </div>
                    </div>
                   <?php } ?> 
                   <?php if(count($tracking_info) == 1) {?>
                    <div class="tracking-item">
                      <div class="tracking-icon status-intransit">
                         <i class="fas fa-cube fa-2x"></i> 
                      </div>
                      <div class="tracking-date"> <span> </span></div>
                      <div class="tracking-content">In-Transit</div>
                    </div>
                   <?php } ?>  
                    
                   <?php if((! in_array('Delivered', $tracking_status))) {?>
                   <div class="tracking-item">
                      <div class="tracking-icon status-intransit">
                          <i class="fas fa-cube fa-2x"></i> 
                      </div>
                      <div class="tracking-date"> <span> </span></div>
                      <div class="tracking-content">Delivered</div> 
                    </div>
                     <?php } ?>
                    
                </div>
                <div class="text-center tracking-status-intransit" >
                   <p class="tracking-status text-tight">
                        <strong>Destination</strong> <br />
                        <?php echo  $booking_info['country'];?> <br />
                        <?php echo "Receiver: " .$booking_info['receiver_name'];?><br />
                        <div style="font-size: 14px;" class="text-white"><?php if(!empty($booking_info['exp_dv_date'])) echo "Expected Delivery Date: " .date('M d, Y', strtotime($booking_info['exp_dv_date']));?></div>
                   </p>
                </div>
             </div>
          </div>
       </div>
    </div>
<?php } else { ?>
         <?php if(isset($_REQUEST['awbno']) ) {?>
            <div class="text-center p-3"><h2 class="text-primary text-uppercase font-weight-bold">Invalid AWB No. Try Again</h2></div>
         <?php } ?>
<?php } ?>

     


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white mt-5 py-5 px-sm-3 px-md-5" id="contact">
        <div class="row pt-5">
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <h3 class="text-primary mb-4">Get In Touch</h3>
                        <h5 class="text-primary mb-4">Support Desk:</h5>
                        <p><i class="fa fa-map-marker-alt mr-2"></i>Office No.951, Group 3, Opp. DDA Market, Uttam Nagar, New Delhi - 110059</p>
                        <!--<p><a href="tel:+917736893993" target="_blank" class="text-white"><i class="fa fa-mobile mr-2"></i>+91 773 689 3993</a></p>-->
                        <p><a href="mailto:support@couriersyndicate.co.in" target="_blank" class="text-white"><i class="fa fa-envelope mr-2"></i>support@couriersyndicate.co.in</a></p>
                        <div class="d-flex justify-content-start mt-4">
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <h3 class="text-primary mb-4">Quick Links</h3>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-white mb-2" href="index.html"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-white mb-2" href="index.html#about"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                            <a class="text-white mb-2" href="index.html#service"><i class="fa fa-angle-right mr-2"></i>Our Services</a>
                            <!--<a class="text-white mb-2" href="#testimonial"><i class="fa fa-angle-right mr-2"></i>Testimonial</a>-->
                            <a class="text-white mb-2" href="index.html#contact"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 mb-5">
                <h3 class="text-primary mb-4">Pincode Search <br /> [ Serviceable Location ]</h3>
                <div class="w-100">
                    <div class="input-group">
                        <input type="number" name="pincode" id="pincode" class="form-control border-light" style="padding: 30px;" placeholder="Pincode">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4 btn_pin_search" id="btn_pin_search" name="btn_pin_search">Search</button>
                        </div> 
                    </div>
                    <div class="text-white p-2" id="result"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5" style="border-color: #3E3E4E !important;">
        <div class="row">
            <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
                <p class="m-0 text-white"> &copy; 2026 <a href="index.html">Courier Syndicate. </a> All Rights Reserved. </p>
                <strong style="display: none;">Powered by CR Info Tech</strong>
            </div>
            <div class="col-lg-6 text-center text-md-right">
                <ul class="nav d-inline-flex">
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Terms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Help</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>