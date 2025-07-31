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
                  <h3>Cover Management</h3>
                </div>
                
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid general-widget">
            <div class="row">
             
             <div class="col-sm-12">
                <div class="card">
                 <?php 
				 if(isset($_GET['id']))
				 {
					 $data = $service->query("select * from tbl_cover where id=".$_GET['id']."")->fetch_assoc();
					 ?>
					 <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										<div class="form-group mb-3">
                                            <label>Category Name</label>
                                            <select name="cat_id"  required class="form-control select2-single" data-placeholder="Select Category">
<option value="" selected disabled>Select
Zone</option>
<?php 
$zone = $service->query("select * from tbl_category where id IN(".$sdata['catid'].")");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>" <?php if($data['cat_id'] == $row['id']) {echo 'selected';}?>><?php echo $row['title'];?></option>
	<?php 
}
?>
</select>
                                        </div>
										
										
										
                                       
										
										
										
										 <div class="form-group mb-3">
                                            <label>Cover Status</label>
											<input type="hidden" name="type" value="edit_cover"/>
											
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
											<option value="0" <?php if($data['status'] == 0){echo 'selected';}?> >UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Edit  Cover</button>
                                    </div>
                                </form>
					 <?php 
				 }
				 else 
				 {
				 ?>
                  <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										
										<div class="form-group mb-3">
                                            <label>Category Name</label>
                                            <select name="cat_id"  required class="form-control select2-single" data-placeholder="Select Category">
<option value="" selected disabled>Select
Category</option>
<?php 
$zone = $service->query("select * from tbl_category where id IN(".$sdata['catid'].")");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
	<?php 
}
?>
</select>
                                        </div>
										
										
										
										
										 <div class="form-group mb-3">
										 <input type="hidden" name="type" value="add_cover"/>
                                            <label>Cover Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Add Cover</button>
                                    </div>
                                </form>
				 <?php } ?>
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