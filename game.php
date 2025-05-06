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

<script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
<script type='text/javascript' src='vendor/threejs/ar-threex-location-only.js'></script>
<script type='text/javascript' src='vendor/aframe/aframe-ar-nft.js'></script>
    
<script>
    AFRAME.registerComponent('clicker', {
        init: function() {
            this.el.addEventListener('click', e => {
                window.location.href='submit_blog.php';
            });
        }
    });
</script>

    
<!-- Bootstrap Modal for Gamification Alert with Enhanced Styles -->
<div class="modal fade" id="gameAlertModal" tabindex="-1" aria-labelledby="gameAlertLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center bg-gradient p-3" style="border: 3px solid #ff9800; border-radius: 20px;">
      <div class="modal-header border-0">
        <h5 class="modal-title w-100 text-warning fw-bold" id="gameAlertLabel" style="font-size: 28px; text-shadow: 2px 2px #000;">
          🎮 Ready to Play!
        </h5>
      </div>
      <div class="modal-body">
        <img src="assets/images/game.gif" alt="Game Alert" class="img-fluid mb-4" style="max-width: 120px; border: 3px dashed #ffc107; border-radius: 50%;">
        <p class="fs-5 fw-semibold">🔎 <strong>Scan your surroundings!</strong></p>
        <p class="fs-6 text-dark">👀 <span>Find the <strong>hidden game box</strong></span> nearby and <strong>tap on it</strong> to unlock rewards! 🎁</p>
        <p class="fs-5 text-danger fw-bold">🏆 Are you ready for adventure?</p>
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button type="button" class="btn btn-success btn-lg px-4 py-2" data-bs-dismiss="modal"  style="font-size: 18px; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
          🚀 Let's Go!
        </button>
      </div>
    </div>
  </div>
</div>

		
<?php include("includes/menu_bar.php"); ?>

	

	
</div>  

   <a-scene
        vr-mode-ui="enabled: false"
        cursor="rayOrigin: mouse"
        raycaster="near: 0; far: 50000"
        arjs="sourceType: webcam; videoTexture: true; debugUIEnabled: false;"
        renderer="antialias: true; alpha: true">
        
         <!--Camera -->
        <a-camera gps-new-camera="simulateLatitude: 54.976119; simulateLongitude: -1.609522"></a-camera>

         <!--3D GLB Model -->
        <a-entity 
            gps-new-entity-place="latitude:54.9761749536438; longitude:-1.6101059347244777" 
            gltf-model="assets/gift.glb"  
            scale="10 10 10"
            rotation="0 180 0"
            clicker>
        </a-entity>
        
 
        <a-entity 
            gps-new-entity-place="latitude:54.9797542905695; longitude:-1.6074849229494328" 
            gltf-model="assets/gift.glb"  
            scale="10 10 10"
            rotation="0 180 0"
            clicker>
        </a-entity>
        
        <a-entity 
            gps-new-entity-place="latitude:54.978367938535726; longitude:-1.6079914078012691" 
            gltf-model="assets/gift.glb"  
            scale="10 10 10"
            rotation="0 180 0"
            clicker>
        </a-entity>
    
        <a-entity 
            gps-new-entity-place="latitude:54.97747474994541; longitude:-1.607685744944228" 
            gltf-model="assets/gift.glb"  
            scale="10 10 10"
            rotation="0 180 0"
            clicker>
        </a-entity>
        
        <a-entity 
            gps-new-entity-place="latitude:54.976015437039855; longitude:-1.6039203327616358" 
            gltf-model="assets/gift.glb"  
            scale="10 10 10"
            rotation="0 180 0"
            clicker>
        </a-entity>

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
    
    document.addEventListener("DOMContentLoaded", function () {
        let gameModal = new bootstrap.Modal(document.getElementById('gameAlertModal'), { backdrop: 'static', keyboard: false });
        gameModal.show();
    });
  
</script>
</body>
</html>