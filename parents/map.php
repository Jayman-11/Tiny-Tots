<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Preschool Locator a</title>
    <link rel="stylesheet" href="../css/map.css" />
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZfHbnPIwY5R-7DuYixbwiq3FPYeKb9B0&libraries=places"> -->
    </script>

    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

</head>

<body>
    <div><?php require 'include/index-header.php'; ?></div>
    <div class="container-fluid">
        <div class="row">
            <!-- First Column - Branch List -->
            <div class="col-md-4">
                <h2>Preschools</h2>
                <ul id="preschoolList">
                    <?php  require '../include/Connection.php'; 

// Call the getDB function to obtain a database connection
$conn = getDB();

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
                    
// Perform the database query to get preschool data with marker details
$query = "SELECT p.*, m.lat, m.lng
FROM preschool p
LEFT JOIN markers m ON p.ps_id = m.ps_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error fetching preschool data: ' . mysqli_error($conn));
}

// Fetch the result rows and generate HTML
while ($row = mysqli_fetch_assoc($result)) {
    ?>
                    <li class="map-container">
                        <p><?= $row['school_name'] ?></p>
                        <p>Location: <?= $row['address'] ?></p>
                        <p>Phone: <?= $row['phone_number'] ?></p>
                        <p>Hours: <?= $row['hours'] ?></p>
                        <a href="PS-Details.php?ps_id=<?= $row['ps_id'] ?>">Learn More</a>
                        <!-- Marker details -->
                        <p hidden>Lat: <?= $row['lat'] ?></p>
                        <p hidden>Lng: <?= $row['lng'] ?></p>
                        <br>
                    </li>
                    <?php
}
?>
                </ul>
            </div>

            <!-- Second Column - Embedded Map -->
            <div class="col-md-8">
                <!-- Add your embedded map with the ID 'map' -->
                <div id="map"></div>


            </div>
        </div>
    </div>

    <!-- JavaScript code -->
    <script src="script.js"></script>

    <!-- Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZfHbnPIwY5R-7DuYixbwiq3FPYeKb9B0&callback=initMap"
        async defer>
    </script>

    <!-- Add Bootstrap JS and Popper.js (required for some Bootstrap components) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>