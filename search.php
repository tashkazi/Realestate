<?php

$servername = "localhost:3306";
$username = "root";
$password = "Tashreeka94_";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$propertyType = $conn->real_escape_string($_GET['propertyType']);
$priceRange = $conn->real_escape_string($_GET['priceRange']);
$location = $conn->real_escape_string($_GET['location']);
$yearToBuild = $conn->real_escape_string($_GET['yearToBuild']);

$sql = "SELECT * FROM property WHERE 1=1";

if ($propertyType !== 'allproperties') {
    $sql .= " AND PropertyType = '$propertyType'";
}

$priceRangeParts = explode('-', $priceRange);
if (count($priceRangeParts) === 2) {
    $sql .= " AND Price BETWEEN $priceRangeParts[0] AND $priceRangeParts[1]";
}

if ($location !== 'allCities') {
    $sql .= " AND Location = '$location'";
}

$yearToBuildParts = explode('-', $yearToBuild);
if (count($yearToBuildParts) === 2) {
    $sql .= " AND YearToBuild BETWEEN $yearToBuildParts[0] AND $yearToBuildParts[1]";
}

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Error executing query: ' . $conn->error]);
    exit();
}

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}

echo json_encode($properties);

$conn->close();

