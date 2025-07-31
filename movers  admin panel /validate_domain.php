<?php 
include 'inc/Header.php';
?>
  
    <!-- Loader starts-->
    
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <section>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-5"><img class="bg-img-cover bg-center" src="assets/images/login/3.jpg" alt="looginpage"></div>
          <div class="col-xl-7 p-0">    
            <div class="login-card">
                	<div class="text-center mb-3 mt-18">
			<img class="" src="<?php echo $set['weblogo'];?>" width="200px" alt="looginpage">
			</div>
             <div class="theme-form login-form">
                <h4>Validate Domain</h4>
                <h6>Welcome back! Validate your account.</h6>
                <div id="getmsg"></div>
                <div class="form-group">
                  <label>Enter Envato Purchase Code</label>
                  <div class="input-group"><span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input class="form-control" type="text" required="" name="inputCode" id="inputCode" placeholder="Enter  Enavato Purchase Code">
					
                  </div>
                </div>
				
			
                
                
                <div class="form-group">
                  <button class="btn btn-primary btn-block" id="sub_activate" >Activate & Enjoy Our Service</button>
                </div>
                </div>
              
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- page-wrapper end-->
    <!-- latest jquery-->
    <?php include 'inc/Footer.php';?>
	
    <!-- login js-->
    <!-- Plugin used-->
  </body>


</html>