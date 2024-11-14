<?php
global $conn;
session_start();

function fetchAndUpdateUser()
{
    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_SESSION['id'])) {
        die("User not authenticated.");
    }

    $userId = $_SESSION['id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('success' => true, 'message' => 'User information updated successfully');
        } else {
            $response = array('success' => false, 'error' => 'Failed to update user information');
        }

        header('Content-Type: application/json');
        echo json_encode($response);

        $stmt->close();

    } else {

        $stmt = $conn->prepare("SELECT id, firstName, lastName, email, phone, userType, imagePath FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = null;
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }


        $response = array('user' => $user, 'profilePicture' => $user['imagePath']);

        header('Content-Type: application/json');
        echo json_encode($response);


        $stmt->close();
    }

    $conn->close();
}

fetchAndUpdateUser();

