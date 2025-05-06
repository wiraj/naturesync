document.addEventListener("DOMContentLoaded", function () {
    // ✅ Gamification Points
    const gameLocations = [
            [54.976173, -1.610376],
        [54.97529691623826, -1.6040027078544983],
        [54.97624717986799, -1.6042277591133507],
        [54.97656316061688, -1.6057347988254533],
        [54.97698292680734, -1.6070248248260455],
        [54.97744650964757, -1.6078486732228032],
        [54.97756644065279, -1.6071293129544393],
        [54.97730351451931, -1.608921685466676],
        [54.9784013350568, -1.6081701750666173],
        [54.97889257695678, -1.607916992401111],
        [54.979194699272966, -1.6070167873674122],
        [54.979764343564256, -1.6074307209384529]
    ];

    // ✅ Store visited markers to prevent repeated alerts
    let visitedMarkers = new Set();

    // ✅ Function to calculate distance (Haversine formula)
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

    function updateUserLocation(position) {
        const userLat = position.coords.latitude;
        const userLon = position.coords.longitude;

        // ✅ Check if user is near any gamification point
        gameLocations.forEach((coords, index) => {
            if (!visitedMarkers.has(index)) { // Check if already alerted
                const distance = getDistance(userLat, userLon, coords[0], coords[1]);

                if (distance <= 10) { // ✅ Updated radius to 10 meters
                    alert("🎮 You are near a game location! Participate and earn points!");
                    visitedMarkers.add(index); // Mark this location as visited
                }
            }
        });
    }

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updateUserLocation, error => {
            console.error("Error retrieving location: ", error);
        }, { enableHighAccuracy: true });
    } else {
        alert("⚠️ Geolocation is not supported by this browser.");
    }
});
