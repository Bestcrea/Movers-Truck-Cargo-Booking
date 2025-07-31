<?php 
include 'inc/Header.php';
?>

    <!-- Loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <?php include 'inc/Navbar.php'; ?>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
       <?php 
	   include 'inc/Sidebar.php';
	   ?>
        <!-- Page Sidebar Ends-->
		
        <div class="page-body">
          <div class="container-fluid">        
            <div class="page-title">
              <div class="row">
                <div class="col-md-8 col-sm-12">
                  <h3>Report Dashboard</h3>
                </div>
				<div class="col-md-4 col-sm-12">
				<div class="float-end"><a href="payout.php" class="btn btn-outline-primary-2x">Click To Check Payout</a></div> 
				<div class=""><a href="earningreport.php" class="btn btn-outline-primary-2x">Click To Get Earning Report</a></div>
                 </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid general-widget dashboardt">
            <div class="row">
              <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget mb-0">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Banner</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from banner")->num_rows;?></h4>
                      </div>
                       <img src="assets/dashboard/icon1.svg" class="img-100">
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget mb-0">
                      <div class="media-body">
                        <h6 class="font-roboto">Total State</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_state")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon11.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Transporter</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_user")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			 
            
              
              
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total FAQ</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_faq")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon6.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              
			   <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Lorry Owner</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_lowner")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Lorry</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_lorry")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
              <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget mb-0">
                      <div class="media-body">
                        <h6 class="font-roboto">Pages</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_page")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon10.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget mb-0">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Pending Load</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_load where load_status='Pending'")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon10.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
             
              
            </div>
			<div class="row">
              
			   <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Accepted Load</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_load where load_status='Accepted'")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Pick Up Load</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_load where load_status='Load_start'")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Completed Load</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_load where load_status='Completed'")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Cancelled Load</h6>
                        <h4 class="mb-0 counter"><?php echo $service->query("select * from tbl_load where load_status='Cancelled'")->num_rows;?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  
			  </div>
			  
			  <div class="row">
              
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Sales</h6>
                        <h4 class="mb-0 counter"><?php $total_earn            = $service->query("select sum(total_amt) as total_amt from tbl_load where  load_status ='Completed'")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	echo $total_earns.$set['currency'];
	?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  
			   <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Earning</h6>
                        <h4 class="mb-0 counter"><?php $total_earn            = $service->query("select sum(total_amt * commission/100) as total_amt from tbl_load where  load_status ='Completed'")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	echo $total_earns.$set['currency'];
	?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Pending Payout</h6>
                        <h4 class="mb-0 counter"><?php 
						$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where status='pending'")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	echo $receive_payout.$set['currency'];
	?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  
			  <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden">
                  <div class="card-body">
                    <div class="media static-widget">
                      <div class="media-body">
                        <h6 class="font-roboto">Total Completed Payout</h6>
                        <h4 class="mb-0 counter"><?php 
						$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where status='completed'")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	echo $receive_payout.$set['currency'];
	?></h4>
                      </div>
                     <img src="assets/dashboard/icon4.svg" class="img-100">

                    </div>
                    
                  </div>
                </div>
              </div>
			  
			  
			  </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
	
      </div>
    </div>
    <!-- latest jquery-->
   <?php include 'inc/Footer.php';?>
    <!-- login js-->
    <!-- Plugin used-->
  </body>

</html>