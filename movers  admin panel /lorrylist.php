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
				<?php 
				if(isset($_GET['doc_id']))
				{
					?>
					<h3>Document List Management</h3>
				<?php } 
				else if(isset($_GET['rej_id']))
				{
					?>
					<h3>Reject Form Management</h3>
					<?php
				}
				else 
				{
					?>
					<h3>Lorry List Management</h3>
					<?php 
				}
				?>
                  
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
				<?php 
				if(isset($_GET['doc_id']))
				{
					?>
					<table class="display" id="basic-1">
                        <thead>
                           <tr>
                                                <th>Document ID.</th>
												<th>Lorry Document.</th>
                                                <th>Document Decision</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
											 $stmt = $service->query("SELECT * FROM `tbl_lorry` where id=".$_GET['doc_id']."")->fetch_assoc();

											?>
                                            <tr>
											<td><?php echo $stmt['id'];?></td>
											
                                                <td>
												<div class="my-gallery" itemscope="">
                                    <div class="d-flex gallery">
												<?php 
												$img = explode('$;',$stmt['document']);
												foreach($img as $vb)
												{
												?>
												<figure class="" itemprop="associatedMedia" itemscope=""><a href="<?php echo $vb;?>" itemprop="contentUrl" data-size="600x600"><img  src="<?php echo $vb;?>" itemprop="thumbnail" alt="Image description" width="60" height="60"></a>
                                        
                                      </figure> &nbsp;
									  
												
												<?php } ?>
												</div>
												</div>
												</td>
												<?php 
												if($stmt['is_verify'] == 0)
												{
													?>
												<td><span data-id="<?php echo $stmt['id'];?>" data-status="1" data-type="update_status" data-coll-type="documentstatus" class="drop badge badge-success cursor-pointer">Approve</span>
												<a href="?rej_id=<?php echo $stmt['id'];?>"><span class="badge badge-danger cursor-pointer">Reject</span></td>
												<?php 
												}
												else if($stmt['is_verify'] == 1)
												{
													?>
													<td><span class="badge badge-success">All Document Approved</span></td>
													<?php
												}
												else
													{
													?>
													<td><span class="badge badge-danger">All Document Rejected</span></td>
													<?php
												}
												?>
                                            </tr>

                                            
                                        </tbody>
                      </table>
					  <!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

          </div>

        </div>

</div>
					<?php 
				}
				else if(isset($_GET['rej_id']))
				{
					?>
					<form class="form" method="post">
							<div class="form-body">
								

							

								<div class="form-group">
									<label for="cname">Cancle Reason</label>
									<textarea name="c_reason" class="form-control" style="resize:none;"></textarea>
									<input type="hidden" name="type" value="cancle_order"/>
											
										<input type="hidden" name="id" value="<?php echo $_GET['rej_id'];?>"/>
								</div>
                                
								
								<br>
								<div class="form-group">
                                        <button  class="btn btn-primary">Reject Order</button>
                                    </div>
								</div>
								</form>
					<?php 
				}
			else 
			{
				?>
                <table class="display" id="basic-1">
                        <thead>
                           <tr>
                                                <th>Lorry ID.</th>
												<th>Lorry Img.</th>
												<th>Lorry title.</th>
                                                <th>Lorry Owner Name</th>
                                                <th>Lorry Owner Number</th>
                                                <th>Lorry Number</th>
                                                <th>Lorry Routes</th>
                                                <th>Status</th>
												<th>is_verify?</th>
												<th>Rejected Comment</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
											 $stmt = $service->query("SELECT * FROM `tbl_lorry`");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$vdata = $service->query("SELECT title,img FROM `tbl_vehicle` where id=".$row['vehicle_id']."")->fetch_assoc();
	$odata = $service->query("SELECT name,ccode,mobile FROM `tbl_lowner` where id=".$row['owner_id']."")->fetch_assoc();
	$state = $service->query("select GROUP_CONCAT(title)  as fav_routes   from tbl_state where id IN(".$row['routes'].")")->fetch_assoc();
	$i = $i + 1;
											?>
                                            <tr>
											<td><?php echo $row['id'];?></td>
											
                                                <td><img class="rounded-circle" width="35" height="35" src="<?php echo $vdata['img'];?>" alt=""></td>
												<td><?php echo $vdata['title'];?></td>
                                                <td><?php echo $odata['name'];?></td>
												<td><?php echo $odata['ccode'].$odata['mobile'];?></td>
												<td><?php echo $row['lorry_no'];?></td>
												<td><?php echo '<span class="badge badge-dark tag-pills-sm-mb">'.str_replace(',','</span><span class="badge badge-dark tag-pills-sm-mb">',$state['fav_routes']);?>
												</td>
												<?php if($row['status'] == 1) { ?>
												
                                                <td><span class="badge badge-success">Publish</span></td>
												<?php } else { ?>
												
												<td>
												<span class="badge badge-danger">Unpublish</span></td>
												<?php } ?>
												<td>
												<?php
                                                if($row['is_verify'] == 0)
												{
													?>
													<span class="badge badge-info">Document Not Upload Yet.</span></td>
													<?php 
												}
else {												
												?>
												<a href="?doc_id=<?php echo $row['id'];?>"><span class="badge badge-warning">Check Document</span></a></td>
<?php } ?>
												<td><?php
                                                if($row['is_verify'] == 2)
												{
													?>
												<?php echo $row['cancle_reason'];?></td>
												<?php } else {
													
													?>
                                               				</td>
													<?php
												} ?>
                                               												
                                            </tr>
<?php } ?>
                                            
                                        </tbody>
                      </table>
			<?php } ?>
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