<?php
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
    echo "User not authenticated.";
    exit();
}

$addedBy = $_SESSION['id'];

$propertyName = $_POST['propertyName'];
$propertyType = $_POST['propertyType'];
$location = $_POST['propertyLocation'];
$size = $_POST['propertySize'];
$price = $_POST['propertyPrice'];
$yearToBuild = $_POST['propertyYear'];
$contactRealtor = $_POST['realtorContact'];
$status = $_POST['propertyStatus'];

$imagePaths = [];

if (isset($_FILES["propertyImages"]) && is_array($_FILES["propertyImages"]["tmp_name"])) {
    $targetDir = "uploads/";

    foreach ($_FILES["propertyImages"]["tmp_name"] as $key => $tmp_name) {
        $targetFile = $targetDir . basename($_FILES["propertyImages"]["name"][$key]);

        if ($_FILES["propertyImages"]["error"][$key] != UPLOAD_ERR_OK) {
            echo "Sorry, there was an error uploading your file. Error Code: " . $_FILES["propertyImages"]["error"][$key];
            exit();
        }

        if (move_uploaded_file($tmp_name, $targetFile)) {
            $imagePaths[] = $targetFile;
        } else {
            echo "Sorry, there was an error moving your file.";
            exit();
        }
    }
} else {
    echo "No file uploaded.";
    exit();
}

$imagePathsString = implode(',', $imagePaths);

$stmt = $conn->prepare("INSERT INTO property (
    PropertyName, PropertyType, Location, Size, Price, YearToBuild, ContactRealtor, Status, ImagePaths, AddedBy
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssi", $propertyName, $propertyType, $location, $size, $price, $yearToBuild, $contactRealtor, $status, $imagePathsString, $addedBy);

if ($stmt->execute()) {
    echo '<script>alert("Property added successfully. Image added successfully.");';
    echo 'window.location.href = "addproperty.html";</script>';
} else {
    echo "Error adding property: " . $stmt->error;
}

$stmt->close();
$conn->close();