<?php
function fetchProperties()
{
    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM property");

    $properties = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = array(
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
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($properties);
}

fetchProperties();


