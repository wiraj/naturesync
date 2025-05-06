<?php

    session_start();
    
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
    	// Redirect to the login page or handle accordingly
    	header("Location:login.php");
    	exit();
    }


?>


<?php include("includes/app_header.php"); ?>


<!--include all required libraries for AR-->
<script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
<script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
<script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-detector.js"></script>
<script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-handler.js"></script>



<?php include("includes/menu_bar.php"); ?>
	



</div> 


<a-scene embedded arjs="sourceType: webcam; debugUIEnabled: false;">
    <!-- Marker 1-->
    <a-marker type="pattern" url="assets/images/pattern-sib.patt">
      <!-- Plane to display the image -->
      <a-plane id="imagePlane" position="0 0 0" rotation="-90 0 0" width="2.25" height="4" material="shader: flat; scale: 2 2 2; src:assets/images/2.png"></a-plane>
    </a-marker>
    
  <!-- Marker 2 -->
  <a-marker type="pattern" url="assets/images/pattern-Oak.patt">
    <a-plane position="0 0 0" rotation="-90 0 0" width="2.25" height="4" material="shader: flat; src:assets/images/tree_info2.png">
    </a-plane>
  </a-marker>
  
  <!-- Marker 3 -->
  <a-marker type="pattern" url="assets/images/pattern-cob.patt">
    <a-plane position="0 0 0" rotation="-90 0 0" width="2.25" height="4" material="shader: flat; src:assets/images/3.png">
    </a-plane>
  </a-marker>
      
    <!-- Camera to view the scene -->
    <a-entity camera></a-entity>
</a-scene>


<!--**********************************
    Scripts
***********************************-->
<script src="assets/js/jquery.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script><!-- Swiper -->
<script src="assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script><!-- Swiper -->
<script src="assets/js/dz.carousel.js"></script><!-- Swiper -->
<script src="assets/js/settings.js"></script>
<script src="assets/js/custom.js"></script>
<script src="index.js"></script>
<script>
    $(".stepper").TouchSpin();
</script>
</body>
</html>