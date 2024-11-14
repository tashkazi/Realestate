<?php
global $stmt;
session_start();

$servername = "localhost:3306";
$username = "root";
$password = "Tashreeka94_";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not authenticated.']);
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$loggedInUserID = $_SESSION['id'];
$loggedInUserType = $_SESSION['userType'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProperty'])) {
    $propertyID = $_POST['propertyID'];
    $propertyName = $_POST['propertyName'];
    $propertyType = $_POST['propertyType'];
    $location = $_POST['location'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $yearToBuild = $_POST['yearToBuild'];
    $contactRealtor = $_POST['contactRealtor'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("SELECT AddedBy, RealtorID FROM property WHERE PropertyID = ?");
    $stmt->bind_param("i", $propertyID);
    $stmt->execute();
    $stmt->bind_result($addedBy, $realtorID);
    $stmt->fetch();
    $stmt->close();

    if (($loggedInUserType === 'admin') || ($loggedInUserType === 'realtor' && $loggedInUserID == $addedBy)) {

        $stmt = $conn->prepare("UPDATE property SET PropertyName=?, PropertyType=?, Location=?, Size=?, Price=?, YearToBuild=?, ContactRealtor=?, Status=?, SaleDate=NOW(), RealtorID=? WHERE PropertyID = ?");
        $stmt->bind_param("ssssssssii", $propertyName, $propertyType, $location, $size, $price, $yearToBuild, $contactRealtor, $status, $loggedInUserID, $propertyID);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update property: ' . $stmt->error]);
        }

        $stmt->close();
    } else {

        echo json_encode(['success' => false, 'error' => 'Unauthorized action!']);
    }

    $conn->close();
    exit;
}
