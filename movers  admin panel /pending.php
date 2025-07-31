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
                <div class="col-12 col-sm-6">
                  <h3>Pending Load List Management</h3>
                </div>
                
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid general-widget">
            <div class="row">
             
             <div class="col-sm-12">
                <div class="card">
				<div class="card-body">
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
							<th>Load Vehicle Name</th>
							<th>Load Vehicle</th>
											<th>Load Owner Name</th>
												<th>Lorry Owner Name</th>
												<th>Pick Up Point</th>
												<th>Drop Point</th>
												<th>Material Name</th>
												<th>Load Weight</th>
												<th>Load Type</th>
												<th>Load Amount</th>
												<th>Load Amount Type</th>
												<th>Load Total Amount</th>
												<th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                           <?php 
										$city = $service->query("select * from tbl_load where load_status='Pending'");
										$i=0;
										while($row = $city->fetch_assoc())
										{
											$i = $i + 1;
											$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
											$tdata = $service->query("select name from tbl_user where id=".$row['uid']."")->fetch_assoc();
											$odata = $service->query("select name from tbl_lowner where id=".$row['lorry_owner_id']."")->fetch_assoc();
											?>
											<tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $vdata['title'];?>
                                                </td>
												
                                                <td class="align-middle">
                                                   <img src="<?php echo $vdata['img']; ?>" width="40" height="40"/>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $tdata['name'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo empty($odata['name']) ? 'Not Selected Lorry Owner':$odata['name'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['pickup_point'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['drop_point'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['material_name'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['weight'];?>
                                                </td>
												
												<td class="align-middle">
                                                  <span class="badge badge-success"> <?php echo $row['load_type'];?> </span>
                                                </td>
												
												
												<td class="align-middle">
                                                   <?php echo $row['amount'].$set['currency'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['amt_type'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['total_amt'].$set['currency'];?>
                                                </td>
												
												<td class="align-middle">
                                                 <a href="https://www.google.com/maps/dir/?api=1&origin=<?php echo $row['pick_lat'].','.$row['pick_lng'];?>&destination=<?php echo $row['drop_lat'].','.$row['drop_lng'];?>" target="_blank"> <span class="cursor-pointer badge badge-success">Show Direction</span> </a>
                                                </td>
                                               
												
												
                                                
                                                </tr>
											<?php 
										}
										?>
                          
                        </tbody>
                      </table>
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