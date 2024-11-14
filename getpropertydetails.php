<?php
function fetchPropertyDetails($propertyID)
{
    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM property WHERE PropertyID = ?");
    $stmt->bind_param("i", $propertyID);
    $stmt->execute();

    $result = $stmt->get_result();

    $propertyDetails = array();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $propertyDetails = array(
            'PropertyID' => $row['PropertyID'],
            'PropertyName' => $row['PropertyName'],
            'PropertyType' => $row['PropertyType'],
            'Location' => $row['Location'],
            'Size' => $row['Size'],
            'Price' => $row['Price'],
            'YearToBuild' => $row['YearToBuild'],
            'ContactRealtor' => $row['ContactRealtor'],
            'Status' => $row['Status'],
            'ImagePaths' => $row['ImagePaths']
        );
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'property' => $propertyDetails]);
}

if (isset($_GET['propertyID'])) {
    $propertyID = $_GET['propertyID'];
    fetchPropertyDetails($propertyID);
} else {

    fetchProperties();
}

