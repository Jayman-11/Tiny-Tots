<?php
require '../include/Connection.php';

// Call the getDB function to obtain a database connection
$conn = getDB();

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Perform the database query to get preschool data with marker details
$query = "SELECT p.*, m.lat, m.lng, m.name as marker_name, m.address as marker_address
          FROM preschool p
          LEFT JOIN markers m ON p.ps_id = m.ps_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error fetching preschool data: ' . mysqli_error($conn));
}

// Fetch the result rows and convert to JSON
$preschoolData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $preschoolData[] = [
        'ps_id' => $row['ps_id'],
        'school_name' => $row['school_name'],
        'address' => $row['address'],
        'hours' => $row['hours'],
        'phone_number' => $row['phone_number'],
        'email' => $row['email'],
        'website' => $row['website'],
        'ratings' => $row['ratings'],
        'accreditation' => $row['accreditation'],
        'status' => $row['status'],
        'fees' => $row['fees'],
        'ps_zoom' => $row['ps_zoom'],
        'ps_profile' => $row['ps_profile'],
        'created_at' => $row['created_at'],
        'lat' => $row['lat'],
        'lng' => $row['lng'],
        'marker_name' => $row['marker_name'],
        'marker_address' => $row['marker_address'],
    ];
}

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($preschoolData);

mysqli_close($conn);
?>