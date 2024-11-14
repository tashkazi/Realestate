<?php

header('Content-Type: application/json');

$servername = "localhost:3306";
$username = "root";
$password = "Tashreeka94_";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['propertyID'])) {
    $propertyID = $_POST['propertyID'];

    session_start();
    $loggedInUserID = $_SESSION['id'];
    $loggedInUserType = $_SESSION['userType'];

    $stmt = $conn->prepare("SELECT AddedBy FROM property WHERE PropertyID = ?");
    $stmt->bind_param("i", $propertyID);
    $stmt->execute();
    $stmt->bind_result($addedBy);
    $stmt->fetch();
    $stmt->close();

    if ($loggedInUserType === 'admin' || $loggedInUserID == $addedBy) {

        $stmt = $conn->prepare("DELETE FROM property WHERE PropertyID = ?");
        $stmt->bind_param("i", $propertyID);

        if ($stmt->execute()) {

            echo json_encode(['success' => true, 'message' => 'Property deleted successfully!']);
        } else {

            echo json_encode(['success' => false, 'error' => 'Error deleting property: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } else {

        echo json_encode(['success' => false, 'error' => 'Unauthorized action!']);
    }

    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'PropertyID parameter not set']);
    exit;
}



