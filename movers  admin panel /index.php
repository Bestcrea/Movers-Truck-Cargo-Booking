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
              <form class="theme-form login-form">
                <h4>Login</h4>
                <h6>Welcome back! Log in to your account.</h6>
                <div class="form-group">
                  <label>Username</label>
                  <div class="input-group"><span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input class="form-control" type="text" required="" name="username" placeholder="Enter a Username">
					<input type="hidden" name="type" value="login"/>
                  </div>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <div class="input-group"><span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input class="form-control" type="password" name="password" required="" placeholder="*********">
                  </div>
                </div>
				
				
                
                <div class="form-group">
                  <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                </div>
                
              </form>
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