let map, infoWindow, userPos;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
    });
    infoWindow = new google.maps.InfoWindow();

    // Mark user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userPos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                const userMarker = new google.maps.Marker({
                    position: userPos,
                    map: map,
                    title: "Your Location",
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                        scaledSize: new google.maps.Size(40, 40),
                    },
                });

                infoWindow.setPosition(userPos);
                infoWindow.setContent("Your Location");
                infoWindow.open(map, userMarker);

                map.setCenter(userPos);

                // Fetch preschool data from PHP script
                fetchPreschoolData();
            },
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
        );
    } else {
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function fetchPreschoolData() {
    // Fetch preschool data from PHP script
    fetch("get_preschool_data.php") // Replace with the actual path to your PHP script
        .then((response) => response.json())
        .then((data) => {
            data.forEach((preschool) => {
                const preschoolPos = {
                    lat: parseFloat(preschool.lat),
                    lng: parseFloat(preschool.lng),
                };

                createMarkerAndLine(
                    preschoolPos,
                    `Preschool ${preschool.ps_id}`,
                    preschool.school_name
                );
            });
        })
        .catch((error) =>
            console.error("Error fetching preschool data:", error)
        );
}

function createMarkerAndLine(position, title, content) {
    const marker = new google.maps.Marker({
        position: position,
        map: map,
        title: title,
    });

    const markerInfoWindow = new google.maps.InfoWindow({
        content: content,
    });

    marker.addListener("click", () => {
        markerInfoWindow.open(map, marker);
    });

    // Draw a line from user to the marker
    const lineToMarker = new google.maps.Polyline({
        path: [userPos, position],
        geodesic: true,
        strokeColor: getRandomColor(),
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    lineToMarker.setMap(map);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

// Function to generate a random color for lines
function getRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function getDistances() {
    // Fetch preschool data from PHP script
    fetch("get_preschool_data.php") // Replace with the actual path to your PHP script
        .then((response) => response.json())
        .then((data) => {
            // Locations
            var locations = data.map((preschool) => {
                return {
                    name: preschool.school_name,
                    lat: parseFloat(preschool.lat),
                    lng: parseFloat(preschool.lng),
                };
            });

            // Get current location
            getCurrentLocation(function (currentLocation) {
                var service = new google.maps.DistanceMatrixService();
                var preschoolList = document.getElementById("preschoolList");

                // Counter to keep track of completed requests
                var completedRequests = 0;

                // Callback function to process each response
                function processResponse(response, status, index) {
                    if (status == "OK") {
                        var distance =
                            response.rows[0].elements[0].distance.text;
                        var duration =
                            response.rows[0].elements[0].duration.text;

                        // Display the result inside the corresponding list item
                        var listItem = preschoolList.children[index];
                        listItem.innerHTML += ` (${distance}, ${duration})`;
                    } else {
                        console.error(
                            `Error for Preschool ${index + 1}: ${status}`
                        );
                    }

                    // Check if all requests are completed
                    completedRequests++;
                    if (completedRequests === locations.length) {
                        console.log("All requests completed.");
                    }
                }

                // Calculate distance and time for each location
                locations.forEach(function (location, index) {
                    var origin =
                        currentLocation.lat + "," + currentLocation.lng;
                    var destination = location.lat + "," + location.lng;

                    service.getDistanceMatrix(
                        {
                            origins: [origin],
                            destinations: [destination],
                            travelMode: "DRIVING",
                            unitSystem: google.maps.UnitSystem.METRIC,
                            avoidHighways: false,
                            avoidTolls: false,
                        },
                        function (response, status) {
                            processResponse(response, status, index);
                        }
                    );
                });
            });
        })
        .catch((error) =>
            console.error("Error fetching preschool data:", error)
        );
}

function getCurrentLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                callback(currentLocation);
            },
            function (error) {
                console.error("Error getting current location:", error.message);
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}

// Call getDistances when the page loads
window.onload = function () {
    getDistances();
};
