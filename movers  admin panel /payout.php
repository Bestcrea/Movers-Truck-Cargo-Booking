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
                  <h3>Payout List Management</h3>
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
				    <?php 
	if(isset($_GET['payout']))
						{
							?>
							<form class="form" method="post"  enctype="multipart/form-data">
							<div class="form-body">
								

								

								
								
								

								
<div class="form-group mb-3">
                                            <label>Payout Image</label>
                                            <input type="file" class="form-control" name="cat_img" required="">
											<input type="hidden" name="type" value="com_payout">
											<input type="hidden" name="payout_id" value="<?php echo $_GET['payout'];?>"/>
                                        </div>
								
							</div>

							 <div class=" text-left">
                                        <button  class="btn btn-primary">Complete Payout <i class="fas fa-receipt"></i></button>
                                    </div>
							
							
						</form>
						
						<?php 
						}
						else 
						{ ?>
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                                                <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                               
                                    <th>Amount</th>
                                   
									<th>Lorry Owner Name</th>
									<th>Transfer Details</th>
                                    <th>Transfer Type</th>
									<th>Vendor Mobile</th>
									<th>Transfer Photo</th>
									
									 <th>Status</th>
<th>Action</th>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            <?php 
											 $stmt = $service->query("SELECT * FROM `payout_setting`");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$i = $i + 1;
											?>
                                                <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                               
                                    <td><?php echo $row['amt'].' '.$set['currency'];?></td>
									<?php 
									$vdetails = $service->query("select name,ccode,mobile from tbl_lowner where id=".$row['owner_id']."")->fetch_assoc();
									?>
									<td><?php echo $vdetails['name'];?></td>
									<?php 
									if($row['r_type'] == 'UPI')
									{
									  ?>
									  <td><?php echo $row['upi_id'];?></td>
									  <?php 
									}
									else if($row['r_type'] == 'BANK_Transfer')
									{
									 ?>
									 <td><?php echo 'Bank Name: '.$row['bank_name'].'<br>'.'A/C No: '.$row['acc_number'].'<br>'.'A/C Name: '.$row['receipt_name'].'<br>'.'IFSC CODE: '.$row['ifsc'].'<br>';?></td>
									 <?php 
									}
									else 
									{
									   ?>
									   <td><?php echo $row['paypal_id'];?></td>
									   <?php 
									}
									?>
									
									<td><?php echo $row['r_type'];?></td>
									 <td><?php echo $vdetails['ccode'].$vdetails['mobile'];?></td>
									 <?php
									 if($row['proof'] == '')
									 {
                                       ?>
									   <td></td>
<?php									   
									 }else {
									     ?>
									 
									  <td><img src="<?php echo $row['proof']; ?>" width="70" height="80"/></td>
									  <?php } ?>
									 <td><?php echo ucfirst($row['status']);?></td>
                                     <td>
									 <?php if($row['status'] == 'pending') {?>
									<a href="?payout=<?php echo $row['id'];?>"><button class="btn shadow-z-2 btn-danger gradient-pomegranate">Make A Payout</button></a>
									 <?php } else { ?>
									 <p><?php echo ucfirst($row['status']);?></p>
									 <?php } ?>
									</td>
                                                </tr>
<?php } ?>                                           
                                        </tbody>
                      </table>
					  </div>
					  <?php } ?>
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