<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_SESSION['email'])) {
        $user_email = $_SESSION['email'];

        $query = "SELECT users.id, users.firstName, users.lastName, users.email, users.phone, realtor.custom_id
                  FROM users
                  JOIN realtor ON users.id = realtor.user_id
                  WHERE users.email = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            header('Content-Type: application/json');
            echo json_encode($userData);
        } else {
            echo "User not found.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "User is not logged in.";
    }
}
