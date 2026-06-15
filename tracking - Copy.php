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
                a.status_city_code
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
  
  /* 
  echo "<pre>";
  print_r($booking_info);  
  print_r($tracking_info);  
  echo "</pre>";  
  */
   
 }
 
 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Courier Syndicate - Best Courier Service in Kerala</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Best Courier Service in Kerala" name="keywords">
    <meta content="We offers integrated express services network for the swift, safe and sure transportation and distribution of your consignment document, parcels and commercial goods." name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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
                    <small><a href="tel:+917736893993" target="_blank"><i class="fa fa-mobile mr-2"></i><strong>+91 773 689 3993</strong></a></small>
                    <small class="px-3">|</small>
                    <small><a href="mailto:support@couriersyndicate.co.in" target="_blank"><i class="fa fa-envelope mr-2"></i>support@couriersyndicate.co.in</a></small>
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
            <a href="index.html" class="navbar-brand ml-lg-3">
                <img src="img/logo.png" class="img-fluid" alt="courier syndicate" width="40%" />
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                 <div class="navbar-nav m-auto py-0">
                    <a href="index.html" class="nav-item nav-link ">Home</a>
                    <a href="#about" class="nav-item nav-link">About</a>
                    <a href="#service" class="nav-item nav-link ">Service</a> 
                    <a href="tracking.php" class="nav-item nav-link active">Tracking</a> 
                    <a href="#testimonial" class="nav-item nav-link">Testimonial</a> 
                    <a href="#contact" class="nav-item nav-link">Contact</a>
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


    <!-- Pricing Plan Start -->
    <div class="container-fluid pt-5">
        <div class="container">
            <div class="text-center pb-2">
                <h1 class="text-primary text-uppercase font-weight-bold">Consignment Tracking</h1><br />
                <form method="post" action="tracking.php">
                <div class="row">
                    <div class="col-lg-6"><h3 class="mb-4">Tracking AWB No </h3></div>
                    <div class="col-lg-6 text-center">
                        <div class="input-group"> 
                            <input type="number" name="awbno" id="awbno" value="<?php if(isset($_REQUEST['awbno'])) echo $_REQUEST['awbno']; ?>" class="form-control border-dark" style="padding: 30px;" placeholder="Tracking AWB No">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-3" name="btn_search" value="Tracking" type="submit">Track & Trace</button>
                            </div> 
                        </div>
                   </div> 
               </div> 
               </form>
            </div>
            <?php if(!empty($tracking_info)){?> 
            <div class="row m-3">
                 <div class="col-lg-5 p-2 m-1 text-left" style="border: 1px dotted silver; border-radius: 10px;"> 
                     Origin : <br />
                    <div class="p-2" ><?php echo $booking_info['origin_pincode'];?><br />
                    <?php echo $booking_info['origin_state'] . ' - ' . $booking_info['origin_city'];?><br />
                    <?php echo $booking_info['sender_name'];?> 
                    </div>
                </div> 
                <div class="col-lg-5 p-2 m-1 text-left"  style="border: 1px dotted silver; border-radius: 10px;"> 
                    Destination : <br />
                    <div class="p-2" ><?php echo $booking_info['dest_pincode'];?><br />
                    <?php echo $booking_info['dest_state'] . ' - ' . $booking_info['dest_city'];?><br />
                    <?php echo $booking_info['receiver_name'];?> 
                    </div>
                </div> 
                <!--<div class="col-lg-3 p-2 m-1 text-left"  style="border: 1px dotted silver; border-radius: 10px;"> 
                    <i >
                    <div class="p-2" >
                     No.Of Pcs: <?php //echo $booking_info['no_of_pieces'];?><br />
                     Weight : <?php //echo $booking_info['weight'] . " kgs";?> 
                    </div>
                </div> -->
            </div> 
             
             
        </div>
          
         <div class="container-timeline"> 
           <?php foreach($tracking_info as $i => $info) {?>   
           <div class="timeline-block timeline-block-right">
              <div class="marker active text-white p-2"> <i class="fa fa-truck active" aria-hidden="true"></i></div>
              <div class="timeline-content bg-gray-light">
                 <h3><?php echo $info['tracking_status'];?></h3>
                 <span class="text-dark"> 
                     <?php echo $info['city'];?>   <br />
                    <em><?php echo date('d-m-Y', strtotime($info['status_date'])) ;?></em> <br />
                    <em><?php echo date('h:i a', strtotime($info['status_time'])) ;?></em> 
                </span>
                 
              </div>
           </div>
           <?php } ?>  
           <?php if(count($tracking_info) == 1) {?>
           <div class="timeline-block timeline-block-left">
              <div class="marker text-white  p-2"><i class="fa fa-truck" aria-hidden="true"></i></div>
              <div class="timeline-content p-3 m-1">
                 <h3>In-Transit</h3>  
                 <br />
                 <br />
              </div>
           </div>
            <?php } ?>
           <?php if((! in_array('Out for Delivery', $tracking_status)) || (! in_array('Delivered', $tracking_status))) {?>
           <div class="timeline-block timeline-block-left">
              <div class="marker text-white  p-2"><i class="fa fa-truck" aria-hidden="true"></i></div>
              <div class="timeline-content p-3 m-1">
                 <h3>Out for Delivery</h3>  
                 <br />
                 <br />
              </div>
           </div>
           <?php } ?>
           <?php if((! in_array('Delivered', $tracking_status))) {?> 
           <div class="timeline-block timeline-block-left">
              <div class="marker text-white p-2"><i class="fa fa-truck" aria-hidden="true"></i></div>
              <div class="timeline-content p-3 m-1">
                 <h3>Delivered</h3>  
                 <br />
                 <br />
              </div>
           </div>
           <?php } ?>
        
           
        </div>
        <?php } else { ?>
         <?php if(isset($_REQUEST['awbno']) ) {?>
            <div class="text-center p-3"><h2 class="text-primary text-uppercase font-weight-bold">Invalid AWB No. Try Again</h2></div>
         <?php } ?>
         <?php } ?>
    </div>
    <!-- Pricing Plan End -->


     


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white mt-5 py-5 px-sm-3 px-md-5" id="contact">
        <div class="row pt-5">
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <h3 class="text-primary mb-4">Get In Touch</h3>
                        <p><i class="fa fa-map-marker-alt mr-2"></i>Office No.951, Group 3, Opp. DDA Market, Uttam Nagar, New Delhi - 110059</p>
                        <p><a href="tel:+917736893993" target="_blank" class="text-white"><i class="fa fa-mobile mr-2"></i>+91 773 689 3993</a></p>
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
                            <a class="text-white mb-2" href="#about"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                            <a class="text-white mb-2" href="#services"><i class="fa fa-angle-right mr-2"></i>Our Services</a>
                            <a class="text-white mb-2" href="#testimonial"><i class="fa fa-angle-right mr-2"></i>Testimonial</a>
                            <a class="text-white mb-2" href="#contact"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 mb-5">
                <h3 class="text-primary mb-4">Pincode Search <br /> [ Servicable Location ]</h3>
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
                <p class="m-0 text-white"> &copy; 2023 <a href="index.html">Courier Syndicate. </a> All Rights Reserved. </p>
                Powered by CR Info Tech
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