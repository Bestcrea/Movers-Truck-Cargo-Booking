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
                 
					<h3>Earning Report Management</h3>
					
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
                                                <th></th>
                                                <th>Name</th>
                                                <th>Total Sales Generate</th>
                                                <th>Lorry Owner Earning</th>
                                                <th>Admin Earning</th>
                                                <th>Lorry Owner Pending Payout</th>
                                                <th>Lorry Owner Complete Payout</th>
												<th>Lorry Owner After Payout Remain Earning</th>
												
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
											 $stmt = $service->query("SELECT pro_pic,id,name FROM `tbl_lowner`");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$i = $i + 1;
											?>
                                            <tr>
                                                <td><img class="rounded-circle" width="35" height="35" src="<?php echo $row['pro_pic'];?>" alt=""></td>
                                                <td><?php echo $row['name'];?></td>
												<td><span class="badge badge-success"><?php $total_earn            = $service->query("select sum(total_amt) as total_amt from tbl_load where  load_status ='Completed' and lorry_owner_id=".$row["id"]."")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	echo $total_earns.$set['currency'];?></span></td>
												<td><span class="badge badge-info"><?php $total_earn            = $service->query("select sum((total_amt) - ((total_amt) * commission/100)) as total_amt from tbl_load where  load_status ='Completed' and lorry_owner_id=".$row["id"]."")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	echo $total_earns.$set['currency'];?></span></td>
												<td><span class="badge badge-success"><?php $total_earn            = $service->query("select sum(total_amt * commission/100) as total_amt from tbl_load where  load_status ='Completed' and lorry_owner_id=".$row["id"]."")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	echo $total_earns.$set['currency'];?></span></td>
												<td><span class="badge badge-warning"><?php 
						$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where status='pending' and owner_id=".$row["id"]."")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	echo $receive_payout.$set['currency'];
	?></span></td>	
                                                <td><span class="badge badge-success"><?php 
						$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where status='completed' and owner_id=".$row["id"]."")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	echo $receive_payout.$set['currency'];
	?></span></td>		

<td><span class="badge badge-info"><?php 
						$total_earn            = $service->query("select sum((total_amt) - ((total_amt) * commission/100)) as total_amt from tbl_load where lorry_owner_id=" . $row["id"] . " and load_status ='Completed'")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	
	$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where owner_id=" . $row["id"] . "")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	echo $final_earn            = number_format((float) ($total_earns) - $receive_payout, 2, '.', '').$set['currency'];
	?></span></td>	

	
                                            </tr>
<?php } ?>
                                            
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