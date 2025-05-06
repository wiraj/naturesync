
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


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<style>
  
    #map {
        height: 100vh;
        width: 100%;
  
    }
    

    #gameModal .modal-content {
        background: linear-gradient(135deg, #ffecd2, #fcb69f);
        border: 3px dashed #ff6b6b;
        border-radius: 20px;
        box-shadow: 0 0 25px rgba(255, 107, 107, 0.7);
        text-align: center;
        animation: bounceIn 0.6s;
    }

    #gameModal .modal-title {
        font-size: 26px;
        font-weight: 800;
        color: #ff4e50;
        text-shadow: 2px 2px #fff;
    }

    #gameModal .modal-body p {
        font-size: 18px;
        color: #4a4a4a;
        font-weight: 600;
        padding: 10px 20px;
    }

    #gameModal .modal-footer {
        justify-content: center;
        padding-bottom: 25px;
    }

    #gameModal .btn-success {
        background: linear-gradient(135deg, #00c853, #b2ff59);
        border: none;
        font-weight: 700;
        font-size: 16px;
        padding: 10px 25px;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 200, 83, 0.5);
        transition: all 0.3s;
    }

    #gameModal .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0, 200, 83, 0.7);
    }

    #gameModal .btn-dafault {
        background: #ff6f61;
        color: white;
        border: none;
        font-weight: 700;
        padding: 10px 25px;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(255, 111, 97, 0.4);
        transition: all 0.3s;
    }

    #gameModal .btn-dafault:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(255, 111, 97, 0.7);
    }

    @keyframes bounceIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }


</style>

	
<div id="map"></div>

<!-- Bootstrap Modal for Game Interaction -->
<div class="modal fade" id="gameModal" tabindex="-1" aria-labelledby="gameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gameModalLabel">🎮 Earn Loyalty Points!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are near a game location! Would you like play and earn **Loyalty Points**?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dafault" data-bs-dismiss="modal">No, Thanks</button>
                <a id="playGameBtn" href="game.php" class="btn btn-success">Yes, Play Now</a>
            </div>
        </div>
    </div>
</div>
<audio id="alertSound" preload="auto">
    <source src="assets/sounds/alert.mp3" type="audio/mpeg">
</audio>

<script>
    
    document.addEventListener("DOMContentLoaded", function () {

        
    let map = L.map('map').setView([54.9779413181625, -1.6077514408103724], 17);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 22  
    }).addTo(map);
    
    //Marker Icons
    const treeIcon = L.icon({ iconUrl: 'assets/images/tree.png', iconSize: [25, 25], iconAnchor: [12, 25] });
    const benchIcon = L.icon({ iconUrl: 'assets/images/bench.png', iconSize: [25, 25], iconAnchor: [12, 25] });
    const gameIcon = L.icon({ iconUrl: 'assets/images/game.gif', iconSize: [70, 70], iconAnchor: [12, 25] });
    
    // Live User Location (Blue Dot)
    let userMarker = L.circleMarker([0, 0], {
        radius: 8,
        color: 'blue',
        fillColor: 'blue',
        fillOpacity: 0.8
    }).addTo(map).bindPopup("Your Location");
    
            
    function updateUserLocation(position) {
        const userCoords = [position.coords.latitude, position.coords.longitude];
        userMarker.setLatLng(userCoords);
        map.setView(userCoords, 18); // Adjust the map to follow the user
    }
    
    //handle getting error in live location tracking
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updateUserLocation, error => {
            console.error("Error retrieving location: ", error);
        }, { enableHighAccuracy: true });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
    
    // Bench Markers
    const benchLocations = [
        [54.97836028477019, -1.6083819527299599],
        [54.97716484801091, -1.6070314558742291],
        [54.975606130192865, -1.6040014561096665],
        [54.977522795195654, -1.607445472976338]
    ];
    benchLocations.forEach(coords => L.marker(coords, { icon: benchIcon }).addTo(map).bindPopup("Bench Here"));
    
    // Tree Markers
    const treeLocations = [
        
        [54.977801011833876, -1.607917576212888],
        [54.97729424547089, -1.6081375943734502],
        [54.97908654262674, -1.6069799147778696]
    
    ];
    treeLocations.forEach(coords => L.marker(coords, { icon: treeIcon }).addTo(map).bindPopup("Tree Here"));
    
    // Gamification Points Markers
    const gameLocations = [
       
        [54.9797542905695, -1.6074849229494328],
        [54.978367938535726, -1.6079914078012691],
        [54.97747474994541, -1.607685744944228],
        [54.976015437039855, -1.6039203327616358]
        
        
    ];
    gameLocations.forEach(coords => L.marker(coords, { icon: gameIcon }).addTo(map).bindPopup("Game Spot!"));
    
    //Nature hotspost area markers
    const natureHotspots = [
  
    [[54.97823115422873, -1.6081568812437228], [54.97820575599305, -1.6081508462738061], [54.97826155512603, -1.607810876301841], [54.97839470308871, -1.607875249314284], [54.97837392279992, -1.607995948712615]],
    [[54.97854401300173, -1.6080261235691704], [54.97854863082505, -1.6079684560788565], [54.97867177258436, -1.6080334996435126], [54.97866138251303, -1.6080871438205486]],
    [[54.9779732991365, -1.6080342692307434], [54.97801593864021, -1.6078028462552865], [54.977767091058986, -1.6076652104855966], [54.97773982957322, -1.6076956608771045], [54.977699286816545, -1.6076761726265394], [54.977686704573365, -1.607780921973325], [54.97771186905575, -1.6079149036959581], [54.97797329913398, -1.6080354872463278]],
    [[54.977143179483754, -1.6071923310680196], [54.977150716363695, -1.6070347390411563], [54.97711114772818, -1.6069247529390747], [54.977032010340146, -1.6068607311781617], [54.97694627799379, -1.6068919212668118]],
    [[54.97709701606318, -1.6072941092520352], [54.97701505230813, -1.6071496498940774], [54.97692649358015, -1.6070429469592222], [54.97688032991046, -1.607041305375609], [54.97688692472367, -1.6072103884877644], [54.9769500464524, -1.6073335072587511], [54.97701316808192, -1.6073581310129486]],
    
   
    [[54.97688315625463, -1.6064651094842695], [54.97681438171436, -1.606511073825438], [54.97676633367798, -1.6065127154090513], [54.97672582254379, -1.6064454104809116], [54.97670509590119, -1.6063797471363856], [54.97679930782678, -1.6063108006246327], [54.976901998573865, -1.6062451372801063], [54.97691707242289, -1.6062911016212749], [54.97686714027632, -1.6064864500712406]],
    
 
    [[54.9763916316406, -1.6048870604381278], [54.97629750918465, -1.6044427673585195], [54.976088727676704, -1.6041058202578773], [54.97599802717521, -1.6042042207385956], [54.97608188236301, -1.6045352041737402], [54.97615718074974, -1.6045530951702343], [54.97612808820797, -1.6048482966123903], [54.97607332571921, -1.6048244419503979], [54.97586625438344, -1.6051643708837895], [54.97551833890887, -1.605320865232175], [54.97528307729205, -1.605320865232175], [54.975264625342255, -1.605541897716868], [54.975762825010975, -1.6053731092740116], [54.976198744650716, -1.605091795202584], [54.97638095304479, -1.60491496921483], [54.97635096944183, -1.6046698240954431]]
    ];
    
    natureHotspots.forEach(area => {
        L.polygon(area, {
    	  color: 'green',
    	  fillColor: 'lightgreen',
    	  fillOpacity: 0.5
        }).addTo(map).bindPopup("Nature Hotspot");
    });
    
    
    
    // Store visited markers to prevent repeated alerts
    let visitedMarkers = new Set();
    
    // Add Game Markers
    let gameMarkers = [];
    gameLocations.forEach((coords, index) => {
        let marker = L.marker(coords, { icon: gameIcon }).addTo(map).bindPopup("Game Spot!");
        gameMarkers.push({ marker, index });
    });
    
    // Function to calculate the distance between two location
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius of Earth in meters
        const dLat = (lat2 - lat1) * (Math.PI / 180);
        const dLon = (lon2 - lon1) * (Math.PI / 180);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in meters
    }
    
    //Play alert sound
    let sound = document.getElementById("alertSound");
    function playAlertSound() {
        sound.play().catch(error => {
            console.log("Playback prevented by browser:", error);
        });
    }

        
    function updateUserLocation(position) {
        const userCoords = [position.coords.latitude, position.coords.longitude];
        userMarker.setLatLng(userCoords);
        map.setView(userCoords, 18);
    
        // Check if user is within 20m of the game location
        gameMarkers.forEach(({ marker, index }) => {
            const markerCoords = marker.getLatLng();
            const distance = getDistance(userCoords[0], userCoords[1], markerCoords.lat, markerCoords.lng);
            
            if (distance <= 20 && !visitedMarkers.has(index)) { // Check if not already visited
                
                // Play sound with alert
                playAlertSound();
                 
                let gameModal = new bootstrap.Modal(document.getElementById('gameModal'), { backdrop: 'static' });
                gameModal.show();
                
                visitedMarkers.add(index); 
            }
        });
    }
    
    //handle getting error in live location tracking
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updateUserLocation, error => {
            console.error("Error retrieving location: ", error);
        }, { enableHighAccuracy: true });
    } else {
        alert("⚠️ Geolocation is not supported by this browser.");
    }
       
    });

</script>



<?php include("includes/menu_bar.php"); ?>
	
<?php include("includes/app_footer.php"); ?>
	
